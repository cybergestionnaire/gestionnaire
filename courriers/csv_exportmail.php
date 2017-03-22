<?php

include("../connect_db.php");
// 


$epn=$_GET["epn"];

$separator = ";";
    header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=liste-mail.csv");


//liste des adherents a exporter

$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT DISTINCT tab_user.id_user, `nom_user` ,  `prenom_user`, mail_user, tel_user 
FROM  `rel_atelier_user` , tab_user, tab_atelier
WHERE tab_user.id_user =  `rel_atelier_user`.`id_user`
AND `epn_user`= ".$epn."
AND YEAR(date_atelier)=YEAR(NOW())
UNION 
SELECT DISTINCT tab_user.id_user, `nom_user` ,  `prenom_user` , mail_user, tel_user 
FROM  `rel_session_user` , tab_user,tab_session
WHERE tab_user.id_user =  `rel_session_user`.`id_user` 
AND `epn_user`=".$epn."
AND YEAR(date_session)=YEAR(NOW())
ORDER BY nom_user ASC
 ";
$userrow=mysqli_query($db, $sql);
mysqli_close ($db) ;


//********

//Ligne d'entête
$csv_output = "Liste des adhérents participants aux ateliers/sessions cette année";
$csv_output .= "\n";
$csv_output .= "Nom".$separator."Prénom". $separator."Courriel".$separator."téléphone";
$csv_output .= "\n";


//affichage des adherents avec mail et telephone
if($userrow!=FALSE){

	while ($row = mysqli_fetch_array($userrow)) {
		
		$csv_output .= $row['nom_user'].$separator.$row['prenom_user'].$separator.$row['mail_user'].$separator.$row['tel_user'];
		$csv_output .= "\n";
		
	}
	
} else {
	
	$csv_output.="Pas d'adhérents enregistrés pour les ateliers cette année'";
}

	print $csv_output ;
	
	exit();
		
	    		
?>