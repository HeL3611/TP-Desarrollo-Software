<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'empleado') {
    header("Location: ../inicio/formulario.html");
    exit();
}

$enlace = mysqli_connect("localhost", "root", "", "access");

$id = $_SESSION['id'];
$consulta = "SELECT nombre FROM datos_empleado WHERE id = $id";
$resultado = mysqli_query($enlace, $consulta);
$fila = mysqli_fetch_assoc($resultado);
$nombre = $fila['nombre'];

// Generar contenido único para el QR
$codigoQR = "ACCESO-" . uniqid();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Empleado</title>
  <link rel="icon" href="../../imagenes/Safe_Entry_Negro_icon.png" type="image/peng">

  <link rel="stylesheet" href="../../css/style_4.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<style>
.qr-section {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    box-sizing: border-box;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin: 10px;
}



.qr-icon {
    background-color: #333;
    color: white;
    font-weight: bold;
    font-size: 20px;
    padding: 20px;
    border-radius: 20px;
    cursor: pointer;
    min-width: 80px;
    
    text-align: center;
    transition: background-color 0.3s;
}

.qr-icon:hover {
    background-color: #b4f1b4;
    color: #333;
}

.qr-text {
    font-size: 14px;
    color: #333;
    max-width: 250px;
    line-height: 1.4;
    text-align: left;
}

.qr-reader {
    width: 100%;
    max-width: 500px;
    position: relative;
    left: 25px;
    margin: 15px ;

}


.cerrar-qr {
    display: none;
    background-color: #d9534f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s ease;
    position: relative;
    z-index: 10;
}

.cerrar-qr:hover {
    background-color: #c9302c;
}


</style>
</head>
<body>

<div class="panel-residente">
    <h1>Bienvenido <?php echo htmlspecialchars($nombre); ?></h1>

    <div class="qr-section">
        <div class="qr-icon" onclick="mostrarQR()">🔲 Generar QR</div>

        <div class="qr-reader-wrapper">
            <p>Código Qr para ingresar Modo-Empleado</p>
           
            <div class="qr-reader" id="qr-reader"></div>

            <div id="qr-result"></div>


            <button class="cerrar-qr" id="cerrar-qr" onclick="cerrarQR()">Cerrar</button>
        </div>
    </div>


    <div class="opciones">
        <ul>
            <li><a class="opciones_1" href="mis_datos.php">📄 Mis datos</a></li>
            <li><a class="opciones_2" href="cerrar_sesion.php">🚪 Cerrar sesión</a></li>
        </ul>
    </div>

    <img class="logo-safe" src="../../imagenes/Safe_Entry_Verde.png" alt="Logo Safe Entry">
</div>

<script>
function mostrarQR() {
    const qrReader = document.getElementById("qr-reader");
    const qrResult = document.getElementById("qr-result");
    const btnCerrar = document.getElementById("cerrar-qr");

    // Limpia el contenido anterior
    qrReader.innerHTML = "";
    qrResult.innerText = "";

    // Contenido QR desde PHP
    const contenido = "<?php echo $codigoQR; ?>";

    // Generar el QR
    new QRCode(qrReader, {
        text: contenido,
        width: 250,
        height: 250
    });

    qrResult.innerText = "Código generado: " + contenido;
    btnCerrar.style.display = "inline-block";
}

function cerrarQR() {
    document.getElementById("qr-reader").innerHTML = "";
    document.getElementById("qr-result").innerText = "";
    document.getElementById("cerrar-qr").style.display = "none";
}
</script>

</body>
</html>
