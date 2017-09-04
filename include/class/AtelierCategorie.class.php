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

class AtelierCategorie {

    private $_id;
    private $_label;

    private function __construct($array) {
        $this->_id = $array["id_atelier_categorie"];
        $this->_label = $array["label_categorie"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId() {
        return $this->_id;
    }

    public function getLabel() {
        return $this->_label;
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($label) {
        $success = FALSE;
        $db = Mysql::opendb();

        $label = mysqli_real_escape_string($db, $label);

        $sql = "UPDATE `tab_atelier_categorie` "
                . "SET `label_categorie`='" . $label . "' "
                . "WHERE `id_atelier_categorie`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_label = $label;

            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_atelier_categorie` WHERE `id_atelier_categorie`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        return $result;
    }

    /*
     * Fonctions statiques
     */

    public static function getAtelierCategorieById($id) {

        $atelierCategorie = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_atelier_categorie` "
                    . "WHERE `id_atelier_categorie` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $atelierCategorie = new AtelierCategorie(mysqli_fetch_assoc($result));
            }
        }

        return $atelierCategorie;
    }

    public static function creerAtelierCategorie($label) {
        $atelierCategorie = null;

        $db = Mysql::opendb();

        $label = mysqli_real_escape_string($db, $label);

        $sql = "INSERT INTO `tab_atelier_categorie` (`label_categorie`) VALUES ('" . $label . "')";

        $result = mysqli_query($db, $sql);

        if ($result) {
            $atelierCategorie = new AtelierCategorie(array("id_atelier_categorie" => mysqli_insert_id($db), "label_categorie" => $label));
        }

        Mysql::closedb($db);

        return $atelierCategorie;
    }

    public static function getAtelierCategories() {
        $atelierCategories = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_atelier_categorie";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $atelierCategories = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $atelierCategories[] = new AtelierCategorie($row);
            }
            mysqli_free_result($result);
        }

        return $atelierCategories;
    }

}
