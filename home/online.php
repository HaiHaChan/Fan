 <?php 
  include "../view/header.php";
  include "../view/header_account.php";
 $q=''; ?>
 <?php if (!isset($users)): ?>
   <div class="error">
      user  " <strong><?php echo $q; ?></strong>" not found
   </div>
 <?php endif; ?>
 <div class="wall">
      </br></br></br>
      <div class="find">
        <?php foreach ($users as $key => $user): ?>
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
             <?php if ($_SESSION['fan']['online'] === "1"){ ?>
                  <spam><button type="button" class="online_button"></button></spam>
              <?php }else{ ?>
                  <spam><button type="button" class="offline_button"></button></spam>
              <?php } ?>
             </div>
        </div>
    <?php endforeach; ?>
    </div>
 </div>
<?php include "../view/footer.php" ?>
