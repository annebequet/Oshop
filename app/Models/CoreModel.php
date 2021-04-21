<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;

    protected static $tableName = '';


    abstract public function update();
    abstract public function insert();

    
    //*a été mutualisé
    ////abstract public function delete();

    //*a été mutualisé
    ////abstract public static function find();
    //*a été mutualisé
    ////abstract public static function findAll();



    public function delete()
    {
        $sql =
        "
            DELETE FROM ".static::$tableName."
            WHERE id = :id
        ";
        return static::execute($sql, [':id' => $this->getId()]);
    }


    public static function execute($sql, $parameters = [])
    {
        //!Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();
        $preparedStatement = $pdo->prepare($sql);

        //?voir les commentaire dans la fonction queryAndFetch
        foreach($parameters as $placeholder => $value) {
            $preparedStatement->bindValue($placeholder, $value);
        }

        $success = $preparedStatement->execute();
        if ($success) {
            return true;
        }
        return false;
    }



    public static function find($id)
    {
        $sql = "
            SELECT * FROM `".static::$tableName."`
            WHERE id=:id
        ";
        return static::queryAndFetch($sql, [':id' => $id]);
    }

    public static function findAll()
    {
        return static::queryAndFetchAll(
            "SELECT * FROM `" . static::$tableName ."`"
        );
    }


    //!cette methode nous permet d'exécuter une requête et de récupérer UNE une ligne de résultat (sous la forme d'un objet du Model)
    public static function queryAndFetch($sql, $parameters = [])
    {
        //!récupération de l'objet PDO
        $pdo = Database::getPDO();

        //!création de la requête préparée
        $preparedStatement = $pdo->prepare($sql);

        //*placeholder : par exemple :email/:name/:id....
        foreach($parameters as $placeholder => $value) {
            //!pour chaque paramètre de la requête, nous bindons les bonnes valeurs dans les ":variableName"
            $preparedStatement->bindValue($placeholder, $value);
        }

        //!execution de la requête
        $success = $preparedStatement->execute();


        if($success) {
            //!si la requête s'est bien passé, récupération du résultat
            $result = $preparedStatement->fetchObject(static::class);
            return $result;
        }
        //!sinon return false
        else {
            return false;
        }
    }


    public static function queryAndFetchAll($sql, $parameters = [], $class = null)
    {
        if($class === null) {
            $class = static::class;
        }
        $pdo = Database::getPDO();
        $pdoStatement = $pdo->prepare($sql);
        foreach($parameters as $name => $value) {
            $pdoStatement->bindValue($name, $value);
        }
        $pdoStatement->execute();

        $objects = $pdoStatement->fetchAll(PDO::FETCH_CLASS, $class);
        
        return $objects;
    }


    public function save()
    {
        if($this->getId()) {
            return $this->update();
        }
        else {
            return $this->insert();
        }
    }



    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }
}
