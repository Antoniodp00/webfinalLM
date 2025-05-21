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
<body class="dashboard-page">
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
    <div class="container dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($employee_info["nombre"] ?? $_SESSION["username"]); ?></h1>
            <p>Panel de control para empleados de Fitness360.</p>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Employee Profile -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="dashboard-card">
                    <h3>Información Personal</h3>
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
        <h3 class="mb-4">Funcionalidades Disponibles</h3>
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="feature-title">Gestión de Clientes</h4>
                    <p class="feature-description">Administra la información de tus clientes, revisa su progreso y asigna nuevos planes.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h4 class="feature-title">Rutinas de Entrenamiento</h4>
                    <p class="feature-description">Crea y gestiona rutinas de entrenamiento personalizadas para tus clientes.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4 class="feature-title">Planes Nutricionales</h4>
                    <p class="feature-description">Diseña planes de alimentación adaptados a las necesidades de cada cliente.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="feature-title">Revisiones</h4>
                    <p class="feature-description">Registra y consulta las revisiones periódicas de tus clientes para seguir su evolución.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4 class="feature-title">Calendario</h4>
                    <p class="feature-description">Organiza tus citas y sesiones con los clientes de manera eficiente.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="feature-title">Estadísticas</h4>
                    <p class="feature-description">Analiza el rendimiento y progreso de tus clientes con gráficos detallados.</p>
                    <a href="#" class="btn btn-primary" onclick="showComingSoon()">Acceder</a>
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
        // Function to show "Coming Soon" message
        function showComingSoon() {
            alert('Esta funcionalidad estará disponible próximamente.');
        }

        // Animation for feature cards
        document.addEventListener('DOMContentLoaded', function() {
            const featureCards = document.querySelectorAll('.feature-card');

            featureCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
