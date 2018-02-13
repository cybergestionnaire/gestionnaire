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

 */
// error_log('in post_breve.php -------------------------');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));
 
// Page de traitement du formulaire de breve
$act = (string)filter_input(INPUT_GET, "act");
 if ($act != "") {
 
    $act = (string)filter_input(INPUT_GET, "act");
    $id = (string)filter_input(INPUT_GET, "idbreve");

    $titr = (string)filter_input(INPUT_POST, "titr");
    $comment = (string)filter_input(INPUT_POST, "comment");
    $datenews = (string)filter_input(INPUT_POST, "datenews");
    $datepublish = (string)filter_input(INPUT_POST, "datepublish");
    $idEspace = (string)filter_input(INPUT_POST, "idepn");
    $type = (string)filter_input(INPUT_POST, "type");
    $visible = (string)filter_input(INPUT_POST, "visible");

    if ($act != 3) {  
        // Traitement des champs a insérer
        if (!$titr || !$comment) {
            $mess = getError(4);
        } else {
            switch ($act) {
                case 1:   // ajout d'un poste
                    $breve = Breve::creerBreve($titr, $comment, $visible, $type, $datepublish, $datenews, $idEspace);
                    
                    if ($breve === null) {
                        header("Location: ./index.php?a=4&mesno=0");
                    } else {
                        header("Location: ./index.php?a=4&mesno=14");   
                    }
//                    if (false == addBreve($titr, $comment, $visible, $type, $datepublish, $datenews, $idEspace)) {
//                        header("Location: ./index.php?a=4&mesno=0");
//                    } else {
//                        header("Location: ./index.php?a=4");
//                    }
                    break;
                case 2:   // modifie un poste
                    $breve = Breve::getBreveById($id);
                    
                    if ($breve->modifier($titr, $comment, $visible, $type, $datepublish, $datenews, $idEspace)) {
                        header("Location: ./index.php?a=4&mesno=14");
                    } else {
                        header("Location: ./index.php?a=4mesno=0");
                    }
//                    if (false == modBreve($id, $titr, $comment, $visible, $type, $datepublish, $datenews, $idEspace)) {
//                        header("Location: ./index.php?a=4mesno=0");
//                    } else {
//                        header("Location: ./index.php?a=4");
//                    }
                    break;
            }
        }
    }
    if ($act == 3) { 
        $breve = Breve::getBreveById($id);
        if ($breve->supprimer()) {
            header("Location: ./index.php?a=4&mesno=14");
        } else {
            header("Location: ./index.php?a=4&mesno=0");
        }
    }
 }