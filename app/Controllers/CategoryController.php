<?php

namespace App\Controllers;

use App\Models\CoreModel;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;



class CategoryController extends CoreController
{


    public function delete($categoryId)
    {
        //!chargement de la category
        $category = Category::find($categoryId);
        $category->delete();
        $this->redirect('category-list');
    }

    //!enregistrement des catégories qui doivent s'afficher en home page
    public function homeSelectionSave()
    {
        //!récupérons les donénes envoyées en $_POST
        //*remarquons que nous récupérons la variable "categories" dans POST sans utiliser les []

        $selectedCategoriesId = $_POST['categories'];
        //$selectedCategoriesId = filter_input(INPUT_POST, 'categories');


        //!resetons les home_order de toutes les categories
        Category::resetHomeOrder();

        //!pour chacune des catégories envoyées, nous allons mettre à jour la propriété home_order
        foreach($selectedCategoriesId as $index => $id) {

            if(!$id) {
                continue;
            }


            //*récupération de l'objet catégorie grace à son id
            $category = Category::find($id);
            //!calcul du rang d'affichage de la catégorie
            $homeOrder = $index + 1;

            //!enregistrement du rang dang dans la propriété dédiée
            $category->setHomeOrder($homeOrder);

            if($category->save()) {
                //on ne fait rien pour le moment
            }
            else {
                //@todo
                echo "TODO ERREUR LORS DE LA SAUVEGARDE";
                echo __FILE__.':'.__LINE__; exit();
            }
        }
        $this->redirect('category-home-selection');

    }

    public function homeSelection()
    {
        $viewVars = [
            //!c'est géré de façon automatique désormais
            //'tokenCSRF' => $this->generateCSRFToken(),
            'categories' => Category::findAll(),
            'categoriesForHome' => Category::findAllHomepage()
        ];

        $this->show('category/home-selection', $viewVars);

    }


    public function list()
    {
        $this->checkUser();

        //!récupération de toutes les categorie
        $categories = Category::findAll();

        $viewVars = [
            'categories' => $categories
        ];

        $this->show('category/list', $viewVars);
    }


    public function add()
    {
        $this->checkUser(['admin']);
        $this->show('category/add');
    }


    public function createOrUpdate()
    {
        $data = $_POST;
        $categoryId = \filter_input(\INPUT_POST,'id');


        if($categoryId) {
            //!récupération de la catégorie que l'on souhaite mettre à jour (grace à categoryId)
            $category = Category::find($categoryId);
        }
        else {
            $category = new Category();
        }


        //!mise à jour des propriété de la catégorie demandée
        $category->setName($data['name']);
        $category->setSubtitle($data['subtitle']);
        $category->setPicture($data['picture']);

        $success =$category->save();



        if(!$success) {
            echo 'Echec d\'enregistrement';
            exit();
        }
        else {
            
            $redirectionURL = $this->router->generate('category-list');
            header('Location: '.$redirectionURL);
        }
    }



    public function update($categoryId)
    {
        $category = Category::find($categoryId);

        $viewVars = [
            'category' => $category
        ];

        $this->show('category/update', $viewVars);
    }
}


