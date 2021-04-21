<div class="container my-4">
    <a href="<?=$router->generate('main-home')?>" class="btn btn-success float-right">Retour</a>
    <h2>Login</h2>

    <?php
        $submitURL = $router->generate('user-checkLogin');
    ?>

    <form action="<?=$submitURL?>" method="POST" class="mt-5">
        <div class="form-group">
            <label for="email">User Name</label>
            <input name="email" type="text" class="form-control" id="email" placeholder="ex : Toto">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="subtitle" placeholder="Password" aria-describedby="subtitleHelpBlock">
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-5">Se connecter</button>
    </form>
</div>