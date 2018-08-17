<?php
      include "../view/header.php";
      include "../view/header_account.php"; ?>

</br></br></br>
<div class="wall">

          <div id="edit_profile">Editing <strong><?= $_SESSION["fan"]["username"]; ?></strong>'s profile</div>
          <?php if(!empty($errors)): ?>
              <div class=update>
                <ul>
                  <?php foreach ($errors as $value) : ?>
                    <li><?= $value ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
          <?php endif;?>

          <?php if (isset($updated) && $updated){ ?>
                  <div class=update>update succefully</div>
                  <?php $updated = false; ?>
          <?php }?> 
          <form class="" action="." method="post">
              <input type="hidden" name="action" value="edit_profile">
              <input type="hidden" name="updated" value="false">
                  <div class="update">
                      <div>
                            <p><label for="last_name">User name</label><p/>
                            <p><input type="text" name="username" value="<?= $user_info["username"]; ?>"></p>
                      </div>
                      <div>
                            <p><label for="first_name">First name</label></p>
                            <p><input type="text" name="firstname" value="<?= $user_info["first_name"]; ?>"></p>
                      </div>
                      <div>
                          <p><label for="last_name">Last name</label><p/>
                          <p><input type="text" name="lastname" value="<?= $user_info["last_name"]; ?>"></p>
                      </div>
                      <div>
                          <p><label for="last_name">Email</label><p/>
                          <p><input type="text" name="email" value="<?= $user_info["email"]; ?>"></p>
                      </div>
                      <div>
                          <p><label for="location">Location</label></p>
                          <p><input type="text" name="location" value="<?= $user_info["location"]; ?>" id="location"></p>
                      </div>
                      <div>
                          <button type="submit" name="submit">Save</button>
                      </div>
                  </div>
          </form>
</div>
<?php include "../view/footer.php" ?>
