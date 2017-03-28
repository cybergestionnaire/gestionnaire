<?php

include("../connect_db.php");
// 



$separator = ";";
    header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=liste-newsletter.csv");


//liste des adherents a exporter

$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `nom_user`, `prenom_user`, `mail_user`, `epn_user` FROM `tab_user` WHERE `newsletter_user`=1";
$userrow=mysqli_query($db, $sql);
mysqli_close ($db) ;


//********

//Ligne d'entête
$csv_output = "Liste des adhérents inscrits à la newsletter";
$csv_output .= "\n";
$csv_output .= "Nom".$separator."Prénom". $separator."Courriel";
$csv_output .= "\n";


//affichage des adherents avec mail et telephone
if($userrow!=FALSE){

	while ($row = mysqli_fetch_array($userrow)) {
		
		$csv_output .= $row['nom_user'].$separator.$row['prenom_user'].$separator.$row['mail_user'];
		$csv_output .= "\n";
		
	}
	
} else {
	
	$csv_output.="Pas d'adhérents inscrits a la newsletter";
}

	print $csv_output ;
	
	exit();
		
	    		
?>