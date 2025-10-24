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
$mensaje = "";

// Si se enviaron datos para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $trabajo = $_POST['trabajo'];

    $actualizar = "UPDATE datos_empleado SET 
                    nombre = '$nombre',
                    apellido = '$apellido',
                    dni = '$dni',
                    correo = '$correo',
                    telefono = '$telefono',
                    trabajo = '$trabajo'
                  WHERE id = '$id'";

    if (mysqli_query($enlace, $actualizar)) {
        $mensaje = "✅ Datos actualizados correctamente.";
    } else {
        $mensaje = "❌ Error al actualizar los datos.";
    }
}

// Consultar los datos actuales
$consulta = "SELECT nombre, apellido, dni, correo, telefono, trabajo FROM datos_empleado WHERE id = '$id'";
$resultado = mysqli_query($enlace, $consulta);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "❌ No se encontraron datos para este usuario.";
    exit();
}

$datos = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mis datos</title>
    <link rel="stylesheet" href="../../css/style_5.css">
</head>
<body>
    <div class="container">
        <h1>Mis datos</h1>

        <?php if ($mensaje): ?>
            <p style="color: green; font-weight: bold;"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="post" action="mis_datos.php">
            <label>Nombre:
                <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required>
            </label>

            <label>Apellido:
                <input type="text" name="apellido" value="<?= htmlspecialchars($datos['apellido']) ?>" required>
            </label>

            <label>DNI:
                <input type="text" name="dni" value="<?= htmlspecialchars($datos['dni']) ?>" required>
            </label>

            <label>Correo:
                <input type="email" name="correo" value="<?= htmlspecialchars($datos['correo']) ?>" required>
            </label>

            <label>Teléfono:
                <input type="text" name="telefono" value="<?= htmlspecialchars($datos['telefono']) ?>" required>
            </label>

            <label>Trabajo:
                <input type="text" name="trabajo" value="<?= htmlspecialchars($datos['trabajo']) ?>" required>
            </label>

            <button class="guardar_cambios" type="submit">💾 Guardar cambios</button>
        </form>

        <a href="empleado.php" class="volver-inicio">⬅ Volver al inicio</a>
      <img class="logo-safe" src="../../imagenes/Safe_Entry_Verde.png" alt="">

    </div>
</body>
</html>
