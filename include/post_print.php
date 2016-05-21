<?php
/* fichier d'envoi des données transactions à la base*/

$id_user= $_GET["iduser"];
$id_transac=$_GET["idtransac"];
$act=$_GET["act"];
$donneep=$_POST["printU"];
$epn_p=$_SESSION["idepn"];
$caissier=$_SESSION["iduser"];
//Ajout de l'utilisateur externe
if ($_GET["ext"]==1){
	if (isset($_POST["nomuser"]))
	{	$nomuser_p=addslashes($_POST["nomuser"]);
		}else{
		$mess= getError(4);
		//exit;
	}
}else{
	$nomuser_p='0';
}


if ($act !="" AND $act!=3)  // verife si non vide
{

	switch($act)  
			{
				case 1:   // ajout d'une transaction
					$nbt=count($donneep)/3;
					$donnees=array_chunk($donneep ,3);
					$credit=$_POST["credit"];
					$somme=$_POST["sommecreditee"]; //ce qui a ete encaissé
					$date_p=$_POST["date"];
					$moyen_p=$_POST["moyen_paiement"];
					$paiement=$_POST["paiement"];
					
				if($credit>0){
					//on boucle sur les tarifs 
					for ($i=0;$i<$nbt;$i++){
						$tab_print=$donnees[$i];
						$tarif_p=$tab_print[0];
						$debit_p=$tab_print[1];
						$credit_p=$tab_print[2];
						
						if ($_POST["submit"] =="Encaisser" ){
							$statut_p="1";
							}else{
							$statut_p="0";
							}
						
						if ($debit_p>"0"){
								$Gprint=addPrint($date_p,$id_user,$debit_p, $tarif_p,$statut_p,$credit_p,$nomuser_p,$epn_p,$caissier,$moyen_p);
							}
					}
						//rajouter le credit, regulariser le credit en cas de somme differente	
						$du=$_POST["du"]; //ce qui doit etre payé
						$credit=$somme-$du; // ce qui reste en plus apres deduction de la somme due
						$Gprint=addPrint($date_p,$id_user,0,0,2,$credit,$nomuser_p,$epn_p,$caissier,$moyen_p);
							
				}else{
				
				
				//sinon boucle normale, on ajoute le credit en fonction du tarif
				
					for ($i=0;$i<$nbt;$i++){
						$tab_print=$donnees[$i];
						$tarif_p=$tab_print[0];
						$debit_p=$tab_print[1];
						
						//remise a zero si le credit est deja positif sur le compte et est superieur au total depense
						if($paiement==0){
							$credit_p=0;
							}else{
							$credit_p=$tab_print[2];
						}
						if ($_POST["submit"] =="Encaisser" ){
							$statut_p="1";
							}else{
							$statut_p="0";
							}
						if ($debit_p>"0"){
							$Gprint=addPrint($date_p,$id_user,$debit_p, $tarif_p,$statut_p,$credit_p,$nomuser_p,$epn_p,$caissier,$moyen_p);
						}
					
					}
					
				}
				
				if (FALSE ==$Gprint)
						 {
							header("Location: ./index.php?a=21&mesno=0");
						 }
						 else
						 {
							header("Location: ./index.php?a=21&b=1&act=&iduser=".$id_user);
						 }
				
				break;
				
				/*
				case 2:   // modifie une transaction
					 if (FALSE == modPrint($id_transac,$date_p,$debit_p,$tarif_p, $statut_p, $credit_p,$nomuser_p, $moyen_p ))
					 {
						 header("Location: ./index.php?a=21&mesno=0");
					 }
					 else
					 {
						 header("Location: ./index.php?a=21&b=1&iduser=".$id_user."");
					 }
				break;
				
				*/
				
				case 4: //crediter le compte d'impression
					$credit_p = $_POST["credit"];
					$date_p = $_POST["datec"];
					//statut 2 == credit uniquement
					if(isset($credit_p)){
						if (FALSE == addPrint($date_p,$id_user,0,0, 2, $credit_p,$nomuser_p,$epn_p,$caissier,$moyen_p ))
						 {
							 header("Location: ./index.php?a=21&mesno=0");
						 }
						 else
						 {
							 header("Location: ./index.php?a=21&b=1&iduser=".$id_user."");
						 }
					
					}
				break;
				
				
	}

}

?>