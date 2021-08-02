<?php

$DB_HOST='localhost';
$DB_USER='nom_utilisateur';
$DB_PASS='mot_de_passe';
$DB_NAME='nom_projet';

define('DB_HOST',$DB_HOST);
define('DB_USER',$DB_USER);
define('DB_PASS',$DB_PASS);
define('DB_NAME',$DB_NAME);  

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$db->set_charset("utf8");

if($db->connect_errno){
	http_response_code(500);
	die();
	}
?>