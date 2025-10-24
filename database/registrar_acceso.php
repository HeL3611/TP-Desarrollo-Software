<?php
header('Content-Type: application/json');

$conexion = mysqli_connect("localhost", "root", "", "access");

if (!$conexion) {
    echo json_encode(['success' => false, 'error' => 'Fallo la conexión']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$tokenRaw = $data['qr'] ?? '';
$idEscaneador = $data['id_residente'] ?? null;

// Validar formato del QR: ejemplo RES5-a1b2c3d4
if (!preg_match('/^RES(\d+)-([a-f0-9]+)$/', $tokenRaw, $matches)) {
    echo json_encode(['success' => false, 'error' => 'Formato QR inválido']);
    exit;
}

$idDelQR = intval($matches[1]); // ID del que generó el QR
$token = $matches[2];

// Verificar que el token e ID del QR existan en la base de datos
$stmt = mysqli_prepare($conexion, "SELECT id FROM datos_residente WHERE id = ? AND token = ?");
mysqli_stmt_bind_param($stmt, "is", $idDelQR, $token);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (!$fila = mysqli_fetch_assoc($resultado)) {
    echo json_encode(['success' => false, 'error' => 'QR inválido']);
    exit;
}

// Obtener fecha y hora actuales
$fecha = date('Y-m-d');
$hora = date('H:i:s');
$tipo = 'QR';

// Registrar acceso del que ESCANEA
$stmtInsert = mysqli_prepare($conexion, "INSERT INTO historial_accesos (id_residente, fecha, hora, tipo) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmtInsert, "isss", $idEscaneador, $fecha, $hora, $tipo);
$exito = mysqli_stmt_execute($stmtInsert);

if ($exito) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo registrar']);
}
?>
