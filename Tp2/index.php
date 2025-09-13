<html>
<head>
    <title>App Asistencias</title>
    <meta charset="utf-8"></meta>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        form {
            width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0077cc;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f0ff;
        }

        .btn {
            padding: 10px 20px;
            margin: 10px 5px 0 5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .btn-guardar {
            background-color: #28a745;
            color: #fff;
        }

        .btn-borrar {
            background-color: #dc3545;
            color: #fff;
        }

        .mensaje {
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            color: #155724;
            background-color: #d4edda;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>
    <form method="POST">
        <h1>Toma De Asistencias</h1>

        <?php 
            require_once('./php/Funciones.php');
            $Raiz = './files/';
            $RutaAlumnos = $Raiz.'alumnos.json';
            $mensaje = "";

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $fechaInput = !empty($_POST['fecha']) ? $_POST['fecha'] : date("Y-m-d");
                $fecha = date("dmY", strtotime($fechaInput));
                $RutaAsistencia = $Raiz."asistencia_".$fecha.".json";

                if(isset($_POST['guardar'])) {
                    if (file_exists($RutaAlumnos)) {
                        $json = file_get_contents($RutaAlumnos);
                        $data = json_decode($json, true);

                        if (isset($data["Alumnos"])) {
                            $presentes = isset($_POST['presente']) ? $_POST['presente'] : [];

                            foreach ($data["Alumnos"] as &$alumno) {
                                $alumno["asistencia"] = in_array($alumno["apellido"], $presentes) ? "p" : "a";
                            }

                            EscribirArchivo($RutaAsistencia, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                            $mensaje = "Asistencia guardada correctamente";
                        }
                    }
                }

                if(isset($_POST['borrar'])) {
                    if(file_exists($RutaAsistencia)) {
                        unlink($RutaAsistencia);
                        $mensaje = "Archivo de asistencia borrado correctamente";
                    } else {
                        $mensaje = "No existe un archivo de asistencia para la fecha seleccionada";
                    }
                }
            }
        ?>
        
        <center>
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d'); ?>">
        </center>
        <strong><u>Alumnos</u></strong>

        <table>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Presente</th>
            </tr>

            <?php 
                if(file_exists($RutaAlumnos)){
                    $json = file_get_contents($RutaAlumnos);
                    $data = json_decode($json, true);

                    if(isset($data["Alumnos"])){
                        foreach($data["Alumnos"] as $alumno){
                            echo "<tr>";
                            echo "<td>".$alumno["apellido"]."</td>";
                            echo "<td>".$alumno["nombre"]."</td>";
                            $checked = ($alumno["asistencia"] ?? "a") === "p" ? "checked" : "";
                            echo "<td><input type='checkbox' name='presente[]' value='".$alumno["apellido"]."' $checked></td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontró el archivo alumnos.json</td></tr>";
                }
            ?>
        </table>
        <center>
        <button type="submit" name="guardar" class="btn btn-guardar">Registrar</button>
        <button type="submit" name="borrar" class="btn btn-borrar">Borrar asistencia</button>
        </center>
        <?php
            if(!empty($mensaje)){
                echo "<div class='mensaje'>$mensaje</div>";
            }
        ?>
    </form>
</body>
</html>
