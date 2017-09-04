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

class Captcha {

    private $_id;
    private $_activation;
    private $_code;

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
        $this->_id = $array["id_captcha"];
        $this->_activation = $array["capt_activation"];
        $this->_code = $array["capt_code"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId() {
        return $this->_id;
    }

    public function getActivation() {
        return $this->_activation;
    }

    public function getCode() {
        return $this->_code;
    }

    public function isActive() {
        return $this->_activation == "Y" ? true : false;
    }

    /*
     * Fonctions de l'objet
     */

    public function modifier($activation, $code) {
        $success = FALSE;
        $db = Mysql::opendb();

        $activation = mysqli_real_escape_string($db, $activation);
        $code = mysqli_real_escape_string($db, $code);

        $sql = "UPDATE `tab_captcha` "
                . "SET `capt_activation`='" . $activation . "', "
                . "`capt_code`='" . $code . "' "
                . "WHERE `id_captcha`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_activation = $activation;
            $this->_code = $code;

            $success = TRUE;
        }

        return $success;
    }

    /**
     * la fonction "supprimer" ne devrait jamais être appelée !
     */
    public function supprimer() {
        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_captcha` WHERE `id_captcha`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        return $result;
    }

    /*
     * Fonctions statiques
     */

    public static function getCaptchaById($id) {

        $captcha = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_captcha` "
                    . "WHERE `id_captcha` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $captcha = new Captcha(mysqli_fetch_assoc($result));
            }
        }

        return $captcha;
    }

    public static function creerCaptcha($activation, $code) {
        $captcha = null;

        $db = Mysql::opendb();

        $activation = mysqli_real_escape_string($db, $activation);
        $code = mysqli_real_escape_string($db, $code);

        $sql = "SELECT * FROM `tab_captcha`";

        $result = mysqli_query($db, $sql);

        if ($result && mysqli_num_rows($result) == 0) {
            // ok, on n'a pas de captcha
            $sql = "INSERT INTO `tab_captcha` (`capt_activation`, `capt_code`) VALUES ('" . $activation . "', '" . $code . "')";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $captcha = new Captcha(array("id_captcha" => mysqli_insert_id($db), "capt_activation" => $activation, "capt_code" => $code));
            }
        }

        Mysql::closedb($db);

        return $captcha;
    }

    public static function getCaptcha() {
        return self::getCaptchaById(1);
    }

}
