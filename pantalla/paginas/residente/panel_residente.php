<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'residente') {
    echo "<p style='color:red; text-align:center;'>No se encontró sesión del residente. Por favor inicie sesión.</p>";
    exit;
}

$id = $_SESSION['id']; // ✅ Corrección importante
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Residente</title>
  <a class="atras" href="../inicio/index.html">←</a>
  <link rel="stylesheet" href="../../css/estilo_residente.css" />
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>
<body>
  <div class="panel">
  <div class="container">
    <h1>Residente</h1>

    <!-- Canvas donde se mostrará el QR -->
    <canvas id="canvas-qr" class="qr"></canvas>

    <a class="opciones_1" href="pin.html">Pin</a>
    <a class="opciones_2" href="emergencia.html">Emergencia</a>
  </div>
  </div>
  <script>
    // 👇 Colocá tu URL pública de ngrok acá
    const ngrokURL = "https://4986-2802-8010-228a-7d00-3cbd-2016-b42e-d307.ngrok-free.app"; // ⚠️ CAMBIAR POR LA TUYA
    const id = <?= json_encode($id) ?>;
    const link = `${ngrokURL}/database/verificar_qr.php?id=${id}`;

    QRCode.toCanvas(document.getElementById('canvas-qr'), link, error => {
      if (error) {
        console.error('Error generando QR:', error);
        alert('No se pudo generar el código QR');
      }
    });
  </script>
</body>
</html>
