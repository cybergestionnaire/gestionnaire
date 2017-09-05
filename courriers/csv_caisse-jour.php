<?php
include("../include/fonction.php");
include("../connect_db.php");
// 
$date= $_GET["date"];
$epn=$_GET["epn"];

$separator = ";";

header('Pragma: public'); 
/////////////////////////////////////////////////////////////
// prevent caching....
/////////////////////////////////////////////////////////////
// Date in the past sets the value to already have been expired.
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");    
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1 
header ("Pragma: no-cache"); 
header("Expires: 0"); 
/////////////////////////////////////////////////////////////
// end of prevent caching....
/////////////////////////////////////////////////////////////
header('Content-Transfer-Encoding: none'); 
// This should work for IE & Opera
header('Content-Type: application/vnd.ms-excel;');  
 // This should work for the rest
header("Content-type: application/x-msexcel");     

header("Content-disposition: attachment; filename=caissedujour-".$date.".csv");
	
// Ligne entête	
$csv_output = "Etat de caisse pour la date du ".date('Y-m-d');
$csv_output .= "\n";
$csv_output .= "date".$separator."nom". $separator."prenom".$separator."Tarif".$separator."Nombre".$separator."Encaissé".$separator."Caissier";
$csv_output .= "\n";


 //Les impressions du jour
$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `print_date`,`print_debit`,`donnee_tarif`,`print_credit`, nom_user, prenom_user ,print_userexterne,print_user,print_caissier
FROM tab_print, tab_user , tab_tarifs 
 WHERE DATE(print_date)='".$date."'
AND print_statut=1 
AND tab_print.`print_user`=tab_user.id_user 
AND tab_print.print_tarif=tab_tarifs.`id_tarif` 
AND print_epn=".$epn."
ORDER BY print_date ASC";    
$resultprint = mysqli_query($db, $sql);
mysqli_close ($db) ;

$nbprint=mysqli_num_rows($resultprint);
$total=0;
//*** verification utilisateur externe
$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql1="SELECT `id_user` FROM `tab_user` WHERE `nom_user`='Externe' AND `login_user`='compte_imprim' ";
$resultexterne = mysqli_query($db,$sql1);
mysqli_close ($db) ;
if ($resultexterne==FALSE)
	{
      return FALSE;
	} else {
	$row=mysqli_fetch_array($resultexterne);
    $userexterne=$row['id_user'] ;
	}
	
function getNomCaissier($n,$host,$userdb,$passdb,$database)
{

$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `nom_user`, prenom_user FROM `tab_user` WHERE `id_user`='".$n."' ";
$row= mysqli_query($db,$sql);
mysqli_close ($db) ;
if($row==FALSE){
	return "inconnu";
	} else {
	$result=mysqli_fetch_array($row);
	$nom=$result["prenom_user"]." ".$result["nom_user"];
	
	return $nom;
	}
}




if($nbprint>0){
	while ($row = mysqli_fetch_array($resultprint)) {
		if($userexterne==$row['print_user']){
			if($row['print_userexterne']==NULL){
			$name_user="externe".$separator."Non renseigné";
			} else {
			$name_user="externe".$separator.$row['print_userexterne'];
			}
		} else {
			$name_user=$row['nom_user'].$separator.$row['prenom_user'];
		}
		
		if($row['print_caissier']==NULL){
			$caissier="Non renseigné";
		} else {
			$caissier=getNomCaissier($row['print_caissier'],$host,$userdb,$passdb,$database);
		}
		
		$csv_output .= $row['print_date'].$separator.$name_user.$separator.$row['donnee_tarif'].$separator.$row['print_debit'].$separator.$row['print_credit'].$separator.$caissier;
		$csv_output .= "\n";
		$total=$total+$row['print_credit'];
	}
	
	// ajouter la ligne du total de la journée
	$csv_output .= "\n";
	$csv_output .=$separator.$separator.$separator.$separator."Total".$separator.$total;
	
	
} else {
	
	$csv_output="Pas de données pour la journée demandée";
}
	 
	 print $csv_output ;
	
	exit();
		
	    		
?>