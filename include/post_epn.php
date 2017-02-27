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
 

 include/post_epn.php V0.1
*/
require_once("include/class/Espace.class.php");

// fichier de recuperation des variables du formulaire espace
$b   =  isset($_GET['b'])   ? $_GET['b']   : '';
$act =  isset($_GET['act']) ? $_GET['act'] : '';

//recuperation du formulaire epn
if ($b == 1 OR $b == 2) {
    $idEspace   = isset($_GET["idespace"])      ? $_GET["idespace"]   : '';
    $nom        = isset($_POST["nom"])          ? $_POST["nom"]       : '';
    $adresse    = isset($_POST["adresse"])      ? $_POST["adresse"]   : '';
    $ville      = isset($_POST["ville"])        ? $_POST["ville"]     : '';
    $tel        = isset($_POST["telephone"])    ? $_POST["telephone"] : '';
    $fax        = isset($_POST["fax"])          ? $_POST["fax"]       : '';
    $couleur    = isset($_POST["ecouleur"])     ? $_POST["ecouleur"]  : '';
    $logoespace = isset($_POST["elogo"])        ? $_POST["elogo"]     : '';
    $mail       = isset($_POST["mail"])         ? $_POST["mail"]      : '';
} else if ($b == 3 OR $b == 4) {
    $nom        = isset($_POST["nomreseau"])     ? $_POST["nomreseau"]     : '';
    $adresse    = isset($_POST["adressereseau"]) ? $_POST["adressereseau"] : '';
    $ville      = isset($_POST["villereseau"])   ? $_POST["villereseau"]   : '';
    $tel        = isset($_POST["telreseau"])     ? $_POST["telreseau"]     : '';
    $logo       = isset($_POST["logoreseau"])    ? $_POST["logoreseau"]    : '';
    $mail       = isset($_POST["mailreseau"])    ? $_POST["mailreseau"]    : '';
    $courrier   = isset($_POST["courriers"])     ? $_POST["courriers"]     : '';
    $activation = isset($_POST["activation"])    ? $_POST["activation"]    : '';
    
}

//b=1 b=2 pour les espaces, b=3 b=4 pour le reseau

if ($act != "" AND $act != 3) { // verife si non vide
  // Traitement des champs a insérer
    if (!$nom || !$ville || !$mail || !$adresse ) {
       $mess = getError(4);
    } else {
        switch($act) {
            case 1:   // ajout d'un epn
                $nouvelEspace = Espace::creerEspace($nom, $adresse, intval($ville), $tel, $fax, $logoespace, $couleur, $mail) ;
                if ($nouvelEspace == null) {
                    header("Location: ./index.php?a=43&mesno=0");
                } else {
                    $nouvelEspace->copyHoraires();
                    $nouvelEspace->copyConfig('0');
                    $nouvelEspace->copyConfigLogiciel();

                    header("Location: ./index.php?a=43&mesno=14");
                }
                break;
                        
            case 2:   // modifie un espace
                $espaceAModifier = Espace::getEspaceById($idEspace);
                if ($espaceAModifier != null && $espaceAModifier->modifier($nom,$adresse,$ville,$tel,$fax,$logoespace,$couleur,$mail)) {
                    header("Location: ./index.php?a=43&mesno=14");
                } else {
                    header("Location: ./index.php?a=43&mesno=0");
                }
                break;
                        
            
            case 4: // modification du nom du reseau par defaut
                if (FALSE == modreseau($nom,$adresse,$ville,$tel,$mail,$logo,$courrier,$activation)) {
                    echo getError(0);
                } else {
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
    $idEspace =  $_GET["idespace"];
    $espaceASupprimer = Espace::getEspaceById($idEspace);
    $errno = $espaceASupprimer->supprimer() ;
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