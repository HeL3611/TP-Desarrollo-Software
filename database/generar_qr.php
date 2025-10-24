<?php
require_once 'phpqrcode/qrlib.php';
include 'conexion.php';

if (!isset($_GET['id'])) {
    echo "Falta el ID del residente.";
    exit;
}

$id = intval($_GET['id']);

// Buscar el token en la base de datos
$stmt = $enlace->prepare("SELECT token FROM datos_residente WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Token no encontrado para este ID.";
    exit;
}

$token = $row['token'];

// ✅ Usá tu URL actual de ngrok
$ngrok_url = "https://4986-2802-8010-228a-7d00-3cbd-2016-b42e-d307.ngrok-free.app";
$contenido = "RES$id-$token"; // formato seguro y único

header('Content-Type: image/png');
QRcode::png($contenido);
?>
