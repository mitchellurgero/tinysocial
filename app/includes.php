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
//DB Backend
require_once(__DIR__."/db/db.php");


?>