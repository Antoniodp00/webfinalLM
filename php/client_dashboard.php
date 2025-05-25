<?php
/**
 * Panel de Cliente - Fitness360
 * 
 * Este archivo maneja la visualización del panel de control del cliente,
 * mostrando su información personal, revisiones, dietas y rutinas asignadas.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Iniciar sesión para manejar la autenticación del usuario
session_start();

// Verificar si el usuario ha iniciado sesión como cliente, si no, redirigir a la página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "cliente") {
    header("location: login.php");
    exit;
}

// Variable para la conexión a la base de datos
$db_connection = null;

/**
 * Función para establecer conexión con la base de datos
 * 
 * @return boolean Verdadero si la conexión fue exitosa, falso en caso contrario
 */
function connectToDatabase() {
    global $db_connection;

    // Incluir archivo de configuración de la base de datos
    require_once '../database/config_mysql.php';

    try {
        // Crear conexión PDO
        $db_connection = new PDO(DB_HOST . ";" . DB_NOMBRE, DB_USUARIO, DB_CONTRA);
        $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db_connection->exec("SET NAMES " . DB_CHARSET);
        return true;
    } catch(PDOException $e) {
        $error_message = "Error de conexión: " . $e->getMessage();
        return false;
    }
}

// Inicializar variables para almacenar datos del cliente
$client_info = [];  // Información personal del cliente
$revisiones = [];   // Revisiones físicas del cliente
$dietas = [];       // Dietas asignadas al cliente
$rutinas = [];      // Rutinas de ejercicio asignadas al cliente
$error_message = ""; // Mensaje de error si ocurre algún problema

// Obtener toda la información del cliente desde la base de datos
if(connectToDatabase()) {
    try {
        // Consulta para obtener información personal del cliente
        $sql = "SELECT * FROM Cliente WHERE idCliente = :id";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $client_info = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "No se pudo obtener la información del cliente.";
        }

        // Consulta para obtener las revisiones físicas del cliente
        $sql = "SELECT r.*, e.nombre as empleado_nombre, e.apellidos as empleado_apellidos 
                FROM Revision r 
                LEFT JOIN Empleado e ON r.idEmpleado = e.idEmpleado 
                WHERE r.idCliente = :id 
                ORDER BY r.fecha DESC";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        $revisiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para obtener las dietas asignadas al cliente
        $sql = "SELECT d.*, cd.fechaAsignacion, cd.fechaFin, e.nombre as empleado_nombre, e.apellidos as empleado_apellidos 
                FROM Dieta d 
                JOIN ClienteDieta cd ON d.idDieta = cd.idDieta 
                LEFT JOIN Empleado e ON d.idEmpleado = e.idEmpleado 
                WHERE cd.idCliente = :id 
                ORDER BY cd.fechaAsignacion DESC";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        $dietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Consulta para obtener las rutinas de ejercicio asignadas al cliente
        $sql = "SELECT r.*, ur.fechaAsignacion, ur.fechaFin, e.nombre as empleado_nombre, e.apellidos as empleado_apellidos 
                FROM Rutina r 
                JOIN UsuarioRutina ur ON r.idRutina = ur.idRutina 
                LEFT JOIN Empleado e ON r.idEmpleado = e.idEmpleado 
                WHERE ur.idUsuario = :id 
                ORDER BY ur.fechaAsignacion DESC";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        $rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        // Capturar cualquier error de base de datos
        $error_message = "Error: " . $e->getMessage();
    }

    // Cerrar conexión a la base de datos
    unset($db_connection);
} else {
    $error_message = "Error de conexión a la base de datos.";
}

/**
 * Función para formatear fechas al formato español (dd/mm/yyyy)
 * 
 * @param string $date Fecha en formato SQL
 * @return string Fecha formateada o "N/A" si es nula
 */
function formatDate($date) {
    if(!$date) return "N/A";
    return date("d/m/Y", strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente - Fitness360</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-php">
    <!-- Navbar -->
    <header id="header">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-success" href="../index.html">
                    Fitness360
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.html">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.html#servicios">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../index.html#caracteristicas">Características</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="client_dashboard.php">Mi Panel</a></li>
                                <li><a class="dropdown-item" href="edit_profile.php">Editar Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido del Panel -->
    <div class="container contenedor-panel shadow-sm rounded-3">
        <div class="cabecera-panel text-center mb-4">
            <h1 class="fw-bold text-success">Bienvenido, <?php echo htmlspecialchars($client_info["nombre"] ?? $_SESSION["username"]); ?></h1>
            <p class="lead">Aquí puedes ver toda tu información y seguimiento.</p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="pestanasPanelControl" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Mi Perfil</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Mis Revisiones</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="diets-tab" data-bs-toggle="tab" data-bs-target="#diets" type="button" role="tab" aria-controls="diets" aria-selected="false">Mis Dietas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="routines-tab" data-bs-toggle="tab" data-bs-target="#routines" type="button" role="tab" aria-controls="routines" aria-selected="false">Mis Rutinas</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="contenidoPestanasPanelControl">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="tarjeta-panel">
                    <h3>Información Personal</h3>
                    <div class="info-perfil">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($client_info["nombre"] ?? "N/A"); ?> <?php echo htmlspecialchars($client_info["apellidos"] ?? ""); ?></p>
                        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($client_info["nombreUsuario"] ?? "N/A"); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($client_info["correo"] ?? "N/A"); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($client_info["telefono"] ?? "N/A"); ?></p>
                        <p><strong>Fecha de Nacimiento:</strong> <?php echo formatDate($client_info["fechaNacimiento"] ?? null); ?></p>
                        <p><strong>Sexo:</strong> 
                            <?php 
                                $sexo = $client_info["sexo"] ?? "NS";
                                switch($sexo) {
                                    case "M": echo "Masculino"; break;
                                    case "F": echo "Femenino"; break;
                                    case "O": echo "Otro"; break;
                                    default: echo "No especificado";
                                }
                            ?>
                        </p>
                        <p><strong>Altura:</strong> <?php echo htmlspecialchars($client_info["altura"] ?? "N/A"); ?> cm</p>
                        <p><strong>Estado:</strong> 
                            <?php 
                                $estado = $client_info["estado"] ?? "ACTIVO";
                                switch($estado) {
                                    case "ACTIVO": echo '<span class="badge bg-success">Activo</span>'; break;
                                    case "INACTIVO": echo '<span class="badge bg-warning">Inactivo</span>'; break;
                                    case "SUSPENDIDO": echo '<span class="badge bg-danger">Suspendido</span>'; break;
                                    default: echo '<span class="badge bg-secondary">Desconocido</span>';
                                }
                            ?>
                        </p>
                    </div>
                    <a href="edit_profile.php" class="btn btn-primary">Editar Perfil</a>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="tarjeta-panel">
                    <h3>Mis Revisiones</h3>

                    <?php if(empty($revisiones)): ?>
                        <div class="alert alert-info">No tienes revisiones registradas.</div>
                    <?php else: ?>
                        <?php foreach($revisiones as $revision): ?>
                            <div class="elemento-revision">
                                <div class="fecha-revision">
                                    <i class="fas fa-calendar-alt"></i> <?php echo formatDate($revision["fecha"]); ?>
                                    <?php if(!empty($revision["empleado_nombre"])): ?>
                                        <span class="text-muted">- Realizada por: <?php echo htmlspecialchars($revision["empleado_nombre"] . " " . $revision["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="metricas-revision">
                                    <div class="metrica-revision">
                                        <strong>Peso:</strong> <?php echo htmlspecialchars($revision["peso"] ?? "N/A"); ?> kg
                                    </div>
                                    <div class="metrica-revision">
                                        <strong>Grasa:</strong> <?php echo htmlspecialchars($revision["grasa"] ?? "N/A"); ?>%
                                    </div>
                                    <div class="metrica-revision">
                                        <strong>Músculo:</strong> <?php echo htmlspecialchars($revision["musculo"] ?? "N/A"); ?>%
                                    </div>
                                    <div class="metrica-revision">
                                        <strong>Pecho:</strong> <?php echo htmlspecialchars($revision["mPecho"] ?? "N/A"); ?> cm
                                    </div>
                                    <div class="metrica-revision">
                                        <strong>Cintura:</strong> <?php echo htmlspecialchars($revision["mCintura"] ?? "N/A"); ?> cm
                                    </div>
                                    <div class="metrica-revision">
                                        <strong>Cadera:</strong> <?php echo htmlspecialchars($revision["mCadera"] ?? "N/A"); ?> cm
                                    </div>
                                </div>
                                <?php if(!empty($revision["observaciones"])): ?>
                                    <div class="observaciones-revision">
                                        <strong>Observaciones:</strong>
                                        <p><?php echo nl2br(htmlspecialchars($revision["observaciones"])); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($revision["imagen"])): ?>
                                    <div class="imagen-revision mt-2">
                                        <img src="<?php echo htmlspecialchars($revision["imagen"]); ?>" alt="Imagen de revisión" class="img-fluid">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Diets Tab -->
            <div class="tab-pane fade" id="diets" role="tabpanel" aria-labelledby="diets-tab">
                <div class="tarjeta-panel">
                    <h3>Mis Dietas</h3>

                    <?php if(empty($dietas)): ?>
                        <div class="alert alert-info">No tienes dietas asignadas.</div>
                    <?php else: ?>
                        <?php foreach($dietas as $dieta): ?>
                            <div class="elemento-dieta">
                                <div class="nombre-dieta">
                                    <i class="fas fa-utensils"></i> <?php echo htmlspecialchars($dieta["nombre"]); ?>
                                </div>
                                <div class="fechas-dieta">
                                    <strong>Asignada:</strong> <?php echo formatDate($dieta["fechaAsignacion"]); ?>
                                    <?php if(!empty($dieta["fechaFin"])): ?>
                                        <strong>Hasta:</strong> <?php echo formatDate($dieta["fechaFin"]); ?>
                                    <?php endif; ?>
                                    <?php if(!empty($dieta["empleado_nombre"])): ?>
                                        <span>- Por: <?php echo htmlspecialchars($dieta["empleado_nombre"] . " " . $dieta["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($dieta["descripcion"])): ?>
                                    <div class="descripcion-dieta">
                                        <p><?php echo nl2br(htmlspecialchars($dieta["descripcion"])); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($dieta["archivo"])): ?>
                                    <div class="archivo-dieta mt-2">
                                        <a href="<?php echo htmlspecialchars($dieta["archivo"]); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-file-download"></i> Descargar Plan
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Routines Tab -->
            <div class="tab-pane fade" id="routines" role="tabpanel" aria-labelledby="routines-tab">
                <div class="tarjeta-panel">
                    <h3>Mis Rutinas</h3>

                    <?php if(empty($rutinas)): ?>
                        <div class="alert alert-info">No tienes rutinas asignadas.</div>
                    <?php else: ?>
                        <?php foreach($rutinas as $rutina): ?>
                            <div class="elemento-rutina">
                                <div class="nombre-rutina">
                                    <i class="fas fa-dumbbell"></i> <?php echo htmlspecialchars($rutina["nombre"]); ?>
                                </div>
                                <div class="fechas-rutina">
                                    <strong>Asignada:</strong> <?php echo formatDate($rutina["fechaAsignacion"]); ?>
                                    <?php if(!empty($rutina["fechaFin"])): ?>
                                        <strong>Hasta:</strong> <?php echo formatDate($rutina["fechaFin"]); ?>
                                    <?php endif; ?>
                                    <?php if(!empty($rutina["empleado_nombre"])): ?>
                                        <span>- Por: <?php echo htmlspecialchars($rutina["empleado_nombre"] . " " . $rutina["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($rutina["descripcion"])): ?>
                                    <div class="descripcion-rutina">
                                        <p><?php echo nl2br(htmlspecialchars($rutina["descripcion"])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white pt-5">
        <div class="bg-white py-5 border-top border-bottom border-light">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <h3 class="fs-4 fw-bold text-success mb-4">Fitness360</h3>
                        <p class="mb-3">
                            Calle Ejemplo 123 <br>
                            28001 Madrid<br>
                            España <br><br>
                            <strong>Teléfono:</strong> +34 912 345 678<br>
                            <strong>Email:</strong> info@fitness360.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4 class="fs-5 fw-bold text-success mb-4 position-relative pb-2">Enlaces Útiles</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="../index.html" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Inicio</a></li>
                            <li class="mb-2"><a href="../index.html#servicios" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Servicios</a></li>
                            <li class="mb-2"><a href="../index.html#caracteristicas" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Características</a></li>
                            <li class="mb-2"><a href="../index.html#testimonios" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Testimonios</a></li>
                            <li class="mb-2"><a href="../index.html#contacto" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Contacto</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4 class="fs-5 fw-bold text-success mb-4 position-relative pb-2">Nuestros Servicios</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Entrenamiento Personal</a></li>
                            <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Planes Nutricionales</a></li>
                            <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Seguimiento de Progreso</a></li>
                            <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Asesoramiento Deportivo</a></li>
                            <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Comunidad Fitness</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4 class="fs-5 fw-bold text-success mb-4 position-relative pb-2">Nuestras Redes Sociales</h4>
                        <p>Síguenos en nuestras redes sociales para estar al día de todas nuestras novedades y consejos fitness.</p>
                        <div class="d-flex gap-2 mt-3">
                            <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-success py-4 text-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <div>
                            &copy; Copyright <strong><span class="text-light">Fitness360</span></strong>. Todos los derechos reservados
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div>
                            Diseñado por <a href="#" class="text-light fw-medium">Fitness360 Team</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botón volver arriba -->
    <a href="#" class="volver-arriba position-fixed bottom-0 end-0 m-4 bg-success rounded-circle d-flex align-items-center justify-content-center shadow text-white" style="width: 50px; height: 50px; z-index: 999;"><i class="fas fa-arrow-up"></i></a>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
</body>
</html>
