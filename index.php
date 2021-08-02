<!DOCTYPE html>
<html lang="fr">
	<head>
	<title>Jeu de cartes</title>
	<meta charset="UTF-8" />
	</head>
<style>
/*html{
background-image:url(fond.jpg);
background-size: 100% 100%;
min-height: 100%;
}*/
#div_j5 img{
margin:-20px auto -25px 20px;
}
#div_j6 img{
margin: -20px 20px -25px;
}
img{
width:80px;
}
.j5{
transform: rotate(90deg);
}
.j6{
transform: rotate(-90deg);
}
.stock2{
margin-left:30px;
}
.grandes{
width:120px;
}
#fieldset1{
width:100px;
float:left;
border: solid black 2pt;
margin-right:50px;
}
#fieldset2{
width:500px;
border: solid black 2pt;
}
</style>
<?php 
if (!isset($_GET['id_partie'])){
	header("Location: connexion.php");
}
?>
<body>
<?php
echo "<input type='hidden' id='id_partie' value='".$_GET['id_partie']."' />"; 
?>
</body>	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type='text/javascript'>
	old="";	
	degres=0;
	animation_sablier();
	function animation_sablier(){
		if (document.getElementById("panneau")!=null){			
			if (document.getElementById("panneau").alt=="pas_votre_tour"){
				document.getElementById("panneau").style=' margin:0 auto; text-align:center; float: right; width:75px; margin-right:45px;';
				document.getElementById("panneau").style.transform='rotate('+degres+'deg)';
				degres+=5;
			}else{
				degres=0;
				document.getElementById("panneau").style.transform='rotate(0deg)';
				document.getElementById("panneau").style=' margin:0 auto; text-align:center; float: right; width:150px;';
			}
		}
		setTimeout(animation_sablier, 50); 
	}
	function actualisation_var(){
		id_partie=document.getElementById("id_partie").value;
		if (document.getElementById("1ere_actu") != null) {
			session_id=document.getElementById("session_id").value;
			tour=document.getElementById("tour").value;
			if ((tour==7 || tour==8 || tour==9 || tour==10 || tour==11) && parseInt(document.getElementById("test_IA").value) != 0){
				document.getElementById("test_IA").value=0
				//setTimeout(envoi_ia, 1000);
				envoi_ia();
			}else if (tour!=7 && tour!=8 && tour!=9 && tour!=10 && tour!=11 && parseInt(document.getElementById("test_IA").value) == 0)
				document.getElementById("test_IA").value=1
			gagne=document.getElementById("gagne").value;
		}
		setTimeout(actualisation_var, 100); 
	}
	function visio(){
		jQuery(document).ready(function($) {		
			var data = 'visio='+session_id+'&id_partie='+id_partie;
			$.ajax({
			  type: "GET",
			  url: "jeu.php",
			  data: data,
			  success: function(msg) {	
						//document.location.reload(true);
					}
			})
		})
	}
	actualisation_var();
	reception();
	function reception(){
		jQuery(document).ready(function($) {		
				var data = 'id_partie='+id_partie;
			$.ajax({
			  type: "GET",
			  url: "principale.php",
			  data: data,
			  success: function(msg) {	
							if (msg=="connexion"){
								document.location.href="connexion.php";
							}
							if (document.getElementById("1ere_actu") == null) {
								document.getElementsByTagName("body")[0].innerHTML=msg;
								var doc = document.implementation.createHTMLDocument("Nouveau Document");
								var p = doc.createElement("html");
								p.innerHTML = msg;
								doc.body.appendChild(p);
								document.getElementsByTagName("body")[0].style["background-color"]=doc.getElementsByTagName("body")[1].style["background-color"];
								document.getElementsByTagName("body")[0].style.color=doc.getElementsByTagName("body")[1].style.color;
							}else{									
								if (document.getElementsByTagName("body")[0].innerHTML != msg){
									var doc = document.implementation.createHTMLDocument("Nouveau Document");
									var p = doc.createElement("html");
									p.innerHTML = msg;
									doc.body.appendChild(p);
									recharge_maintenant=1;
									document.getElementsByTagName("body")[0].style["background-color"]=doc.getElementsByTagName("body")[1].style["background-color"];
									document.getElementsByTagName("body")[0].style.color=doc.getElementsByTagName("body")[1].style.color;
									if (document.getElementById("div_main").innerHTML!=doc.getElementById("div_main").innerHTML){
										for (i=1;i<=5;i++){
											num_carte="carte"+i;
											if (document.getElementById(num_carte)!=null){
												if (document.getElementById(num_carte).style.border=='2pt solid red')
													recharge_maintenant=0;
											}
										}
										if (recharge_maintenant==1)
											document.getElementById("div_main").innerHTML=doc.getElementById("div_main").innerHTML;										
									}
									if (document.getElementById("div_j2").innerHTML!=doc.getElementById("div_j2").innerHTML){
										document.getElementById("div_j2").innerHTML=doc.getElementById("div_j2").innerHTML;
									}
									if (document.getElementById("div_j3")!=null){
										if (document.getElementById("div_j3").innerHTML!=doc.getElementById("div_j3").innerHTML)
											document.getElementById("div_j3").innerHTML=doc.getElementById("div_j3").innerHTML;
									}
									if (document.getElementById("div_j4")!=null){
										if (document.getElementById("div_j4").innerHTML!=doc.getElementById("div_j4").innerHTML)
											document.getElementById("div_j4").innerHTML=doc.getElementById("div_j4").innerHTML;
									}
									if (document.getElementById("div_j5")!=null){
										if (document.getElementById("div_j5").innerHTML!=doc.getElementById("div_j5").innerHTML)
											document.getElementById("div_j5").innerHTML=doc.getElementById("div_j5").innerHTML;
									}
									if (document.getElementById("div_j6")!=null){
										if (document.getElementById("div_j6").innerHTML!=doc.getElementById("div_j6").innerHTML)
											document.getElementById("div_j6").innerHTML=doc.getElementById("div_j6").innerHTML;
									}
									if (document.getElementById("div_central").innerHTML!=doc.getElementById("div_central").innerHTML){
										document.getElementById("div_central").innerHTML=doc.getElementById("div_central").innerHTML;
									}									
									recharge_maintenant=1;
									if (document.getElementById("piles_defausses").innerHTML!=doc.getElementById("piles_defausses").innerHTML){
										for (i=1;i<=4;i++){
											num_carte="defausse"+i;
											if (document.getElementById(num_carte)!=null){
												if (document.getElementById(num_carte).style.border=='2pt solid red' || document.getElementById("carte_stock").style.border=='2pt solid red')
													recharge_maintenant=0;			
											}
										}
										if (recharge_maintenant==1)
											document.getElementById("piles_defausses").innerHTML=doc.getElementById("piles_defausses").innerHTML;
									}
									

									if (document.getElementById("indic_tour").innerHTML!=doc.getElementById("indic_tour").innerHTML){
										document.getElementById("indic_tour").innerHTML=doc.getElementById("indic_tour").innerHTML;
										document.getElementById("panneau").src=doc.getElementById("panneau").src;
										document.getElementById("panneau").alt=doc.getElementById("panneau").alt;
										document.getElementById("tour").value=doc.getElementById("tour").value;
										document.getElementById("gagne").value=doc.getElementById("gagne").value;

										if (document.getElementById("indic_tour").innerHTML=="A vous de jouer !")
											new Audio('notif.mp3').play();
									}
									
									
									document.getElementById("fieldset1").style.border=doc.getElementById("fieldset1").style.border;
									document.getElementById("fieldset2").style.border=doc.getElementById("fieldset2").style.border;
									document.getElementById("mode_nuit").style.border=doc.getElementById("mode_nuit").style.border;
									document.getElementById("mode_nuit").innerHTML=doc.getElementById("mode_nuit").innerHTML;

									document.getElementById("message").style.color=doc.getElementById("message").style.color;
									document.getElementById("fil_messages").style.color=doc.getElementById("fil_messages").style.color;
									if (document.getElementById("fil_messages").innerHTML!=doc.getElementById("fil_messages").innerHTML){
										document.getElementById("fil_messages").innerHTML=doc.getElementById("fil_messages").innerHTML;
										if (doc.getElementById("fil_messages").name != null){
											if (doc.getElementById("fil_messages").name == "audio_msg"){
												document.getElementById("fil_messages").name = "audio_msg_ok";
												new Audio('msg.mp3').play();
											}
										}
									}
									if(document.getElementById("invite").innerHTML!=doc.getElementById("invite").innerHTML || document.getElementById("invite").style["background-color"]!= doc.getElementById("invite").style["background-color"]){
										document.getElementById("invite").innerHTML=doc.getElementById("invite").innerHTML;
										document.getElementById("invite").style["background-color"]=doc.getElementById("invite").style["background-color"];	
										if (doc.getElementById("invite").innerHTML != " "){
											new Audio('invitation.mp3').play();
											setTimeout(alert_invitation, 1000);
										}										
											
										
									}
									if (document.getElementById("invitation").innerHTML!=doc.getElementById("invitation").innerHTML  || document.getElementById("invitation").style["background-color"]!= doc.getElementById("invitation").style["background-color"]){
										document.getElementById("invitation").innerHTML=doc.getElementById("invitation").innerHTML;
										document.getElementById("invitation").style["background-color"]=doc.getElementById("invitation").style["background-color"];	
									}

									
									if (document.getElementById("bouton_visio")!=null){										
										if (document.getElementById("bouton_visio").innerHTML!=doc.getElementById("bouton_visio").innerHTML){
											document.getElementById("bouton_visio").innerHTML=doc.getElementById("bouton_visio").innerHTML;
										}
									}
								}
							}
						}
			})
		})
	}
	function demande_reception(){		
		setTimeout(reception, 1000); 
		setTimeout(degage, 2000);
		setTimeout(demande_reception, 1000); 
	}
	function alert_invitation(){
		alert("Vous avez reçu une invitation !");
	}
	demande_reception();

	function invitation(id_partie_invite,id_joueur_invite,id_joueur,id_partie){
		jQuery(document).ready(function($) {	
				if (id_joueur_invite==-1 ||id_joueur_invite==-2)
					var data = 'id_partie='+id_partie_invite+'&ok=1';
				else
					var data = 'id_partie_invite='+id_partie_invite+'&id_joueur_invite='+id_joueur_invite+'&id_joueur='+id_joueur+'&id_partie='+id_partie;
			$.ajax({
			  type: "POST",
			  url: "invitation.php",
			  data: data,
			  success: function(msg) {
							if (id_joueur_invite==-1)
								document.location.href="?id_partie="+id_partie;
			  			}
			})
		})
	}	
	function envoi_ia(){		
		if ((tour==7 || tour==8 || tour==9 || tour==10 || tour==11) && (session_id!=7 && session_id!=8 && session_id!=9 && session_id!=10 && session_id!=11)){		
			var data = 'id_partie='+id_partie+"&IA=1";
			$.ajax({
			type: "GET",
			url: "principale.php",
			data: data,
			success: function(msg) {			
						reception();
						setTimeout(envoi_ia, 1000); 
					}
			})
		}
	}
	function nuit(id,val){		
		var data = 'id_user='+id+"&nuit="+val;
		$.ajax({
		type: "GET",
		url: "jeu.php",
		data: data,
		success: function(msg) {	
					reception();		
				}
		})
	}
	function envoi_message(){
		jQuery(document).ready(function($) {		
				var data = 'id_partie='+id_partie+'&id_joueur='+session_id+'&message='+encodeURIComponent(message.value);
			$.ajax({
			  type: "GET",
			  url: "jeu.php",
			  data: data,
			  success: function(msg) {	
				  		document.getElementById("message").value="";
						reception();
						//document.location.reload(true);
					}
			})
		})
	}	
	function degage(){
		jQuery(document).ready(function($) {		
				var data = 'id_partie='+id_partie;
			$.ajax({
			  type: "GET",
			  url: "retire_tas.php",
			  data: data,
			  success: function(msg) {	
							if (msg=="connexion"){
								document.location.href="connexion.php";
							}
					}
			})
		})
	}	
	function action(idimg,old){				
		if (tour==session_id && parseInt(gagne)==0 ){	
			if (old!=""){			
				class_idimg=document.getElementById(idimg).className;
				class_old=document.getElementById(old).className;
				if (class_idimg.indexOf("centrale")!=-1  && class_old.indexOf("main")!=-1){
					if (parseInt(document.getElementById(idimg).alt)+1==parseInt(document.getElementById(old).alt) || document.getElementById(old).alt=="13")
						envoi(idimg,old);
					else 
						alert("Action non autorisée !");
				}else if (class_idimg.indexOf("centrale")!=-1  && class_old.indexOf("defausse")!=-1){
					if (parseInt(document.getElementById(idimg).alt)+1==parseInt(document.getElementById(old).alt) || document.getElementById(old).alt=="13")
						envoi(idimg,old);
					else 
						alert("Action non autorisée !");
				}else if (class_idimg.indexOf("centrale")!=-1 && class_old.indexOf("stock")!=-1){
					if (parseInt(document.getElementById(idimg).alt)+1==parseInt(document.getElementById(old).alt) || document.getElementById(old).alt=="13")
						envoi(idimg,old);
					else 
						alert("Action non autorisée !");
				}else if (class_idimg.indexOf("defausse")!=-1 && class_old.indexOf("main")!=-1){
					if (idimg=="defausse1"){
						idimg1="defausse2";
						idimg2="defausse3";
						idimg3="defausse4";					
					}else if (idimg=="defausse2"){
						idimg1="defausse1";
						idimg2="defausse3";
						idimg3="defausse4";					
					}else if (idimg=="defausse3"){
						idimg1="defausse2";
						idimg2="defausse1";
						idimg3="defausse4";					
					}else {
						idimg1="defausse2";
						idimg2="defausse3";
						idimg3="defausse1";					
					}
					if (parseInt(document.getElementById(idimg).alt)==0 && parseInt(document.getElementById(idimg1).alt)==0  && parseInt(document.getElementById(idimg2).alt)==0 && parseInt(document.getElementById(idimg3).alt)==0)
						envoi(idimg,old);
					else if ((parseInt(document.getElementById(idimg).alt)!=0) && (parseInt(document.getElementById(idimg1).alt)==0  || parseInt(document.getElementById(idimg2).alt)==0 || parseInt(document.getElementById(idimg3).alt)==0))
						alert("Action non autorisée : toutes les piles de défausse doivent ête non vides !");
					else 
						envoi(idimg,old);
				}else{
					alert("Action non autorisée !");
				}			
			}
		}
	}
	function bordure(idimg,old){		
		if (tour==session_id && parseInt(gagne)==0 ){
			var tout = document.getElementsByTagName('img');
			for (i=0;i<tout.length;i++){
				if (tout[i].id!=idimg)
					tout[i].style.border="";
				else{
					if (tout[i].style.border=='2pt solid red'){
						tout[i].style.border="";
						return('');
						}
					else{
						tout[i].style.border='solid red 2pt';
						action(idimg,old);
					}
				}
			}
			return(idimg);
		}else if (tour!=session_id && parseInt(gagne)==0 ){		
			alert('Merci d\'attendre votre tour pour jouer');
		}else {
			alert('La partie est terminée !');
		}
	}
	function pioche(){		
		if (tour==session_id && parseInt(gagne)==0 ){
			if (document.getElementById("carte1") == null && 
				document.getElementById("carte2") == null &&
				document.getElementById("carte3") == null &&
				document.getElementById("carte4") == null &&
				document.getElementById("carte5") == null)
				envoi("pioche");
			else 
				alert("Action non autorisée !");
				
		}else if (tour!=session_id && parseInt(gagne)==0 ){		
			alert('Merci d\'attendre votre tour pour jouer');
		}else {
			alert('La partie est terminée !');
		}
	}
	function envoi(idimg,old1){
		jQuery(document).ready(function($) {		
			if (old1!=null){
				var data = idimg+'='+document.getElementById(idimg).alt+'&'+old1+'='+document.getElementById(old1).alt+'&id_partie='+id_partie;
			}else 
				var data = idimg+'&id_partie='+id_partie;
			$.ajax({
			  type: "GET",
			  url: "jeu.php",
			  data: data,
			  success: function(msg) {	
						reception();
						old="";
						var tout = document.getElementsByTagName('img');
						for (i=0;i<tout.length;i++){
							tout[i].style.border="";
						}						
						//document.location.reload(true);
					}
			})
		})
	}

//alert("Alerte de sécurité : Votre PC vient d'être piraté ! Non, je rigole c'est Alexis, on peut faire une partie tous les deux si tu veux");
/*
var mouse_down = false;
var id_square = ""; 
function on_mouse_down_square(event,id) {
	mouse_down=true; 
	id_square = id;
}
function on_mouse_out(event,id){
	if (id_square == id && mouse_down === true) {
		mouse_down=false;		
		test_deplacement(event,id,"cartepioche");	
		test_deplacement(event,id,"carte_stock");	
		for (j=0;j<2;j++){
			for (i=1;i<=4;i++){
				if (j==0)
					emp="centrale";
				else 
					emp="defausse";	
				test_deplacement(event,id,emp+i);
			}
		}
		for (i=1;i<=5;i++)
				test_deplacement(event,id,"carte"+i);
	}
}
function test_deplacement(event,id,emp){
	if (id!=emp){	
		if (document.getElementById(emp)!=null){
			if (event.clientX<= document.getElementById(emp).getBoundingClientRect().right && event.clientX >= document.getElementById(emp).getBoundingClientRect().left &&
			 event.clientY<= document.getElementById(emp).getBoundingClientRect().bottom && event.clientY >= document.getElementById(emp).getBoundingClientRect().top)
				action(emp,id);
		}
	}
}
*/
</script>
</html>