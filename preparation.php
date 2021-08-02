<?php 

function envoi_mail($construction_mail,$mail,$pseudo){
	$adress = array($mail, $_SESSION["pseudo"], "");
	$subject = 'Une partie a commencé avec vous';
	$body = '<html>
	<head>
	<title>Une partie a commencé avec vous</title>	   
	<meta charset="UTF-8" />
	</head>
	<body>	  
	<p>Bonjour '.$pseudo.',<br>
	Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>'.
	$_SESSION["pseudo"].' vient de démarrer une partie de Skip-Bo&reg; avec vous !<br>
	<a href="http://'.$_SERVER['HTTP_HOST'].'/skipb/connexion.php#">Cliquez ici pour jouer.</a> <br>
	N\'oubliez pas : la partie a commencé donc après avoir cliqué sur le lien , connectez-vous et appuyez sur le bouton "Parties commencées".<br> 
	Merci de ne pas répondre à ce mail :)<br>
	</p>
	</body>
	</html>';
	$alt='
	Bonjour '.$pseudo.',
	Ceci est un message texte automatique envoyé par le site d\'Alexis Chatelain.'.
	$_SESSION["pseudo"].' vient de démarrer une partie de Skip-Bo(R) avec vous !
	N\'oubliez pas : la partie a commencé donc après avoir cliqué sur le lien , connectez-vous et appuyez sur le bouton "Parties commencées".
	Merci de ne pas répondre à ce mail :)';				
	sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeux de cartes");

}

function pseudo($db,$nom){
return($db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$nom."'")->fetch_array()[0]);
}
require_once("ConnexionClass.php"); 
$login = new Login();
require('../aca/config/mail.php');
if(!$login->isUserLoggedIn() || !isset($_POST["mode"])){
	header('Location: connexion.php');		
	die();
}else{	
	require_once("db_file.php");
	$poursuivre=0;
	if ($_POST["nb"]==2){
		$reponse=$db->query("SELECT id_partie FROM parties WHERE gagne=0 and joueur1 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2'])." ) and joueur2 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).") and joueur3 IS null and joueur4 IS null and joueur5 IS null and joueur6 IS null");
		if ($reponse->num_rows!=0)	
			$poursuivre=$reponse->fetch_array()[0];
	}else if ($_POST["nb"]==3){
		$reponse=$db->query("SELECT id_partie FROM parties WHERE gagne=0 and joueur1 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3'])." ) and joueur2 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3'])." ) and joueur3 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3'])." ) and joueur4 IS null and joueur5 IS null and joueur6 IS null");
		if ($reponse->num_rows!=0)	
			$poursuivre=$reponse->fetch_array()[0];
	}else if ($_POST["nb"]==4){
		$reponse=$db->query("SELECT id_partie FROM parties WHERE gagne=0 and joueur1 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4'])." ) and joueur2 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4'])." ) and joueur3 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4'])." ) and joueur4 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4'])." ) and joueur5 IS null  and joueur6 IS null");
		if ($reponse->num_rows!=0)	
			$poursuivre=$reponse->fetch_array()[0];
	}else if ($_POST["nb"]==5){
		$reponse=$db->query("SELECT id_partie FROM parties WHERE gagne=0 and joueur1 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5'])." ) and joueur2 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5'])." ) and joueur3 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5'])." ) and joueur4 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5'])." ) and joueur5 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5'])." ) and joueur6 IS null");
		if ($reponse->num_rows!=0)	
			$poursuivre=$reponse->fetch_array()[0];
	}else {
		$reponse=$db->query("SELECT id_partie FROM parties WHERE gagne=0 and joueur1 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." ) and joueur2 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." ) and joueur3 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." ) and joueur4 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." ) and joueur5 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." ) and joueur6 IN (".$_SESSION['id'].", ".pseudo($db,$_POST['joueur2']).", ".pseudo($db,$_POST['joueur3']).", ".pseudo($db,$_POST['joueur4']).", ".pseudo($db,$_POST['joueur5']).", ".pseudo($db,$_POST['joueur6'])." )");
		if ($reponse->num_rows!=0)	
			$poursuivre=$reponse->fetch_array()[0];
	}
	if ($poursuivre==0){
		$db->query("UPDATE utilisateurs SET demo=true WHERE pseudo='".$_SESSION['pseudo']."'");	
		$j2= $db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$_POST['joueur2']."'")->fetch_object()->id_user;	
		$db->query("UPDATE utilisateurs SET demo=true WHERE id_user=".$j2);	
		if ($_POST['joueur2']!="IA 2")
			envoi_mail($construction_mail,$db->query("SELECT mail FROM utilisateurs WHERE pseudo='".$_POST['joueur2']."'")->fetch_object()->mail,$_POST['joueur2']);
		if (isset($_POST['joueur3'])){
			if ($_POST['joueur3']!=""){
				$j3= $db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$_POST['joueur3']."'")->fetch_object()->id_user;			
				$db->query("UPDATE utilisateurs SET demo=true WHERE id_user=".$j3);	
				if ($_POST['joueur3']!="IA 3")
					envoi_mail($construction_mail,$db->query("SELECT mail FROM utilisateurs WHERE pseudo='".$_POST['joueur3']."'")->fetch_object()->mail,$_POST['joueur3']);
			}else
				$j3= "null";
		}else
			$j3= "null";
		
		if (isset($_POST['joueur4'])){
			if ($_POST['joueur4']!=""){
				$j4= $db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$_POST['joueur4']."'")->fetch_object()->id_user;
				$db->query("UPDATE utilisateurs SET demo=true WHERE id_user=".$j4);	
				if ($_POST['joueur4']!="IA 4")
					envoi_mail($construction_mail,$db->query("SELECT mail FROM utilisateurs WHERE pseudo='".$_POST['joueur4']."'")->fetch_object()->mail,$_POST['joueur4']);
			}else
				$j4= "null";
		}else
			$j4= "null";
		
		if (isset($_POST['joueur5'])){
			if ($_POST['joueur5']!=""){
				$j5= $db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$_POST['joueur5']."'")->fetch_object()->id_user;		
				$db->query("UPDATE utilisateurs SET demo=true WHERE id_user=".$j5);				
				if ($_POST['joueur5']!="IA 5")
					envoi_mail($construction_mail,$db->query("SELECT mail FROM utilisateurs WHERE pseudo='".$_POST['joueur5']."'")->fetch_object()->mail,$_POST['joueur5']);
			}else
				$j5= "null";
		}else
			$j5= "null";
		
		if (isset($_POST['joueur6'])){
			if ($_POST['joueur6']!=""){
				$j6= $db->query("SELECT id_user FROM utilisateurs WHERE pseudo='".$_POST['joueur6']."'")->fetch_object()->id_user;			
				$db->query("UPDATE utilisateurs SET demo=true WHERE id_user=".$j6);	
				if ($_POST['joueur6']!="IA 6")
					envoi_mail($construction_mail,$db->query("SELECT mail FROM utilisateurs WHERE pseudo='".$_POST['joueur6']."'")->fetch_object()->mail,$_POST['joueur6']);
			}else
				$j6= "null";
		}else
			$j6= "null";
		
		$query = "INSERT INTO parties (joueur1, joueur2, joueur3, joueur4, joueur5, joueur6, visio, tour, gagne) Values(".$_SESSION['id'].",$j2,$j3,$j4,$j5,$j6,0,$j2,0)";
		$resultat = $db->query($query);		
		$id_partie = $db->insert_id;
		$query="INSERT INTO cartes(valeur, etat, alea, id_partie) VALUES ";
		$liste=range(1, 162);
		for ($i=1;$i<14;$i++){
			if ($i==13)
				$max=18;
			else	
				$max=12;
			$j=0;
			while ($j<$max){
				$nb=rand(1, 162);
				if (isset($liste[$nb-1])){
					$query.="($i,0,$nb,$id_partie),";	
					 unset($liste[$nb-1]);
					 $j+=1;
				}
			}
		}
			$query=substr($query,0,-1);
			$resultat = $db->query($query);
			
			
			if (isset($_POST['joueur5'])){
				if ($_POST['joueur5']!="")		
					$stock=20;
				else 
					$stock=30;
			}else 
				$stock=30;
			if ($db->query("SELECT admin FROM utilisateurs WHERE pseudo='".$_SESSION['pseudo']."'")->fetch_array()[0]==0)			
				$stock=5;
			for ($i=1;$i<=$_POST["nb"];$i++){
				if ($i==1)
					$k=$_SESSION["id"];
				else if ($i==2)
					$k=$j2;
				else if ($i==3)
					$k=$j3;
				else if ($i==4)
					$k=$j4;
				else if ($i==5)
					$k=$j5;
				else 
					$k=$j6;	
				$query = "SELECT id_carte FROM cartes WHERE id_partie=$id_partie and etat=0 ORDER BY alea ASC LIMIT $stock";
				$resultat = $db->query($query);			
				while($result=$resultat->fetch_assoc())
					$db->query("UPDATE cartes SET etat=$k WHERE id_carte=".$result['id_carte']);
			}
			$query="INSERT INTO emplacements (nom,id_joueur,id_partie) VALUES ";
			for ($j=1;$j<=4;$j++)
				$query.="('Série centrale $j',0,$id_partie),";
			for ($i=1;$i<=$_POST["nb"];$i++){
				if ($i==1)
					$k=$_SESSION["id"];
				else if ($i==2)
					$k=$j2;
				else if ($i==3)
					$k=$j3;
				else if ($i==4)
					$k=$j4;
				else if ($i==5)
					$k=$j5;
				else 			
					$k=$j6;			
				$query.="('Main',$k,$id_partie),";
				for ($j=1;$j<=4;$j++)			
					$query.="('Pile de défausse $j',$k,$id_partie),";			
			}
			$query=substr($query,0,-1);
			$resultat = $db->query($query);
		 
			echo "<form action='/skipb/' id='myForm' method='GET'>
			<input type='hidden' id='id_partie' name='id_partie' value=".$id_partie." />
			</form>		
			<script>
			myForm.submit();
			</script>";	
		}else{			
			echo "<form action='/skipb/' id='myForm' method='GET'>
			<input type='hidden' id='id_partie' name='id_partie' value=".$poursuivre." />
			</form>		
			<script>
			/*
			alert('La partie que vous venez de demander ne peut pas se créer car une autre est déjà en cours avec le(s) même(s) joueur(s). Vous y serez redirigé automatiquement en cliquant sur OK.');
			*/
			myForm.submit();
			</script>";	
		}
	}
?>