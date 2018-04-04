<?php
//Entry point for all php.
if (!isset($_SESSION)) {
    session_start();
};
require_once("app/includes.php");
define("TINY", TRUE);
$ver = new QuickGit();
define("VERSHORT", $ver->version()['short']);
define("VERLONG", $ver->version()['full']);
date_default_timezone_set($config['timezone']);
if($config['debug']){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$location = rtrim($config['sitePath'],"/");
//Connect and install if needed.
$db = new JSONDatabase($config['databaseName'], $config['databaseLocation']);


if(!isset($_POST['type'])){
	die("OOPS");
}
if(cleanstring($_POST['type']) !== "register" && cleanstring($_POST['type']) !== "login"){
	if(!isset($_SESSION['username'])){
		die($lang['notLoggedIn']);
	}
}
Event::handle('ApiLoad',array(&$_POST));
//Before connecting to backend, let's check the session is at the very least not malicous.
if(isset($_SESSION['username']) && isset($_SESSION['_USER_LOOSE_IP'])){
	if($_SESSION['_USER_LOOSE_IP'] != long2ip(ip2long($_SERVER['REMOTE_ADDR']) & ip2long("255.255.0.0"))
    || $_SESSION['_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']
    || $_SESSION['_USER_ACCEPT_ENCODING'] != $_SERVER['HTTP_ACCEPT_ENCODING']
    || $_SESSION['_USER_ACCEPT_LANG'] != $_SERVER['HTTP_ACCEPT_LANGUAGE']){
    	//Bad session detected, let's not allow any further data to be transfered and redirect to logout.
    	session_unset(); // Same as $_SESSION = array();
	    session_destroy(); // Destroy session on disk
	    setcookie("sid", "", 1);
		header("Location: logout.php");
		die();
    }
    $_SESSION['_USER_LAST_ACTIVITY'] = time(); //Reset user activity timer
} else {
	//Store identification data so we can detect malicous logins potentially. (Like XSS)
	$_SESSION['_USER_AGENT']           = $_SERVER['HTTP_USER_AGENT']; //Save user agent (Spoofable, so we have the other stuff below to check for as well which may or may not be a little more difficult to guess for an attacker.)
	$_SESSION['_USER_ACCEPT_ENCODING'] = $_SERVER['HTTP_ACCEPT_ENCODING'];
	$_SESSION['_USER_ACCEPT_LANG']     = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$_SESSION['_USER_LOOSE_IP'] = long2ip(ip2long($_SERVER['REMOTE_ADDR']) & ip2long("255.255.0.0"));
	$_SESSION['_USER_LAST_ACTIVITY'] = time();
}

switch(cleanstring($_POST['type'])){
	case "register":
		if(!$config['registration']){
			die($lang['disabledRegistration']);
		}
		$_SESSION['tempStore'] = $_POST;
		unset($_SESSION['tempStore']['password1']);
		unset($_SESSION['tempStore']['password2']);
		if(isset($_POST['username'],$_POST['name'],$_POST['email'],$_POST['password1'],$_POST['password2'],$_POST['captcha'])){
			if(!(strtolower($_SESSION['captcha']['code']) === strtolower($_POST['captcha']))){
				$_SESSION['error'] = $lang['invalidCaptcha'];
				header("Location: $location/page/register");
				die();
			}
			$name = cleanstring($_POST['name']);
			$username = strtolower(cleanstring($_POST['username']));
			if(strlen($username) < 4 || strlen($username) > 24){
				$_SESSION['error'] = $lang['badUsername'];
				header("Location: $location/page/register");
				die();
			}
			if(!(strlen($_POST['password1']) >= 4)){
				$_SESSION['error'] = $lang['badPassword'];
				header("Location: $location/page/register");
				die();
			}
			if(!preg_match('/[\'^£!@#$%^&*()]/', $_POST['password1'])){
				$_SESSION['error'] = $lang['badPassword'];
				header("Location: $location/page/register");
				die();
			}
			$email = cleanstring($_POST['email']);
			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];
			$profilepic = "default.jpg";
			$get = $db->select("users","username",$username);
			if(count($get) > 0){
				//username exists.
				$_SESSION['error'] = $lang['usernameTaken'];
				header("Location: $location/page/register");
				die();
			}
			$get = $db->select("users","email",$email);
			if(count($get) > 0){
				//username exists.
				$_SESSION['error'] = $lang['emailTaken'];
				header("Location: $location/page/register");
				die();
			}
			//email and user clean, let's continue.
			if($password1 === $password2){
				$hash = password_hash($password1, PASSWORD_DEFAULT);
				$data = array(
					"username"		=> $username,
					"password"		=> $hash,
					"email"			=> $email,
					"name"			=> $name,
					"profilePic"	=> $profilepic,
					"friends"		=> json_encode(array("")),
					);
					if($db->insert("users", json_encode($data))){
						$_SESSION['message'] = str_replace(array("%u"),array($username),$lang['newUserCreated']);
						unset($_SESSION['tempStore']);
						header("Location: $location/page/home");
						die();
					} else {
						$_SESSION['error'] = $lang['failedRegistration'];
						header("Location: $locationpage/register");
						die();
					}
			} else {
				$_SESSION['error'] = $lang['passMismatch'];
				header("Location: $location/page/register");
				die();
			}
		} else {
			$_SESSION['error'] = $lang['invalidAPI'];
			header("Location: $location/page/register");
			die();
		}
		break;
	case "login":
		//Need to login now
		if(isset($_POST['username'], $_POST['password'])){
			//Check given password:
			$username = cleanstring($_POST['username']);
			$password = $_POST['password'];
			$user = '';
			if(strpos($username,"@") !== false){
				$user = $db->select("users","email",$username);
			} else {
				$user = $db->select("users","username",$username);
			}
			$user = array_values($user);
			if(!isset($user[0])){
				$_SESSION['error'] = $lang['uapIncorrect'];
				header("Location: $location/page/home");
				die();
				break;
			}
			$user = $user[0];
			if(!empty($user)){
				//Found user, compare password.
				if(password_verify($password,$user['password'])){
					$_SESSION['username'] = $user['username'];
					$_SESSION['profilePic'] = $user['profilePic'];
					$_SESSION['friends'] = $user['friends'];
					$_SESSION['name'] = $user['name'];
					$_SESSION['lang'] = $config['defaultLang'];
					session_regenerate_id(true);
					header("Location: $location/page/dash");
					die();
				} else {
					$_SESSION['error'] = $lang['uapIncorrect'];
					header("Location: $location/page/home");
					die();
				}
			} else {
				$_SESSION['error'] = $lang['uapIncorrect'];
				header("Location: $location/page/home");
				die();
			}
		} else {
			$_SESSION['error'] = $lang['uapIncorrect'];
			header("Location: $location/page/home");
			die();
		}
		break;
	case "post":
		//Nice
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		if(isset($_POST['data'])){
			//Will need to pre-process post but for quick test...
			$id = bin2hex(random_bytes(24));
			$data = array(
				"post"		=> cleanstring($_POST['data']),
				"author"	=> $_SESSION['username'],
				"date"		=> date('m-d-Y h:i:s a'),
				"likes"		=> "{}",
				"like_counter"=>0,
				"post_id"	=> $id,
				);
			if(!$db->insert("posts", json_encode($data))){
				$_SESSION['error'] = $lang['generalError'];
				header("Location: $location/page/dash");
				die();
			} else {
				header("Location: $location/post/$id");
				die();
			}
		}
		break;
	case "add":
		//time to add to friends list
		if(!isset($_POST['friend'])){
			header("Location: $location/user/".$_POST['friend']);
			die();
			break;
		}
		//Clean string and get current users friend list
		$f = cleanstring($_POST['friend']);
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$friends = json_decode($user['friends']);
		if(in_array($f, $friends)){
			header("Location: $location/user/".$_POST['friend']);
			die();
			break;
		}
		//check if requested friend exists as user.
		$tuser = $db->select("users","username",$f);
		$tuser = array_values($tuser);
		$tuser = $tuser[0];
		if(!empty($tuser)){
			$friends[] = $f;
			$nF = json_encode($friends);
			$user['friends'] = $nF;
			if($db->insert("users",json_encode($user),$user['row_id'])){
				$_SESSION['message'] = "Added $f as a friend!";
			} else {
				$_SESSION['error'] = $lang['generalError'];
			}
		}
		header("Location: $location/user/$f");
		die();
		break;
	case "remove":
		//time to add to friends list
		if(!isset($_POST['friend'])){
			header("Location: $location/user/".$_POST['friend']);
			die();
			break;
		}
		//Clean string and get current users friend list
		$f = cleanstring($_POST['friend']);
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$friends = json_decode($user['friends']);
		if(!in_array($f, $friends)){
			header("Location: $location/user/".$_POST['friend']);
			die();
			break;
		}
		//check if requested friend exists as user.
		$tuser = $db->select("users","username",$f);
		$tuser = array_values($tuser);
		$tuser = $tuser[0];
		if(!empty($tuser)){
			if (($key = array_search($f, $friends)) !== false) {
    			unset($friends[$key]);
			}
			$nF = json_encode($friends);
			$user['friends'] = $nF;
			if($db->insert("users",json_encode($user),$user['row_id'])){
				$_SESSION['message'] = "Removed $f from friends!";
			} else {
				$_SESSION['error'] = $lang['generalError'];
			}
		}
		header("Location: $location/user/$f");
		die();
		break;
	case "comment":
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$post_id = cleanstring($_POST['post_id']);
		//Do some more cleaning but for now:
		$data = array(
				"post"		=> cleanstring($_POST['data']),
				"author"	=> $_SESSION['username'],
				"date"		=> date('m-d-Y h:i:s a'),
				"post_id"	=> $post_id,
				);
		//Before allowing comment, check that post exists.
		$fatherPost = $db->select("posts","post_id",$post_id);
		if(count($fatherPost) > 0){
			//Post exists, let's comment
			if($db->insert("comments", json_encode($data))){
				$_SESSION['message'] = "Comment saved!";
			} else {
				$_SESSION['error'] = $lang['generalError'];
			}
		} else {
			$_SESSION['error'] = $lang['commentNonExist'];
		}
		header("Location: $location/post/$post_id");
		die();
		break;
	case "changeName":
		$newName = cleanstring($_POST['name']);
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$user['name'] = $newName;
		if($db->insert("users",json_encode($user),$user['row_id'])){
			$_SESSION['message'] = "Changed your name!";
		} else {
			$_SESSION['error'] = $lang['generalError'];
		}
		header("Location: $location/page/settings");
		die();
		break;
	case "changePassword":
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		if($_POST['password1'] == $_POST['password2']){
			$user['password'] = password_hash($_POST['password1'],PASSWORD_DEFAULT);
			if($db->insert("users",json_encode($user),$user['row_id'])){
				$_SESSION['message'] = $lang['passwordChanged'];
			} else {
				$_SESSION['error'] = $lang['generalError'];
			}
		} else {
			$_SESSION['error'] = $lang['passMismatch'];
		}
		header("Location: $location/page/settings");
		die();
		break;
	case "changeProfilePic":
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$newPic = cleanstring($_POST['pic']);
		$user['profilePic'] = $newPic;
		if($db->insert("users",json_encode($user),$user['row_id'])){
			$_SESSION['message'] = $lang['profilePicChanged'];
		} else {
			$_SESSION['error'] = $lang['generalError'];
		}
		header("Location: $location/page/settings");
		die();
		break;
	case "like":
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		if(isset($_POST['post'])){
			$post = $_POST['post'];
			$post = $db->select("posts","post_id",$post);
			$post = array_values($post);
			$post = $post[0];
			if(!empty($post)){
				//add like to post
				$likes = json_decode($post['likes'], true);
				if(in_array($user['username'], $likes)){
					$_SESSION['error'] = $lang['likeError'];
					header("Location: $location/post/".$post['post_id']);
					die();
					break;
				} else {
					$likes[] = $_SESSION['username'];
					$post['like_counter']++;
					$post['likes'] = json_encode($likes);
					if($db->insert("posts",json_encode($post),$post['row_id'])){
						$_SESSION['message'] = $lang['likeSuccess'];
						header("Location: $location/post/".$post['post_id']);
						die();
						break;
					} else {
						$_SESSION['error'] = $lang['generalError'];
						header("Location: $location/page/dash/");
						die();
						break;
					}
				}
			} else {
				$_SESSION['error'] = $lang['generalError'];
				header("Location: $location/page/dash/");
				die();
				break;
			}
		}
		break;
	default:
		
		break;
}