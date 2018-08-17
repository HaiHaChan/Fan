<?php 

function is_liked($like_owner_id, $like_post_id){
  global $db;
  $select = $db->query("SELECT * FROM likes WHERE like_owner_id='$like_owner_id' AND like_post_id='$like_post_id'");
  if ($select->rowCount() >= 1 ) {
    return true;
  }else {
    return false;
  }
}

function like_posts($user_id, $post_id){
	global $db;
	$db->query("INSERT INTO likes SET like_owner_id='$user_id', like_post_id='$post_id'");
}

function count_likes(){
	global $db;
	$count_likes = $db->query("SELECT * FROM likes");

	if ($count_likes->rowCount() >= 1) {
	  $count_likes = $count_likes->fetchAll();
	}
	return $count_likes;
}

