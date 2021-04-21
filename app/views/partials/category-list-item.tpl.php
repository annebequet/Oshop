<tr>
    <th scope="row"><?=$category->getId()?></th>
    <td><?=$category->getName()?></td>
    <td><?=$category->getSubtitle()?></td>
    <td class="text-right">
        <?php
            //!génération du lien d'édition de la catégorie
            //!argument 1 nom de la route
            //!argument 2 paramètres pour contruire la route
            $link = $router->generate('category-update', ['id' => $category->getId()]);
        ?>
        <a href="<?=$link?>" class="btn btn-sm btn-warning">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
        <!-- Example single danger button -->
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-danger dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                href="
                <?=$this->generateCSRFLink('category-delete', ['id' => $category->getId()])?>
                
                ">
                    Oui, je veux supprimer
                </a>
                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
            </div>
        </div>
    </td>
</tr>
