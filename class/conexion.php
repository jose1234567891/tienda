<?php
$usuario = 'root';
$contraseña = '';
try {
    $mbd = new PDO('mysql:host=localhost;dbname=mitienda', $usuario, $contraseña);
    /*
    foreach($mbd->query('SELECT * from FOO') as $fila) {
        print_r($fila);
    }
    $mbd = null;
    */
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
