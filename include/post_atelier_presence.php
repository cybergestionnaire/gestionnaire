<?php
/*
   Validation des inscriptions aux ateliers pour les statistiques AJOUT 2013
 include/post_atelier_presence.php V0.1
*/

$idatelier  = $_GET["idatelier"];
$act=$_GET["act"];

if (isset($_POST["valider_presence"]))   // si le formulaire est posté
{
	//rrecuperation des variables
	$idatelier=$_POST['idatelier'];
	$categorie=$_POST['id_categorie'];
	$date_atelier=$_POST['date_atelier'];
	$nombre_present= count($_POST['present_']);
	$nombre_inscrit=$_POST["nbrinscrits"];
	$nom_atelier = $_POST["nom_atelier"];
	$attente=$_POST["attente"];
	$nbplace=$_POST["nbplace"];
	$anim=$_POST["anim"];
	$epn=$_SESSION["idepn"];
	
	switch ($act)
	{
	//1er validation
	case 0 : 
		
		for ($x = 0; $x < $nombre_present; $x++) {
			
		 ModifyUserAtelier($idatelier,$_POST['present_'][$x],1);
		 //test du forfait de l'adherent
		 $depense=getForfaitUserEncours($_POST['present_'][$x]);
			 if($depense["depense"]+1 ==$depense["total_atelier"]){
				clotureforfaitUser($depense["total_atelier"],$depense["id_forfait"]);
				$header="Location:index.php?a=13&b=1&idatelier=".$idatelier; //vers l'atelier pour reactiver epnconnect
			}else{
				updateForfaitdepense($depense["id_forfait"]);
				$header="Location:index.php?a=13&b=1&idatelier=".$idatelier; //vers l'atelier pour reactiver epnconnect
			 }
		
		}	
		
	//modifier le statut de l'atelier = cloturé ==2
		UpdateAtelierStatut($idatelier,2);
	//entrer les stats
	$absents=$nombre_inscrit-$nombre_present;
	InsertStatAS('a',$idatelier,$date_atelier,$nombre_inscrit,$nombre_present,$absents,$attente,$nbplace,$categorie,1,$anim,$epn);
		
	//REDIRECTION
	header($header) ;
	break;
	
	
	//modification depuis les archives
	case 1:
		
		//charger la liste des inscrits
		$archivarr=getAtelierArchivUser($idatelier);
		$nbarchiv=mysqli_num_rows($archivarr);
		
		for ($x = 0; $x < $nbarchiv; $x++) {
			$archiv=mysqli_fetch_array($archivarr);
			$iduser=$archiv['id_user'];
			$statutuser=$archiv["status_rel_atelier_user"];
			
		//Cas 1: un adhérent est absent à l'origine, mais présent de fait
			if(in_array($iduser,$_POST['present_'])==TRUE){
				if($statutuser==0){
					ModifyUserAtelier($idatelier,$iduser,1);
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
					ModifyUserAtelier($idatelier,$iduser,0);
					//enlever 1 atelier au compte du forfait
					$depense=getForfaitUserEncours($iduser);
					DeleteOneFromForfait($depense["id_forfait"],$iduser);
				}
			}
		}
		//modifier dans les stats !
		$absents=$nombre_inscrit-$nombre_present;
		ModifStatAS($nombre_inscrit,$nombre_present,$absents,$idatelier,'a');
		
		header("Location:index.php?a=18&mesno=43"); //vers les archives	
	break;
	
	}
	
}
?>