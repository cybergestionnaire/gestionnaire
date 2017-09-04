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
require_once("Tarif.class.php");

class Transaction {

    private $_id;
    private $_type;
    private $_idUtilisateur;
    private $_nombreForfait;
    private $_date;
    private $_statut;

    public function __construct($array) {
        $this->_id = $array["id_transac"];
        $this->_type = $array["type_transac"];
        $this->_idUtilisateur = $array["id_user"];
        $this->_idTarif = $array["id_tarif"];
        $this->_nombreForfait = $array["nbr_forfait"];
        $this->_date = $array["date_transac"];
        $this->_statut = $array["status_transac"];
    }

    public function getId() {
        return $this->_id;
    }

    public function getType() {
        return $this->_type;
    }

    public function getIdUtilisateur() {
        return $this->_idUtilisateur;
    }

    public function getIdTarif() {
        return $this->_idTarif;
    }

    public function getTarif() {
        return Tarif::getTarifById($this->_idTarif);
    }

    public function getNombreForfait() {
        return $this->_nombreForfait;
    }

    public function getDate() {
        return $this->_date;
    }

    public function getStatut() {
        return $this->_statut;
    }

    public function modifier($type, $idUtilisateur, $idTarif, $nombreForfait, $date, $statut) {
        $success = FALSE;
        $db = Mysql::opendb();

        $type = mysqli_real_escape_string($db, $type);
        $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
        $idTarif = mysqli_real_escape_string($db, $idTarif);
        $nombreForfait = mysqli_real_escape_string($db, $nombreForfait);
        $date = mysqli_real_escape_string($db, $date);
        $statut = mysqli_real_escape_string($db, $statut);

        $sql = "UPDATE `tab_transactions` "
                . "SET `type_transac` = '" . $type . "', `id_user` = '" . $idUtilisateur . "', `id_tarif` = '" . $idTarif . "', `nbr_forfait` = '" . $nombreForfait . "', `date_transac` = '" . $date . "', `status_transac` = '" . $statut . "' "
                . "WHERE `id_transac` = " . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_type = $type;
            $this->_idUtilisateur = $idUtilisateur;
            $this->_idTarif = $idTarif;
            $this->_nombreForfait = $nombreForfait;
            $this->_date = $date;
            $this->_statut = $statut;

            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $success = false;
        $db = Mysql::opendb();

        // vérification des relations
        $sql = "DELETE FROM rel_user_forfait WHERE id_transac=" . $this->_id;
        $sql2 = "DELETE FROM `tab_transactions` WHERE `id_transac`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        $result2 = mysqli_query($db, $sql2);
        if ($result && $result2) {
            $success = true;
        }
        Mysql::closedb($db);

        return $success;
    }

    public static function creerTransaction($type, $idUtilisateur, $idTarif, $nombreForfait, $date, $statut) {
        $transaction = null;
        if ($type != "" && $idUtilisateur != 0
        ) {
            $db = Mysql::opendb();

            $type = mysqli_real_escape_string($db, $type);
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $idTarif = mysqli_real_escape_string($db, $idTarif);
            $nombreForfait = mysqli_real_escape_string($db, $nombreForfait);
            $date = mysqli_real_escape_string($db, $date);
            $statut = mysqli_real_escape_string($db, $statut);


            $sql = "INSERT INTO `tab_transactions` (`type_transac`,`id_user`,`id_tarif`,`nbr_forfait`,`date_transac`,`status_transac`) "
                    . "VALUES ('" . $type . "', '" . $idUtilisateur . "', '" . $idTarif . "', '" . $nombreForfait . "', '" . $date . "', '" . $statut . "') ";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $transaction = new Transaction(array("id_transac" => mysqli_insert_id($db), "type_transac" => $type, "id_user" => $idUtilisateur, "id_tarif" => $idTarif, "nbr_forfait" => $nombreForfait, "date_transac" => $date, "status_transac" => $statut));
            }

            Mysql::closedb($db);
        }
        return $transaction;
    }

    public static function getTransactionById($id) {
        $transaction = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_transactions` "
                    . "WHERE `id_transac` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $transaction = new Transaction(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $transaction;
    }

    public static function getTransactions() {

        $transactions = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_transactions";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $transactions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = new Transaction($row);
            }
            mysqli_free_result($result);
        }

        return $transactions;
    }

    public static function getTransactionsByUtilisateurAndType($idUtilisateur, $type) {

        $transactions = null;

        $db = Mysql::opendb();

        $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
        $type = mysqli_real_escape_string($db, $type);

        $sql = "SELECT * FROM tab_transactions WHERE id_user=" . $idUtilisateur . " AND type_transac='" . $type . "'";

        // error_log("sql = $sql");
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $transactions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = new Transaction($row);
            }
            mysqli_free_result($result);
        }
        // error_log("getTransactionsByUtilisateurAndType = " . print_r($transactions, true));
        return $transactions;
    }

    public static function getTransactionsEnAttenteByIdutilisateur($idUtilisateur) {
        $transactions = null;

        $db = Mysql::opendb();

        $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);

        $sql = "SELECT * FROM `tab_transactions` WHERE `status_transac` = 0 AND `id_user`=" . $idUtilisateur;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $transactions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = new Transaction($row);
            }
            mysqli_free_result($result);
        }

        return $transactions;
    }

}
