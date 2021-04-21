<?php

namespace App\Models;

use App\Utils\Database;
use PDO;


class AppUser extends CoreModel
{

   private $email;
   private $password;
   private $firstname;
   private $lastname;
   private $role;
   private $status;

   protected static $tableName = 'app_user';


   public static function findByEmail($email)
   {
      //!requête sql
      $sql = "
         SELECT * FROM
            `".static::$tableName."`
         WHERE
            email = :email
      ";
      return static::queryAndFetch($sql, [':email' => $email]);
   }



   
   public function insert()
   {
      $sql = "
         INSERT INTO `". static::$tableName."` (
            `lastname`,
            `firstname`,
            `email`,
            `password`,
            `role`,
            `status`
         ) VALUES (
            :lastname,
            :firstname,
            :email,
            :password,
            :role,
            :status
         );
      ";

      //?https://www.php.net/manual/en/function.password-hash.php
      //!attention de ne pas oublier de hasher le password !
      $success = static::execute($sql, [
         ':lastname' => $this->getLastname(),
         ':firstname' => $this->getFirstname(),
         ':email' => $this->getEmail(),
         ':password' => \password_hash($this->getPassword(), PASSWORD_DEFAULT),//!<--- ici
         ':role' => $this->getRole(),
         ':status' => $this->getStatus(),
      ]);

      //!si la requête s'est bien passée, alors nous récupérons le dernier id inséré en bdd
      if($success) {
         $pdo = Database::getPDO();
         $this->id = $pdo->lastInsertId();
         return true;
      }
      else {
         return false;
      }

   }


   public function update()
   {
        $sql = "
            UPDATE `" . static::$tableName . "`
            SET
               `lastname` = :lastname ,
               `firstname` = :firstname,
               `email` = :email,
               `role` = :role,
               `status` = :status
        ";
        //!gestion est ce que le mot de passe doit être mis à jour
        if($this->getPassword()) {
           $sql .=  "
               ,`password` = :password
            ";
        }

        //!on n'oublie pas d'ajouter la condition where !
        $sql .= "WHERE `id` = :id";

        $values = [
            ':lastname' => $this->getLastname(),
            ':firstname' => $this->getFirstname(),
            ':email' => $this->getEmail(),
            ':role' => $this->getRole(),
            ':status' => $this->getStatus(),
            ':id' => $this->getId(),
        ];
        if($this->getPassword()) {
            $values[':password'] = password_hash($this->getPassword(), PASSWORD_DEFAULT);
        }
        return static::execute($sql, $values);
   }

  

   /**
    * Get the value of email
    */ 
   public function getEmail()
   {
      return $this->email;
   }

   /**
    * Set the value of email
    *
    * @return  self
    */ 
   public function setEmail($email)
   {
      $this->email = $email;

      return $this;
   }

   /**
    * Get the value of password
    */ 
   public function getPassword()
   {
      return $this->password;
   }

   /**
    * Set the value of password
    *
    * @return  self
    */ 
   public function setPassword($password)
   {
      $this->password = $password;

      return $this;
   }

   /**
    * Get the value of firstname
    */ 
   public function getFirstname()
   {
      return $this->firstname;
   }

   /**
    * Set the value of firstname
    *
    * @return  self
    */ 
   public function setFirstname($firstname)
   {
      $this->firstname = $firstname;

      return $this;
   }

   /**
    * Get the value of lastname
    */ 
   public function getLastname()
   {
      return $this->lastname;
   }

   /**
    * Set the value of lastname
    *
    * @return  self
    */ 
   public function setLastname($lastname)
   {
      $this->lastname = $lastname;

      return $this;
   }

   /**
    * Get the value of role
    */ 
   public function getRole()
   {
      return $this->role;
   }

   /**
    * Set the value of role
    *
    * @return  self
    */ 
   public function setRole($role)
   {
      $this->role = $role;

      return $this;
   }

   /**
    * Get the value of status
    */ 
   public function getStatus()
   {
      return $this->status;
   }

   /**
    * Set the value of status
    *
    * @return  self
    */ 
   public function setStatus($status)
   {
      $this->status = $status;

      return $this;
   }
}

