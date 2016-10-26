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
 
*/
    //error_log(print_r($_POST, true));
    //error_log(print_r($_GET, true));

    require_once("include/class/Ville.class.php");
    require_once("include/class/Espace.class.php");
    require_once("include/class/Utilisateur.class.php");
    
    $act            = isset($_GET["act"])           ? $_GET["act"] : '';
    $id             = isset($_POST["iduser"])       ? $_POST["iduser"] : '';
    $id             = isset($_GET["iduser"])        ? $_GET["iduser"] : $id;  //astuce pour récupérer l'id en POST ou GET (GET PRIORITARE
    
    
    //recuperation et traitement des variables
    $date           = isset($_POST["date_inscription"]) ? $_POST["date_inscription"] : '';
    $sexe           = isset($_POST["sexe"])         ? $_POST["sexe"] : '';
    $nom            = isset($_POST["nom"])          ? $_POST["nom"] : '';
    $prenom         = isset($_POST["prenom"])       ? $_POST["prenom"] : '';
    $jour           = isset($_POST["jour"])         ? $_POST["jour"] : '';
    $mois           = isset($_POST["mois"])         ? $_POST["mois"] : '';
    $annee          = isset($_POST["annee"])        ? $_POST["annee"] : '';
    $adresse        = isset($_POST["adresse"])      ? $_POST["adresse"] : '';
    $epn            = isset($_POST["epn"])          ? $_POST["epn"] : '';
    
    $tel            = isset($_POST["tel"])          ? $_POST["tel"] : '';
    $telport        = isset($_POST["telport"])      ? $_POST["telport"] : '';
    //trim($_POST["tel"])."/".trim($_POST["telport"]);
   
    $mail           = isset($_POST["mail"])         ? $_POST["mail"] : '';
    
    $csp            = isset($_POST["csp"])          ? $_POST["csp"] : 14; // 14: non renseigné
    $equipement     = isset($_POST["equipement"])   ? implode("-", $_POST["equipement"]) : 0;
    $utilisation    = isset($_POST["utilisation"])  ? $_POST["utilisation"] : 0;
    $connaissance   = isset($_POST["connaissance"]) ? $_POST["connaissance"] : 0;
    $info           = isset($_POST["info"])         ? $_POST["info"] : '';
    $login          = isset($_POST["login"])        ? $_POST["login"] : '';
    $pass           = isset($_POST["passw"])        ? $_POST["passw"] : '';
    $status         = isset($_POST["status"])       ? $_POST["status"] : '';
    $tarif          = isset($_POST["tarif"])        ? $_POST["tarif"] : '';
    $lastvisit      = isset($_POST["date_inscription"]) ? $_POST["date_inscription"] : '';
    $temps          = isset($_POST["temps"])        ? $_POST["temps"] : '';

    //date de renouvellement adhesion automatiquement crée
    $daterenouv     = date_create($date);
    date_add($daterenouv, date_interval_create_from_date_string('365 days'));
    $daterenouv     = date_format($daterenouv, 'Y-m-d');
    $newsletter     = "0";
      
    
    $idVille          = isset($_POST["ville"]) ? $_POST["ville"] : '';
    $codepost       = isset($_POST["codepostal"]) ? $_POST["codepostal"] : '';
    $commune        = isset($_POST["commune"]) ? $_POST["commune"] : '';
    $pays           = isset($_POST["pays"]) ? $_POST["pays"] : '';

    if ($act == 2 ) { //suppression
        
        delUserInsc($id);
        header("Location:index.php?a=24&mesno=27");
    }    
    else {
        
        if (isset($_POST["submit"])) {

            //1 ajout de la ville en plus si besoin
            if ($idVille == 0) {
                $newcity = Ville::creerVille($commune, $codepost, $pays);
                if ($newcity == null) {
                    $mess = getError(0);
                    $idVille = 0;
                }
                else {
                    $idVille = $newcity->getId();
                }
            }
            else {
                $idVille    =  $_POST["ville"];
            }
          
            if (Utilisateur::existsLogin($login)) {
                $mess = getError(5);
            }
            else {
                if ($nom == '' || $prenom == '' || $annee == '' || $adresse == '' || $login == '' ) {
                    $mess = getError(4);
                    exit;
                }
                else {       
                    //insertion du nouvel utilisateur
                    $dateNaissance = $annee . "-" . $mois . "-" . $jour;

                    $utilisateur = Utilisateur::creerUtilisateur(
                                        $date,
                                        $nom,
                                        $prenom,
                                        $sexe,
                                        $dateNaissance,
                                        $adresse,
                                        intval($idVille),
                                        $tel,
                                        $mail,
                                        $temps,
                                        $login,
                                        $pass,
                                        intval($status),
                                        $lastvisit,
                                        $csp,
                                        $equipement,
                                        $utilisation,
                                        $connaissance,
                                        $info,
                                        $tarif,
                                        $daterenouv,
                                        intval($epn),
                                        $newsletter);
                    // $iduser = addUser($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$idVille,$tel,$mail,$temps,$login,$pass,$status,$lastvisit,$csp,$equipement,$utilisation,$connaissance,$info,$tarif,$daterenouv,$epn,$newsletter);
                    //enlever le preinscription
                    if ($utilisateur == null) {
                        $mess = getError(0);
                    }
                    else {    
                        delUserInsc($id);
                        header("Location:index.php?a=1&b=2&iduser=" . $utilisateur->getId());
                    }
                }
            }
        }
    }
?>