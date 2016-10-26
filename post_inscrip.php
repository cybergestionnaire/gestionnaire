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
    if(isset($_POST["submit"])) {

        $_SESSION['sauvegarde'] = $_POST;
        //debug($_POST);
        //recuperation et traitement des variables oligatoires
        
        $sexe     = $_POST["sexe"];
        $nom      = $_POST["nom"];
        $prenom   = $_POST["prenom"];
        $jour     = $_POST["jour"];
        $mois     = $_POST["mois"];
        $annee    = $_POST["annee"];
        $adresse  = $_POST["adresse"];
         
        $ville    = $_POST["ville"];
        $tel      = $_POST["tel"];
        $telport  = $_POST["telport"];
        $mail     = $_POST["mail"];
        
        $epn      = $_POST["epn"];
         
        $captcha  = $_POST["g-recaptcha-response"];

        //recuperation donnes optionnelles
        $csp          = isset($_POST["csp"])          ? $_POST["csp"] : 14; // 14 : non renseigné
        $equipement   = isset($_POST["equipement"])   ? implode("-", $_POST["equipement"]) : 0;
        $commune      = isset($_POST["commune"])      ? $_POST["commune"] : "vide";
        $codepostal   = isset($_POST["codepostal"])   ? $_POST["codepostal"] : "vide";
        $pays         = isset($_POST["pays"])         ? $_POST["pays"] : "vide";
        $utilisation  = isset($_POST["utilisation"])  ? $_POST["utilisation"] : 0;
        $connaissance = isset($_POST["connaissance"]) ? $_POST["connaissance"] : 0;
        $info         = isset($_POST["info"])         ? $_POST["info"] : '';
        
        $urlRedirect  = "validation.php?epn=" . $epn ; //puis redirection vers url de page neutre des infos de l'epn
      
      
        $date         = date('Y-m-d'); //date d'inscription
        $status       = 2; //statut inactif par defaut
        
        $temps        = 1; //tarif consultation par defaut = sans tarif !
        //login et mot de passe provisoires
        $loginn       = $nom;
        $passs        = $prenom;
        
         // Traitement des champs a insérer
        //if (!$sexe || !$nom || !$prenom || !$annee || !$mois || !$jour || !$adresse || !$mail || !$epn || !$captcha) {
        if (!$sexe || !$nom || !$prenom || !$annee || !$mois || !$jour || !$adresse || !$mail || !$epn) {
            $mess = getError(4);
        }
        else {
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $mess = getError(48); //format mail invalide
            }
            else {
                if (!checkdate($mois, $jour, $annee)) {
                    $mess = getError(49); //date invalide
                }
                else {
                    if (FALSE == addUserinscript($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$pays,$codepostal,$commune,$ville,$tel,$telport,$mail,$temps,$loginn,$passs,$status,$csp,$equipement,$utilisation,$connaissance, $info,$epn)) {
                        $mess = getError(0);
                    }
                    else { 
                        header("Location:".$urlRedirect."");
                    }
                }
            }
        }
    }
?>