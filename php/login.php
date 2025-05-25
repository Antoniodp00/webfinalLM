<?php
/**
 * Página de Inicio de Sesión - Fitness360
 * 
 * Este archivo maneja la autenticación de usuarios, permitiendo el acceso
 * tanto a clientes como a empleados del gimnasio.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Iniciar sesión para manejar la autenticación del usuario
session_start();

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

// Inicializar variables para el formulario de login
$username = $password = "";  // Variables para almacenar los datos del formulario
$username_err = $password_err = $login_err = "";  // Variables para mensajes de error

// Procesar datos del formulario cuando se envía mediante POST
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar el nombre de usuario
    if(empty(trim($_POST["username"]))) {
        $username_err = "Por favor ingrese su nombre de usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validar la contraseña
    if(empty(trim($_POST["password"]))) {
        $password_err = "Por favor ingrese su contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Si no hay errores de validación, intentar autenticar al usuario
    if(empty($username_err) && empty($password_err)) {
        // Conectar a la base de datos
        if(connectToDatabase()) {
            // Primero verificar si es un cliente
            $sql = "SELECT idCliente, nombreUsuario, password FROM Cliente WHERE nombreUsuario = :username";

            if($stmt = $db_connection->prepare($sql)) {
                // Vincular parámetros a la consulta preparada
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = $username;

                // Ejecutar la consulta
                if($stmt->execute()) {
                    // Verificar si existe el usuario como cliente
                    if($stmt->rowCount() == 1) {
                        if($row = $stmt->fetch()) {
                            $id = $row["idCliente"];
                            $username = $row["nombreUsuario"];
                            $hashed_password = $row["password"];

                            // Verificar la contraseña usando password_verify
                            if(password_verify($password, $hashed_password)) {
                                // Contraseña correcta, iniciar sesión como cliente
                                session_start();

                                // Almacenar datos en variables de sesión
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["user_type"] = "cliente";

                                // Redirigir al panel de cliente
                                header("location: client_dashboard.php");
                            } else {
                                // Contraseña incorrecta
                                $login_err = "Nombre de usuario o contraseña incorrectos.";
                            }
                        }
                    } else {
                        // Si no es cliente, verificar si es empleado
                        $sql = "SELECT idEmpleado, nombreUsuario, password FROM Empleado WHERE nombreUsuario = :username";

                        if($stmt = $db_connection->prepare($sql)) {
                            // Vincular parámetros a la consulta preparada
                            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                            $param_username = $username;

                            // Ejecutar la consulta
                            if($stmt->execute()) {
                                // Verificar si existe el usuario como empleado
                                if($stmt->rowCount() == 1) {
                                    if($row = $stmt->fetch()) {
                                        $id = $row["idEmpleado"];
                                        $username = $row["nombreUsuario"];
                                        $hashed_password = $row["password"];

                                        // Verificar la contraseña usando password_verify
                                        if(password_verify($password, $hashed_password)) {
                                            // Contraseña correcta, iniciar sesión como empleado
                                            session_start();

                                            // Almacenar datos en variables de sesión
                                            $_SESSION["loggedin"] = true;
                                            $_SESSION["id"] = $id;
                                            $_SESSION["username"] = $username;
                                            $_SESSION["user_type"] = "empleado";

                                            // Redirigir al panel de empleado
                                            header("location: employee_dashboard.php");
                                        } else {
                                            // Contraseña incorrecta
                                            $login_err = "Nombre de usuario o contraseña incorrectos.";
                                        }
                                    }
                                } else {
                                    // El usuario no existe ni como cliente ni como empleado
                                    $login_err = "Nombre de usuario o contraseña incorrectos.";
                                }
                            } else {
                                // Error al ejecutar la consulta
                                $login_err = "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                            }
                        }
                    }
                } else {
                    // Error al ejecutar la consulta
                    $login_err = "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                }

                // Liberar recursos
                unset($stmt);
            }

            // Cerrar conexión a la base de datos
            unset($db_connection);
        } else {
            // Error de conexión a la base de datos
            $login_err = "Error de conexión a la base de datos. Por favor, inténtelo de nuevo más tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Fitness360</title>
    <!-- Bootstrap 5 CSS - Framework CSS para el diseño responsive y componentes de interfaz -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-login">
    <div class="container">
        <div class="contenedor-login shadow-sm rounded-3">
            <div class="logo-login">
                <h1 class="fw-bold text-success">Fitness360</h1>
                <p class="lead">Inicia sesión en tu cuenta</p>
            </div>

            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <!-- Formulario de login - Componentes Bootstrap para formulario de inicio de sesión con validación -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="formulario-login">
                <div class="form-group mb-3">
                    <label for="username" class="form-label fw-medium">Nombre de Usuario</label>
                    <input type="text" name="username" id="username" class="form-control rounded-3 py-2 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label fw-medium">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control rounded-3 py-2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>

                <div class="form-group olvido-contrasena mb-4">
                    <a href="reset_password.php" class="text-success">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="form-group mb-4">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-medium shadow-sm">Iniciar Sesión</button>
                </div>

                <div class="form-group enlace-registro text-center">
                    ¿No tienes una cuenta? <a href="register.php" class="text-success fw-medium">Regístrate aquí</a>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="../index.html" class="btn btn-outline-success rounded-3 shadow-sm"><i class="fas fa-arrow-left me-2"></i> Volver a la página principal</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper - Biblioteca JavaScript para funcionalidades interactivas de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
