<?php 
	$fan=0;
	require_once('/util/main.php');

	require_once('/model/database.php');
	require_once('/model/users_db.php');
	// require_once();
	$error_message='';
	if (isset($_POST['action'])) {
    	$action = $_POST['action'];
	} elseif (isset($_GET['action'])) {
    	$action = $_GET['action'];
	} elseif (isset($_SESSION['fan'])) {
    	$action = 'login_view';
	} else {
    	$action = 'login_view';
	}

	switch ($action) {
		case 'login_view':
			include 'login.php';
			break;
		case 'login':
			try {
				$errors=[];
				$email=$_POST['email'];
				$pass=$_POST['password'];
				if(is_valid_user_login($email, $pass)){
					$_SESSION['fan']['email']=$email;
					$user=get_by_email($email);
					$_SESSION["fan"] = $user;
					set_online($user['id']);
					redirect('home');
				}else{
					$errors['login']="incorrect! you can try again?";
					include 'login.php';
				}
			} catch (Exception $e) {
				$error_message=$e;
				include 'errors/error.php';
			}
			break;
		case 'home':
			redirect('home');
			break;
		case 'signup':
			if(!empty($_POST)){
			try {
				$firstname=$_POST['firstname'];
				$lastname=$_POST['lastname'];
				$username=$_POST['username'];
				$password=$_POST['reg_passwd__'];
				$email=$_POST['reg_email__'];
				$birthday=$_POST['birthday_year']."-".$_POST['birthday_month']."-".$_POST['birthday_day'];
				$sex=$_POST['sex'];

				$errors = [];
				$created=false;
				if(isset($firstname)){
					if(empty($firstname) || !preg_match(('/^[a-zA-Z\-0-9_]+$/'), $firstname)){
						$errors["firstname"] = "invalide firstname";
					}
				}
				if(isset($lastname)){
					if(empty($lastname) || !preg_match(('/^[a-zA-Z\-0-9_]+$/'), $lastname)){
						$errors["lastname"] = "invalide lastname";
					}
				}
				if(isset($username)){
					if(!empty($username) && preg_match(('/^[a-zA-Z\-0-9_]+$/'), $username)){
						if(valid_username($username)){
							$errors["username"] = "username is already taken";
						}
					}else{
						$errors["username"] = "invalide username";
					}
				}

				if(isset($email)){
					if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						if(valid_email($email)){
							$errors["email"] = "email  is already taken";
						}
					}else{
							$errors["email"] = "invalide email";
					}
				}

				if(!isset($password) || empty($password)){
					$errors["password"] = "invalide password";
				}

				if(empty($errors)){
					if(!mkdir_folder($username)){
						$errors['folder']="dont create your folder";
					}
				}

				if(empty($errors)){
					$created=add_user($firstname, $lastname, $username, $password, $email, $birthday, $sex);
					if($created){
						include "login.php";
					}
				} else{
					include "login.php";
				}

				} catch (Exception $e) {
					$error_message=$e;
					include 'errors/error.php';
				}
			}
			break;
	}

 ?>