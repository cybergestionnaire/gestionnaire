<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

 include/post_epn.php V0.1
*/



$actarif=$_GET["actarif"];
$typetarif=$_GET["typetarif"];

$idtarif=$_GET["idtarif"];
$espaces= implode('-',$_POST['espace']);

switch ($actarif)
{
  case 1: // creation des tarifs
	
		switch($typetarif) {
			
			case 1: //impressions et divers
				$nomtarif = htmlentities(addslashes($_POST["newnomtarif"]));
				$prixtarif=$_POST["newprixtarif"];
				if(is_numeric($prixtarif)){$prixtarif=$prixtarif; }else{ $prixtarif="";}
				$comment=htmlentities(addslashes($_POST["newdescriptiontarif"]));
				$categoryTarif=$_POST["catTarif"];
				$duree="0"; //0=illimité
			 
			 if (!$nomtarif || !$prixtarif )
				{
				$mess= getError(4) ; //autres champs manquants
				}
				else
				{
			 if (FALSE == newTarif($nomtarif,$prixtarif,$comment,0,$categoryTarif,$duree,$espaces))
				 {
					 echo getError(0);
				 }else{
					header("Location:index.php?a=47&catTarif=1&mesno=32") ;
				 }
				}
				
			break;
			 
			 case 2: //ateliers
				$nomtarif = htmlentities(addslashes($_POST["newnomtarifa"]));
				$prixtarif=$_POST["newprixtarifa"];
				//$prixtarif=preg_replace("/[^0-9]/","",$prixtarif0); 
				$comment=htmlentities(addslashes($_POST["newdescriptiontarifa"]));
				$categoryTarif=5;
				$typeduree=$_POST["typedureetarifa"]; //0=illimite
				if($typeduree>0){
					$duree=$_POST["dureetarifa"].'-'.$_POST["typedureetarifa"];
				}
				$nbatelier=$_POST['newnumbertarifa'];
				
				 if (!$nomtarif | !$prixtarif | !$typeduree | !$nbatelier)
				{
				$mess= getError(4) ; //autres champs manquants
				}else{
				 
				 if (FALSE == newTarif($nomtarif,$prixtarif,$comment,$nbatelier,$categoryTarif,$duree,$espaces))
				 {
					 echo getError(0);
				 }else{
					header("Location:index.php?a=47&mesno=32") ;
				 }
				}
			
			break;
			
			case 3: //consult
			$nom_forfait= htmlentities(addslashes($_POST["nom_forfait"]));
			$prix_forfait = $_POST["prix_forfait"];
			$critere_forfait= "";
			$comment_forfait= htmlentities(addslashes($_POST["comment_forfait"]));
			$date_debut_forfait = date('d/m/Y');
			$date_creat_forfait     = date("d/m/Y");
		
			$nombre_atelier_forfait= 0;
			$status_forfait=1;
			$type_forfait = 1; // type affectation normal
				$temps_affectation_occasionnel=0;
				$nombre_temps_affectation = $_POST["nombre_temps_affectation"];
				$unite_temps_affectation = $_POST["unite_temps_affectation"];
				$frequence_temps_affectation= $_POST["frequence_temps_affectation"];
			
			/*
			//pour une affectation occasionnelle
			if($_POST["temps_affectation_occasionnel"]>0){
				$temps_affectation_occasionnel=$_POST["temps_affectation_occasionnel"];
				$type_forfait = 4; // type affectation occasionnel
					$nombre_temps_affectation =0;
					$unite_temps_affectation =0;
				$frequence_temps_affectation= 0;
			
			}else{
				$type_forfait = 1; // type affectation normal
				$temps_affectation_occasionnel=0;
				$nombre_temps_affectation = $_POST["nombre_temps_affectation"];
				$unite_temps_affectation = $_POST["unite_temps_affectation"];
				$frequence_temps_affectation= $_POST["frequence_temps_affectation"];
				
				}
			*/
			//validité et duree des tarifs
			//$unite_duree_forfait0= $_POST["unite_duree_forfait"]; //duree illimitée du forfait ou pas
			
			if ( $_POST["unite_duree_forfait"]==4){
				$temps_forfait_illimite=1;
				$nombre_duree_forfait =0;
				$unite_duree_forfait=0;
				}else{
				$temps_forfait_illimite=0;
				$nombre_duree_forfait = $_POST["nombre_duree_forfait"];
				$unite_duree_forfait= $_POST["unite_duree_forfait"];
				}
				
						
			$espacesselected=$_POST['espace'];
			
			
			// AJOUT DU FORFAIT
			 if (!$nom_forfait || !$nombre_temps_affectation)
				{
				$mess= getError(4) ; //autres champs manquants
				}
				else
				{
			
				$newid=addForfait($date_creat_forfait, $type_forfait, $nom_forfait, $prix_forfait, $critere_forfait, $comment_forfait,  $nombre_duree_forfait, $unite_duree_forfait, $temps_forfait_illimite, $date_debut_forfait,$status_forfait,$nombre_temps_affectation, $unite_temps_affectation, $frequence_temps_affectation, $temps_affectation_occasionnel,$nombre_atelier_forfait);
						
				if (FALSE == $newid)
					{
						 echo getError(0);
					}else{
							//inserer la relation pour les epn
						foreach($espacesselected as $key => $value ){
							modConfigForfaitEsp(1, $value);
							addForfaitEspace($newid, $value);
						}
						header("Location:index.php?a=47&mesno=32") ;
					}
					
				}
				
			
			break;
		}
	
  break;

	
  case 2: // modification
	$categoryTarif=$_POST["catTarif"];
	
		if($categoryTarif<6) {
			//categories 1 à 5 impression et ateliers
				$nomtarif = htmlentities(addslashes($_POST["nomtarif"]));
				$prixtarif=$_POST["prixtarif"];
				$desctarif=htmlentities(addslashes($_POST["descriptiontarif"]));
				$typeduree=$_POST["typedureetarif"]; //0=illimite
				if($typeduree>0){
					$duree=$_POST["dureetarif"].'-'.$_POST["typedureetarif"];
				}
				$nbatelier=$_POST['numberatelier'];
			
			 if (!$nomtarif | !$prixtarif )
				{
				$mess= getError(4) ; //champs manquants
				}
				else
				{
				
				if (FALSE == modifTarif($idtarif,$nomtarif,$prixtarif,$desctarif,$nbatelier,$categoryTarif,$duree,$espaces))
						{
								 echo getError(0);
						 }else{
							header("Location:index.php?a=47&catTarif=".$categoryTarif."&mesno=33") ;
					 
						}
					
				}
			
			//categorie 6 : la consultation		
		}	else{
			/* criteres non modifiés
			$nombre_atelier_forfait= 0;
			$status_forfait=1;
			$critere_forfait= "";
			$date_creat_forfait     = date("d/m/Y");
			$date_debut_forfait = date('d/m/Y');
			*/
		
			$idforfait=$_POST["id_forfait"];
			$nom_forfait= htmlentities(addslashes($_POST["nom_forfait"]));
			$prix_forfait = $_POST["prix_forfait"];
			$comment_forfait= htmlentities(addslashes($_POST["commentaire_forfait"]));
									
			//pour une affectation occasionnelle
			if($_POST["temps_affectation_occasionnel"]>0){
				$temps_affectation_occasionnel=$_POST["temps_affectation_occasionnel"];
				$type_forfait = 4; // type affectation occasionnel
					$nombre_temps_affectation =0;
					$unite_temps_affectation =0;
				$frequence_temps_affectation= 0;
			}else{
				$type_forfait = 1; // type affectation normal
				$temps_affectation_occasionnel=0;
				$nombre_temps_affectation = $_POST["nombre_temps_affectation"];
				$unite_temps_affectation = $_POST["unite_temps_affectation"];
				$frequence_temps_affectation= $_POST["frequence_temps_affectation"];
			}
			
			//validité et duree des tarifs
		//	$unite_duree_forfait0= $_POST["unite_duree_forfait"];
			if ($_POST["unite_duree_forfait"]==4){
			
				$temps_forfait_illimite=1;
				$nombre_duree_forfait =0;
				$unite_duree_forfait=0;
				}else{
				$temps_forfait_illimite=0;
				$nombre_duree_forfait = $_POST["nombre_duree_forfait"];
				$unite_duree_forfait= $_POST["unite_duree_forfait"];
				}
			
			$espacesselected=$_POST['espace'];
			
			///Modification des données dans la base
			if (!$idforfait || !$prix_forfait || !$type_forfait )
				{
				$mess= getError(4) ; //champs manquants
				}
				else
				{
			
				if (FALSE == modForfait($idforfait,$type_forfait, $nom_forfait, $prix_forfait,$comment_forfait, $nombre_duree_forfait, $unite_duree_forfait, $temps_forfait_illimite, $nombre_temps_affectation, $unite_temps_affectation, $frequence_temps_affectation, $nombre_atelier_forfait, $temps_affectation_occasionnel)){
							echo getError(0);
						
						}else{
							// vider les liaisons espace & tab config
							$delF= deleteAllEspaceForfait($idforfait);
							
							if($delF==TRUE){
								//inserer la relation pour les epn
								foreach($espacesselected as $key => $value ){
									modConfigForfaitEsp(1, $value);
									addForfaitEspace($idforfait, $value);
								}
							}
							header("Location:index.php?a=47&catTarif=6&mesno=33") ;
					 	}
			
				}
		}
		
  break;
  
  case 3: // suppression
	
		if($typetarif<3){
			 if (FALSE == deletarif($idtarif))
       {
           echo getError(0);
      }else{
				header("Location:index.php?a=47&mesno=34") ;
			}
	   }else{
			 if (FALSE == deletarifConsult($idtarif))
       {
          echo getError(0);
      }else{
				header("Location:index.php?a=47&mesno=34") ;
			}
		 }

  break; 
}



?>