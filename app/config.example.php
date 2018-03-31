<?php

$config = array(
	"siteName"			=> "TinySocial!",									// Default site name
	"siteDesc"			=> "Flat File social network!",						// Default site description
	"sitePath"			=> "http://192.168.1.12/codiad/workspace/tinysocial/", // where is the site located (host and path!) - use a trailing slash!
	"databaseLocation"	=> "/var/www/html/codiad/workspace/databases",		// database location (Folder to hold the database)
	"databaseName"		=> "tinysocial",									// database name
	"timezone"			=> "America/Chicago",								// set site default timezone (Important for timestamping!)
	"registration"		=> true,											// Allow registration
	
	
	"debug"				=> true,											// Enable debug (All error and Warning output)
	);
	
	//addPlugin("example"); //Plugin support not working ATM.