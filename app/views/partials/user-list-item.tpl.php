<tr>
    <th scope="row"><?=$user->getId()?></th>
    <td><?=$user->getLastname()?></td>
    <td><?=$user->getFirstname()?></td>
    <td><?=$user->getEmail()?></td>
    <td><?=$user->getRole()?></td>
    <td><?=$user->getStatus()?></td>
    <td class="text-right">
        <?php
            $link = $router->generate('user-update', ['id' => $user->getId()]);
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
                <a class="dropdown-item" href="#">Oui, je veux supprimer</a>
                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
            </div>
        </div>
    </td>
</tr>
