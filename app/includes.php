<?php

//Plugin stuff
require_once(__DIR__."/classhelper.php"); //To assist in pulling in plugins
require_once(__DIR__."/Events.php"); //Event system
require_once(__DIR__."/Plugin.php"); //Plugin system-ish
function addPlugin($name, array $attrs=array()){
	return ClassHelper::addPlugin($name, $attrs);
}
//Config
if(file_exists(__DIR__."/config.php")){
	require_once(__DIR__."/config.php");
} else {
	require_once(__DIR__."/config.example.php");
}
//Read lang:
$lfile = __DIR__."/lang/".$config['defaultLang'].".json";
$ljson = file_get_contents($lfile);
$ljson = removeBOM($ljson);
$ljson = utf8ize($ljson);
$lang = json_decode($ljson,true);

//DB Backend
require_once(__DIR__."/db/db.php");

//Captcha backend
require_once(__DIR__."/captcha/simple-php-captcha.php");

function cleanstring($string){
	//$string = escapeshellcmd($string);
	//$string = escapeshellarg($string);
	$string = strip_tags($string);
	$string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
	//$string = htmlspecialchars($string); //<- Might not really be needed... Will experiment.
	return $string;
}
//Check if string starts with needle.
function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
//check if string ends with needle
function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 || 
    (substr($haystack, -$length) === $needle);
}
function removeBOM($data) {
    if (0 === strpos(bin2hex($data), 'efbbbf')) {
       return substr($data, 3);
    }
    return $data;
}
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
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