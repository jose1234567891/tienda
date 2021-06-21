<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");

//creamos la consulta a la tabla de imagens ordenado de manera ascendente para usar esos datos
$res = $mbd->query("SELECT i.id, i.titulo, i.imagen, i.activo, i.portada, p.nombre as producto, m.nombre as marca FROM imagenes as i INNER JOIN productos as p ON i.producto_id = p.id INNER JOIN marcas as m ON p.marca_id = m.id");
$imagenes = $res->fetchall(); //pido a PDO que disponibilice todo las marcas registradas

//print_r($marcas);exit;

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 5): ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Imagenes Productos</title>

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
            <div class="col-md-12 offset-md-3">
                <h1>Imagenes Productos</h1>
                <div>
                    <?php foreach ($imagenes as $imagen) : ?>
                        <div class="col-md-3">
                        <img src="<?php echo PRODUCTOS . 'img/' . $imagen['imagen']; ?>" alt="" class="img-thumbnail" style="height:170px">
                        <table class="table table-hover">
                            <tr>
                                <th>Título:</th>
                                <td><?php echo $imagen['titulo']; ?></td>
                            </tr>
                            <tr>
                                <th>Activo:</th>
                                <td>
                                    <?php if($imagen['activo'] == 1): ?>
                                        Si
                                    <?php else: ?>
                                        No
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Portada:</th>
                                <td>
                                    <?php if($imagen['portada'] == 1): ?>
                                        Si
                                    <?php else: ?>
                                        No
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Producto:</th>
                                <td><?php echo $imagen['producto']; ?></td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td><?php echo $imagen['marca']; ?></td>
                            </tr>
                        </table>
                        <a href="show.php?id=<?php echo $imagen['id'] ?>" class="btn btn-link">Ver Detalles</a>
                    </div>
                    
                <?php endforeach; ?>
            </div>
            <?php if($_SESSION['usuario_rol'] == 2): ?>
            <a href="add.php" class="btn btn-primary">Nueva Imagen</a>
            <?php endif; ?>
        </div>
        
    </div>
    
</body>
</html>
<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>