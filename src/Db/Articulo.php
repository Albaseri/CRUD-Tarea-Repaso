<?php

namespace App\Db;

use PDO;
use PDOException;

class Articulo extends Conexion
{


    private int $id;
    private string $nombre;
    private string $disponible;
    private string $precio;
    private string $imagen;
    private int $category_id;

    public function __construct()
    {
        parent::__construct();
    }



    //-------------------CRUD----------------------


    public function create()
    {
        $q = "INSERT INTO articulos (nombre, disponible, precio, imagen, category_id) values(:n,:d,:p,:im,:c)";

        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->disponible,
                ':p' => $this->precio,
                ':im' => $this->imagen,
                ':c' => $this->category_id,
            ]);
        } catch (PDOException $ex) {
            die("Error en create artículos: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function read()
    {
        parent::setConexion();
        $q = "SELECT articulos.*, categorias.nombre as catNombre from articulos,categorias where category_id=categorias.id";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en read articulos: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function detalle($id)
    {
        parent::setConexion();

        $q = "SELECT articulos.*, categorias.nombre as catNombre from articulos, categorias where category_id = categorias.id and articulos.id = :i";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error en detalle de artículo: " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update($id)
    {
        $q = "UPDATE articulos set nombre=:n, disponible=:d,precio=:p,imagen=:im,category_id=:c where id=:i ";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->disponible,
                ':p' => $this->precio,
                ':im' => $this->imagen,
                ':c' => $this->category_id,
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error en update articulos " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function delete($id)
    {
        parent::setConexion();
        $q = "DELETE from articulos where id=:i";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            die("Error en delete artículos: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    //-------------------FAKER----------------------

    public static function hayArticulos()
    {
        parent::setConexion();

        $q = "SELECT * from articulos";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayArticulos: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }


    public static function generarArticulos($cant)
    {
        if (self::hayArticulos()) return;

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));

        for ($i = 0; $i < $cant; $i++) {

            $nombre = ucfirst($faker->unique()->word());
            $disponible = $faker->randomElement(["SI", "NO"]);
            $precio = $faker->randomFloat(2, 5, 999.99);
            $imagen = "img/" . $faker->picsum(dir: "./img", width: 640, height: 480, fullPath: false);
            $category_id = $faker->randomElement(Categoria::devolverIdCategoria())->id;

            (new Articulo)
                ->setNombre($nombre)
                ->setDisponible($disponible)
                ->setPrecio($precio)
                ->setImagen($imagen)
                ->setCategoryId($category_id)
                ->create();
        }
    }




    //-------------------OTROS----------------------
    public static function existeNombre(string $nombre, $id = null): bool
    {
        parent::setConexion();
        //comprobar si el nombre ya está registrado
        $q = ($id == null) ? "SELECT * from articulos where nombre=:n" : "SELECT * from articulos where nombre=:n AND id!=:i ";
        $options = ($id == null) ? [':n' => $nombre] : [':n' => $nombre, ':i' => $id];

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute($options);
        } catch (PDOException $ex) {
            die("Error en existeNombre" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }



    //-------------------SETTERS----------------------
    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }



    /**
     * Set the value of category_id
     */
    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}
