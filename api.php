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
$location = ltrim($config['sitePath'],"/");
//Connect and install if needed.
$db = new JSONDatabase($config['databaseName'], $config['databaseLocation']);


if(!isset($_POST['type'])){
	die("OOPS");
}
if(cleanstring($_POST['type']) !== "register" && cleanstring($_POST['type']) !== "login"){
	if(!isset($_SESSION['username'])){
		die("OOPS - NOT LOGGED IN.");
	}
}
switch(cleanstring($_POST['type'])){
	case "register":
		if(!$config['registration']){
			die("Registration is DISABLED.");
		}
		//Check if username is taken:
		if(isset($_POST['username'],$_POST['name'],$_POST['email'],$_POST['password1'],$_POST['password2'])){
			$name = cleanstring($_POST['name']);
			$username = cleanstring($_POST['username']);
			$email = cleanstring($_POST['email']);
			$password1 = cleanstring($_POST['password1']);
			$password2 = cleanstring($_POST['password2']);
			$profilepic = "default.jpg";
			$get = $db->select("users","username",$username);
			if(count($get) > 0){
				//username exists.
				$_SESSION['error'] = "That username already exists, please try again.";
				header("Location: $location/page/register");
				die();
			}
			$get = $db->select("users","email",$email);
			if(count($get) > 0){
				//username exists.
				$_SESSION['error'] = "That email already in use, please try again.";
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
					"friends"		=> json_encode(array(""=>"")),
					);
					if($db->insert("users", json_encode($data))){
						$_SESSION['message'] = "User $username created! Please login to continue!";
						header("Location: $location/page/home");
						die();
					} else {
						$_SESSION['error'] = "Failed to create user for an unknown reason. Please contact the system admin!";
						header("Location: $locationpage/register");
						die();
					}
			} else {
				$_SESSION['error'] = "Passwords did not match, please try again!";
				header("Location: $location/page/register");
				die();
			}
		} else {
			$_SESSION['error'] = "Please use the API properly.";
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
			$user = $db->select("users","username",$username);
			if(count($user) === 1){
				$user = array_values($user);
				$user = $user[0];
				//Found user, compare password.
				if(password_verify($password,$user['password'])){
					$_SESSION['username'] = $user['username'];
					$_SESSION['profilePic'] = $user['profilePic'];
					$_SESSION['friends'] = $user['friends'];
					$_SESSION['name'] = $user['name'];
					session_regenerate_id(true);
					header("Location: $location/page/dash");
					die();
				} else {
					$_SESSION['error'] = "The username or password entered is incorrect.";
					header("Location: $location/page/home");
					die();
				}
			} else {
				$_SESSION['error'] = "INTERNAL ERROR.";
				header("Location: $location/page/home");
				die();
			}
		} else {
			$_SESSION['error'] = "The username or password entered is incorrect.";
			header("Location: $location/page/home");
			die();
		}
		break;
	case "post":
		//Nice
		if(isset($_POST['data'])){
			//Will need to pre-process post but for quick test...
			$id = bin2hex(random_bytes(24));
			$data = array(
				"post"		=> cleanstring($_POST['data']),
				"author"	=> $_SESSION['username'],
				"date"		=> date('m-d-Y h:i:s a'),
				"likes"		=> 0,
				"post_id"	=> $id,
				);
			if(!$db->insert("posts", json_encode($data))){
				$_SESSION['error'] = "Error posting, please try again!";
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
			break;
		}
		$f = cleanstring($_POST['friend']);
		$user = $db->select("users","username",$_SESSION['username']);
		$user = array_values($user);
		$user = $user[0];
		$friends = json_decode($user['friends']);
		if(!empty($friends)){
			if(in_array($f, $friends)){
				break;
			}
			$tuser = $db->select("users","username",$f);
			$tuser = array_values($tuser);
			$tuser = $tuser[0];
			if(!empty($tuser)){
				$friends[] = $f;
				$nF = json_encode($friends);
				$ndata = json_encode(array("friends"=>$nf));
				$db->insert("users",$ndata,$user['row_id']);
			}
		}
		break;
	case "remove":
		
		break;
	default:
		
		break;
}