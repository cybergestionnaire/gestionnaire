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

//require_once("Mysql.class.php");
//require_once("Atelier.class.php");
//require_once("Utilisateur.class.php");
//require_once("Espace.class.php");

class StatAtelierSession
{
    private $_id;
    private $_type;
    private $_idAtelierSession;
    private $_dateAtelierSession;
    private $_nbInscrits;
    private $_nbPresents;
    private $_nbAbsents;
    private $_nbEnAttente;
    private $_nbPlaces;
    private $_idCategorie;
    private $_statut;
    private $_idAnimateur;
    private $_idEspace;

    public function __construct($array)
    {
        $this->_id = $array["id_stat"];
        $this->_type = $array["type_AS"];
        $this->_idAtelierSession = $array["id_AS"];
        $this->_dateAtelierSession = $array["date_AS"];
        $this->_nbInscrits = $array["inscrits"];
        $this->_nbPresents = $array["presents"];
        $this->_nbAbsents = $array["absents"];
        $this->_nbEnAttente = $array["attente"];
        $this->_nbPlaces = $array["nbplace"];
        $this->_idCategorie = $array["id_categorie"];
        $this->_statut = $array["statut_programmation"];
        $this->_idAnimateur = $array["id_anim"];
        $this->_idEspace = $array["id_epn"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getidAtelierSession()
    {
        return $this->_idAtelierSession;
    }

    public function getAtelierSession()
    {
        $atelierSession = null;
        if ($this->_type = 'a') {
            $atelierSession = Atelier::getAtelierById($this->_idAtelierSession);
        } elseif ($this->_type = 's') {
            //TODO : créer l'objer session
            $atelierSession = Session::getSessionById($this->_idAtelierSession);
        }

        return $atelierSession;
    }

    public function getdateAtelierSession()
    {
        return $this->_dateAtelierSession;
    }

    public function getNbInscrits()
    {
        return $this->_nbInscrits;
    }

    public function getNbPresents()
    {
        return $this->_nbPresents;
    }

    public function getNbAbsents()
    {
        return $this->_nbAbsents;
    }

    public function getNbEnAttente()
    {
        return $this->_nbEnAttente;
    }

    public function getNbPlaces()
    {
        return $this->_nbPlaces;
    }

    public function getIdCategorie()
    {
        return $this->_idCategorie;
    }

    public function getStatut()
    {
        return $this->_statut;
    }

    public function getIdAnimateur()
    {
        return $this->_idAnimateur;
    }

    public function getAnimateur()
    {
        return Utilisateur::getUtilisateurById($this->_idAnimateur);
    }

    public function getIdEspace()
    {
        return $this->_idEspace;
    }

    public function getEspace()
    {
        return Espace::getEspaceById($this->_idEspace);
    }

    public function modifier($type, $idAtelierSession, $dateAtelierSession, $nbInscrits, $nbPresents, $nbAbsents, $nbEnAttente, $nbPlaces, $idCategorie, $statut, $idAnimateur, $idEspace)
    {
        $success = false;

        $db = Mysql::opendb();

        if ($idAtelierSession != "" && $dateAtelierSession != "" && $idAnimateur != "" && $idEspace != ""
        ) {
            $db = Mysql::opendb();

            $type = mysqli_real_escape_string($db, $type);
            $idAtelierSession = mysqli_real_escape_string($db, $idAtelierSession);
            $dateAtelierSession = mysqli_real_escape_string($db, $dateAtelierSession);
            $nbInscrits = mysqli_real_escape_string($db, $nbInscrits);
            $nbPresents = mysqli_real_escape_string($db, $nbPresents);
            $nbAbsents = mysqli_real_escape_string($db, $nbAbsents);
            $nbEnAttente = mysqli_real_escape_string($db, $nbEnAttente);
            $nbPlaces = mysqli_real_escape_string($db, $nbPlaces);
            $idCategorie = mysqli_real_escape_string($db, $idCategorie);
            $statut = mysqli_real_escape_string($db, $statut);
            $idAnimateur = mysqli_real_escape_string($db, $idAnimateur);
            $idEspace = mysqli_real_escape_string($db, $idEspace);


            $sql = "UPDATE `tab_as_stat` "
                    . "SET type_AS= '" . $type . "', "
                    . "id_AS='" . $idAtelierSession . "', "
                    . "date_AS='" . $dateAtelierSession . "', "
                    . "inscrits='" . $nbInscrits . "', "
                    . "presents='" . $nbPresents . "', "
                    . "absents= '" . $nbAbsents . "', "
                    . "attente='" . $nbEnAttente . "', "
                    . "nbplace='" . $nbPlaces . "', "
                    . "id_categorie='" . $idCategorie . "', "
                    . "statut_programmation='" . $statut . "', "
                    . "id_anim= '" . $idAnimateur . "', "
                    . "id_epn='" . $idEspace . "' "
                    . "WHERE id_stat='" . $this->_id . "'";

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result) {
                $this->_type = $type;
                $this->_idAtelierSession = $idAtelierSession;
                $this->_dateAtelierSession = $dateAtelierSession;
                $this->_nbInscrits = $nbInscrits;
                $this->_nbPresents = $nbPresents;
                $this->_nbAbsents = $nbAbsents;
                $this->_nbEnAttente = $nbEnAttente;
                $this->_nbPlaces = $nbPlaces;
                $this->_idCategorie = $idCategorie;
                $this->_statut = $statut;
                $this->_idAnimateur = $idAnimateur;
                $this->_idEspace = $idEspace;

                $success = true;
            }
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;

        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_as_stat` WHERE `id_stat`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }
        return $success;
    }

    public static function creerStatAtelierSession($type, $idAtelierSession, $dateAtelierSession, $nbInscrits, $nbPresents, $nbAbsents, $nbEnAttente, $nbPlaces, $idCategorie, $statut, $idAnimateur, $idEspace)
    {
        $statAtelier = null;

        if ($idAtelierSession != "" && $dateAtelierSession != "" && $idAnimateur != "" && $idEspace != ""
        ) {
            $db = Mysql::opendb();

            $type = mysqli_real_escape_string($db, $type);
            $idAtelierSession = mysqli_real_escape_string($db, $idAtelierSession);
            $dateAtelierSession = mysqli_real_escape_string($db, $dateAtelierSession);
            $nbInscrits = mysqli_real_escape_string($db, $nbInscrits);
            $nbPresents = mysqli_real_escape_string($db, $nbPresents);
            $nbAbsents = mysqli_real_escape_string($db, $nbAbsents);
            $nbEnAttente = mysqli_real_escape_string($db, $nbEnAttente);
            $nbPlaces = mysqli_real_escape_string($db, $nbPlaces);
            $idCategorie = mysqli_real_escape_string($db, $idCategorie);
            $statut = mysqli_real_escape_string($db, $statut);
            $idAnimateur = mysqli_real_escape_string($db, $idAnimateur);
            $idEspace = mysqli_real_escape_string($db, $idEspace);


            $sql = "INSERT INTO `tab_as_stat` (`type_AS`,`id_AS`, `date_AS`, `inscrits`, `presents`, `absents`, `attente`, `nbplace`, `id_categorie`, `statut_programmation`, `id_anim`, `id_epn`) "
                    . "VALUES ('" . $type . "', '" . $idAtelierSession . "', '" . $dateAtelierSession . "', '" . $nbInscrits . "', '" . $nbPresents . "', '" . $nbAbsents . "', '" . $nbEnAttente . "', '" . $nbPlaces . "', '" . $idCategorie . "', '" . $statut . "', '" . $idAnimateur . "', '" . $idEspace . "') ";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $statAtelier = new StatAtelierSession(array(
                    "id_stat" => mysqli_insert_id($db),
                    "type_AS" => $type,
                    "id_AS" => $idAtelierSession,
                    "date_AS" => $dateAtelierSession,
                    "inscrits" => $nbInscrits,
                    "presents" => $nbPresents,
                    "absents" => $nbAbsents,
                    "attente" => $nbEnAttente,
                    "nbplace" => $nbPlaces,
                    "id_categorie" => $idCategorie,
                    "statut_programmation" => $statut,
                    "id_anim" => $idAnimateur,
                    "id_epn" => $idEspace
                ));
            }

            Mysql::closedb($db);
        }
        return $statAtelier;
    }

    public static function getStatAtelierById($id)
    {
        $statAtelier = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_as_stat` "
                    . "WHERE `type_AS` = 'a' "
                    . "AND `id_stat` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $statAtelier = new StatAtelier(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $statAtelier;
    }

    public static function getStatAteliers()
    {
        $statAteliers = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_as_stat WHERE `type_AS` = 'a' ORDER BY date_AS ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $statAteliers = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $statAteliers[] = new StatAtelier($row);
            }
            mysqli_free_result($result);
        }

        return $statAteliers;
    }

    public static function getStatAteliersArchivesParAnnee($annee)
    {
        $statAteliers = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_as_stat "
                . "WHERE `type_AS` = 'a' "
                . "AND `statut_programmation`=1 "
                . "AND YEAR(`date_AS`)=" . $annee . " "
                . "ORDER BY date_AS ASC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $statAteliers = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $statAteliers[] = new StatAtelierSession($row);
            }
            mysqli_free_result($result);
        }

        return $statAteliers;
    }

    public static function getStatAteliersArchivesParAnneeEtParAnimateur($annee, $idAnimateur)
    {
        $statAteliers = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_as_stat "
                . "WHERE `type_AS` = 'a' "
                . "AND `statut_programmation`=1 "
                . "AND YEAR(`date_AS`)=" . $annee . " "
                . "AND `id_anim` =" . $idAnimateur . " "
                . "ORDER BY date_AS ASC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $statAteliers = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $statAteliers[] = new StatAtelierSession($row);
            }
            mysqli_free_result($result);
        }

        return $statAteliers;
    }

    public static function getStatAtelierByIdAtelier($idAtelier)
    {
        $statAtelier = null;

        if ($idAtelier != 0) {
            $db = Mysql::opendb();
            $idAtelier = mysqli_real_escape_string($db, $idAtelier);
            $sql = "SELECT * "
                    . "FROM `tab_as_stat` "
                    . "WHERE `type_AS` = 'a' "
                    . "AND `id_AS` = " . $idAtelier . "";

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $statAtelier = new StatAtelierSession(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $statAtelier;
    }

    public static function getStatSessionByIdSessionAndDate($idSession, $date)
    {
        $statSession = null;

        if ($idSession != 0) {
            $db = Mysql::opendb();
            $idSession = mysqli_real_escape_string($db, $idSession);
            $sql = "SELECT * "
                    . "FROM `tab_as_stat` "
                    . "WHERE `type_AS` = 's' "
                    . "AND `id_AS` = " . $idSession . " "
                    . "AND `date_AS` = '" . $date . "'";

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $statSession = new StatAtelierSession(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $statSession;
    }

    public static function getStatSessionsArchiveesParAnnee($annee)
    {
        $statAteliers = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_as_stat "
                . "WHERE `type_AS` = 's' "
                . "AND `statut_programmation`=1 "
                . "AND YEAR(`date_AS`)=" . $annee . " "
                . "ORDER BY date_AS ASC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $statAteliers = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $statAteliers[] = new StatAtelierSession($row);
            }
            mysqli_free_result($result);
        }

        return $statAteliers;
    }

    //retourne les années contenues dans les ateliers et sessions
    public static function getYearStatAtelierSessions()
    {
        $annees = null;

        $db = Mysql::opendb();
        $sql = "SELECT DISTINCT (YEAR( `date_AS` )) AS Y FROM `tab_as_stat` WHERE YEAR( `date_AS` )<YEAR(NOW())";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $annees = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $annees[] = $row["Y"];
            }
            mysqli_free_result($result);
        }

        return $annees;
    }
}
