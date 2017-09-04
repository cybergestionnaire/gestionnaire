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

class Message {

    private $_id;
    private $_date;
    private $_idAuteur;
    private $_texte;
    private $_tag;
    private $_idDestinataire;

    public function __construct($array) {
        $this->_id = $array["id_messages"];
        $this->_date = $array["mes_date"];
        $this->_idAuteur = $array["mes_auteur"];
        $this->_texte = $array["mes_txt"];
        $this->_tag = $array["mes_tag"];
        $this->_idDestinataire = $array["mes_destinataire"];
    }

    public function getId() {
        return $this->_id;
    }

    public function getDate() {
        return $this->_date;
    }

    public function getIdAuteur() {
        return $this->_idAuteur;
    }

    public function getAuteur() {
        return utilisateur::getUtilisateurById($this->_idAuteur);
    }

    public function getTexte() {
        return $this->_texte;
    }

    public function getTag() {
        return $this->_tag;
    }

    public function getIdDestinataire() {
        return $this->_idDestinataire;
    }

    public function getDestinataire() {
        return utilisateur::getUtilisateurById($this->_idDestinataire);
    }

    public function modifier($date, $idAuteur, $texte, $tag, $idDestinataire) {
        $success = FALSE;
        $db = Mysql::opendb();

        $date = mysqli_real_escape_string($db, $date);
        $idAuteur = mysqli_real_escape_string($db, $idAuteur);
        $texte = mysqli_real_escape_string($db, $texte);
        $tag = mysqli_real_escape_string($db, $tag);
        $idDestinataire = mysqli_real_escape_string($db, $idDestinataire);

        $sql = "UPDATE `tab_messages` "
                . "SET `mes_date` = '" . $date . "', `mes_auteur` = '" . $idAuteur . "', `mes_txt` = '" . $texte . "', `mes_tag` = '" . $tag . "', `mes_destinataire` = '" . $idDestinataire . "' "
                . "WHERE `id_messages` = " . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_date = $date;
            $this->_idAuteur = $idAuteur;
            $this->_texte = $texte;
            $this->_tag = $tag;
            $this->_idDestinataire = $idDestinataire;

            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $success = false;

        $sql = "DELETE FROM `tab_messages` WHERE `id_messages`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }
        return $success;
    }

    public static function creerMessage($date, $idAuteur, $texte, $tag, $idDestinataire) {
        $message = null;

        if ($date != "" && (is_int($idAuteur) && $idAuteur != 0)
        ) {
            $db = Mysql::opendb();

            $date = mysqli_real_escape_string($db, $date);
            $idAuteur = mysqli_real_escape_string($db, $idAuteur);
            $texte = mysqli_real_escape_string($db, $texte);
            $tag = mysqli_real_escape_string($db, $tag);
            $idDestinataire = mysqli_real_escape_string($db, $idDestinataire);


            $sql = "INSERT INTO `tab_messages` (`mes_date`, `mes_auteur`, `mes_txt`, `mes_tag`, `mes_destinataire`) "
                    . "VALUES ('" . $date . "','" . $idAuteur . "','" . $texte . "','" . $tag . "','" . $idDestinataire . "')";
            $result = mysqli_query($db, $sql);

            if ($result) {
                $message = new Message(array("id_messages" => mysqli_insert_id($db), "mes_date" => $date, "mes_auteur" => $idAuteur, "mes_txt" => $texte, "mes_tag" => $tag, "mes_destinataire" => $idDestinataire));
            }

            Mysql::closedb($db);
        }
        return $message;
    }

    public static function getMessageById($id) {
        $message = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_messages` "
                    . "WHERE `id_messages` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $message = new Message(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $message;
    }

    public static function getMessages() {

        $messages = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_messages ORDER BY date ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $messages = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $messages[] = new Message($row);
            }
            mysqli_free_result($result);
        }

        return $messages;
    }

}
