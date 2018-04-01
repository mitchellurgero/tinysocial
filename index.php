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
	if(!$db->check_table("comments")){
		$db->create_table("comments");
		$db->insert("comments", json_encode(array("DEFAULT"=>true)));
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
$location = ltrim($config['sitePath'],"/");
?>
<!DOCTYPE html>
<html lang="<?php echo $config['defaultLang']; ?>">
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="<?php echo $config['siteDesc']; ?>">
	    <meta name="author" content="<?php echo $config['siteName']; ?>">
	    <title><?php echo $config['siteName']; ?></title>
	    <link href="<?php echo $config['sitePath']; ?>css/bootstrap.min.css" rel="stylesheet">
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
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
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/home/"><?php echo $lang['homeLink'];?></a></li>
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
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/dash/"><?php echo $lang['dashboardLink'];?></a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/public/"><?php echo $lang['publicDashLink'];?></a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>page/settings/"><?php echo $lang['settingsLink'];?></a></li>
				<li class="nav-item"><a class="nav-link" href="<?php echo $config['sitePath']; ?>logout.php"><?php echo $lang['logoutLink'];?></a></li>
			</ul>
			</div>
		</nav>
			<?php
		}
		?>
		<br>
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
				//Get current user infomation from db
				$uname = cleanstring($args['page']);
				$user = $db->select("users","username",$uname);
				$user = array_values($user);
				$user = $user[0];
				if($user == false || empty($user)){
					break;
				}
				
				$friends = array();
				$location = ltrim($config['sitePath'],"/");
				$pcount = $db->check_table("posts");
				$pfinal = array();
				//filter posts to following / username:
				$ii = 1;
				for($i=1; $i<=($pcount + 1); $i++){
					if(count($pfinal) >=  20){
						break;
					}
					$curr = $pcount - $i;
					if($curr === 0){
						break;
					}
				    $t = $db->select("posts", "row_id", ($pcount - $i));
				    if(count($t) === 1){
				    	if(gettype($t) === "boolean"){
					    	continue;
				    	}
				    	$t = array_values($t);
				    	$t = $t[0];
				    	if(isset($t['author'])){
				    		if($t['author'] === $uname){
					    		$pfinal[] = $t;
					    	}
				    	}
				    }
				}
				//:D
				?>
<div class="row">
	<div class="col-md-3">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center">
					<img src="<?php echo $location."/files/".$user['profilePic']; ?>" class="img-fluid img-thumbnail">
				</div>
				<p class="lead text-center"><?php echo $user['name']; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<form action="<?php echo $config['sitePath']."api.php"?>" method="POST">
					<?php
					$userT = $db->select("users","username",$_SESSION['username']);
					$userT = array_values($userT);
					$userT = $userT[0];
					$friends = json_decode($userT['friends']);
					if(in_array($user['username'], $friends)){
						?>
						<input type="hidden" name="type" value="remove">
						<input type="hidden" name="friend" value="<?php echo $user['username']; ?>">
						<button type="submit" class="btn btn-sm btn-warning"><?php echo $lang['removeFriend'];?></button>
						<?php
					} else {
						?>
						<input type="hidden" name="type" value="add">
						<input type="hidden" name="friend" value="<?php echo $user['username']; ?>">
						<button type="submit" class="btn btn-sm btn-primary"><?php echo $lang['addFriend'];?></button>
						<?php
					}
					?>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-12">
							<?php
		if(isset($_SESSION['error'])){
			echo '
<div class="alert alert-danger" role="alert">
<strong>Oh snap!</strong> '.$_SESSION['error'].'
</div>
			';
			unset($_SESSION['error']);
		}
		?>
		<?php
		if(isset($_SESSION['message'])){
			echo '
<div class="alert alert-info" role="alert">
<strong>Notice: </strong> '.$_SESSION['message'].'
</div>
			';
			unset($_SESSION['message']);
		}
		?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="border-bottom"><h3><?php echo $lang['userTimeline'];?></h3></div>
				<?php
				if(!empty($pfinal)){
					foreach($pfinal as $post){
						$byuser = '<a href="'.$config['sitePath'].'user/'.$post['author'].'">'.$post['author'].'</a>';
						$bydate = '<small>'.$post['date'].'</small>';
						?>
					<div class="row">
						<div class="col">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo str_replace(array("%u","%d"),array($byuser,$bydate),$lang['postBy']); ?></h4>
									<p class="card-text"><?php echo $post['post'];?></p>
									<a href="#" class="card-link btn btn-sm btn-info"><?php echo $lang['likeBtn'];?> (<?php echo $post['likes']; ?>)</a>
									<a href="<?php echo $config['sitePath'].'post/'.$post['post_id']; ?>" class="card-link btn btn-sm btn-info"><?php echo $lang['viewFullBtn'];?></a>
								</div>
							</div>
						</div>
					</div>
					<br>
						<?php
					}
				} else {
					echo '<p class="lead">You should follow someone!</p>';
				}
				?>
			</div>
		</div>
		
	</div>
</div>
<?php
					break;
				case "post":
					//Display post!
					
					$post = $db->select("posts","post_id", cleanstring($args['page']));
					$post = array_values($post);
					$post = $post[0];
					if(!empty($post)){
						//Found post, let's continue
						$byuser = '<a href="'.$config['sitePath'].'user/'.$post['author'].'">'.$post['author'].'</a>';
						$bydate = '<small>'.$post['date'].'</small>';
						?>
			<div class="container">
											<?php
		if(isset($_SESSION['error'])){
			echo '
<div class="alert alert-danger" role="alert">
<strong>Oh snap!</strong> '.$_SESSION['error'].'
</div>
			';
			unset($_SESSION['error']);
		}
		?>
		<?php
		if(isset($_SESSION['message'])){
			echo '
<div class="alert alert-info" role="alert">
<strong>Notice: </strong> '.$_SESSION['message'].'
</div>
			';
			unset($_SESSION['message']);
		}
		?>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title"><?php echo str_replace(array("%u","%d"),array($byuser,$bydate),$lang['postBy']); ?></h4>
								<p class="card-text"><?php echo $post['post'];?></p>
								<a href="#" class="card-link btn btn-sm btn-info"><?php echo $lang['likeBtn'];?> (<?php echo $post['likes']; ?>)</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<br>
						<div class="row border-bottom">
							<div class="col-md-12">
								<form class="form" action="<?php echo $config['sitePath']."api.php";?>" method="POST">
									<input type="hidden" name="type" value="comment">
									<input type="hidden" name="post_id" value="<?php echo $post['post_id'];?>">
									<textarea id="data" name="data" rows="2" required class="form-control"></textarea>
									<button class="btn btn-primary float-right"><?php echo $lang['commentBtn'];?></button>
									<br>
								</form>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<?php
								$comments = $db->select("comments","post_id",$post['post_id'], false);
								if(count($comments) > 0){
									foreach($comments as $comment){
										$byuser = '<a href="'.$config['sitePath'].'user/'.$comment['author'].'">'.$comment['author'].'</a>';
										$bydate = '<small>'.$comment['date'].'</small>';
										?>
								<div class="row">
									<div class="col-md-12">
										<div class="card">
											<div class="card-body">
												<h4 class="card-title"><?php echo str_replace(array("%u","%d"),array($byuser,$bydate),$lang['commentBy']); ?></h4>
												<p class="card-text"><?php echo $comment['post'];?></p>
											</div>
										</div>
									</div>
								</div>
								<br>
										<?php
									}
								} else {
									echo '<p class="lead text-center">'.$lang['noComments'].'</p>';
								}
								?>
							</div>
						</div>
						<br>
						<div class="row border-top">
							<div class="col-md-12">
								<form class="form" action="<?php echo $config['sitePath']."api.php";?>" method="POST">
									<input type="hidden" name="type" value="comment">
									<input type="hidden" name="post_id" value="<?php echo $post['post_id'];?>">
									<textarea id="data" name="data" rows="2" required class="form-control"></textarea>
									<button class="btn btn-primary float-right"><?php echo $lang['commentBtn'];?></button>
									<br>
								</form>
							</div>
						</div>
					</div>
					
				</div>
			</div>
						<?php
					}
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
		<span class="text-muted">Coded with &hearts; | Copyrigth &copy; 2018 <a href="https://urgero.org">Mitchell Urgero</a></span>
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