<?php

///FICHER DE FONCTIONS POUR LES MISES A JOURS
///Backup integral de la base
function backupbdd() {
    include ("./connect_db.php");

    new BackupMySQL(array(
        'username' => $userdb,
        'passwd' => $passdb,
        'dbname' => $database
    ));

    $sql = "INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`) 
    VALUES ('','bac' ,NOW(), '1.9','1', 'Backup integral de la base effectue') ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    if (FALSE == $result) {
        return FALSE;
    } else {
        return TRUE;
    }
}

//*****************************FONCTIONS  PERENNES
//retrouver la dernière sauvegarde de la base avant de lancer les maj
function getLastBackup() {
    $sql = "SELECT `id_log`, `log_type`, `log_date` FROM `tab_logs` WHERE `log_type`='bac' AND MONTH(`log_date`)=MONTH(NOW()) AND YEAR(`log_date`)=YEAR(NOW())";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        return FALSE;
    } else {
        if (mysqli_num_rows($result) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

///Inserer dans les logs
function InsertLogMAJ($type, $version, $date, $comment) {
    $sql = "INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`) 
VALUES ('', '" . $type . "','" . $date . "','" . $version . "','1','" . $comment . "') ";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/**
 * Output span with progress.
 *
 * @param $current integer Current progress out of total
 * @param $total   integer Total steps required to complete
 */
function outputProgress($current, $total, $table) {
    echo "<span style='position: absolute;z-index:$current;background:#FFF;'>" . $table . " : " . round($current / $total * 100) . "% </span>";
    myFlush();
    sleep();
}

/**
 * Flush output buffer
 */
function myFlush() {
    echo(str_repeat(' ', 256));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}

//******** Ajout des tables supplémentaires
// ***** maj version 1.1 *******

function AddTab_courrier() {
    $sql = "CREATE TABLE IF NOT EXISTS `tab_courriers` (
            `id_courrier` int(11) NOT NULL AUTO_INCREMENT,
            `courrier_titre` varchar(150) COLLATE latin1_general_ci NOT NULL,
            `courrier_text` varchar(800) COLLATE latin1_general_ci NOT NULL,
            `courrier_name` int(11) NOT NULL,
            `courrier_type` int(11) NOT NULL,
            PRIMARY KEY (`id_courrier`)
            ) ENGINE=MyISAM ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function add_courriertest() {
    $sql = "INSERT INTO `tab_courriers` (`id_courrier`, `courrier_titre`, `courrier_text`, `courrier_name`, `courrier_type`) VALUES
            (1, 'rappel', 'Piqure de rappel', 1, 2),
            (2, 'rappel', 'Vous etes inscrit(e) a un atelier :', 1, 3),
            (3, 'rappel', 'N''hesitez pas a nous recontacter aux coordonnees suivantes', 1, 4);";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function alterEspace() {
    $sql = "ALTER TABLE `tab_espace` ADD `mail_espace` VARCHAR( 300 ) NOT NULL";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

// ***** maj version 1.2 *******

function Tab_ins1() {
    $sql = "ALTER TABLE `tab_inscription_user` CHANGE `equipement_inscription_user` `equipement_inscription_user` VARCHAR( 50 ) NOT NULL ;";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function Tab_ins2() {
    $sql = "ALTER TABLE `tab_inscription_user` CHANGE `connaissance_inscription_user` `connaissance_inscription_user` INT(11) NOT NULL ;";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function alterMessageMAJ() {
    $sql = "ALTER TABLE `tab_messages` CHANGE `mes_date` `mes_date` DATETIME NULL DEFAULT NULL ;";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function createtabinscriptMAJ() {
    $sql = "CREATE TABLE IF NOT EXISTS `tab_captcha` (
            `id_captcha` int(11) NOT NULL AUTO_INCREMENT,
            `capt_activation` ENUM('N', 'Y') NOT NULL,
            `capt_code` varchar(500) NOT NULL,
            PRIMARY KEY (`id_captcha`)
            ) ENGINE=MyISAM ;";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

function insertCapt() {
    $sql = " INSERT INTO `tab_captcha`(`id_captcha`, `capt_activation`, `capt_code`) VALUES (1,'N','') ;";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result) {
        $row = "echec";
    } else {
        $row = "OK";
    }
    return $row;
}

?>