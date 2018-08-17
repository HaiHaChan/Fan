<?php
      include "../view/header.php";
      include "../view/header_account.php"; ?>

</br></br></br>
<div class="wall">         

          <div id="edit_profile">Reset <strong><?= $_SESSION["fan"]["username"]; ?></strong>'s password</div>

          <?php if(!empty($errors)): ?>
              <div class=update>
                <ul>
                  <?php foreach ($errors as $value) : ?>
                    <li><?= $value ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
          <?php endif;?>

          <?php if (isset($sucess) && $sucess){ ?>
                  <div class=update>reset password succefully</div>
                  <?php $sucess = false; ?>
          <?php }?> 
          <form class="" action="." method="post">
              <input type="hidden" name="action" value="reset_password">
                  <div class="update">
                      <div>
                            <p><label for="old_password">Password</label><p/>
                            <p><input type="password" name="old_pass" value=""></p>
                      </div>
                      <div>
                            <p><label for="new_password">New Password</label></p>
                            <p><input type="password" name="new_pass" value=""></p>
                      </div>
                      <div>
                          <p><label for="re_enter_password">Re_enter Password</label><p/>
                          <p><input type="password" name="re_enter_pass" value=""></p>
                      </div>
                      <div>
                          <button type="submit" name="submit">Reset</button>
                      </div>
                  </div>
          </form>
</div>
<?php include "../view/footer.php" ?>
