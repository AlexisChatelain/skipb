<?php
	if (!isset($_GET['id_partie'])){
		echo "connexion"; //header("Location: connexion.php");
		die;
	}else{
		require_once("ConnexionClass.php"); 
		$login = new Login();	
		if(!$login->isUserLoggedIn()){
			echo "connexion"; // header('Location: connexion.php');		
			die();
		}else{		
			require_once("db_file.php");
			$parametre=0;
			for($i=1;$i<=6;$i++){		
				if ($db->query("SELECT joueur$i FROM parties WHERE id_partie=".$_GET['id_partie'])->fetch_array()[0]==$_SESSION['id']){
					$db->query("UPDATE parties SET derniere_connexion$i=now() WHERE id_partie=".$_GET['id_partie']);
					$parametre=1;
				}
			}
			if ($parametre==0){
				echo "connexion"; // header('Location: connexion.php');	
				die();		
			}
			function degage($db, $pile){
			$emplacement = $db->query("SELECT * FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='$pile'")->fetch_array();
			$supprimer="";
			for ($i=2;$i<=13;$i++){
				$db->query("UPDATE cartes SET etat=-2 WHERE id_carte=".$emplacement[$i]);	
				$j=$i-1;
				$supprimer.=" carte$j = null ,";
			}
			$supprimer=substr($supprimer,0,-1);
			$db->query("UPDATE emplacements SET $supprimer WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='$pile'");
			}
			for ($k=1;$k<=4;$k++){
				if ($db->query("SELECT carte12 FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série centrale $k'")->fetch_array()[0] !=null)
					degage($db, "Série centrale ".$k);
			}
		}
	}
?>
		