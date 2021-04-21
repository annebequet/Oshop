<?php


namespace App\Controllers;
use App\Models\Product;
use App\Models\Tag;


class ProductController extends CoreController
{


    public function list()
    {
        
        //!récupération de la liste des produits
        $products = Product::findAll();

        $viewVars = [
            'products' => $products
        ];

        $this->show('product/list', $viewVars);
    }

    public function add()
    {
        $this->show('product/add');
    }

    public function update($productId)
    {
        //!récupération du produit
        $product = Product::find($productId);
        
        $viewVars = [
            'selectedTags' => $product->getTags(),
            'tags' => Tag::findAll(),
            'product' => $product
        ];

        $this->show('product/edit', $viewVars);
    }


    public function createOrUpdate()
    {

        $productId = filter_input(\INPUT_POST, 'id');

        //!récupération de toutes les données envoyées par l'utlisateur
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $picture = filter_input(INPUT_POST, 'picture');
        $price = filter_input(INPUT_POST, 'price');
        $rate = filter_input(INPUT_POST, 'rate');
        $status = filter_input(INPUT_POST, 'status');
        $brand_id = filter_input(INPUT_POST, 'brand_id');
        $type_id = filter_input(INPUT_POST, 'type_id');
        $category_id = filter_input(INPUT_POST, 'category_id');

        $tagId = $_POST['tag_id'];


        //!si un id a été envoyé, nous chargeons le produit demandé, sinon nous créons un produit vide
        if($productId) {
            $product = Product::find($productId);
        }
        else {
            $product = new Product();
        }

        //!renseignement des propriétés du produit
        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setRate($rate);
        $product->setStatus($status);
        $product->setCategoryId($category_id);
        $product->setBrandId($brand_id);
        $product->setTypeId($type_id);

        $success = $product->save();


        if(!$success) {
            echo "Erreur lors de l'enregistrement";
            exit();
        }
        else {
            global $router;
            header('Location: '.$router->generate('product-list'));

        }



    }




    public function create()
    {
        //?https://www.php.net/filter_input
        //!récupération de toutes les données envoyées par l'utlisateur
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $picture = filter_input(INPUT_POST, 'picture');
        $price = filter_input(INPUT_POST, 'price');
        $rate = filter_input(INPUT_POST, 'rate');
        $status = filter_input(INPUT_POST, 'status');
        $brand_id = filter_input(INPUT_POST, 'brand_id');
        $type_id = filter_input(INPUT_POST, 'type_id');
        $category_id = filter_input(INPUT_POST, 'category_id');
        
        //!instanciation d'un nouvel objet Product
        $product = new Product();

        //!renseignement des propriétés du produit
        $product->setName($name);
        $product->setDescription($description);
        $product->setPicture($picture);
        $product->setPrice($price);
        $product->setRate($rate);
        $product->setStatus($status);
        $product->setCategoryId($category_id);
        $product->setBrandId($brand_id);
        $product->setTypeId($type_id);

        $product->insert();

        //!récupération du router
        //global $router;
        //header('Location: ' . $router->generate('product-list'));



    }


}