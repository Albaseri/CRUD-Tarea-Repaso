<?php

use App\Db\Articulo;
use App\Db\Categoria;

session_start();
require_once __DIR__ . "/../vendor/autoload.php";

Categoria::generarCategorias(5);
Articulo::generarArticulos(5);
$articulos = Articulo::read();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body style="background-color: white">
    <div class="container p-12 mx-auto">
        <!-- Cargamos listado de artículos -->
        <h3 class="my-2 text-xl text-center mt-10 font-bold">LISTADO DE ARTÍCULOS</h3>

        <!-- Botón para crear nuevo artículo -->
        <div class="mb-2 flex flex-row-reverse">
            <a href="create.php" class=" bg-pink-800 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded"><i class="fas fa-add mr-2"></i>Nuevo Artículo</a>
        </div>

        <!-- TABLA -->

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center"">
            <thead class=" text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Disponibilidad
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Categoría
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($articulos as $item) {
                        $color = ($item->disponible == "SI") ? "text-green-400" : "text-red-400";

                        echo <<<TXT
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="{$item->imagen}" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{$item->nombre}</div>
                            </div>
                        </th>
                       
                        <td class="px-6 py-4 $color">
                        {$item->disponible}
                        </td>
                        
                        <td class="px-6 py-4">
                        {$item->catNombre}
                        </td>
                        
                        <td class="px-6 py-4">
                        {$item->precio} €
                        </td>
                        
                        <td class="px-6 py-4">
                            <form action="delete.php" method="POST">
                                <input type="hidden" name="id" value="{$item->id}">
                                <a href="detalle.php?id={$item->id}"><i class="fas fa-info text-blue-400 mr-2"></i></a>
                                <a href="update.php?id={$item->id}"><i class="fas fa-edit text-yellow-400 mr-2"></i></a>
                                <button type="submit"><i class="fas fa-trash text-red-400"></i></button>
                            </form>
                        </td>
                    </tr>
                    TXT;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- FIN DE TABLA -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo <<<TXT
    <script>
    Swal.fire({
        icon: "success",
        title: "{$_SESSION['mensaje']}",
        showConfirmButton: false,
        timer: 1500
      });
    </script>
    TXT;
            unset($_SESSION['mensaje']);
        }
        ?>
</body>

</html>