<div class="container my-4">
    <a href="<?=$router->generate('user-add')?>" class="btn btn-success float-right">Ajouter</a>
    <h2>Liste des utilisateurs</h2>
    <table class="table table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Email</th>
                <th scope="col">Rôle</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($users as $user) {
                require(__DIR__.'/../partials/user-list-item.tpl.php');
            }
        ?>

        </tbody>
    </table>
</div>
