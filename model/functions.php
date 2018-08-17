<?php
function get_by_username ($username){
  global $db;
  $username = $db->quote($username);
  $select_users = $db->query("SELECT * FROM users WHERE username=$username");
  if($select_users->rowCount() >= 1){
    return $select_users->fetch();
  }else {
    return array();
  }
}
function get_by_id ($id){
  global $db;
  $id = $db->quote($id);
  $select_users = $db->query("SELECT * FROM users WHERE id=$id");
  if($select_users->rowCount() >= 1){
    return $select_users->fetch();
  }else {
    return array();
  }
}

function valid_username($username){
  global $db;
  $select = $db->query("SELECT username FROM users WHERE username='$username'");
  if ($select->rowCount() >= 1 ) {
    return true;
  }else{
    return false;
  }
}

function is_friends($req_rec_id, $req_sen_id){
  global $db;
  $select = $db->query("SELECT * FROM friends_control WHERE
    req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id'
    OR
    req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id'
    ");
   if($select->rowCount() >= 1 ) {
      $select = $select->fetch();
      if($select["status"]){
        return 1;
      }else {
        return $select;
      }
   }else {
       return 0;
   }
}

function get_friends($id){
  global $db;
  $friends = $db->query("SELECT * FROM friends_control WHERE (req_rec_id = '$id' OR req_sen_id = '$id') AND status = 1");
  if ($friends->rowCount() >= 1 ) {
    return $friends->fetchAll();
  }else {
    return null;
  }
}

// function get_username($id){
//   global $db;
//   $select = $db->query("SELECT * FROM users WHERE id=$id");
//   if($selects->rowCount() >= 1){
//     $selects->fetch();
//     foreach ($selects as $value) {
//       $a=$value;
//     }
//     return $a;
//   }else {
//     return false;
//   }
// }

function is_liked($like_owner_id, $like_post_id){
  global $db;
  $select = $db->query("SELECT * FROM likes_control WHERE like_owner_id='$like_owner_id' AND like_post_id='$like_post_id'");
  if ($select->rowCount() >= 1 ) {
    return true;
  }else {
    return false;
  }
}

function debug($arr){
  echo "<pre>",print_r($arr, true),"</pre>";
}

//----------------------------------------------------------------------------------------------------

  function accept($req_sen_id, $req_rec_id ){
    global $db;
    $db->query("UPDATE friends_control SET status=1 
    WHERE req_rec_id = '$req_rec_id' AND req_sen_id='$req_sen_id'");
  }

  function add_friend($sender_id, $reciever_id){
    global $db;
    $select = $db->query("SELECT * FROM friends_control WHERE
                        req_rec_id='$sender_id' AND req_sen_id='$reciever_id'
                          OR
                        req_rec_id='$reciever_id' AND req_sen_id='$sender_id'
                        ");
    if($select->rowCount() <= 0){
      $db->query("INSERT INTO friends_control SET req_sen_id = '$sender_id', req_rec_id = '$reciever_id'");
      return true;
    }else {
      return false;
    }
  }

function cancel_request($req_rec_id, $req_sen_id ){
  global $db;
  $frship = $db->query("SELECT * FROM friends_control WHERE
                        ((req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id')
                        OR
                        (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id'))
                        AND status = 1
                        ");
  if ($frship->rowCount() === 0 ) {
    $select = $db->query("SELECT * 
              FROM friends_control 
              WHERE (req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id') OR (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id')");
    if($select->rowCount() >= 1 ){
      $db->query("DELETE FROM friends_control 
      WHERE (req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id') 
      OR (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id')");
    }
  }
}

  function delete_friend($connected_id, $delete_id){
    global $db;
    $db->query("DELETE FROM friends_control 
        WHERE (req_rec_id = '$connected_id' AND req_sen_id = '$delete_id') 
        OR (req_rec_id = '$delete_id' AND req_sen_id = '$connected_id')");
  }

  function post($post_content,$post_owner){
    global $db;
    $db->query("INSERT INTO posts SET content='$post_content', owner_id='$post_owner'");
  }

//=========================================================================================================================

function get_requests($id){
  global $db;
  $result = $db->query("SELECT * FROM friends_control WHERE req_rec_id = '$id' OR req_sen_id = '$id'");
  if ($result->rowCount() >= 1) { 
    $result = $result->fetchAll();
    return $result;
  }
}

// function get_friends($id){
//   global $db;
//   $friends_info = $db->query("SELECT * FROM friends_control 
//                 WHERE (req_rec_id = '$id' OR req_sen_id = '$id') AND status = 1");
//   if ($friends_info->rowCount() >= 1) {
//     $friends_info = $friends_info->fetchAll();
//     return $friends_info;
//   }
// }

  function add_replys($user_id, $post_to_reply, $reply_content){
    global $db;
    $db->query("INSERT INTO replies 
      SET owner_id='$user_id', post_reply_id='$post_to_reply', reply_content='$reply_content'");
  }

function get_posts($id){
  global $db;
  $result=array();
  $select = $db->query("SELECT posts.content, posts.created_at, posts.owner_id,posts.id AS post_id,
                                    users.first_name, users.last_name, users.username
                        FROM posts 
                        INNER JOIN users 
                        ON posts.owner_id = users.id 
                        ORDER BY posts.created_at DESC
                        ");
  if ($select->rowCount() >= 1) {
    $posts = $select->fetchAll();
    foreach ($posts as $post) {
      if (is_friends($post["owner_id"], $id) === 1 || $post["owner_id"] === $id) {
        $result[]=$post;
      }
    }
  }
  return $result;
}

  function get_replies(){
    global $db;
    $replies = $db->query("SELECT replies.owner_id, replies.post_reply_id, replies.reply_content,replies.created_at,
                      users.first_name, users.last_name, users.username
                       FROM replies LEFT JOIN users ON
                       replies.owner_id = users.id ORDER BY replies.created_at DESC
                       ");
    if ($replies->rowCount() >= 1) {
      $replies = $replies->fetchAll();
    }
    return $replies;
  }

  function count_likes(){
    global $db;
    $count_likes = $db->query("SELECT * FROM likes_control");

    if ($count_likes->rowCount() >= 1) {
      $count_likes = $count_likes->fetchAll();
    }

    return $count_likes;
  }
  
  function delete_status($user_id, $post_id){

    global $db;
    
    $select = $db->query("SELECT * FROM posts WHERE id='$post_id' AND owner_id='$user_id'");
    if ($select->rowCount() >= 1) {
      $db->query("DELETE FROM posts WHERE id='$post_id'");
      return true;
    }else {
      return false;
    }
  }
  function like_status($user_id, $post_id){
    global $db;
    $db->query("INSERT INTO likes_control SET like_owner_id='$user_id', like_post_id='$post_id'");
  }

  function get_friend($prof_id, $user_id){ 
    global $db;
   $select = $db->query("SELECT * FROM friends_control WHERE
     req_rec_id='$prof_id' AND req_sen_id='$user_id'
     OR
     req_rec_id='$user_id' AND req_sen_id='$prof_id'
     "); 
    if ($select->rowCount() >= 1) { 
      $select = $select->fetch(); 
      return $select;
    }
  }

  // function get_friends($id){
  //   global $db;
  //   $friends_rel = $db->query("SELECT * FROM friends_control WHERE status='1' AND (req_rec_id='$id' OR req_sen_id='$id')");
  //   return $friends_rel;
  // }
  
  function check_friends($prof_id, $user_id){
    $is_friends=false;
    $select=get_friend($prof_id, $user_id);
      if($select["status"]){ 
        $is_friends = true; 
      }else { 
        $is_friends = "pending";
      }
    return $is_friends;
  }

  function update_profile($id, $first_name, $last_name, $location){
    global $db;
    $que = $db->prepare("UPDATE users SET first_name=?, last_name=?, location=? WHERE id='$id'");
    if($que->execute([$first_name, $last_name, $location])){
    return true;
    }
  }
//========================================================================


function search($q=''){
  global $db;
  if(!empty($q)){
            $search_data_ent = trim(addslashes($q));
            $search_data_ent = preg_split("/[\s]+/",$search_data_ent);
            foreach ($search_data_ent as $search_data) {
              $search_data = $db->quote($search_data);
              $users = $db->query("SELECT * FROM users 
                WHERE username=$search_data OR first_name=$search_data 
                OR last_name=$search_data 
                ORDER BY first_name ASC");
            }
        } else {
          $users = $db->query("SELECT * FROM users");
        }
  return $users;
}