<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<?php $location = $config['sitePath'];if(isset($_SESSION['username'])) {header("Location: $location/page/dash");die(); }?>
<br>
<div class="row">
	<div class="col-md-8">
		<div class="border-bottom">
			<h2>Welcome to <?php echo $config['siteName'];?></h2>
			<p><?php echo $config['siteDesc']; ?></p>
		</div>
		<br>
		<div class="row text-center">
			<div class="col-md-4">
				<p><i class="fa <?php echo $lang['col1icon'];?> fa-5x"></i></p>
				<p class="lead"><?php echo $lang['col1'];?></p>
				<p><?php echo $lang['col1desc'];?></p>
			</div>
			<div class="col-md-4">
				<p><i class="fa <?php echo $lang['col2icon'];?> fa-5x"></i></p>
				<p class="lead"><?php echo $lang['col2'];?></p>
				<p><?php echo $lang['col2desc'];?></p>
			</div>
			<div class="col-md-4">
				<p><i class="fa <?php echo $lang['col3icon'];?> fa-5x"></i></p>
				<p class="lead"><?php echo $lang['col3'];?></p>
				<p><?php echo $lang['col3desc'];?></p>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-1">
				
			</div>
			<div class="col-md-11">
				<p class="lead"><?php echo str_replace(array("%i","%p"),array(($db->check_table("users") - 1),($db->check_table("posts") - 1)),$lang['userCount'])?></p>
			</div>
		</div>
	</div>
	<div class="col-md-4">
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
				<form class="form-signin" action="<?php echo $config['sitePath']."api.php"?>" method="POST">
					<h2 class="form-signin-heading"><?php echo $lang['pleaseSignIn'];?></h2>
					<input type="hidden" name="type" value="login">
					<label for="username" class="sr-only"><?php echo $lang['usernameTxt'];?></label>
					<input type="username" id="username" name="username" class="form-control" placeholder="<?php echo $lang['usernameTxt'];?>" required autofocus>
					<br>
					<label for="password" class="sr-only"><?php echo $lang['passwordTxt'];?></label>
					<input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $lang['passwordTxt'];?>" required>
					<br>
					<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $lang['loginBtn'];?></button>
					<br>
					<a href="<?php echo $config['sitePath'],"page/register/"; ?>"><?php echo $lang['newAccountLink'];?></a>
				</form>
			</div>
		</div>
	</div>
</div>