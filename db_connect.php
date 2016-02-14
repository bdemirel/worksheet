<?php

$dsn = 'mysql:host=localhost;dbname=worksheet;charset=utf8';
$db_user = 'root';
$db_pass = 'root';
$dbo = new PDO($dsn, $db_user, $db_pass);
$dbo -> exec("set names utf8");

?>
