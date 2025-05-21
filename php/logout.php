<?php
/**
 * Página de Cierre de Sesión - Fitness360
 * 
 * Este archivo maneja el cierre de sesión del usuario,
 * eliminando todas las variables de sesión y redirigiendo al login.
 * 
 * @author Fitness360 Team
 * @version 1.0
 */

// Inicializar la sesión para poder cerrarla
session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir completamente la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("location: login.php");
exit;
?>
