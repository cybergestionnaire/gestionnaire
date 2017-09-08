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
/*
  2006 Namont Nicolas
  2012 Florence DAUVERGNE
 */
// error_log("GET : " . print_r($_GET, true));
// error_log("POST : " . print_r($_POST, true));

//require_once("include/class/SessionSujet.class.php");

$s = isset($_GET["s"]) ? $_GET["s"] : '';
$id = isset($_GET["idSujet"]) ? $_GET["idSujet"] : '';

if ($s == "del") {//suppression du sujet de la session
    //delSujetSession($id);
    $sessionSujet = SessionSujet::getSessionSujetById($id);
    if ($sessionSujet !== null) {
        $sessionSujet->supprimer();
        header("Location:./index.php?a=29&mesno=24");
    }
}

if (isset($_POST["submit_session"])) {
    $idNiveau = isset($_POST["niveau"]) ? $_POST["niveau"] : '';
    $idCategorie = isset($_POST["categorie"]) ? $_POST["categorie"] : '';
    $sujet = isset($_POST["label_session"]) ? $_POST["label_session"] : '';
    $content = isset($_POST["content"]) ? $_POST["content"] : '';
    $idSujet = isset($_POST["idSujet"]) ? $_POST["idSujet"] : '';


    if ($s == "new") {//creation du sujet de la session
        if ($sujet == "" || $idCategorie == "") {
            $mesno = 4;
        } else {
            // if (FALSE == createSession($sujet,$content,$idNiveau,$idCategorie)) {

            $sessionSujet = SessionSujet::creerSessionSujet($sujet, $content, $idNiveau, $idCategorie);

            if ($sessionSujet !== null) {
                header("Location:./index.php?a=29&mesno=23");
            } else {
                $mesno = 0;
            }
        }
    }

    if ($s == "mod") { //modification du sujet de la session
        if ($sujet == "") {
            $mesno = 4;
        } else {
            $sessionSujet = SessionSujet::getSessionSujetById($idSujet);

            if ($sessionSujet->modifier($sujet, $content, $idNiveau, $idCategorie)) {
                header("Location:./index.php?a=29&mesno=22");
            } else {
                $mesno = 0;
            }
            //ModifSujetsession($id,$sujet,$content,$idNiveau,$idCategorie);
        }
    }
}
