<?php
	require_once("ConnexionClass.php"); 
	$login = new Login();	
	require_once("db_file.php");
	if (isset($_GET["id_joueur"]))
		$id_joueur=$_GET["id_joueur"];
	else
		$id_joueur=$_SESSION["id"];
	if ((isset($_GET["carte1"]) || isset($_GET["carte2"]) || isset($_GET["carte3"]) || isset($_GET["carte4"]) || isset($_GET["carte5"])) && 
		 (isset($_GET["centrale1"]) || isset($_GET["centrale2"]) || isset($_GET["centrale3"]) || isset($_GET["centrale4"]))){
		if (isset($_GET["carte1"])){
			$key="carte1";
			$carte_main=$_GET["carte1"];
		}else if (isset($_GET["carte2"])){
			$key="carte2";
			$carte_main=$_GET["carte2"];
		}else if (isset($_GET["carte3"])){
			$key="carte3";
			$carte_main=$_GET["carte3"];
		}else if (isset($_GET["carte4"])){
			$key="carte4";
			$carte_main=$_GET["carte4"];
		}else if (isset($_GET["carte5"])){
			$key="carte5";
			$carte_main=$_GET["carte5"];
		}		
		if (isset($_GET["centrale1"])){
			$key2="centrale 1";
			$carte_centrale=$_GET["centrale1"];
		}else if (isset($_GET["centrale2"])){
			$key2="centrale 2";
			$carte_centrale=$_GET["centrale2"];
		}else if (isset($_GET["centrale3"])){
			$key2="centrale 3";
			$carte_centrale=$_GET["centrale3"];
		}else if (isset($_GET["centrale4"])){
			$key2="centrale 4";
			$carte_centrale=$_GET["centrale4"];
		}
		$carte_centrale+=1;
		$emplacement=$db->query("SELECT $key FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Main'" )->fetch_array()[0];
		$db->query("UPDATE emplacements SET ".$key."=null WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Main'" );	
		$db->query("UPDATE emplacements SET carte".$carte_centrale."=$emplacement WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'" );	
		/*if ($db->query("SELECT carte12 FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'")->fetch_array()[0] !=null){ 
			degage($db, "Série ".$key2);
		}*/
	}else if ((isset($_GET["carte1"]) || isset($_GET["carte2"]) || isset($_GET["carte3"]) || isset($_GET["carte4"]) || isset($_GET["carte5"])) && 
		 (isset($_GET["defausse1"]) || isset($_GET["defausse2"]) || isset($_GET["defausse3"]) || isset($_GET["defausse4"]))){
		 if (isset($_GET["carte1"])){
			$key="carte1";
			$carte_main=$_GET["carte1"];
		}else if (isset($_GET["carte2"])){
			$key="carte2";
			$carte_main=$_GET["carte2"];
		}else if (isset($_GET["carte3"])){
			$key="carte3";
			$carte_main=$_GET["carte3"];
		}else if (isset($_GET["carte4"])){
			$key="carte4";
			$carte_main=$_GET["carte4"];
		}else if (isset($_GET["carte5"])){
			$key="carte5";
			$carte_main=$_GET["carte5"];
		}		
		if (isset($_GET["defausse1"])){
			$key2="défausse 1";
			$carte_centrale=$_GET["defausse1"];
		}else if (isset($_GET["defausse2"])){
			$key2="défausse 2";
			$carte_centrale=$_GET["defausse2"];
		}else if (isset($_GET["defausse3"])){
			$key2="défausse 3";
			$carte_centrale=$_GET["defausse3"];
		}else if (isset($_GET["defausse4"])){
			$key2="défausse 4";
			$carte_centrale=$_GET["defausse4"];
		}
		$carte_centrale+=1;
		$emplacement=$db->query("SELECT * FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Pile de $key2'" )->fetch_array();
		$carte = "0";
		$compt=2;
		while ($carte != null){			
			$carte=$emplacement[$compt];			
			$compt+=1;
		}
		$carte_centrale=$compt-2;
		$emplacement=$db->query("SELECT $key FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Main'" )->fetch_array()[0];
		$db->query("UPDATE emplacements SET ".$key."=null WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Main'" );	
		$db->query("UPDATE emplacements SET carte".$carte_centrale."=$emplacement WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Pile de $key2'" );	
		$partie=$db->query("SELECT * FROM parties WHERE id_partie=".$_GET["id_partie"])->fetch_object();
		if ($partie->tour==$partie->joueur1){
			$tour=$partie->joueur2;
		}else if ($partie->tour==$partie->joueur2){
			if ($partie->joueur3==null)
				$tour=$partie->joueur1;
			else
				$tour=$partie->joueur3;
		}else if ($partie->tour==$partie->joueur3){
			if ($partie->joueur4==null)
				$tour=$partie->joueur1;
			else
				$tour=$partie->joueur4;
		}else if ($partie->tour==$partie->joueur4){
			if ($partie->joueur5==null)
				$tour=$partie->joueur1;
			else
				$tour=$partie->joueur5;
		}else if ($partie->tour==$partie->joueur5){
			if ($partie->joueur6==null)
				$tour=$partie->joueur1;
			else
				$tour=$partie->joueur6;
		}else if ($partie->tour==$partie->joueur6){
				$tour=$partie->joueur1;
		}
		$db->query("UPDATE emplacements SET carte6=null WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$tour." and nom='Main'" );
		$db->query("UPDATE parties SET tour=".$tour. " WHERE id_partie=".$_GET["id_partie"]);	
	}else if (isset($_GET["pioche"])){	
		$db->query("UPDATE emplacements SET carte6=null WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Main'");		 
	}else if ((isset($_GET["defausse1"]) || isset($_GET["defausse2"]) || isset($_GET["defausse3"]) || isset($_GET["defausse4"])) && 
		 (isset($_GET["centrale1"]) || isset($_GET["centrale2"]) || isset($_GET["centrale3"]) || isset($_GET["centrale4"]))){
		 if (isset($_GET["defausse1"])){
			$key="défausse 1";
			$carte_main=$_GET["defausse1"];
		}else if (isset($_GET["defausse2"])){
			$key="défausse 2";
			$carte_main=$_GET["defausse2"];
		}else if (isset($_GET["defausse3"])){
			$key="défausse 3";
			$carte_main=$_GET["defausse3"];
		}else if (isset($_GET["defausse4"])){
			$key="défausse 4";
			$carte_main=$_GET["defausse4"];
		}		
		if (isset($_GET["centrale1"])){
			$key2="centrale 1";
			$carte_centrale=$_GET["centrale1"];
		}else if (isset($_GET["centrale2"])){
			$key2="centrale 2";
			$carte_centrale=$_GET["centrale2"];
		}else if (isset($_GET["centrale3"])){
			$key2="centrale 3";
			$carte_centrale=$_GET["centrale3"];
		}else if (isset($_GET["centrale4"])){
			$key2="centrale 4";
			$carte_centrale=$_GET["centrale4"];
		}
		$carte_centrale+=1;
		$emplacement=$db->query("SELECT * FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Pile de $key'" )->fetch_array();
		$carte = "0";
		$compt=2;
		while ($carte != null && $compt < 15){
			$carte=$emplacement[$compt];
			$compt+=1;
		}
		$emplacement=$compt-3;
		$carte=$db->query("SELECT carte".$emplacement." FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Pile de $key'")->fetch_array()[0];	
		$db->query("UPDATE emplacements SET carte".$emplacement."=null WHERE id_partie=".$_GET["id_partie"]." and id_joueur=".$id_joueur." and nom='Pile de $key'" );
		$db->query("UPDATE emplacements SET carte".$carte_centrale."=$carte WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'" );	
		/*if ($db->query("SELECT carte12 FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'")->fetch_array()[0] !=null){ 
			degage($db, "Série ".$key2);
		}*/
	}else if ((isset($_GET["carte_stock"])) && 
		 (isset($_GET["centrale1"]) || isset($_GET["centrale2"]) || isset($_GET["centrale3"]) || isset($_GET["centrale4"]))){
			$key="stock";
			$carte_main=$_GET["carte_stock"];
		if (isset($_GET["centrale1"])){
			$key2="centrale 1";
			$carte_centrale=$_GET["centrale1"];
		}else if (isset($_GET["centrale2"])){
			$key2="centrale 2";
			$carte_centrale=$_GET["centrale2"];
		}else if (isset($_GET["centrale3"])){
			$key2="centrale 3";
			$carte_centrale=$_GET["centrale3"];
		}else if (isset($_GET["centrale4"])){
			$key2="centrale 4";
			$carte_centrale=$_GET["centrale4"];
		}
		$carte_centrale+=1;
		$emplacement = $db->query("SELECT id_carte FROM cartes WHERE id_partie=".$_GET["id_partie"]." and etat=".$id_joueur." ORDER BY alea ASC LIMIT 1")->fetch_array()[0];
		$db->query("UPDATE cartes SET etat=-1 WHERE id_carte=".$emplacement." and id_partie=".$_GET["id_partie"]);
		$db->query("UPDATE emplacements SET carte".$carte_centrale."=$emplacement WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'" );	
		/*if ($db->query("SELECT carte12 FROM emplacements WHERE id_partie=".$_GET["id_partie"]." and id_joueur=0 and nom='Série $key2'")->fetch_array()[0] !=null){ 
			degage($db, "Série ".$key2);
		}*/
		$gagne = $db->query("SELECT id_carte FROM cartes WHERE id_partie=".$_GET["id_partie"]." and etat=".$id_joueur." ORDER BY alea ASC LIMIT 1");
		if ($gagne->num_rows == 0) 
			$db->query("UPDATE parties SET gagne=".$id_joueur. " WHERE id_partie=".$_GET["id_partie"]);		
	}else if (isset($_GET["message"]) && isset($_GET["id_joueur"]) && isset($_GET["id_partie"])){	
		$message=$db->real_escape_string(htmlentities(urldecode($_GET['message']), ENT_QUOTES));
		$db->query("INSERT into discussion (message,id_partie,id_joueur) VALUES('".$message."',".$_GET['id_partie'].",".$_GET['id_joueur'].")");	
	}else if (isset($_GET["visio"]) && isset($_GET["id_partie"])){
		$db->query("UPDATE parties SET visio=".$_GET["visio"]. " WHERE id_partie=".$_GET["id_partie"]);	
	}	
	/*
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
	}*/
	if (isset($_GET["id_user"]) && isset($_GET["nuit"])){
		$db->query("UPDATE utilisateurs SET nuit=".$_GET["nuit"]." WHERE id_user=".$_GET["id_user"]);			
		echo "UPDATE utilisateurs SET nuit=".$_GET["nuit"]." WHERE id_user=".$_GET["id_user"];	
	}
	if (isset($_GET["id_partie"])){
		if ($db->query("SELECT count(id_carte) as compteur FROM cartes WHERE id_partie=".$_GET["id_partie"]." and etat=0")->fetch_array()[0] < 6 ){
			$result = $db->query("SELECT id_carte, alea FROM cartes WHERE id_partie=".$_GET["id_partie"]." and etat=-2");
			$tableau = array();
			$ids = array();
			while($row=$result->fetch_array()){		
				array_push ($ids,$row[0]);	
				array_push ($tableau,$row[1]);		
			}		
			while (count($tableau)>0){	
				$nb = array_rand($tableau, 1);
				$db->query("UPDATE cartes SET etat=0, alea=".$tableau[$nb]." WHERE id_carte=".$ids[$nb]);	
				unset($tableau[$nb]);
				unset($ids[$nb]);
			}
		}
	}