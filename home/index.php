<?php 
	require_once('../util/main.php');
	require_once('../util/images.php');
	require_once('../model/includes.php');
	
	$user_id=$_SESSION['fan']['id'];

	if (isset($_POST['action'])) {
    	$action = $_POST['action'];
	} elseif (isset($_GET['action'])) {
    	$action = $_GET['action'];
	}else {
    	$action = 'timeline';
	}

	switch ($action) {
		case 'timeline':
			if (isset($_POST["reply"]) && !empty($_POST["reply"])) {
            	$post_to_reply = $db->quote($_POST["submit_reply"]);
            	$reply_content = $db->quote($_POST["reply"]);
            	header("location: index.php?");
        	}
        	
        	$posts = get_posts($user_id);
       		$replies = get_replies();
      		$count_likes = count_likes();
      		
			include "timeline.php";
			break;
		case 'home':
			$username=$_GET['name'];
			if(empty($username) || !valid_username($username)){
				$msg="profile not found";
 			}else{
 				$profile_owner = get_by_username($username);
 				$prof_id = $db->quote($profile_owner["id"]);  
 				$select=get_friend($profile_owner["id"],$user_id);

 				$is_friends = false;
 				if(isset($select) && !empty($select)){
	 				if($select["status"]==1){ 
			              $is_friends = true; 
			            }else { 
			              $is_friends = "pending";
			              $sent_name=$username;
			        }
			    }
 				$posts=get_posts($profile_owner["id"]);
 				$replies=get_replies();
 				$count_likes=count_likes();

 				$friends_rel=get_friends($profile_owner["id"]);

 				if(isset($friends_rel) || !empty($friends_rel)){
	 				foreach ($friends_rel as $frship) {
			          if ($frship["req_rec_id"] === $profile_owner["id"]) {
			            $friend_id = $frship["req_sen_id"];
			          }else{
			            $friend_id = $frship["req_rec_id"];
			          }
			          $friends[]= get_by_id($friend_id);
			         }
		            }
		        }
 			include("home.php");
			break;
		case 'search':
			$q=$_GET['q'];
  			$temp=search_users($q);
  			$groups=search_groups($q);
  			$users=array();
  			foreach ($temp as $key_1=>$user) {
  				$k=array("check"=>is_friends($user["id"],$user_id));
  				$use =array_merge($user,$k);
  				$users[]=$use;
  			}
	      	include("search.php");
			break;
		case 'online':
  			$temp=search_users('');
			foreach ($temp as $key_1=>$user) {
  				$k=array("check"=>is_friends($user["id"],$user_id));
  				$use =array_merge($user,$k);
  				$users[]=$use;
  			}
			include("online.php");
			break;
		case 'post':
			$type='';
			$group='';
			$type=$_POST['type'];
			$group=$_POST['group'];
			if(empty($type)){
				if (!isset($_POST["post_content"]) || empty($_POST["post_content"])){
	 				$msg = "error";
	 				if(empty($group)){
						header("location: .");
					}else{
						$name=$_POST['name'];
						header("location: .?action=group&name=".$name);
					}
				}else {
					$post_content = trim($_POST["post_content"]);
					if(empty($group)){
						post($post_content,$user_id);
						header("location: .");
					}else{
						$name=$_POST['name'];
						if(is_member($group, $user_id)){
							post($post_content,$user_id,0,$group);
						}
						header("location: .?action=group&name=".$name);
					}
	  			}

	      	}else{
	      		if(isset($_FILES['image'])){
      				$file_name = $_FILES['image']['name']; 
     				$file_size =$_FILES['image']['size'];
      				$file_tmp =$_FILES['image']['tmp_name']; 
     				$file_type=$_FILES['image']['type'];
      				$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      				$expensions= array("jpeg","jpg","png");
      
      				if(in_array($file_ext,$expensions)=== false){
         					$msg="You cant update the type";
      				}
      
      				if($file_size > 2097152){
         					$msg='size file must be 2 MB';
     				 }
      
      				
      				if(empty($msg)==true){
      					if(empty($group)){
      						$path="images/".$_SESSION['fan']['username']."/posts/".$file_name;
      						if(!file_exists($path)){
         					move_uploaded_file($file_tmp,$path);
      						}
         					post($file_name,$user_id,$type);
         					header("location: .");
         				}else{
         					$name=$_POST['name'];
         					if(is_member($group, $user_id)){
	         					$path="images/".$name."/posts/".$file_name;
	         					if(!file_exists($path)){
	         					move_uploaded_file($file_tmp,$path);
	      						}
	         					post($file_name,$user_id,$type,$group);
	         				}
	         				header("location: .?action=group&name=".$name);
         				}
         				// $_SESSION['fan']['avatar']=$file_name;
      				}else{
      					if(empty($group)){
							header("location: .");
						}else{
							$name=$_POST['name'];
							header("location: .?action=group&name=".$name);
						}
      				}
  				}
	      	}
	      	break;
      	case 'delete_status':
      		$location='';
      		$post_id=$_GET['id'];
      		$location=$_GET['location'];
			if(delete_posts($user_id, $post_id)){
       			$_SESSION["delete_success"] = "success";
			}else {
				$_SESSION["delete_error"] = "wrong";
			}

			if (isset($_SESSION["delete_error"])){
      			$msg="some thing went wrong while deleting the status";
      			unset($_SESSION["delete_error"]);
    		}
   			if (isset($_SESSION["delete_success"])){
   				$msg="status successfully deleted";
   				unset($_SESSION["delete_success"]);
   			}
   			if(empty($location)){
   				header("location: .");
   			}else{
   				$name=$_GET['name'];
   				header("location: .?action=".$location."&name=".$name);
   			}
			break;
		case 'like':
			$location='';
			$location=$_GET['location'];
			if (isset($_GET["id"]) && !empty($_GET["id"])) {
  				$post_id =$_GET["id"];
  				if (!is_liked($user_id, $post_id)) {
  					like_posts($user_id, $post_id);
    			}
    		}
    		if(empty($location)){
   				header("location: .");
   			}else{
   				$name=$_GET['name'];
   				header("location: .?action=".$location."&name=".$name);
   			}
    		// include('timeline.php');
			break;
		case 'reply':
			$location='';
			$location=$_POST['location'];
			if (isset($_POST["reply"]) && !empty($_POST["reply"])) {
               $post_to_reply = $_POST["submit_reply"];
               $reply_content = $_POST["reply"];
               add_replys($user_id, $post_to_reply, $reply_content);
         	}
			if(empty($location)){
   				header("location: .");
   			}else{
   				$name=$_POST['name'];
   				header("location: .?action=".$location."&name=".$name);
   			}
			break;
		case 'add_request':
			$reciever_id = $_POST["add_request"];

			add_friend($user_id, $reciever_id);
			header("location: .?action=search&q=");
			break;
		case 'accept_request':
			$req_sen_id = $_POST["accept_request"];
			accept($req_sen_id, $user_id );
			header("location: .?action=search");
			break;
		case 'cancel_request':
			$req_rec_id = $_POST["cancel_request"];
			cancel_request($req_rec_id, $user_id );
			header("location: .?action=search&q=");
			break;
		case 'delete_friend':
			$delete_id = $_POST["delete_friend"];
			delete_friend($user_id, $delete_id);
			header("location: .?action=search&q=");
			break;
		case 'reset_password_view':
			include('reset_password.php');
			break;
		case 'reset_password':
			$email=$_SESSION['fan']['email'];
			$pass=$_POST['old_pass'];
			$new_pass=$_POST['new_pass'];
			$re_pass=$_POST['re_enter_pass'];
			$errors=[];
			$sucess=false;
			if(!isset($pass) || empty($pass)){
				$errors["password"] = "invalide password";
			}else{
				if(is_valid_user_login($email, $pass)){
					if(!isset($new_pass) || empty($new_pass)){
						$errors["new_password"] = "invalide new password";
					}

					if(!isset($re_pass) || empty($re_pass)){
						$errors["re_password"] = "invalide re_enter password";
					}

					if(empty($errors)){
						if($new_pass==$re_pass){
							reset_password($user_id,$email,$new_pass);
							$sucess=true;
						}else{
							$errors['check']="re_enter password incorrect!";
						}
					}

				}else{
					$errors['pass']="Incorrect password! Please, try again!";
				}
			}
			include("reset_password.php");
			break;
		case 'change_avatar':
			$group='';
			$group=$_POST['group'];
			$id=$_POST['id'];
			$name=$_POST['name'];
			$msg='';
			if($id==$user_id || (!empty($group) && is_group($user_id,$id))){
				if(isset($_FILES['image'])){
      				$file_name = $_FILES['image']['name']; 
     				$file_size =$_FILES['image']['size'];
      				$file_tmp =$_FILES['image']['tmp_name']; 
     				$file_type=$_FILES['image']['type'];
      				$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      				$expensions= array("jpeg","jpg","png");
      
      				if(in_array($file_ext,$expensions)=== false){
         					$msg="You cant update the type";
      				}
      
      				if($file_size > 2097152){
         					$msg='size file must be 2 MB';
     				 }
      				
      				if(empty($msg)==true){
      					$path_a="images/".$name."/avatar/".$file_name;
      					$path_b="images/".$name."/posts/".$file_name;
      					if(!file_exists($path_a)){
         					move_uploaded_file($file_tmp,$path_a);
      					}

      					if(!file_exists($path_b)){
         						copy($path_a, $path_b);
         				}
      						
      					
      					if(empty($group)){
	         				change_avatar($id, $file_name);
	         				post($file_name,$user_id,"1");
	         				unset($_SESSION['fan']['avatar']);
	         				$_SESSION['fan']['avatar']=$file_name;
	         			}else if($group==1){
	         				change_avatar($id,$file_name,true);
	         				post($file_name,$user_id,"1",$id);
	         			}
      				}
  				}
  			}			
  			if(empty($group)){
  				header("location: .?action=home&name=".$name);
  			}else{
  				header("location: .?action=group&name=".$name);
  			}
			break;
		case 'edit_profile':
			$user_info = get_by_id($user_id);
			if(isset($_POST['updated'])){
				
				$username=$_POST['username'];
				$firstname = trim($_POST["firstname"]);
				$lastname = trim($_POST["lastname"]);
				$email=$_POST['email'];
				$location = trim($_POST["location"]);
				$errors=[];
				$updated=$_POST['updated'];
				if (!empty($_POST)) {
				  
				  	if(isset($_POST['username']) && $_SESSION['fan']['username'] != $_POST['username']){
					  	
					  	if(!empty($username) && preg_match(('/^[a-zA-Z\-0-9_]+$/'), $username)){
							if(valid_username($username)){
								$errors["username"] = "username is already taken";
							}
						}else{
							$errors["username"] = "invalide username";
						}
				 	}
					  
				  	if(isset($_POST["firstname"]) && !empty($_POST)){
				    
				    	if(!preg_match('/^[a-zA-Z ,.\'-]+$/i', $firstname)){
				      		$errors["firstname"] = "invalide first name";
				    	}
				  	}

				  	if(isset($_POST["lastname"]) && !empty($_POST)){
				    
				   		if(!preg_match('/^[a-zA-Z ,.\'-]+$/', $lastname)){
				      		$errors["lastname"] = "invalide last name";
				    	}
				  	}

				  
				  	if(isset($_POST['email']) && $_SESSION['fan']['email'] != $_POST['email']){
					  	
					  	if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
							if(valid_email($email)){
								$errors["email"] = "email  is already taken";
							}
						}else{
								$errors["email"] = "invalide email";
						}
			  		}
				 

					if(isset($_POST["location"]) && !empty($_POST)){
					
					    if(!preg_match('/^[a-zA-Z ,.\'-]+$/', $location)){
					      $errors["location"] = "invalide location";
					    }
					}

				  if(empty($errors)){
				  	
					  	if(!edit_folder($_SESSION['fan']['username'],$username)){
					  		$errors['folder']="cant rename your folder";
					  	}else{
					  		if(update_profile($user_id, $username, $firstname, $lastname, $email, $location)){
					    		$updated = true;
				    		}
					  	}	
				  }
	  			}
	  		}
	  		$user_info = get_by_id($user_id);
    		$_SESSION['fan']=array();
    		$_SESSION['fan']=$user_info;
	  		include("edit_profile.php");
			break;
		case 'create_group':
			if(isset($_POST) && !empty($_POST)){
				$type=$_POST['type'];
				$name=$_POST['name'];
				$created=false;

				if($type=="0"){
					$errors['type']="you can choose type groups";
				}
				if(isset($name)){
					if(!empty($name)){//&& preg_match(('/^[a-zA-Z\-0-9_]+$/'), $name)
						if(valid_name($name) || valid_username($name)){
							$errors["name"]= "name is already taken";
						}
					}else{
						$errors["name"] = "invalide name group";
					}
				}

				if(empty($errors)){
					if(!mkdir_folder($name)){
						$errors['folder']="Cant create folder for your group";
					}
				}
 				if(empty($errors)){
 					if(add_group($user_id, $type, $name)){
 						$created=true;
 					}

 				}
			}
			include('create_group.php');
			break;
		case 'my_groups':
			$temp=get_by_userid($user_id);
			if(isset($temp)){
				foreach ($temp as $key => $group) {
	  				$k=array("check"=>is_new_members($group['id']));
	  				$new =array_merge($group,$k);
	  				$groups[]=$new;
	  			}
  			}
			include("my_groups.php");
			break;
		case 'group':
			$name=$_GET['name'];

			if(empty($name) || !valid_name($name)){
				$msg="profile not found";
 			}else{
 				$group = get_by_nameofgroup($name);  
 				$select_1=get_members($group['id']);
 				$select_2=get_spending($group['id']);
 				$is_my_group=is_owner($group['id'],$user_id);
 				$is_member=is_member($group['id'],$user_id);
 				$posts=get_post_in_group($group['id']);
 				$replies=get_replies();
 				$count_likes=count_likes();

 				if(isset($select_1) || !empty($select_1)){
	 				foreach ($select_1 as $member){
			          $members[]= get_by_id($member['member_id']);
	 				}
		        }	

		    // //     if(isset($select_2) || !empty($select_2)){
	 				// // foreach ($select_2 as $spending){
			   // //        $spendings[]= get_by_id($spending['id']);
	 				// // }
		    // //     }		
		    }
			include('group.php');
			break;
		case 'like_page':
			$name=$_POST['name'];
			$id=$_POST['group_id'];
			add_member($id,$user_id);
			header("location: .?action=group&name=".$name);
			break;
		case 'dislike_page':
			$name=$_POST['name'];
			$id=$_POST['group_id'];
			delete_member($id,$user_id);
			header("location: .?action=group&name=".$name);
			break;
			break;
		case 'logout':
			set_online($user_id,"0");
			$_SESSION['fan']=array();
			header("Location: ../");
			break;
	}

 ?>