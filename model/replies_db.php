<?php

function get_replies(){
	global $db;
	$replies = $db->query("SELECT replies.owner_id, replies.post_reply_id, replies.reply_content,replies.created_at,
	                  users.first_name, users.last_name, users.username, users.avatar
	                   FROM replies LEFT JOIN users ON
	                   replies.owner_id = users.id ORDER BY replies.created_at ASC
	                   ");
	if ($replies->rowCount() >= 1) {
	  $replies = $replies->fetchAll();
	}
	return $replies;
}
  
function add_replys($user_id, $post_to_reply, $reply_content){
	global $db;
	$db->query("INSERT INTO replies 
	  SET owner_id='$user_id', post_reply_id='$post_to_reply', reply_content='$reply_content'");
}

