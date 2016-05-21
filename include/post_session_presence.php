<?php
//////
/*
Fichier POST du formulaire de validation des présences aux sessions
Ajout et modifications 2015

//

$statusarray=array(
	0=>"Atelier En cours",
	1=>"En programmation",
	2=>"Atelier Annulé / Annuler",
	3=>"Supprimer"
);
*/
$act=$_GET["act"];

if(isset($_POST['present_'])) 
	{
		//retrouver les valeurs à insérer
		$idsession=$_POST['idsession'];
		$iddate=$_POST["dateid"];
		$nbre_dates=$_POST["nbre_dates"];
		$numerodate=$_POST["num"];
		$anim=$_POST["anim"];
		$categorie=$_POST['categorie'];
		$nom_session= $_POST["nom_session"];
		$date_session=$_POST["date_session"];	
		
		$attente=$_POST["attente"];
		$nombre_present= count($_POST['present_']);
		$nombre_inscrit=$_POST["inscrits"];
		$nbplace=$_POST["nbplace"];
		//$nbre_origin=getSessionNbreDates($idsession); //nombre initial entrée lors de la premiere creation
				
	if($act==0){ //depuis le formulaire, la première fois
		//cumuler la liste des présents
		for ($i = 0; $i < $nombre_present; $i++) {
			$ids_presents=$ids_presents.$_POST['present_'][$i].";";
		}
		//decompter les incriptions des présents // modifier le statut : 0= par defaut inscrit; 2=en attente; 1=presence validée; 3=atelier annule
		// modifier et incrementer le forfait en cours s'il y a lieu
		for($x=0;$x < $nombre_present;$x++){
				ModifyUser1Session($idsession,$_POST['present_'][$x],1,$iddate);
				 //test du forfait de l'adherent
				$depense=getForfaitUserEncours($_POST['present_'][$x]);
				if($depense!=FALSE){
				 if($depense["depense"]+1 ==$depense["total_atelier"]){
					clotureforfaitUser($depense["total_atelier"],$depense["id_forfait"]);
				}else{
					updateForfaitdepense($depense["id_forfait"]);
				}
				}
			}
			//modifier le statut de la date de la session
			updateDatesessionStatut($iddate);
		//inscription dans la stats
		$absents=$nombre_inscrit-$nombre_present;
		InsertStatAS('s',$idsession,$date_session,$nombre_inscrit,$nombre_present,$absents,$attente,$nbplace,$categorie,1,$anim,$_SESSION["idepn"]);
		
		//en cas de session cloturée toutes dates finies changer son statut --> archives !
			$nbrvalides=getDatesValidesbysession($idsession);
			$nbreannule=getDatesAnnulebysession($idsession);
			if(($nbrvalides+$nbreannule)==$nbre_dates){ 
				updateSessionStatut($idsession);}
			
			
		//redirection
		header("Location:index.php?a=30&b=1&idsession=".$idsession) ;
		
		} else{
		//venue des archives modification
			//charger la liste des inscrits
		$archivarr=getSessionValidpresences($idsession,$iddate);
		$nbarchiv=mysqli_num_rows($archivarr);
		for ($x = 0; $x < $nbarchiv; $x++) {
			$archiv=mysqli_fetch_array($archivarr);
			$iduser=$archiv['id_user'];
			$statutuser=$archiv["status_rel_session"];
		//Cas 1: un adhérent est absent à l'origine, mais présent de fait
			if(in_array($iduser,$_POST['present_'])==TRUE){
				if($statutuser==0){
					ModifyUser1Session($idsession,$iduser,1,$iddate);
					//rajouter au forfait
					$depense=getForfaitUserEncours($iduser);
						 if($depense["depense"]+1 ==$depense["total_atelier"]){
							clotureforfaitUser($depense["total_atelier"],$depense["id_forfait"]);
						}else{
							updateForfaitdepense($depense["id_forfait"]);
						}
						
				}
		
			}else{
			//cas 2 ; un adhérent est présent à l'origine, mais en fait absent
				if ($statutuser==1){
					//retirer de la liste des présents
					ModifyUser1Session($idsession,$iduser,0,$iddate);
					//enlever 1 atelier au compte du forfait
					$depense=getForfaitUserEncours($iduser);
					DeleteOneFromForfait($depense["id_forfait"],$iduser);
				}
			}
				
			 }
			//inscription dans la stats
		$absents=$nombre_inscrit-$nombre_present;
		ModifStatAS($nombre_inscrit,$nombre_present,$absents,$idsession,'s');
		
			header("Location:index.php?a=36&mesno=14") ;
		}
		
	}
?>