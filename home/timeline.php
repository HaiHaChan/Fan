<?php 
    require_once('../util/main.php');
    include '../view/header.php'; 
    include '../view/header_account.php';
?>
<div class="wall">
    </br></br></br>
    <div class="post">
       <form action="." id="" enctype="multipart/form-data" method="post">
           <fieldset> 
                <input type="hidden" name="action" value="post">
                <div class="tabs-input">
                    <div class="wall-tabs">
                        <div class="item">
                            <div class="text">Post</div>
                        </div>
                    </div>
                    <textarea placeholder="i love you..." name="post_content" rows="4" cols="75"></textarea>
                    <div id="photo" style="display:none;">
                        <input type="hidden" name="type" value="1">
                        <input type="file" name="image">
                    </div>
                </div>
                <div class="controls">
                    <li class="ossn-wall-photo">
                        <img src="../icons/photo.png" alt="" onclick="up_photo()">
                        <script type="text/javascript" charset="utf-8">
                            function up_photo(){
                                document.getElementById("photo").style="";
                            }
                        </script>
                    </li>
                    <div style="float:right;">
                        <div class="ossn-loading ossn-hidden"></div>
                         <input class="button_post" type="submit" value="Post">
                    </div>
                </div>      
            </fieldset>
        </form>
    </div> 

    <!--===================================-->
    <?php if (!empty($msg)){?>
      <div class="alert alert-success">
        <?php echo $msg;?>
      </div>
    <?php } ?>
    <!--===================================-->
    <div class="timeline_wall">
        <?php foreach ($posts as $post) {?>
            <!-- <fieldset> -->
        <div class="status">
        
            <div class="user">
                <span><img src="images/<?php echo $post['username']."/avatar/".$post['avatar'];?>" alt=""></span>  
                <span>
                    <ul>
                    <li><h4 class="media-heading">
                      <a href="?action=home&name=<?php echo $post['username']; ?>">
                                    <?php if(!empty($post["first_name"]) && !empty($post["last_name"])){
                                    echo $post["first_name"]." ".$post["last_name"];
                                  }else {
                                    echo $post["username"];
                                  }?>
                      </a>
                    </h4></li>
                    <li><?php echo $post["created_at"] ?></li>
                    </ul>
                </span>  
                <span></span>
            </div>
            <?php if($post['type']==0){?>
            <div class="content">
                <p><?php echo $post["content"] ?></p>
            </div >
            <?php } else{?>
            <div class="content">
                <img src="images/<?php echo $post['username'].'/posts/'.$post['content'];?>" alt="">
            </div>
            <?php }?>
            <div>
                <p class="media-heading">
                    <?php if ($post["owner_id"] === $user_id):?>
                        <a href="?action=delete_status&id=<?php echo $post['post_id'];?>">Remove</a> 
                    <?php endif; ?>

                    <?php if ($post["owner_id"] != $user_id ):?>
                        <a href="?action=like&id=<?php echo $post['post_id']; ?>">Like</a> /
                    <?php endif; ?>
                    <?php $like_count = 0;
                        foreach ($count_likes as $like) {
                          if ($post["post_id"] === $like["like_post_id"]) {
                                $like_count += 1;
                          }
                        }
                    echo "$like_count : likes / ";
                    $reply_count = 0;
                        foreach ($replies as $reply) {
                          if ($post["post_id"] === $reply["post_reply_id"] ) {
                              $reply_count += 1;
                          }
                        }
                    echo "$reply_count : replies"; ?>
                </p>
            </div>

            <div>
                <?php foreach ($replies as $reply) {
                    if ($post["post_id"] === $reply["post_reply_id"]) { ?>
                    <div class="replies">
                        <table>
                                <tr>
                                    <td width="10%" rowspan="2">
                                        <a href="#">
                                            <img class="media-object" src="images/<?php echo $reply['username']."/avatar/".$reply['avatar']; ?>" alt="...">
                                        </a>
                                    </td>
                                    <td>
                                        <span class="media-heading">
                                            <a href=".?action=home&name=<?php echo $reply['username']; ?>"><?php
                                                if(!empty($reply["first_name"]) && !empty($reply["last_name"])){
                                                    echo $reply["first_name"]." ".$reply["last_name"];
                                                }else {
                                                    echo $reply["username"];
                                                }?>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%"><?php echo $reply["reply_content"] ?></td>
                                </tr>
                        </table>

                    </div>
                <?php
                    }
                  }?>
            </div>
            <div>
                <form class="reply_form" action="." method="post">
                <input type="hidden" name="action" value="reply">
                    <div class="form-group">
                      <textarea name="reply" style="resize:none;" rows="2" cols="65" placeholder="reply the status"></textarea>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="reply" name="submit_reply" style="float:right;" value="<?php echo $post["post_id"] ?>">Reply</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- </fieldset> -->

        <?php } ?>
    </div>   
    </div>         
<?php include '../view/footer.php'; ?>
