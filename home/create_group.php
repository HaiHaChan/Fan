<?php
      include "../view/header.php";
      include "../view/header_account.php"; ?>

</br></br></br>
<div class="wall">         

          <div id="edit_profile">Create a new group</div>

          <?php if(!empty($errors)): ?>
              <div class=update>
                <ul>
                  <?php foreach ($errors as $value) : ?>
                    <li><?= $value ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
          <?php endif;?>

          <?php if (isset($created) && $created){ ?>
                  <div class=update>created succefully</div>
                  <?php $created = false; ?>
          <?php }?> 
          <form class="" action="." method="post">
              <input type="hidden" name="action" value="create_group">
                  <div class="update">
                      <div>
                            <p><label></label>Type<p/>
                            <select name="type">
                              <option value="0" selected="1">Type group</option>
                              <option value="1">Singer</option>
                              <option value="2">Music band</option>
                              <option value="3">Actor</option>
                              <option value="4">Cartoon Character</option>
                              <option value="5">Annime Character</option>
                              <option value="6">Famous Person</option>
                            </select>
                      </div>
                      <div>
                            <p><label></label>Name<p/>
                            <p><input type="text" name="name"></p>
                      </div>
                      <div>
                          <button type="submit" name="submit">Create</button>
                      </div>
                  </div>
          </form>
</div>
<?php include "../view/footer.php" ?>
