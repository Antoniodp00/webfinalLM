<?php
/**
 * Panel de Empleado - Fitness360
 * 
 * Este archivo maneja la visualización del panel de control del empleado,
 * mostrando su información personal y funcionalidades específicas para empleados.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Iniciar sesión para manejar la autenticación del usuario
session_start();

// Verificar si el usuario ha iniciado sesión como empleado, si no, redirigir a la página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "empleado") {
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

// Inicializar variables para almacenar datos del empleado
$employee_info = [];  // Información personal del empleado
$error_message = "";  // Mensaje de error si ocurre algún problema

// Obtener información del empleado desde la base de datos
if(connectToDatabase()) {
    try {
        // Consulta para obtener información personal del empleado
        $sql = "SELECT * FROM Empleado WHERE idEmpleado = :id";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $employee_info = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "No se pudo obtener la información del empleado.";
        }

    } catch(PDOException $e) {
        // Capturar cualquier error de base de datos
        $error_message = "Error: " . $e->getMessage();
    }

    // Cerrar conexión a la base de datos
    unset($db_connection);
} else {
    $error_message = "Error de conexión a la base de datos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empleado - Fitness360</title>
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
                                <li><a class="dropdown-item" href="employee_dashboard.php">Mi Panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Dashboard Content -->
    <div class="container contenedor-panel shadow-sm rounded-3">
        <div class="cabecera-panel text-center mb-4">
            <h1 class="fw-bold text-success">Bienvenido, <?php echo htmlspecialchars($employee_info["nombre"] ?? $_SESSION["username"]); ?></h1>
            <p class="lead">Panel de control para empleados de Fitness360.</p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Employee Profile -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="tarjeta-panel">
                    <h3 class="fw-bold text-success mb-3">Información Personal</h3>
                    <div class="profile-info">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($employee_info["nombre"] ?? "N/A"); ?> <?php echo htmlspecialchars($employee_info["apellidos"] ?? ""); ?></p>
                        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($employee_info["nombreUsuario"] ?? "N/A"); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($employee_info["correo"] ?? "N/A"); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($employee_info["telefono"] ?? "N/A"); ?></p>
                        <p><strong>Rol:</strong> <?php echo htmlspecialchars($employee_info["rol"] ?? "N/A"); ?></p>
                        <p><strong>Especialidad:</strong> 
                            <?php 
                                $especialidad = $employee_info["especialidad"] ?? "AMBOS";
                                switch($especialidad) {
                                    case "ENTRENADOR": echo "Entrenador"; break;
                                    case "DIETISTA": echo "Dietista"; break;
                                    case "AMBOS": echo "Entrenador y Dietista"; break;
                                    default: echo "No especificado";
                                }
                            ?>
                        </p>
                        <p><strong>Estado:</strong> 
                            <?php 
                                $estado = $employee_info["estado"] ?? "ACTIVO";
                                switch($estado) {
                                    case "ACTIVO": echo '<span class="badge bg-success">Activo</span>'; break;
                                    case "INACTIVO": echo '<span class="badge bg-warning">Inactivo</span>'; break;
                                    case "SUSPENDIDO": echo '<span class="badge bg-danger">Suspendido</span>'; break;
                                    default: echo '<span class="badge bg-secondary">Desconocido</span>';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <h3 class="fw-bold text-success mb-4 position-relative pb-2">Funcionalidades Disponibles</h3>
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-users text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Gestión de Clientes</h4>
                        </div>
                        <p class="card-text mb-3">Administra la información de tus clientes, revisa su progreso y asigna nuevos planes.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-dumbbell text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Rutinas de Entrenamiento</h4>
                        </div>
                        <p class="card-text mb-3">Crea y gestiona rutinas de entrenamiento personalizadas para tus clientes.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-utensils text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Planes Nutricionales</h4>
                        </div>
                        <p class="card-text mb-3">Diseña planes de alimentación adaptados a las necesidades de cada cliente.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-clipboard-list text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Revisiones</h4>
                        </div>
                        <p class="card-text mb-3">Registra y consulta las revisiones periódicas de tus clientes para seguir su evolución.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-calendar-alt text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Calendario</h4>
                        </div>
                        <p class="card-text mb-3">Organiza tus citas y sesiones con los clientes de manera eficiente.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-chart-line text-success fa-3x mb-3"></i>
                            <h4 class="card-title fw-bold text-success mb-3">Estadísticas</h4>
                        </div>
                        <p class="card-text mb-3">Analiza el rendimiento y progreso de tus clientes con gráficos detallados.</p>
                        <div class="mt-auto text-center">
                            <a href="#" class="btn btn-success py-2 fw-medium shadow-sm" onclick="showComingSoon()">Acceder</a>
                        </div>
                    </div>
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
