
<?php
/* Example plugin that can turn certain pages into blog posts */
class ShoutoutsPlugin extends Plugin{
	public function __construct(){
		//this is required by the plugin system to get working properly. This adds all the below events to global like {Class}::{onEventName};
		parent::__construct(); //Required
		return true;
	}
	
	public function initialize(){
		return true;
	}
	public function onIndexCustom(&$sess, &$args){
		if($args['type'] == "shoutout"){
			echo "boop. Here is another example plugin :3";
		}
		return true;
	}
	public function onPageLoad(&$sess, &$post){
		
		return true;
	}
	public function onLoggedOutNavEnd(&$SESS){
		echo '<li class="nav-item"><a class="nav-link" href="https://github.com/mitchellurgero/tinysocial">GitHub</a></li>';
		
		return true;
	}
}
?>