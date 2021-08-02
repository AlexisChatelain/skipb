<?php		
require_once("db_file.php");

if (isset($_POST["compteur_mon_tour"]) && isset($_POST["compteur_leur_tour"]) && isset($_POST["compteur_gagnees"]) && isset($_POST["compteur_perdues"]) && isset($_POST["id"])){
	if ($db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and tour=".$_POST["id"]." and gagne=0")->fetch_array()[0]==$_POST["compteur_mon_tour"] &&
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and tour!=".$_POST["id"]." and gagne=0")->fetch_array()[0]==$_POST["compteur_leur_tour"] &&
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and gagne=".$_POST["id"])->fetch_array()[0]==$_POST["compteur_gagnees"] && 
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and gagne!=0 and gagne!=".$_POST["id"])->fetch_array()[0]==$_POST["compteur_perdues"])
		echo "OK";
	/*else
		echo "Recharge".
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and tour=".$_POST["id"]." and gagne=0")->fetch_array()[0].
		"<br>".$_POST["compteur_mon_tour"]."<br>".
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and tour!=".$_POST["id"]." and gagne=0")->fetch_array()[0].
		"<br>".$_POST["compteur_leur_tour"]."<br>".
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and gagne=".$_POST["id"])->fetch_array()[0].
		"<br>".$_POST["compteur_gagnees"]."<br>".
		$db->query( "SELECT count(id_partie) FROM parties WHERE (joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"].
		" or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"].") and gagne!=0 and gagne!=".$_POST["id"])->fetch_array()[0].
		"<br>".$_POST["compteur_perdues"]."<br>";*/
		
}elseif (isset($_POST["choix"]) && isset($_POST["id"])){
	$donnees = $db->query("SELECT * FROM utilisateurs WHERE id_user=".$_POST["id"])->fetch_object();
	require('mail.php');
	if ($_POST["choix"]==1){
		$adress = array($donnees->mail, $donnees->pseudo, "");
		$subject = 'Acceptation de votre inscription';
		$body = '<html>
		<head>
		<title>Acceptation de votre inscription au Skip-Bo&reg;</title>	   
		<meta charset="UTF-8" />
		</head>
		<body>	  
		<p>Bonjour '.$donnees->pseudo.',<br>
		Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>
		Pour rappel, ce jeu est une réplique sur Internet du jeu de cartes Skip-Bo&reg; de &copy; Mattel.
		Ce jeu doit donc normalement respecté des droits d\'auteurs, c\'est pourquoi ne sont
		autorisées que l\'espace resserré de la famille.<br>
		Par conséquent, vous venez d\'être accepté à sortir du mode de démonstration 
		et d\'avoir accès à toutes les fonctions du jeu (nombre de parties indéfini,
		30 cartes dans le stock pour des parties de 2 à 4 joueurs 
		et 20 pour des parties de 5 à 6 joueurs).<br>
		A bientôt sur le site, <br>
		Merci de ne pas répondre à ce mail :)<br>
		</p>
		</body>
		</html>';
		$alt='
		Bonjour '.$donnees->pseudo.',
		Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.
		Pour rappel, ce jeu est une réplique sur Internet du jeu de cartes Skip-Bo(R) de (C) Mattel.
		Ce jeu doit donc normalement respecté des droits d\'auteurs, c\'est pourquoi ne sont autorisées que l\'espace resserré de la famille.
		Par conséquent, vous venez d\'être accepté à sortir du mode de démonstration et d\'avoir accès à toutes les fonctions du jeu (nombre de parties indéfini, 30 cartes dans le stock pour des parties de 2 à 4 joueurs  et 20 pour des parties de 5 à 6 joueurs).
		A bientôt sur le site;,
		Merci de ne pas répondre à ce mail :)';
		if (sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeux de cartes")){	
			$db->query("UPDATE utilisateurs SET admin=true WHERE pseudo='".$donnees->pseudo."'");	
			echo "OK";
		}
	}else{
		$adress = array($donnees->mail, $donnees->pseudo, "");
		$subject = 'Refus de votre inscription';
		$body = '<html>
		<head>
		<title>Refus de votre inscription</title>	   
		<meta charset="UTF-8" />
		</head>
		<body>	  
		<p>Bonjour '.$donnees->pseudo.',<br>
		Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>
		Pour rappel, ce jeu est une réplique sur Internet du jeu de cartes Skip-Bo&reg; de &copy; Mattel.
		Ce jeu doit donc normalement respecté des droits d\'auteurs, c\'est pourquoi ne sont
		autorisées que l\'espace resserré de la famille.<br>
		Par conséquent, vous n\'avez malheureusement pas été accepté à sortir du mode de démonstration 
		et d\'avoir accès à toutes les fonctions du jeu.<br>
		Votre compte vient aussi d\'être supprimé, toutes vos données personnelles (pseudo, mail, mot de passe, etc.) ne sont plus stockées sur le serveur.<br>
		A bientôt sur le site, <br>
		Merci de ne pas répondre à ce mail :)<br>
		</p>
		</body>
		</html>';
		$alt='
		Bonjour '.$donnees->pseudo.',
		Ceci est un message texte automatique envoyé par le site d\'Alexis Chatelain.
		Pour rappel, ce jeu est une réplique sur Internet du jeu de cartes Skip-Bo(R) de (C) Mattel.
		Ce jeu doit donc normalement respecté des droits d\'auteurs, c\'est pourquoi ne sont autorisées que l\'espace resserré de la famille.
		Par conséquent, vous n\'avez malheureusement pas été accepté à sortir du mode de démonstration 	et d\'avoir accès à toutes les fonctions du jeu.
		Votre compte vient aussi d\'être supprimé, toutes vos données personnelles (pseudo, mail, mot de passe, etc.) ne sont plus stockées sur le serveur.
		A bientôt sur le site,
		Merci de ne pas répondre à ce mail :)';		
		if (sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeux de cartes")){
			while ($partie = $db->query("SELECT id_partie FROM parties WHERE joueur1=".$_POST["id"]." or joueur2=".$_POST["id"]." or joueur3=".$_POST["id"]." or joueur4=".$_POST["id"]." or joueur5=".$_POST["id"]." or joueur6=".$_POST["id"])->fetch_array()){				
				$db->query("DELETE FROM cartes WHERE id_partie=".$partie[0]);				
				$db->query("DELETE FROM emplacements WHERE id_partie=".$partie[0]);				
				$db->query("DELETE FROM discussion WHERE id_partie=".$partie[0]);
				$db->query("DELETE FROM parties WHERE id_partie=".$partie[0]);
			}
			$db->query("DELETE FROM utilisateurs WHERE id_user=".$_POST["id"]);
			echo "OK";
		}
	}
}else if (isset($_POST["code"]) && isset($_POST["pseudo"])){
	$pseudo = urldecode($_POST['pseudo']);
	$resultat = $db->query("SELECT confirmation FROM utilisateurs WHERE pseudo='{$pseudo}'");
	$row = $resultat->fetch_object();
	if ($_POST["code"]==$row->confirmation){
		$db->query("UPDATE utilisateurs SET confirmation='OK' WHERE pseudo='{$_POST['pseudo']}'");
		require('../config/mail.php');
		$adress = array("alexis.chatelain@yahoo.fr", "Admin", "Skip-Bo");
		$subject = 'Utilisateur en attente d\'acceptation dans le Skip-Bo';
		$body = '<html>
		<head>
		<title>Utilisateur en attente d\'acceptation dans le Skip-Bo</title>	   
		<meta charset="UTF-8" />
		</head>
		<body>	  
		<p>Bonjour,<br>
		Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>
		Un nouvel utilisateur de pseudo : '.$_POST["pseudo"].' est en attente d\'acceptation dans le Skip-Bo<br>
		<a href="http://'.$_SERVER['HTTP_HOST'].'/skipb/connexion.php#">Rendez-vous ici avec l\'utilisateur d\'ID égal à 1 pour l\'accepter ou le refuser.</a> <br>
		Merci de ne pas répondre à ce mail :)<br>
		</p>
		</body>
		</html>';
		$alt='
		Bonjour,
		Ceci est un message texte automatique envoyé par le site d\'Alexis Chatelain.
		Un nouvel utilisateur de pseudo : '.$_POST["pseudo"].' est en attente d\'acceptation dans le Skip-Bo<br>
		Rendez-vous sur alexischatelain.freeboxos.fr/skipb/connexion.php avec l\'utilisateur d\'ID égal à 1 pour l\'accepter ou le refuser.
		Merci de ne pas répondre à ce mail :)';				
		sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeu de cartes");
		echo "Code correct";
	}else{
		echo "Code incorrect";
	}
}else if (isset($_POST["question"]) && isset($_POST["oubli"])){
	$pseudo = urldecode($_POST['pseudo']);		
	$mail = urldecode($_POST['mail']);
	$mdp = urldecode($_POST['mdp']);
	$question = urldecode($_POST['question']);
	$reponse = urldecode($_POST['reponse']);
	$mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);			
	
	$result=$db->query("SELECT reponse FROM utilisateurs WHERE pseudo='{$pseudo}' and mail='{$mail}' and question='{$question}'");	
	if($result->num_rows==1)
		if (password_verify($reponse, $result->fetch_object()->reponse)){
			$db->query("UPDATE utilisateurs SET mdp='".$mdp_hash."' WHERE pseudo='{$_POST['pseudo']}'");
			require('../config/mail.php');
			$adress = array($mail, $pseudo, "");
			$subject = 'Votre mot de passe a bien été modifié';
			$body = '<html>
			<head>
			<title>Confirmation de votre inscription</title>	   
			<meta charset="UTF-8" />
			</head>
			<body>	  
			<p>Bonjour '.$pseudo.',<br>
			Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>
			Votre mot de passe Skip-Bo&reg; a bien été modifié à l\'instant<br>
			Si vous n\'êtes pas à l\'origine de ce changement, écrivez vite un message <a href="http://alexischatelain.freeboxos.fr/#contact">en cliquant vite sur ce lien (http://alexischatelain.freeboxos.fr/</a>)<br>
			Merci de ne pas répondre à ce mail :)<br>
			</p>
			</body>
			</html>';
			$alt='(logo du club) 
			Bonjour '.$pseudo.',
			Ceci est un message texte automatique envoyé par le site d\'Alexis Chatelain.
			Votre mot de passe a bien été modifié à l\'instant<br>		
			Si vous n\'êtes pas à l\'origine de ce changement, écrivez vite un message ici : http://alexischatelain.freeboxos.fr )<br>
			Merci de ne pas répondre à ce mail :)';				
			sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeu de cartes");
		}else{		
			echo "La réponse à la question secrète est incorrecte.";
	}else{
		$result=$db->query("SELECT id_user FROM utilisateurs WHERE pseudo='{$pseudo}' and mail='{$mail}'");	
		if($result->num_rows==0)
			echo "Le mail saisi n'est pas associé à ce pseudo.";
		else {
			$result=$db->query("SELECT id_user FROM utilisateurs WHERE pseudo='{$pseudo}' and question='{$question}'");	
			if($result->num_rows==0)
				echo "La question n'est pas associée à ce pseudo. Veuillez réessayer ultérieurement.";
			else {
				echo "Les informations saisies ne nous permettent pas de vous connecter, veuillez réessayer.";
			}
		}
	}
}else if (isset($_POST["mail"]) && isset($_POST["pseudo"])){
	$pseudo = urldecode($_POST['pseudo']);
	$nom = urldecode($_POST['nom']);
	$prenom = urldecode($_POST['prenom']);	
	$question = urldecode($_POST['question']);
	$reponse = urldecode($_POST['reponse']);
	$query = "SELECT pseudo FROM utilisateurs WHERE pseudo='{$pseudo}'";
	$resultat = $db->query($query);
	if($resultat->num_rows!=0){
		echo "Pseudo pas libre";
	}else{
		$mail = urldecode($_POST['mail']);
		$query = "SELECT mail FROM utilisateurs WHERE mail='{$mail}'";
		$resultat = $db->query($query);
		if($resultat->num_rows!=0){
			echo "Pas ok";
		}else{
			$mdp = urldecode($_POST['mdp']);
			$mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);				
			if (!$db->connect_errno) {
				$code="";
				for ($i=0; $i<15; $i++)
					$code.= chr(rand(65, 90));
				$query="INSERT INTO utilisateurs (confirmation, admin, demo, pseudo, prenom, nom, mail, mdp, question, reponse) VALUES('" . $code . "',false,false,'".$pseudo."', '".$prenom."', '".$nom."',  '" . $mail . "', '" .$mdp_hash. "', '" . $question . "', '" . $reponse . "')";
				$query_new_user = $db->query($query);
					if ($query_new_user) {
					require('../config/mail.php');
					$adress = array($mail, $pseudo, "");
					$subject = 'Confirmation que vous n\'êtes pas un robot';
					$body = '<html>
					<head>
					<title>Confirmation que vous n\'êtes pas un robot</title>	   
					<meta charset="UTF-8" />
					</head>
					<body>	  
					<p>Bonjour '.$pseudo.',<br>
					Ceci est un message automatique envoyé par le site d\'Alexis Chatelain.<br>
					Voici le code pour confirmer que vous n\'êtes pas un robot  : '.$code.'<br>
					Merci de ne pas répondre à ce mail :)<br>
					</p>
					</body>
					</html>';
					$alt='(logo du club) 
					Bonjour '.$pseudo.',
					Ceci est un message texte automatique envoyé par le site d\'Alexis Chatelain.
					Voici le code pour confirmer que vous n\'êtes pas un robot : '.$code.'
					Merci de ne pas répondre à ce mail :)';				
					sendMail($construction_mail, $adress, $subject, $body, $alt, true, "Jeu de cartes");
				} else 						
					echo "503";
			} else 						
				echo "503";
		}
	}
	$db->close();
}else if (isset($_POST["question"]) && isset($_POST["pseudo"])){
	$pseudo = urldecode($_POST['pseudo']);
	$result=$db->query("SELECT question FROM utilisateurs WHERE pseudo='{$pseudo}'");	
	if($result->num_rows!=0)
		echo $result->fetch_array()[0];
	else 
		echo "Rien";
}