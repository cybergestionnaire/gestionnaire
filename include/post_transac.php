<?php
///**********Fichier de modification d'une transaction *************///


$id_user= $_GET["iduser"];
$transac=$_GET["idtransac"];
$b=$_GET["b"];
$typeTransac=$_GET["typetransac"];
$statutp=$_POST["statutprint"];
//debug($statutp);

switch ($typeTransac)
{
///impressions	

	case "p":


		$datep=$_POST["date"];
		$debitp=$_POST["debitprint"];
		$tarifp=$_POST["tarifprint"];
		$creditp=$_POST["creditprint"];
	//	$statutp=$_POST["statutprint"];
		$nomuserp=$_POST["nomuser"];
		$moyen_p=$_POST["moyen_paiement"];
		
		if (TRUE == isset($_POST["submit"])){
			if($_POST["statutprint"]<2){
				if ($_POST["submit"]=="Encaisser"){
					$statutp="1";
					} else {
					$statutp="0";
					}
			} else {
				$statutp=2;
				}
		
		if (FALSE == modPrint($transac,$datep,$debitp,$tarifp, $statutp, $creditp,$nomuserp,$moyen_p))
			 {
			 header("Location: ./index.php?a=21&mesno=0");
			 } else {
				header("Location: ./index.php?a=21&b=1&iduser=".$id_user."");
			 }
	 
		}
  break;

	
	///renouvellement adhésion
case "adh":
	
	$daterenouv=$_POST["daterenouv"];
	$datetransac=$_POST["date"];
	$adhesiontarif=$_POST["tarif_adh"];
	$transac=$_POST["idtransac"];
	$type_transac="adh";
	
		if (TRUE == isset($_POST["submit"])){
			
			if ($_POST["submit"] =="Encaisser" ){
				$statutp="1";
				} else {
				$statutp="0";
				}
		
	//renouvellement et nouvelle inscription, entrée dans la tab_ransac de la nouvelle transaction
	
	if (FALSE == addAdhesion($datetransac,$type_transac,$id_user,$adhesiontarif, $statutp))
					 {
						 header("Location: ./index.php?a=1&mesno=0");
					 } else {
						//modification de la date de renouvellement dans tab_user, actualisation du statut et du tarif
						
					if(FALSE==modifUserStatut($id_user,1, $daterenouv, $adhesiontarif))
						{
							 header("Location: ./index.php?a=1&mesno=0");
						} else {
							header("Location: ./index.php?a=1&b=2&iduser=".$id_user."&mesno=26");
						}
					 }
				
			
		}

  break;

	
	///forfait
case "forfait":
//recuperation
	$date=$_POST["date"];
	$tarif_forfait=$_POST["tarif_forfait"];
	$nbredeforfait=$_POST["nbrf"];
	//calcul du nombre d'atelier total pour le forfait choisi
	$totalatelier=getNbatelierbytarif($tarif_forfait);
	$nbatelier=$nbredeforfait*$totalatelier;
	$transac=$_GET["idtransac"];
	$type_transac="for";
	if (TRUE == isset($_POST["submit"])){
			
		if ($_POST["submit"] =="Encaisser" ){
				$statutp="1"; //en cours
				} else {
				$statutp="0"; // en attente de paiement
				}
				
		if(isset($transac)){
			//modification
			if (FALSE == modifForfaitUser($transac,$tarif_forfait,$date,$nbredeforfait,$statutp,$nbatelier))
			 {
				 header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
			 } else {
				 header("Location: ./index.php?a=6&iduser=".$id_user."");
			 }	
		} else {
			//creation
			$idtransac=addForfaitUser($type_transac,$id_user,$tarif_forfait,$nbreforfait,$date,$statutp);
			if (FALSE ==$idtransac )
			 {
				 header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
			 } else {
				 ///verifier avant la participation aux ateliers et inscrementer pour régulariser !
				 $avalid=getnbASUserEncours($id_user,1);// total des ateliers et session validés
				 $farchiv=getFUserArchiv($id_user);//anciens forfaits archivés à décompter..
				 $reste=$avalid-$farchiv;
					 if($reste>0){
					 $depense= $reste;
					 } else {
					 $depense=0;
					 }
				 //
				 addRelforfaitUser($id_user,$idtransac,$nbatelier,$depense,$statutp);
				 header("Location: ./index.php?a=6&iduser=".$id_user."");
			 }	
		
		}
	}

break;

case "temps" :
//recuperation
	$date=$_POST["date"];
	$tarif_forfait=$_POST["tarif_forfait"];
	$nbreforfait=1;
	$type_transac="temps";
	$transac=$_GET["idtransac"];
	$nbatelier=0;
	
	if (TRUE == isset($_POST["submit"])){
			
		if ($_POST["submit"] =="Encaisser" ){
				$statutp="1"; //en cours
				} else {
				$statutp="0"; // en attente de paiement
				}
	
	if(isset($transac)){
	//modification
			$rowtransacuser=getForfait($transac);
			$statut0=$rowtransacuser['status_transac'];
			if (FALSE == modifForfaitUser($transac,$tarif_forfait,$date,$nbreforfait,$statutp,$nbatelier))
			 {
				 header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
			 } else {
				if($statut0==0 and $statutp==1){ 
						addrelconsultationuser(1,$tarif_forfait,$id_user);//ajouter la relation pour activer-epnconnect
					}else if($statut0==1 and $statutp==1){
						addrelconsultationuser(2,$tarif_forfait,$id_user);//modifier la relation si car forfait a changé..
					}
				 
				 header("Location: ./index.php?a=6&iduser=".$id_user."");
			 }	
			 
			 
		} else {
			//creation
		$idtransac=addForfaitUser($type_transac,$id_user,$tarif_forfait,$nbreforfait,$date,$statutp);
		
		if (FALSE ==$idtransac )
			 {
				 header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user."");
			 } else {
					//n'ajouter la relation pour epnconnect que si c'est encaissé
					if($statutp==1){
					 addrelconsultationuser(1,$tarif_forfait,$id_user);
				 }
					header("Location: ./index.php?a=6&mesno=26&iduser=".$id_user."");
			 }
			
			}
	}


break;


}
?>