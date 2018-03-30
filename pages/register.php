<?php if(!defined("TINY")){die();} ?>
<?php Event::handle('PageLoad',array(&$_SESSION));?>
<br>
<div class="row">
	<div class="col-4">

	</div>
	<div class="col-4">
		<h3>Register for <?php echo $config['siteName']; ?></h3>
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
				    <h3 class="mb-0">Sign Up</h3>
				</div>
				<div class="card-body">
				    <form class="form" role="form" autocomplete="off" action="<?php echo $config['sitePath']."api.php";?>" method="POST">
				    	<input type="hidden" name="type" value="register">
				    	<div class="form-group">
				            <label for="username">Username</label>
				            <input type="text" class="form-control" id="username" name="username" placeholder="username" required="">
				        </div>
				        <div class="form-group">
				            <label for="name">Name</label>
				            <input type="text" class="form-control" id="name" name="name" placeholder="Full name" required="">
				        </div>
				        <div class="form-group">
				            <label for="email">Email</label>
				            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="">
				        </div>
				        <div class="form-group">
				            <label for="password1">Password</label>
				            <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required="">
				        </div>
				        <div class="form-group">
				            <label for="password2">Verify Password</label>
				            <input type="password" class="form-control" id="password2" name="password2" placeholder="Password (again)" required="">
				        </div>
				        <div class="form-group">
				            <button type="submit" class="btn btn-success btn-lg float-right">Register</button>
				        </div>
				    </form>
				</div>
			</div>
	</div>
	<div class="col-4">

	</div>
	
</div>