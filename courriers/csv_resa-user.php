<?php
include("../include/fonction.php");
include("../include/fonction2.php");
include("../connect_db.php");
// 
$date1= $_GET["date1"];
$date2= $_GET["date2"];
$user= $_GET["user"];
$epn=$_GET["epn"];

$separator = ";";
    header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=historique-resa-".$user.".csv");


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

//Les reservations de l'utilisateur
$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `dateresa_resa`,`debut_resa`,`duree_resa`,nom_computer FROM tab_resa 
          INNER JOIN tab_computer ON id_computer=id_computer_resa
          WHERE `id_user_resa`=".$user." 
		AND `dateresa_resa` BETWEEN '".$date1."' AND '".$date2."'
		ORDER BY `dateresa_resa` DESC , `debut_resa` DESC";    
$resarow = mysqli_query($db, $sql);
mysqli_close ($db) ;

$nbresa=mysqli_num_rows($resarow);


// Ligne entête	
While($username=mysqli_fetch_array($userrow)){
	$csv_output = "Historique des réservations de ".$username['nom_user']." ".$username['prenom_user'];
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

$csv_output .= "Liste des réservations entre le ".getDateFr($date1)." et le ".getDateFr($date2);
$csv_output .= "\n";
$csv_output .= "\n";


$csv_output .= "date".$separator."Heure de début". $separator."Heure de fin".$separator."Durée".$separator."Poste";
$csv_output .= "\n";


if($nbresa>0){
	while ($row = mysqli_fetch_array($resarow)) {
		
		$csv_output .= dateFr($row['dateresa_resa']).$separator.getTime($row['debut_resa']).$separator.getTime(($row['debut_resa']+$row['duree_resa'])).$separator.getTime($row['duree_resa']).$separator.$row['nom_computer'];
		$csv_output .= "\n";
		
	}
	
	
	
	
} else {
	
	$csv_output="Pas de données pour la période demandée pour l'adhérent";
}
	 
	 print $csv_output ;
	
	exit();
		
	    		
?>