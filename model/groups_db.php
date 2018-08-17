<?php

function get_by_userid($id){
    global $db;
   $select = $db->query("SELECT * FROM groups WHERE
     owner_id='$id'
     "); 
    if ($select->rowCount() >= 1) { 
      return $select->fetchAll();
    }
}

function get_owner_id($id){
  global $db;
  $id = $db->quote($id);
  $select = $db->query("SELECT * FROM groups WHERE id=$id");
  if($select->rowCount() >= 1){
    $select = $select->fetch();
    return $select['owner_id'];
  }else {
    return NULL;
  }
}

function is_owner($group_id, $user_id){
  if(get_owner_id($group_id)==$user_id){
    return true;
  }else{
    return false;
  }
}


function get_by_nameofgroup($name){
  global $db;
  $name = $db->quote($name);
  $selects = $db->query("SELECT * FROM groups WHERE name=$name");
  if($selects->rowCount() >= 1){
    return $selects->fetch();
  }else {
    return array();
  }
}

function valid_name($name){
  global $db;
  $select = $db->query("SELECT name FROM groups WHERE name='$name'");
  if ($select->rowCount() >= 1 ) {
    return true;
  }else{
    return false;
  }
}

function add_group($owner_id, $type, $name, $avatar='home.jpg') {
    global $db;
    $query = '
        INSERT INTO groups (owner_id, name, type, avatar)
        VALUES (:owner_id, :name, :type, :avatar)';
    $statement = $db->prepare($query);

    $statement->bindValue(':owner_id', $owner_id);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':type', $type);
    $statement->bindValue(':avatar', $avatar);
    $statement->execute();
    $valid = ($statement->rowCount() == 1);
    $statement->closeCursor();
    return $valid;
}

function add_member($group_id,$user_id, $status=1){
  global $db;
    $query = '
        INSERT INTO members (group_id, member_id, status)
        VALUES (:group_id, :member_id, :status)';
    $statement = $db->prepare($query);

    $statement->bindValue(':group_id', $group_id);
    $statement->bindValue(':member_id', $user_id);
    $statement->bindValue(':status', $status);
    $statement->execute();
    $valid = ($statement->rowCount() == 1);
    $statement->closeCursor();
    return $valid;
}

function delete_member($group_id,$member_id){
  global $db;
  $check = $db->query("SELECT * FROM members WHERE
                        (group_id='$group_id' AND member_id='$member_id')
                        AND status = 1
                        ");
  if ($check->rowCount() > 0 ) {
      $db->query("DELETE FROM members 
      WHERE (group_id='$group_id' AND member_id='$member_id')");
  }
}

function is_group($user_id, $group_id){
  global $db;
    $query = '
        SELECT * FROM groups
        WHERE owner_id = :user_id AND id = :group_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':group_id', $group_id);
    $statement->execute();
    $valid = ($statement->rowCount() == 1);
    $statement->closeCursor();
    return $valid;

}

function is_new_members($id){
  global $db;
  $select = $db->query("SELECT * FROM members WHERE
    group_id='$id'
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

function get_members($group_id){
  global $db;
  $members = $db->query("SELECT * FROM members WHERE group_id = '$group_id' AND status = 1");
  if ($members->rowCount() >= 1 ) {
    return $members->fetchAll();
  }else {
    return null;
  }
}


function get_spending($group_id){
  global $db;
  $spendings = $db->query("SELECT * FROM members WHERE group_id = '$group_id' AND status != 1");
  if ($spendings->rowCount() >= 1 ) {
    return $spendings->fetchAll();
  }else {
    return null;
  }
}

function is_member($group_id, $user_id){
  global $db;
  $select = $db->query("SELECT * FROM members 
    WHERE group_id='$group_id'  AND member_id='$user_id'
    ");
   if($select->rowCount() >= 1 ) {
      return 1;
   }else {
       return 0;
   }
}

function search_groups($q=''){
  global $db;
  if(!empty($q)){
            $search_data_ents = trim(addslashes($q));
            $search_data_ent = preg_split("/[\s]+/",$search_data_ents);
            foreach ($search_data_ent as $search_data) {
              $search_data = $db->quote($search_data);
              $groups = $db->query('SELECT * FROM groups 
                WHERE groups.name LIKE "'.$search_data.'%"'.
                'ORDER BY name ASC');
            }

            if (isset($groups)) {
              $groups = $db->query('SELECT * FROM groups 
                WHERE groups.name LIKE "'.$search_data_ents.'%"'.
                'ORDER BY name ASC');
            }
        } else {
          $groups = $db->query("SELECT * FROM groups");
        }
  return $groups;
}
