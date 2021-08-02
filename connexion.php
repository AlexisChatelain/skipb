<!DOCTYPE html>
<html lang="fr">
	<head>
	<title>Jeu de cartes</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width">	
	<!--<meta http-equiv="refresh" content="0;URL=http://alexischatelain.freeboxos.fr/skipb/?id_partie=41">-->
	<link href="skipb.css" rel="stylesheet"> 
	</head>
	<?php require_once("ConnexionClass.php"); 
		$login = new Login();
		require_once("db_file.php");
	?>
<body>
<div id='page'>
<?php  if (1==2) // en cas de maintenance mettre if ($_SERVER['REMOTE_ADDR']!='ip_locale') avec la bonne ip locale
echo "Le jeu est en cours de maintenance pour vous permettre de récupérer votre compte en cas d'oubli de votre mot de passe.<br>
	Le retour à la normale est prévu à 16h10.<br>
	Toutes mes excuses pour la gêne occasionnée.";
else{	
	if($login->isUserLoggedIn()){ 
	echo "<style>html{	background-image:url(fond2.jpg); }</style>";
		$moi = $db->query("SELECT * FROM utilisateurs WHERE pseudo='".$_SESSION["pseudo"]."'")->fetch_object();
		if($moi->admin==0)
			echo "<h1 style='background-color:red;'>Attention, vous êtes en mode démonstration.</h1><br>
			<h2 style='background-color:red;'>Par conséquent, vous ne pourrez jouer qu'une seule partie et votre stock ne sera composé que de 5 cartes contre 30 habituellement.<br>
			Vous recevrez un mail dans les 24 heures vous informant de l'acceptation ou du refus de votre inscription.<br>
			En cas de refus, l'explication sera inscrite dans le mail et votre compte sera automatiquement supprimé.</h2>";
		else if ($moi->id_user==1){
			$resultat=$db->query("SELECT * FROM utilisateurs WHERE admin=0");			
			if($resultat->num_rows!=0){
				echo "<table style='background-color:#F0FFFF; margin:0 auto;'>
				<tr><th>ID</th><th>code</th><th>admin</th><th>demo</th><th>pseudo</th>
				<th>prenom</th><th>nom</th><th>mail</th><th>dernière connexion</th>
				<th>Accepter</th><th>Refuser</th></tr>";
				while ($table = $resultat->fetch_object()){
					echo "<tr><td>".$table->id_user."</td><td>".$table->confirmation."</td>
					<td>".$table->admin."</td><td>".$table->demo."</td>
					<td>".$table->pseudo."</td><td>".$table->nom."</td>
					<td>".$table->prenom."</td><td>".$table->mail."</td>
					<td>".$table->derniere_connexion."</td>
					<td><input type='button' style='background-color:green; color:white; font-weight:bold;' value='Accepter' onmousedown='reponse_adhesion(1,".$table->id_user.")'></td>					
					<td><input type='button' style='background-color:red; color:white; font-weight:bold;'  value='Refuser'  onmousedown='reponse_adhesion(0,".$table->id_user.")'></td>	
					</tr>";
				}
				echo "</table>";
			}
		}	
		echo "<br><h2 class='accueil' id='bonjour'>Bonjour ".$_SESSION["pseudo"].", faites votre choix.</h2>"; 	
	}else 
		echo "<style>html{	background-image:url(fond.jpg); background-size: 100% 100%; }</style><br><br>"; ?>
	<div id="col1"  <?php if(!$login->isUserLoggedIn()) echo "hidden"; ?>>
	<?php 	
	$suggestion="";
	$nb_suggestion=0;
	for ($jn=1;$jn<=6;$jn++){	
		$suggestions1=$db->query("SELECT joueur$jn FROM parties WHERE gagne=0 and CURRENT_TIMESTAMP-derniere_connexion$jn<60 GROUP BY joueur$jn");
		while ($suggestions=$suggestions1->fetch_assoc()){
			if ($suggestions["joueur".$jn]!=$_SESSION["id"]){
				$pseudo_suggestion=$db->query("SELECT pseudo FROM utilisateurs WHERE id_user=".$suggestions["joueur".$jn])->fetch_array()[0];
				if (strpos($suggestion, $pseudo_suggestion)==false ){
					$suggestion=" ".$suggestion.$pseudo_suggestion."<br>";
					$nb_suggestion+=1;
				}
			}
		}
	}	
	if ($nb_suggestion==1)
		echo "<div style='width:400px; margin: 0 auto;'><fieldset><legend>Suggestions de parties :</legend>$suggestion est connecté·e</fieldset><br></div>";
	else if ($nb_suggestion>=2)
		echo "<div style='width:400px; margin: 0 auto;'><fieldset><legend>Suggestions de parties :</legend>Les personnes suivantes sont connectées :<br>$suggestion</fieldset><br></div>";
	?>
		<div id="nouvelle" class="bouton">Nouvelle partie</div>
		<div id="restauration" class="bouton" onclick="affich_restauration();">Parties commencées</div>
		<div id="deconnexion" class="bouton">Se déconnecter</div>
	</div>
	<div id="col2" <?php if($login->isUserLoggedIn()) echo "hidden"; ?>>
		<h2 style="line-height: 2.0; color:white; font-weight:bold; background-color: rgba(0, 0, 0, 0.5);" > What's new ? <br>
		Les invitations ont été améliorées (ajout d'un habillage sonore et d'une alerte visuelle) 
		<!--<span style="color:red">Une nouvelle mise à jour importante vient d'être mise en place sur le jeu :</span><br>
				
																  <br> - Point majeur (non visible) : les différents modules se rechargent maintenant en interne. 
																  		 La page du joueur n'est plus jamais rechargée entièrement, ce qui procure une meilleure expérience de jeu (écriture des messages par exemple)
																  <br> - La 12e carte des séries centrales est maintenant affichée 1 à 2 secondes avant d'être retirée
																  <br> - Nouveau son de motification (bien connu) lors de la réception d'un message
																  <br> - Le temps de rafraichissement est passé de 2 secondes à 1 seconde
																  <br> - Animation du sablier lors des phases de jeu des adversaires
																  <br> - Pop-up (fenêtre secondaire) sur la page de jeu pour inviter un ami occupé sur une autre partie à rejoindre la partie actuelle
																  <br> - Enfin, sur la page de connexion, nouveau cadre désignant les joueurs connectés
													<br><br>-->
		</h2>
		<h2 style="color:white; font-weight:bold;" >
		Si vous souhaitez signaler la moindre incohérence graphique, incompatibilité, erreur ou autre, <br>merci d'écrire et d'envoyer un message en cliquant sur <a target="_blank" href='../#contact'>ce lien</a>.<br>
		</h2>
		<div id="seconnecter" class="bouton">SE CONNECTER</div>
		<div id="sinscrire" class="bouton">S'INSCRIRE</div>	
	</div>
	<div id="col3" hidden>		
		<input type="hidden" id="ok" name="ok" value="<?php if (isset($_POST["ok"])) echo 1; else echo 0; ?>" />		
		<input type="hidden" id="maj" value="" />
		<div id="div_inscription" >
		<label class="connexion">Votre pseudo : <input id="pseudo" name="pseudo" type="text" placeholder="Tapez votre pseudo..." /></label>
		<br><label for='mdp' id='label_mdp' class="connexion">Votre mot de passe : </label><input id="mdp" name="mdp" type="password" placeholder="Tapez votre mot de passe..." />
		<br><label class="connexion"><input type='checkbox' onchange='if (document.getElementById("mdp").type=="password") document.getElementById("mdp").type="text"; else document.getElementById("mdp").type="password";'>Afficher le mot de passe </label>						
		<br><label class="connexion" id="label_oubli" ><input type='checkbox' id="oubli">Mot de passe oublié</label>	
		<div hidden id="fin_inscription">		
			<label id="label_question" class="connexion">Votre question secrète : <input id="question" name="question" type="text" placeholder="Tapez votre question..." /></label>
			<br><label id="label_reponse" class="connexion">Votre réponse à la question secrète : <input id="reponse" name="reponse" type="text" placeholder="Tapez la réponse ..." /></label>	
			<br><label id="label_mail" class="connexion">Votre email : <input id="mail" name="mail" type="email" placeholder="Tapez votre email..." /></label>
			<br><label id="label_prenom" class="connexion">Votre prénom : <input id="prenom" name="prenom" type="text" placeholder="Tapez votre prénom..." /></label>
			<br><label id="label_nom" class="connexion">Votre nom de famille : <input id="nom" name="nom" type="text" placeholder="Tapez votre nom..." /></label>
			<div id="envoi_code" class="bouton">Soumettre l'inscription</div>
		</div>
		</div>
		<div id="fin_connexion">
			<div id="connexion" class="bouton">Se connecter</div>
					</div>
		<div hidden id="div_connexion_oubli">
			<div id="connexion_oubli" class="bouton">Se connecter</div>
		</div>
		<div><p  id='message' style='font-size:18pt; color:red; font-weight:bold;'></p></div>
		</div>
		<div hidden id="div_code">		
		<form id="myForm" action="preparation.php" method="post" >
		<br><label id="label_code" class="connexion">Votre code : <input id="code" name="code" maxlength="15" type="text" placeholder="Tapez le code reçu par mail..." /></label>
		<div id="valider_inscription" class="bouton">Valider l'inscription</div>	
		</div>
		<div hidden id="niveau" >		
		
		<label>Nombre de joueurs :
		<SELECT required name="nbjoueurs" id="nbjoueurs" size="1">
		<OPTION value="2">2
		<OPTION value="3">3
		<OPTION value="4">4
		<OPTION value="5">5
		<OPTION value="6">6
		</SELECT> </label><br>
		<?php
		$query = "SELECT pseudo FROM utilisateurs";
		$resultat = $db->query($query);
		$affich= "";
		while($donnees = $resultat->fetch_object()){
			if ($donnees->pseudo!=$_SESSION['pseudo']){
				if ($donnees->pseudo!="IA 2" && $donnees->pseudo!="IA 3" && $donnees->pseudo!="IA 4" && $donnees->pseudo!="IA 5" && $donnees->pseudo!="IA 6")
					$affich.= "<option class='pseudos' value='".$donnees->pseudo."'>".$donnees->pseudo."</option>";
			}
		}
		echo "<datalist id='datalist' >".$affich."</datalist>";
		for($i=2;$i<7;$i++){
			if ($i!=2)
				$hidden="hidden";
			else 
				$hidden="";				
			echo "<div $hidden id='div$i'>Joueur $i : 
			<label id='label_ia$i' ><input name='ia$i' id='ia$i' type='radio' value='ia' checked >IA</input></label>
			<label id='label_humain$i' ><input name='ia$i' id='humain$i' type='radio' value='humain'>Humain</input></label></div>";
			echo "<label hidden id='labelj$i'>Pseudo du joueur $i : </label><input id='joueur$i' list='' onkeyup='ecrire(joueur$i);' name='joueur$i' hidden placeholder='Saisir le pseudo ...'>";
			}
		?>		
		<input type="hidden" id="nb" name="nb" value="2" />		
		<input type="hidden" id="mode" name="mode" value="" />
		<div class="bouton"><input class="classIE" style="color:white;" type="button" onmousedown= "nouvelle_partie();" value="Commencer à jouer"/></div>
		</form>		
		</div>
		<div hidden id="encours" style='background-color:white;' >
			<?php			
				function pseudo($db,$id_user){
				if ($id_user!=null)
					return $db->query("SELECT pseudo FROM utilisateurs WHERE id_user=$id_user")->fetch_object()->pseudo;
				else 
					return "";
				}
				if($login->isUserLoggedIn()){
					$mon_tour="";
					$leur_tour="";
					$gagnees="";
					$perdues="";
					$query = "SELECT * FROM parties WHERE joueur1=".$_SESSION["id"]." or joueur2=".$_SESSION["id"]." or joueur3=".$_SESSION["id"]." or joueur4=".$_SESSION["id"]." or joueur5=".$_SESSION["id"]." or joueur6=".$_SESSION["id"];
					$resultat = $db->query($query);
					$compteur_mon_tour=0;
					$compteur_leur_tour=0;
					$compteur_gagnees=0;
					$compteur_perdues=0;
					while($donnees = $resultat->fetch_object()){
						$suite="";
						if (pseudo($db,$donnees->joueur1)!="" && pseudo($db,$donnees->joueur1)!= $_SESSION["pseudo"] )
							$suite.=", ".pseudo($db,$donnees->joueur1);
						if (pseudo($db,$donnees->joueur2)!="" && pseudo($db,$donnees->joueur2)!= $_SESSION["pseudo"])
							$suite.=", ".pseudo($db,$donnees->joueur2);
						if (pseudo($db,$donnees->joueur3)!="" && pseudo($db,$donnees->joueur3)!= $_SESSION["pseudo"])
							$suite.=", ".pseudo($db,$donnees->joueur3);
						if (pseudo($db,$donnees->joueur4)!="" && pseudo($db,$donnees->joueur4)!= $_SESSION["pseudo"])
							$suite.=", ".pseudo($db,$donnees->joueur4);
						if (pseudo($db,$donnees->joueur5)!="" && pseudo($db,$donnees->joueur5)!= $_SESSION["pseudo"])
							$suite.=", ".pseudo($db,$donnees->joueur5);
						if (pseudo($db,$donnees->joueur6)!="" && pseudo($db,$donnees->joueur6)!= $_SESSION["pseudo"])
							$suite.=", ".pseudo($db,$donnees->joueur6);
						if ($donnees->tour == $_SESSION["id"] && $donnees->gagne==0){						
							$compteur_mon_tour+=1;
							$mon_tour.="<label><input type='radio' onclick='restaure($donnees->id_partie);' >$donnees->date_creation : Partie commencée avec Vous".$suite."</label><br>";					
						}else if($donnees->tour != $_SESSION["id"] && $donnees->gagne==0){		
							$compteur_leur_tour+=1;		
							$leur_tour.="<label><input type='radio' onclick='restaure($donnees->id_partie);' >$donnees->date_creation : Partie commencée avec Vous".$suite."</label><br>";
						}else if($donnees->gagne==$_SESSION["id"]){
							$compteur_gagnees+=1;		
							$gagnees.="<label><input type='radio' onclick='restaure($donnees->id_partie);' >$donnees->date_creation : Partie commencée avec Vous".$suite."</label><br>";
						}else{
							$compteur_perdues+=1;		
							$perdues.="<label><input type='radio' onclick='restaure($donnees->id_partie);' >$donnees->date_creation : Partie commencée avec Vous".$suite."</label><br>";
						}
					}		
					echo "<h2>Votre tour :</h2>";
					if ($mon_tour =="")
						echo "Il n'y a aucune partie en cours en attente d'une action de votre part.";
					else{
						echo $mon_tour;
					}
					echo "<h2>Leur tour :</h2>";
					if ($leur_tour =="")
						echo "Il n'y a aucune partie en cours en attente d'une action d'adversaires.";
					else{
						echo $leur_tour;
					}
					echo "<h2>Parties gagnées :</h2>";
					if ($gagnees =="")
						echo "Vous n'avez gagné aucune partie dans les dernières 24 heures.";
					else{
						echo $gagnees;
					}
					echo "<h2>Parties perdues :</h2>";
					if ($perdues =="")
						echo "Vous n'avez perdu aucune partie dans les dernières 24 heures.";
					else{
						echo $perdues;
					}
					echo "<form action='/skipb/' id='myFormRestaure' method='GET'>
						<input type='hidden' id='id_partie_restaure' name='id_partie' value='' />
						</form>";								
					echo "<input type='hidden' id='compteur_mon_tour' value=".$compteur_mon_tour." />
					<input type='hidden' id='compteur_leur_tour' value=".$compteur_leur_tour." />
					<input type='hidden' id='compteur_gagnees' value=".$compteur_gagnees." />
					<input type='hidden' id='compteur_perdues' value=".$compteur_perdues." />
					<input type='hidden' id='id_user' value=".$_SESSION['id']." />";
					echo "<form action='connexion.php' id='myFormRecharge' method='POST'>
						<input type='hidden' id='recharge' name='recharge' value='0' />
						</form>";	
				}
		echo "</div>
		</form>
		</div>";
		echo "<h2 style='border:solid 2pt red; background-color:#F0FFFF; position:absolute; top:0; left: 50%; transform: translate(-50%); ' class='accueil' id='consigne' hidden><span>Lors de la saisie des pseudos,<br>attention à bien respecter<br> les majuscules et minuscules<br> et si le pseudo existe,<br> il deviendra vert.</span></h2>";
		
			}
			?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="connexion.js"></script>
	<script type="text/javascript" >	
	//location.reload(true);
	function redirection(){
			if (document.getElementById("recharge")!=null){
				if (document.getElementById("recharge").value=="1" ){
					fonction_recharge();
				}
			}
			setTimeout(redirection, 5000);
	}
	setTimeout(redirection, 5000); 
	<?php if (isset($_POST["recharge"])){
				if($_POST["recharge"]==1){
					echo "affich_restauration();";
				}
			}
		  if (isset($moi)){ if($moi->demo==true && $moi->admin==false){ ?>
	function nouvelle_partie(){
		alert("Vous êtes arrivé à la fin de votre démonstration. Vous recevrez un mail dans les 24 heures vous informant de l'acceptation ou du refus de votre inscription."); 
	}
	<?php 	}else{ ?>
	function ecrire(id){
		if (document.getElementById(id.id).value.length>=3)
			$(id).attr("list","datalist");	
		else 
			$(id).attr("list",""); 
		if (id.value!=""){		
		texte=id.value;
			i=0;
			var tout = document.getElementsByClassName('pseudos');
			ok=0;
			while ( i < tout.length && ok==0){
				if (tout[i].value==id.value){
					$(id).attr('style','color:green');
					ok=1;
					return(1);
				}				
				i+=1;
			}
			if (ok==0){				
				$(id).attr('style','color:red');
				return(0);
			}
		}
	}
	function nouvelle_partie(){
		fin=0;
		//if (document.getElementById("joueur6").required){	
			if ((document.getElementById("joueur2").value==document.getElementById("joueur3").value && document.getElementById("joueur2").required==true && document.getElementById("joueur3").required==true) || 
			(document.getElementById("joueur2").value==document.getElementById("joueur4").value && document.getElementById("joueur2").required==true && document.getElementById("joueur4").required==true) || 
			(document.getElementById("joueur2").value==document.getElementById("joueur5").value && document.getElementById("joueur2").required==true && document.getElementById("joueur5").required==true) || 
			(document.getElementById("joueur2").value==document.getElementById("joueur6").value && document.getElementById("joueur2").required==true && document.getElementById("joueur6").required==true) || 
			(document.getElementById("joueur3").value==document.getElementById("joueur4").value && document.getElementById("joueur3").required==true && document.getElementById("joueur4").required==true) || 
			(document.getElementById("joueur3").value==document.getElementById("joueur5").value && document.getElementById("joueur3").required==true && document.getElementById("joueur5").required==true) || 
			(document.getElementById("joueur3").value==document.getElementById("joueur6").value && document.getElementById("joueur3").required==true && document.getElementById("joueur6").required==true) || 
			(document.getElementById("joueur4").value==document.getElementById("joueur5").value && document.getElementById("joueur4").required==true && document.getElementById("joueur5").required==true) || 
			(document.getElementById("joueur4").value==document.getElementById("joueur6").value && document.getElementById("joueur4").required==true && document.getElementById("joueur6").required==true) || 
			(document.getElementById("joueur5").value==document.getElementById("joueur6").value && document.getElementById("joueur5").required==true && document.getElementById("joueur6").required==true)){
				alert('Vous avez inscrit au moins 2 fois la même personne !');
				fin=1;
			}
		//}
		if (fin==0){
			if (document.getElementById("joueur2").required==true){
				if (ecrire(document.getElementById("joueur2"))==0){		
					alert('Le pseudo du joueur 2 est inconnu : il doit être vert !');
					fin=1;
				}
			}
			if (document.getElementById("joueur3").required==true){
				if (ecrire(document.getElementById("joueur3"))==0){
					alert('Le pseudo du joueur 3 est inconnu : il doit être vert !');
					fin=1;
				}
			}
			if (document.getElementById("joueur4").required==true){
				if (ecrire(document.getElementById("joueur4"))==0){
					alert('Le pseudo du joueur 4 est inconnu : il doit être vert !');
					fin=1;
				}
			}
			if (document.getElementById("joueur5").required==true){
				if (ecrire(document.getElementById("joueur5"))==0){
					alert('Le pseudo du joueur 5 est inconnu : il doit être vert !');
					fin=1;
				}
			}
			if (document.getElementById("joueur6").required==true){
				if (ecrire(document.getElementById("joueur6"))==0){
					alert('Le pseudo du joueur 6 est inconnu : il doit être vert !');
					fin=1;
				}
			}
		}
				
		if (fin==0){
		
			if (document.getElementById("ia2").checked && document.getElementById("div2").hidden==false)
				document.getElementById("joueur2").value="IA 2";
			if (document.getElementById("ia3").checked  && document.getElementById("div3").hidden==false)
				document.getElementById("joueur3").value="IA 3";
			if (document.getElementById("ia4").checked  && document.getElementById("div4").hidden==false)
				document.getElementById("joueur4").value="IA 4";
			if (document.getElementById("ia5").checked  && document.getElementById("div5").hidden==false)
				document.getElementById("joueur5").value="IA 5";
			if (document.getElementById("ia6").checked  && document.getElementById("div6").hidden==false)
				document.getElementById("joueur6").value="IA 6";
			/*	
			if (document.getElementById("nbjoueurs").value==5){
			document.getElementById("joueur6").value="0";			
			}else if (document.getElementById("nbjoueurs").value==4){
			document.getElementById("joueur6").value="0";
			document.getElementById("joueur5").value="0";
			}else if (document.getElementById("nbjoueurs").value==3){
			document.getElementById("joueur6").value="0";
			document.getElementById("joueur5").value="0";
			document.getElementById("joueur4").value="0";
			}else if (document.getElementById("nbjoueurs").value==2){
			document.getElementById("joueur6").value="0";
			document.getElementById("joueur5").value="0";
			document.getElementById("joueur4").value="0";
			document.getElementById("joueur3").value="0";
			}*/
		}
		if (fin==0)
			myForm.submit();
	}
	function message_pseudos(){
		if (document.getElementById("humain2").checked || document.getElementById("humain3").checked || document.getElementById("humain4").checked || document.getElementById("humain5").checked || document.getElementById("humain6").checked )
			document.getElementById("consigne").hidden=false;
		else 
			document.getElementById("consigne").hidden=true;
	}
	document.getElementById("ia2").onchange = function (){			
		message_pseudos();
		document.getElementById("joueur2").hidden=true;
		document.getElementById("labelj2").hidden=true;		
		document.getElementById("joueur2").required=false;
	}	
	document.getElementById("humain2").onchange = function (){		
		message_pseudos();
		document.getElementById("joueur2").hidden=false;
		document.getElementById("labelj2").hidden=false;		
		document.getElementById("joueur2").required=true;
	}
	document.getElementById("ia3").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur3").hidden=true;
		document.getElementById("labelj3").hidden=true;		
		document.getElementById("joueur3").required=false;
	}	
	document.getElementById("humain3").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur3").hidden=false;
		document.getElementById("labelj3").hidden=false;		
		document.getElementById("joueur3").required=true;
	}	
	document.getElementById("ia4").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur4").hidden=true;
		document.getElementById("labelj4").hidden=true;		
		document.getElementById("joueur4").required=false;
	}	
	document.getElementById("humain4").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur4").hidden=false;
		document.getElementById("labelj4").hidden=false;		
		document.getElementById("joueur4").required=true;
	}
	document.getElementById("ia5").onchange = function (){	
		message_pseudos();		
		document.getElementById("joueur5").hidden=true;
		document.getElementById("labelj5").hidden=true;		
		document.getElementById("joueur5").required=false;
	}	
	document.getElementById("humain5").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur5").hidden=false;
		document.getElementById("labelj5").hidden=false;		
		document.getElementById("joueur5").required=true;
	}
	document.getElementById("ia6").onchange = function (){			
		message_pseudos();
		document.getElementById("joueur6").hidden=true;
		document.getElementById("labelj6").hidden=true;		
		document.getElementById("joueur6").required=false;
	}	
	document.getElementById("humain6").onchange = function (){		
		message_pseudos();	
		document.getElementById("joueur6").hidden=false;
		document.getElementById("labelj6").hidden=false;		
		document.getElementById("joueur6").required=true;
	}
	document.getElementById("nbjoueurs").onchange = function (){
		if(document.getElementById("nbjoueurs").value=="2"){
			document.getElementById("nb").value="2";
			document.getElementById("div2").hidden=false;
			document.getElementById("div3").hidden=true;
			document.getElementById("div4").hidden=true;
			document.getElementById("div5").hidden=true;
			document.getElementById("div6").hidden=true;			
		}else if(document.getElementById("nbjoueurs").value=="3"){
			document.getElementById("nb").value="3";
			document.getElementById("div2").hidden=false;
			document.getElementById("div3").hidden=false;
			document.getElementById("div4").hidden=true;
			document.getElementById("div5").hidden=true;
			document.getElementById("div6").hidden=true;	
		}else if(document.getElementById("nbjoueurs").value=="4"){			
			document.getElementById("nb").value="4";
			document.getElementById("div2").hidden=false;
			document.getElementById("div3").hidden=false;
			document.getElementById("div4").hidden=false;
			document.getElementById("div5").hidden=true;
			document.getElementById("div6").hidden=true;	
		}else if(document.getElementById("nbjoueurs").value=="5"){
			document.getElementById("nb").value="5";
			document.getElementById("div2").hidden=false;
			document.getElementById("div3").hidden=false;
			document.getElementById("div4").hidden=false;
			document.getElementById("div5").hidden=false;
			document.getElementById("div6").hidden=true;
		}
		else if(document.getElementById("nbjoueurs").value=="6"){			
			document.getElementById("nb").value="6";
			document.getElementById("div2").hidden=false;
			document.getElementById("div3").hidden=false;
			document.getElementById("div4").hidden=false;
			document.getElementById("div5").hidden=false;
			document.getElementById("div6").hidden=false;
		}
	}
	
	<?php }} ?>
	function restaure(id){
		document.getElementById("id_partie_restaure").value=id;
		document.getElementById("myFormRestaure").submit();
	}
	function affich_restauration(){
		document.getElementById("col1").hidden=true;
		document.getElementById("encours").hidden=false;
		document.getElementById("recharge").value=1;
		
	}
	</script>
	</body>
</html>
