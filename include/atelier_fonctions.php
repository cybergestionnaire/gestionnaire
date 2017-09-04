<?php

//
// renvoi si il y a atelier et le nom de l'atelier

function checkDayAtelier($j, $m, $year) {

    $sql = "SELECT `date_atelier`,label_atelier, id_atelier
          FROM `tab_atelier`,tab_atelier_sujet
          WHERE tab_atelier.id_sujet=tab_atelier_sujet.id_sujet
		  AND date_atelier='" . $year . "-" . $m . "-" . $j . "' ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if ($result == FALSE) {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

//fonction qui affiche les dates des sessions.  ---> a faire
//renvoi la liste des mois o� les ateliers sont programm�s pour le calendrier

function ListMoisAtelier() {
    $sql = "SELECT `date_atelier`
FROM `tab_atelier`
ORDER BY `date_atelier` ASC
";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if ($result == FALSE) {
        return FALSE;
    } else {
        return $result;
    }
}

/// fonction pour v�rifier si la validation des pr�sences a d�j� �t� faire ou pas
function getAtelierValid($id) {
    $sql = "SELECT `id_programmation` FROM `tab_atelier_stat` WHERE `id_atelier`='" . $id . "' ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if (FALSE == $result) {
        return FALSE;
    } else {
        $nb = mysqli_fetch_array($result);
        return $nb;
    }
}

//Pour les stat, validation des pr�sents
function ValidPresenceAtelier($idatelier, $nombre_present, $nombre_inscrit, $ids_presents, $date_atelier, $id_categorie, $nom_atelier) {
    $sql = "INSERT INTO `tab_atelier_stat`
		VALUES ('','" . $idatelier . "','" . $nombre_present . "','" . $nombre_inscrit . "','" . $ids_presents . "','" . $date_atelier . "','" . $id_categorie . "','" . $nom_atelier . "')";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        return FALSE;
    } else {
        return True;
    }
}

// verification de la validation presence � un atelier
function CheckEntreeAtelier($idatelier) {
    $sql = " SELECT id_atelier FROM tab_atelier_stat
		WHERE id_atelier='" . $idatelier . "'";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE != $result) {
        $row = mysqli_fetch_array($result);
        return $row;
    }
}

// supression d'un sujet d'atelier de la base
function delSujetAtelier($id) {

    $sql = "DELETE FROM `tab_atelier_sujet` WHERE `id_sujet`='" . $id . "'
	";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        return FALSE;
    } else {
        return TRUE;
    }
}

?>