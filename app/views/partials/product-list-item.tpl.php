<tr>
    <th scope="row"><?=$product->getId()?></th>
    <td><?=$product->getName()?></td>
    <td><?=$product->getPrice()?></td>
    <td><?=$product->getDescription()?></td>

    <td><?php
        //!récupérons les tags associés au produit courant
        $tags = $product->getTags();

        echo '<ul>';
        foreach($tags as $tag) {
            echo '<li>';
                echo $tag->getName();
            echo '</li>';
        }
        echo '</ul>';

    ?></td>

    <td><img src="<?=$product->getPicture()?>" style="height: 150px"/></td>
    <td class="text-right">


        <a
            href="<?=$router->generate('product-update', ['id' => $product->getId()])?>"
            class="btn btn-sm btn-warning"
        >
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>

        <!-- Example single danger button -->
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-danger dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Oui, je veux supprimer</a>
                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
            </div>
        </div>
    </td>
</tr>