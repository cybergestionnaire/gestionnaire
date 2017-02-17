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
    
 2006 Namont Nicolas
 2013 

 
*/
    // error_log("GET : " . print_r($_GET, true));
    // error_log("POST : " . print_r($_POST, true));

    require_once("include/class/AtelierSujet.class.php");

    $b       = isset($_GET['b']) ? $_GET['b'] : '';
    $idSujet = isset($_GET['idSujet']) ? $_GET['idSujet'] : '';
    $idSujet = isset($_POST['idSujet']) ? $_POST['idSujet'] : $idSujet;

    $submit  = isset($_POST["submit_atelier"]) ? $_POST["submit_atelier"] : '' ;
    
    if ($submit != "") { // on utilise le formulaire
        $idniveau    = isset($_POST["niveau"]) ? $_POST["niveau"] : '' ;
        $idcategorie = isset($_POST["categorie"]) ? $_POST["categorie"] : '' ;
    
        $sujet       = isset($_POST["label_atelier"]) ? $_POST["label_atelier"] : '' ;
        $content     = isset($_POST["content"]) ? $_POST["content"] : '' ;
        $ressource   = isset($_POST["ressource"]) ? $_POST["ressource"] : '' ;

        if ($b == 11) {
            //error_log("--- modification ! ");
            if ($sujet == "" || $content == "") {
                $mesno = 4 ;
            }
            else {
                //error_log("--- modification : champs ok ! ");
                $atelierSujet = AtelierSujet::getAtelierSujetById($idSujet);
                
                if ($atelierSujet->modifier($sujet, $content, $ressource, $idniveau, $idcategorie)) {
                    header ("Location:index.php?a=17&mesno=22");
                }
                else {
                    $mesno = 0;
                }
            }
        }
        
        if ($b == 12) {
            if ($sujet == "" || $content == "") {
                $mesno = 4 ;
            }
            else {
                $atelierSujet = AtelierSujet::creerAtelierSujet($sujet, $content, $ressource, $idniveau, $idcategorie);
                if ($atelierSujet !== null) {
                    header ("Location:index.php?a=17&mesno=20");
                }
                else {
                    $mesno = 0;
                }
            }
        }

    }
    if ($b == 13) {
        //debug($id);
        $atelierSujet = AtelierSujet::getAtelierSujetById($idSujet);
        error_log(print_r($atelierSujet, true));
        // delSujetAtelier($idSujet);// suppression du sujet d'atelier dans la base
        if ($atelierSujet->supprimer()) {
            header ("Location:index.php?a=17&mesno=24");
        }
        else {
            $mesno = 0;
        }
    }


?>
