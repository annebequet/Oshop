<div class="container my-4">

    <a href="<?=$router->generate('product-list')?>" class="btn btn-success float-right">Retour</a>
    <h2>Ajouter un produit</h2>
    <?php

    $submitURL = $router->generate('product-createOrUpdate');
    ?>

    <form action="<?=$submitURL?>" method="POST" class="mt-5">

        <input name="id" value="<?=$product->getId()?>"/>

        <div class="form-group">
            <label for="name">Nom</label>
            <input name="name" type="text" class="form-control" id="name" placeholder="Nom de la catégorie" value="<?=$product->getName()?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input
                name="description"
                type="text"
                class="form-control"
                id="description"
                placeholder="Sous-titre" 
                value="<?=$product->getDescription()?>"
                aria-describedby="descriptionHelpBlock"
            >
            <small id="subtitleHelpBlock" class="form-text text-muted">
                La description du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="picture">Image</label>
            <input
                name="picture"
                type="text"
                class="form-control"
                id="picture"
                placeholder="image jpg, gif, svg, png"
                aria-describedby="pictureHelpBlock"
                value="<?=$product->getPicture()?>"
            >
            <small id="pictureHelpBlock" class="form-text text-muted">
                URL relative d'une image (jpg, gif, svg ou png) fournie sur 
                <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
            </small>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input
                name="price"
                type="number"
                class="form-control"
                id="price"
                placeholder="Prix" 
                aria-describedby="priceHelpBlock"
                value="<?=$product->getPrice()?>"
            >
            <small id="priceHelpBlock" class="form-text text-muted">
                Le prix du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="rate">Note</label>
            <input
                name="rate"
                type="text"
                class="form-control"
                id="rate"
                placeholder="Note" 
                aria-describedby="rateHelpBlock"
                value="<?=$product->getRate()?>"
            >
            <small id="rateHelpBlock" class="form-text text-muted">
                Le note du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="status">Statut</label>
            <select
                name="status"
                class="custom-select"
                id="status"
                aria-describedby="statusHelpBlock"
                value="value="<?=$product->getStatus()?>"
            >
                <option value="0">Inactif</option>
                <option value="1">Actif</option>
            </select>
            <small id="statusHelpBlock" class="form-text text-muted">
                Le statut du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="category">Categorie</label>
            <select name="category_id" class="custom-select" id="category" aria-describedby="categoryHelpBlock">
                <option value="1">Détente</option>
                <option value="2">Au travail</option>
                <option value="3">Cérémonie</option>
            </select>
            <small id="categoryHelpBlock" class="form-text text-muted">
                La catégorie du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="brand">Marque</label>
            <select  name="brand_id" class="custom-select" id="brand" aria-describedby="brandHelpBlock">
                <option value="1">oCirage</option>
                <option value="2">BOOTstrap</option>
                <option value="3">Talonette</option>
            </select>
            <small id="brandHelpBlock" class="form-text text-muted">
                La marque du produit 
            </small>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type_id" class="custom-select" id="type" aria-describedby="typeHelpBlock">
                <option value="1">Chaussures de ville</option>
                <option value="2">Chaussures de sport</option>
                <option value="3">Tongs</option>
            </select>
            <small id="typeHelpBlock" class="form-text text-muted">
                Le type de produit 
            </small>
        </div>



        <div class="form-group">
            <label for="tag_id">Tags</label>

            <?php
                //!pour tous les tags déjà associés au produit que l'on souhaite éditer nous allons enregister dans un tableau leurs id
                $selectedTagsById = [];
                foreach($selectedTags as $tag) {
                    $selectedTagsById[$tag->getId()] = true;
                }
                //*faire un debug de $selectedTagsById pour comprendre la construction de ce tableau
            ?>
            <select
                name="tag_id[]"
                class="custom-select"
                id="tag_id[]"
                aria-describedby="tagHelpBlock"
                multiple
            >
                <?php
                    foreach($tags as $tag) {
                        $selected = '';
                        if(isset($selectedTagsById[$tag->getId()])) {
                            $selected = 'selected';
                        }
                        echo '<option ' . $selected . ' value="' . $tag->getId() . '">' . $tag->getName() . '</option>';
                    }
                ?>
            </select>
            <small id="tagHelpBlock" class="form-text text-muted">
                Les tags associés au produit
            </small>
        </div>



        
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div>