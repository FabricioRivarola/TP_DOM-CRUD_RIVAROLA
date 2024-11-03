<?php
require './config.php';

// Verificar que se ha recibido el ID
if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    
    // Consultar la base de datos para obtener la remera
    $sql = "SELECT r.id, r.nombre, r.genero, r.marca, r.fecha_creacion, r.fecha_ingreso, r.valor 
            FROM remeras AS r WHERE r.id = '$id'";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $remera = $result->fetch_assoc();
        echo json_encode($remera);
    } else {
        echo json_encode(['error' => 'No se encontró la remera.']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado.']);
}
?>
