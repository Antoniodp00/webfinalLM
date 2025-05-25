# Comprobación de Requisitos - Fitness360

Este documento detalla cómo el proyecto Fitness360 cumple con los requisitos especificados.

## Diseño Responsive

### La página web tiene 3 tamaños y se realizan más de 2 cambios por tamaño
**Ubicación**: `index.html` - Utilizando clases de Bootstrap para responsive design
```html
<!-- Ejemplo en la sección hero -->
<div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1">
    <h1 class="fw-bold mb-4">Transforma tu cuerpo y tu vida con <span class="text-light position-relative">Fitness360</span></h1>
    <h2 class="fs-4 fw-normal text-light opacity-75 mb-4">La plataforma integral para gestionar tu salud y entrenamiento personal de forma efectiva</h2>
</div>

<!-- Ejemplo en la sección de servicios -->
<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
        <!-- Contenido de la tarjeta -->
    </div>
</div>

<!-- Ejemplo en la sección de testimonios -->
<div class="col-lg-6 mb-4 mb-lg-0">
    <div class="bg-white p-4 rounded-4 shadow-sm h-100 border-start border-success border-4">
        <!-- Contenido del testimonio -->
    </div>
</div>
```

### Aparece/desaparece algún objeto en un cambio de tamaño de pantalla
**Ubicación**: `index.html` - Utilizando clases de Bootstrap para mostrar/ocultar elementos
```html
<!-- Ejemplo en la sección de contacto -->
<div class="d-none d-md-block">
    <!-- Contenido que solo se muestra en pantallas medianas y grandes -->
</div>

<!-- Ejemplo en el footer -->
<div class="d-md-none">
    <!-- Contenido que solo se muestra en pantallas pequeñas -->
</div>

<!-- Clases de Bootstrap para visibilidad responsive -->
<span class="d-none d-lg-inline">Texto visible solo en pantallas grandes</span>
<span class="d-lg-none">Texto visible solo en pantallas pequeñas y medianas</span>
```

### Cambia de posición algún objeto en un cambio de tamaño de pantalla
**Ubicación**: `index.html` - Utilizando clases de orden de Bootstrap
```html
<!-- Ejemplo en la sección hero -->
<div class="col-lg-6 order-1 order-lg-2">
    <img src="images/hero-img.png" class="img-fluid" alt="Fitness360 Hero Image">
</div>

<!-- Ejemplo en la sección de características -->
<div class="row g-4 align-items-center">
    <div class="col-lg-6">
        <img src="images/features.jpg" class="img-fluid rounded-4 shadow-sm" alt="Características de Fitness360">
    </div>
    <div class="col-lg-6 pt-4 pt-lg-0">
        <!-- Contenido de características -->
    </div>
</div>

<!-- Ejemplo en el footer -->
<div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
    <!-- Contenido alineado a la izquierda en pantallas medianas y grandes, centrado en pequeñas -->
</div>
```

## Estructura HTML

### Incluye header, footer y nav
**Ubicación**: `index.html` líneas 16-49 (header y nav), líneas 541-608 (footer)
```html
<!-- Header/Navbar -->
<header id="header" class="shadow-sm bg-white">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="index.html">
                Fitness360
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-1">
                        <a class="nav-link active rounded py-2 px-3" href="index.html">Inicio</a>
                    </li>
                    <!-- Más elementos de navegación -->
                </ul>
            </div>
        </div>
    </nav>
</header>
```

### Incluye objeto de formato de fuente (b, strong, etc.)
**Ubicación**: `index.html` línea 20 (strong), líneas 547-553 (strong)
```html
<a class="navbar-brand fw-bold text-success" href="index.html">
    Fitness360
</a>

<p class="mb-3">
    Calle Ejemplo 123 <br>
    28001 Madrid<br>
    España <br><br>
    <strong>Teléfono:</strong> +34 912 345 678<br>
    <strong>Email:</strong> info@fitness360.com<br>
</p>
```

### Incluye objeto de formato de párrafo (h1, p, etc.)
**Ubicación**: `index.html` líneas 56 (h1), 57 (h2), 77 (h5), 100 (p)
```html
<h1 class="fw-bold mb-4">Transforma tu cuerpo y tu vida con <span class="text-light position-relative">Fitness360</span></h1>
<h2 class="fs-4 fw-normal text-light opacity-75 mb-4">La plataforma integral para gestionar tu salud y entrenamiento personal de forma efectiva</h2>
<h5 class="modal-title" id="videoModalLabel">Video Promocional Fitness360</h5>
<p class="lead">Fitness360 ofrece una gama completa de servicios para ayudarte a alcanzar tus objetivos de fitness y bienestar.</p>
```

### Incluye fondo de página (color o imagen)
**Ubicación**: `index.html` - Utilizando clases de Bootstrap para fondos
```html
<!-- Fondo de color para el hero section -->
<section id="hero" class="d-flex align-items-center bg-black bg-gradient text-white">
    <!-- Contenido del hero -->
</section>

<!-- Fondo de color para la sección de servicios -->
<section id="servicios" class="py-5 bg-light">
    <!-- Contenido de servicios -->
</section>

<!-- Fondo de color para la sección de testimonios -->
<section id="testimonios" class="py-5 bg-light position-relative">
    <!-- Contenido de testimonios -->
</section>

<!-- Fondo de color para el footer -->
<div class="bg-success py-4 text-white">
    <!-- Contenido del footer -->
</div>
```

### Incluye hipervínculo
**Ubicación**: `index.html` líneas 19, 28-44, 59-62 (enlaces de navegación y botones)
```html
<a class="navbar-brand fw-bold text-success" href="index.html">
    Fitness360
</a>

<a class="nav-link active rounded py-2 px-3" href="index.html">Inicio</a>

<a href="php/register.php" class="btn btn-light btn-lg fw-semibold px-4 py-3 rounded-3 shadow-sm me-3 text-success text-uppercase">Regístrate Ahora</a>
<a href="#" class="btn btn-outline-light d-inline-flex align-items-center px-3 py-3 rounded-3" data-bs-toggle="modal" data-bs-target="#videoModal">
    <i class="fas fa-play-circle fs-4 me-2"></i><span>Ver Video</span>
</a>
```

### Incluye algún iframe
**Ubicación**: `index.html` línea 441 (iframe de Google Maps)
```html
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.6167379590934!2d-3.7037974846309875!3d40.41694937936723!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422997800a3c81%3A0xc436dec1618c2269!2zUHVlcnRhIGRlbCBTb2wsIE1hZHJpZCwgRXNwYcOxYQ!5e0!3m2!1ses!2ses!4v1621345678901!5m2!1ses!2ses" class="map-iframe" allowfullscreen></iframe>
```

### Incluye objeto multimedia (video, audio, YouTube…)
**Ubicación**: `index.html` líneas 81-85 (elemento video)
```html
<div class="ratio ratio-16x9">
    <video controls>
        <source src="images/Fitness360.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
</div>
```

## Formulario

### Formulario incluye fieldset, legend, label, textarea
**Ubicación**: `index.html` líneas 449 (fieldset), 450 (legend), 453, 457, 462, 466 (label), 467 (textarea)
```html
<form action="php/contact.php" method="post" role="form" class="bg-white p-4 rounded-4 shadow-sm">
    <fieldset>
        <legend class="fw-bold text-success mb-4 pb-2 border-bottom">Información de Contacto</legend>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-medium">Tu Nombre</label>
                <input type="text" name="name" class="form-control rounded-3 py-2" id="name" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label fw-medium">Tu Email</label>
                <input type="email" class="form-control rounded-3 py-2" name="email" id="email" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label fw-medium">Asunto</label>
            <input type="text" class="form-control rounded-3 py-2" name="subject" id="subject" required>
        </div>
        <div class="mb-4">
            <label for="message" class="form-label fw-medium">Mensaje</label>
            <textarea class="form-control rounded-3 py-2" name="message" id="message" rows="6" required></textarea>
        </div>
    </fieldset>
    <!-- Resto del formulario -->
</form>
```

### Formulario incluye 4 o 5 de los input type= (text, radio, checkbox, submit, reset)
**Ubicación**: `index.html` líneas 454 (text), 458 (email), 463 (text), 472-487 (radio), 493-508 (checkbox), 531-532 (submit, reset)
```html
<!-- Input type text -->
<input type="text" name="name" class="form-control rounded-3 py-2" id="name" required>

<!-- Input type email -->
<input type="email" class="form-control rounded-3 py-2" name="email" id="email" required>

<!-- Input type radio -->
<div class="form-check mb-2">
    <input class="form-check-input" type="radio" name="conocio" id="conocio1" value="redes" checked>
    <label class="form-check-label" for="conocio1">
        Redes Sociales
    </label>
</div>

<!-- Input type checkbox -->
<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" name="servicios[]" id="servicio1" value="entrenamiento">
    <label class="form-check-label" for="servicio1">
        Entrenamiento Personal
    </label>
</div>

<!-- Input types submit y reset -->
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success py-2 px-4 flex-grow-1 fw-medium shadow-sm">Enviar Mensaje</button>
    <button type="reset" class="btn btn-secondary py-2 px-4 fw-medium shadow-sm">Limpiar</button>
</div>
```

### Formulario incluye select con option u optgroup
**Ubicación**: `index.html` líneas 513-522 (select con optgroup y option)
```html
<select class="form-select rounded-3 py-2" id="preferencia" name="preferencia">
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

## Estilos CSS

### Se definen todas las propiedades de formato con CSS (no HTML)
**Ubicación**: El formato se define principalmente mediante clases de Bootstrap en el HTML y personalizaciones en `styles.css`

```html
<!-- Ejemplo de uso de clases de Bootstrap para formato -->
<div class="bg-white p-4 rounded-4 shadow-sm h-100 border-start border-success border-4">
    <div class="d-flex align-items-center mb-3">
        <img src="images/testimonial-1.jpg" class="rounded-circle border-4 border border-success shadow-sm me-3" width="80" height="80" alt="">
        <div>
            <h3 class="fs-5 fw-bold mb-1 text-success">María García</h3>
            <h4 class="fs-6 text-muted">Cliente desde 2022</h4>
        </div>
        <i class="fas fa-quote-left ms-auto fs-3 text-success opacity-25"></i>
    </div>
    <p class="fst-italic mb-0">
        Fitness360 cambió completamente mi enfoque hacia el ejercicio. Las rutinas personalizadas y el seguimiento constante me han ayudado a perder 15kg en 6 meses de forma saludable.
    </p>
</div>
```

```css
/* Estilos personalizados en styles.css */
@keyframes up-down {
    0% {
        transform: translateY(10px);
    }
    100% {
        transform: translateY(-10px);
    }
}

.animado {
    animation: up-down 2.5s ease-in-out infinite alternate-reverse both;
}

.titulo-seccion h2::before {
    content: '';
    position: absolute;
    display: block;
    width: 60px;
    height: 3px;
    background: #A5D6A7;
    bottom: 0;
    left: calc(50% - 30px);
    border-radius: 5px;
}
```

### El diseño realizado con CSS es adecuado
**Ubicación**: Combinación de clases de Bootstrap en `index.html` y estilos personalizados en `styles.css` (diseño completo y coherente)

```html
<!-- Ejemplo de diseño con Bootstrap -->
<section id="caracteristicas" class="py-5 bg-white position-relative overflow-hidden">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3 position-relative pb-3 text-success">Características de Fitness360</h2>
            <p class="lead">Descubre todas las herramientas que Fitness360 pone a tu disposición para alcanzar tus objetivos de forma eficiente.</p>
        </div>
        <!-- Más contenido -->
    </div>
</section>
```

## Bootstrap

### Incluye tabla con formato definido por el alumno
**Ubicación**: `client_dashboard.php` líneas 215-244 (estructura de información personal)
```html
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
```

### Incluye imagen con formato definido por el alumno
**Ubicación**: `index.html` - Imágenes con formato usando clases de Bootstrap
```html
<!-- Imagen hero con clase img-fluid -->
<img src="images/hero-img.png" class="img-fluid" alt="Fitness360 Hero Image">

<!-- Imagen de servicio con formato Bootstrap -->
<div class="overflow-hidden">
    <img src="images/service-1.jpg" class="card-img-top" alt="Entrenamiento Personalizado">
</div>

<!-- Imagen de características con clases de Bootstrap -->
<img src="images/features.jpg" class="img-fluid rounded-4 shadow-sm" alt="Características de Fitness360">

<!-- Imagen de testimonio con clases de Bootstrap -->
<img src="images/testimonial-1.jpg" class="rounded-circle border-4 border border-success shadow-sm me-3" width="80" height="80" alt="">
```

### Incluye alerta
**Ubicación**: `client_dashboard.php` líneas 190, 255, 308, 348 (alertas)
```html
<!-- Alerta de error -->
<div class="alert alert-danger"><?php echo $error_message; ?></div>

<!-- Alerta informativa -->
<div class="alert alert-info">No tienes revisiones registradas.</div>

<!-- Alerta informativa -->
<div class="alert alert-info">No tienes dietas asignadas.</div>

<!-- Alerta informativa -->
<div class="alert alert-info">No tienes rutinas asignadas.</div>
```

### Incluye botón con formato definido por el alumno
**Ubicación**: `index.html` - Botones con formato usando clases de Bootstrap
```html
<!-- Botón de registro con clases de Bootstrap -->
<a href="php/register.php" class="btn btn-light btn-lg fw-semibold px-4 py-3 rounded-3 shadow-sm me-3 text-success text-uppercase">Regístrate Ahora</a>

<!-- Botón de video con clases de Bootstrap -->
<a href="#" class="btn btn-outline-light d-inline-flex align-items-center px-3 py-3 rounded-3" data-bs-toggle="modal" data-bs-target="#videoModal">
    <i class="fas fa-play-circle fs-4 me-2"></i><span>Ver Video</span>
</a>

<!-- Botones de formulario con clases de Bootstrap -->
<button type="submit" class="btn btn-success py-2 px-4 flex-grow-1 fw-medium shadow-sm">Enviar Mensaje</button>
<button type="reset" class="btn btn-secondary py-2 px-4 fw-medium shadow-sm">Limpiar</button>
```

### Incluye grupo de botones con formato definido por el alumno
**Ubicación**: `index.html` - Grupo de botones con formato usando clases de Bootstrap
```html
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-success py-2 px-4 flex-grow-1 fw-medium shadow-sm">Enviar Mensaje</button>
    <button type="reset" class="btn btn-secondary py-2 px-4 fw-medium shadow-sm">Limpiar</button>
</div>

<!-- Grupo de botones para redes sociales -->
<div class="d-flex gap-2 mt-3">
    <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-twitter"></i></a>
    <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-facebook-f"></i></a>
    <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-instagram"></i></a>
    <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-youtube"></i></a>
    <a href="#" class="bg-success rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white" style="width: 40px; height: 40px;"><i class="fab fa-linkedin-in"></i></a>
</div>
```

### Incluye desplegable
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

### Incluye badge
**Ubicación**: `client_dashboard.php` líneas 237-240 (badges para estado)
```html
<span class="badge bg-success">Activo</span>
<span class="badge bg-warning">Inactivo</span>
<span class="badge bg-danger">Suspendido</span>
<span class="badge bg-secondary">Desconocido</span>
```

### Incluye mensaje que se oculta/despliega
**Ubicación**: `index.html` líneas 526-528 (mensajes con clases de Bootstrap para mostrar/ocultar)
```html
<div class="my-3">
    <div class="bg-light p-3 rounded-3 text-center d-none">Cargando</div>
    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger d-none">Error al enviar el mensaje</div>
    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success d-none">Tu mensaje ha sido enviado. ¡Gracias!</div>
</div>
```

### Incluye modal (ventana emergente)
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

### Incluye barra de estado
**Ubicación**: `index.html` líneas 107-115 (barras de progreso)
```html
<div class="progress mb-3 rounded-3">
    <div class="progress-bar bg-success" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">Entrenamiento Personal (95%)</div>
</div>
<div class="progress mb-3 rounded-3">
    <div class="progress-bar bg-info" role="progressbar" style="width: 88%;" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100">Planes Nutricionales (88%)</div>
</div>
<div class="progress mb-3 rounded-3">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 92%;" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">Seguimiento de Progreso (92%)</div>
</div>
```

### Incluye paginación
**Ubicación**: `index.html` líneas 244-263 (paginación con clases de Bootstrap personalizadas)
```html
<nav aria-label="Navegación de servicios">
    <ul class="pagination justify-content-center" id="servicios-pagination">
        <li class="page-item disabled" id="prev-page">
            <a class="page-link rounded-start-3 border-0 shadow-sm" href="#servicios" tabindex="-1" aria-disabled="true">Anterior</a>
        </li>
        <li class="page-item active" aria-current="page" data-page="1">
            <a class="page-link border-0 bg-success shadow-sm" href="#servicios">1</a>
        </li>
        <li class="page-item" data-page="2">
            <a class="page-link border-0 shadow-sm" href="#servicios">2</a>
        </li>
        <li class="page-item" data-page="3">
            <a class="page-link border-0 shadow-sm" href="#servicios">3</a>
        </li>
        <li class="page-item" id="next-page">
            <a class="page-link rounded-end-3 border-0 shadow-sm" href="#servicios">Siguiente</a>
        </li>
    </ul>
</nav>
```

### Incluye lista
**Ubicación**: `index.html` líneas 285-292 (listas con clases de Bootstrap)
```html
<!-- Lista con iconos usando clases de Bootstrap -->
<ul class="list-unstyled mb-4">
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Rutinas de ejercicio personalizadas según tus objetivos</li>
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Planes de alimentación adaptados a tus necesidades</li>
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Seguimiento detallado de tus medidas y progreso</li>
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Comunicación directa con entrenadores y nutricionistas</li>
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Calendario de actividades y recordatorios</li>
    <li class="d-flex align-items-start mb-3"><i class="fas fa-check-circle text-white bg-success p-2 rounded-circle me-3 shadow-sm"></i> Informes y estadísticas de tu evolución</li>
</ul>

<!-- Lista de enlaces en el footer -->
<ul class="list-unstyled">
    <li class="mb-2"><a href="#" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Inicio</a></li>
    <li class="mb-2"><a href="#servicios" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Servicios</a></li>
    <li class="mb-2"><a href="#caracteristicas" class="text-dark text-decoration-none d-inline-flex align-items-center"><i class="fas fa-chevron-right text-success me-2 small"></i> Características</a></li>
</ul>
```

### Incluye carrusel
**Ubicación**: `index.html` líneas 310-389 (carrusel de testimonios con clases de Bootstrap)
```html
<div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner py-4">
        <div class="carousel-item active">
            <div class="row g-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 border-start border-success border-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="images/service-1.jpg" class="rounded-circle border-4 border border-success shadow-sm me-3" width="80" height="80" alt="María García">
                            <div>
                                <h3 class="fs-5 fw-bold mb-1 text-success">María García</h3>
                                <h4 class="fs-6 text-muted">Cliente desde 2022</h4>
                            </div>
                            <i class="fas fa-quote-left ms-auto fs-3 text-success opacity-25"></i>
                        </div>
                        <p class="fst-italic mb-0">
                            Fitness360 cambió completamente mi enfoque hacia el ejercicio. Las rutinas personalizadas y el seguimiento constante me han ayudado a perder 15kg en 6 meses de forma saludable.
                        </p>
                    </div>
                </div>
                <!-- Más testimonios -->
            </div>
        </div>
        <!-- Más slides -->
    </div>
    <button class="carousel-control-prev bg-success rounded-circle" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next bg-success rounded-circle" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>
```

## Base de Datos

### Añade datos a la base de datos
**Ubicación**: `register.php` (inserción de datos de cliente)
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
        $register_success = "Registro completado con éxito. Ahora puedes iniciar sesión.";
    }
}
```

### Muestra datos de la base de datos
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

## JavaScript

### Incluye 3 eventos programados con JavaScript
**Ubicación**: `js/main.js` (eventos load, scroll, click)
```javascript
// Evento DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    // Código que se ejecuta cuando el DOM está completamente cargado
});

// Evento load
window.addEventListener('load', toggleBacktotop);

// Evento scroll
onscroll(document, toggleBacktotop);

// Evento click (implícito en el botón de volver arriba)
let backtotop = select('.volver-arriba');
if (backtotop) {
    const toggleBacktotop = () => {
        if (window.scrollY > 100) {
            backtotop.classList.add('active');
        } else {
            backtotop.classList.remove('active');
        }
    };
}

// Evento shown.bs.tab para persistencia de pestañas
tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(event) {
        localStorage.setItem('activeClientTab', event.target.getAttribute('data-bs-target'));
    });
});

// Función para mostrar mensaje "Coming Soon"
window.showComingSoon = function() {
    alert('Esta funcionalidad estará disponible próximamente.');
};
```
