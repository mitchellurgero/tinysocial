<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<?php if(!isset($_SESSION['username'])) die("You must be signed in to see this page."); ?>
<?php
//Get current user infomation from db
$user = $db->select("users","username",$_SESSION['username']);
$user = array_values($user);
$user = $user[0];
$friends = json_decode($user['friends']);
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
    		if($t['author'] === $_SESSION['username'] || (in_array($t['author'], $friends))){
	    		$pfinal[] = $t;
	    	} else {
	    		
	    	}
    	}
    }
}
//:D
?>
<div class="row">
	<div class="col-3">
		<div class="row">
			<div class="col-12">
				<div class="text-center">
					<img src="<?php echo $location."/files/".$user['profilePic']; ?>" class="img-fluid img-thumbnail">
				</div>
				<p class="lead text-center"><?php echo $user['name']; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p class="lead">New Post:</p>
				<form class="form" action="<?php echo $config['sitePath']."api.php"?>" method="POST">
					<input type="hidden" name="type" value="post">
					<textarea id="data" name="data" rows="2" required class="form-control"></textarea>
					<button class="btn btn-primary float-right">Post!</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-9">
		<div class="row">
			<div class="col">
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
			<div class="col-12">
				<div class="border-bottom"><h3>Timeline</h3></div>
				<?php
				if(!empty($pfinal)){
					foreach($pfinal as $post){
						?>
					<div class="row">
						<div class="col">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">Post by <?php echo '<a href="'.$config['sitePath'].'user/'.$post['author'].'">'.$post['author'].'</a> <small>at '.$post['date'].''; ?></small></h4>
									<p class="card-text"><?php echo $post['post'];?></p>
									<a href="#" class="card-link">Like</a>
									<a href="#" class="card-link">Comment</a>
									<a href="<?php echo $config['sitePath'].'post/'.$post['post_id']; ?>" class="card-link">View Full</a>
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