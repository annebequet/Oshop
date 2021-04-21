<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel {

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    protected static $tableName = 'category';



    public function update()
    {
        $sql = "
            UPDATE `category`
            SET
                `name` = :name,
                `subtitle` = :subtitle,
                `picture` = :picture,
                `home_order` = :home_order,
                `updated_at` = NOW()
            WHERE
                `id` = :id
        ";

        return  static::execute($sql, [
            ':name' => $this->getName(),
            ':subtitle' => $this->getSubtitle(),
            ':picture' => $this->getPicture(),
            ':home_order' => $this->getHomeOrder(),
            ':id'  => $this->getId()
        ]);
    }

    public static function resetHomeOrder()
    {
        //!remarquons qu'il n'y a oas de condition where car nous souhaitons updater toutes les lignes de la table category
        $sql = "
            UPDATE `" . static::$tableName . "`
            SET
                `home_order` = 0
        ";
        return static::execute($sql);
    }


    public function insert()
    {
        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `category` (
                `name`,
                `subtitle`,
                `picture`
            )
            VALUES (
                :name,
                :subtitle,
                :picture
            )
        ";
        $success = static::execute($sql, [
            ':name' => $this->getName(),
            ':subtitle' => $this->getSubtitle(),
            ':picture' => $this->getPicture()
        ]);

        // Si au moins une ligne ajoutée
        if ($success) {
            $pdo = Database::getPDO();
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            //?https://www.php.net/manual/fr/pdo.lastinsertid.php
            $this->id = $pdo->lastInsertId();
            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }


    /**
     * Récupérer les 5 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public static function findAllHomepage()
    {
        $sql = '
            SELECT *
            FROM `' . static::$tableName . '`
            WHERE home_order > 0
            ORDER BY home_order ASC
            LIMIT 5 
        ';
        return static::queryAndFetchAll($sql);
    }



    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        //?htmlentities permet d'échapper les caractère spéciaux "html"
        //?https://www.php.net/htmlentities
        //?https://www.php.net/manual/fr/function.htmlspecialchars.php
        return htmlspecialchars(ucfirst($this->name));
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */ 
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */ 
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */ 
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */ 
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }



}
