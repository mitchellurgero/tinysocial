<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<?php $location = $config['sitePath'];if(isset($_SESSION['username'])) {header("Location: $location/page/dash");die(); }?>
<br>
<div class="row">
	<div class="col-8">
		<div class="border-bottom">
			<h2>Welcome to <?php echo $config['siteName'];?></h2>
			<p><?php echo $config['siteDesc']; ?></p>
		</div>
		<br>
		<div class="row text-center">
			<div class="col">
				<p><i class="fa fa-comments fa-5x"></i></p>
				<p class="lead">Community Driven</p>
				<p></p>
			</div>
			<div class="col">
				<p><i class="fa fa-file-code fa-5x"></i></p>
				<p class="lead">Open Source</p>
				<p></p>
			</div>
			<div class="col">
				<p><i class="fa fa-hdd fa-5x"></i></p>
				<p class="lead">Small Footprint</p>
				<p></p>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-1">
				
			</div>
			<div class="col">
				<p class="lead">Currently have <?php echo ($db->check_table("users") - 1)." users with ".($db->check_table("posts") - 1)." posts."; ?></p>
			</div>
		</div>
	</div>
	<div class="col-4">
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
				<form class="form-signin" action="<?php echo $config['sitePath']."api.php"?>" method="POST">
					<h2 class="form-signin-heading">Please sign in</h2>
					<input type="hidden" name="type" value="login">
					<label for="username" class="sr-only">Username</label>
					<input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
					<br>
					<label for="password" class="sr-only">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
					<br>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
					<br>
					<a href="<?php echo $config['sitePath'],"page/register/"; ?>">Need an account?</a>
				</form>
			</div>
		</div>
	</div>
</div>