<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

//lista de productos
$res = $mbd->query("SELECT id, nombre FROM productos ORDER BY nombre");
$productos = $res->fetchall();

//validar que los datos del formulario lleguen via post
if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
    # code...
    //print_r($_POST);
    $titulo = trim(strip_tags($_POST["titulo"]));
    $imagen = $_FILES["imagen"]["name"];
    $dir_tmp = $_FILES["imagen"]["tmp_name"];
    $descripcion = trim(strip_tags($_POST["descripcion"]));
    $producto = (int) $_POST["producto"];

    if (strlen($titulo) < 5) {
        $msg = "Debe ingresar un título de almenos 5 caracteres";
    } elseif (strlen($descripcion) < 10) {
        $msg = "Ingrese una descripcion de almenos 10 caracteres";
    } elseif ($producto <= 0) {
        $msg = "Seleccione un producto";
    } elseif (!$imagen) {
        $msg = "Ingrese una imagen";
    } elseif ($_FILES["imagen"]["type"] != "image/jpeg") {
        $msg = "La imagen no es valida";
    } elseif ($_FILES["imagen"]["size"] > 50000) {
        $msg = "El tamaño de la imagen esta exedido";
    } else {
        //preguntar si la imagen ingresada existe en la tabla imagenes
        $res = $mbd->prepare("SELECT id FROM imagenes WHERE imagen = ?");
        $res->bindParam(1, $imagen);
        $res->execute();
        $img = $res->fetch();

        //print_r($marca);exit;
        if ($img) {
            $msg = "La imagen ya existe... intente con otra";
        } else {

            //creamos la ruta de guardado de la imagen en el servidor 
            $upload = $_SERVER["DOCUMENT_ROOT"] . "/mitienda/productos/img/";
            $img_subida = $upload . basename($_FILES["imagen"]["name"]);

            //comprobamos que la imagen se ha subido al servidor
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $img_subida)) {
                //consultar si hay una una imagen del producto seleccionado que sea portada
                $res = $mbd->prepare("SELECT id FROM imagenes HERE producto_id = ? AND portada = 1");
                $res->bindParam(1, $producto);
                $res->execute();

                $img_portada = $res->fetch();
                //portada = 1, no portada = 2
                if ($img_portada) {
                    $portada = 2;
                } else {
                    $portada = 1;
                }
                //preparamos la consulta antes de ser enviada a la base de datos
                $res = $mbd->prepare("INSERT INTO imagenes VALUES(null, ?, ?, ?, 1, ?, ?, now(), now())");
                //sanitizamos el dato indicando cual es la posicion del ? en el orden en el que aparece en la consulta anteriro
                $res->bindParam(1, $titulo);
                $res->bindParam(2, $imagen);
                $res->bindParam(3, $descripcion);
                $res->bindParam(4, $portada);
                $res->bindParam(5, $producto);
                //ejecutamos la consulta sanitizada
                $res->execute();
                //rescatamos el numero de la filas insertadas en la tabla
                $row = $res->rowCount();

                if ($row) {
                    $_SESSION["success"] = "La imagen se ha registrado correctamente";
                    header("Location: index.php");
                }
            }
        }
    }
}

?>

<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 2): ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagenes</title>
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

            <div class="cal-md-6 offset-md-3">
                <h1>Nueva Imagen</h1>

                <!-- mensaje de validacion y errores -->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="titulo">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" value="<?php if (isset($_POST['titulo'])) echo $_POST['titulo']; ?>" class="form-control" placeholder="Ingrese el título de la imagen">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descripcion">descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control text-left" rows="4" placeholder="Ingrese la descripción de la imagen">
                        <?php if (isset($_POST['descripcion'])) echo $_POST['descripcion']; ?>
                    </textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="producto">Producto <span class="text-danger">*</span></label>
                        <select name="producto" class="form-control">
                            <option value="">Seleccione...</option>

                            <?php foreach ($productos as $producto) : ?>
                                <option value="<?php echo $producto['id']; ?>">
                                    <?php echo $producto['nombre']; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="imagen">Imagen <span class="text-danger">*</span></label>
                        <input type="file" name="imagen" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </div>
                </form>
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