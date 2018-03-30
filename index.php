<?php
//Entry point for all php.
define("TINY", TRUE);
$ver = new QuickGit();
define("VERSHORT", $ver->version()['short']);
define("VERLONG", $ver->version()['full']);
require_once("app/includes.php");
date_default_timezone_set($config['timezone']);
if($config['debug']){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$page = "home";
$path = ltrim($_SERVER['REQUEST_URI'], '/');
$elements = explode('/', $path);
$args = array();
if(empty($elements[0])) {                       
    $page = "home";
} else {
	$args = array_shift($elements);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="<?php echo $config['siteDesc']; ?>">
	    <meta name="author" content="<?php echo $config['siteName']; ?>">
	    <title><?php echo $config['siteName']; ?></title>
	</head>
	<body>
		
		<?php
		if($page){
			switch($page){
				
			}
		}
		?>

	</body>
</html>

<?php
class QuickGit {
  public static function version() {
    exec('git describe --always',$version_mini_hash);
    exec('git rev-list HEAD | wc -l',$version_number);
    exec('git log -1',$line);
    $version['short'] = "v".trim($version_number[0]).".".$version_mini_hash[0];
    $version['full'] = "v".trim($version_number[0]).".$version_mini_hash[0] (".str_replace('commit ','',$line[0]).")";
    return $version;
  }
}

?>