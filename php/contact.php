<?php
/**
 * Página de Contacto - Fitness360
 * 
 * Este archivo maneja el procesamiento del formulario de contacto,
 * validando los datos ingresados y simulando el envío de un correo electrónico.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Inicializar variables para el formulario de contacto
$name = $email = $subject = $message = "";  // Variables para almacenar los datos del formulario
$name_err = $email_err = $subject_err = $message_err = "";  // Variables para mensajes de error
$success_message = $error_message = "";  // Mensajes de éxito o error general

/**
 * Procesar datos del formulario cuando se envía mediante POST
 * Realiza validación de todos los campos y prepara el envío del correo
 */
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar el nombre (no puede estar vacío)
    if(empty(trim($_POST["name"]))) {
        $name_err = "Por favor ingrese su nombre.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validar el correo electrónico (formato válido)
    if(empty(trim($_POST["email"]))) {
        $email_err = "Por favor ingrese su correo electrónico.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        // Usar filter_var para validar el formato del correo
        $email_err = "Por favor ingrese un correo electrónico válido.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validar el asunto (no puede estar vacío)
    if(empty(trim($_POST["subject"]))) {
        $subject_err = "Por favor ingrese el asunto.";
    } else {
        $subject = trim($_POST["subject"]);
    }

    // Validar el mensaje (no puede estar vacío)
    if(empty(trim($_POST["message"]))) {
        $message_err = "Por favor ingrese su mensaje.";
    } else {
        $message = trim($_POST["message"]);
    }

    // Verificar que no haya errores antes de procesar el envío
    if(empty($name_err) && empty($email_err) && empty($subject_err) && empty($message_err)) {
        // Nota: En un entorno de producción, aquí se enviaría un correo electrónico real
        // Para este ejemplo, solo simulamos un envío exitoso

        // Preparar el contenido del correo electrónico
        $email_to = "info@fitness360.com"; // Dirección de correo del destinatario
        $email_subject = "Nuevo mensaje de contacto: " . $subject;

        // Cuerpo del mensaje con todos los datos del formulario
        $email_body = "Has recibido un nuevo mensaje desde el formulario de contacto de Fitness360.\n\n".
                      "Detalles:\n\nNombre: " . $name . "\n".
                      "Email: " . $email . "\n".
                      "Asunto: " . $subject . "\n".
                      "Mensaje: " . $message . "\n";

        // Cabeceras del correo
        $headers = "From: " . $email . "\r\n" .
                   "Reply-To: " . $email . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // En producción, descomentar esta línea para enviar el correo
        // mail($email_to, $email_subject, $email_body, $headers);

        // Establecer mensaje de éxito para mostrar al usuario
        $success_message = "¡Mensaje enviado con éxito! Gracias por contactarnos.";

        // Limpiar los datos del formulario después del envío exitoso
        $name = $email = $subject = $message = "";
    } else {
        // Si hay errores, mostrar mensaje general
        $error_message = "Por favor corrija los errores en el formulario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Fitness360</title>
    <!-- Bootstrap 5 CSS - Framework CSS para el diseño responsive y componentes de interfaz -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-contacto">
    <!-- Navbar - Componente Bootstrap para la barra de navegación responsive -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="../index.html">Fitness360</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <li class="nav-item">
                        <a class="nav-link" href="../index.html#testimonios">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../index.html#contacto">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success ms-lg-3 text-white fw-medium shadow-sm" href="login.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Respuesta del Formulario de Contacto -->
    <div class="container contenedor-contacto shadow-sm rounded-3">
        <div class="cabecera-contacto text-center mb-4">
            <h1 class="fw-bold text-success">Contacto</h1>
            <p class="lead">Gracias por ponerte en contacto con Fitness360</p>
        </div>

        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
            </div>
            <div class="text-center mt-4">
                <a href="../index.html" class="btn btn-success py-2 fw-medium shadow-sm">Volver a la página principal</a>
            </div>
        <?php elseif(!empty($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
            </div>
            <!-- Formulario de contacto - Componentes Bootstrap para formulario con validación -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-4">
                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control rounded-3 py-2 <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                    <div class="invalid-feedback"><?php echo $name_err; ?></div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control rounded-3 py-2 <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <div class="invalid-feedback"><?php echo $email_err; ?></div>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label fw-medium">Asunto</label>
                    <input type="text" name="subject" id="subject" class="form-control rounded-3 py-2 <?php echo (!empty($subject_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subject; ?>">
                    <div class="invalid-feedback"><?php echo $subject_err; ?></div>
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label fw-medium">Mensaje</label>
                    <textarea name="message" id="message" rows="5" class="form-control rounded-3 py-2 <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo $message; ?></textarea>
                    <div class="invalid-feedback"><?php echo $message_err; ?></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success py-2 fw-medium shadow-sm">Enviar Mensaje</button>
                    <a href="../index.html" class="btn btn-outline-success rounded-3 shadow-sm">Volver a la página principal</a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No se ha enviado ningún formulario. Por favor, utilice el formulario de contacto en la <a href="../index.html#contacto" class="text-success fw-medium">página principal</a>.
            </div>
            <div class="text-center mt-4">
                <a href="../index.html" class="btn btn-success py-2 fw-medium shadow-sm">Volver a la página principal</a>
            </div>
        <?php endif; ?>
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

    <!-- Bootstrap 5 JS Bundle with Popper - Biblioteca JavaScript para funcionalidades interactivas de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
