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
// error_log('in post_print.php');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));

require_once "include/class/Utilisateur.class.php";
require_once "include/class/Transaction.class.php";
require_once "include/class/Impression.class.php";

/* fichier d'envoi des données transactions à la base */

$act = isset($_GET["act"]) ? $_GET["act"] : "";

if ($act != "" and $act != 3) { // verife si non vide
    $id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : "";
    // $id_transac = isset($_GET["idtransac"]) ? $_GET["idtransac"] : "";
    $idsTarif = isset($_POST["printUIdTarif"]) ? $_POST["printUIdTarif"] : "";
    $debits = isset($_POST["printUDebit"]) ? $_POST["printUDebit"] : "";
    $prixs = isset($_POST["printUPrix"]) ? $_POST["printUPrix"] : "";
    $epn_p = $_SESSION["idepn"];
    $caissier = $_SESSION["iduser"];

    $Gprint = null;

    //Ajout de l'utilisateur externe
    if (isset($_GET["ext"]) && $_GET["ext"] == 1) {
        if (isset($_POST["nomuser"])) {
            $nomuser_p = $_POST["nomuser"];
        } else {
            $mess = getError(4);
            //exit;
        }
    } else {
        $nomuser_p = '0';
    }


    switch ($act) {
        case 1:   // ajout d'une transaction
            $nbt = count($idsTarif);
            $credit = isset($_POST["credit"]) ? $_POST["credit"] : 0; //ce qui a ete encaissé
            $date_p = isset($_POST["date"]) ? $_POST["date"] : "";
            $moyen_p = isset($_POST["moyen_paiement"]) ? $_POST["moyen_paiement"] : "";
            $paiement = isset($_POST["paiement"]) ? $_POST["paiement"] : "";

            if ($credit > 0) {
                //on boucle sur les tarifs
                for ($i = 0; $i < $nbt; $i++) {
                    if ($_POST["submit"] == "Encaisser") {
                        $statut_p = "1";
                    } else {
                        $statut_p = "0";
                    }

                    if ($debits[$i] > "0") {
                        $Gprint = Impression::creerImpression($date_p, $id_user, $debits[$i], $idsTarif[$i], $statut_p, $prixs[$i], $nomuser_p, $epn_p, $caissier, $moyen_p);
                    }
                }
                //rajouter le credit, regulariser le credit en cas de somme differente
                $du = isset($_POST["du"]) ? $_POST["du"] : "";
                ; //ce qui doit etre payé
                $credit = $credit - $du; // ce qui reste en plus apres deduction de la somme due
                $Gprint = Impression::creerImpression($date_p, $id_user, 0, 0, 2, $credit, $nomuser_p, $epn_p, $caissier, $moyen_p);
            } else {
                $totalPaye = 0;
                //sinon boucle normale, on ajoute le credit en fonction du tarif
                for ($i = 0; $i < $nbt; $i++) {

                    //remise a zero si le credit est deja positif sur le compte et est superieur au total depense
                    // if ($paiement == 0) {
                    // $credit_p = 0;
                    // } else {
                    // $credit_p = $prixs[$i];
                    // }

                    if ($_POST["submit"] == "Encaisser") {
                        $statut_p = "1";
                    } else {
                        $statut_p = "0";
                    }

                    if ($debits[$i] > "0") {
                        $totalPaye = $totalPaye + $prixs[$i];
                        $Gprint = Impression::creerImpression($date_p, $id_user, $debits[$i], $idsTarif[$i], $statut_p, $prixs[$i], $nomuser_p, $epn_p, $caissier, $moyen_p);
                    }
                }
                $du = isset($_POST["du"]) ? $_POST["du"] : ""; //ce qui doit etre payé
                $reliquat = $du - $totalPaye; // ce qui reste en plus apres deduction de la somme due
                $Gprint = Impression::creerImpression($date_p, $id_user, 0, 0, 2, $reliquat, $nomuser_p, $epn_p, $caissier, $moyen_p);
            }

            if ($Gprint !== null) {
                header("Location: ./index.php?a=21&mesno=0");
            } else {
                header("Location: ./index.php?a=21&b=1&act=&iduser=" . $id_user);
            }

            break;

        case 4: //crediter le compte d'impression
            $credit_p = isset($_POST["credit"]) ? $_POST["credit"] : "";
            $date_p = isset($_POST["datec"]) ? $_POST["datec"] : "";
            $moyen_p = isset($_POST["moyen_paiement"]) ? $_POST["moyen_paiement"] : "";
            //statut 2 == credit uniquement
            if (isset($credit_p)) {
                if (Impression::creerImpression($date_p, $id_user, 0, 0, 2, $credit_p, $nomuser_p, $epn_p, $caissier, $moyen_p) !== null) {
                    header("Location: ./index.php?a=21&mesno=0");
                } else {
                    header("Location: ./index.php?a=21&b=1&iduser=" . $id_user . "");
                }
            }
            break;
    }
}
