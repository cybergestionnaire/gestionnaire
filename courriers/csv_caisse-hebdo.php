<?php
include("../include/fonction.php");
include("../connect_db.php");
// 
$date= $_GET["date"];
$epn=$_GET["epn"];

$separator = ";";
    header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=caissedesemaine-".$date.".csv");
	
// Ligne entête
$csv_output = "Etat de caisse pour la semaine n°".date('W');
$csv_output .= "\n";
$csv_output .= "date".$separator."nom". $separator."prenom".$separator."Tarif".$separator."Nombre".$separator."Encaissé".$separator."Caissier";
$csv_output .= "\n";


 //Les impressions du jour
$db = mysqli_connect($host,$userdb,$passdb,$database) ;
$sql="SELECT `print_date`,`print_debit`,`donnee_tarif`,`print_credit`, nom_user, prenom_user  ,print_userexterne,print_user,print_caissier
FROM tab_print, tab_user , tab_tarifs 
 WHERE WEEK(print_date)=WEEK('".$date."')
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
	}
    else
	{
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
	}else{
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
			}else{
			$name_user="externe".$separator.$row['print_userexterne'];
			}
		}else{
		$name_user=$row['nom_user'].$separator.$row['prenom_user'];
		}
		if($row['print_caissier']==NULL){
			$caissier="Non renseigné";
		}else{
			$caissier=getNomCaissier($row['print_caissier'],$host,$userdb,$passdb,$database);
		}
		$csv_output .= $row['print_date'].$separator.$name_user.$separator.$row['donnee_tarif'].$separator.$row['print_debit'].$separator.$row['print_credit'].$separator.$caissier;
		$csv_output .= "\n";
		$total=$total+$row['print_credit'];
	}
	
	// ajouter la ligne du total de la journée
	$csv_output .= "\n";
	$csv_output .=$separator.$separator.$separator.$separator."Total".$separator.$total;
	
	
}else{
	
	$csv_output="Pas de données pour la semaine demandée";
}
	 
	 print $csv_output ;
	
	exit();
		
	    		
?>