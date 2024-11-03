<?php
require './config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM remeras WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Registro eliminado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al eliminar el registro: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'No se recibió el ID.']);
}
?>
