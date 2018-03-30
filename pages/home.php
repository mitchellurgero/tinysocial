<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<br>
<div class="row">
	<div class="col-8">
		<div class="border-bottom">
			<h2>Welcome to <?php echo $config['siteName'];?></h2>
		</div>
	</div>
	<div class="col-4">
		<div class="row">
			<div class="col">
				<form class="form-signin">
					<h2 class="form-signin-heading">Please sign in</h2>
					<label for="inputEmail" class="sr-only">Email address</label>
					<input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
					<br>
					<label for="inputPassword" class="sr-only">Password</label>
					<input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
					<div class="checkbox">
						<label>
						<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
					<br>
					<a href="<?php echo $config['sitePath'],"page/register/"; ?>">Need an account?</a>
				</form>
			</div>
		</div>
	</div>
</div>