<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");

//creamos la consulta a la tabla roles ordenado de manera ascendente para usar esos datos
$res = $mbd->query("SELECT p.id, p.nombre, p.email, r.nombre as rol, c.nombre as comuna FROM personas as p INNER JOIN roles as r ON p.rol_id = r.id INNER JOIN comunas as c ON p.comuna_id = c.id");
$personas = $res->fetchall(); //pido a PDO que disponibilice todo los roles registrados

// echo"<pre>";
// print_r($personas);exit;
// echo"</pre>";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personas</title>

    <!--Enlaces CDM de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <!-- Seccion de cabecera del sitio  -->
        <header>
            <!-- Navegador principal -->
            <?php include("../partials/menu.php"); ?>
        </header>

        <!-- seccion de contenido principal -->
        <section>
            <div class="col-md-10 offset-md-1">
                <h1>Personas</h1>
                <!-- mensaje de registro de la persona -->
                <?php if (isset($_GET["m"]) &&  $_GET["m"] == "ok") : ?>
                    <div class="alert alert-success">
                        La persona se ha registrado correctamente
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET["e"]) &&  $_GET["e"] == "ok") : ?>
                    <div class="alert alert-success">
                        La persona se ha eliminado correctamente
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET["error"]) &&  $_GET["error"] == "error") : ?>
                    <div class="alert alert-danger">
                        La la persona no se ha eliminado.. intente nuevamente
                    </div>
                <?php endif; ?>

                <!-- listar las personas que estan registrados -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>nombre</th>
                            <th>email</th>
                            <th>comuna</th>
                            <th>rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personas as $persona) : ?>
                            <tr>
                                <td>
                                    <a href="show.php?id=<?php echo $persona["id"]; ?>">
                                        <?php echo $persona["nombre"]; ?>
                                </td>
                                </a>
                                <td>
                                    <?php echo $persona["email"]; ?> </td>
                                <td>
                                    <?php echo $persona["comuna"]; ?> </td>
                                <td>
                                    <?php echo $persona["rol"]; ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="add.php" class="btn btn-success">Nueva persona</a>
            </div>
        </section>

        <!-- pie de pagina -->
        <footer>
            footer
        </footer>
    </div>
</body>

</html>