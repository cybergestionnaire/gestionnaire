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
  $id       =  $_GET["iduser"];
  
    //recuperation et traitement des variables
    $sexe     =  $_POST["sexe"];
    $nom      =  $_POST["nom"];
    $prenom   =  $_POST["prenom"];
    $jour     =  $_POST["jour"];
    $mois     =  $_POST["mois"];
    $annee    =  $_POST["annee"];
    $adresse  =  $_POST["adresse"];
    $code_postale    =  $_POST["code_postale"];
    $commune_autre    =  $_POST["commune_autre"];
    $ville    =  $_POST["ville"];
    $pays    =  $_POST["pays"];
    $tel      =  $_POST["tel"];
    $telport    =  $_POST["telport"];
    $mail     =  $_POST["mail"];
    $temps     =  999;
    //$temps     =  $_POST["temps"];
    $csp     =  $_POST["csp"];
    $equipement     =  $_POST["equipement"];
    $utilisation     =  $_POST["utilisation"];
    $connaissance     =  $_POST["connaissance"];
    $info     =  $_POST["info"];
    $loginn    =  $_POST["login"];
    $passs     =  $_POST["passw"];
      
      // Traitement des champs a insérer
      if (!$nom || !$prenom || !$annee || !$loginn )
      {
         $mess = getError(4);
      }
      else
      {
	   		$resultville=getcity($ville);
			if(strcmp($resultville, "Autres")!=0)
			{
    			$code_postale    =  "";
    			$commune_autre    =  "";
    			$pays    =  "";
			}
                if (FALSE == checkLoginUpdate($loginn,$id))
                {
                  
                   $mess = getError(5);
                }
                else
                {
                  
                  $result=modUserbyuser($id,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$code_postale, $commune_autre,$ville, $pays,$tel, $telport,$mail, $temps, $csp, $equipement, $utilisation, $connaissance, $info,$loginn,$passs) ;
        			if ($result == FALSE)
        			{
            			$mess = getError(0);
        			}
        			else
        			{
            			$mess = getError(14);
        			}
                }
        }
?>