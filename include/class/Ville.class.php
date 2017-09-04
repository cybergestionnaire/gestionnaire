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

/**
 * La classe Ville permer "d'abstraire" les données venant de la table tab_city.
 *
 * Toutes les manipulations sur la table tab_city devrait passer par une fonction 
 * de cette classe.
 */
class Ville {

    private $_id;
    private $_nom;
    private $_codePostal;
    private $_pays;

    /**
     * constructeur privé : il est appelé uniquement par les méthodes statiques de la classe
     *
     * Il ne devrait pas y avoir de "new Ville()" ailleurs que dans la classe elle-même.
     * Charge à chaque fonction statique de renvoyer le ou les objets qui vont bien.
     *
     * @param ArrayObject $array Tableau associatif contenant les données d'initialisation de l'objet
     *                           les clés utilisées dépendent du nommage des champs dans la table "tab_city"
     */
    private function __construct($array) {
        $this->_id = $array["id_city"];
        $this->_nom = $array["nom_city"];
        $this->_codePostal = $array["code_postale_city"];
        $this->_pays = $array["pays_city"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId() {
        return $this->_id;
    }

    public function getNom() {
        return $this->_nom;
    }

    public function getCodePostal() {
        return $this->_codePostal;
    }

    public function getPays() {
        return $this->_pays;
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($nom, $codePostal, $pays) {
        $success = FALSE;
        $db = Mysql::opendb();

        $nom = mysqli_real_escape_string($db, $nom);
        $codePostal = mysqli_real_escape_string($db, $codePostal);
        $pays = mysqli_real_escape_string($db, $pays);

        $sql = "UPDATE `tab_city` "
                . "SET `nom_city`='" . $nom . "', `code_postale_city`='" . $codePostal . "', `pays_city`='" . $pays . "' "
                . "WHERE `id_city`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_nom = $nom;
            $this->_codePostal = $codePostal;
            $this->_pays = $pays;

            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {

        // Verification avant suppression si il n'y a plus d'adherents
        $db = Mysql::opendb();
        $sql = "SELECT `id_user` FROM `tab_user` WHERE `ville_user`=" . $this->_id;
        $result = mysqli_query($db, $sql);


        if ($result == FALSE) {
            return 0; // echec de la requete
        } else {
            ;
            if (mysqli_num_rows($result) > 0) {
                return 1; // il reste des utilisateurs lies a la ville
            } else {
                // Suppression de la ville
                $sql2 = "DELETE FROM `tab_city` WHERE `id_city`=" . $this->_id;
                $result = mysqli_query($db, $sql2);
                if ($result == FALSE) {
                    return 0;
                } else {
                    return 2;
                }
            }
        }
        Mysql::closedb($db);
    }

    public function nbAdherents() {

        $db = Mysql::opendb();

        $sql = "SELECT count(`id_user`) AS nb FROM `tab_user` "
                . "WHERE `ville_user` = '" . $this->_id . "' "
                . "AND `status_user` < 3";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $row = mysqli_fetch_array($result);
            return intval($row['nb']);
        } else {
            return 0;
        }
    }

    /*
     * Fonctions statiques
     */

    public static function getVilleById($id) {

        $ville = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_city` "
                    . "WHERE `id_city` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $ville = new Ville(mysqli_fetch_assoc($result));
            }
        }

        return $ville;
    }

    public static function creerVille($nom, $codePostal, $pays) {
        $ville = null;

        $db = Mysql::opendb();

        $nom = mysqli_real_escape_string($db, $nom);
        $codePostal = mysqli_real_escape_string($db, $codePostal);
        $pays = mysqli_real_escape_string($db, $pays);

        // est ce que la ville existe déjà ?
        $sql = "SELECT * FROM `tab_city` WHERE `nom_city` = '" . $nom . "' AND `code_postale_city` = '" . $codePostal . "' AND `pays_city` = '" . $pays . "'";

        $result = mysqli_query($db, $sql);

        if ($result && mysqli_num_rows($result) == 0) {
            // ok, on n'a pas de ville correspondante
            $sql = "INSERT INTO `tab_city` (`id_city`,`nom_city`, `code_postale_city`, `pays_city`) VALUES ('','" . $nom . "','" . $codePostal . "','" . $pays . "')";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $ville = new Ville(array("id_city" => mysqli_insert_id($db), "nom_city" => $nom, "code_postale_city" => $codePostal, "pays_city" => $pays));
            }
        }
        // est ce qu'on devrait créer l'objet si une ville existante est trouvée ??? Pour le moment, non, mais à réfléchir.

        Mysql::closedb($db);

        return $ville;
    }

    public static function getVilles() {

        $villes = null;

        $db = Mysql::opendb();
        $sql = "SELECT `id_city`,`nom_city`,`code_postale_city`,`pays_city` FROM `tab_city` ORDER BY nom_city";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $villes = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $villes[] = new Ville($row);
            }
        }

        return $villes;
    }

}
