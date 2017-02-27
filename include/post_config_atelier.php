<?php
/*
    This file is part of CyberGestionnaire.

    CyberGestionnaire is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire.  If not, see <http://www.gnu.org/licenses/>
 
*/
    //error_log("GET : " . print_r($_GET, true));
    //error_log("POST : " . print_r($_POST, true));

    
    require_once("include/class/CSP.class.php");
    require_once("include/class/AtelierCategorie.class.php");
    require_once("include/class/AtelierNiveau.class.php");
    // Configuration des cat&eacute;gories modifiables pour les statistiques

    // traitement des post
    $act      = isset($_GET["act"]) ? $_GET["act"] : '';

    $idcat    = isset($_GET["idcat"]) ? $_GET["idcat"] : '';
    $idniveau = isset($_GET["idniveau"]) ? $_GET["idniveau"] : '';
    $idcsp    = isset($_GET["idcsp"]) ? $_GET["idcsp"] : '';

    $testcat  = isset($_POST["submitcat"]);
    $testniv  = isset($_POST["submitniv"]);
    $testcsp  = isset($_POST["submitcsp"]);
    
    switch ($act) {
        case 1: // creation
            error_log("----- cas n 1 : creation");

            $nom    = isset($_POST["newcat"])    ? $_POST["newcat"]    : '';
            $niveau = isset($_POST["newniveau"]) ? $_POST["newniveau"] : '';
            $newcsp = isset($_POST["newcsp"])    ? $_POST["newcsp"]    : '';
            if ($testcat != "") {
                if (AtelierCategorie::creerAtelierCategorie($nom) !== null) {
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            elseif ($testniv != "") {
                if (AtelierNiveau::creerAtelierNiveau('', $niveau) !== null) { // code_level inutilisé, mis à ''
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }

            }
            elseif ($testcsp != "") {
                if (CSP::creerCSP($newcsp) !== null) {
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            break;

        case 2: // modification
            error_log("----- cas n 2 : modification");
            $nom    = isset($_POST["categorie"]) ? $_POST["categorie"] : '';
            $niveau = isset($_POST["niveau"])    ? $_POST["niveau"]    : '';
            $modcsp = isset($_POST["csp"])       ? $_POST["csp"]       : '';
       
            if ($testcat != "") {
                $atelierCategorie = AtelierCategorie::getAtelierCategorieById($idcat);
                if ($atelierCategorie->modifier( $nom)) { 
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            elseif ($testniv != "") {
                $atelierNiveau = AtelierNiveau::getAtelierNiveauById($idniveau);
                if ($atelierNiveau->modifier('', $niveau)) {// code_level inutilisé, mis à ''
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            elseif ($testcsp != "") {
                $csp = CSP::GetCSPById($idcsp);
                if ($csp->modifier($modcsp)) {
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
    
            break;
        
        case 3: // suppression
            error_log("----- cas n 3 : suppression");
            if ($idcat != "") {
                $atelierCategorie = AtelierCategorie::getAtelierCategorieById($idcat);
                if ($atelierCategorie->supprimer()){
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            elseif ($idniveau != "") {
                $atelierNiveau = AtelierNiveau::getAtelierNiveauById($idniveau);
                if ($atelierNiveau->supprimer()){
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            elseif ($idcsp != "") {
                $csp = CSP::GetCSPById($idcsp);
                if ($csp->supprimer()){
                    header("Location:index.php?a=7&mesno=14") ;
                } else {
                    $mesno = 0;
                }
            }
            break; 
    }





?>