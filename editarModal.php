<?php
// Conexión a la base de datos
require 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Remera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="modal fade" id="remeraModal" tabindex="-1" aria-labelledby="remeraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remeraModalLabel">Editar Remera</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="remeraForm">
                    <input type="hidden" id="remera_id" name="remera_id" value="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <input type="text" class="form-control" id="genero" name="genero" required>
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_creacion" class="form-label">Fecha de Creación</label>
                        <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="number" class="form-control" id="valor" name="valor" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Lógica para cargar los datos en el modal cuando se selecciona una remera
    function loadRemeraData(id) {
        $.ajax({
            url: 'get_remera.php', // Cambia esto a la ruta donde obtienes los datos
            type: 'GET',
            data: { id: id },
            success: function(data) {
                const remera = JSON.parse(data);
                if (remera) {
                    $('#remera_id').val(remera.id);
                    $('#nombre').val(remera.nombre);
                    $('#genero').val(remera.genero);
                    $('#marca').val(remera.marca);
                    $('#fecha_creacion').val(remera.fecha_creacion);
                    $('#fecha_ingreso').val(remera.fecha_ingreso);
                    $('#valor').val(remera.valor);
                } else {
                    alert('No se encontró la remera.');
                }
            },
            error: function() {
                alert('Error al cargar los datos de la remera.');
            }
        });
    }

    // Función para limpiar el formulario del modal
function limpiarFormulario() {
    $('#remera_id').val('');  // Asegúrate de que el campo id esté vacío
    $('#nombre').val('');
    $('#genero').val('');
    $('#marca').val('');
    $('#fecha_creacion').val('');
    $('#fecha_ingreso').val('');
    $('#valor').val('');
}

// Evento para abrir el modal de "Nueva Remera"
$('#botonNuevo').on('click', function() {
    limpiarFormulario(); // Limpiar el formulario
    $('#remeraModal').modal('show'); // Abrir el modal vacío
});

// Limpiar el formulario cuando se cierra el modal
$('#remeraModal').on('hidden.bs.modal', function () {
    limpiarFormulario();
});


    // Manejar el envío del formulario
    $('#remeraForm').on('submit', function(e) {
        e.preventDefault(); // Prevenir el envío normal del formulario

        const formData = $(this).serialize(); // Serializar los datos del formulario

        $.ajax({
            url: 'guardar.php', // Ruta para guardar la remera
            type: 'POST',
            data: formData,
            success: function(response) {
                const jsonResponse = JSON.parse(response);
                if (jsonResponse.error) {
                    alert(jsonResponse.error);
                } else {
                    alert(jsonResponse.message);
                    $('#remeraModal').modal('hide'); // Cerrar el modal
                    getData(1); // Recargar los datos después de guardar
                }
            },
            error: function() {
                alert('Error en la solicitud al servidor.');
            }
        });
    });
</script>

</body>
</html>
