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

// fichier de recuperation des variables du formulaire espace
$b=$_GET["b"];
$act      =  $_GET["act"];

//recuperation du formulaire epn
if($b<3){
	$id       =  $_GET["idespace"];
	$nom     = htmlentities(addslashes($_POST["nom"])) ;
	$adresse = htmlentities(addslashes($_POST["adresse"])) ;
	$ville      = $_POST["ville"] ;
	$tel      = $_POST["telephone"] ;
	$fax      = $_POST["fax"] ;
	$couleur=$_POST["ecouleur"];
	$logoespace=trim($_POST["elogo"]);
	$mail=$_POST["mail"] ;
}else{
	$nom = htmlentities(addslashes($_POST["nomreseau"]));
	$adresse = htmlentities(addslashes($_POST["adressereseau"])) ;
	$ville  = $_POST["villereseau"] ;
	$tel = trim($_POST["telreseau"]) ;
	$logo=trim($_POST["logoreseau"]);
	$mail=trim($_POST["mailreseau"] );
	$courrier=$_POST["courriers"];
	$activation=$_POST["activation"];
	
}

//b=1 b=2 pour les espaces, b=3 b=4 pour le reseau

if ($act !="" AND $act!=3)  // verife si non vide
{
  // Traitement des champs a insérer
    if (!$nom || !$ville || !$mail || !$adresse )
    {
       $mess = getError(4);
    }
    else
    {
        switch($act)  
        {
           


					 case 1:   // ajout d'un epn
            $idespace = addEspace($nom,$adresse,$ville,$tel,$fax,$logoespace,$couleur,$mail) ;
                 if (FALSE == $idespace)
                 {
                     header("Location: ./index.php?a=43&mesno=0");
                 }
                 else
                 {
										copyhoraires($idespace);
										copyconfig($idespace,'0');
										copyconfiglogiciel($idespace);
					
										header("Location: ./index.php?a=43&mesno=14");
                 }
            break;
						
            case 2:   // modifie un espace
                 if (FALSE == modEspace($id, $nom,$adresse,$ville,$tel,$fax,$logoespace,$couleur,$mail))
                 {
                     header("Location: ./index.php?a=43&mesno=0");
                 }
                 else
                 {
					
										header("Location: ./index.php?a=43&mesno=14");
                 }
            break;
						
						
						case 4: // modification du nom du reseau par defaut
						if (FALSE == modreseau($nom,$adresse,$ville,$tel,$mail,$logo,$courrier,$activation))
						 {
							echo getError(0);
						 }else{
						 
								header("Location:index.php?a=43&mesno=14") ;
							}
							break;
						
        }
    }
}

/*
	
*/



if ($act==3) // supprime un espace
{
  $id       =  $_GET["idespace"];
   $errno = supEspace($id) ;
	       switch ($errno)
	       {
					case 0: // impossible de joindre la base
						header("Location:index.php?a=43&mesno=0");
					break;
					case 1:// l'espace contient des salles
						header("Location:index.php?a=43&mesno=50") ;
					break;
					case 2:
						header("Location:index.php?a=43&mesno=14") ;
					break;
						}
  
 
}





?>