<?php

use App\Db\Articulo;

if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}
require_once __DIR__ . "/../vendor/autoload.php";

$id = $_GET['id'];
$articulo = Articulo::detalle($id);

//Si no existe el artículo, volvemos al index
if(!$articulo){
    header("Location:index.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Detalle</title>
</head>

<body style="background-color:white">
    <h3 class="my-2 text-xl text-center mt-20 font-bold">DETALLE DE ARTÍCULOS</h3>

    <!-- CARD DETALLE -->
    <div class="mx-auto max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <img class="rounded-t-lg" src="<?php echo "./{$articulo->imagen}" ?>" alt="<?php echo $articulo->nombre ?>" />

        <div class="p-5">
            <h5 class="mb-2 font-xl font-bold tracking-tight text-gray-900 dark:text-white text-center"><?php echo $articulo->nombre ?></h5>


            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <span class=" text-blue-300">Categoria:</span> <?php echo $articulo->catNombre ?></p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <span class=" text-orange-500">Disponibilidad:</span> <?php echo $articulo->disponible ?></p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                <span class=" text-green-500">Precio: </span><?php echo $articulo->precio ?>€
            </p>

            <a href="index.php" class="center inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-pink-700 rounded-lg hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-pink-300 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">
                <i class="fas fa-home mr-2"></i>INICIO
            </a>
        </div>
    </div>
    <!-- FIN CARD DETALLE -->
</body>

</html>