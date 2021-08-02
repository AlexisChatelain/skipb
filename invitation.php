<?php
	require_once("ConnexionClass.php"); 
	$login = new Login();	
	require_once("db_file.php");

	if (isset($_POST["id_partie_invite"]) && isset($_POST["id_joueur_invite"]) && isset($_POST["id_joueur"]) && isset($_POST["id_partie"])){
		$invitation=$_POST["id_joueur"].";".$_POST["id_joueur_invite"]."!".$_POST["id_partie"];																	 
		$db->query("UPDATE parties SET invitation='".$invitation."' WHERE id_partie=".$_POST["id_partie_invite"]);

	}elseif (isset($_POST["id_partie"]) && isset($_POST["ok"])){
		$db->query("UPDATE parties SET invitation=null WHERE id_partie=".$_POST["id_partie"]);
	}