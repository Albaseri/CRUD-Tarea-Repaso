<?php

use App\Db\Articulo;

if (!isset($_POST['id'])) {
    header("Location:index.php");
    die();
}

session_start();
require_once __DIR__ . "/../vendor/autoload.php";

$id = $_POST['id'];
$articulo = Articulo::detalle($id);

//Si no existe el artículo, volvemos al index
if(!$articulo){
    header("Location:index.php");
    die();
}

if (basename($articulo->imagen) != "default.jpg") {
    //Si la imagen no es la por defecto, la borro
    unlink("./" . $articulo->imagen);
}

Articulo::delete($id);

$_SESSION['mensaje'] = "El artículo ha sido eliminado con éxito";
header("Location:index.php");
