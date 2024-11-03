<?php
require './config.php';
session_start();

if (isset($_POST['nombre'])) {
    $remera_id = $_POST['remera_id'] ?? null;
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $marca = mysqli_real_escape_string($conn, $_POST['marca']);
    $fecha_creacion = mysqli_real_escape_string($conn, $_POST['fecha_creacion']);
    $fecha_ingreso = mysqli_real_escape_string($conn, $_POST['fecha_ingreso']);
    $valor = mysqli_real_escape_string($conn, $_POST['valor']);
    $foto = '';

    if ($_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $permitidos = array("image/jpg", "image/png", "image/jpeg");
        if (in_array($_FILES['foto']['type'], $permitidos)) {
            $dir = "imagenes";
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            // Genera un nombre único para la imagen
            $nombre_foto = uniqid() . '.jpg'; // Solo guarda el nombre del archivo
        $foto = $nombre_foto; // Almacena solo el nombre, sin la ruta
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dir . '/' . $foto)) {
            $_SESSION['msg'] .= "<br>Error al guardar la imagen";
        }
        } else {
            $_SESSION['msg'] .= "<br>Formato de imagen no válido";
        }
    }

    if (empty($remera_id)) {
        // Inserción
        $sql = "INSERT INTO remeras (nombre, genero, marca, fecha_creacion, fecha_ingreso, valor, foto)
                VALUES ('$nombre', '$genero', '$marca', '$fecha_creacion', '$fecha_ingreso', '$valor', '$foto')";
    } else {
        // Actualización
        $sql = "UPDATE remeras SET 
                nombre = '$nombre',
                genero = '$genero',
                marca = '$marca',
                fecha_creacion = '$fecha_creacion',
                fecha_ingreso = '$fecha_ingreso',
                valor = '$valor'";

        if ($foto) { // Solo actualiza la foto si se ha subido una nueva
            $sql .= ", foto = '$foto'";
        }
        
        $sql .= " WHERE id = '$remera_id'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['msg'] = "Registro guardado correctamente";
    } else {
        $_SESSION['msg'] = "Error al guardar el registro: " . $conn->error;
    }
    
    // Redirigir a la página de registro o mostrar mensaje
    header('Location: index.php'); // Reemplaza 'tu_pagina.php' con la página que deseas
    exit();
}
?>
