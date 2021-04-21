<div class="container my-4">
    <h2>Sélection des catégories de la page d'accueil</h2>

    <form action="<?=$router->generate('category-home-selection-save')?>" method="POST" class="mt-5" id="categories-home-page">

        <?=$this->generateCSRFInput()?>

        <div class="row">


            <?php
            for($emplacementNumber = 1; $emplacementNumber <= 5; $emplacementNumber++) {
                //!les catégories sélectionnées sont stocké dans le tableau $categoriesForHome (attention ce tableau est indexé de 0 à 4 alors que les emplacements sont numérotés de 1 à 5)
                $selectedCategory = false;
                if(isset($categoriesForHome[$emplacementNumber-1])) {
                    $selectedCategory = $categoriesForHome[$emplacementNumber-1];
                }
                
                require(__DIR__.'/../partials/category-home-select-item.tpl.php');
            }
            ?>

        </div>
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div>