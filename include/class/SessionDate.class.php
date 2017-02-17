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

class SessionDate
{
    private $_id;
    private $_idSession;
    private $_date;
    private $_statut;

    private function __construct($array) {
        $this->_id        = $array["id_datesession"];
        $this->_idSession = $array["id_session"];
        $this->_date      = $array["date_session"];
        $this->_statut    = $array["statut_datesession"];
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
    
    /*
     * Fonctions de l'objet
     */
    
    public function modifier($idSession, $date, $statut) {

        $success = FALSE;
        $db = Mysql::opendb();
        
        $idSession = mysqli_real_escape_string($db, $idSession);
        $date      = mysqli_real_escape_string($db, $date);
        $statut    = mysqli_real_escape_string($db, $statut);

        $sql = "UPDATE `tab_session_dates` "
            . "SET `id_session`='" . $idSession . "', "
            . "`date_session`='" . $date . "', "
            . "`statut_datesession`='" . $statut . "' "
            . "WHERE `id_datesession`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_idSession = $idSession;
            $this->_date      = $date;
            $this->_statut    = $statut;
            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $success = false;
        $db = Mysql::opendb();

        // effacer les relations
        $sql1    = "DELETE FROM `rel_session_user` where `id_datesession`=" . $this->_id;
        $result1 = mysqli_query($db, $sql1);
        // error_log ("date à supprimer : " . $this->_date);
        if ($result1) {
            $sql    = "DELETE FROM `tab_session_dates` WHERE `id_datesession`=" . $this->_id;
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
                 . "FROM `tab_session_sujet` "
                 . "WHERE `id_session_sujet` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                $sessionDate = new SessionDate(mysqli_fetch_assoc($result));
            }
        }
        
        return $sessionDate;
    }
    
    public static function creerSessionDate($idSession, $date, $statut)
    {
        $sessionDate = null;
        
        $db  = Mysql::opendb();
        
        $idSession = mysqli_real_escape_string($db, $idSession);
        $date      = mysqli_real_escape_string($db, $date);
        $statut    = mysqli_real_escape_string($db, $statut);

        $sql = "INSERT INTO `tab_session_dates` (`id_session`,`date_session`,`statut_datesession`) VALUES ('" . $idSession . "', '" . $date . "', '" . $statut . "')";
        
        $result = mysqli_query($db,$sql);
        
        if ($result) {
            $sessionDate = new SessionDate(array("id_datesession" => mysqli_insert_id($db), "id_session" => $idSession, "date_session" => $date, "statut_datesession" => $statut));
        }
        
        Mysql::closedb($db);

        return $sessionDate;
    }
    
    
    public static function getSessionDates() {
        $sessionDates = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_session_dates ORDER BY date_session";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $sessionDates = array();
            while($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }
        
        return $sessionDates;

    }
    
    public static function getSessionDatesByIdSession($idSession) {
        $sessionDates = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_session_dates where `id_session`=" . $idSession . " ORDER BY date_session";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $sessionDates = array();
            while($row = mysqli_fetch_assoc($result)) {
                $sessionDates[] = new SessionDate($row);
            }
            mysqli_free_result($result);
        }
        
        return $sessionDates;

    }
}