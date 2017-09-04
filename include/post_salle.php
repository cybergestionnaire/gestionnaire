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


  include/post_materiel.php V0.1
 */
require_once("include/class/Salle.class.php");
// fichier de recuperation des variables du formulaire materiel

$act = isset($_GET["act"]) ? $_GET["act"] : '';
$idSalle = isset($_GET["idsalle"]) ? $_GET["idsalle"] : '';

$nom = isset($_POST["nom"]) ? $_POST["nom"] : '';
$espace = isset($_POST["espace"]) ? $_POST["espace"] : '';
$comment = isset($_POST["comment"]) ? $_POST["comment"] : '';

if ($act != "" and $act != 3) { // verife si non vide
    // Traitement des champs a insérer
    if ($nom == '' || $espace == '') {
        $mess = getError(4);
    } else {
        switch ($act) {
            case 1:   // ajout d'une salle
                $salle = Salle::creerSalle($nom, intval($espace), $comment);
                if ($salle == null) {
                    header("Location: ./index.php?a=44&mesno=0");
                } else {
                    header("Location: ./index.php?a=44");
                }
                break;
            case 2:   // modifie une salle
                $salle = Salle::getSalleById($idSalle);
                if ($salle != null && $salle->modifier($nom, $espace, $comment)) {
                    header("Location: ./index.php?a=44");
                } else {
                    header("Location: ./index.php?a=44&mesno=0");
                }
                break;
        }
    }
}

if ($act == 3) { // supprime une salle
    $salle = Salle::getSalleById($idSalle);
    $errno = $salle->supprimer();

    switch ($errno) {
        case 0: // impossible de joindre la base
            header("Location:index.php?a=44&mesno=0");
            break;
        case 1:// des postes sont dans la salle
            header("Location:index.php?a=44&mesno=51");
            break;
        case 2: //reussi
            header("Location:index.php?a=44&mesno=14");
            break;
    }
}
