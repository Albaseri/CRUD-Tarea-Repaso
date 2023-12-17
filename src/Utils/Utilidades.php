<?php


namespace App\Utils;

use App\Db\Articulo;
use App\Db\Categoria;

const MAY_ON = 0;
const MAY_OFF = 1;

class Utilidades
{



    public static function sanearCadenas(string $cadena, $mode = MAY_OFF)
    {
        return ($mode == MAY_ON) ? ucfirst(htmlspecialchars(trim($cadena))) : htmlspecialchars(trim($cadena));
    }

    public static function errorCadenaTexto($campo, $valor, $longitud)
    {
        if (strlen($valor) < $longitud) {
            $_SESSION[$campo] = "El campo $campo debe tener al menos $longitud caracteres";
            return true;
        }
        return false;
    }


    public static function errorNombreDuplicado($campo, $nombre, $id = null)
    {
        if (Articulo::existeNombre($nombre, $id)) {
            $_SESSION[$campo] = "El nombre introducido ya está registrado en la BD";
            return true;
        }

        return false;
    }

    public static function errorCampoNumerico($campo, $valor, $min, $max)
    {
        if ($valor < $min || $valor > $max) {
            $_SESSION[$campo] = "El campo $campo debe estar entre $min y $max";
            return true;
        }

        return false;
    }

    public static function errorExisteCategoria($campo, $id)
    {
        if (!Categoria::existeIdCategoria($id)) {
            $_SESSION[$campo] = "Error, debe escoger una categoría";
            return true;
        }

        return false;
    }

    // Array tipos Mime
    static array $tiposMime = [

        'image/gif',
        'image/png',
        'image/jpeg',
        'image/bmp',
        'image/webp'
    ];

    public static function errorTipoTamanioImagen($tipo, $tamanio)
    {
        if (!in_array($tipo, self::$tiposMime)) {
            $_SESSION['imagen'] = "El tipo de archivo subido es inválido";
            return true;
        }
        if ($tamanio > 2000000) {
            $_SESSION['imagen'] = "El tamaño de la imagen no puede ser superior a los 2MB";
            return true;
        }
    }

    public static function mostrarErrores($err)
    {
        if (isset($_SESSION[$err])) {
            echo "<p class='italic text-red-600 ml-2 mt-2'>$_SESSION[$err]</p>";
            unset($_SESSION[$err]);
        }
    }
}
