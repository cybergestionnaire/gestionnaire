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


require_once("Mysql.class.php");
require_once("include/class/Session.class.php");

class SessionDate {

    private $_id;
    private $_idSession;
    private $_date;
    private $_statut;

    private function __construct($array) {
        $this->_id = $array["id_datesession"];
        $this->_idSession = $array["id_session"];
        $this->_date = $array["date_session"];
        $this->_statut = $array["statut_datesession"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId() {
        return $this->_id;
    }

    public function getIdSession() {
        return $this->_idSession;
    }

    public function getSession() {
        return Session::getSessionById($this->_idSession);
    }

    public function getDate() {
        return $this->_date;
    }

    public function getStatut() {
        return $this->_statut;
    }

    function getUtilisateursInscrits() {
        return Utilisateur::getUtilisateursInscritsSessionDate($this->_id);
    }

    function getNbUtilisateursInscrits() {
        return count(self::getUtilisateursInscrits());
    }

    function getUtilisateursPresents() {
        return Utilisateur::getUtilisateursPresentsSessionDate($this->_id);
    }

    function getNbUtilisateursPresents() {
        return count(self::getUtilisateursPresents());
    }

    function getUtilisateursInscritsOuPresents() {
        return array_merge(Utilisateur::getUtilisateursInscritsSessionDate($this->_id), Utilisateur::getUtilisateursPresentsSessionDate($this->_id));
    }

    function getNbUtilisateursInscritsOuPresents() {
        return count(self::getUtilisateursInscritsOuPresents());
    }

    function getUtilisateursEnAttente() {
        return Utilisateur::getUtilisateursEnAttenteSessionDate($this->_id);
    }

    function getNbUtilisateursEnAttente() {
        return count(self::getUtilisateursEnAttente());
    }

    function inscrireUtilisateurInscrit($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '0');
    }

    function inscrireUtilisateurPresent($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '1');
    }

    function inscrireUtilisateurEnAttente($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '2');
    }

    function inscrireUtilisateur($idUtilisateur, $statut) {
        $success = FALSE;

        $db = Mysql::opendb();

        if (!self::isUtilisateurInscrit($idUtilisateur)) {
            $sql = "INSERT INTO `rel_session_user` (`id_session` , `id_datesession`, `id_user` , `status_rel_session` )
                    VALUES ('" . $this->_idSession . "', '" . $this->_id . "', '" . $idUtilisateur . "', '" . $statut . "')";
        } else {
            $sql = "UPDATE `rel_session_user` "
                    . "SET status_rel_session=" . $statut . " "
                    . "WHERE `id_user`=" . $idUtilisateur . " AND `id_datesession`=" . $this->_id;
        }

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            $success = TRUE;
        }
        return $success;
    }

    function isUtilisateurInscrit($idUtilisateur) {
        // verifie si le user n'est pas deja inscrit
        $success = FALSE;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM `rel_session_user` WHERE `id_datesession` =" . $this->_id . " AND `id_user` =" . $idUtilisateur;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if (mysqli_num_rows($result) == 1) {
            $success = TRUE;
        }

        return $success;
    }

    function isUtilisateurPresent($idUtilisateur) {
        // verifie si le user n'est pas deja inscrit
        $success = FALSE;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM `rel_session_user` WHERE `id_datesession` =" . $this->_id . " AND `id_user` =" . $idUtilisateur . " AND status_rel_session = '1'";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if (mysqli_num_rows($result) == 1) {
            $success = TRUE;
        }

        return $success;
    }

    public function cloturer() {
        $success = FALSE;

        $db = Mysql::opendb();
        $sql = "UPDATE `tab_session_dates` SET `statut_datesession`=1 WHERE `id_datesession`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = TRUE;
        }
        return $success;
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($idSession, $date, $statut) {

        $success = FALSE;
        $db = Mysql::opendb();

        $idSession = mysqli_real_escape_string($db, $idSession);
        $date = mysqli_real_escape_string($db, $date);
        $statut = mysqli_real_escape_string($db, $statut);

        $sql = "UPDATE `tab_session_dates` "
                . "SET `id_session`='" . $idSession . "', "
                . "`date_session`='" . $date . "', "
                . "`statut_datesession`='" . $statut . "' "
                . "WHERE `id_datesession`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_idSession = $idSession;
            $this->_date = $date;
            $this->_statut = $statut;
            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $success = false;
        $db = Mysql::opendb();

        // effacer les relations
        $sql1 = "DELETE FROM `rel_session_user` where `id_datesession`=" . $this->_id;
        $result1 = mysqli_query($db, $sql1);
        // error_log ("date à supprimer : " . $this->_date);
        if ($result1) {
            $sql = "DELETE FROM `tab_session_dates` WHERE `id_datesession`=" . $this->_id;
            $result = mysqli_query($db, $sql);
            if ($result) {
                $success = true;
            }
        }

        Mysql::closedb($db);

        return $success;
    }

    /*
     * Fonctions statiques
     */

    public static function getSessionDateById($id) {

        $sessionDate = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_session_dates` "
                    . "WHERE `id_datesession` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $sessionDate = new SessionDate(mysqli_fetch_assoc($result));
            }
        }

        return $sessionDate;
    }

    public static function creerSessionDate($idSession, $date, $statut) {
        $sessionDate = null;

        $db = Mysql::opendb();

        $idSession = mysqli_real_escape_string($db, $idSession);
        $date = mysqli_real_escape_string($db, $date);
        $statut = mysqli_real_escape_string($db, $statut);

        $sql = "INSERT INTO `tab_session_dates` (`id_session`,`date_session`,`statut_datesession`) VALUES ('" . $idSession . "', '" . $date . "', '" . $statut . "')";

        $result = mysqli_query($db, $sql);

        if ($result) {
            $sessionDate = new SessionDate(array("id_datesession" => mysqli_insert_id($db), "id_session" => $idSession, "date_session" => $date, "statut_datesession" => $statut));
        }

        Mysql::closedb($db);

        return $sessionDate;
    }

    public static function getSessionDates() {
        $sessionDates = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_session_dates ORDER BY date_session";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionDates = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }

        return $sessionDates;
    }

    public static function getSessionDatesByIdSession($idSession) {
        $sessionDates = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_session_dates where `id_session`=" . $idSession . " ORDER BY date_session";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionDates = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }

        return $sessionDates;
    }

    public static function getSessionDatesParSemaine($jour, $idEspace) {

        $sessionDates = null;

        $db = Mysql::opendb();
        if ($idEspace == 0) {
            $sql = "SELECT tab_session_dates.* "
                    . "FROM tab_session_dates "
                    . "WHERE WEEK(`date_session`) = WEEK('" . $jour . "') "
                    . "  AND statut_datesession=0 "
                    . "ORDER BY date_session ASC";
        } else {
            $sql = "SELECT  tab_session_dates.* "
                    . "FROM tab_session_dates,tab_salle,tab_session "
                    . "WHERE WEEK(tab_session_dates.`date_session`) = WEEK('" . $jour . "') "
                    . "  AND statut_datesession=0 "
                    . "  AND tab_salle.`id_espace` =" . $idEspace . " "
                    . "  AND tab_session.`id_salle` = tab_salle.id_salle "
                    . "  AND tab_session.`id_session` = tab_session_dates.`id_session` "
                    . "ORDER BY date_session ASC ";
        }

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionDates = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }

        return $sessionDates;
    }

    public static function getSessionDatesEnCoursParUtilisateurEtParStatut($idUtilisateur, $statut) {
        $sessionDates = null;

        $db = Mysql::opendb();
        $sql = "SELECT tab_session_dates.* "
                . "FROM tab_session_dates, tab_session, `rel_session_user` "
                . "WHERE tab_session_dates.id_datesession = rel_session_user.id_datesession "
                . "  AND `rel_session_user`.`status_rel_session`=" . $statut . " "
                . "  AND tab_session_dates.statut_datesession = '0' "
                . "  AND tab_session.status_session = 0 "
                . "  AND tab_session_dates.id_session = tab_session.id_session "
                . "  AND `rel_session_user`.`id_user`=" . $idUtilisateur;

//SELECT tab_session_dates.*, rel_session_user.* FROM tab_session_dates, tab_session, `rel_session_user` WHERE tab_session_dates.id_datesession = rel_session_user.id_datesession AND`rel_session_user`.`status_rel_session`=0   AND tab_session.status_session = 0   AND `rel_session_user`.`id_user`=9                    

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionDates = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }
        // error_log("sql = " . $sql);
        return $sessionDates;
    }

    public static function getSessionDatesFermeesParUtilisateurEtParStatut($idUtilisateur, $statut) {
        $sessionDates = null;

        $db = Mysql::opendb();
        $sql = "SELECT tab_session_dates.* "
                . "FROM tab_session_dates, tab_session, `rel_session_user` "
                . "WHERE tab_session_dates.id_datesession = rel_session_user.id_datesession "
                . "  AND `rel_session_user`.`status_rel_session`=" . $statut . " "
                . "  AND tab_session.status_session = 1 "
                . "  AND tab_session.id_session = tab_session_dates.id_session "
                . "  AND `rel_session_user`.`id_user`=" . $idUtilisateur;

//SELECT tab_session_dates.*, rel_session_user.* FROM tab_session_dates, tab_session, `rel_session_user` WHERE tab_session_dates.id_datesession = rel_session_user.id_datesession AND`rel_session_user`.`status_rel_session`=0   AND tab_session.status_session = 0   AND `rel_session_user`.`id_user`=9                    

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionDates = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }
        // error_log("sql = " . $sql);
        return $sessionDates;
    }

}
