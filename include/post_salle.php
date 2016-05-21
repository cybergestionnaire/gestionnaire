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
$id       =  $_GET["idsalle"];

$nom     = addslashes($_POST["nom"]) ;
$espace      = addslashes($_POST["espace"]) ;
$comment = addslashes($_POST["comment"]) ;

if ($act !="" AND $act!=3)  // verife si non vide
{
  // Traitement des champs a insérer
    if (!$nom || !$espace )
    {
       $mess = getError(4);
    }
    else
    {
        switch($act)  
        {
            case 1:   // ajout d'une salle
            $idsalle = addSalle($nom,$espace,$comment) ;
                 if (FALSE == $idsalle)
                 {
                     header("Location: ./index.php?a=44&mesno=0");
                 }
                 else
                 {
      				 header("Location: ./index.php?a=44");
                 }
            break;
            case 2:   // modifie une salle
                 if (FALSE == modSalle($id,$nom,$espace,$comment))
                 {
                     header("Location: ./index.php?a=44&mesno=0");
                 }
                 else
                 {
      				 header("Location: ./index.php?a=44");
                 }
            break;
        }
    }
}
if ($act==3) // supprime une salle
{
  if (FALSE == supSalle($id))
  {
      header("Location: ./index.php?a=44mesno=");
  }
  else
  {
      header("Location: ./index.php?a=44");
  }
}
?>
