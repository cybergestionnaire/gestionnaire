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

    2006 Namont Nicolas (Cybermin
 
*/
    require_once("include/class/Materiel.class.php");
    require_once("include/class/Usage.class.php");

    // fichier de recuperation des variables du formulaire materiel

    $act            =  isset($_GET["act"])              ? $_GET["act"] : '';
    $idMateriel     =  isset($_GET["idmat"])            ? $_GET["idmat"] : '';

    $nom            = isset($_POST["nom"])              ? $_POST["nom"] : '';
    $os             = isset($_POST["os"])               ? $_POST["os"] : '';
    $salle          = isset($_POST["salle"] )           ? $_POST["salle"] : '';
    $usage          = isset($_POST["usage"] )           ? $_POST["usage"] : '';
    $adresseIP      = isset($_POST["adresseIP"] )       ? $_POST["adresseIP"] : '';
    $adresseMAC     = isset($_POST["adresseMAC"] )      ? $_POST["adresseMAC"] : '';
    $nomhote        = isset($_POST["nomhotecomputer"] ) ? $_POST["nomhotecomputer"] : '';
    $fonctions      = isset($_POST["fonction"])         ? implode(";",$_POST["fonction"]) : '';
    $comment        = isset($_POST["comment"])          ? $_POST["comment"] : '';

    if (isset($_POST["submit"]) && $_POST["submit"] != "" ) {
        $epnr = isset($_POST["epn_r"]) ?$_POST["epn_r"] : '';
        header("Location:index.php?a=2&epnr=" . $epnr);
    }

    if ($act != "" AND $act != 3) { // verife si non vide
        // Traitement des champs a insérer
        if ($nom == '' || $salle == '') {
           $mess = getError(4);
        }
        else {
            switch($act) {
                case 1:   // ajout d'un poste
                    $materiel = Materiel::creerMateriel($nom, $os, $comment, $usage, $fonctions, intval($salle), $adresseIP, $adresseMAC, $nomhote) ;
            
                    if ($materiel == null) {
                        header("Location: ./index.php?a=2&mesno=0");
                    }
                    else {
                        $usages = Usage::getUsages();
                        if (isset($_POST["fonction"])) {
                            
                            $fonctionsArray = $_POST["fonction"];
                            
                            foreach ($fonctionsArray AS $key=>$value) {
                                $materiel->addUsageById($value);
                            }
                        }
                        header("Location: ./index.php?a=2&mesno=14");
                    }
                    break;
                
                case 2:   // modifie un poste
                    $materiel = Materiel::getMaterielById($idMateriel);
                    if ($materiel->modifier($nom, $os, $comment, $usage, $fonctions, intval($salle), $adresseIP, $adresseMAC ,$nomhote)) {
                        header("Location: ./index.php?a=2&mesno=14");
                    }
                    else {
                        header("Location: ./index.php?a=2&mesno=0");
                    }
                    break;
            }
        }
    }
    
    if ($act == 3) { // supprime un poste
        $materiel = Materiel::getMaterielById($idMateriel);
        if ($materiel->supprimer()) {
            header("Location: ./index.php?a=2");
        }
        else {
            header("Location: ./index.php?a=2&mesno=");
        }
    }
?>
