<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'residente') {
    header("Location: ../inicio/formulario.html");
    exit();
}

$enlace = mysqli_connect("localhost", "root", "", "access");

$id = $_SESSION['id'];
$consulta = "SELECT nombre FROM datos_residente WHERE id = $id";
$resultado = mysqli_query($enlace, $consulta);
$fila = mysqli_fetch_assoc($resultado);
$nombre = $fila['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Residente</title>
  <link rel="stylesheet" href="../../css/style_4.css" />
      <link rel="icon" href="../../imagenes/Safe_Entry_Negro_icon.png" type="image/peng">

  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
  <div class="panel-residente">
    <h1>Bienvenido <?php echo htmlspecialchars($nombre); ?></h1>

    <div class="qr-section">
      <div class="qr-icon" onclick="abrirCamara()">QR</div>
      <p class="qr-text">Presioná el botón para escanear<br />el QR de acceso que está en<br />la pantalla de la entrada.</p>

      <div class="qr-reader" id="qr-reader"></div>
      <div id="qr-result"></div>
      <button class="cerrar-qr" id="cerrar-qr" onclick="cerrarCamara()">Cerrar</button>
    </div>

    <div class="opciones">
      <ul>
        <li><a class="opciones_1" href="historial_accesos.php">📜 Historial de accesos</a></li>
        <li><a class="opciones_1" href="pin_invitado.php">🔑 Pin temporal para invitados</a></li>
        <li><a class="opciones_1" href="mis_datos.php">📄 Mis datos</a></li>
        <li><a class="opciones_2" href="cerrar_sesion.php">🚪 Cerrar sesión</a></li>
      </ul>
    </div>

    <img class="logo-safe" src="../../imagenes/Safe_Entry_Verde.png" alt="" />
  </div>

  <script>
    // Guardamos el ID del residente actual para enviarlo al backend
    const usuarioId = <?php echo json_encode($id); ?>;
    let html5QrCode;

    function abrirCamara() {
      const qrReader = document.getElementById("qr-reader");
      const btnCerrar = document.getElementById("cerrar-qr");
      qrReader.innerHTML = "";

      html5QrCode = new Html5Qrcode("qr-reader");

      Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
          const backCamera = devices[devices.length - 1];

          html5QrCode.start(
            backCamera.id,
            {
              fps: 10,
              qrbox: 250
            },
            qrCodeMessage => {
              document.getElementById("qr-result").innerText = `QR Detectado: ${qrCodeMessage}`;

              // Paso 1: Verificar si el QR es válido
              fetch('../../database/verificar_qr.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({ qr: qrCodeMessage })
              })
              .then(response => response.json())
              .then(data => {
                if (data.acceso === 'permitido') {
                  alert('✅ Acceso permitido');

                  // Paso 2: Registrar el acceso con el ID del residente actual
                  fetch('../../database/registrar_acceso.php', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ qr: qrCodeMessage, id_residente: usuarioId })
                  })
                  .then(response => response.json())
                  .then(res => {
                    if (!res.success) {
                      console.error('Error al registrar el acceso:', res);
                    }
                  });

                } else {
                  alert('✅ Acceso permitido'); // Acceso denegado
                }
              });

              cerrarCamara(); // cerrar después del escaneo
            },
            errorMessage => {
              console.warn(`Error escaneando: ${errorMessage}`);
            }
          ).then(() => {
            btnCerrar.style.display = "inline-block";
          });
        }
      }).catch(err => {
        console.error(`No se pudieron obtener las cámaras: ${err}`);
      });
    }

    function cerrarCamara() {
      if (html5QrCode) {
        html5QrCode.stop().then(() => {
          document.getElementById("qr-reader").innerHTML = "";
          document.getElementById("cerrar-qr").style.display = "none";
        }).catch(err => {
          console.error("Error al detener el lector QR:", err);
        });
      }
    }
  </script>
</body>
</html>
