<?php
function is_friends($req_rec_id, $req_sen_id){
  global $db;
  $select = $db->query("SELECT * FROM friends WHERE
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
  $friends = $db->query("SELECT * FROM friends WHERE (req_rec_id = '$id' OR req_sen_id = '$id') AND status = 1");
  if ($friends->rowCount() >= 1 ) {
    return $friends->fetchAll();
  }else {
    return null;
  }
}

  function get_friend($prof_id, $user_id){ 
    global $db;
   $select = $db->query("SELECT * FROM friends WHERE
     req_rec_id='$prof_id' AND req_sen_id='$user_id'
     OR
     req_rec_id='$user_id' AND req_sen_id='$prof_id'
     "); 
    if ($select->rowCount() >= 1) { 
      $select = $select->fetch(); 
      return $select;
    }
  }


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

  

function accept($req_sen_id, $req_rec_id ){
	global $db;
	$db->query("UPDATE friends SET status=1 
	WHERE req_rec_id = '$req_rec_id' AND req_sen_id='$req_sen_id'");
}

 function add_friend($sender_id, $reciever_id){
    global $db;
    $select = $db->query("SELECT * FROM friends WHERE
                        req_rec_id='$sender_id' AND req_sen_id='$reciever_id'
                          OR
                        req_rec_id='$reciever_id' AND req_sen_id='$sender_id'
                        ");
    if($select->rowCount() <= 0){
      $db->query("INSERT INTO friends SET req_sen_id = '$sender_id', req_rec_id = '$reciever_id'");
      return true;
    }else {
      return false;
    }
 }

function cancel_request($req_rec_id, $req_sen_id ){
  global $db;
  $frship = $db->query("SELECT * FROM friends WHERE
                        ((req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id')
                        OR
                        (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id'))
                        AND status = 1
                        ");
  if ($frship->rowCount() === 0 ) {
    $select = $db->query("SELECT * 
              FROM friends 
              WHERE (req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id') OR (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id')");
    if($select->rowCount() >= 1 ){
      $db->query("DELETE FROM friends 
      WHERE (req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id') 
      OR (req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id')");
    }
  }
}

function delete_friend($connected_id, $delete_id){
global $db;
$db->query("DELETE FROM friends 
    WHERE (req_rec_id = '$connected_id' AND req_sen_id = '$delete_id') 
    OR (req_rec_id = '$delete_id' AND req_sen_id = '$connected_id')");
}

function get_requests($id){
  global $db;
  $result = $db->query("SELECT * FROM friends WHERE req_rec_id = '$id' OR req_sen_id = '$id'");
  if ($result->rowCount() >= 1) { 
    $result = $result->fetchAll();
    return $result;
  }
}

// function is_friends($req_rec_id, $req_sen_id){
//   global $db;
//   $select = $db->query("SELECT * FROM friends WHERE
//     req_rec_id='$req_rec_id' AND req_sen_id='$req_sen_id'
//     OR
//     req_rec_id='$req_sen_id' AND req_sen_id='$req_rec_id'
//     ");
//    if($select->rowCount() >= 1 ) {
//       $select = $select->fetch();
//       if($select["status"]){
//         return 1;
//       }else {
//         return $select;
//       }
//    }else {
//        return 0;
//    }
// }

function get_posts($id){
  global $db;
  $result=array();
  $select = $db->query("SELECT posts.content, posts.created_at, posts.type,posts.owner_id,posts.in_group,posts.id AS post_id,
                                    users.first_name, users.last_name, users.username, users.avatar
                        FROM posts 
                        INNER JOIN users 
                        ON posts.owner_id = users.id 
                        ORDER BY posts.created_at DESC
                        ");
  if ($select->rowCount() >= 1) {
    $posts = $select->fetchAll();
    foreach ($posts as $post) {
      if ((is_friends($post["owner_id"], $id) === 1 || $post["owner_id"] === $id) && $post["in_group"]=="0") {
        $result[]=$post;
      }
    }
  }
  return $result;
}

function get_post_in_group($id){
  global $db;
  $result=array();
  $select = $db->query("SELECT posts.content, posts.created_at, posts.type,posts.owner_id,posts.id AS post_id,
                                    users.first_name, users.last_name, users.username, users.avatar
                        FROM posts 
                        INNER JOIN users 
                        ON posts.owner_id = users.id AND posts.in_group='$id'
                        ORDER BY posts.created_at DESC
                        ");
  if ($select->rowCount() >= 1) {
    return $select->fetchAll();
  }
  
}

function delete_posts($user_id, $post_id){

    global $db;
    
    $select = $db->query("SELECT * FROM posts WHERE id='$post_id' AND owner_id='$user_id'");
    if ($select->rowCount() >= 1) {
      $db->query("DELETE FROM posts WHERE id='$post_id'");
      return true;
    }else {
      return false;
    }
}


  function post($post_content,$post_owner,$type=0,$in_group=0){
    global $db;
    if($type==0){
        if($in_group==0){
          $db->query("INSERT INTO posts SET content='$post_content', owner_id='$post_owner'");
        }else{
          $db->query("INSERT INTO posts SET content='$post_content', owner_id='$post_owner', in_group='$in_group'");
        }
    }else{
        if($in_group==0){
          $db->query("INSERT INTO posts SET content='$post_content', owner_id='$post_owner', type='$type'"); 
        }else{
          $db->query("INSERT INTO posts SET content='$post_content', owner_id='$post_owner', type='$type', in_group='$in_group'");
        }
    }
  }