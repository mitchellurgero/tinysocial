<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<br>
<div class="row">
	<div class="col-md-4">

	</div>
	<div class="col-md-4">
		<h3><?php echo $lang['registerFor'];?> <?php echo $config['siteName']; ?></h3>
		<p class="lead"></p>
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
			<div class="card card-outline-secondary">
				<div class="card-header">
				    <h3 class="mb-0"><?php echo $lang['signUp'];?></h3>
				</div>
				<div class="card-body">
				    <form class="form" role="form" autocomplete="off" action="<?php echo $config['sitePath']."api.php";?>" method="POST">
				    	<input type="hidden" name="type" value="register">
				    	<div class="form-group">
				            <label for="username"><?php echo $lang['usernameTxt'];?></label>
				            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $lang['usernameTxt'];?>" required="">
				        </div>
				        <div class="form-group">
				            <label for="name"><?php echo $lang['nameTxt'];?></label>
				            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $lang['nameTxt'];?>" required="">
				        </div>
				        <div class="form-group">
				            <label for="email"><?php echo $lang['emailTxt'];?></label>
				            <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $lang['emailTxt'];?>" required="">
				        </div>
				        <div class="form-group">
				            <label for="password1"><?php echo $lang['passwordTxt'];?></label>
				            <input type="password" class="form-control" id="password1" name="password1" placeholder="<?php echo $lang['passwordTxt'];?>" required="">
				        </div>
				        <div class="form-group">
				            <label for="password2"><?php echo $lang['passwordTxt2'];?></label>
				            <input type="password" class="form-control" id="password2" name="password2" placeholder="<?php echo $lang['passwordTxt2'];?>" required="">
				        </div>
				        <div class="form-group">
				            <button type="submit" class="btn btn-success btn-lg float-right"><?php echo $lang['registerBtn'];?></button>
				        </div>
				    </form>
				</div>
			</div>
	</div>
	<div class="col-md-4">

	</div>
	
</div>