<?php
// Datos de conexión


$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "access";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

// Verificar conexión
if (!$enlace) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}
?>
