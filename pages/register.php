<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION, &$_POST));?>
<?php
$dir = __DIR__;
$images = array();
foreach (glob($dir."/../app/captcha/backgrounds/*.png") as $filename) {
    $images[] = $filename;
}
$fonts = array();
foreach (glob($dir."/../app/captcha/fonts/*.ttf") as $filename) {
    $fonts[] = $filename;
}
$_SESSION['captcha'] = simple_php_captcha( array(
    'min_length' => 5,
    'max_length' => 8,
    'backgrounds' => $images,
    'fonts' => $fonts,
    'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
    'min_font_size' => 14,
    'max_font_size' => 28,
    'color' => '#666',
    'angle_min' => 0,
    'angle_max' => 10,
    'shadow' => true,
    'shadow_color' => '#fff',
    'shadow_offset_x' => -1,
    'shadow_offset_y' => 1
));
?>
<br>
<div class="row">
	<div class="col-md-1">

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
				            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $lang['usernameTxt'];?>" required="" value="<?php if(isset($_SESSION['tempStore'])){echo $_SESSION['tempStore']['username'];}?>">
				        </div>
				        <div class="form-group">
				            <label for="name"><?php echo $lang['nameTxt'];?></label>
				            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $lang['nameTxt'];?>" required="" value="<?php if(isset($_SESSION['tempStore'])){echo $_SESSION['tempStore']['name'];}?>">
				        </div>
				        <div class="form-group">
				            <label for="email"><?php echo $lang['emailTxt'];?></label>
				            <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $lang['emailTxt'];?>" required="" value="<?php if(isset($_SESSION['tempStore'])){echo $_SESSION['tempStore']['email'];}?>">
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
				        	<label for="captcha"><?php echo $lang['captchaTxt'];?></label>
				        	<div class="row">
				        		<div class="col-md-6 text-center">
				        			<img src="<?php echo $_SESSION['captcha']['image_src'];?>" class="img-responsive">
				        		</div>
				        		<div class="col-md-6">
				        			<input type="text" name="captcha" id="captcha" class="form-control" placeholder="<?php echo $lang['captchaTxt'];?>">
				        		</div>
				        	</div>
				        </div>
				        <div class="form-group">
				            <button type="submit" class="btn btn-success btn-lg float-right"><?php echo $lang['registerBtn'];?></button>
				        </div>
				    </form>
				</div>
			</div>
	</div>
	<div class="col-md-7" style="margin-top:45px;">
		
		<div class="alert alert-dark" role="alert">
			<h4><?php echo $lang['regRequireTitle']; ?></h4>
			<ul>
				<li><?php echo $lang['regUsername']; ?></li>
				<li><?php echo $lang['regName']; ?></li>
				<li><?php echo $lang['regPassword']; ?></li>
				<li><?php echo $lang['regEmail']; ?></li>
			</ul>
		</div>
	</div>
</div>
<?php if(isset($_SESSION['tempStore'])){ unset($_SESSION['tempStore']);}?>