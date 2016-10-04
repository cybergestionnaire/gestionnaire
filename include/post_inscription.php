<?php
/*
     This file is part of CyberGestionnaire.

    CyberGestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

 include/post_user.php V0.1
*/

  $act       =  $_GET["act"];
	$id=$_POST["iduser"];
    //recuperation et traitement des variables
    $date     =  $_POST["date_inscription"];
    $sexe     =  $_POST["sexe"];
    $nom      =  addslashes($_POST["nom"]);
    $prenom   =  addslashes($_POST["prenom"]);
    $jour     =  $_POST["jour"];
    $mois     =  $_POST["mois"];
    $annee    =  $_POST["annee"];
    $adresse  = addslashes( $_POST["adresse"]);
    $epn=$_POST["epn"];
    
    $tel      = trim($_POST["tel"])."/".trim($_POST["telport"]);
   
    $mail     =  trim($_POST["mail"]);
     if(isset($_POST["csp"])) {$csp     =  $_POST["csp"];} else {$csp =1 ;}
   if (isset($_POST["equipement"])){
			$equipementarr=array();
			$equipementarr=  $_POST["equipement"];
			$equipement=implode("-",$equipementarr);
		}else{
			$equipementarr=0;
			
		}
   if(isset($_POST["utilisation"])){ $utilisation     =  $_POST["utilisation"];}else{$utilisation     = 0;}
   if(isset($_POST["connaissance"])){ $connaissance     =  $_POST["connaissance"];}else{$connaissance     = 0;}
   if(isset($_POST["info"])){  $info     =  addslashes($_POST["info"]);}else{$info     = "";}
    $login    = addslashes( $_POST["login"]);
    $pass     =  $_POST["passw"];
		$status   =  $_POST["status"];
		$tarif=$_POST["tarif"];
   $lastvisit   =  $_POST["date_inscription"];
    $temps   =  $_POST["temps"];
  
     //date de renouvellement adhesion automatiquement crée
	$daterenouv = date_create($date);
	date_add($daterenouv, date_interval_create_from_date_string('365 days'));
	$daterenouv=date_format($daterenouv, 'Y-m-d');
	$newsletter="0";
	  
	
		$ville=$_POST["ville"];
		$codepost    =  $_POST["codepostal"];
    $commune    =  addslashes($_POST["commune"]);
    $pays    =  addslashes($_POST["pays"]);
    
if(isset($_POST["submit"])){
	//1 ajout de la ville en plus si besoin
	if ($ville == 0) {
        $idnewcity == addCity($commune, $codepost, $pays);
        if (FALSE == $idnewcity) {
            echo getError(0);
            $ville=0;
						
	       }else{
						$ville=$idnewcity;
	       }
	       
		}else {
			$ville    =  $_POST["ville"];
			
		}
	  
    if (FALSE == checkLogin($login))
			{
					$mess = getError(5);
			}
			else
			{
					
       if (!$nom || !$prenom || !$annee || !$adresse || !$login )
				{
					$mess = getError(4);
					exit;
				}
				else
				{       
					//insertion du nouvel utilisateur
					$iduser=addUser($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$ville,$tel,$mail,$temps,$login,$pass,$status,$lastvisit,$csp,$equipement,$utilisation,$connaissance,$info,$tarif,$daterenouv,$epn,$newsletter);
					//enlever le preinscription
					if (FALSE ==$iduser)
									{
									$mess = getError(0);
									} else {	
										delUserInsc($id);
											header("Location:index.php?a=1&b=2&iduser=".$iduser);
									}
				  
        }
			 }
	
	}
?>