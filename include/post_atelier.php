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

  include/post_atelier.php V0.1
 */
// error_log("---- _POST ----");
// error_log(print_r($_POST, true));
// error_log("---- _GET ----");
// error_log(print_r($_GET, true));

require_once("include/class/Atelier.class.php");
require_once("include/class/StatAtelierSession.class.php");

$idAtelier = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '';
if (isset($_POST["submit_atelier"]) && $_POST["submit_atelier"] != "") {  // si le formulaire est posté
    $m = $_GET["m"];

    $date = isset($_POST["date"]) ? $_POST["date"] : '';
    $heure = isset($_POST["heure"]) ? $_POST["heure"] : '';
    $nbplace = isset($_POST["nbplace"]) ? $_POST["nbplace"] : '';
    $idSujet = isset($_POST["sujet"]) ? $_POST["sujet"] : '';
    $public = isset($_POST["public"]) ? $_POST["public"] : '';
    $idAnim = isset($_POST["anim"]) ? $_POST["anim"] : '';
    $duree = isset($_POST["duree"]) ? $_POST["duree"] : '';
    $stateAtelier = isset($_POST["statut"]) ? $_POST["statut"] : '';
    $idSalle = isset($_POST["salle"]) ? $_POST["salle"] : '';
    $idTarif = isset($_POST["tarif"]) ? $_POST["tarif"] : '';

    //debug($m);
    //debug($sujet);
    if ($m != "" and $m != 3) {  // verife si non vide
        if ($date == '' || $heure == '' || $idSujet == '' || $nbplace == '') {
            $mess = getError(4);
        } else {
            switch ($m) {
                case 1:   // ajout planification d'un poste
                    $atelier = Atelier::creerAtelier($date, $heure, $duree, intval($idAnim), intval($idSujet), $nbplace, $public, $stateAtelier, intval($idSalle), intval($idTarif), 0, 0);
                    if ($atelier == null) {
                        header("Location: ./index.php?a=11&mesno=0");
                    } else {
                        /*
                          // insertion de la relation usage de poste (resa=1 ou atelier=2) EN MODIF
                          $minute = str_split($heure);
                          $min = 0;
                          if ($minute[1] >= 0 and $minute[1] < 4) {
                          $min=substr_replace(date('i'),"0",1,1);
                          }
                          else if ($minute[1] > 3 and $minute[1] < 8) {
                          $min=substr_replace(date('i'),"5",1,1);
                          }
                          else if ($minute[1] > 7) {
                          $minu=($minute[0]+1)."0";
                          $min=substr_replace(date('i'),$minu,0,2);
                          }

                          $heurer = date('G') * 60 + $min;

                          //retrouver les id des ordis dans la salle et inserer pour la resa
                          for ($nbcomp = 0 ; $nbcomp < $nbplace ; $nbcomp++) {
                          $idresarel=addResa($idcomp,$anim ,$date,$heurer,$duree);
                          }
                          insertrelresa($idresarel, 2);

                          ///
                         */
                        //rajouter la relation des computers à libérer pour epnconnect
                        if (false == connectAtelierComputer($idSalle, $idAtelier)) {
                            header("Location: ./index.php?a=11&mesno=0");
                        } else {
                            header("Location: ./index.php?a=11&mesno=14");
                        }
                    }
                    break;

                case 2:   // modifie un poste
                    $atelier = Atelier::getAtelierById($idAtelier);
                    if ($atelier->modifier($date, $heure, $duree, $idAnim, $idSujet, $nbplace, $public, $stateAtelier, $idSalle, $idTarif, $atelier->getStatus(), $atelier->getCloturer())) {
                        // modifier la rel aussi !! duree/postes/heure/date
                        if ($stateAtelier == 3) { //en cas d'annulation d'atelier, l'inscrire dans les stats
                            $statAtelier = StatAtelierSession::getStatAtelierByIdAtelier($atelier->getId());
                            if ($statAtelier === null) {
                                $statAtelier = StatAtelierSession::creerStatAtelierSession('a', $idAtelier, $atelier->getDate(), $atelier - getNbUtilisateursInscritsOuPresents(), 0, 0, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 2, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                            } else {
                                $statAtelier->modifier('a', $idAtelier, $atelier->getDate(), $atelier - getNbUtilisateursInscritsOuPresents(), 0, 0, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 2, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                            }
                        }
                        header("Location: ./index.php?a=11&mesno=14");
                    } else {
                        header("Location: ./index.php?a=11mesno=0");
                    }
                    break;
            }
        }
    }
}

// Si le bouton supprimé est posté
if ($m == 4) { // supprime un atelier
    $atelier = Atelier::getAtelierById($idAtelier);

    if ($atelier->supprimer()) {
        header("Location: ./index.php?a=11&mesno=14");
        // }
    } else {
        header("Location: ./index.php?a=11&mesno=0");
    }
}
