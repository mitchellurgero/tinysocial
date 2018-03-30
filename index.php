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
//Connect and install if needed.
$db = new JSONDatabase($config['databaseName'], $config['databaseLocation']);
if(!$db->check_table("admin")){
	$db->create_table("admin");
	$db->insert("admin", json_encode(array("DEFAULT"=>true)));
	if(!$db->check_table("users")){
		$db->create_table("users");
		$db->insert("users", json_encode(array("DEFAULT"=>true)));
	}
	if(!$db->check_table("posts")){
		$db->create_table("posts");
		$db->insert("posts", json_encode(array("DEFAULT"=>true)));
	}
}


$a1 = parse_url($config['sitePath'], PHP_URL_PATH);
$a2 = $_SERVER['REQUEST_URI'];
$a2 = str_replace($a1,"",$a2);
$path = ltrim($a2, '/');
$elements = explode('/', $path);
$args = array();
if(empty($elements[0])) {
    $args['type'] = "page";
    $args['page'] = "home";
} else {
	$args['type'] = $elements[0];
	$args['page'] = $elements[1];
	if(count($elements) > 2){
		$args['params'] = array_slice($elements, 2);
	}
}
//var_dump($args);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="<?php echo $config['siteDesc']; ?>">
	    <meta name="author" content="<?php echo $config['siteName']; ?>">
	    <title><?php echo $config['siteName']; ?></title>
	    <link href="<?php echo $config['sitePath']; ?>css/bootstrap.min.css" rel="stylesheet">
	    <style>
	    	html {
			  position: relative;
			  min-height: 100%;
			}
			body {
			  margin-bottom: 60px; /* Margin bottom by footer height */
			}
			.footer {
			  position: absolute;
			  bottom: 0;
			  width: 100%;
			  height: 60px; /* Set the fixed height of the footer here */
			  line-height: 60px; /* Vertically center the text there */
			  background-color: #f5f5f5;
			}
	    </style>
	</head>
	<body>
		<?php
		if(!isset($_SESSION['username'])){
			?>
		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<a class="navbar-brand" href="<?php echo $config['sitePath']; ?>"><?php echo $config['siteName']; ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/home/">Home</a></li>
			</ul>
			</div>
		</nav>
			<?php
		} else {
			?>
		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<a class="navbar-brand" href="<?php echo $config['sitePath']; ?>"><?php echo $config['siteName']; ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/dash/">Dashboard</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/settings/">Settings</a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>logout.php">Logout</a></li>
			</ul>
			</div>
		</nav>
			<?php
		}
		?>

		<div class="container-fluid">
		<?php
		if($args['type']){
			$type = cleanstring(str_replace(array(".."),array(""),$args['type']));
			switch(cleanstring($type)){
				case "page":
					$p = cleanstring(str_replace(array(".."),array(""),$args['page']));
					if(file_exists(__DIR__."/pages/$p.php")){
						include(__DIR__."/pages/$p.php");
					} else {
						include(__DIR__."/pages/404.php");
					}
					break;
				case "user":
					
					break;
				case "post":
					
					break;
				default:
					//oops! Not found!
					break;
			}
		} else {
			//404
		}
		?>
		</div>
<footer class="footer">
	<div class="container">
		<span class="text-muted">Place sticky footer content here.</span>
	</div>
</footer>
<script
src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>
	</body>
</html>

<?php


?>