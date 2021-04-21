<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel {
    
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var float
     */
    private $price;
    /**
     * @var int
     */
    private $rate;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $brand_id;
    /**
     * @var int
     */
    private $category_id;
    /**
     * @var int
     */
    private $type_id;

    protected static $tableName = 'product';
    


    //!retourne tous les tags associés au produit courant
    /**
     * @return App\Models\Tag[]
     */
    public function getTags()
    {
        $sql ="
            SELECT
                `tag`.`id`,
                `tag`.`name`,
                `tag`.`created_at`,
                `tag`.`updated_at`
            FROM `product`
            INNER JOIN `product_has_tag`
                ON `product`.`id`= `product_has_tag`.`product_id`
            INNER JOIN `tag`
                ON `product_has_tag`.`tag_id` = `tag`.`id`
            WHERE
                `product`.`id` = :id
            ORDER BY
                `product`.`id`
        ";


        return static::queryAndFetchAll(
            $sql,
            [':id' => $this->getId()],
            'App\Models\Tag'
        );
    }



    public function update()
    {
        $sql = "
            UPDATE `product`
            SET
                `name` = :name,
                `description` = :description,
                `price` = :price,
                `rate` = :rate,
                `picture` = :picture,
                `status` = :status,
                `category_id` = :category_id,
                `brand_id` = :brand_id,
                `type_id` = :type_id,
                `updated_at` = NOW()
            WHERE
                `id` = :id
        ";

        $pdo = Database::getPDO();
        //!préparation de la requête
        //?https://www.php.net/manual/fr/pdostatement.bindvalue.php
        $preparedStatement = $pdo->prepare($sql);

        //!définition des valeurs à injecter
        $preparedStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $preparedStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $preparedStatement->bindValue(':rate', $this->rate, PDO::PARAM_INT);
        $preparedStatement->bindValue(':price', $this->price);
        $preparedStatement->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $preparedStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $preparedStatement->bindValue(':type_id', $this->type_id, PDO::PARAM_INT);
        $preparedStatement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $preparedStatement->bindValue(':brand_id', $this->brand_id, PDO::PARAM_INT);

        $preparedStatement->bindValue(':id', $this->getId(), PDO::PARAM_INT);


        //!execution de la requête
        $success = $preparedStatement->execute();
        return $success;

    }


    

    public function insert()
    {
        //! Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        //! Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `product` (
                `name`,
                `description`,
                `rate`,
                `price`,
                `picture`,
                `status`,
                `type_id`,
                `category_id`,
                `brand_id`
            )
            VALUES (
                :name,
                :description,
                :rate,
                :price,
                :picture,
                :status,
                :type_id,
                :category_id,
                :brand_id
            )
        ";
        //!préparation de la requête
        //?https://www.php.net/manual/fr/pdostatement.bindvalue.php
        $preparedStatement = $pdo->prepare($sql);

        //!définition des valeurs à injecter
        $preparedStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
        $preparedStatement->bindValue(':description', $this->description, PDO::PARAM_STR);
        $preparedStatement->bindValue(':rate', $this->rate, PDO::PARAM_INT);
        $preparedStatement->bindValue(':price', $this->price);
        $preparedStatement->bindValue(':picture', $this->picture, PDO::PARAM_STR);
        $preparedStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $preparedStatement->bindValue(':type_id', $this->type_id, PDO::PARAM_INT);
        $preparedStatement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $preparedStatement->bindValue(':brand_id', $this->brand_id, PDO::PARAM_INT);


        //!execution de la requête
        $success = $preparedStatement->execute();


        //! Si au moins une ligne ajoutée
        if ($success) {
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




    public static function findAllHomepage()
    {
        $sql = '
            SELECT *
            FROM product
            ORDER BY id ASC
            LIMIT 3 
        ';
        return static::queryAndFetchAll($sql);
    }






    public function getName()
    {
        return $this->name;
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
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return htmlspecialchars($this->description);
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the value of picture
     *
     * @return  string
     */ 
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param  string  $picture
     */ 
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     */ 
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Get the value of rate
     *
     * @return  int
     */ 
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set the value of rate
     *
     * @param  int  $rate
     */ 
    public function setRate(int $rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Get the value of brand_id
     *
     * @return  int
     */ 
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * Set the value of brand_id
     *
     * @param  int  $brand_id
     */ 
    public function setBrandId(int $brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Get the value of category_id
     *
     * @return  int
     */ 
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @param  int  $category_id
     */ 
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the value of type_id
     *
     * @return  int
     */ 
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @param  int  $type_id
     */ 
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }


}
