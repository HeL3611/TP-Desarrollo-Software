<?php
session_start();
$pin = rand(100000, 999999);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PIN Temporal</title>
    <link rel="stylesheet" href="../../css/style_5.css">
    
</head>
<body>
    <div class="container">
    <h1>PIN Temporal para Invitados</h1>
    <p>Tu PIN temporal es:</p>
    <div style="font-size: 39px; font-weight: bold; text-align: center; margin: 5px;"><?= $pin ?></div>
    <p>Compártelo con tu invitado.</p>
    <p>Expira en 1 hora.</p>
    <a id="volver-inicio" href="residente.php" class="volver-inicio">⬅ Volver al inicio</a>
    <img class="logo-safe" src="../../imagenes/Safe_Entry_Verde.png" alt="">

    </div>
</body>
</html>
