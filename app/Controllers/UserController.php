<?php

namespace App\Controllers;

use App\Models\AppUser;
use App\Models\Category;
use App\Models\Product;

class UserController extends CoreController
{


    public function list()
    {
        //!récupération de tous les utilisateurs
        $users = AppUser::findAll();

        $viewVars = [
            'users' => $users
        ];

        $this->show('user/list', $viewVars);
    }


    public function add()
    {

        //!on instancie un utilisateur vide car la vue a besoin d'un objet user
        $user = new AppUser();
        $token = $this->generateCSRFToken();
        $viewVars = [
            'tokenCSRF' => $token,
            'user' => $user
        ];

        $this->show('user/form', $viewVars);
    }

    public function update($userId)
    {
        $user = AppUser::find($userId);
        $token = $this->generateCSRFToken();
        $viewVars = [
            'tokenCSRF' => $token,
            'user' => $user
        ];
        $this->show('user/form', $viewVars);
    }







    public function createOrUpdate()
    {

        $lastname = filter_input(INPUT_POST, 'lastname');
        $firstname = filter_input(INPUT_POST, 'firstname');
        $password = filter_input(INPUT_POST, 'password');
        $email = filter_input(INPUT_POST, 'email');
        $role = filter_input(INPUT_POST, 'role');
        $status = filter_input(INPUT_POST, 'status');
        $id =  filter_input(INPUT_POST, 'id');

       if($id) {
            //!un id user a été envoyé, on récupère l'utilisateur en bdd
            $user = AppUser::find($id);
       }
       else {
           //!instanciation d'un nouvel utilisateur et renseignement des ses propriétés
            $user = new AppUser();
       }
        
        
        $user->setLastname($lastname);
        $user->setFirstname($firstname);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);
        $user->setStatus($status);


        //!gestion des erreurs==========================================================
        $errors = [];   //tableau qui va stocker les champs "non valides

        
       //!pas d'id envoyé, celà signifie que c'est un nouvel utilisateur
        if(!$id) {
            //!on teste si l'email n'existe pas déjà
            $userExists = AppUser::findByEmail($email);
            if($userExists) {
                $errors[] = "L'adresse email " .htmlspecialchars($email) . " existe déjà";
            }
        }


        foreach($_POST as $variableName => $value) {

            
            
            if($variableName == 'password') {
                //!on ne teste si le mot de passe est valide seulement si c'est une création OU SI le mot de passe a été modifié ()
                //!si $id ne vaut rien, ceci signifie que c'est un nouvel utilisateur
                if(!$id || $value != '') {
                    if(!$this->validatePassword($value)) {
                        $errors['password'] = "Mot de passe invalide";
                    }
                }
            }

            //!pour l'adresse email, il faut que nous vérifions qu'elle est valide
            else if($variableName == 'email') {
                //?https://www.php.net/filter_var
                //?https://www.php.net/manual/fr/filter.filters.validate.php
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "Adresse email invalide";
                }
            }
            else if(!$value) {
                $errors[$variableName] = "Le champ {$variableName} ne doit pas être vide";
            }

        }


        //!s'il y a des erreurs réaffichage du formulaire et envoie à la vue des messages d'erreurs
        if(!empty($errors)) {
            $viewVars = [
                //!on n'oublie de générer un nouveau token car sinon la soumission du formulaire sera refusée
                'tokenCSRF' => $this->generateCSRFToken(),
                'errors' => $errors,
                'user' => $user
            ];
            $this->show('user/form', $viewVars);
            return;
        }
        //!=================================================================================

        
        //!si l'utilisateur a bien été enregistré, redirigeons vers la page liste des utlisateurs
        if($user->save()) {
            $this->redirect('user-list');
        }
        else {
            echo '@TODO page d\'erreur';
            echo __FILE__.':'.__LINE__; exit();
        }


    }


    public function logout()
    {
        //*cette méthode efface les données utilisateurs enregistrées dans $_SESSION
        //?https://www.php.net/unset
        unset($_SESSION['userId']);
        unset($_SESSION['userObject']);

        //*il était également possible de détruire la session
        //*session_destroy();

        $this->redirect('user-login');
    }

    public function login()
    {

        $this->show('user/login');
        
    }

    public function checkLogin()
    {
        //*une fois l'email récupéré
        $email = filter_input(INPUT_POST, 'email');

        //*mot de passe saisi par l'utilisateur
        $password = filter_input(INPUT_POST, 'password');

        //!récupération de l'utilisateur via son email
        $user = AppUser::findByEmail($email);
        //*l'utilisateur a été trouvé grace à son email


        if($user) { 
            //!vérification du mot de passe
            if(password_verify($password, $user->getPassword())) {
                //!oh joie !! le mot de passe est correct !
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userObject'] = $user;

                $this->redirect('main-home');
            }
            else {
                echo "TODO ERREUR MDP";
                echo __FILE__.':'.__LINE__; exit();                
            }
        }
        else {
            echo "TODO utilisateur {$email} non trouvé !";
            echo __FILE__.':'.__LINE__; exit();
            
        }
    }


    protected function validatePassword($password)
    {


        //au moins 8 caractères
        if(strlen($password) < 8) {
            return false;
        }

        //au moins une lettre en minuscule
        //[a-zA-Z0-9] aurait pu remplacer les 3 regex ci dessous
        //?https://www.php.net/manual/en/function.preg-match.php
        //===========================================================
        if(!preg_match('`[a-z]`', $password)) {
            return false;
        }

        //au moins une lettre en majuscule
        if(!preg_match('`[A-Z]`', $password)) {
            return false;
        }

        //au moins un chiffre
        if(!preg_match('`[0-9]`', $password)) {
            return false;
        }
        //===========================================================

        //au moins un caractère spécial parmi ['_', '-', '|', '%', '&', '*', '=', '@', '$']
        if(!preg_match('`[^_\-%&*=@\$]`u', $password)) {
            return false;
        }

        return true;
        
    }


}
