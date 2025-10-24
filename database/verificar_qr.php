<?php
header('Content-Type: application/json');
include 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);
$qr = $data['qr'] ?? '';

// Validar formato: RES[id]-token
if (preg_match('/^RES(\d+)-([a-f0-9]{32})$/', $qr, $matches)) {
    $id = intval($matches[1]);
    $token = $matches[2];

    $stmt = $enlace->prepare("SELECT id FROM datos_residente WHERE id = ? AND token = ?");
    $stmt->bind_param("is", $id, $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["acceso" => "permitido"]);
    } else {
        echo json_encode(["acceso" => "denegado"]);
    }
} else {
    echo json_encode(["acceso" => "denegado"]);
}
?>
