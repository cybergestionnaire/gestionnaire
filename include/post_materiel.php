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
 

 include/post_materiel.php V0.1
*/

// fichier de recuperation des variables du formulaire materiel

$act      =  $_GET["act"];
$id       =  $_GET["idmat"];

$nom     = addslashes($_POST["nom"] );
$os      = $_POST["os"] ;
$salle      = $_POST["salle"] ;
$usage   = $_POST["usage"] ;
$adresseIP=$_POST["adresseIP"] ;
$adresseMAC=$_POST["adresseMAC"] ;
$nomhote=$_POST["nomhotecomputer"] ;
$fonctionarray=$_POST["fonction"];
$fonction=implode(";",$fonctionarray);
$comment = addslashes($_POST["comment"]) ;

if ($_POST["submit"] !="" )
{
$epnr=$_POST["epn_r"];
		header("Location:index.php?a=2&epnr=".$epnr);

}


if ($act !="" AND $act!=3)  // verife si non vide
{
  // Traitement des champs a insérer
    if (!$nom || !$salle )
    {
       $mess = getError(4);
    }
    else
    {
        switch($act)  
        {
            case 1:   // ajout d'un poste
             $idcomputer = addMateriel($nom,$os,$comment,$usage, $fonction,$salle,$adresseIP,$adresseMAC,$nomhote) ;
		
                 if (FALSE == $idcomputer)
                 {
                     header("Location: ./index.php?a=2&mesno=0");
		     
                 }
                 else
                 {
                       $usage=getAllUsage();
                       foreach ($usage AS $key =>$value)
                       {
                           if ($_POST["$key"] == "on")
                           {
                               addMaterielUsage($idcomputer,$key) ;
                           }
                       }
                       
                       header("Location: ./index.php?a=2&mesno=14");
                 }
            break;
			
            case 2:   // modifie un poste
                 if (FALSE == modMateriel($id,$nom,$os,$comment,$usage,$fonction,$salle,$adresseIP,$adresseMAC,$nomhote))
                 {
                     header("Location: ./index.php?a=2&mesno=0");
                 }
                 else
                 {
                     header("Location: ./index.php?a=2&mesno=14");
                 }
            break;
        }
    }
}
if ($act==3) // supprime un poste
{
  if (FALSE == supMateriel($id))
  {
      header("Location: ./index.php?a=2&mesno=");
  }
  else
  {
      header("Location: ./index.php?a=2");
  }
}
?>
