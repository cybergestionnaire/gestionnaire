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

  2006 Namont Nicolas (CyberMin)

 */

// POST de Configuration de l'espace

require_once("include/class/Horaire.class.php");

function updateresaconfig($epnr, $unitconfig, $maxtime_config, $resarapide, $duree_resarapide)
{
    $sql = "UPDATE `tab_config` SET 
                `unit_config`='" . $unitconfig . "',
                `maxtime_config`='" . $maxtime_config . "',
                `resarapide`='" . $resarapide . "',
                `duree_resarapide`='" . $duree_resarapide . "' WHERE `id_espace`=" . $epnr;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

if (isset($_POST["submit"]) && $_POST["submit"] != "") {
    $epnr = $_POST["epn_r"];

    switch ($_POST["form"]) {
        case 1: //choix epn pour en modifier les horaires
            header("Location:index.php?a=42&epnr=" . $epnr);
            break;

        case 2: //horaire
            $horaires = Horaire::getHorairesByIdEspace(intval($epnr));

            for ($i = 1; $i < 8; $i++) {
                if ($horaires[$i - 1]->modifier($_POST[$i . "-h1begin"], $_POST[$i . "-h1end"], $_POST[$i . "-h2begin"], $_POST[$i . "-h2end"])) {

                    //updateConfig("tab_horaire", $update, "jour_horaire", $i, $epnr) ;
                    // header("Location:index.php?a=42&mess=ok&epnr=".$epnr) ;
                } else {
                    header("Location:index.php?a=42&mess=Hwrong&dayline=" . $i);
                    exit;
                }
            }
            break;

        case 3: //reservation minimum
            //$update = array() ;
            $unitconfig = $_POST["unit"];
            $maxtime_config = $_POST["maxtime"];
            $resarapide = $_POST["resarapide"];
            $duree_resarapide = $_POST["duree_resarapide"];


            if (true == updateresaconfig($epnr, $unitconfig, $maxtime_config, $resarapide, $duree_resarapide)) {
                header("Location:index.php?a=42&mess=ok&epnr=" . $epnr);
            } else {
                header("Location:index.php?a=42&mess=Hwrong&epnr=" . $epnr);
            }

            break;
    }

    //header("Location:index.php?a=42&mess=ok") ;
}
