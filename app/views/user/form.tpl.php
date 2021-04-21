<div class="container my-4">
    <h1>Nouvel utilisateur</h1>


    <?php
    if(!empty($errors)) {
        echo '<div class="alert alert-danger">';
            echo '<h2>Vous devez corriger les erreurs suivantes</h2>';
            echo '<ul>';
                foreach($errors as $message) {
                    echo '<li>' . $message .'</li>';
                }
            echo '</ul>';
        echo '</div>';
    }

    ?>

    <form method="post" action="<?=$router->generate('user-createOrUpdate');?>">

    <input name="tokenCSRF" value="<?=$tokenCSRF?>" type="hidden"/>
    <input name="id" value="<?=$user->getId()?>" type="hidden"/>

    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input value="<?=$user->getEmail()?>" name="email" type="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
        <label for="password">Password</label>
        <input value="" name="password" type="password" class="form-control" id="password" placeholder="Password">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="lastname">Nom</label>
            <input value="<?=$user->getLastname()?>" name="lastname" type="text" class="form-control" id="lastname" placeholder="Nom">
        </div>
        <div class="form-group col-md-3">
            <label for="firstname">Prénom</label>
            <input value="<?=$user->getFirstname()?>" name="firstname" type="text" class="form-control" id="firstname" placeholder="Prénom">
        </div>

        <div class="form-group col-md-3">
            <label for="role">Rôle</label>
            <select name="role" id="role" class="form-control">
                <?php //opérateur ternaire..... parfois c'est utile ?>
                <option <?=$user->getRole() == 'admin'? 'selected' : ''?> value="admin">Admin</option>
                <option <?=$user->getRole() == 'catalog-manager'? 'selected' : ''?> value="catalog-manager">Catalog manager</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="status">Compte</label>
            <select name="status" id="status" class="form-control">
                <option <?=$user->getStatus() == '1'? 'selected' : ''?> value="1">Actif</option>
                <option <?=$user->getStatus() == '2'? 'selected' : ''?> value="2">Inactif</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>