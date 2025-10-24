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
$casa = $_POST['casa'];

// 🔐 Generar token único
$token = bin2hex(random_bytes(16)); // 32 caracteres hexadecimales

// Insertar datos del residente con token
$insertar = "INSERT INTO datos_residente (id, nombre, apellido, dni, correo, telefono, casa, token)
             VALUES ('$id', '$nombre', '$apellido', '$dni', '$correo', '$telefono', '$casa', '$token')";
$guardar = mysqli_query($enlace, $insertar);

if ($guardar) {
    // Actualizar el campo 'completado' en la tabla general
    $actualizar = "UPDATE datos SET completado = 1 WHERE id = $id";
    mysqli_query($enlace, $actualizar);

    // Redirigir al panel del residente
    header("Location: ../paginas/part_residente/residente.php");
    exit();
} else {
    echo "❌ Error al guardar los datos del residente.";
}
?>
