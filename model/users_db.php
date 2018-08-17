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

function get_by_email ($email){
  global $db;
  $email = $db->quote($email);
  $select_users = $db->query("SELECT * FROM users WHERE email=$email");
  if($select_users->rowCount() >= 1){
      return $select_users->fetch();
  }else{
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

function valid_email($email){
  global $db;
  $email = $db->quote($email);
  $select = $db->query("SELECT email FROM users WHERE email=$email");
  if($select->rowCount() >= 1){
    return true;
  }else{
    return false;
  }
}


function is_valid_user_login($email, $password) {
    global $db;
    $password = sha1($email . $password);
    $query = '
        SELECT * FROM users
        WHERE email = :email AND password = :password';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $password);
    $statement->execute();
    $valid = ($statement->rowCount() == 1);
    $statement->closeCursor();
    return $valid;
}

function set_online($user_id, $online=1){
    global $db;
    $query = '
        UPDATE users
        SET online = :online
        WHERE id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':online', $online);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();

}

function add_user($first_name, $last_name, $username, $password, $email, $birthday, $sex, $location='', $avatar="home.jpg") {
    global $db;
    $password = sha1($email . $password);
    $query = '
        INSERT INTO users (first_name, last_name, username, password, email, birthday, sex, location, avatar)
        VALUES (:first_name, :last_name, :username, :password, :email, :birthday, :sex, :location, :avatar)';
    $statement = $db->prepare($query);

    $statement->bindValue(':first_name', $first_name);
    $statement->bindValue(':last_name', $last_name);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':birthday', $birthday);
    $statement->bindValue(':sex', $sex);
    $statement->bindValue(':location', $location);
    $statement->bindValue(':avatar', $avatar);
    $statement->execute();
    $valid = ($statement->rowCount() == 1);
    $statement->closeCursor();
    return $valid;
}

function update_profile($user_id, $username, $first_name, $last_name, $email, $location) {
    global $db;
    $query = '
        UPDATE users
        SET username = :username,
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            location = :location
        WHERE id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':first_name', $first_name);
    $statement->bindValue(':last_name', $last_name);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':location', $location);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();   
}

function reset_password($user_id, $email,$new_password){
  global $db;
   $password = sha1($email . $new_password);
    $query = '
        UPDATE users
        SET password = :password
        WHERE id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();
}

function search_users($q=''){
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

function change_avatar($id, $avatar, $is_group=false){
  global $db;
  if($is_group){
      $query = '
      UPDATE groups
      SET avatar = :avatar
      WHERE id = :id';
  }else{
      $query = '
      UPDATE users
      SET avatar = :avatar
      WHERE id = :id';
  }
  $statement = $db->prepare($query);
  $statement->bindValue(':avatar', $avatar);
  $statement->bindValue(':id', $id);
  $statement->execute();
  $statement->closeCursor();
}