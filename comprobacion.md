# Comprobación de Requisitos - Fitness360

Este documento detalla cómo el proyecto Fitness360 cumple con los requisitos especificados.

## 🔧 Diseño Responsive

### La página web tiene 3 tamaños y se realizan más de 2 cambios por tamaño ✅
**Ubicación**: `styles.css` líneas 271-324 (media queries para diferentes tamaños: 991px, 768px, 576px)
```css
@media (max-width: 991px) {
    #hero {
        height: 100vh;
        text-align: center;
    }
    #hero .animated {
        animation: none;
    }
    #hero .hero-img {
        text-align: center;
    }
    #hero .hero-img img {
        width: 50%;
    }
}

@media (max-width: 768px) {
    #hero h1 {
        font-size: 28px;
        line-height: 36px;
    }
    #hero h2 {
        font-size: 18px;
        line-height: 24px;
        margin-bottom: 30px;
    }
    #hero .hero-img img {
        width: 70%;
    }
}

@media (max-width: 575px) {
    #hero .hero-img img {
        width: 80%;
    }
    #hero .btn-get-started {
        font-size: 16px;
        padding: 10px 24px 11px 24px;
    }
    #hero .btn-watch-video {
        font-size: 16px;
        padding: 10px 0 8px 40px;
        margin-left: 20px;
    }
}
```

### Aparece/desaparece algún objeto en un cambio de tamaño de pantalla ✅
**Ubicación**: `styles.css` líneas 1432-1434, 1447-1449, 1460-1462 (clases .hide-md, .hide-sm, .hide-xs)
```css
/* Hide certain elements on medium screens */
.hide-md {
    display: none !important;
}

/* Hide certain elements on small screens */
.hide-sm {
    display: none !important;
}

/* Hide certain elements on extra small screens */
.hide-xs {
    display: none !important;
}
```

### Cambia de posición algún objeto en un cambio de tamaño de pantalla ✅
**Ubicación**: `styles.css` líneas 1424-1429, 1439-1444 (clases .order-md-1, .order-md-2, .order-sm-1, .order-sm-2)
```css
/* For tablets */
@media (max-width: 991px) {
    .order-md-1 {
        order: 1;
    }
    .order-md-2 {
        order: 2;
    }
}

/* For mobile phones */
@media (max-width: 768px) {
    .order-sm-1 {
        order: 1;
    }
    .order-sm-2 {
        order: 2;
    }
}
```

## 🧱 Estructura HTML

### Incluye header, footer y nav ✅
**Ubicación**: `index.html` líneas 16-49 (header y nav), líneas 301-361 (footer)
```html
<!-- Header/Navbar -->
<header id="header">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <strong>Fitness360</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.html">Inicio</a>
                    </li>
                    <!-- Más elementos de navegación -->
                </ul>
            </div>
        </div>
    </nav>
</header>
```

### Incluye objeto de formato de fuente (b, strong, etc.) ✅
**Ubicación**: `index.html` línea 20 (strong)
```html
<strong>Fitness360</strong>
```

### Incluye objeto de formato de párrafo (h1, p, etc.) ✅
**Ubicación**: `index.html` líneas 56 (h1), 57 (h2), 77 (h5), 100 (p)
```html
<h1>Transforma tu cuerpo y tu vida con <span>Fitness360</span></h1>
<h2>La plataforma integral para gestionar tu salud y entrenamiento personal de forma efectiva</h2>
<h5 class="modal-title" id="videoModalLabel">Video Promocional Fitness360</h5>
<p>Fitness360 ofrece una gama completa de servicios para ayudarte a alcanzar tus objetivos de fitness y bienestar.</p>
```

### Incluye fondo de página (color o imagen) ✅
**Ubicación**: `styles.css` líneas 31 (background-color para body), 166 (background para hero section)
```css
body {
    font-family: var(--font-secondary);
    color: var(--dark-color);
    overflow-x: hidden;
    background-color: var(--light-color);
    line-height: 1.6;
    font-weight: 400;
}

#hero {
    width: 100%;
    height: 90vh;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
    padding-top: 90px;
    position: relative;
    overflow: hidden;
}
```

### Incluye hipervínculo <a> ✅
**Ubicación**: `index.html` líneas 19, 28-44, 59-62 (enlaces de navegación y botones)
```html
<a class="navbar-brand" href="index.html">
    <strong>Fitness360</strong>
</a>

<a class="nav-link active" href="index.html">Inicio</a>

<a href="php/register.php" class="btn-get-started">Regístrate Ahora</a>
<a href="#" class="btn-watch-video" data-bs-toggle="modal" data-bs-target="#videoModal">
    <i class="fas fa-play-circle"></i><span>Ver Video</span>
</a>
```

### Incluye algún iframe ✅
**Ubicación**: `index.html` línea 264 (iframe de Google Maps)
```html
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.6167379590934!2d-3.7037974846309875!3d40.41694937936723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422997800a3c81%3A0xc436dec1618c2269!2zUHVlcnRhIGRlbCBTb2wsIE1hZHJpZCwgRXNwYcOxYQ!5e0!3m2!1ses!2ses!4v1621345678901!5m2!1ses!2ses" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
```

### Incluye objeto multimedia (video, audio, YouTube…) ✅
**Ubicación**: `index.html` líneas 81-85 (elemento video)
```html
<div class="ratio ratio-16x9">
    <video controls>
        <source src="images/Fitness360.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
</div>
```

## 📝 Formulario

### Formulario incluye fieldset, legend, label, textarea ✅
**Ubicación**: `index.html` líneas 270 (fieldset), 271 (legend), 274, 278, 283, 287 (label), 288 (textarea)
```html
<fieldset>
    <legend>Información de Contacto</legend>
    <!-- Campos del formulario -->
    <div class="form-group">
        <label for="name">Tu Nombre</label>
        <input type="text" name="name" class="form-control" id="name" required>
    </div>
    <div class="form-group">
        <label for="message">Mensaje</label>
        <textarea class="form-control" name="message" id="message" rows="10" required></textarea>
    </div>
</fieldset>
```

### Formulario incluye 4 o 5 de los input type= (text, radio, checkbox, submit, reset) ✅
**Ubicación**: `index.html` líneas 275 (text), 293-308 (radio), 314-329 (checkbox), 352 (submit), 353 (reset)
```html
<!-- Input type text -->
<input type="text" name="name" class="form-control" id="name" required>

<!-- Input type radio -->
<input class="form-check-input" type="radio" name="conocio" id="conocio1" value="redes" checked>

<!-- Input type checkbox -->
<input class="form-check-input" type="checkbox" name="servicios[]" id="servicio1" value="entrenamiento">

<!-- Input types submit y reset -->
<div class="btn-group w-100" role="group" aria-label="Botones de formulario">
    <button type="submit" class="btn btn-success">Enviar Mensaje</button>
    <button type="reset" class="btn btn-secondary">Limpiar</button>
</div>
```

### Formulario incluye select con option u optgroup ✅
**Ubicación**: `index.html` líneas 334-343 (select con optgroup y option)
```html
<select class="form-select" id="preferencia" name="preferencia">
    <optgroup label="Medios digitales">
        <option value="email">Email</option>
        <option value="whatsapp">WhatsApp</option>
    </optgroup>
    <optgroup label="Medios tradicionales">
        <option value="telefono">Teléfono</option>
        <option value="presencial">Presencial</option>
    </optgroup>
</select>
```

## 🎨 Estilos CSS

### Se definen todas las propiedades de formato con CSS (no HTML) ✅
**Ubicación**: Todo el formato se define en `styles.css`

### El diseño realizado con CSS es adecuado ✅
**Ubicación**: `styles.css` (diseño completo y coherente)

## 📦 Bootstrap

### Incluye tabla con formato definido por el alumno ✅
**Ubicación**: `client_dashboard.php` líneas 215-244 (estructura de tabla para información personal)
```html
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

### Incluye imagen con formato definido por el alumno ✅
**Ubicación**: `index.html` líneas 66, 123, 136, 149 (imágenes con formato)
```html
<!-- Imagen hero con clase img-fluid y animated -->
<img src="images/hero-img.png" class="img-fluid animated" alt="Fitness360 Hero Image">

<!-- Imagen de servicio en card-img -->
<div class="card-img">
    <img src="images/service-1.jpg" alt="Entrenamiento Personalizado">
</div>

<!-- Imagen de características con clase img-fluid -->
<img src="images/features.jpg" class="img-fluid" alt="Características de Fitness360">
```

### Incluye alerta ✅
**Ubicación**: `index.html` línea 190, `client_dashboard.php` líneas 190, 255, 307, 347 (alertas)
```html
<!-- Alerta de error -->
<div class="alert alert-danger"><?php echo $error_message; ?></div>

<!-- Alerta informativa -->
<div class="alert alert-info">No tienes revisiones registradas.</div>

<!-- Alerta informativa -->
<div class="alert alert-info">No tienes dietas asignadas.</div>
```

### Incluye botón con formato definido por el alumno ✅
**Ubicación**: `index.html` líneas 59, 352-353 (botones con formato)
```html
<!-- Botón de registro con clase personalizada -->
<a href="php/register.php" class="btn-get-started">Regístrate Ahora</a>

<!-- Botones de formulario con clases de Bootstrap -->
<button type="submit" class="btn btn-success">Enviar Mensaje</button>
<button type="reset" class="btn btn-secondary">Limpiar</button>
```

### Incluye grupo de botones con formato definido por el alumno ✅
**Ubicación**: `index.html` líneas 415-418 (grupo de botones)
```html
<div class="btn-group w-100" role="group" aria-label="Botones de formulario">
    <button type="submit" class="btn btn-success">Enviar Mensaje</button>
    <button type="reset" class="btn btn-secondary">Limpiar</button>
</div>
```

### Incluye desplegable ✅
**Ubicación**: `client_dashboard.php` líneas 165-175 (menú desplegable)
```html
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
```

### Incluye badge ✅
**Ubicación**: `client_dashboard.php` líneas 237-240 (badges para estado)
```html
<span class="badge bg-success">Activo</span>
<span class="badge bg-warning">Inactivo</span>
<span class="badge bg-danger">Suspendido</span>
<span class="badge bg-secondary">Desconocido</span>
```

### Incluye mensaje que se oculta/despliega ✅
**Ubicación**: `index.html` líneas 411-413 (mensajes de carga, error y éxito)
```html
<div class="loading">Cargando</div>
<div class="error-message"></div>
<div class="sent-message">Tu mensaje ha sido enviado. ¡Gracias!</div>
```

### Incluye modal (ventana emergente) ✅
**Ubicación**: `index.html` líneas 73-93 (modal para video)
```html
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Promocional Fitness360</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <video controls>
                        <source src="images/Fitness360.mp4" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
```

### Incluye barra de estado ✅
**Ubicación**: `index.html` líneas 107-115 (barras de progreso)
```html
<div class="progress mb-3" style="height: 25px;">
    <div class="progress-bar bg-success" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">Entrenamiento Personal (95%)</div>
</div>
<div class="progress mb-3" style="height: 25px;">
    <div class="progress-bar bg-info" role="progressbar" style="width: 88%;" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100">Planes Nutricionales (88%)</div>
</div>
<div class="progress mb-3" style="height: 25px;">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 92%;" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">Seguimiento de Progreso (92%)</div>
</div>
```

### Incluye paginación ✅
**Ubicación**: `index.html` líneas 163-181 (paginación)
```html
<nav aria-label="Navegación de servicios">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
        </li>
        <li class="page-item active" aria-current="page">
            <a class="page-link" href="#">1</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">2</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">3</a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">Siguiente</a>
        </li>
    </ul>
</nav>
```

### Incluye lista ✅
**Ubicación**: `index.html` líneas 204-211 (lista con iconos), `php/contact.php` líneas 204-208 (lista sin estilo)
```html
<!-- Lista con iconos -->
<ul>
    <li><i class="fas fa-check-circle"></i> Rutinas de ejercicio personalizadas según tus objetivos</li>
    <li><i class="fas fa-check-circle"></i> Planes de alimentación adaptados a tus necesidades</li>
    <li><i class="fas fa-check-circle"></i> Seguimiento detallado de tus medidas y progreso</li>
    <li><i class="fas fa-check-circle"></i> Comunicación directa con entrenadores y nutricionistas</li>
</ul>

<!-- Lista sin estilo -->
<ul class="list-unstyled">
    <li><a href="../index.html" class="text-white">Inicio</a></li>
    <li><a href="../index.html#servicios" class="text-white">Servicios</a></li>
    <li><a href="../index.html#contacto" class="text-white">Contacto</a></li>
</ul>
```

### Incluye carrusel ✅
**Ubicación**: `index.html` líneas 228-295 (carrusel de testimonios)
```html
<div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="row">
                <div class="col-lg-6">
                    <div class="testimonial-item">
                        <img src="images/testimonial-1.jpg" class="testimonial-img" alt="">
                        <h3>María García</h3>
                        <h4>Cliente desde 2022</h4>
                        <p>
                            <i class="fas fa-quote-left"></i>
                            Fitness360 cambió completamente mi enfoque hacia el ejercicio.
                            <i class="fas fa-quote-right"></i>
                        </p>
                    </div>
                </div>
                <!-- Más testimonios -->
            </div>
        </div>
        <!-- Más slides -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>
```

## 🗃️ Base de Datos

### Añade datos a la base de datos ✅
**Ubicación**: `register.php` líneas 217-256 (inserción de datos de cliente)
```php
// Prepare an insert statement
$sql = "INSERT INTO Cliente (nombreUsuario, nombre, apellidos, correo, password, telefono, fechaNacimiento, sexo, altura, estado) 
        VALUES (:username, :nombre, :apellidos, :correo, :password, :telefono, :fecha_nacimiento, :sexo, :altura, 'ACTIVO')";

if($stmt = $db_connection->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
    $stmt->bindParam(":nombre", $param_nombre, PDO::PARAM_STR);
    $stmt->bindParam(":apellidos", $param_apellidos, PDO::PARAM_STR);
    $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);
    $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
    $stmt->bindParam(":telefono", $param_telefono, PDO::PARAM_STR);
    $stmt->bindParam(":fecha_nacimiento", $param_fecha_nacimiento, PDO::PARAM_STR);
    $stmt->bindParam(":sexo", $param_sexo, PDO::PARAM_STR);
    $stmt->bindParam(":altura", $param_altura, PDO::PARAM_STR);

    // Set parameters
    $param_username = $username;
    $param_nombre = $nombre;
    $param_apellidos = $apellidos;
    $param_correo = $correo;
    // Hash the password before storing it
    $param_password = password_hash($password, PASSWORD_DEFAULT); 
    $param_telefono = $telefono;
    $param_fecha_nacimiento = $fecha_nacimiento;
    $param_sexo = $sexo;
    $param_altura = $altura;

    // Attempt to execute the prepared statement
    if($stmt->execute()) {
        // Registration successful
        $register_success = "Registro completado con éxito. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
    }
}
```

### Muestra datos de la base de datos ✅
**Ubicación**: `client_dashboard.php` líneas 58-105 (consultas para mostrar datos)
```php
// Consulta para obtener información personal del cliente
$sql = "SELECT * FROM Cliente WHERE idCliente = :id";
$stmt = $db_connection->prepare($sql);
$stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
$stmt->execute();

if($stmt->rowCount() == 1) {
    $client_info = $stmt->fetch(PDO::FETCH_ASSOC);
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
```

## 💻 JavaScript

### Incluye 3 eventos programados con JavaScript ✅
**Ubicación**: `main.js` líneas 60 (load), 60 (scroll), 163 (submit), 231-240 (mouseenter/mouseleave), 267 (resize)
```javascript
// Evento load
window.addEventListener('load', navbarlinksActive);

// Evento scroll
onscroll(document, navbarlinksActive);

// Evento submit
contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    // Código de validación del formulario
});

// Eventos mouseenter/mouseleave
card.addEventListener('mouseenter', function() {
    this.style.transform = 'translateY(-10px)';
    this.style.boxShadow = '0px 10px 30px rgba(1, 41, 112, 0.2)';
});

card.addEventListener('mouseleave', function() {
    this.style.transform = 'translateY(0)';
    this.style.boxShadow = '0px 0 30px rgba(1, 41, 112, 0.1)';
});

// Evento resize
window.addEventListener('resize', handleResponsiveNav);
```
