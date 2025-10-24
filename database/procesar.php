<?php
session_start();

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "access";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

// Registro
if (isset($_POST["registro"])) {
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];
    $tipo = $_POST["tipo"];

    // Verificar si el usuario ya existe
    $verificar = "SELECT * FROM datos WHERE usuario = '$usuario'";
    $resultado = mysqli_query($enlace, $verificar);

    if (mysqli_num_rows($resultado) > 0) {
        header("Location: ../paginas/inicio/formulario.html?registro=duplicado");
        exit();
    }

    // Insertar el nuevo usuario
    $insertar = "INSERT INTO datos (usuario, contraseña, tipo, completado)
                 VALUES ('$usuario', '$contraseña', '$tipo', 0)";
    $guardar = mysqli_query($enlace, $insertar);

    if ($guardar) {
        header("Location: ../paginas/inicio/formulario.html?registro=exitoso");
    } else {
        header("Location: ../paginas/inicio/formulario.html?registro=error");
    }
    exit();
}

// Login
if (isset($_POST["login"])) {
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];

    $consulta = "SELECT * FROM datos WHERE usuario = '$usuario' AND contraseña = '$contraseña'";
    $resultado = mysqli_query($enlace, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);

        // Guardar información en la sesión
        $_SESSION['id'] = $fila['id'];
        $_SESSION['tipo'] = $fila['tipo'];

        // Específicamente para residentes
        if ($fila['tipo'] === 'residente') {
            $_SESSION['residente_id'] = $fila['id'];
        }

        if ($fila['completado'] == 0) {
            if ($fila['tipo'] === 'residente') {
                header("Location: ../paginas/part_residente/completar_datos_residente.html");
            } elseif ($fila['tipo'] === 'empleado') {
                header("Location: ../paginas/part_empleado/completar_datos_empleado.html");
            }
        } else {
            if ($fila['tipo'] === 'residente') {
                header("Location: ../paginas/part_residente/residente.php");
            } elseif ($fila['tipo'] === 'empleado') {
                header("Location: ../paginas/part_empleado/empleado.php");
            }
        }
    } else {
        header("Location: ../paginas/inicio/formulario.html?login=error");
    }
    exit();
}
