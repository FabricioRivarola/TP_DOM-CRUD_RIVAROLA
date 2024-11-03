<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require './config.php';

$dir = "imagenes/";

// Parámetros de paginación
$num_registros = isset($_POST['registros']) ? (int)$_POST['registros'] : 10;
$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$offset = ($pagina - 1) * $num_registros;

// Modificar la consulta SQL para incluir la paginación y búsqueda
$busqueda = isset($_POST['busqueda']) ? $conn->real_escape_string(trim($_POST['busqueda'])) : '';
$sqlRemeras = "SELECT r.id, r.nombre, r.genero, r.marca, r.fecha_creacion, r.fecha_ingreso, r.valor 
FROM remeras AS r 
WHERE (r.nombre LIKE '%$busqueda%' 
       OR r.genero LIKE '%$busqueda%' 
       OR r.marca LIKE '%$busqueda%' 
       OR r.id = CAST('$busqueda' AS UNSIGNED) 
       OR r.valor = CAST('$busqueda' AS DECIMAL)) 
LIMIT $num_registros OFFSET $offset";



$remeras = $conn->query($sqlRemeras);

// Contar el total de registros
$sqlCount = "SELECT COUNT(*) as total FROM remeras WHERE nombre LIKE '%$busqueda%'";
$resultCount = $conn->query($sqlCount);
$totalRegistros = $resultCount->fetch_assoc()['total'];

$totalPaginas = ceil($totalRegistros / $num_registros);
$paginacion = '<ul class="pagination">';

// Páginas previas
for ($i = 1; $i <= $totalPaginas; $i++) {
    $active = ($i == $pagina) ? 'active' : '';
    $paginacion .= "<li class='page-item $active'><a class='page-link' href='javascript:getData($i)'>$i</a></li>";
}

$paginacion .= '</ul>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacén - Remeras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #036cb6;
        }
        table, th, td {
            border: none;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            border: none;
            border-radius: 0.5rem;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border-radius: 0.5rem;
        }
        .row-custom {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 1.5rem;
            margin: 20px 0;
        }
        .col-auto {
            display: flex;
            align-items: center;
        }
        .form-select, .form-control {
            min-width: 150px;
            border-radius: 0.5rem;
            background-color: white;
        }
        .form-select option {
            background-color: black;
            color: white;
        }
        .pagination {
            list-style: none;
            display: flex;
            gap: 0.5rem;
            padding: 0;
        }
        .page-item .page-link {
            padding: 5px 10px;
            border: 1px solid #a52a2a;
            text-decoration: none;
            color: #fff;
            background-color: #7a1f1f;
            cursor: pointer;
            border-radius: 0.5rem;
        }
        .page-item.active .page-link {
            background-color: #a52a2a;
            color: white;
            border-color: #036cb6;
        }
        .btn-primary {
            background-color: #a52a2a;
            border: 1px;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #7a1f1f;
        }
        .container-custom {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        table tbody tr:hover {
            background-color: #3a3a3a;
        }
        a {
            color: #7a1f1f;
        }
        a:hover {
            color: #a52a2a;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <h2 class="my-4 text-light">Remeras</h2>

        <?php if(isset($_SESSION['msg'])){?>
            <div>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']);?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
            
        <?php
        unset($_SESSION['msg']);
        }
        ?>

        <div class="row-custom">
            <div class="col-auto">
                <label for="num_registros" class="form-label text-light">Mostrar:</label>
            </div>
            <div class="col-auto">
                <select name="num_registros" id="num_registros" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label text-light">Registros</label>
            </div>

            <div class="col-1"></div>
            <div class="col-auto">
                <button class="btn btn-primary" id="botonNuevaRemera" data-bs-toggle="modal" data-bs-target="#remeraModal">Añadir Nueva Remera</button>
            </div>
            <div class="col-1"></div>

            <div class="col-auto">
                <label for="campo" class="form-label text-light">Buscar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="campo" id="campo" class="form-control" placeholder="Buscar remeras...">
            </div>
        </div>

        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Género</th>
                    <th>Marca</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Ingreso</th>
                    <th>Valor</th>
                    <th>Imagen</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="content">
                <?php if ($remeras->num_rows > 0): ?>
                    <?php while ($row = $remeras->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['genero']); ?></td>
                        <td><?php echo htmlspecialchars($row['marca']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_creacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_ingreso']); ?></td>
                        <td><?php echo htmlspecialchars($row['valor']); ?></td>
                        <td>
                            <img src="<?php echo $dir . $row['id'] . '.jpg'; ?>" alt="foto remera" width="100px">
                        </td>

                        <td>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#remeraModal"
                                    onclick="cargarDatos(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nombre']); ?>', '<?php echo addslashes($row['genero']); ?>', '<?php echo addslashes($row['marca']); ?>', '<?php echo $row['fecha_creacion']; ?>', '<?php echo $row['fecha_ingreso']; ?>', <?php echo $row['valor']; ?>)">
                                Editar
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="abrirEliminarModal(<?php echo $row['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No hay registros disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php include 'modal_remera.php'; ?>

        <div class="row">
            <div class="col text-center">
            <p class="text-light">Mostrando <?php echo $remeras->num_rows ?> de <?php echo $totalRegistros ?> registros</p>
            <?php echo $paginacion; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarModalLabel">Eliminar Remera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta remera?
                    <input type="hidden" id="eliminar_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="deleteRemera()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#num_registros').change(function() {
                getData(1);
            });

            $('#campo').on('input', function() {
                getData(1);
            });

            getData(1);
        });

        function getData(pagina) {
            const numRegistros = $('#num_registros').val();
            const busqueda = $('#campo').val();

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    registros: numRegistros,
                    pagina: pagina,
                    busqueda: busqueda
                },
                success: function(response) {
                    const $response = $(response);
                    $('#content').html($response.find('#content').html());
                    $('.pagination').html($response.find('.pagination').html());
                },
                error: function() {
                    alert('Error al cargar los datos.');
                }
            });
        }

        // Cargar datos para editar
        function cargarDatos(id, nombre, genero, marca, fecha_creacion, fecha_ingreso, valor) {
            $('#remera_id').val(id);
            $('#nombre').val(nombre);
            $('#genero').val(genero);
            $('#marca').val(marca);
            $('#fecha_creacion').val(fecha_creacion);
            $('#fecha_ingreso').val(fecha_ingreso);
            $('#valor').val(valor);
            $('#foto').val(foto);
        }

        // Limpiar campos para crear nueva remera
        $('#botonNuevaRemera').click(function() {
            $('#remera_id').val('');
            $('#nombre').val('');
            $('#genero').val('');
            $('#marca').val('');
            $('#fecha_creacion').val('');
            $('#fecha_ingreso').val('');
            $('#valor').val('');
            $('#foto').val('');
        });

        // Abrir el modal para eliminar
        function abrirEliminarModal(id) {
            $('#eliminar_id').val(id);
            $('#eliminarModal').modal('show');
        }

        // Confirmar eliminación
        function deleteRemera() {
            const id = $('#eliminar_id').val();
            $.ajax({
                url: 'eliminar.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    $('#eliminarModal').modal('hide');
                    getData(1); // Actualizar la lista después de eliminar
                },
                error: function() {
                    alert('Error al eliminar la remera.');
                }
            });
        }
    </script>
</body>
</html>