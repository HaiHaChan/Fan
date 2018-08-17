 <?php 
  include "../view/header.php";
  include "../view/header_account.php";
 $q=''; ?>
 <?php if (!isset($groups)){?>
   <div class="wall">
      </br></br></br>
      <blockquote>
        <p>You dont have any group.</p>
      </blockquote>
   </div>
 <?php }else{ ?>
 <div class="wall">
      </br></br></br>
      <div class="find">
        <?php foreach ($groups as $key => $group): ?>
        <div class="findlist">
            <span>
            <div class="avata_photo_">
                <img src="images/<?php echo $group['name']."/avatar/".$group['avatar'];?>" alt="<?php echo $group['name']; ?>">
            </div>
            </span>
            <span>
              <a href="?action=group&name=<?php echo $group['name']; ?>"><?php echo $group['name'];?></a>
            </span>
             <div class="request">
             <?php if ($group['check'] != 1 && $group['check'] != 0 ) { ?>
                  <span ><input type="submit" class="submit_request" name="" value="Spendding"></span>
              <?php } ?>
             </div>
        </div>
    <?php endforeach; ?>
     <?php } ?>
    </div>
 </div>
<?php include "../view/footer.php" ?>
