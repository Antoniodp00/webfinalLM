<?php
// Start session
session_start();

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
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if(empty(trim($_POST["username"]))) {
        $username_err = "Por favor ingrese su nombre de usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))) {
        $password_err = "Por favor ingrese su contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)) {
        // Connect to database
        if(connectToDatabase()) {
            // Prepare a select statement
            $sql = "SELECT idCliente, nombreUsuario, password FROM Cliente WHERE nombreUsuario = :username";

            if($stmt = $db_connection->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                // Set parameters
                $param_username = $username;

                // Attempt to execute the prepared statement
                if($stmt->execute()) {
                    // Check if username exists, if yes then verify password
                    if($stmt->rowCount() == 1) {
                        if($row = $stmt->fetch()) {
                            $id = $row["idCliente"];
                            $username = $row["nombreUsuario"];
                            $hashed_password = $row["password"];

                            // Use password_verify to check the hashed password
                            if(password_verify($password, $hashed_password)) {
                                // Password is correct, start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["user_type"] = "cliente";

                                // Redirect user to client dashboard
                                header("location: client_dashboard.php");
                            } else {
                                // Password is not valid
                                $login_err = "Nombre de usuario o contraseña incorrectos.";
                            }
                        }
                    } else {
                        // Check if it's an employee
                        $sql = "SELECT idEmpleado, nombreUsuario, password FROM Empleado WHERE nombreUsuario = :username";

                        if($stmt = $db_connection->prepare($sql)) {
                            // Bind variables to the prepared statement as parameters
                            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                            // Set parameters
                            $param_username = $username;

                            // Attempt to execute the prepared statement
                            if($stmt->execute()) {
                                // Check if username exists, if yes then verify password
                                if($stmt->rowCount() == 1) {
                                    if($row = $stmt->fetch()) {
                                        $id = $row["idEmpleado"];
                                        $username = $row["nombreUsuario"];
                                        $hashed_password = $row["password"];

                                        // Use password_verify to check the hashed password
                                        if(password_verify($password, $hashed_password)) {
                                            // Password is correct, start a new session
                                            session_start();

                                            // Store data in session variables
                                            $_SESSION["loggedin"] = true;
                                            $_SESSION["id"] = $id;
                                            $_SESSION["username"] = $username;
                                            $_SESSION["user_type"] = "empleado";

                                            // Redirect user to employee dashboard
                                            header("location: employee_dashboard.php");
                                        } else {
                                            // Password is not valid
                                            $login_err = "Nombre de usuario o contraseña incorrectos.";
                                        }
                                    }
                                } else {
                                    // Username doesn't exist
                                    $login_err = "Nombre de usuario o contraseña incorrectos.";
                                }
                            } else {
                                $login_err = "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                            }
                        }
                    }
                } else {
                    $login_err = "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
                }

                // Close statement
                unset($stmt);
            }

            // Close connection
            unset($db_connection);
        } else {
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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: var(--gray-light);
        }
        .login-container {
            max-width: 450px;
            margin: 100px auto;
            padding: 30px;
            background: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo h1 {
            font-size: 36px;
            margin: 0;
            color: var(--primary-dark);
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .login-form .form-group {
            margin-bottom: 20px;
        }
        .login-form .form-control {
            height: 50px;
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-medium);
            padding: 10px 15px;
            transition: var(--transition);
        }
        .login-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        .login-form .btn-primary {
            height: 50px;
            border-radius: var(--border-radius);
            font-weight: 600;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: var(--transition);
            box-shadow: var(--box-shadow);
        }
        .login-form .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .login-form .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        .login-form .forgot-password a {
            color: var(--primary-color);
            transition: var(--transition);
        }
        .login-form .forgot-password a:hover {
            color: var(--primary-dark);
        }
        .login-form .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-form .register-link a {
            color: var(--primary-color);
            font-weight: 500;
            transition: var(--transition);
        }
        .login-form .register-link a:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <h1>Fitness360</h1>
                <p>Inicia sesión en tu cuenta</p>
            </div>

            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>

                <div class="form-group forgot-password">
                    <a href="reset_password.php">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </div>

                <div class="form-group register-link">
                    ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="../index.html" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Volver a la página principal</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
