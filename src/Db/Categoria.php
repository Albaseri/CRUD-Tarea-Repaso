<?php

namespace App\Db;

use PDO;
use PDOException;

class Categoria extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;


    public function __construct()
    {
        parent::__construct();
    }

    //------------------------------------CRUD--------------------------


    public function create()
    {
        $q = "INSERT INTO categorias (nombre, descripcion) values(:n,:d)";

        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion,

            ]);
        } catch (PDOException $ex) {
            die("Error en create categorias: " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function read()
    {
        parent::setConexion();
        $q = "SELECT * from categorias";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en read de categorias: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    //------------------------------------FAKER--------------------------

    public static function hayCategorias()
    {
        parent::setConexion();

        $q = "SELECT * from categorias";

        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayCategorias: " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }


    public static function generarCategorias($cant)
    {
        if (self::hayCategorias()) return;

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));

        for ($i = 0; $i < $cant; $i++) {

            $nombre = ucfirst($faker->unique()->word());
            $descripcion = $faker->text();


            (new Categoria)
                ->setNombre($nombre)
                ->setDescripcion($descripcion)

                ->create();
        }
    }


    //------------------------------------OTROS--------------------------

    //Devuelve un array de objetos a los que accedo poniendo el id
    public static function devolverIdCategoria(): array
    {
        parent::setConexion();
        $q = "SELECT id from categorias";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al devolverID Categorias" . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function existeidCategoria($idCategoria)
    {
        parent::setConexion();
        $q = "SELECT id FROM categorias WHERE id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':i' => $idCategoria]);
        } catch (PDOException $ex) {
            die("Error en existeidCategorias") . $ex->getMessage();
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }

    
    //------------------------------------SETTERS--------------------------


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
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}
