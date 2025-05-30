<?php
/**
 * Página de Registro - Fitness360
 * 
 * Este archivo maneja el proceso de registro de nuevos clientes,
 * validando los datos ingresados y creando una nueva cuenta en la base de datos.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Iniciar sesión para manejar el estado del usuario
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

// Define variables and initialize with empty values
$username = $password = $confirm_password = $nombre = $apellidos = $correo = $telefono = $fecha_nacimiento = $sexo = $altura = "";
$username_err = $password_err = $confirm_password_err = $nombre_err = $apellidos_err = $correo_err = $telefono_err = $fecha_nacimiento_err = $sexo_err = $altura_err = "";
$register_success = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Por favor ingrese un nombre de usuario.";
    } else {
        // Connect to database
        if(connectToDatabase()) {
            // Prepare a select statement
            $sql = "SELECT idCliente FROM Cliente WHERE nombreUsuario = :username";

            if($stmt = $db_connection->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                // Set parameters
                $param_username = trim($_POST["username"]);

                // Attempt to execute the prepared statement
                if($stmt->execute()) {
                    if($stmt->rowCount() > 0) {
                        $username_err = "Este nombre de usuario ya está en uso.";
                    } else {
                        // Check if username exists in Empleado table
                        $sql = "SELECT idEmpleado FROM Empleado WHERE nombreUsuario = :username";

                        if($stmt = $db_connection->prepare($sql)) {
                            // Bind variables to the prepared statement as parameters
                            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                            // Attempt to execute the prepared statement
                            if($stmt->execute()) {
                                if($stmt->rowCount() > 0) {
                                    $username_err = "Este nombre de usuario ya está en uso.";
                                } else {
                                    $username = trim($_POST["username"]);
                                }
                            } else {
                                echo "Oops! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                            }
                        }
                    }
                } else {
                    echo "Oops! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                }

                // Close statement
                unset($stmt);
            }
        } else {
            echo "Error de conexión a la base de datos. Por favor, inténtelo de nuevo más tarde.";
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Por favor ingrese una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor confirme la contraseña.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }

    // Validate nombre
    if(empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingrese su nombre.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Validate apellidos
    if(empty(trim($_POST["apellidos"]))) {
        $apellidos_err = "Por favor ingrese sus apellidos.";
    } else {
        $apellidos = trim($_POST["apellidos"]);
    }

    // Validate correo
    if(empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor ingrese su correo electrónico.";
    } elseif(!filter_var(trim($_POST["correo"]), FILTER_VALIDATE_EMAIL)) {
        $correo_err = "Por favor ingrese un correo electrónico válido.";
    } else {
        // Check if email already exists
        if(connectToDatabase()) {
            $sql = "SELECT idCliente FROM Cliente WHERE correo = :correo";

            if($stmt = $db_connection->prepare($sql)) {
                $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);
                $param_correo = trim($_POST["correo"]);

                if($stmt->execute()) {
                    if($stmt->rowCount() > 0) {
                        $correo_err = "Este correo electrónico ya está registrado.";
                    } else {
                        // Check if email exists in Empleado table
                        $sql = "SELECT idEmpleado FROM Empleado WHERE correo = :correo";

                        if($stmt = $db_connection->prepare($sql)) {
                            $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);

                            if($stmt->execute()) {
                                if($stmt->rowCount() > 0) {
                                    $correo_err = "Este correo electrónico ya está registrado.";
                                } else {
                                    $correo = trim($_POST["correo"]);
                                }
                            }
                        }
                    }
                }

                unset($stmt);
            }
        }
    }

    // Validate telefono (optional)
    if(!empty(trim($_POST["telefono"]))) {
        $telefono = trim($_POST["telefono"]);
    }

    // Validate fecha_nacimiento
    if(empty(trim($_POST["fecha_nacimiento"]))) {
        $fecha_nacimiento_err = "Por favor ingrese su fecha de nacimiento.";
    } else {
        $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);

        // Check if date is valid and not in the future
        $input_date = new DateTime($fecha_nacimiento);
        $today = new DateTime();

        if($input_date > $today) {
            $fecha_nacimiento_err = "La fecha de nacimiento no puede ser en el futuro.";
        }
    }

    // Validate sexo
    if(empty($_POST["sexo"])) {
        $sexo_err = "Por favor seleccione su sexo.";
    } else {
        $sexo = $_POST["sexo"];
    }

    // Validate altura
    if(empty(trim($_POST["altura"]))) {
        $altura_err = "Por favor ingrese su altura.";
    } elseif(!is_numeric(trim($_POST["altura"])) || trim($_POST["altura"]) <= 0) {
        $altura_err = "Por favor ingrese una altura válida.";
    } else {
        $altura = trim($_POST["altura"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nombre_err) && 
       empty($apellidos_err) && empty($correo_err) && empty($fecha_nacimiento_err) && empty($sexo_err) && empty($altura_err)) {

        // Connect to database
        if(connectToDatabase()) {
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

                    // Clear form data
                    $username = $password = $confirm_password = $nombre = $apellidos = $correo = $telefono = $fecha_nacimiento = $sexo = $altura = "";
                } else {
                    echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                }

                // Close statement
                unset($stmt);
            }

            // Close connection
            unset($db_connection);
        } else {
            echo "Error de conexión a la base de datos. Por favor, inténtelo de nuevo más tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Fitness360</title>
    <!-- Bootstrap 5 CSS - Framework CSS para el diseño responsive y componentes de interfaz -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-registro">
    <div class="container">
        <div class="contenedor-registro shadow-sm rounded-3">
            <div class="logo-registro">
                <h1 class="fw-bold text-success">Fitness360</h1>
                <p class="lead">Crea tu cuenta</p>
            </div>

            <?php 
            if(!empty($register_success)){
                echo '<div class="alert alert-success">' . $register_success . '</div>';
            }        
            ?>

            <!-- Formulario de registro - Componentes Bootstrap para formulario de registro con validación -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="formulario-registro">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label fw-medium">Nombre de Usuario</label>
                            <input type="text" name="username" id="username" class="form-control rounded-3 py-2 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="correo" class="form-label fw-medium">Correo Electrónico</label>
                            <input type="email" name="correo" id="correo" class="form-control rounded-3 py-2 <?php echo (!empty($correo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $correo; ?>">
                            <span class="invalid-feedback"><?php echo $correo_err; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label fw-medium">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 py-2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="confirm_password" class="form-label fw-medium">Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control rounded-3 py-2 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nombre" class="form-label fw-medium">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control rounded-3 py-2 <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                            <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="apellidos" class="form-label fw-medium">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control rounded-3 py-2 <?php echo (!empty($apellidos_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $apellidos; ?>">
                            <span class="invalid-feedback"><?php echo $apellidos_err; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="telefono" class="form-label fw-medium">Teléfono (opcional)</label>
                            <input type="tel" name="telefono" id="telefono" class="form-control rounded-3 py-2" value="<?php echo $telefono; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fecha_nacimiento" class="form-label fw-medium">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control rounded-3 py-2 <?php echo (!empty($fecha_nacimiento_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fecha_nacimiento; ?>">
                            <span class="invalid-feedback"><?php echo $fecha_nacimiento_err; ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-medium">Sexo</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="sexo" id="sexo_m" value="M" <?php if($sexo == "M") echo "checked"; ?>>
                                <label class="form-check-label" for="sexo_m">
                                    Masculino
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="sexo" id="sexo_f" value="F" <?php if($sexo == "F") echo "checked"; ?>>
                                <label class="form-check-label" for="sexo_f">
                                    Femenino
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="sexo" id="sexo_o" value="O" <?php if($sexo == "O") echo "checked"; ?>>
                                <label class="form-check-label" for="sexo_o">
                                    Otro
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="sexo" id="sexo_ns" value="NS" <?php if($sexo == "NS" || $sexo == "") echo "checked"; ?>>
                                <label class="form-check-label" for="sexo_ns">
                                    Prefiero no decirlo
                                </label>
                            </div>
                            <span class="text-danger"><?php echo $sexo_err; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="altura" class="form-label fw-medium">Altura (cm)</label>
                            <input type="number" name="altura" id="altura" class="form-control rounded-3 py-2 <?php echo (!empty($altura_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $altura; ?>" step="0.1" min="0">
                            <span class="invalid-feedback"><?php echo $altura_err; ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4 mb-4">
                    <button type="submit" class="btn btn-success w-100 py-2 fw-medium shadow-sm">Registrarse</button>
                </div>

                <div class="form-group enlace-login text-center">
                    ¿Ya tienes una cuenta? <a href="login.php" class="text-success fw-medium">Inicia sesión aquí</a>
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
