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

class AtelierNiveau
{
    private $_id;
    private $_code;
    private $_nom;

    private function __construct($array)
    {
        $this->_id = $array["id_level"];
        $this->_code = $array["code_level"];
        $this->_nom = $array["nom_level"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId()
    {
        return $this->_id;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function getNom()
    {
        return $this->_nom;
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($code, $nom)
    {
        $success = false;
        $db = Mysql::opendb();

        $code = mysqli_real_escape_string($db, $code);
        $nom = mysqli_real_escape_string($db, $nom);

        $sql = "UPDATE `tab_level` "
                . "SET `code_level`='" . $code . "', "
                . "`nom_level`='" . $nom . "' "
                . "WHERE `id_level`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_code = $code;
            $this->_nom = $nom;

            $success = true;
        }

        return $success;
    }

    public function supprimer()
    {
        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_level` WHERE `id_level`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        return $result;
    }

    /*
     * Fonctions statiques
     */

    public static function getAtelierNiveauById($id)
    {
        $atelierNiveau = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_level` "
                    . "WHERE `id_level` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $atelierNiveau = new AtelierNiveau(mysqli_fetch_assoc($result));
            }
        }

        return $atelierNiveau;
    }

    public static function creerAtelierNiveau($code, $nom)
    {
        $atelierNiveau = null;

        $db = Mysql::opendb();

        $code = mysqli_real_escape_string($db, $code);
        $nom = mysqli_real_escape_string($db, $nom);

        $sql = "INSERT INTO `tab_level` (`code_level`, `nom_level`) VALUES ('" . $code . "', '" . $nom . "')";

        $result = mysqli_query($db, $sql);

        if ($result) {
            $atelierNiveau = new atelierNiveau(array("id_level" => mysqli_insert_id($db), "code_level" => $code, "nom_level" => $nom));
        }

        Mysql::closedb($db);

        return $atelierNiveau;
    }

    public static function getAtelierNiveaux()
    {
        $atelierNiveaux = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_level";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $atelierNiveaux = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $atelierNiveaux[] = new AtelierNiveau($row);
            }
            mysqli_free_result($result);
        }

        return $atelierNiveaux;
    }
}
