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
 

 include/post_user.php V0.1
*/
if(isset($_POST["submit"])){

	 $_SESSION['sauvegarde']=$_POST;
	//debug($_POST);
    //recuperation et traitement des variables oligatoires
    
    $sexe     =  $_POST["sexe"];
    $nom      =  trim($_POST["nom"]);
    $prenom   =  trim($_POST["prenom"]);
    $jour     =  $_POST["jour"];
    $mois     =  $_POST["mois"];
    $annee    =  $_POST["annee"];
    $adresse  =  $_POST["adresse"];
     
    $ville    =  $_POST["ville"];
    $tel      =  $_POST["tel"];
    $telport=$_POST["telport"];
    $mail     =  trim($_POST["mail"]);
    
   	$epn=$_POST["epn"];
	 
	  $captcha=$_POST["g-recaptcha-response"];
    //recuperation donnes optionnelles
	 if(isset($_POST["csp"])) {$csp     =  $_POST["csp"];} else {$csp =1 ;}
   if (isset($_POST["equipement"])){
			$equipementarr=array();
			$equipementarr=  $_POST["equipement"];
			$equipement=implode("-",$equipementarr);
		}else{
			$equipementarr=0;
			
		}
	
		if(isset($_POST["commune"])){ $commune     =  $_POST["commune"];}else{$commune = "vide";}
		if(isset($_POST["codepostal"])){ $codepostal     =  trim($_POST["codepostal"]);}else{$codepostal = "vide";}
			if(isset($_POST["pays"])){ $pays     =  trim($_POST["pays"]);}else{$pays = "vide";}
   if(isset($_POST["utilisation"])){ $utilisation     =  $_POST["utilisation"];}else{$utilisation     = 0;}
   if(isset($_POST["connaissance"])){ $connaissance     =  $_POST["connaissance"];}else{$connaissance     = 0;}
   if(isset($_POST["info"])){  $info     =  $_POST["info"];}else{$info     = "";}
    
    
    
  $urlRedirect = "validation.php?epn=".$epn ; //puis redirection vers url de page neutre des infos de l'epn
  
  
	$date     =  date('Y-m-d'); //date d'inscription
	$status = 2; //statut inactif par defaut
	
	$temps=1; //tarif consultation par defaut = sans tarif !
	//login et mot de passe provisoires
		$loginn    = $nom;
    $passs     = $prenom;
	
	
	
	
	
	 // Traitement des champs a insérer
      if (!$sexe || !$nom || !$prenom || !$annee || !$mois || !$jour || !$adresse || !$mail || !$epn || !$captcha)
      {
       
       
        $mess = getError(4);
				
        
      }
      else
      {
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $mess = getError(48); //format mail invalide
            }
            else {
                if (!checkdate($mois, $jour, $annee)) {
                    $mess = getError(49); //date invalide
                }
                else {
				  if (FALSE == addUserinscript($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$pays,$codepostal,$commune,$ville,$tel,$telport,$mail,$temps,$loginn,$passs,$status,$csp,$equipement,$utilisation,$connaissance, $info,$epn))
                  {
            			$mess = getError(0);
                  }
                  else
                  {	
							
                     header("Location:".$urlRedirect."");
                  }
                }
			
			}
      }
}
?>