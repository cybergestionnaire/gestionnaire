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

//fonctions
// opendb ()
// connexion a la base de données
function opendb()
{
    include("./connect_db.php");

    if ($port == "" or ! is_numeric($port)) {
        $port = "3306";
    }

    /* creation de la liaison avec la base de donnees */
    $db = mysqli_connect($host, $userdb, $passdb, $database);
    /* en cas d'echec */
    if (mysqli_connect_errno()) {
        return false;
    } else {
        $db->set_charset("utf8");
        return $db;
    }
}

//
// closedb()
// fermeture de la connexion a la base de donnée
function closedb($mydb)
{
    mysqli_close($mydb);
}

//
// countUser()
// compte le nombre d'utilisateur actif ,inactifs , total
function countUser($id)
{
    switch ($id) {
        case 1: // TOTAL ACTIFS + INACTIFS
            $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`!=3 AND `status_user`!=4  ";
            break;
        case 2: // TOTAL ACTIFS
            $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=1";
            break;
        case 3: // TOTAL INACTIFS
            $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=2";
            break;
        case 4: // TOTAL ARCHIVES
            $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=6";
            break;
    }
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $nb = mysqli_num_rows($result);
        return $nb;
    }
}

//
// Fonction url ----------------------------------------------------------------
//
//
// checkBookmark()
// renvoi TRUE si le user a au moins un lien
function checkBookmark($id)
{
    $sql = "SELECT `id_url` FROM `tab_url` WHERE `iduser_url`=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        if (mysqli_num_rows($result) <= 0) {
            return false;
        } else {
            return true;
        }
    }
}

// getBookmark()
// renvoi TRUE si le user a au moins un lien
function getBookmark($id)
{
    if ($id != 0) {
        /* $sql = "SELECT `id_url`,`titre_url`,`url_url` ,
          (
          SELECT rub.label_url_rub
          FROM rel_url_rub AS rel
          INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
          WHERE rel.id_url = url.id_url
          ) AS Flabel
          FROM `tab_url`
          WHERE `iduser_url`=".$id."
          ORDER BY `titre_url` ASC" ; */
        $sql = "SELECT  url.id_url AS Fid, url.titre_url AS Ftitre, url.url_url AS Furl, 
                (
                    SELECT rub.label_url_rub
                    FROM rel_url_rub AS rel
                    INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
                    WHERE rel.id_url = url.id_url
                ) AS Flabel
                FROM tab_url AS url
                WHERE `iduser_url`=" . $id . " 
                ORDER BY Flabel ASC, Ftitre ASC";
    } else {
        $sql = "SELECT  url.id_url AS Fid, url.titre_url AS Ftitre, url.url_url AS Furl, 
                (
                    SELECT rub.label_url_rub
                    FROM rel_url_rub AS rel
                    INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
                    WHERE rel.id_url = url.id_url
                ) AS Flabel
                FROM tab_url AS url
                WHERE url.iduser_url=0
                ORDER BY Flabel ASC, Ftitre ASC";
        //$sql = "SELECT `id_url`,`titre_url`,`url_url` FROM `tab_url` WHERE `iduser_url`=0 ORDER BY `titre_url` ASC" ;
    }
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//
//
function getOneUrl($id)
{
    $sql = "SELECT U.titre_url, U.url_url, R.label_url_rub 
            FROM tab_url AS U
            INNER JOIN rel_url_rub AS RU ON RU.id_url = U.id_url
            INNER JOIN tab_url_rub AS R ON R.id_url_rub = RU.id_rub
            WHERE U.id_url = " . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_array($result);
    closedb($db);
}

//
// getUrlSelect
// renvoi le select contenant les rubrique d'url
function getUrlSelect()
{
    $sql = "SELECT * FROM `tab_url_rub` ORDER BY label_url_rub";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $var = '<select name="rubSel">';
        while ($row = mysqli_fetch_array($result)) {
            $var .= '<option value="' . $row['id_url_rub'] . '">' . $row['label_url_rub'] . '</option>';
        }
        $var .= "</select>";

        return $var;
    }
}

//
// addBokmark()
// ajoute un favoris dans la liste d'un utilisateur
function addBookmark($id, $titre, $url, $rubId = null, $rubName = false)
{
    $db = opendb();
    // Requete d'insertion du lien
    $sql = "INSERT INTO `tab_url` ( `id_url` , `iduser_url` , `titre_url` , `url_url` )
            VALUES ('', '" . $id . "', '" . $titre . "', '" . $url . "')";
    $result = @mysqli_query($db, $sql);
    $idUrl = @mysql_insert_id($db);

    //debut de creation de la requete d'insertion de la relation
    $sql3 = "INSERT INTO `rel_url_rub` (`id_url_rub`,`id_url`,`id_rub`)";

    // Requete de creation de la rubrique et execution si elle existe pas
    if (false != isset($rubName) and $rubName != "") {
        $sql2 = "INSERT INTO `tab_url_rub` (`id_url_rub`,`iduser_url_rub`,`label_url_rub`)
                 VALUES('','0','" . $rubName . "')";
        $result2 = @mysqli_query($db, $sql2);
        $idRub = @mysql_insert_id($db);
        $sql3 .= "VALUES ('','" . $idUrl . "','" . $idRub . "')";
    } else {
        $sql3 .= "VALUES ('','" . $idUrl . "','" . $rubId . "')";
    }
    $result3 = @mysqli_query($db, $sql3);

    closedb($db);

    if (false == $result or false == $result2 or false == $result3) {
        return false;
    } else {
        return true;
    }
}

// updateBookmark
// modifie certaines infos du bookmark
function updateBookmark($id, $name, $url)
{
    $sql = "UPDATE `tab_url` SET titre_url='" . $name . "' , url_url='" . $url . "' WHERE id_url ='" . $id . "' LIMIT 1";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

//
// delBokmark()
// supprime un favoris dans la liste d'un utilisateur
function delBookmark($iduser, $idurl)
{
    $sql = "SELECT `id_url` FROM `tab_url` WHERE `iduser_url`=" . $iduser . " AND `id_url`=" . $idurl;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (mysqli_num_rows($result) != 1) {
        return false;
    } else {
        $sql = "DELETE FROM `tab_url` WHERE `id_url`=" . $idurl;
        $db = opendb();
        $result = mysqli_query($db, $sql);
        closedb($db);
        if (false == $result) {
            return false;
        } else {
            return true;
        }
    }
}


// renvoi le statut ouvert ou ferme en fonction des horaire de la journé
function checkHoraireDay($j, $m, $y, $epn)
{
    $row = getHoraire(date("w", mktime(0, 0, 0, $m, $j, $y)), $epn);
    if ($row["hor1_begin_horaire"] == 0 and $row["hor1_end_horaire"] == 0 and $row["hor2_begin_horaire"] == 0 and $row["hor2_end_horaire"] == 0) {
        return false;
    } else {
        return true;
    }
}

// renvoi les horaires d'ouverture en min.
function getHoraire($day, $epn)
{
    $sql = "SELECT `hor1_begin_horaire`,`hor1_end_horaire`,`hor2_begin_horaire`,`hor2_end_horaire`
          FROM `tab_horaire`
          WHERE `jour_horaire`='" . $day . "'
                AND `id_epn`='" . $epn . "'
      ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

//
// Interventions ---------------------------------------------------------------
//

// checkInter()
// verifie si une intervention est en cours sur une machine
// TRUE : une intervention est en cours sur la machine
// FALSE : aucune intervention en cours sur la  machine
function checkInter($id_comp)
{
    $sql = "SELECT COUNT(TI.id_inter) AS nb
           FROM tab_inter AS TI
           INNER JOIN rel_inter_computer AS RIC ON RIC.id_inter = TI.id_inter
           WHERE RIC.id_computer = " . $id_comp . "
           AND TI.statut_inter = 0";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == true) {
        $row = mysqli_fetch_array($result);
        if ($row['nb'] > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// renvoi si le jour est ouvert ou fermé
function checkDayOpen($daynum, $year, $epn)
{
    $sql = "SELECT id_days_closed, state_days_closed FROM `tab_days_closed` WHERE `year_days_closed`='" . $year . "' AND `num_days_closed`='" . $daynum . "' AND `id_epn`='" . $epn . "'
          ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    $nb = mysqli_num_rows($result);

    if ($nb == 0) {
        return $nb;
    } else {
        $row = mysqli_fetch_array($result);
        return $row["id_days_closed"];
    }
}

// renvoi si le jour est ouvert ou fermé
function checkDayOpen2($j, $m, $year, $epn)
{
    $daynum = getDayNum($j, $m, $year);
    $sql = "SELECT `state_days_closed`
          FROM `tab_days_closed`
          WHERE `num_days_closed`='" . $daynum . "'
          AND `year_days_closed` = '" . $year . "'
          AND `id_epn`='" . $epn . "'
          ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    closedb($db);
    if (mysqli_num_rows($result) == 0) {
        if (false == checkHoraireDay($j, $m, $year, $epn)) {
            return "ferme";
        } else {
            return "ouvert";
        }
    } elseif ($row["state_days_closed"] == "F") {
        return "ferie";
    }
}


function insertJourFerie($daynum, $year, $epn)
{
    $sql = "INSERT INTO `tab_days_closed` (`id_days_closed`, `year_days_closed`, `num_days_closed`, `state_days_closed`, `id_epn`) VALUES ('','" . $year . "','" . $daynum . "','F','" . $epn . "') ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == true) {
        return true;
    } else {
        return false;
    }
}

function deleteJourFerie($id)
{
    $sql = "DELETE FROM `tab_days_closed` WHERE `id_days_closed`='" . $id . "' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == true) {
        return true;
    } else {
        return false;
    }
}

function getCyberName($epn)
{
    $sql = "SELECT `nom_espace` FROM `tab_espace` WHERE `id_espace`='" . $epn . "' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row["nom_espace"];
    }
}

//****** Fonction pour la tab_connexion ***************//
//entre le log de connexion dans la base
function enterConnexionstatus($iduser, $date, $type, $macadress, $navig, $exploitation)
{
    $sql = "INSERT INTO `tab_connexion`(`id_connexion`, `id_user`, `date_cx`, `type_cx`, `macasdress_cx`, `navigateur_cx`, `system_cx`) 
    VALUES ('','" . $iduser . "','" . $date . "','" . $type . "','" . $macadress . "','" . $navig . "','" . $exploitation . "')";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == true) {
        return true;
    } else {
        return false;
    }
}


///////////***********Transaction sur les ateliers, gestion des forfaits ***************///
///


//////*****
//Forfait à modifier
function getForfait($id)
{
    $sql = "SELECT * from `tab_transactions` WHERE   id_transac='" . $id . "'  LIMIT 1";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

//gettransactemps(id)
// retourne la transaction sur forfait temps en cours + id rel pour epnconnect*******************************//////////////////////////

function getTransactemps($id_user)
{
    $type = "temps";
    $sql = "SELECT `id_transac` , `id_rel_forfait_user`,`date_transac`,`status_transac`,`id_tarif`, nbr_forfait
FROM `tab_transactions` , `rel_forfait_user`
WHERE `type_transac`='" . $type . "'
AND `tab_transactions`.`id_user`='" . $id_user . "'
AND `tab_transactions`.`id_tarif`=`rel_forfait_user`.`id_forfait`";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

// Inutile au niveau de CyberGestionnaire, mais casse potentiellement EPN-Connect

function addrelconsultationuser($type, $tarif_forfait, $id_user)
{
    if ($type == 1) {
        $sql = "INSERT INTO `rel_forfait_user`(`id_rel_forfait_user`, `id_forfait`, `id_user`) VALUES ('','" . $tarif_forfait . "','" . $id_user . "') ";
    } elseif ($type == 2) {
        $sql = "UPDATE `rel_forfait_user` SET `id_forfait`='" . $tarif_forfait . "' WHERE `id_user`='" . $id_user . "' ";
    }
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

function addRelforfaitUser($id_user, $idtransac, $nbatelier, $depense, $statutp)
{
    $sql = "INSERT INTO `rel_user_forfait`(`id_forfait`, `id_user`, `id_transac`, `total_atelier`, `depense`, `statut_forfait`) 
VALUES ('','" . $id_user . "','" . $idtransac . "','" . $nbatelier . "','" . $depense . "','" . $statutp . "') ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

//modification d'un forfait sur le compte
function modifForfaitUser($id, $tarif_forfait, $date, $nbreforfait, $statutp, $nbatelier)
{
    $sql = "UPDATE `tab_transactions` SET `id_tarif`='" . $tarif_forfait . "',`nbr_forfait`='" . $nbreforfait . "',`date_transac`='" . $date . "',`status_transac`='" . $statutp . "' WHERE `id_transac`=" . $id;

    $sql2 = "UPDATE `rel_user_forfait` SET `total_atelier`='" . $nbatelier . "',`statut_forfait`='" . $statutp . "' WHERE `id_transac`=" . $id;

    $db = opendb();
    $result = mysqli_query($db, $sql);
    $result2 = mysqli_query($db, $sql2);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

//retourner le nombre d'atelier dépensés sur le forfait
function getForfaitdepense($iduser, $tarif, $status)
{
    $sql = "SELECT COUNT( `id_forfait` ) AS total
FROM `rel_user_forfait`
WHERE `id_user` ='" . $iduser . "'
AND `id_tarif` ='" . $tarif . "'
AND `statut_forfait` =" . $status;

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row['total'];
    }
}

//
//////////////********GESTION DES SESSIONS*********///////////////////
// retourne toutes les dates d'une session
function getDatesSession($id)
{
    $sql = "SELECT * FROM `tab_session_dates` WHERE `id_session`=" . $id . " ORDER BY `date_session` ASC ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//////////////*****************FIN SESSIONS*************//////////////

/// ***********FONCTIONS SUR LES IMPRESSIONS ****************///
///// retrouver la transaction
function getPrintFromID($id)
{
    $sql = "SELECT * FROM tab_print WHERE id_print='" . $id . "' 
";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

///********FIN IMPRESSIONS ***** //////////////////////////////////////////////

///***********************************************************
//
// renvoi si il y a atelier et le nom de l'atelier

function checkDayAtelier($j, $m, $year)
{
    $sql = "SELECT `date_atelier`,label_atelier, id_atelier
          FROM `tab_atelier`,tab_atelier_sujet
          WHERE tab_atelier.id_sujet=tab_atelier_sujet.id_sujet
          AND date_atelier='" . $year . "-" . $m . "-" . $j . "' ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

//fonction qui affiche les dates des sessions.  ---> a faire
//renvoi la liste des mois où les ateliers sont programmés pour le calendrier

function ListMoisAtelier()
{
    $sql = "SELECT `date_atelier`
FROM `tab_atelier`
ORDER BY `date_atelier` ASC
";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if ($result == false) {
        return false;
    } else {
        return $result;
    }
}

function getSessionValid($id)
{
    $sql = "SELECT `id_programmation` FROM `tab_session_stat` WHERE `id_session`='" . $id . "' ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if (false == $result) {
        return false;
    } else {
        $nb = mysqli_fetch_array($result);
        return $nb;
    }
}

//modifier les nombres statistiuques, après modif par les archives
function ModifStatAS($inscrit, $present, $absents, $idatelier, $type)
{
    $sql = "UPDATE `tab_as_stat` SET `inscrits`=" . $inscrit . ",`presents`=" . $present . ",`absents`=" . $absents . " WHERE `type_AS`='" . $type . "' AND`id_AS`=" . $idatelier;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

//////////
/// Gestion des tarifs //////

function getTarifs($cat)
{
    $sql = "SELECT * FROM `tab_tarifs` WHERE `categorie_tarif`='" . $cat . "' AND `id_tarif`>1 ORDER BY `id_tarif` ASC";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        //  $row = mysqli_fetch_array($result);
        return $result;
    }
}

function getNomTarif($id)
{
    $sql = "SELECT `nom_tarif` FROM `tab_tarifs` WHERE `id_tarif`=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row["nom_tarif"];
    }
}

////***** FONCTIONS SUR LA GESTION MULTIESPACE ***/////
///RESEAU ***///
// retourne le nom du reseau
function getnomreseau()
{
    $sql = "SELECT `res_nom` FROM `tab_reseau` LIMIT 1";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row['res_nom'];
    }
}

//retourne les parametres du reseau
function getReseau()
{
    $sql = "SELECT * FROM `tab_reseau`";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

function modreseau($nom, $adresse, $ville, $tel, $mail, $logo, $courrier, $activation)
{
    $sql = "UPDATE `tab_reseau` SET 
    `res_nom`='" . $nom . "',
    `res_adresse`='" . $adresse . "',
    `res_ville`='" . $ville . "',
    `res_tel`='" . $tel . "',
    `res_mail`='" . $mail . "',
    `res_logo`='" . $logo . "',
    `res_courrier`='" . $courrier . "',
    `res_activation`='" . $activation . "'
    ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

//
// getAllSalle()
// recupere les salless
function getAllSalle()
{
    $sql = "SELECT * FROM tab_salle;";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//
// getSalle()
// recupere les salless
function getSalle($numsalle)
{
    $sql = "SELECT * FROM tab_salle WHERE id_salle=" . $numsalle . ";";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//
// getEspace()
// recupere les espaces
function getEspace($numespace)
{
    $sql = "SELECT * FROM tab_espace WHERE id_espace=" . $numespace . " ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//
//recuperer l'activation des forfaits pour l'epn
//
function getActivationForfaitEpn($id)
{
    $sql = "SELECT `activer_console`,`inscription_usagers_auto`, `message_inscription`, `activation_forfait` FROM `tab_config` WHERE id_espace='" . $id . "' ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

function getAllEPN()
{
    $sql = "SELECT `id_espace`, `nom_espace` FROM `tab_espace`  ORDER BY `nom_espace` asc";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $epn = array();
        $nb = mysqli_num_rows($result);
        for ($i = 1; $i <= $nb; $i++) {
            $row = mysqli_fetch_array($result);
            $epn[$row["id_espace"]] = $row["nom_espace"];
        }
        return $epn;
    }
}

//retourne la liste des salles par epn
function getAllSallesbyepn($epn)
{
    $sql = "SELECT `id_salle`, `nom_salle` FROM `tab_salle`  
                    WHERE id_espace='" . $epn . "'
        
    ORDER BY `id_salle`";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $epn = array();
        $nb = mysqli_num_rows($result);
        for ($i = 1; $i <= $nb; $i++) {
            $row = mysqli_fetch_array($result);
            $epn[$row["id_salle"]] = $row["nom_salle"];
        }
        return $epn;
    }
}

///***** GESTION DES COURRIERS ***** ///
/*
function createCourrier($titre, $texte, $name, $type)
{
    $sql = "INSERT INTO `tab_courriers`(`id_courrier`, `courrier_titre`, `courrier_text`, `courrier_name`, `courrier_type`) 
    VALUES ('','" . $titre . "','" . $texte . "','" . $name . "','" . $type . "')
    ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return true;
    }
}

function getAllCourrier()
{
    $sql = "SELECT * FROM `tab_courriers`";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return $result;
    }
}

function getcourrier($id)
{
    $sql = "SELECT * FROM `tab_courriers` WHERE id_courrier=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

function modCourrier($id, $titre, $texte, $name, $type)
{
    $sql = "UPDATE `tab_courriers` SET    `courrier_titre`='" . $titre . "',
    `courrier_text`='" . $texte . "',
    `courrier_name`=" . $name . ",
    `courrier_type`=" . $type . " 
    WHERE `id_courrier`=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return true;
    }
}

function supCourrier($id)
{
    $sql = "DELETE FROM `tab_courriers` WHERE `id_courrier`=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return true;
    }
}
*/
//sur la page atelier, recuperer les infos du mail de rappel
function getMailRappel()
{
    $sql = "SELECT `courrier_titre` ,`courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $txt = array();
        $nb = mysqli_num_rows($result);
        for ($i = 1; $i <= $nb; $i++) {
            $row = mysqli_fetch_array($result);
            $txt[$row["courrier_type"]] = $row["courrier_text"];
        }
        return $txt;
       // debug($txt);
    }
}

//sur la page utilisateur, recuperer les infos du mail d'inscription
function getMailInscript()
{
    $sql = "SELECT `courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1 AND `courrier_titre` LIKE '%inscription%' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $txt = array();
        $nb = mysqli_num_rows($result);
        for ($i = 1; $i <= $nb; $i++) {
            $row = mysqli_fetch_array($result);
            $txt[$row["courrier_type"]] = $row["courrier_text"];
        }
        return $txt;
    }
}

//gestin de la newsletter

function getNewsletterUsers()
{
    $sql = "SELECT `nom_user`, `prenom_user`, `mail_user`, `epn_user` FROM `tab_user` WHERE `newsletter_user`=1";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return $result;
    }
}


///*******************************************************************///
///***** GESTION DES PROFILS ANIMATEURS ***** ///


function getAvatar($id)
{
    $sql = "SELECT `anim_avatar` FROM `rel_user_anim` WHERE `id_animateur`='" . $id . "' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

function getSallesbyAnim($id)
{
    $sql = "SELECT `id_salle` FROM `rel_user_anim` WHERE `id_animateur`='" . $id . "' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

function getNomsalleforAnim($id)
{
    $sql = "SELECT `nom_salle` FROM `tab_salle` WHERE `id_salle`='" . $id . "' ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row["nom_salle"];
    }
}

function updateNewsletter($iduser, $type)
{
    $sql = "UPDATE `tab_user` SET `newsletter_user`=" . $type . " WHERE `id_user`=" . $iduser;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return true;
    }
}

function readMyMessage($iduser)
{
    $sql = "SELECT * FROM `tab_messages` WHERE `mes_auteur`='" . $iduser . "' OR `mes_destinataire`='" . $iduser . "'
ORDER BY `mes_date` DESC ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return $result;
    }
}

//*************************************************************///
//********Gestion des préinscriptions *********************/////
//****preinscriptions automatiques par internet
//retourne l'activation du module ou pas...
function getPreinsmode()
{
    $sql = "SELECT * FROM tab_captcha";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

//
// getAllUserInsc()
// recupere les utilisateurs
function getAllUserInsc()
{
    $sql = "SELECT `id_inscription_user`, `date_inscription_user`, `nom_inscription_user`, `prenom_inscription_user`, `login_inscription_user`, `id_inscription_computer`
        FROM tab_inscription_user  ORDER BY `nom_inscription_user`";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $result;
    }
}

//
// getUserInsc()
// recupere un utilisateur
function getUserInsc($id)
{
    $sql = "SELECT *
        FROM tab_inscription_user WHERE id_inscription_user=" . $id;
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

//
// deluser
// Supprime un utilisateur
function delUserInsc($id)
{
    $sql = "DELETE FROM `tab_inscription_user` WHERE `id_inscription_user`=" . $id . " LIMIT 1 ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

function addUserinscript($date, $nom, $prenom, $sexe, $jour, $mois, $annee, $adresse, $pays, $codepostal, $commune, $ville, $tel, $telport, $mail, $temps, $loginn, $passs, $status, $csp, $equipement, $utilisation, $connaissance, $info, $epn)
{
    $db = opendb();
    $nom = mysqli_real_escape_string($db, $nom);
    $prenom = mysqli_real_escape_string($db, $prenom);
    $adresse = mysqli_real_escape_string($db, $adresse);
    $pays = mysqli_real_escape_string($db, $pays);
    $codepostal = mysqli_real_escape_string($db, $codepostal);
    $commune = mysqli_real_escape_string($db, $commune);
    $ville = mysqli_real_escape_string($db, $ville);
    $tel = mysqli_real_escape_string($db, $tel);
    $telport = mysqli_real_escape_string($db, $telport);
    $mail = mysqli_real_escape_string($db, $mail);
    $info = mysqli_real_escape_string($db, $info);
    $loginn = mysqli_real_escape_string($db, $loginn);
    $passs = mysqli_real_escape_string($db, $passs);

    $sql = "INSERT INTO `tab_inscription_user`(`id_inscription_user`, `date_inscription_user`, `nom_inscription_user`, `prenom_inscription_user`, `sexe_inscription_user`, `jour_naissance_inscription_user`, `mois_naissance_inscription_user`, `annee_naissance_inscription_user`, `adresse_inscription_user`, `quartier_inscription_user`, `code_postal_inscription`, `commune_inscription_autres`, `ville_inscription_user`, `tel_inscription_user`, `tel_port_inscription_user`, `mail_inscription_user`, `temps_inscription_user`, `login_inscription_user`, `pass_inscription_user`, `status_inscription_user`, `lastvisit_inscription_user`, `csp_inscription_user`, `equipement_inscription_user`, `utilisation_inscription_user`, `connaissance_inscription_user`, `info_inscription_user`, `id_inscription_computer`) 
VALUES ('','" . $date . "','" . $nom . "','" . $prenom . "','" . $sexe . "','" . $jour . "','" . $mois . "','" . $annee . "','" . $adresse . "','" . $pays . "','" . $codepostal . "','" . $commune . "','" . $ville . "','" . $tel . "','" . $telport . "','" . $mail . "','" . $temps . "','" . $loginn . "','" . $passs . "','" . $status . "','" . date('Y-m-d') . "','" . $csp . "','" . $equipement . "','" . $utilisation . "','" . $connaissance . "','" . $info . "','" . $epn . "')";

    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return true;
    }
}

///Ajouter la relation atelier-commputer pour quEPN cpnnect libère la salle !
function connectAtelierComputer($salle, $idatelier)
{
    $sql = "INSERT INTO `rel_atelier_computer` ( `id_atelier_computer` , `id_atelier_rel` , `id_computer_rel` )
SELECT '', '" . $idatelier . "', `id_computer`
FROM `tab_computer`
WHERE `id_salle` ='" . $salle . "'
AND `usage_computer` =1 ";
    $db = opendb();
    $resultrow = mysqli_query($db, $sql);
    closedb($db);
    if (false == $resultrow) {
        return false;
    } else {
        return true;
    }
}

///************Fonctions de la page d'accueil ********************///
//
// retourne l'id d'un log du jour pour la mose à jour du statut des adherents
function getLogUser($type)
{
    $sql = "SELECT `id_log`,`log_date` FROM `tab_logs` WHERE date(`log_date`)=date(NOW()) AND `log_type`='" . $type . "'  ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return $result;
    }
}

// update des adherents actifs ---> inactifs
function updateUserStatut()
{
    $sql = "UPDATE `tab_user` SET `status_user`=2 WHERE `status_user`=1 AND DATE(`dateRen_user`)<=DATE(NOW())";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    $nb = mysqli_affected_rows($db);

    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return $nb;
    }
}

//retourne id + nom + prenom des adherents inactifs du jour
function getAdhInactif($jour)
{
    $sql = "SELECT `id_user`,`nom_user`,`prenom_user`  FROM `tab_user` WHERE  DATE(`dateRen_user`)=DATE(NOW())";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return mysqli_fetch_array($result);
    }
}

// test si la base a été sauvegardée
function getLogBackup()
{
    $sql = "SELECT `id_log` FROM `tab_logs` WHERE YEAR(`log_date`) = YEAR(NOW()) AND MONTH(`log_date`)=MONTH(NOW())  AND `log_type`='bac' AND DATE(`log_date`)>DATE(`log_date`)-15 ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (mysqli_num_rows($result) <= 0) {
        return true;
    } else {
        return false;
    }
}

//insert un log dans la table des logs
function addLog($date, $type, $valid, $comment)
{
    $sql = "INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`)
VALUES ('','" . $type . "','" . $date . "','','" . $valid . "','" . $comment . "')";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == false) {
        return false;
    } else {
        return true;
    }
}


///fonctions pour le flux rss
function getAtelierDuMois()
{
    $sql = "SELECT *
FROM `tab_atelier`,tab_atelier_sujet
WHERE MONTH( `date_atelier` ) = MONTH( NOW( ) )
AND YEAR( `date_atelier` ) = YEAR( NOW( ) ) 
AND `tab_atelier`.id_sujet=tab_atelier_sujet.`id_sujet`";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if (mysqli_num_rows($result) == 0) {
        return false;
    } else {
        return $result;
    }
}
?>


