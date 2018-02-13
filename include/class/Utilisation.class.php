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
//require_once("Espace.class.php");

/**
 * La classe Espace permer "d'abstraire" les données venant de la table tab_espace.
 *
 * Toutes les manipulations sur la table tab_espace devrait passer par une fonction
 * de cette classe.
 */
class Utilisation
{
    private $_id;
    private $_nom;
    private $_type;
    private $_visible;

    public function __construct($array)
    {
        $this->_id = $array["id_utilisation"];
        $this->_nom = $array["nom_utilisation"];
        $this->_type = $array["type_menu"];
        $this->_visible = $array["visible"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getNom()
    {
        return $this->_nom;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getVisible()
    {
        return $this->_visible;
    }

    public function modifier($nom, $type, $visible)
    {
        $success = false;
        $db = Mysql::opendb();

        if ($nom != ""
        ) {
            $db = Mysql::opendb();

            $nom = mysqli_real_escape_string($db, $nom);
            $type = mysqli_real_escape_string($db, $type);
            $visible = mysqli_real_escape_string($db, $visible);

            $sql = "UPDATE `tab_utilisation` "
                    . "SET `nom_utilisation` = '" . $nom . "', `type_menu` = '" . $type . "', `visible` = '" . $visible . "' "
                    . "WHERE `id_utilisation` = " . $this->_id;

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);
            if ($result) {
                $this->_nom = $nom;
                $this->_type = $type;
                $this->_visible = $visible;

                $success = true;
            }
        }
        return $success;
    }

    public function supprimer()
    {
        $success = false;

        $db = Mysql::opendb();
        // on efface d'abord les relations
        $sql = "DELETE FROM `rel_utilisation_user` WHERE `id_utilisation` = " . $this->_id;
        $result = mysqli_query($db, $sql);

        if ($result) {
            // si ça marche, on efface l'utilisation
            $sql2 = "DELETE FROM `tab_utilisation` WHERE `id_utilisation` = " . $this->_id;
            $result2 = mysqli_query($db, $sql2);

            if ($result2) {
                $success = true;
            }
        }

        Mysql::closedb($db);

        return $success;
    }

    public static function creerUtilisation($nom, $type, $visible)
    {
        $utilisation = null;

        if ($nom != ""
        ) {
            $db = Mysql::opendb();

            $nom = mysqli_real_escape_string($db, $nom);
            $type = mysqli_real_escape_string($db, $type);
            $visible = mysqli_real_escape_string($db, $visible);


            $sql = "INSERT INTO `tab_utilisation` (`nom_utilisation`,`type_menu`,`visible`) "
                    . "VALUES ('" . $nom . "', '" . $type . "', '" . $visible . "') ";
            $result = mysqli_query($db, $sql);

            if ($result) {
                $utilisation = new Utilisation(array("id_utilisation" => mysqli_insert_id($db), "nom_utilisation" => $nom, "type_menu" => $type, "visible" => $visible));
            }

            Mysql::closedb($db);
        }
        return $utilisation;
    }

    public static function getUtilisationById($id)
    {
        $utilisation = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_utilisation` "
                    . "WHERE `id_utilisation` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $utilisation = new Utilisation(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $utilisation;
    }

    public static function getUtilisations()
    {
        $utilisations = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_utilisation ORDER BY id_utilisation";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisations = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $utilisations[] = new Utilisation($row);
            }
            mysqli_free_result($result);
        }

        return $utilisations;
    }
}
