<?php
session_start();
require_once '../../database/conexion.php';

$id = $_SESSION['id'] ?? null;

if (!$id) {
    header('Location: ../inicio/formulario.html');
    exit;
}

// Obtener historial real de la base de datos
$stmt = $enlace->prepare("SELECT fecha, hora, tipo FROM historial_accesos WHERE id_residente = ? ORDER BY fecha DESC, hora DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

$historial = [];
while ($row = $resultado->fetch_assoc()) {
    $historial[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de accesos</title>
    <link rel="stylesheet" href="../../css/style_6.css">
</head>
<body>
    <div class="container" id="container">
    <h1>Historial de accesos</h1>
    <table class="container-table">
        <tr><th>Fecha</th><th>Hora</th><th>Tipo</th></tr>
        <?php foreach ($historial as $evento): ?>
        <tr>
            <td><?= htmlspecialchars($evento['fecha']) ?></td>
            <td><?= htmlspecialchars($evento['hora']) ?></td>
            <td><?= htmlspecialchars($evento['tipo']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <a id="button-iniciar" href="residente.php">Volver al inicio</a>
    
</body>
</html>
