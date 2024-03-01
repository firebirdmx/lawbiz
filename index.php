<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla de Personajes de Star Wars</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .highlight {
            background-color: yellow !important;
            font-weight: bold;
        }
        .selected-column {
            background-color: black;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="my-4">Tabla de Personajes de Star Wars</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="select_columna">Seleccionar Columna:</label>
                <select class="form-control" id="select_columna">
                    <option value="0">ID</option>
                    <option value="1">Nombre</option>
                    <option value="2">Altura</option>
                    <option value="3">Peso</option>
                    <option value="4">Color de Pelo</option>
                    <option value="5">Color de Piel</option>
                    <option value="6">Color de Ojos</option>
                    <option value="7">Año de Nacimiento</option>
                    <option value="8">Género</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="select_nombre">Seleccionar Nombre:</label>
                <select class="form-control" id="select_nombre">
                    <option value="">Todos</option>
                    <?php
                    $url = "https://swapi.dev/api/people/";
                    $data = array();
                    
                    do {
                        $json = file_get_contents($url);
                        $result = json_decode($json, true);
                        $data = array_merge($data, $result['results']);
                        $url = $result['next'];
                    } while ($url != null);
                    
                    foreach ($data as $personaje) {
                        echo "<option value='" . $personaje['name'] . "'>" . $personaje['name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <table id="tabla_personajes" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Altura</th>
                <th>Peso</th>
                <th>Color de Pelo</th>
                <th>Color de Piel</th>
                <th>Color de Ojos</th>
                <th>Año de Nacimiento</th>
                <th>Género</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $contador = 1;
            foreach ($data as $personaje) {
                echo "<tr>";
                echo "<td>" . $contador . "</td>";
                echo "<td>" . $personaje['name'] . "</td>";
                echo "<td>" . $personaje['height'] . "</td>";
                echo "<td>" . $personaje['mass'] . "</td>";
                echo "<td>" . $personaje['hair_color'] . "</td>";
                echo "<td>" . $personaje['skin_color'] . "</td>";
                echo "<td>" . $personaje['eye_color'] . "</td>";
                echo "<td>" . $personaje['birth_year'] . "</td>";
                echo "<td>" . $personaje['gender'] . "</td>";
                echo "</tr>";
                $contador++;
            }
        ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        var tabla = $('#tabla_personajes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
        });

        $('#select_columna').on('change', function () {
            var columnaIndex = $(this).val();
            tabla.order([parseInt(columnaIndex), 'asc']).draw();
            // Remover clase de columna seleccionada de todas las columnas
            $('#tabla_personajes thead tr th').removeClass('selected-column');
            // Agregar clase de columna seleccionada a la columna correspondiente
            $('#tabla_personajes thead tr th:eq(' + columnaIndex + ')').addClass('selected-column');
        });

        $('#select_nombre').on('change', function () {
            var nombre = $(this).val();
            tabla.columns(1).search(nombre).draw();
        });

        // Resaltar todas las palabras buscadas en la tabla
        tabla.on('draw.dt', function () {
            var searchTerm = tabla.search();
            if (searchTerm && searchTerm.length > 0) {
                var terms = searchTerm.split(/\s+/).map(function(term) {
                    return term.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                }).join('|');
                var regex = new RegExp(terms, 'ig');
                tabla.cells().every(function() {
                    var cell = this;
                    var cellData = cell.data();
                    var html = cellData.replace(regex, function(match) {
                        return '<span class="highlight">' + match + '</span>';
                    });
                    $(cell.node()).html(html);
                });
            } else {
                tabla.cells().every(function() {
                    var cell = this;
                    var cellData = cell.data();
                    $(cell.node()).html(cellData);
                });
            }
        });
    });
</script>

</body>
</html>
