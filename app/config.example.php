<?php

$config = array(
	"siteName"			=> "TinySocial!",									// Default site name
	"siteDesc"			=> "Flat File social network!",						// Default site description
	"sitePath"			=> "http://192.168.1.12/codiad/workspace/tinysocial/", // where is the site located (host and path!) - use a trailing slash!
	"databaseLocation"	=> "/var/www/html/codiad/workspace/databases",		// database location (Folder to hold the database)
	"databaseName"		=> "tinysocial",									// database name
	"timezone"			=> "America/Chicago",								// set site default timezone (Important for timestamping!)
	"registration"		=> true,											// Allow registration
	"defaultLang"		=> "en",											// Set default language
	"homeBody"			=> '
	<div class="row text-center">
	<div class="col-md-4">
		<p><i class="fa fa-comments fa-5x"></i></p>
		<p class="lead">Community Driven</p>
		<p>Join the community today to become part of a community of like-minded people!</p>
	</div>
	<div class="col-md-4">
		<p><i class="fa fa-file-code fa-5x"></i></p>
		<p class="lead">Open Source</p>
		<p>Fork us on <a href="https://github.com/mitchellurgero/tinysocial">GitHub</a> and run your own TinySocial!</p>
	</div>
	<div class="col-md-4">
		<p><i class="fa fa-hdd fa-5x"></i></p>
		<p class="lead">No Database Required</p>
		<p>Using the <a href="https://github.com/mitchellurgero/jsondatabase">JSONDatabase class</a>, this PHP script doesn\'t need any database server.</p>
	</div>
</div>
	',
	"debug"				=> true,											// Enable debug (All error and Warning output)
	);
	
	//Ability to add plugins to the system
	//addPlugin("example"); //Plugin support not working ATM.
	//addPlugin("Shoutouts");