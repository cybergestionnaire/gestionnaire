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
 
*/


    // retour 
    if(TRUE==isset($_POST["retour"]))
    {
      $step = $_POST["step"]-1;
    }
    // etape 1 : choix de l'heure de depart de la resa
    else if(TRUE==isset($_POST["submit1"]))
    {
      if ($_POST['debut']!="")
      {
          $_SESSION['debut']=$_POST["debut"] ;
          $step = $_POST["step"]+1;
      }
      else
      {
          $step = $_POST['step'] ;
          $messErr = 'Vous devez s&eacute;lectionner l\'heure de d&eacute;but de votre r&eacute;servation </br>' ;                 
      }
    }
    // etape 2 :  choix de la duree de la resa
    else if(TRUE==isset($_POST["submit2"]))
    {
      if ($_POST['duree']!="")
      {
          $_SESSION['duree']=$_POST["duree"] ;
          $step = $_POST["step"]+1;
      }
      else
      {
          $step = $_POST['step'] ;
          $messErr = 'Vous devez s&eacute;lectionner la dur&eacute;e de votre r&eacute;servation </br>' ; 
      }
    }
    // choix de l'adherent
    else if(TRUE == isset($_POST['adh_submit']))
    {
        $step = $_POST['step'] ;
        unset($_SESSION["other_user"]);
        $searchuser = $_POST["searchuser"];
    }
    else if (TRUE == isset($_POST['choose_adh']))
    {
        $step = $_POST['step'] ;
        $_SESSION['other_user'] = $_POST['choose'] ;
    }
    // etape 3 : finalisation de la reservation
    else if(TRUE==isset($_POST["valider"]))
    {
        if (TRUE == is_numeric($_SESSION['other_user']))
        {
           $id_user = $_SESSION["other_user"];
        }
        else
        {
            $id_user = $_SESSION["iduser"];
        }
		
     $idresarel= addResa($_GET["idcomp"],$id_user,$_GET["date"],$_SESSION["debut"],$_SESSION["duree"]);
			
			// insertion de la relation usage de poste (resa=1 ou atelier=2)
		if($idresarel!=FALSE){
			insertrelresa($idresarel,1,'');
		}
		
      unset($_SESSION["debut"]);
      unset($_SESSION["duree"]);
      unset($_SESSION["other_user"]) ;
      header('Location:'.$_SESSION['resa']['url']) ;
    }
?>