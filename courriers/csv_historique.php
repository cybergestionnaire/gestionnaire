<?php
include("../include/fonction.php");
include("../connect_db.php");
// 

$user= $_GET["user"];
$epn=$_GET["epn"];

$separator = ";";
    header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=participation-ateliers-".$user."-".date('Y').".csv");


//importer les infos depuis la base
//données usager
$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `nom_user`,`prenom_user`,`jour_naissance_user`,`mois_naissance_user`,`annee_naissance_user`,`adresse_user`,`tel_user`,`login_user`,`mail_user`,`date_insc_user`,`nom_city`,`code_postale_city`,`pays_city`
FROM `tab_user`,`tab_city`
WHERE `id_user`=".$user."
AND tab_user.ville_user=tab_city.`id_city`
 ";
$userrow=mysqli_query($db, $sql);
mysqli_close ($db) ;
$username=array();

//Les données de l'epn



//**///fonctions
function getUserStatutAS($iduser,$statut,$type,$statutatelier,$host,$userdb,$passdb,$database)
{

	if($type==1){
		$sql="SELECT label_atelier, date_atelier, heure_atelier
		FROM `rel_atelier_user` as rel,tab_atelier as atelier ,tab_atelier_sujet
		WHERE `id_user`=".$iduser." 
		AND `status_rel_atelier_user`=".$statut." 
		AND rel.id_atelier=atelier.id_atelier
		AND atelier.id_sujet=tab_atelier_sujet.id_sujet
		AND statut_atelier=".$statutatelier."
		AND YEAR(date_atelier)='".date('Y')."'
		ORDER BY date_atelier DESC
		";
		
		
	}else if ($type==2){
		$sql="SELECT rel.`id_session` , rel.`id_datesession` , dat.date_session, ses.`status_session`,session_titre
		FROM  `rel_session_user` AS rel, tab_session_dates AS dat, tab_session AS ses , tab_session_sujet AS sujet
		WHERE  `id_user` =".$iduser."
		AND  `status_rel_session` =".$statut."
		AND rel.id_datesession = dat.`id_datesession` 
		AND rel.`id_session` = dat.`id_session` 
		AND rel.`id_session` = ses.`id_session` 
		AND ses.nom_session= sujet.id_session_sujet
		AND ses.status_session =".$statutatelier."
		AND YEAR(dat.`date_session`)='".date('Y')."'
		AND dat.statut_datesession<2
		ORDER BY rel.`id_session` , dat.`date_session` ASC 
		";
	}

	$db = mysqli_connect($host,$userdb,$passdb,$database) ;
	  $result = mysqli_query($db,$sql);
	 mysqli_close ($db) ;
		if($result == FALSE)
		{
			return FALSE;
		} else {
			
			return $result;
		}

}


//********


//liste de tous les ateliers et session où l'dherent est inscrit et presence validée cloturé ==2 pour les ateliers, cloturé=1 pour les sessions !
//rappel getUserStatutAS($iduser,$statut,$type,$statutatelier) where $statut== présence, et $type==atelier ou session
$ListeAtelierPresent=getUserStatutAS($user,1,1,2,$host,$userdb,$passdb,$database); //ateliers passés
$ListeSessionPresent=getUserStatutAS($user,1,2,1,$host,$userdb,$passdb,$database);
$nbpresentatelier=mysqli_num_rows($ListeAtelierPresent);
$nbpresentsession=mysqli_num_rows($ListeSessionPresent);
$nbtotalpresent=$nbpresentatelier+$nbpresentsession;
	

//liste de tous les ateliers et session où l'dherent est inscrit et non validée $statut==0
$ListeAtelierAbsent=getUserStatutAS($user,0,1,2,$host,$userdb,$passdb,$database); 
$ListeSessionAbsent=getUserStatutAS($user,0,2,1,$host,$userdb,$passdb,$database);
$nbabsentatelier=mysqli_num_rows($ListeAtelierAbsent);
$nbabsentsession=mysqli_num_rows($ListeSessionAbsent);
$nbtotalabsent=$nbabsentatelier+$nbabsentsession;



// Ligne entête	
While($username=mysqli_fetch_array($userrow)){
	$csv_output = "Historique de la participation aux ateliers de ".$username['nom_user']." ".$username['prenom_user'];
	$csv_output .= "\n";
	$csv_output .= "Coordonnées".$separator.$username['adresse_user'].$separator.$username['code_postale_city'].$separator.$username['nom_city'].$separator.$username['pays_city'];
	$csv_output .= "\n";
	$csv_output .= "Date de Naissance".$separator.$username['jour_naissance_user']."/ ".$username['mois_naissance_user']."/ ".$username['annee_naissance_user'];
	$csv_output .= "\n";
	$csv_output .= "Téléphone/mail".$separator.$username['tel_user'].$separator.$username['mail_user'];
	$csv_output .= "\n";
	$csv_output .= "Date d'inscription".$separator.$username['date_insc_user'].$separator."login".$separator.$username['login_user'];
	$csv_output .= "\n";
	$csv_output .= "\n";
}


//Ligne d'entête
$csv_output .= "Liste des présences validées :";
$csv_output .= "\n";
$csv_output .= "Titre de l'atelier".$separator."date". $separator."Heure";
$csv_output .= "\n";


//affichage des ateliers label_atelier, date_atelier, heure_atelier

if($nbtotalpresent>0){
	//les ateliers
	while ($row1 = mysqli_fetch_array($ListeAtelierPresent)) {
		
		$csv_output .= $row1['label_atelier'].$separator.getDayfr($row1['date_atelier']).$separator.getTime($row1['heure_atelier']);
		$csv_output .= "\n";
		
	}
	$csv_output .= "\n";

	//les sessions enregistrées
	while ($row2 = mysqli_fetch_array($ListeSessionPresent)) {
		
		$csv_output .= $row2['session_titre'].$separator.getDateFr($row2['date_session']).$separator."";
		$csv_output .= "\n";
		
	}
	
} else {
	
	$csv_output.="Pas de présence enregistrée pour l'adhérent";
}
	 
//Ligne d'entête
$csv_output .= "\n\n";
$csv_output .= "Liste des absences :";
$csv_output .= "\n";
$csv_output .= "Titre de l'atelier".$separator."date". $separator."Heure";
$csv_output .= "\n";
//Affichage des absences
if($nbtotalabsent>0){
	//les ateliers
	while ($row1 = mysqli_fetch_array($ListeAtelierAbsent)) {
		
		$csv_output .= $row1['label_atelier'].$separator.getDayfr($row1['date_atelier']).$separator.getTime($row1['heure_atelier']);
		$csv_output .= "\n";
		
	}
	$csv_output .= "\n";

	//les sessions enregistrées
	while ($row2 = mysqli_fetch_array($ListeSessionAbsent)) {
		
		$csv_output .= $row2['session_titre'].$separator.getDateFr($row2['date_session']).$separator."";
		$csv_output .= "\n";
		
	}
	
} else {
	
	$csv_output.="Pas d'absence enregistrée pour l'adhérent";
}

	 
	 
	 
	 print $csv_output ;
	
	exit();
		
	    		
?>