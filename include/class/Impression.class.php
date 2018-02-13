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
//require_once("Tarif.class.php");

class Impression
{
    private $_id;
    private $_date;
    private $_idUtilisateur;
    private $_nombreImpression;
    private $_idTarif;
    private $_statut;
    private $_credit;
    private $_userExterne;
    private $_idEspace;
    private $_idCaissier;
    private $_paiement;

    public function __construct($array)
    {
        $this->_id = $array["id_print"];
        $this->_date = $array["print_date"];
        $this->_idUtilisateur = $array["print_user"];
        $this->_nombreImpression = $array["print_debit"];
        $this->_idTarif = $array["print_tarif"];
        $this->_statut = $array["print_statut"];
        $this->_credit = $array["print_credit"];
        $this->_userExterne = $array["print_userexterne"];
        $this->_idEspace = $array["print_epn"];
        $this->_idCaissier = $array["print_caissier"];
        $this->_paiement = $array["print_paiement"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getIdUtilisateur()
    {
        return $this->_idUtilisateur;
    }

    public function getUtilisateur()
    {
        return Utilisateur::getUtilisateurById($this->_idUtilisateur);
    }

    public function getNombreImpression()
    {
        return $this->_nombreImpression;
    }

    public function getIdTarif()
    {
        return $this->_idTarif;
    }

    public function getTarif()
    {
        return Tarif::getTarifById($this->_idTarif);
    }

    public function getStatut()
    {
        return $this->_statut;
    }

    public function getCredit()
    {
        return $this->_credit;
    }

    public function getUserExterne()
    {
        return $this->_userExterne;
    }

    public function getIdEspace()
    {
        return $this->_idEspace;
    }

    public function getEspace()
    {
        return Espace::getEspaceById($this->_idEspace);
    }

    public function getIdCaissier()
    {
        return $this->_idCaissier;
    }

    public function getCaissier()
    {
        return Utilisateur::getUtilisateurById($this->_idCaissier);
    }

    public function getPaiement()
    {
        return $this->_paiement;
    }

    public function modifier($date, $idUtilisateur, $nombreImpression, $idTarif, $statut, $credit, $userExterne, $idEspace, $idCaissier, $paiement)
    {
        $success = false;
        $db = Mysql::opendb();

        $date = mysqli_real_escape_string($db, $date);
        $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
        $nombreImpression = mysqli_real_escape_string($db, $nombreImpression);
        $idTarif = mysqli_real_escape_string($db, $idTarif);
        $statut = mysqli_real_escape_string($db, $statut);
        $credit = mysqli_real_escape_string($db, $credit);
        $userExterne = mysqli_real_escape_string($db, $userExterne);
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $idCaissier = mysqli_real_escape_string($db, $idCaissier);
        $paiement = mysqli_real_escape_string($db, $paiement);

        $sql = "UPDATE `tab_print` "
                . "SET `print_date` = '" . $date . "', `print_user` = '" . $idUtilisateur . "', `print_debit` = '" . $nombreImpression . "', `print_tarif` = '" . $idTarif . "', `print_statut` = '" . $statut . "', `print_credit` = '" . $credit . "', `print_userexterne` = '" . $userExterne . "', `print_epn` = '" . $idEspace . "', `print_caissier` = '" . $idCaissier . "', `print_paiement` = '" . $paiement . "' "
                . "WHERE `id_print` = " . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_date = $date;
            $this->_idUtilisateur = $idUtilisateur;
            $this->_nombreImpression = $nombreImpression;
            $this->_idTarif = $idTarif;
            $this->_statut = $statut;
            $this->_credit = $credit;
            $this->_userExterne = $userExterne;
            $this->_idEspace = $idEspace;
            $this->_idCaissier = $idCaissier;
            $this->_paiement = $paiement;

            $success = true;
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;
        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_print` WHERE `id_print`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        if ($result) {
            $success = true;
        }
        Mysql::closedb($db);

        return $success;
    }

    public static function creerImpression($date, $idUtilisateur, $nombreImpression, $idTarif, $statut, $credit, $userExterne, $idEspace, $idCaissier, $paiement)
    {
        $impression = null;
        if ($idUtilisateur != 0
        ) {
            $db = Mysql::opendb();

            $date = mysqli_real_escape_string($db, $date);
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $nombreImpression = mysqli_real_escape_string($db, $nombreImpression);
            $idTarif = mysqli_real_escape_string($db, $idTarif);
            $statut = mysqli_real_escape_string($db, $statut);
            $credit = mysqli_real_escape_string($db, $credit);
            $userExterne = mysqli_real_escape_string($db, $userExterne);
            $idEspace = mysqli_real_escape_string($db, $idEspace);
            $idCaissier = mysqli_real_escape_string($db, $idCaissier);
            $paiement = mysqli_real_escape_string($db, $paiement);


            $sql = "INSERT INTO `tab_print` (`print_date`,`print_user`,`print_debit`,`print_tarif`,`print_statut`,`print_credit`,`print_userexterne`,`print_epn`,`print_caissier`,`print_paiement`) "
                    . "VALUES ('" . $date . "', '" . $idUtilisateur . "', '" . $nombreImpression . "', '" . $idTarif . "', '" . $statut . "', '" . $credit . "', '" . $userExterne . "', '" . $idEspace . "', '" . $idCaissier . "', '" . $paiement . "') ";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $impression = new Impression(array("id_print" => mysqli_insert_id($db), "print_date" => $date, "print_user" => $idUtilisateur, "print_debit" => $nombreImpression, "print_tarif" => $idTarif, "print_statut" => $statut, "print_credit" => $credit, "print_userexterne" => $userExterne, "print_epn" => $idEspace, "print_caissier" => $idCaissier, "print_paiement" => $paiement));
            }

            Mysql::closedb($db);
        }
        return $impression;
    }

    public static function getImpressionById($id)
    {
        $impression = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_print` "
                    . "WHERE `id_print` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $impression = new Impression(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $impression;
    }

    public static function getImpressions()
    {
        $impressions = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_print";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $impressions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $impressions[] = new Impression($row);
            }
            mysqli_free_result($result);
        }

        return $impressions;
    }

    public static function getImpressionsByIdUtilisateur($idUtilisateur)
    {
        $impressions = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_print "
                . "WHERE print_user='" . $idUtilisateur . "' "
                . "  AND TO_DAYS(NOW()) - TO_DAYS(print_date) <= 360 "
                . "ORDER BY `print_date` DESC ";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            $impressions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $impressions[] = new Impression($row);
            }
            mysqli_free_result($result);
        }

        return $impressions;
    }

    public static function getImpressionsDuJour()
    {
        return self::getImpressionsByDate("CURDATE()");
    }

    public static function getImpressionsByDate($date)
    {
        $impressions = null;

        $db = Mysql::opendb();
        $sql = "SELECT * "
                . "FROM tab_print "
                . "WHERE DATE(print_date)=" . $date . " "
                . "  AND print_statut < 2 "
                . "ORDER BY print_date DESC";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            $impressions = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $impressions[] = new Impression($row);
            }
            mysqli_free_result($result);
        }

        return $impressions;
    }
}
