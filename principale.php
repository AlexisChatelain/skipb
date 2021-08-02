<?php 
if (!isset($_GET['id_partie'])){
	echo "connexion"; //header("Location: connexion.php");
	die;
}else{
	require_once("ConnexionClass.php"); 
	$login = new Login();		
	$pas_la=0;
	function emplacement($db, $query){	
		$resultat = $db->query($query);
		$place = $resultat->fetch_array();
		$carte='truc';
		$compteur=2;
		while ($carte != null and $compteur <=14){
			$carte=$place[$compteur];
			$compteur+=1;
		}
		if ($compteur == 3){
			return array(0,0);
			
		}else{
			$compteur-=2;
			$carte=$place[$compteur];
			$carte=$db->query("SELECT valeur FROM cartes WHERE id_carte=$carte")->fetch_object()->valeur;
			$compteur-=1;
			return array($carte,$compteur);
		}
	}
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
		$nuit=$db->query("SELECT nuit FROM utilisateurs WHERE id_user=".$_SESSION['id'])->fetch_array()[0];
		if ($nuit==1)
			echo "<fieldset id='mode_nuit' style='position:absolute; top:0; left:0;'><legend>Mode nuit</legend><input type='button' onclick='nuit(".$_SESSION['id'].",2)' type='button' style='background-color:green; padding:20px; border-radius:360px;' value='Toujours'></input></fieldset>";
		else if ($nuit==null)
			echo "<fieldset id='mode_nuit' style='position:absolute; top:0; left:0;'><legend>Mode nuit</legend><input type='button' onclick='nuit(".$_SESSION['id'].",1)' style='background-color:red; padding:20px; border-radius:360px;' value='Jamais'></input></fieldset>";
		else if ($nuit==2)
			echo "<fieldset id='mode_nuit' style='position:absolute; top:0; left:0;'><legend>Mode nuit</legend><input type='button' onclick='nuit(".$_SESSION['id'].",null)' style='background-color:orange; padding:20px; border-radius:360px;' value='Entre 22h et 8h'></input></fieldset>";		
		if ($nuit==2){
			if (date("H")>=22 || date("H")<8)
				$nuit=1;
			else 
				$nuit=0;
		}
		if ($nuit==1){
			$fieldset_blanc="color:white;";
			echo "<body style='background-color:black; color:white;'>";
		}else{
			$fieldset_blanc="color:;";
			echo "<body>";
		}
		$query = "SELECT * FROM parties WHERE id_partie={$_GET['id_partie']}";
		$resultat = $db->query($query);
		$donnees_partie = $resultat->fetch_object();
		$tour=$donnees_partie->tour;
		$gagne=$donnees_partie->gagne;
		if ($nuit==1){
			$couleur_fond="black";
		}else{
			$couleur_fond="white";
		}
		$invitation="<div id='invitation' style='position:absolute; top:0; right:0; background-color:$couleur_fond;'> </div>";
		$invite="<div id='invite' style='position:absolute; top:0; right:0; background-color:$couleur_fond;'> </div>";
		if ($gagne==$_SESSION["id"]){
			$message_tour="Partie terminée, vous avez gagné ! <a href='connexion.php'>Retour à l'accueil</a>";
		}else if ($gagne!=$_SESSION["id"] && $gagne!=0){
			$query = "SELECT pseudo FROM utilisateurs WHERE id_user=$gagne";
			$resultat = $db->query($query);
			$utilisateur = $resultat->fetch_object();
			$nom_joueur=$utilisateur->pseudo;		
			$message_tour="Partie terminée, $nom_joueur a gagné ! <a href='connexion.php'>Retour à l'accueil</a>";			
		}else if ($tour==$_SESSION["id"]){	
			$message_tour="A vous de jouer !";				
			if ($db->query("SELECT carte6 FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$_SESSION["id"]." and nom='Main'")->fetch_array()[0]==null)
				echo "<audio id='audio' autoplay='' id='media' src='notif.mp3' type='audio/mp3'></audio>";
		}else {
			$query = "SELECT pseudo FROM utilisateurs WHERE id_user=$tour";
			$resultat = $db->query($query);
			$utilisateur = $resultat->fetch_object();
			$nom_joueur=$utilisateur->pseudo;
			$message_tour="$nom_joueur joue ...";
		}	
	}
if ($donnees_partie->joueur3 == null)
		$nb=2;
	else if ($donnees_partie->joueur4 == null)
		$nb=3;
	else if ($donnees_partie->joueur5 == null)
		$nb=4;
	else if ($donnees_partie->joueur6 == null)
		$nb=5;
	else 
		$nb=6;
	if ($donnees_partie->joueur1==$_SESSION["id"]){
		if ($nb==6){
			$joueurs=array($donnees_partie->joueur1, $donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur5,$donnees_partie->joueur6);
			$dispos= array($donnees_partie->derniere_connexion1, $donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion6);
		}else if ($nb==5){
			$joueurs=array($donnees_partie->joueur1, $donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur5);
			$dispos= array($donnees_partie->derniere_connexion1, $donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5);
		}else if ($nb==4){
			$joueurs=array($donnees_partie->joueur1, $donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4);
			$dispos= array($donnees_partie->derniere_connexion1, $donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4);
		}else if ($nb==3){
			$joueurs=array($donnees_partie->joueur1, $donnees_partie->joueur2,$donnees_partie->joueur3);
			$dispos= array($donnees_partie->derniere_connexion1, $donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3);
		}else{
			$joueurs=array($donnees_partie->joueur1, $donnees_partie->joueur2);
			$dispos= array($donnees_partie->derniere_connexion1, $donnees_partie->derniere_connexion2);
		}
	}elseif ($donnees_partie->joueur2==$_SESSION["id"]){
		if ($nb==6){
			$joueurs=array($donnees_partie->joueur2, $donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur5,$donnees_partie->joueur6,$donnees_partie->joueur1);
			$dispos= array($donnees_partie->derniere_connexion2, $donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion6,$donnees_partie->derniere_connexion1);
		}else if ($nb==5){
			$joueurs=array($donnees_partie->joueur2, $donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur5,$donnees_partie->joueur1);
			$dispos= array($donnees_partie->derniere_connexion2, $donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion1);
		}else if ($nb==4){
			$joueurs=array($donnees_partie->joueur2, $donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur1);
			$dispos= array($donnees_partie->derniere_connexion2, $donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion1);
		}else if ($nb==3){
			$joueurs=array($donnees_partie->joueur2, $donnees_partie->joueur3,$donnees_partie->joueur1);
			$dispos= array($donnees_partie->derniere_connexion2, $donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion1);
		}else{
			$joueurs=array($donnees_partie->joueur2, $donnees_partie->joueur1);
			$dispos= array($donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion1);
		}
	}elseif ($donnees_partie->joueur3==$_SESSION["id"]){
		if ($nb==6){
			$joueurs=array($donnees_partie->joueur3, $donnees_partie->joueur4,$donnees_partie->joueur5,$donnees_partie->joueur6,$donnees_partie->joueur1,$donnees_partie->joueur2);
			$dispos= array($donnees_partie->derniere_connexion3, $donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion6,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2);
		}else if ($nb==5){
			$joueurs=array($donnees_partie->joueur3, $donnees_partie->joueur4,$donnees_partie->joueur5,$donnees_partie->joueur1,$donnees_partie->joueur2);
			$dispos= array($donnees_partie->derniere_connexion3, $donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2);
		}else if ($nb==4){
			$joueurs=array($donnees_partie->joueur3, $donnees_partie->joueur4,$donnees_partie->joueur1,$donnees_partie->joueur2);
			$dispos= array($donnees_partie->derniere_connexion3, $donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2);
		}else {
			$joueurs=array($donnees_partie->joueur3, $donnees_partie->joueur1,$donnees_partie->joueur2);
			$dispos= array($donnees_partie->derniere_connexion3, $donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2);
		}		
	}elseif ($donnees_partie->joueur4==$_SESSION["id"]){
		if ($nb==6){
			$joueurs=array($donnees_partie->joueur4, $donnees_partie->joueur5,$donnees_partie->joueur6,$donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3);
			$dispos= array($donnees_partie->derniere_connexion4, $donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion6,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3);
		}else if ($nb==5){
			$joueurs=array($donnees_partie->joueur4, $donnees_partie->joueur5,$donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3);
			$dispos= array($donnees_partie->derniere_connexion4, $donnees_partie->derniere_connexion5,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3);
		}else{
			$joueurs=array($donnees_partie->joueur4, $donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3);
			$dispos= array($donnees_partie->derniere_connexion4, $donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3);
		}
	}elseif ($donnees_partie->joueur5==$_SESSION["id"]){
		if ($nb==6){
			$joueurs=array($donnees_partie->joueur5, $donnees_partie->joueur6,$donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4);
			$dispos= array($donnees_partie->derniere_connexion5, $donnees_partie->derniere_connexion6,$donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4);
		}else {
			$joueurs=array($donnees_partie->joueur5, $donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4);
			$dispos= array($donnees_partie->derniere_connexion5, $donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4);
		}
	}else{
		$joueurs=array($donnees_partie->joueur6, $donnees_partie->joueur1,$donnees_partie->joueur2,$donnees_partie->joueur3,$donnees_partie->joueur4,$donnees_partie->joueur5);
		$dispos= array($donnees_partie->derniere_connexion6, $donnees_partie->derniere_connexion1,$donnees_partie->derniere_connexion2,$donnees_partie->derniere_connexion3,$donnees_partie->derniere_connexion4,$donnees_partie->derniere_connexion5);
	}
	if (isset($_GET["IA"]) && ($tour==7 || $tour==8 || $tour==9 || $tour==10 || $tour==11) && ($_SESSION['id']!=7 && $_SESSION['id']!=8 && $_SESSION['id']!=9 && $_SESSION['id']!=10 && $_SESSION['id']!=11)){
		$main=array();
		$defausse=array();
		$centrale=array();
		$defausse_adv=array();
		$stock_adv=array();
		$idimg=array();
		$old=array();
		$row=$db->query("SELECT carte1, carte2, carte3, carte4, carte5, carte6 FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour." and nom='Main'")->fetch_assoc();
		for ($i=0; $i<5; $i++){
			$j=$i+1;
			$j="carte".$j;
			if ($row[$j]!=null){
				array_push($main,intval($db->query("SELECT valeur FROM cartes WHERE id_carte=".$row[$j])->fetch_object()->valeur));			
			}else if ($row["carte6"]==null){
				$lacarte=$db->query("SELECT id_carte, valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=0 ORDER BY alea ASC LIMIT 1")->fetch_object();
				if (!($lacarte==false)){
					$msg_pioche="";
					$carte=$lacarte->valeur;
					$db->query("UPDATE cartes SET etat=-1 WHERE id_carte=".$lacarte->id_carte);
					$db->query("UPDATE emplacements SET ".$j."=".$lacarte->id_carte ." WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour." and nom='Main'" );
					array_push($main,intval($db->query("SELECT valeur FROM cartes WHERE id_carte=".$lacarte->id_carte)->fetch_object()->valeur));	
				}
			}else{
				array_push($main,null);	
			}
		}
		if ($row["carte6"]==null)
			$db->query("UPDATE emplacements SET carte6=1 WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour." and nom='Main'" );
		
		$result = $db->query("SELECT carte1,nom FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour);			
		for ($i=1; $i<=4; $i++){
			array_push($centrale,intval(emplacement($db,"SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=0 and nom='Série centrale $i'")[1]));
			array_push($defausse,intval(emplacement($db,"SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour." and nom='Pile de défausse $i'")[0])); 		
		}
		$stock = $db->query("SELECT valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=".$tour." ORDER BY alea ASC LIMIT 1")->fetch_object()->valeur;
		for ($j=0; $j<$nb; $j++){
			if ($joueurs[$j]==$tour){
				if (isset($joueurs[$j+1]))
					$k=$j+1;
				else 
					$k=0;
				$stock_adv=intval($db->query("SELECT valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=".$joueurs[$k]." ORDER BY alea ASC LIMIT 1")->fetch_object()->valeur);
				for ($i=1; $i<=4; $i++)
					array_push($defausse_adv,intval(emplacement($db,"SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$joueurs[$k]." and nom='Pile de défausse $i'")[0])); 
			}
		}
			
		/****************************ALGO*IA**************************************/
		
		$row=$db->query("SELECT carte1, carte2, carte3, carte4, carte5 FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$tour." and nom='Main'")->fetch_object();
		$nb_cartes_pioche=$db->query("SELECT count(id_partie) FROM `cartes` WHERE id_partie=".$_GET['id_partie']." and etat=0")->fetch_array()[0];
		if ($row->carte1==null && $row->carte2==null && $row->carte3==null && $row->carte4==null && $row->carte5==null && $nb_cartes_pioche!=0)		
			$idimg=array("pioche", 1);
		
		for ($i=1; $i<=4; $i++){
			if (count($idimg)==0){
				if ($stock==$centrale[$i-1]+1 || $stock==13){
					$idimg=array("centrale$i", $centrale[$i-1]);
					$old=array("carte_stock", $stock);
				}
			}
		}
		
		for ($j=1; $j<=4; $j++){
			for ($i=1; $i<=5; $i++){
				if (count($idimg)==0){									
					if(adv($main[$i-1],$defausse_adv,$stock_adv,$defausse,$main) || strat($main,$defausse,$stock)!=0){
						if ($i!=5){
							if (($main[$i-1]==$centrale[$j-1]+1 || $defausse[$i-1]==$centrale[$j-1]+1) && (adv($main[$i-1],$defausse_adv,$stock_adv,$defausse,$main) || strat($main,$defausse,$stock)!=0)){
								if ($main[$i-1]==$centrale[$j-1]+1 && $centrale[$j-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$j-1]<$stock){
									$idimg=array("centrale$j", $centrale[$j-1]);
									$old=array("carte$i", $main[$i-1]);		
								}else if ($centrale[$j-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$j-1]<$stock){
									$idimg=array("centrale$j", $centrale[$j-1]);
									$old=array("defausse$i", $defausse[$i-1]);
								}
							}
						}else if ($main[$i-1]==$centrale[$j-1]+1 && (adv($main[$i-1],$defausse_adv,$stock_adv,$defausse,$main) || strat($main,$defausse,$stock)!=0)  && $centrale[$j-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$j-1]<$stock){
								$idimg=array("centrale$j", $centrale[$j-1]);
								$old=array("carte$i", $main[$i-1]);
						}
					}
				}
			}
		}
		for ($k=1; $k<=4; $k++){
			for ($l=1; $l<=5; $l++){
				if (count($idimg)==0){					
					if ($l!=5){
						if ($main[$l-1]==13){
							if ($centrale[$k-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$k-1]<$stock ){
								$idimg=array("centrale$k", $centrale[$k-1]);
								$old=array("carte$l", $main[$l-1]);
							}
						}else if ($defausse[$l-1]==13){		
							if ($centrale[$k-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$k-1]<$stock ){
								$idimg=array("centrale$k", $centrale[$k-1]);
								$old=array("defausse$l", $defausse[$l-1]);
							}
						}
					}else if ($main[$l-1]==13){	
							if ($centrale[$k-1]>=$stock-strat($main,$defausse,$stock)-1 && $centrale[$k-1]<$stock ){
								$idimg=array("centrale$k", $centrale[$k-1]);
								$old=array("carte$l", $main[$l-1]);
							}
					}
				}
			}
		}
		for ($j=1; $j<=4; $j++){
			for ($i=1; $i<=5; $i++){
				if (count($idimg)==0){				
					if(adv($main[$i-1],$defausse_adv,$stock_adv,$defausse,$main)){
						if ($i!=5){
							if ($main[$i-1]==$centrale[$j-1]+1 || $defausse[$i-1]==$centrale[$j-1]+1){
								if ($main[$i-1]==$centrale[$j-1]+1){
									$idimg=array("centrale$j", $centrale[$j-1]);
									$old=array("carte$i", $main[$i-1]);		
								}else {
									$idimg=array("centrale$j", $centrale[$j-1]);
									$old=array("defausse$i", $defausse[$i-1]);
								}
							}
						}else if ($main[$i-1]==$centrale[$j-1]+1){
								$idimg=array("centrale$j", $centrale[$j-1]);
								$old=array("carte$i", $main[$i-1]);
						}
					}
				}
			}
		}
		for ($j=1; $j<=4; $j++){
			for ($i=1; $i<=5; $i++){
				if (count($idimg)==0){									
					if(($main[0]!=null && $main[1]==null && $main[2]==null && $main[3]==null  && $main[4]==null) || 
					($main[0]==null && $main[1]!=null && $main[2]==null && $main[3]==null  && $main[4]==null) || 
					($main[0]==null && $main[1]==null && $main[2]!=null && $main[3]==null  && $main[4]==null) || 
					($main[0]==null && $main[1]==null && $main[2]==null && $main[3]!=null  && $main[4]==null) || 
					($main[0]==null && $main[1]==null && $main[2]==null && $main[3]==null  && $main[4]!=null)){
						if ($main[$i-1]==$centrale[$j-1]+1){
							if ($main[$i-1]==$centrale[$j-1]+1){
								$idimg=array("centrale$j", $centrale[$j-1]);
								$old=array("carte$i", $main[$i-1]);		
							}
						}
					}
				}
			}
		}
		
		
		$maxi=0;
		$maxi_defausse=0;
		for ($i=1; $i<=4; $i++){
			if (count($idimg)==0){
				if ($defausse[$i-1]==0){								
					for ($j=1; $j<=5; $j++){
						if ($main[$j-1]>$maxi && $main[$j-1]!=13){
							$maxi=$main[$j-1];
							$k=$j;
						}
					}
					$idimg=array("defausse$i", $defausse[$i-1]);
					$old=array("carte$k", $main[$k-1]);
				}
			}
		}
		for ($i=1; $i<=4; $i++){		
			for ($j=1; $j<=5; $j++){
				if (count($idimg)==0){
					if ($main[$j-1]==$defausse[$i-1]-1){
						$idimg=array("defausse$i", $defausse[$i-1]);
						$old=array("carte$j", $main[$j-1]);
					}
				}
			}
		}
		if (count($idimg)==0){
			for ($j=1; $j<=4; $j++){
				if ($defausse[$j-1]>$maxi_defausse && $defausse[$j-1]!=13){
					$maxi_defausse=$defausse[$j-1];
					$l=$j;
				}
			}
			for ($j=1; $j<=5; $j++){
				if ($main[$j-1]>$maxi &&  $main[$j-1]!=13){
					$maxi=$main[$j-1];
					$k=$j;
				}
			}
			$idimg=array("defausse$l", $defausse[$l-1]);
			$old=array("carte$k", $main[$k-1]);
		}
		if (count($old)!=0)
			header("Location: jeu.php?id_partie=".$_GET['id_partie']."&id_joueur=".$tour."&".$idimg[0]."=".$idimg[1]."&".$old[0]."=".$old[1]);
		else
			header("Location: jeu.php?id_partie=".$_GET['id_partie']."&id_joueur=".$tour."&".$idimg[0]."=".$idimg[1]);
		
			
		echo "<input type='hidden' id='idimgia' value='".$idimg[0]."' />";
		echo "<input type='hidden' id='idimgia1' value='".$idimg[1]."' />";
		if (count($old)!=0){
		echo "<input type='hidden' id='oldia' value='".$old[0]."' />";
		echo "<input type='hidden' id='oldia1' value='".$old[1]."' />";
		}else{
		echo "<input type='hidden' id='oldia' value='-1' />";
		}
	/************************FIN*ALGO*IA**************************************/
	}else{	
		
		$disabled=1;
		foreach ($joueurs as $value){
			if ($value==7 || $value==8 || $value==9 || $value==10 || $value==11)
				$disabled+=1;
		}
		if (count($joueurs)==$disabled)
			$disabled="disabled";
		else 
			$disabled="";
		echo "<p style='text-align:center; margin:0 auto;' ><a href='regles.pdf' target='_blank' >Cliquez ici pour voir la règle du jeu.</a></p>";

		for ($j=2; $j<=$nb; $j++){
			if ($j==2){
			if ($donnees_partie->joueur3==null)
				$style_div = "width:33%; margin:0 auto;";
			else if ($donnees_partie->joueur4==null) 
				$style_div =  "width:50%; margin:0 auto; text-align:center; float:left;";
			else 
				$style_div =  "width:33%; margin:0 auto; float:left;";
			}else if ($j==3){
			if ($donnees_partie->joueur4==null) 
				$style_div =  "width:50%; margin:0 auto; text-align:center; float:left;";
			else 
				$style_div =  "width:33%; margin:0 auto; float:left";
			}else if ($j==4){
			$style_div =  "width:33%; float:left";
			}else if ($j==5){
			$style_div =  "width:175px ;float:left;";
			}else{
			$style_div =  "width:175px ;float:right;";
			}
			$div_j="div_j".$j;
			echo "<div id='$div_j' style='$style_div'>
			<fieldset>
			<legend>";	
			echo $db->query("SELECT pseudo FROM utilisateurs WHERE id_user=".$joueurs[$j-1])->fetch_object()->pseudo;
			/*var_dump($dispos);*/	
			$test_invitation=0;
			if ($tour!=7 && $tour!=8 && $tour!=9 && $tour!=10 && $tour!=11){
				if ($dispos[$j-1]==null){
					echo "<span style='color:red;'> &bull; </span>";
					if ($joueurs[$j-1]==$tour && $gagne==0){
						$pas_la=1;				
						$message_tour="$nom_joueur n'est pas connecté ...";
						$test_invitation=1;	
					}
				}else {
					if (strtotime("now")-strtotime($dispos[$j-1])<60)			
						echo "<span style='color:green;'> &bull; </span>";				
					else {
						echo "<span style='color:orange;'> &bull; </span>";				
						if ($joueurs[$j-1]==$tour && $gagne==0){
							$pas_la=2;					
							$message_tour="$nom_joueur n'est plus connecté ...";
							$test_invitation=1;	
						}
					}
				}
			}
			if ($test_invitation==1){
				$invit="";					
				for ($in=0;$in<count($joueurs);$in++){					
					if ($joueurs[$in]!=$_SESSION["id"] && $joueurs[$in]!=7 && $joueurs[$in]!=8 && $joueurs[$in]!=9 && $joueurs[$in]!=10 && $joueurs[$in]!=11){
						for ($jn=1;$jn<=6;$jn++){	
							$resultat_invit=$db->query("SELECT id_partie FROM parties WHERE invitation IS null and gagne=0 and joueur$jn=".$joueurs[$in]." and CURRENT_TIMESTAMP-derniere_connexion$jn<60");
							while($id_parte_invit=$resultat_invit->fetch_assoc()){
								if ($id_parte_invit["id_partie"]!=$_GET["id_partie"] && $id_parte_invit["id_partie"]!=null){
									$invit=$invit.$db->query("SELECT pseudo FROM utilisateurs WHERE id_user=".$joueurs[$in])->fetch_array()[0]." joue en ce moment sur une autre partie.<br>
									<input type='button' style='background-color:green; color:white; font-weight:bold;' value='Envoyer une invitation' onmousedown='invitation(".$id_parte_invit['id_partie'].",".$joueurs[$in].",".$_SESSION['id'].",".$_GET['id_partie'].")'><br>";
								}
							}
						}
					}
				}			
				if ($invit!="")
					$invitation="<div id='invitation' style='position:absolute; top:0; right:0; background-color:$couleur_fond;'><fieldset><legend>Invitations</legend>$invit</fieldset></div>";
			}			
			echo '</legend>';	
			$invites=$db->query("SELECT invitation FROM parties WHERE id_partie=".$_GET["id_partie"])->fetch_array()[0];
			if ($invites!=null){
				$pos = strpos($invites, ";");
				$pos2 = strpos($invites, "!");
				$inviteur=substr($invites, 0,$pos);
				$id_invite=substr($invites, $pos+1,$pos2-$pos-1); 
				$partie_inviteur=substr($invites, $pos2+1); 
				if (intval($id_invite)==$_SESSION['id']){
					$inviteur=$db->query("SELECT pseudo FROM utilisateurs WHERE id_user=".intval($inviteur))->fetch_array()[0];					
					$invite="<div id='invite' style='position:absolute; top:0; right:0; background-color:$couleur_fond;'><fieldset><legend>Invitation</legend>$inviteur vous invite à rejoindre immédiatement sa partie.<br>
					<input type='button' style='background-color:green; color:white; font-weight:bold;' value='Accepter' onmousedown='invitation(".$_GET["id_partie"].",-1,0,$partie_inviteur)'>
					<input type='button' style='background-color:red; color:white; font-weight:bold;' value='Décliner' onmousedown='invitation(".$_GET["id_partie"].",-2,0,0)'><br></fieldset></div>";
				}
			}

			if ($j==5){
				$class_stock="style='margin-top:30px;'";
				$class_img="class='j5'";
				$class_p="'float:right; margin-right:25px;'";
			}else if ($j==6){
				$class_stock="style='margin-top:30px;'";
				$class_img="class='j6'";
				$class_p="'float:right; margin-right:25px;'";
			}else {
				$class_stock="style='margin-left:30px;'";
				$class_img="";			
				$class_p="'float:right; margin-right:60px;'";
				}
			for ($i=1; $i<=4; $i++){
				$query = "SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$joueurs[$j-1]." and nom='Pile de défausse $i'";
				$carte=emplacement($db,$query);
				if ($nuit==1 && $carte[0]==0){
					echo "<img src='cartes/0$carte[0].png' $class_img alt='$carte[0]' style>";

				}else{
					echo "<img src='cartes/$carte[0].png' $class_img alt='$carte[0]' style>";
				}
			}		
			if ($gagne!=$joueurs[$j-1]){
				$carte=$db->query("SELECT valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=".$joueurs[$j-1]." ORDER BY alea ASC LIMIT 1")->fetch_object()->valeur;
				if ($nuit==1 && $carte[0]==0){
					echo "<img src='cartes/0$carte.png' $class_img $class_stock alt='$carte' style>
					<span style=$class_p>";			
					
				}else{
					echo "<img src='cartes/$carte.png' $class_img $class_stock alt='$carte' style>
					<span style=$class_p>";			
				}
					$eliminer= $db->query("SELECT count(id_partie) FROM cartes WHERE etat=".$joueurs[$j-1]." and id_partie =".$_GET['id_partie'])->fetch_array()[0];
					echo $eliminer;
					if ($eliminer<2)
						echo " carte restante </span>";
					else
						echo " cartes restantes </span>";
			}
			echo "
			</fieldset>	
			</div>";
		}
		?>
		<div id="div_central" style="width:900px; margin:0 auto; padding:19px;">
		<fieldset id="fieldset1" <?php if ($nuit==1) echo "style='border: solid white 2pt;'" ?>>
		<legend>Pioche</legend>	
		<img src='cartes/14.png' class="grandes" id='cartepioche' onmousedown="pioche();"  alt="14" style>	
		<?php //echo $db->query("SELECT count(id_partie) FROM cartes WHERE etat=0 and id_partie =".$_GET['id_partie'])->fetch_array()[0]." cartes restantes";  ?>
		</fieldset>	
		<fieldset id="fieldset2" <?php if ($nuit==1) echo "style='border: solid white 2pt;'" ?>>
		<legend>Séries centrales</legend>	
		<?php 
			$span="<br>";
			for ($i=1; $i<=4; $i++){
				$carte = emplacement($db,"SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=0 and nom='Série centrale $i'"); 				
				if ($nuit==1 && $carte[0]==0){
					echo "<div style='float:left;'><img src='cartes/0$carte[0].png' id='centrale$i' onclick=\"old=bordure('centrale$i',old);\" class='grandes centrale' alt='$carte[1]' style>";
				}else{
					echo "<div style='float:left;'><img src='cartes/$carte[0].png' id='centrale$i' onclick=\"old=bordure('centrale$i',old);\" class='grandes centrale' alt='$carte[1]' style>";
				}
				if ($carte[0]==13){
					$span="<br><p style='text-align:center; margin:0 auto;' id='span$i'>$carte[1]</p>";				
					echo $span;
				}
				echo "</div>";
				
			}
			//if ($span!="<br>")
				
		?>	
		</fieldset>			
		</div>	
		
		<div id="cadre_discussion" style='float:left;'>
			<fieldset style="width:260px; height:400px; margin:0 10px 0 20px;">
				<legend <?php echo $disabled; ?> >Discussion instantannée  : </legend>			
			<?php 
					if ($disabled=="disabled")
						echo "<button id='bouton_visio' disabled>Conversation vidéo Jitsi indisponible</button>";
					else{
						$visio=$db->query("SELECT visio FROM parties WHERE id_partie=".$_GET['id_partie'])->fetch_array()[0];
						if ($visio==0)
							echo "<button id='bouton_visio' onclick='visio()'><a target='_blank' href='https://meet.jit.si/Visioskipbalexischatelainpartie". $_GET['id_partie']. "'>Lancer une conversation vidéo Jitsi</a></button>";
						else {
							$pseudo_visio=$db->query("SELECT pseudo FROM utilisateurs WHERE id_user=".$visio)->fetch_array()[0];
							echo "<h3 id='bouton_visio'><a target='_blank' href='https://meet.jit.si/Visioskipbalexischatelainpartie". $_GET['id_partie']. "'>Une conversation vidéo Jitsi a été créée par ".$pseudo_visio.". Cliquez ici pour la rejoindre.</a></h3>";
						}
					}
					
					#$touslesmessages=$db->query("SELECT * FROM discussion WHERE id_partie=".$_GET['id_partie']." ORDER BY id_message DESC");
					#$textarea="";
					#while ($msg= $touslesmessages->fetch_object())
						#$textarea.=$msg->date_creation." ".$db->query("SELECT pseudo FROM utilisateurs WHERE id_user =".$msg->id_joueur)->fetch_array()[0]." :\n".$msg->message."\n";					
					$touslesmessages=$db->query("SELECT * FROM discussion WHERE id_partie=".$_GET['id_partie']." ORDER BY id_message DESC");
					$textarea="";
					$x=0;
					while ($msg= $touslesmessages->fetch_object()){
						if ($x==0){
							$id_joueur_msg=$msg->id_joueur;
							$creation_msg=$msg->date_creation;
						}
						$x+=1;
						$textarea.=$msg->date_creation." ".$db->query("SELECT pseudo FROM utilisateurs WHERE id_user =".$msg->id_joueur)->fetch_array()[0]." :\n".$msg->message."\n";
					}
					$msg_audio="";
					if ($x!=0){
						if ($id_joueur_msg!=$_SESSION["id"] && intval(time()-strtotime($creation_msg))<=1)		
							$msg_audio="name='audio_msg'";
					}
				?>
			<!--<strong> Nouveauté : Jouez maintenant contre des intelligences artificielles.</strong>-->
			<textarea id="fil_messages" <?php echo $msg_audio; echo $disabled; ?> style="<?php echo $fieldset_blanc; ?> background: transparent; border: none;" rows="20" cols="33"><?php echo $textarea; if ($disabled=="disabled") echo "La discussion instantanée est désactivée car vous êtes le seul humain de cette partie.";?>
			</textarea>
			<textarea <?php echo $disabled; ?> style='position:absolute; top:800px; left:<?php 	if ($nb>4) echo(45+175); else echo(45); ?>px;' onKeyPress="if (event.keyCode == 13 && this.value!='') envoi_message();" id="message" rows="2" cols="33" placeholder="Votre message ..." ></textarea>
			<input <?php echo $disabled; ?> style='position:absolute; top:840px; left:<?php if ($nb>4) echo(125+175); else echo(125); ?>px;' onmousedown="if (message.value!='') envoi_message();" type="button" id='bouton_message' value="Envoyer" />
		</div>
		<?php if ($tour==$_SESSION["id"] && $gagne==0 && $pas_la==0){ ?>
		<img id="panneau" src='feu_vert.png' style=" margin:0 auto; text-align:center; float: right; width:150px;" alt='à_votre_tour'>
		<?php }else if ($pas_la!=0 || $gagne!=0 ) {?>
		<img id="panneau" src='sens_interdit.png' style=" margin:0 auto; text-align:center; float: right; width:150px;" alt='Ce_n_est_pas_votre_tour'>
		<?php }else { ?>
		<img id="panneau" src='attente.png' style=" margin:0 auto; text-align:center; float: right; width:75px; margin-right:40px;" alt='pas_votre_tour'>
		<?php }
		echo $invitation;	
		echo $invite;
		?>		
		<br><h2 id="indic_tour" style="float: right; width:250px; text-align:right; padding-right:50px;" ><?php echo $message_tour; ?></h2>
		<div id='piles_defausses' style="/*padding:20px;*/ margin:0 auto; width:900px; text-align:center;">
			<?php 		
			/*if ($nb==6)
				echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; ";*/
			for ($i=1; $i<=4; $i++){
				$carte = emplacement($db,"SELECT * FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$_SESSION["id"]." and nom='Pile de défausse $i'"); 		
				if ($nuit==1 && $carte[0]==0){
					echo "<img src='cartes/0$carte[0].png' id='defausse$i' onclick=\"old=bordure('defausse$i',old);\" class='grandes defausse' alt='$carte[0]' style>";
				}else{				
					echo "<img src='cartes/$carte[0].png' id='defausse$i' onclick=\"old=bordure('defausse$i',old);\" class='grandes defausse' alt='$carte[0]' style>";
				}
			}
			if ($gagne!=$_SESSION["id"]){
				$carte = $db->query("SELECT valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=".$_SESSION["id"]." ORDER BY alea ASC LIMIT 1")->fetch_object()->valeur;
				if ($nuit==1 && $carte[0]==0){
					echo "<img src='cartes/0$carte.png' id='carte_stock' onclick=\"old=bordure('carte_stock',old);\"  class='grandes stock2' alt='$carte' style>";
				}else{
					echo "<img src='cartes/$carte.png' id='carte_stock' onclick=\"old=bordure('carte_stock',old);\"  class='grandes stock2' alt='$carte' style>";
				}
					echo "<br>Encore ";
				$eliminer= $db->query("SELECT count(id_carte) FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=".$_SESSION["id"])->fetch_array()[0];			
				echo $eliminer;
				if ($eliminer<2)
					echo " carte à éliminer pour gagner !";
				else
					echo " cartes à éliminer pour gagner !";
			}
			?>	
		</div>
		
		<?php 	
		if ($nb==6)
			$ajout="";
		else if ($nb==5)	
			$ajout="padding-left:120px;";
		else
			$ajout="padding-left:200px;";
		echo "<div id='div_main' style='width:1150px; $ajout padding-top:10px; text-align:center;'>";
		$row=$db->query("SELECT carte1, carte2, carte3, carte4, carte5, carte6 FROM emplacements WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$_SESSION["id"]." and nom='Main'")->fetch_assoc();
		$msg_pioche="";
		for ($i=0; $i<5; $i++){
			$j=$i+1;
			$j="carte".$j;
			if ($row[$j]!=null){
				$carte=$db->query("SELECT valeur FROM cartes WHERE id_carte=".$row[$j])->fetch_object()->valeur; 	
				echo "<img src='cartes/$carte.png' id='$j' onclick=\"old=bordure('$j',old);\" class='grandes main' alt='$carte' style>";				
			}else if ($row["carte6"]==null){
				$lacarte=$db->query("SELECT id_carte, valeur FROM cartes WHERE id_partie=".$_GET['id_partie']." and etat=0 ORDER BY alea ASC LIMIT 1")->fetch_object();
				if (!($lacarte==false)){
					$carte=$lacarte->valeur;
					$db->query("UPDATE cartes SET etat=-1 WHERE id_carte=".$lacarte->id_carte);
					$db->query("UPDATE emplacements SET ".$j."=".$lacarte->id_carte ." WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$_SESSION["id"]." and nom='Main'" );
					if ($nuit==1 && $carte[0]==0){
						echo "<img src='cartes/0$carte.png' id='$j' onclick=\"old=bordure('$j',old);\" class='grandes main' alt='$carte' style>";
					}else{
						echo "<img src='cartes/$carte.png' id='$j' onclick=\"old=bordure('$j',old);\" class='grandes main' alt='$carte' style>";
					}
				}else
					$msg_pioche = " Aucune autre carte à piocher";
			}
		}	
		echo $msg_pioche;
		if ($row["carte6"]==null)
			$db->query("UPDATE emplacements SET carte6=1 WHERE id_partie=".$_GET['id_partie']." and id_joueur=".$_SESSION["id"]." and nom='Main'" );
		echo "</div>";
		
		echo "<input type='hidden' id='idimgia' value='' />";
		echo "<input type='hidden' id='idimgia1' value='' />";
		echo "<input type='hidden' id='oldia' value='' />";
		echo "<input type='hidden' id='oldia1' value='' />";
		
		echo "</body>";
	}			
	
	echo "<input type='hidden' id='test_IA' value='1' />";
	echo "<input type='hidden' id='1ere_actu' value='0' />";
	echo "<input type='hidden' id='id_partie' value=".$_GET['id_partie']." />";
	echo "<input type='hidden' id='session_id' value=".$_SESSION['id']." />";
	echo "<input type='hidden' id='tour' value=".$tour." />";
	echo "<input type='hidden' id='gagne' value=".$gagne." />";
	echo "<p hidden id='tampon' ></p>";
}

function adv($carte,$defausse_adv,$stock_adv,$defausse,$main){
	$counts = array_count_values($defausse_adv);		
	$compteur1=0;
	if(isset($counts[13]))
		$compteur=$counts[13];
	else 
		$compteur=0;	
	if ($carte+1==$stock_adv && array_search($carte+1, $defausse)==false && array_search($carte+1, $main)==false)
		return false;
	else {
		for ($i=1;$i<=4;$i++){		
			if (((array_search($carte+$i, $defausse_adv)!=false || $compteur>$compteur1) && $carte+$i+1==$stock_adv)  && array_search($carte+$i+1, $defausse)==false && array_search($carte+$i+1, $main)==false)
				return false;
		}
		return true;
	}
}
function strat($main,$defausse,$carte){	
	$compteur0=0;
	$compteur=0;
	$compteur1=0;
	$compteur2=0;
	foreach ($main as $value){
		if ($value==13)
			$compteur0+=1;
	}
	foreach ($defausse as $value){
		if ($value==13)
			$compteur+=1;
	}
	for ($i=1;$i<=12;$i++){
		if ((array_search($carte-$i, $defausse)!=false || array_search($carte-$i, $main)!=false  || $compteur>$compteur1 || $compteur0>$compteur2)){
			if (!(array_search($carte-$i, $defausse)!=false || array_search($carte-$i, $main)!=false) && $compteur>$compteur1)
				$compteur1+=1;
			else if (!(array_search($carte-$i, $defausse)!=false || array_search($carte-$i, $main)!=false) && $compteur0>$compteur2)
				$compteur2+=1;	
		}else
			return $i-1;
	}
}	
?>