<div class="container my-4">
    <a href="<?=$router->generate('category-add')?>" class="btn btn-success float-right">Ajouter</a>
    <h2>Liste des catégories</h2>
    <table class="table table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Sous-titre</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($categories as $category) {
                require(__DIR__.'/../partials/category-list-item.tpl.php');
            }
        ?>

        </tbody>
    </table>
</div>
