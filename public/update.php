<?php

use App\Db\Articulo;
use App\Db\Categoria;
use App\Utils\Utilidades;

use const App\Utils\MAY_ON;

if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}

session_start();
require_once __DIR__ . "/../vendor/autoload.php";

$id = $_GET['id'];
$articulo = Articulo::detalle($id);

//Si no existe el artículo, volvemos al index
if (!$articulo) {
    header("Location:index.php");
    die();
}

$categorias = Categoria::read();

if (isset($_POST['btn'])) {

    $nombre = Utilidades::sanearCadenas($_POST['nombre'], MAY_ON);
    $precio = (int)trim($_POST['precio']);
    $categoria = Utilidades::sanearCadenas($_POST['categoria'], MAY_ON);
    $disponible = (isset($_POST['disponible'])) ? "SI" : "NO";

    $errores = false;

    if (Utilidades::errorCadenaTexto('nombre', $nombre, 3)) {
        $errores = true;
    }

    if (Utilidades::errorNombreDuplicado('nombre', $nombre, $id)) {
        $errores = true;
    }

    if (Utilidades::errorCampoNumerico('precio', $precio, 5, 999.99)) {
        $errores = true;
    }
    if (Utilidades::errorExisteCategoria('categoria', $categoria)) {
        $errores = true;
    }


    if (!$errores) {
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            //Comprobamos que sea una imagen y no exceda los 2MB
            if (Utilidades::errorTipoTamanioImagen($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
                $errores = true;
            } else {
                //Imagen y tamaño válido procedemos a guardarla
                $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name'];

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], "./" . $imagen)) {
                    $_SESSION['imagen'] = "No se ha podido subir la imagen";
                    $errores = true;
                } else {
                    if (basename($articulo->imagen) != "default.jpg") {
                        //Si la imagen no es la por defecto, la borro
                        unlink("./" . $articulo->imagen);
                    }
                }
            }
        } else {
            $imagen = $articulo->imagen;
        }
    }


    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }
    (new Articulo)->setNombre($nombre)
        ->setDisponible($disponible)
        ->setPrecio($precio)
        ->setImagen($imagen)
        ->setCategoryId($categoria)
        ->update($id);

    $_SESSION['mensaje'] = "Artículo actualizado con éxito";
    header("Location:index.php");
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
    <title>Actualizar</title>
</head>

<body style="background-color:white">
    <h3 class="my-2 text-xl text-center mt-10 font-bold">ACTUALIZAR ARTÍCULO</h3>
    <div class="w-1/2 mx-auto p-4 bg-gray-500 rounded-xl shadow-xl">

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?id=$id" ?>" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $articulo->nombre ?>" id="nombre" placeholder="Nombre..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?php
                Utilidades::mostrarErrores('nombre');
                ?>
            </div>

            <div class="mb-6">
                <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Precio:</label>
                <input type="number" value="<?php echo $articulo->precio ?>" id="precio" name="precio" placeholder="Precio..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" step="0.01" min="0" />
                <?php
                Utilidades::mostrarErrores('precio');
                ?>
            </div>

            <div class="mb-6">
                <label for="categoria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Categoría:</label>
                <select name="categoria" id="categoria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>~Selecciona una categoría~</option>
                    <?php
                    foreach ($categorias as $item) {
                        $seleccionada = ($item->id == $articulo->category_id) ? "selected" : "";
                        echo "<option $seleccionada value='{$item->id}'>{$item->nombre}</option>";
                    }
                    ?>
                </select>
                <?php
                Utilidades::mostrarErrores('categoria');
                ?>
            </div>

            <div class="mb-6">
                <label for="categoria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Disponible:</label>
                <div class="flex items-center mb-4">
                    <input id="checkbox" type="checkbox" name="disponible" <?php echo ($articulo->disponible == "SI") ? "checked" : "" ?> class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">SI</label>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex w-full">
                    <div class="w-1/2 mr-2">
                        <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Imagen</label>
                        <input type="file" id="imagen" oninput="img.src=window.URL.createObjectURL(this.files[0])" name="imagen" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        <?php
                        Utilidades::mostrarErrores('imagen');
                        ?>
                    </div>

                    <div class="ml-8">
                        <img src="<?php echo $articulo->imagen ?>" class="w-60" id="img">
                    </div>
                </div>

                <div class="flex flex-row-reverse mt-5">
                    <button type="submit" class="my-2 bg-blue-400 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" name="btn"><i class="fas fa-save mr-2"></i>ACTUALIZAR</button>
                    <button type="reset" class="mx-2 my-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-paintbrush mr-2"></i>LIMPIAR</button>
                    <a href="index.php" class="my-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-home mr-2"></i>VOLVER</a>

                </div>
        </form>
    </div>
</body>

</html>