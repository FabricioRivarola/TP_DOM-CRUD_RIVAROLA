<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php';

// Definición de columnas y tabla
$columns = ['id', 'nombre', 'genero', 'marca', 'fecha_creacion', 'fecha_ingreso', 'valor', 'fotos'];
$table = "remeras";
$id = 'id';

// Captura del campo de búsqueda
$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;

$where = '';

// Construcción de la cláusula WHERE
if ($campo != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $campo . "%' OR ";
    }
    $where = substr_replace($where, "", -3); // Eliminar el último ' OR '
    $where .= ")";
}

/* Límite */
$limit = isset($_POST['registros']) ? (int)$conn->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? (int)$conn->real_escape_string($_POST['pagina']) : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$inicio = ($pagina - 1) * $limit;

$sLimit = "LIMIT $inicio, $limit";

/* Consulta Principal */
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM $table $where $sLimit";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

/* Consulta Total Filtrados */
$sqlFiltro = "SELECT FOUND_ROWS() AS total";
$resFiltro = $conn->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_assoc();
$totalFiltro = $row_filtro['total'];

/* Consulta Total Registros */
$sqlTotal = "SELECT COUNT($id) AS total FROM $table";
$resTotal = $conn->query($sqlTotal);
$row_Total = $resTotal->fetch_assoc();
$totalRegistros = $row_Total['total'];

/* Construcción de la Respuesta */
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['numRows'] = $num_rows; // Cantidad de registros mostrados
$output['data'] = '';
$output['paginacion'] = '';

if (!$resultado) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit;
}

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // Verificar si 'fotos' tiene un valor, de lo contrario usar imagen por defecto
        $foto = !empty($row['fotos']) ? htmlspecialchars($row['fotos']) : 'imagenes/default.jpg';

        $output['data'] .= "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nombre']}</td>
            <td>{$row['genero']}</td>
            <td>{$row['marca']}</td>
            <td>{$row['fecha_creacion']}</td>
            <td>{$row['fecha_ingreso']}</td>
            <td>{$row['valor']}</td>
            <td><img src='{$foto}' alt='foto remera' width='100px'></td>
            <td>
                <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#remeraModal'
                        onclick=\"cargarDatos({$row['id']}, '" . addslashes($row['nombre']) . "', '" . addslashes($row['genero']) . "', '" . addslashes($row['marca']) . "', '{$row['fecha_creacion']}', '{$row['fecha_ingreso']}', {$row['valor']})\">
                    Editar
                </button>
            </td>
            <td>
                <button class='btn btn-danger' onclick='abrirEliminarModal({$row['id']})'>Eliminar</button>
            </td>
        </tr>";
    }
} else {
    $output['data'] .= '<tr><td colspan="10">Sin resultados</td></tr>'; 
}

// Paginación
if ($totalRegistros > 0) {
    $totalPaginas = ceil($totalRegistros / $limit);

    $output['paginacion'] .= '<nav>';
    $output['paginacion'] .= '<ul class="pagination">';

    $numeroInicio = 1;

    if (($pagina - 4) > 1) {
        $numeroInicio = $pagina - 4;
    }

    $numeroFin = $numeroInicio + 9;

    if ($numeroFin > $totalPaginas) {
        $numeroFin = $totalPaginas;
    }

    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {
        if ($pagina == $i) {
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="getData(' . $i . ')">' . $i . '</a></li>';
        }
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

// Enviar respuesta JSON
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
