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
require_once("include/class/AtelierNiveau.class.php");
require_once("include/class/AtelierCategorie.class.php");

class SessionSujet {

    private $_id;
    private $_titre;
    private $_detail;
    private $_idNiveau;
    private $_idCategorie;

    private function __construct($array) {
        $this->_id = $array["id_session_sujet"];
        $this->_titre = $array["session_titre"];
        $this->_detail = $array["session_detail"];
        $this->_idNiveau = $array["session_niveau"];
        $this->_idCategorie = $array["session_categorie"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId() {
        return $this->_id;
    }

    public function getTitre() {
        return $this->_titre;
    }

    public function getDetail() {
        return $this->_detail;
    }

    public function getIdNiveau() {
        return $this->_idNiveau;
    }

    public function getNiveau() {
        return AtelierNiveau::getAtelierNiveauById($this->_idNiveau);
    }

    public function getIdCategorie() {
        return $this->_idCategorie;
    }

    public function getCategorie() {
        return AtelierCategorie::getAtelierCategorieById($this->_idCategorie);
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($titre, $detail, $idNiveau, $idCategorie) {

        $success = FALSE;
        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $detail = mysqli_real_escape_string($db, $detail);
        $idNiveau = mysqli_real_escape_string($db, $idNiveau);
        $idCategorie = mysqli_real_escape_string($db, $idCategorie);

        $sql = "UPDATE `tab_session_sujet` "
                . "SET `session_titre`='" . $titre . "', "
                . "`session_detail`='" . $detail . "', "
                . "`session_niveau`='" . $idNiveau . "', "
                . "`session_categorie`='" . $idCategorie . "' "
                . "WHERE `id_session_sujet`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_titre = $titre;
            $this->_detail = $detail;
            $this->_idNiveau = $idNiveau;
            $this->_idCategorie = $idCategorie;
            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_session_sujet` WHERE `id_session_sujet`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        return $result;
    }

    /*
     * Fonctions statiques
     */

    public static function getSessionSujetById($id) {

        $sessionSujet = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_session_sujet` "
                    . "WHERE `id_session_sujet` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $sessionSujet = new SessionSujet(mysqli_fetch_assoc($result));
            }
        }

        return $sessionSujet;
    }

    public static function creerSessionSujet($titre, $detail, $idNiveau, $idCategorie) {
        $sessionSujet = null;

        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $detail = mysqli_real_escape_string($db, $detail);
        $idNiveau = mysqli_real_escape_string($db, $idNiveau);
        $idCategorie = mysqli_real_escape_string($db, $idCategorie);

        $sql = "INSERT INTO `tab_session_sujet` (`session_titre`,`session_detail`,`session_niveau`,`session_categorie`) VALUES ('" . $titre . "', '" . $detail . "', '" . $idNiveau . "', '" . $idCategorie . "')";

        $result = mysqli_query($db, $sql);

        if ($result) {
            $sessionSujet = new SessionSujet(array("id_session_sujet" => mysqli_insert_id($db), "session_titre" => $titre, "session_detail" => $detail, "session_niveau" => $idNiveau, "session_categorie" => $idCategorie));
        }

        Mysql::closedb($db);

        return $sessionSujet;
    }

    public static function getSessionSujets() {
        $sessionSujets = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_session_sujet ORDER BY session_titre";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $sessionSujets = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $sessionSujets[] = new SessionSujet($row);
            }
            mysqli_free_result($result);
        }

        return $sessionSujets;
    }

}
