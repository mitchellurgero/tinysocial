<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<?php if(!isset($_SESSION['username'])) { die("<br>".$lang['notLoggedIn']); }?>
<?php
//Get current user infomation from db
$user = $db->select("users","username",$_SESSION['username']);
$user = array_values($user);
$user = $user[0];
$friends = array();
$location = rtrim($config['sitePath'],"/");
$ppic = "";
if(startsWith($user['profilePic'], "http://") || startsWith($user['profilePic'], "https://")){
	$ppic = $user['profilePic'];
} else {
	$ppic = $location."/files/".$user['profilePic'];
}
?>
<br>
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
	<div class="col-md-4">
		<h3><?php echo $lang['changeFullName'];?></h3>
		<form action="<?php echo $config['sitePath']."api.php"?>" method="POST">
			<input type="hidden" name="type" value="changeName">
			<input type="text" name="name" placeholder="<?php echo $lang['changeFullName'];?>" value="<?php echo $user['name']; ?>">
			<button class="btn btn-sm btn-info"><?php echo $lang['saveBtn'];?></button>
		</form>
		<br>
		<h3><?php echo $lang['changePassword'];?></h3>
		<form action="<?php echo $config['sitePath']."api.php"?>" method="POST">
			<input type="hidden" name="type" value="changePassword">
			<input type="password" class="form-control" id="password1" name="password1" placeholder="<?php echo $lang['passwordTxt'];?>" required="">
			<input type="password" class="form-control" id="password2" name="password2" placeholder="<?php echo $lang['passwordTxt2'];?>" required="">
			<button class="btn btn-sm btn-info"><?php echo $lang['saveBtn'];?></button>
		</form>
	</div>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-3 text-center">
				<h3><?php echo $lang['currentProfilePic'];?></h3>
				<img src="<?php echo $ppic;?>" class="img-fluid img-thumbnail" style="max-height:150px !important;">
			</div>
			<div class="col-md-9">
				<form action="<?php echo $config['sitePath']."api.php"?>" method="POST" style="margin-top:50px;">
					<input type="hidden" name="type" value="changeProfilePic">
					<input type="text" class="form-control" name="pic" placeholder="<?php echo $lang['changeProfilePic'];?>" value="<?php echo $ppic;?>">
					<br>
					<button class="btn btn-sm btn-info"><?php echo $lang['saveBtn'];?></button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		
	</div>
</div>