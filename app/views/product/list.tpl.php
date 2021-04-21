<div class="container my-4">
        <a href="<?=$router->generate('product-add')?>" class="btn btn-success float-right">Ajouter</a>
        <h2>Liste des produits</h2>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Description</th>
                    <th scope="col">Tags</th>
                    <th scope="col">Image</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                <?php
                foreach($products as $index => $product) {
                    require(__DIR__.'/../partials/product-list-item.tpl.php');
                }
                ?>



            </tbody>
        </table>
    </div>