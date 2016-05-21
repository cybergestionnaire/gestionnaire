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
  $act      =  $_GET["act"];
  $id       =  $_GET["iduser"];
  //debug($_POST);
    
  // Choix de la fonction a utiliser
  if ($act !="")
  {
    //recuperation et traitement des variables
    $date     =  $_POST["inscription"];
    $nom      = htmlentities($_POST["nom"], ENT_QUOTES) ;
    $prenom   =htmlentities($_POST["prenom"], ENT_QUOTES) ;
    $sexe     =  $_POST["sexe"];
   // debug($sexe);
    $jour     =  $_POST["jour"];
    $mois     =  $_POST["mois"];
    $annee    =  $_POST["annee"];
    $adresse  =  htmlentities($_POST["adresse"], ENT_QUOTES) ;
    $ville    =  $_POST["ville"];
    $tel      =  trim($_POST["tel"]);
    $mail     = trim($_POST["mail"]);
    $temps    =  $_POST["temps"];
    $loginn    =  trim($_POST["login"]);
    $pass    =  $_POST["passw"];
    $status   =  $_POST["status"];
	$csp   =  $_POST["csp"]; // ajout de la csp
	
	$utilisation=$_POST["utilisation"];
	 $connaissance=$_POST["connaissance"];
	 $info  = htmlentities($_POST["info"], ENT_QUOTES) ;
	 $tarif=$_POST["tarif"];
	 
	 $equipementarr=array();
	$equipementarr=  $_POST["equipement"];
	$equipement=implode("-",$equipementarr);
	
	
	//$newsletter=$_POST["newsletter"];
	$newsletter=$_POST["newsletter"];
	if(isset($newsletter)) {$newsletter=1; }
	$epn=$_POST["epn"];
	 
	//date de renouvellement adhesion automatiquement crée
	$daterenouv = date_create($date);
	date_add($daterenouv, date_interval_create_from_date_string('365 days'));
	$daterenouv=date_format($daterenouv, 'Y-m-d');
	
	
	
    
	$urlRedirect = "./index.php?a=1" ;
				
    // redirige sur la page animateur ou adherent selon l'origine du lien
    /*
    if ($_POST['type']=='anim' OR $_POST['type']=='admin'){
        $urlRedirect = "./index.php?a=23" ;
		
    } else{
        $urlRedirect = "./index.php?a=1" ;
	}
      */
		// suppression d'un user
     if ($act=='del' AND $_SESSION['status']==4)
    {
        deluser($_GET['iduser']);
        header("Location:".$urlRedirect."");
    }
    
      
      // Traitement des champs a insérer
      if (!$nom || !$prenom || !$annee || !$adresse || !$loginn || !$sexe)
      {
         $mess = getError(4);
      }
      else
      {
            switch($act)
            {
                case 1:   // ajout d'un adherent
								//  $urlRedirect = "./index.php?a=1&b=2" ;
								
                if (FALSE == checkLogin($loginn))
                {
                   $mess = getError(5);
                }
                else
                {
									$iduser= addUser($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$ville,$tel,$mail,$temps,$loginn,$pass,$status,$lastvisit,$csp,$equipement,$utilisation,$connaissance,$info,$tarif,$daterenouv,$epn,$newsletter);
                									
									if (FALSE ==$iduser)
                  {
											header("Location:".$urlRedirect."&mesno=0");
                  }
                  else
                  {
                     //enregistrement des transactions choisies
											addForfaitUser("temps",$iduser,$temps,1,date('Y-m-d'),1); //forfait temps
											addForfaitUser("adh",$iduser,$tarif,1,date('Y-m-d'),1); //adhésion

										 //ajout de la relation forfait-consultation dans rel_forfait_user
                     if(FALSE== addrelconsultationuser(1,$temps,$iduser))
										 {
												header("Location:".$urlRedirect."&mesno=0");
												}else{
												header("Location:./index.php?a=1&b=3&mesno=18");
                      }
											
											
                  }
                }
                break;
              
                case 2:   // modifie un adherent
								
				//Modification du status --> archivé, date de l'archivage==lastvisit_user
				if($status==6) { $lastvisit=date('Y-m-d');}else{ $lastvisit='';}
		
                if (FALSE == checkLoginUpdate($loginn,$id))
                {
                  
                   $mess = getError(5);
                }
                else
                {
                  if(FALSE== modUser($id,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$ville,$tel,$mail,$loginn,$pass,$status,$lastvisit,$csp,$equipement,$utilisation,$connaissance, $info,$epn,$newsletter) )
                  {
											header("Location:".$urlRedirect."&mesno=0");
                  }else{
									 
										header("Location:".$urlRedirect."&mesno=42");
					}
                }
		
                break;
            }
        }
  }
?>
