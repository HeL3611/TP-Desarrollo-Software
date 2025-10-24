<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "⚠️ Sesión no iniciada. Acceso no autorizado.";
    exit();
}

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "access";

// Conexión
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("❌ Error al conectar con la base de datos.");
}

$id = $_SESSION['id'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$dni = $_POST['dni'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$trabajo = $_POST['trabajo'];

// Insertar datos del empleado
$insertar = "INSERT INTO datos_empleado (id, nombre, apellido, dni, correo, telefono, trabajo)
             VALUES ('$id', '$nombre', '$apellido', '$dni', '$correo', '$telefono', '$trabajo')";
$guardar = mysqli_query($enlace, $insertar);

if ($guardar) {
    // Actualizar el campo 'completado'
    $actualizar = "UPDATE datos SET completado = 1 WHERE id = $id";
    mysqli_query($enlace, $actualizar);

    // Redirigir al panel del empleado
    header("Location: ../paginas/part_empleado/empleado.php");
    exit();
} else {
    echo "❌ Error al guardar los datos del empleado.";
}
?>
