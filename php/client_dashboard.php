<?php
// Start session
session_start();

// Check if the user is logged in, if not redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "cliente") {
    header("location: login.php");
    exit;
}

// Database connection
$db_connection = null;

// Function to connect to the database
function connectToDatabase() {
    global $db_connection;

    // Include database configuration
    require_once '../database/config_mysql.php';

    try {
        $db_connection = new PDO(DB_HOST . ";" . DB_NOMBRE, DB_USUARIO, DB_CONTRA);
        $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db_connection->exec("SET NAMES " . DB_CHARSET);
        return true;
    } catch(PDOException $e) {
        $error_message = "Error de conexión: " . $e->getMessage();
        return false;
    }
}

// Initialize variables
$client_info = [];
$revisiones = [];
$dietas = [];
$rutinas = [];
$error_message = "";

// Get client information
if(connectToDatabase()) {
    try {
        // Get client information
        $sql = "SELECT * FROM Cliente WHERE idCliente = :id";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $client_info = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "No se pudo obtener la información del cliente.";
        }

        // Get client reviews
        $sql = "SELECT r.*, e.nombre as empleado_nombre, e.apellidos as empleado_apellidos 
                FROM Revision r 
                LEFT JOIN Empleado e ON r.idEmpleado = e.idEmpleado 
                WHERE r.idCliente = :id 
                ORDER BY r.fecha DESC";
        $stmt = $db_connection->prepare($sql);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();

        $revisiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get client diets
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

        // Get client routines
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
        $error_message = "Error: " . $e->getMessage();
    }

    // Close connection
    unset($db_connection);
} else {
    $error_message = "Error de conexión a la base de datos.";
}

// Function to format date
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
    <style>
        body {
            background-color: var(--gray-light);
            padding-top: 70px;
        }
        .dashboard-container {
            padding: 20px;
        }
        .dashboard-header {
            margin-bottom: 30px;
        }
        .dashboard-header h1 {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 10px;
        }
        .dashboard-card {
            background: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 30px;
            transition: var(--transition);
        }
        .dashboard-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .dashboard-card h3 {
            color: var(--primary-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-medium);
            font-weight: 600;
            position: relative;
        }
        .dashboard-card h3::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 5px;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .profile-info p {
            margin-bottom: 12px;
        }
        .profile-info strong {
            display: inline-block;
            width: 150px;
            color: var(--primary-dark);
        }
        .review-item {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--gray-medium);
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .review-date {
            font-weight: 600;
            color: var(--primary-dark);
        }
        .review-metrics {
            display: flex;
            flex-wrap: wrap;
            margin: 15px 0;
        }
        .review-metric {
            background: rgba(76, 175, 80, 0.1);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-right: 12px;
            margin-bottom: 12px;
            transition: var(--transition);
        }
        .review-metric:hover {
            background: rgba(76, 175, 80, 0.2);
            transform: translateY(-2px);
        }
        .review-metric strong {
            color: var(--primary-dark);
        }
        .review-observations {
            margin-top: 15px;
        }
        .diet-item, .routine-item {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--gray-medium);
        }
        .diet-item:last-child, .routine-item:last-child {
            border-bottom: none;
        }
        .diet-name, .routine-name {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 18px;
        }
        .diet-dates, .routine-dates {
            font-size: 0.9em;
            color: var(--secondary-color);
            margin: 8px 0;
        }
        .diet-description, .routine-description {
            margin-top: 12px;
        }
        .nav-tabs {
            border-bottom: 1px solid var(--gray-medium);
            margin-bottom: 25px;
        }
        .nav-tabs .nav-link {
            color: var(--dark-color);
            border: none;
            padding: 12px 20px;
            margin-right: 5px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            transition: var(--transition);
        }
        .nav-tabs .nav-link:hover {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
            background-color: rgba(76, 175, 80, 0.1);
            border-bottom: 3px solid var(--primary-color);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 10px 20px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
        .badge.bg-success {
            background-color: var(--success-color) !important;
        }
        .badge.bg-warning {
            background-color: var(--warning-color) !important;
        }
        .badge.bg-danger {
            background-color: var(--danger-color) !important;
        }
        @media (max-width: 768px) {
            .profile-info strong {
                width: 120px;
            }
            .review-metrics {
                flex-direction: column;
            }
            .review-metric {
                margin-right: 0;
            }
            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header id="header">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="../index.html">
                    <strong>Fitness360</strong>
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

    <!-- Dashboard Content -->
    <div class="container dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($client_info["nombre"] ?? $_SESSION["username"]); ?></h1>
            <p>Aquí puedes ver toda tu información y seguimiento.</p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
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
        <div class="tab-content" id="dashboardTabsContent">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="dashboard-card">
                    <h3>Información Personal</h3>
                    <div class="profile-info">
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
                <div class="dashboard-card">
                    <h3>Mis Revisiones</h3>

                    <?php if(empty($revisiones)): ?>
                        <div class="alert alert-info">No tienes revisiones registradas.</div>
                    <?php else: ?>
                        <?php foreach($revisiones as $revision): ?>
                            <div class="review-item">
                                <div class="review-date">
                                    <i class="fas fa-calendar-alt"></i> <?php echo formatDate($revision["fecha"]); ?>
                                    <?php if(!empty($revision["empleado_nombre"])): ?>
                                        <span class="text-muted">- Realizada por: <?php echo htmlspecialchars($revision["empleado_nombre"] . " " . $revision["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="review-metrics">
                                    <div class="review-metric">
                                        <strong>Peso:</strong> <?php echo htmlspecialchars($revision["peso"] ?? "N/A"); ?> kg
                                    </div>
                                    <div class="review-metric">
                                        <strong>Grasa:</strong> <?php echo htmlspecialchars($revision["grasa"] ?? "N/A"); ?>%
                                    </div>
                                    <div class="review-metric">
                                        <strong>Músculo:</strong> <?php echo htmlspecialchars($revision["musculo"] ?? "N/A"); ?>%
                                    </div>
                                    <div class="review-metric">
                                        <strong>Pecho:</strong> <?php echo htmlspecialchars($revision["mPecho"] ?? "N/A"); ?> cm
                                    </div>
                                    <div class="review-metric">
                                        <strong>Cintura:</strong> <?php echo htmlspecialchars($revision["mCintura"] ?? "N/A"); ?> cm
                                    </div>
                                    <div class="review-metric">
                                        <strong>Cadera:</strong> <?php echo htmlspecialchars($revision["mCadera"] ?? "N/A"); ?> cm
                                    </div>
                                </div>
                                <?php if(!empty($revision["observaciones"])): ?>
                                    <div class="review-observations">
                                        <strong>Observaciones:</strong>
                                        <p><?php echo nl2br(htmlspecialchars($revision["observaciones"])); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($revision["imagen"])): ?>
                                    <div class="review-image mt-2">
                                        <img src="<?php echo htmlspecialchars($revision["imagen"]); ?>" alt="Imagen de revisión" class="img-fluid" style="max-height: 200px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Diets Tab -->
            <div class="tab-pane fade" id="diets" role="tabpanel" aria-labelledby="diets-tab">
                <div class="dashboard-card">
                    <h3>Mis Dietas</h3>

                    <?php if(empty($dietas)): ?>
                        <div class="alert alert-info">No tienes dietas asignadas.</div>
                    <?php else: ?>
                        <?php foreach($dietas as $dieta): ?>
                            <div class="diet-item">
                                <div class="diet-name">
                                    <i class="fas fa-utensils"></i> <?php echo htmlspecialchars($dieta["nombre"]); ?>
                                </div>
                                <div class="diet-dates">
                                    <strong>Asignada:</strong> <?php echo formatDate($dieta["fechaAsignacion"]); ?>
                                    <?php if(!empty($dieta["fechaFin"])): ?>
                                        <strong>Hasta:</strong> <?php echo formatDate($dieta["fechaFin"]); ?>
                                    <?php endif; ?>
                                    <?php if(!empty($dieta["empleado_nombre"])): ?>
                                        <span>- Por: <?php echo htmlspecialchars($dieta["empleado_nombre"] . " " . $dieta["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($dieta["descripcion"])): ?>
                                    <div class="diet-description">
                                        <p><?php echo nl2br(htmlspecialchars($dieta["descripcion"])); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($dieta["archivo"])): ?>
                                    <div class="diet-file mt-2">
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
                <div class="dashboard-card">
                    <h3>Mis Rutinas</h3>

                    <?php if(empty($rutinas)): ?>
                        <div class="alert alert-info">No tienes rutinas asignadas.</div>
                    <?php else: ?>
                        <?php foreach($rutinas as $rutina): ?>
                            <div class="routine-item">
                                <div class="routine-name">
                                    <i class="fas fa-dumbbell"></i> <?php echo htmlspecialchars($rutina["nombre"]); ?>
                                </div>
                                <div class="routine-dates">
                                    <strong>Asignada:</strong> <?php echo formatDate($rutina["fechaAsignacion"]); ?>
                                    <?php if(!empty($rutina["fechaFin"])): ?>
                                        <strong>Hasta:</strong> <?php echo formatDate($rutina["fechaFin"]); ?>
                                    <?php endif; ?>
                                    <?php if(!empty($rutina["empleado_nombre"])): ?>
                                        <span>- Por: <?php echo htmlspecialchars($rutina["empleado_nombre"] . " " . $rutina["empleado_apellidos"]); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($rutina["descripcion"])): ?>
                                    <div class="routine-description">
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
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h3>Fitness360</h3>
                        <p>
                            Calle Ejemplo 123 <br>
                            28001 Madrid<br>
                            España <br><br>
                            <strong>Teléfono:</strong> +34 912 345 678<br>
                            <strong>Email:</strong> info@fitness360.com<br>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Enlaces Útiles</h4>
                        <ul>
                            <li><i class="fas fa-chevron-right"></i> <a href="../index.html">Inicio</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="../index.html#servicios">Servicios</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="../index.html#caracteristicas">Características</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="../index.html#testimonios">Testimonios</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="../index.html#contacto">Contacto</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Nuestros Servicios</h4>
                        <ul>
                            <li><i class="fas fa-chevron-right"></i> <a href="#">Entrenamiento Personal</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="#">Planes Nutricionales</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="#">Seguimiento de Progreso</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="#">Asesoramiento Deportivo</a></li>
                            <li><i class="fas fa-chevron-right"></i> <a href="#">Comunidad Fitness</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Nuestras Redes Sociales</h4>
                        <p>Síguenos en nuestras redes sociales para estar al día de todas nuestras novedades y consejos fitness.</p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="youtube"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container footer-bottom clearfix">
            <div class="copyright">
                &copy; Copyright <strong><span>Fitness360</span></strong>. Todos los derechos reservados
            </div>
            <div class="credits">
                Diseñado por <a href="#">Fitness360 Team</a>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></a>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/main.js"></script>
    <script>
        // JavaScript for handling tab persistence
        document.addEventListener('DOMContentLoaded', function() {
            // Get the active tab from localStorage
            const activeTab = localStorage.getItem('activeClientTab');

            // If there is an active tab stored, activate it
            if (activeTab) {
                const tab = document.querySelector(`#dashboardTabs button[data-bs-target="${activeTab}"]`);
                if (tab) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }

            // Store the active tab when a tab is clicked
            const tabs = document.querySelectorAll('#dashboardTabs button');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    localStorage.setItem('activeClientTab', event.target.getAttribute('data-bs-target'));
                });
            });
        });
    </script>
</body>
</html>
