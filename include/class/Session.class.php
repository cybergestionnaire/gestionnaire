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
require_once("Utilisateur.class.php");
require_once("Salle.class.php");
require_once("Tarif.class.php");
require_once("SessionSujet.class.php");
require_once("SessionDate.class.php");

class Session
{
    private $_id;
    private $_date;
    private $_idSessionSujet;
    private $_nbPlaces;
    private $_nbDates;
    private $_status;
    private $_idAnimateur;
    private $_idSalle;
    private $_idTarif;


    private function __construct($array) {
        $this->_id             = $array["id_session"];
        $this->_date           = $array["date_session"];
        $this->_idSessionSujet = $array["nom_session"];
        $this->_nbPlaces       = $array["nbplace_session"];
        $this->_nbDates        = $array["nbre_dates_sessions"];
        $this->_status         = $array["status_session"];
        $this->_idAnimateur    = $array["id_anim"];
        $this->_idSalle        = $array["id_salle"];
        $this->_idTarif        = $array["id_tarif"];
    }
    
    /*
    * Accesseurs basiques
    */
    
    public function getId() {
        return $this->_id;
    }
    
    public function getDate() {
        return $this->_date;
    }

    public function getIdSessionSujet() {
        return $this->_idSessionSujet;
    }

    public function getSessionSujet() {
        return SessionSujet::getSessionSujetById($this->_idSessionSujet);
    }

    public function getNbPlaces() {
        return $this->_nbPlaces;
    }

    public function getNbDates() {
        return $this->_nbDates;
    }

    
    public function getStatus() {
        return $this->_status;
    }

    public function getIdAnimateur() {
        return $this->_idAnimateur;
    }

    public function getAnimateur() {
        return Utilisateur::getUtilisateurById($this->_idAnimateur);
    }
        public function getIdSalle() {
        return $this->_idSalle;
    }

    public function getSalle() {
        return Salle::getSalleById($this->_idSalle);
    }
    
    public function getIdTarif() {
        return $this->_idTarif;
    }

    public function getTarif() {
        return Tarif::getTarifById($this->_idTarif);
    }
    
    public function addSessionDate($date, $statut) {
        $success = false;
        
        $dateSession = SessionDate::creerSessionDate($this->_id, $date, $statut);
        if ($dateSession !== null) {
            // ajout des utilisateurs pour cette date
            // on n'inscrit les éventuels présents en inscrits : il ne peut y avoir de présents sur une nouvelle date !
            foreach($this->getUtilisateursInscritsOuPresents() as $utilisateur) {
                $dateSession->inscrireUtilisateurInscrit($utilisateur->getId());
            }
            foreach($this->getUtilisateursEnAttente() as $utilisateur) {
                $dateSession->inscrireUtilisateurEnAttente($utilisateur->getId());
            }
            $success = true;
        }
        
        return $success;
    }
    
    public function getSessionDates() {
        return SessionDate::getSessionDatesByIdSession($this->_id);
    }
    
    public function getUtilisateursInscrits() {
        return Utilisateur::getUtilisateursInscritsSession($this->_id);
    }
    
    public function getNbUtilisateursInscrits() {
        return count(self::getUtilisateursInscrits());
    }    
    
    public function getUtilisateursPresents() {
        return Utilisateur::getUtilisateursPresentsSession($this->_id);
    }
    
    public function getNbUtilisateursPresents() {
        return count(self::getUtilisateursPresents());
    }  

    public function getUtilisateursInscritsOuPresents() {
        return Utilisateur::getUtilisateursInscritsOuPresentsSession($this->_id);
    }
    
    public function getNbUtilisateursInscritsOuPresents() {
        return count(self::getUtilisateursInscritsOuPresents());
    }    
    
    public function getUtilisateursEnAttente() {
        return Utilisateur::getUtilisateursEnAttenteSession($this->_id);
    }
    
    public function getNbUtilisateursEnAttente() {
        return count(self::getUtilisateursEnAttente());
    }

    public function isUtilisateurInscrit($idUtilisateur) {
        // verifie si le user n'est pas deja inscrit
        $success = FALSE;
        
        $db     = Mysql::opendb();
        $sql    = "SELECT * FROM `rel_session_user` WHERE `id_session` =" . $this->_id . " AND `id_user` =" . $idUtilisateur ;
        // attention, renvoi une ligne par datesession !!! Donc, plusieurs "row" !
        
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if (mysqli_num_rows($result) >= 1) {
            $success = TRUE;
        }
        
        return $success;
    }
    
    public function inscrireUtilisateurInscrit($idUtilisateur) {
        return $this->InscrireUtilisateur($idUtilisateur, '0');
    }
    
    public function inscrireUtilisateurPresent($idUtilisateur) {
        return $this->InscrireUtilisateur($idUtilisateur, '1');
    }

    public function inscrireUtilisateurEnAttente($idUtilisateur) {
        return $this->InscrireUtilisateur($idUtilisateur, '2');
    }
    
    public function InscrireUtilisateur($idUtilisateur, $statut) {
        $success = TRUE;

        foreach ($this->getSessionDates() as $dateSession) {
            if (!$dateSession->inscrireUtilisateur($idUtilisateur, $statut)) {
                $success = false;
            }
        }
        return $success;
    }
    
    public function desinscrireUtilisateur($idUtilisateur) {
        $success = FALSE;
        
        $db      = Mysql::opendb();
        $sql     = "DELETE FROM `rel_session_user` WHERE `id_user`=" . $idUtilisateur . " AND `id_session`=" . $this->_id ;
        $result  = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) { 

            $success = TRUE;
        }
                
        return $success;
    }
    
    public function hasSessionDatesValidees() {
        // permet de savoir si des dates ont déjà été validées,
        // auquel cas, on ne doit plus supprimer la session !

        $hasSessionDatesValidees = FALSE;
        $db = Mysql::opendb();

        $sql = "SELECT count( `statut_datesession` ) AS nb FROM `tab_session_dates` WHERE `id_session` =" . $this->_id . " AND `statut_datesession` >0";

        $result = mysqli_query($db,$sql);

        if ($result) {
            $row = mysqli_fetch_array($result);
            $nbDatesValidees = $row["nb"];
            
            if ($nbDatesValidees > 0) {
                $hasSessionDatesValidees = TRUE;
            }
        }
        
        return $hasSessionDatesValidees;
    }

    public function hasSessionDatesNonValidees() {
        // permet de savoir si des dates ont déjà été validées,
        // auquel cas, on ne doit plus supprimer la session !

        $hasSessionDatesNonValidees = FALSE;
        $db = Mysql::opendb();

        $sql = "SELECT count( `statut_datesession` ) AS nb FROM `tab_session_dates` WHERE `id_session` =" . $this->_id . " AND `statut_datesession` = 0";

        $result = mysqli_query($db,$sql);

        if ($result) {
            $row = mysqli_fetch_array($result);
            $nbDatesNonValidees = $row["nb"];
            
            if ($nbDatesNonValidees > 0) {
                $hasSessionDatesNonValidees = TRUE;
            }
        }
        
        return $hasSessionDatesNonValidees;
    }

    public function cloturer() {
        $success = FALSE;
        
        $db      = Mysql::opendb();
        $sql     = "UPDATE `tab_session` SET `status_session`=1 WHERE `id_session`=" . $this->_id;
        $result  = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = TRUE;
        }
        return $success;
    }
    
    /*
     * Fonctions de l'objet
     */
    
    public function modifier($date, $idSessionSujet, $nbPlaces, $nbDates, $status, $idAnimateur, $idSalle, $idTarif) {

        $success = FALSE;
        $db = Mysql::opendb();
        
        $date           = mysqli_real_escape_string($db, $date);
        $idSessionSujet = mysqli_real_escape_string($db, $idSessionSujet);
        $nbPlaces       = mysqli_real_escape_string($db, $nbPlaces);
        $nbDates        = mysqli_real_escape_string($db, $nbDates);
        $status         = mysqli_real_escape_string($db, $status);
        $idAnimateur    = mysqli_real_escape_string($db, $idAnimateur);
        $idSalle        = mysqli_real_escape_string($db, $idSalle);
        $idTarif        = mysqli_real_escape_string($db, $idTarif);

        $sql = "UPDATE `tab_session` "
            . "SET `date_session`='" . $date . "', "
            . "`nom_session`='" . $idSessionSujet . "', "
            . "`nbplace_session`='" . $nbPlaces . "', "
            . "`nbre_dates_sessions`='" . $nbDates . "', "
            . "`status_session`='" . $status . "', "
            . "`id_anim`='" . $idAnimateur . "', "
            . "`id_salle`='" . $idSalle . "', "
            . "`id_tarif`='" . $idTarif . "' "
            . "WHERE `id_session`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_date           = $date;
            $this->_idSessionSujet = $idSessionSujet;
            $this->_nbPlaces       = $nbPlaces;
            $this->_nbDates        = $nbDates;
            $this->_status         = $status;
            $this->_idAnimateur    = $idAnimateur;
            $this->_idSalle        = $idSalle;
            $this->_idTarif        = $idTarif;
            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $success = true;
        
        // on ne supprime plus le session une fois que des dates ont été validées !
        if (!$this->hasSessionDatesValidees()) {
            $db = Mysql::opendb();
            
            // suppression des relations 

            foreach ($this->getSessionDates() as $sessionDate) {
                // error_log("suppression de SessionDate->id =" . $sessionDate->getId());
                if ( !$sessionDate->supprimer() ) {
                    $success = false;
                }
            }
            if ($success) {
                // error_log("suppression de Session->id =" . $this->_id);
                $sql    = "DELETE FROM `tab_session` WHERE `id_session`=" . $this->_id;
                $result = mysqli_query($db, $sql);
                
                if (!$result) {
                    $success = false;
                }
            }
            Mysql::closedb($db);
        }
        return $success;
    }


    /*
    * Fonctions statiques
    */
    
    public static function getSessionById($id) {

        $session = null;
        
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_session` "
                 . "WHERE `id_session` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                $session = new Session(mysqli_fetch_assoc($result));
            }
        }
        
        return $session;
    }
    
    public static function creerSession($date, $idSessionSujet, $nbPlaces, $nbDates, $status, $idAnimateur, $idSalle, $idTarif) {
        $session = null;
        
        $db  = Mysql::opendb();
        
        $date           = mysqli_real_escape_string($db, $date);
        $idSessionSujet = mysqli_real_escape_string($db, $idSessionSujet);
        $nbPlaces       = mysqli_real_escape_string($db, $nbPlaces);
        $nbDates        = mysqli_real_escape_string($db, $nbDates);
        $status         = mysqli_real_escape_string($db, $status);
        $idAnimateur    = mysqli_real_escape_string($db, $idAnimateur);
        $idSalle        = mysqli_real_escape_string($db, $idSalle);
        $idTarif        = mysqli_real_escape_string($db, $idTarif);

        $sql = "INSERT INTO `tab_session` (`date_session`,`nom_session`,`nbplace_session`,`nbre_dates_sessions`,`status_session`,`id_anim`,`id_salle`,`id_tarif`) "
             . "VALUES ('" . $date . "', '" . $idSessionSujet . "', '" . $nbPlaces . "', '" . $nbDates . "', '" . $status . "', '" . $idAnimateur . "', '" . $idSalle . "', '" . $idTarif . "')";
        
        $result = mysqli_query($db,$sql);
        
        if ($result) {
            $session = new Session(array(
                "id_session" => mysqli_insert_id($db),
                "date_session" => $date,
                "nom_session" => $idSessionSujet,
                "nbplace_session" => $nbPlaces,
                "nbre_dates_sessions" => $nbDates,
                "status_session" => $status,
                "id_anim" => $idAnimateur,
                "id_salle" => $idSalle,
                "id_tarif" => $idTarif
                ));
        }
        
        Mysql::closedb($db);

        return $session;
    }
    
    
    public static function getSessions() {
        $sessions = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_session";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $sessions = array();
            while($row = mysqli_fetch_assoc($result)) {
                $sessions[] = new Session($row);
            }
            mysqli_free_result($result);
        }
        
        return $sessions;

    }
    
    public static function getSessionsNonCloturees() {
        $sessions = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_session WHERE status_session=0 ORDER BY `date_session` ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $sessions = array();
            while($row = mysqli_fetch_assoc($result)) {
                $sessions[] = new Session($row);
            }
            mysqli_free_result($result);
        }
        
        return $sessions;
    }
    
}