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


  include/post_animateur.php V0.1
 */
//require_once("include/class/Utilisateur.class.php");
// error_log("----- POST -----");
// error_log(print_r($_POST, true));
// error_log("----- GET -----");
// error_log(print_r($_GET, true));
// $b       =  $_GET["b"];
$idAnim = isset($_GET["idanim"]) ? $_GET["idanim"] : '';

//recuperation et traitement des variables
$avatar_r = isset($_POST["avatar_r"]) ? $_POST["avatar_r"] : '';
$idEspace = isset($_POST["epn_r"]) ? $_POST["epn_r"] : '';
$salles_r = array();
$salles_r = isset($_POST["salle_r"]) ? $_POST["salle_r"] : array();
$salles = implode(";", $salles_r);

$animateur = Utilisateur::getUtilisateurById($idAnim);
// les salles et l'epn de rattachement
if ($idEspace != '') {
    // on a un POST
    if ($salles == '') {
        //erreur : pas de salle sélectionnée
        $mess = getError(13);
    } else {
        if ($animateur->setParametresAnim($idEspace, $salles, $avatar_r)) {
            header("Location:index.php?a=50&b=2&mess=ok&idanim=" . $idAnim);
        } else {
            header("Location:index.php?a=50&idanim=" . $idAnim . "&mesno=50");
        }
    }
}
