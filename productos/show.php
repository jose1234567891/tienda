<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//print_r($_SESSION);exit;

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");
//print_r($_GET);exit;

//validar lla varaible GET id
if (isset($_GET["id"])) {

    //recuperer el dato que viene en la variable id
    $id = (int) $_GET["id"]; //transforma el dato GET a entero

    // print_r($id);exit;

    //consultar si hay una persona con el id enviado por GET
    $res = $mbd->prepare("SELECT p.id, p.sku, p.nombre, p.precio, m.nombre as marca, pt.nombre as producto_tipo, p.created_at, p.updated_at FROM productos as p INNER JOIN marcas as m ON p.marca_id = m.id INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $producto = $res->fetch();

    //preguntar si la persona tiene un usuario
    $res = $mbd->prepare("SELECT id, activo FROM productos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    //"act" = Estado del producto
    $productos = $res->fetch();

    // print_r($productos);exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Productos</title>

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
            <div class="col-md-6 offset-md-3">
                <h1>Productos</h1>
                <!-- mensaje de registro de la persona -->
                <?php if (isset($_GET["m"]) &&  $_GET["m"] == "ok") : ?>
                    <div class="alert alert-success">
                        El producto se ha modificado correctamente
                    </div>
                <?php endif; ?>

                <?php include('../partials/mensajes.php'); ?>

                <!-- listar los roles que estan registrados -->
                <?php if ($producto) : ?>
                    <table class="table table-hover">
                        <tr>
                            <th>Sku:</th>
                            <td><?php echo $producto['sku']; ?></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><?php echo $producto["nombre"]; ?></td>
                        </tr>
                        <tr>
                            <th>Precio:</th>
                            <td><?php echo $producto["precio"]; ?></td>
                        </tr>
                        <th>Estado:</th>
                            <td>
                                <?php if (!empty($productos) && $productos['activo'] == 1) : ?>
                                    Activo
                                <?php else : ?>
                                    Inactivo
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Marca:</th>
                            <td><?php echo $producto["marca"]; ?></td>
                        </tr>
                        <tr>
                            <th>Producto tipo:</th>
                            <td><?php echo $producto["producto_tipo"]; ?></td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>
                                <?php
                                $fecha = new DateTime($producto["created_at"]);
                                echo $fecha->format("d-m-y h:i:s");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>
                                <?php
                                $fecha = new DateTime($producto["updated_at"]);
                                echo $fecha->format("d-m-y h:i:s");
                                ?>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <a href="index.php" class="btn btn-link">Volver</a>
                        <a href="edit.php?id=<?php echo $id ?>" class="btn btn-primary">Editar</a>
                    </p>
                <?php else : ?>
                    <p class="text-info">El dato solicitado no existe</p>
                <?php endif; ?>
            </div>
        </section>
        <!-- pie de pagina -->
        <footer>
            footer
        </footer>
    </div>
</body>

</html>