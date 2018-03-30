<?php
//Entry point for all php.
if (!isset($_SESSION)) {
    session_start();
};
session_regenerate_id(true);
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
$location = $config['sitePath'];
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
					"profilePic"	=> $profilepic
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
		
		break;
	case "post":
		
		break;
	case "add":
		
		break;
	case "remove":
		
		break;
	default:
		
		break;
}