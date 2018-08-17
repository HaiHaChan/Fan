 <?php 
  include "../view/header.php";
  include "../view/header_account.php";
 $q=''; ?>
 <?php if (!isset($users) && !isset($groups)): ?>
   <div class="error">
      "<strong><?php echo $q; ?></strong>" not found
   </div>
 <?php endif; ?>
 <div class="wall">
      </br></br></br>
      <div class="find">
        <?php if(isset($user)){
        foreach ($users as $key => $user): ?>
        <div class="findlist">
            <span>
            <div class="avata_photo_">
                <img src="images/<?php echo $user['username']."/avatar/".$user['avatar'];?>" alt="<?php echo $user['username'] ?>">
            </div>
            </span>
            <span>
              <a href="?action=home&name=<?php echo $user['username'] ?>"><?php
                             if(!empty($user["first_name"]))
                               {
                                 echo $user['first_name']." ".$user['last_name']; ;
                               } else {
                                 echo $user['username'];
                              }?>
             </a></span>
             <div class="request">
             <?php if ($user['check'] === 1){ ?>
                  <spam><input type="submit" class="submit_request" name="" value="Friend"></spam>
              <?php } elseif ($user['check'] != 1 && $user['check'] != 0 ) { ?>
                  <span ><input type="submit" class="submit_request" name="" value="Spendding"></span>
              <?php } ?>
             </div>
        </div>
    <?php endforeach; }?>
      <?php if(isset($groups)){ 
      foreach ($groups as $key => $group): ?>
            <div class="findlist">
                    <span>
                    <div class="avata_photo_">
                        <img src="images/<?php echo $group['name']."/avatar/".$group['avatar'];?>" alt="<?php echo $group['name']; ?>">
                    </div>
                    </span>
                    <span>
                      <a href="?action=group&name=<?php echo $group['name']; ?>"><?php echo $group['name'];?></a>
                    </span>
                    <!--  <div class="request">
                     <?php if ($group['check'] != 1 && $group['check'] != 0 ) { ?>
                          <span ><input type="submit" class="submit_request" name="" value="Spendding"></span>
                      <?php } ?>
                     </div> -->
                    <div class="request">
                      <spam><input type="submit" class="submit_request" name="" value="Group"></spam>
                    </div>
              </div>
      <?php endforeach; }?>
      </div>
 </div>
<!--  <?php echo sha1("haiha@gmail.com" . "12345678");?> -->
<?php include "../view/footer.php" ?>
