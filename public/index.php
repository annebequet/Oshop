<?php

//!nous forçons php à afficher les erreurs
ini_set('display_errors', true);

// POINT D'ENTRÉE UNIQUE : 
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';


//!démarrage de la session (ceci va nous permettre de stocker des variables persistantes entres les rafraichissements de page (dans $_SESSION))
session_start();

/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va 
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
}
// sinon
else {
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter, afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-home'
);


//===================================================
//!routes de gestion du login/logout

$router->map(
    'GET',
    '/user/login',
    [
        'method' => 'login',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-login'
);


$router->map(
    'POST',
    '/user/login',
    [
        'method' => 'checkLogin',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-checkLogin'
);

$router->map(
    'GET',
    '/user/logout',
    [
        'method' => 'logout',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-logout'
);

//!liste des utilisateurs
$router->map(
    'GET',
    '/user/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-list'
);
$router->map(
    'GET',
    '/user/update/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-update'
);




$router->map(
    'GET',
    '/user/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-add'
);

$router->map(
    'POST',
    '/user/add',
    [
        'method' => 'createOrUpdate',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-createOrUpdate'
);




//===================================================

//!page liste des catégories
$router->map(
    'GET',
    '/category/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-list'
);


//!page création d'une catégories
$router->map(
    'GET',
    '/category/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-add'
);




//!page édition d'une catégories
$router->map(
    'GET',
    '/category/update/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-update'
);



$router->map(
    'POST',
    '/category/save',
    [
        'method' => 'createOrUpdate',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-createOrUpdate'
);

$router->map(
    'GET',
    '/category/delete/[i:id]',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-delete'
);




//!Gestion des catégories en home page

$router->map(
    'GET',
    '/category/home-selection',
    [
        'method' => 'homeSelection',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-home-selection'
);

$router->map(
    'POST ',
    '/category/home-selection-save',
    [
        'method' => 'homeSelectionSave',
        'controller' => '\App\Controllers\CategoryController'
    ],
    'category-home-selection-save'
);


//=======================================

//!page liste des catégories
$router->map(
    'GET',
    '/product/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-list'
);


//!page création d'une produit
$router->map(
    'POST',
    '/product/add',
    [
        'method' => 'create',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-create'
);

//!page création d'une produit
$router->map(
    'GET',
    '/product/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-add'
);


//!page édition d'une produit
$router->map(
    'GET',
    '/product/edit/[i:id]',
    [
        'method' => 'update',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-update'
);
//!page d'enregistrement d'un produit
$router->map(
    'POST',
    '/product/save',
    [
        'method' => 'createOrUpdate',
        'controller' => '\App\Controllers\ProductController'
    ],
    'product-createOrUpdate'
);


//================================
//!gestion des erreurs
$router->map(
    'GET',
    '/error/403',
    [
        'method' => 'err403',
        'controller' => '\App\Controllers\ErrorController'
    ],
    'error-err403'
);


/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();