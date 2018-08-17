<?php
// Get the document root
$doc_root = $_SERVER['DOCUMENT_ROOT'];

// Get the application path
$uri = $_SERVER['REQUEST_URI'];
$dirs = explode('/', $uri);
$app_path = '/' . $dirs[1] . '/';

// Set the include path
set_include_path($doc_root . $app_path);

// Get common code
require_once('util/tags.php');
require_once('model/database.php');

// Define some common functions
function display_db_error($error_message) {
    global $app_path;
    include 'errors/error.php';
    exit;
}

function display_error($error_message) {
    global $app_path;
    include 'errors/error.php';
    exit;
}

function redirect($url) {
    // session_write_close();
    header("Location: " . $url);
    exit;
}

function mkdir_folder($username){
	try {
		if(!empty($username)){
				$doc_root = $_SERVER['DOCUMENT_ROOT'];
				$uri = $_SERVER['REQUEST_URI'];
				$dirs = explode('/', $uri);
				$app_path = '/' . $dirs[1] . '/';
				$path=$doc_root . $app_path."home/images/".$username;
				$fd=mkdir($path);
				$fd1=mkdir($path.'/avatar');
				$fd2=mkdir($path.'/posts');
				if(is_dir($path) && is_dir($path.'/avatar') && is_dir($path.'/posts')){
					copy($doc_root . $app_path."icons/home.jpg",$path.'/avatar/home.jpg');
					return true;
				}else{
					return false;
				}
		}else{
			return false;
		}
	} catch (Exception $e) {
		return $e;
	}
}

function edit_folder($old,$new){
	try {
		if(!empty($old) && !empty($new)){
				$doc_root = $_SERVER['DOCUMENT_ROOT'];
				$uri = $_SERVER['REQUEST_URI'];
				$dirs = explode('/', $uri);
				$app_path = '/' . $dirs[1] . '/';
				$path_old=$doc_root . $app_path."home/images/".$old."/";
				$path_new=$doc_root . $app_path."home/images/".$new."/";
				rename($path_old, $path_new);
				return true;
		}else{
			return false;
		}
	} catch (Exception $e) {
		return $e;
	}
}

// Start session to store user and cart data
session_start();
// if (isset($_SESSION["auth"])) {
// 	$id= $_SESSION["auth"]["id"];
// 	$user = $db->query("SELECT * FROM users WHERE id=$id")->fetch();
// }









// if (!isset($auth)) {
// 	if((!isset($_SESSION['auth']['username']) && !isset($_SESSION['auth']['password']))
// 		|| ($_SESSION["auth"]["password"] != $user["password"])
// 	){
// 	header("location: ".SCRIPTROOT."index.php");
// 		die();
// 	}
// }
?>
