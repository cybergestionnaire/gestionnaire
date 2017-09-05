<?php

//header("Content-Type: text/html; charset=iso-8859-1");
header("Content-Type: text/plain");

/* echo '<script type="text/javascript">window.alert("test");</script>'; */

if (isset($_GET["action"])) {
    if (!empty($_GET["action"])) {
        include("../connect_db.php");

        if ($port == "" or false == is_numeric($port)) {
            $port = "3306";
        }

        /* creation de la liaison avec la base de donnees */
        $db = mysqli_connect($host, $userdb, $passdb, $database);

        $action = $_GET["action"];

        if ($action == 1) { //affectation usagers
            if (isset($_GET["id_poste"]) && !empty($_GET["id_poste"])) {
                $id_poste = $_GET["id_poste"];
            } elseif (isset($_GET["id_user"]) && !empty($_GET["id_user"])) {
                $id_user = $_GET["id_user"];
            } elseif (isset($_GET["dureeaffect"]) && !empty($_GET["dureeaffect"])) {
                $dureeaffect = $_GET["dureeaffect"];
            } elseif (isset($_GET["id_atelier"]) && !empty($_GET["id_atelier"])) {
                $id_atelier = $_GET["id_atelier"];
            } elseif (isset($_GET["message"]) && !empty($_GET["message"])) {
                $message = $_GET["message"];
            }

            $date = date("Y-m-d");
            $heure = date("H");
            $minute = date("i");
            $debut = (($heure * 60) + $minute);
            $sql = "INSERT INTO `tab_resa` (`id_resa`, `id_computer_resa`, `id_user_resa`, `dateresa_resa`,`debut_resa`, `duree_resa`, `date_resa`, `status_resa`) VALUES('', '" . $id_poste . "', '" . $id_user . "', '" . $date . "', '" . $debut . "', '" . $duree . "', '" . $date . "', '0');";
            $result = mysqli_query($db, $sql);
            //$result = $db->query($sql);
        } elseif ($action == 2) {//liberation usagers
            if (isset($_GET["id_poste"]) && !empty($_GET["id_poste"])) {
                $id_poste = $_GET["id_poste"];
            }
            //recherche id_resa
            $sql = "SELECT `id_resa`, `dateresa_resa`, `debut_resa` FROM tab_resa WHERE `id_computer_resa`='" . $id_poste . "' AND `status_resa`='0' LIMIT 1;";
            $result = $db->query($sql);

            $row = mysqli_fetch_array($result);

            //poste occupe
            $dateresa = $row["dateresa_resa"];
            $heureresa = $row["debut_resa"];    //heure resa en minute
            $datereel = date("Y-m-d");
            $heure = date("H");
            $minute = date("i");
            $heurereel = (($heure * 60) + $minute); //heure reelle en minute

            $nbSecondes = 60 * 60 * 24;
            $debut_ts = strtotime($dateresa);
            $fin_ts = strtotime($datereel);
            $diff = $fin_ts - $debut_ts;
            $diffjour = round($diff / $nbSecondes);   //difference de jour entre date resa et date reel (en jour)
            //jour meme
            if ($diffjour == 0) {
                $dureetotal = $heurereel - $heureresa;  //duree total en minutes
            }
            //lendemain
            elseif ($diffjour == 1) {
                if ($heurereel < $heureresa) { //moins de 24 h
                    $duree1 = 1439 - $heureresa;    //temps j1 (en minutes)
                    $dureetotal = $duree1 + $heurereel; //duree total en minutes
                } else {//plus de 24h
                    $duree1 = $heureel - $heureresa;    //duree en minutes
                    $dureetotal = $duree1 + 1440;       //duree total en minutes
                }
            }
            //jour d'apres
            elseif ($diffjour > 1) {
                if ($heurereel < $heureresa) {  //moins de 24 h
                    $diffjour--;    //on retire un jour non revolu
                    $duree1 = 1439 - $heureresa;    //temps j1 en minutes
                    $duree2 = $duree1 + $heurereel; //temps j2 en minutes
                    $dureetotal = (($diffjour * 1440) + $duree2); //duree total en minutes
                } else {//plus de 24h
                    $duree1 = $heurereel - $heureresa;  //duree en minutes
                    $dureetotal = ($duree1 + ($diffjour * 1440)); //duree total en minutes
                }
            }

            $sql = "UPDATE `tab_resa` SET `duree_resa` = '" . $dureetotal . "', `status_resa`='1' WHERE `id_resa`= '" . $row["id_resa"] . "';";
            $result = mysqli_query($db, $sql);
            //$result = $db->query($sql);
        }
        mysqli_close($db);
    }
}
