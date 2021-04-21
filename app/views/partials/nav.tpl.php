<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?=$router->generate('main-home')?>">oShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?=$router->generate('main-home')?>">Accueil <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Catalogue
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?=$router->generate('category-list')?>">Catégories</a>
                    <a class="dropdown-item" href="<?=$router->generate('product-list')?>">Produits</a>
                    <a class="dropdown-item" href="#">Marques</a>
                    <a class="dropdown-item" href="#">Types</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?=$router->generate('user-list')?>">Utilisateurs</a>
                </li>

            </ul>


            <div class="my-2 my-lg-0">
                <?php
                    if($this->isUserConnected()) {
                        echo
                        '<a class="nav-link" href="' .
                            $router->generate('user-logout') . '">
                            <button class="btn btn-danger my-2 my-sm-0" type="submit">Logout</button>
                        </a>';
                    }
                ?>
            </div>


        </div>
    </div>
</nav>
