<div class="col">
    <div class="form-group">
        <label for="category<?= $emplacementNumber ?>">Emplacement #<?= $emplacementNumber ?></label>
        <select
            class="form-control"
            id="category<?= $emplacementNumber ?>"
            name="categories[]"
        >
            <option value="">choisissez :</option>
            <?php

            foreach($categories as $category) {

                /*
                $selected = '';
                if($selectedCategory->getId() == $category->getId()) {
                    $selected = 'selected';
                }
                */

                if($selectedCategory !== false) {
                    $selected = ($selectedCategory->getId() == $category->getId()) ? 'selected' : '';
                }
                else {
                    $selected = '';
                }


                echo '<option '.$selected.' value="'.$category->getId().'">';
                    echo  $category->getName();
                echo '</option>';
            }
            
            
            ?>
        </select>
    </div>
</div>