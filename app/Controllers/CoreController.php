<?php

namespace App\Controllers;

class CoreController {

    protected $router;
    protected $user;



    //!déclaration d'une propriété $acl
    //*cette propriété va nous permettre de centraliser toutes les autorisations en fonction de la page demandée, et du rôle de l'utilisateur
    protected $acl = [];


    //!cette propriété liste toutes les routes devant être protégées par un token
    protected $csrfRoutes = [];

    public function __construct()
    {
        global $router;
        $this->router = $router;

        if($this->isUserConnected()) {
            $this->user = $_SESSION['userObject'];
        }

        //!on initialize la liste des autorisations
        $this->initializeACL();

        //!on vérifie que l'utilisateur a bien un rôle autorisé pour afficher la page
        $this->checkACL();


        //!chargement de la configuration pour savoir quelle routes sont protégées par token
        $this->initializeCSRFRoutes();

        //!vérification des token (si la page doit être protégée)
        if(!$this->checkCSRFToken()) {
            echo 'PROBLEME DE TOKEN';
            echo __FILE__.':'.__LINE__; exit();
        }
    }

    //!configuration des pages protégée par token
    protected function initializeCSRFRoutes()
    {
        $this->csrfRoutes['user-createOrUpdate'] = true;
        $this->csrfRoutes['category-home-selection-save'] = true;
        $this->csrfRoutes['category-createOrUpdate'] = true;
        $this->csrfRoutes['category-delete'] = true;
    }

    //!génération d'un token de protection
    protected function generateCSRFToken()
    {
        $token = uniqid();
        $_SESSION['tokenCSRF'] = $token;
        return $token;

    }


    //!si la page demandée requiert un token, vérification
    protected function checkCSRFToken()
    {
        global $match;
        $currentRoute = $match['name'];

        //!un token est nécessaire pour executer cette page
        if(array_key_exists($currentRoute, $this->csrfRoutes)) {
            //!si $_POST['token'] n'est pas set, prend $_GET['token'] ; si $_GET['token'] n'est pas set prend la valeur ''
            $token = $_POST['tokenCSRF'] ?? $_GET['tokenCSRF'] ?? '';

            if(isset($_SESSION['tokenCSRF'])) {
                if($_SESSION['tokenCSRF'] == $token) {
                    //!destruction du token
                    unset($_SESSION['tokenCSRF']);
                    return true;
                }
            }
            unset($_SESSION['tokenCSRF']);
            return false;
        }
        else {
            //!la route ne nécessite de protecte, tout est ok
            return true;
        }
    }




    protected function initializeACL()
    {
        //!autorisons l'accès à la home page aux role "admin" et "catalog-manager
        $this->acl['main-home'] = ['admin', 'catalog-manager'];

        $this->acl['user-list'] = ['admin'];
        $this->acl['user-add'] = ['admin'];

        $this->acl['product-list'] = ['admin', 'catalog-manager'];
        $this->acl['product-add'] = ['admin', 'catalog-manager'];

    }

    protected function checkACL()
    {
        //!pour vérifier si un utilisateur a le droit d'afficher la page demandée, nous devons ABSOLUMENT savoir sur quelle page il est
        //*pour récupérer cette information, nous allons utiliser la variable $match définie dans index.php (c'est sale ; mais le faire proprement serait "complexe")
        global $match;
        //dump($match); //pour bien visualiser le contenu de la variable $match (cette variable est créée par AltoRouter)
        $currentRoute = $match['name'];

        //!testons si l'utilisateur à le droit d'affiche la page courrante
        //*récupération des rôles autorisés
        if(array_key_exists($currentRoute, $this->acl)) {
            $authorizedRoles = $this->acl[$currentRoute];
            $this->checkUser($authorizedRoles);
        }
        else {
            //!nous testons si l'utilisateur est connecté seulement s'il n'est pas sur la page de connexion
            if($currentRoute !== 'user-login' && $currentRoute !== 'user-checkLogin') {
                $this->checkUser();
            }
        }
    }


    public function checkUser($authorizedRoles =null)
    {
        //!si l'utilisateur n'est pas connecté ; redirection vers la page de login
        if(!$this->isUserConnected()) {
            $this->redirect('user-login');
            exit();
        }

        if(!empty($authorizedRoles)) {
            if(!$this->checkAuthorization($authorizedRoles)) {
                $this->error403();
                exit();
            }
        }
    }


    public function error403()
    {
        $this->redirect('error-err403');
    }

    public function isUserConnected()
    {
        if(isset($_SESSION['userId'])) {

            //!vérifie que $_SESSION['userId'] est différent de :
                //null, false, 0, '', '0'
            if($_SESSION['userId']) {
                return true;
            }
 
        }
        return false;
    }

    
    public function checkAuthorization($authorizedRoles=[])
    {
        // Si le user est connecté

        if($this->isUserConnected()) {
            // Alors on récupère l'utilisateur connecté
            $this->user = $_SESSION['userObject'];

            // Puis on récupère son role
            $role = $this->user->getRole();

            // si le role fait partie des roles autorisées (fournis en paramètres $authorizedRoles)
            //?https://www.php.net/array_search
            if(array_search($role, $authorizedRoles) !== false) {
                // Alors on retourne vrai
                return true;
            }
            else {
                //sinon on retourne faux
                return false;
            }
        }
    }


    public function redirect($routeName, $parameters = [])
    {
        $url = $this->router->generate($routeName);
        header('Location: '.$url);
    }


    //!génère à notre place l'input qui stocke la valeur du token
    protected function generateCSRFInput()
    {
        $token = $_SESSION['tokenCSRF'];
        return '<input name="tokenCSRF" value="'.$token.'"/>';
    }
    protected function generateCSRFLink($routeName, $routeParameters = [])
    {
        $token = $_SESSION['tokenCSRF'];
        return $this->router->generate($routeName, $routeParameters) . '?tokenCSRF=' . $token;
    }

    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) {
        // On globalise $router car on ne sait pas faire mieux pour l'instant


        $router = $this->router;

        //!on génère un token pour TOUTES les vues (que l'on en ait besoin ou pas , n'est pas grave)
        $tokenCSRF = $this->generateCSRFToken();

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__.'/../views/layout/header.tpl.php';
        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';
        require_once __DIR__.'/../views/layout/footer.tpl.php';
    }
}
