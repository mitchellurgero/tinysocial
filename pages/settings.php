<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<?php if(!isset($_SESSION['username'])) die("You must be signed in to see this page."); ?>
<?php
//Get current user infomation from db
$user = $db->select("users","username",$_SESSION['username']);
$user = array_values($user);
$user = $user[0];
$friends = array();

?>
<br>
<div class="row">
	<div class="col-3">

	</div>
	<div class="col-6 text-center">
		<div class="border-bottom">
			<h3>Settings</h3>
		</div>
	</div>
	<div class="col-3">

	</div>
	
</div>