<?php
require_once("app/includes.php");
session_start();
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);
$location = $config['sitePath'];
header("Location: $location")
?>