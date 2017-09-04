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
require_once("Materiel.class.php");

class Resa
{
    private $_id;
    private $_idMateriel;
    private $_idUtilisateur;
    private $_dateResa;
    private $_debut;
    private $_duree;
    private $_date;
    private $_statut;

    public function __construct($array)
    {
        $this->_id = $array["id_resa"];
        $this->_idMateriel = $array["id_computer_resa"];
        $this->_idUtilisateur = $array["id_user_resa"];
        $this->_dateResa = $array["dateresa_resa"];
        $this->_debut = $array["debut_resa"];
        $this->_duree = $array["duree_resa"];
        $this->_date = $array["date_resa"];
        $this->_statut = $array["status_resa"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getIdMateriel()
    {
        return $this->_idMateriel;
    }

    public function getMateriel()
    {
        return Materiel::getMaterielById($this->_idMateriel);
    }

    public function getIdUtilisateur()
    {
        return $this->_idUtilisateur;
    }

    public function getUtilisateur()
    {
        return Utilisateur::getUtilisateurById($this->_idUtilisateur);
    }

    public function getDateResa()
    {
        return $this->_dateResa;
    }

    public function getDebut()
    {
        return $this->_debut;
    }

    public function getDuree()
    {
        return $this->_duree;
    }

    public function setDuree($duree)
    {
        $success = false;

        $db = Mysql::opendb();

        $duree = mysqli_real_escape_string($db, $duree);

        $sql = "UPDATE tab_resa SET duree_resa='" . $duree . "' WHERE id_resa='" . $this->_id . "'";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $this->_duree = $duree;
            $success = true;
        }

        return $success;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getStatut()
    {
        return $this->_statut;
    }

    public function modifier($idMateriel, $idUtilisateur, $dateResa, $debut, $duree, $date, $statut)
    {
        $success = false;

        $db = Mysql::opendb();

        // TODO : controle des champs à affiner !!!
        if ($idMateriel != "" && $idUtilisateur != ""
        ) {
            $db = Mysql::opendb();

            $idMateriel = mysqli_real_escape_string($db, $idMateriel);
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $dateResa = mysqli_real_escape_string($db, $dateResa);
            $debut = mysqli_real_escape_string($db, $debut);
            $duree = mysqli_real_escape_string($db, $duree);
            $date = mysqli_real_escape_string($db, $date);
            $statut = mysqli_real_escape_string($db, $statut);


            $sql = "UPDATE `tab_resas` "
                    . "SET id_computer_resa= '" . $idMateriel . "', "
                    . "id_user_resa='" . $idUtilisateur . "', "
                    . "dateresa_resa='" . $dateResa . "', "
                    . "debut_resa='" . $debut . "', "
                    . "duree_resa='" . $duree . "', "
                    . "date_resa= '" . $date . "', "
                    . "status_resa='" . $statut . "' "
                    . "WHERE id_resa='" . $this->_id . "'";

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result) {
                $this->_idMateriel = $idMateriel;
                $this->_idUtilisateur = $idUtilisateur;
                $this->_dateResa = $dateResa;
                $this->_debut = $debut;
                $this->_duree = $duree;
                $this->_date = $date;
                $this->_statut = $statut;

                $success = true;
            }
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;

        $db = Mysql::opendb();
        $sql = "DELETE FROM `tab_resa` WHERE `id_resa`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }
        return $success;
    }

    public static function creerResa($idMateriel, $idUtilisateur, $dateResa, $debut, $duree, $date, $statut)
    {
        $resa = null;

        // TODO : controles à affiner
        if ($idMateriel != "" && $idUtilisateur != ""
        ) {
            $db = Mysql::opendb();

            $idMateriel = mysqli_real_escape_string($db, $idMateriel);
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $dateResa = mysqli_real_escape_string($db, $dateResa);
            $debut = mysqli_real_escape_string($db, $debut);
            $duree = mysqli_real_escape_string($db, $duree);
            $date = mysqli_real_escape_string($db, $date);
            $statut = mysqli_real_escape_string($db, $statut);


            $sql = "INSERT INTO `tab_resa` (`id_computer_resa`,`id_user_resa`, `dateresa_resa`, `debut_resa`, `duree_resa`, `date_resa`, `status_resa`) "
                    . "VALUES ('" . $idMateriel . "', '" . $idUtilisateur . "', '" . $dateResa . "', '" . $debut . "', '" . $duree . "', '" . $date . "', '" . $statut . "') ";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $resa = new Resa(array(
                    "id_resa" => mysqli_insert_id($db),
                    "id_computer_resa" => $idMateriel,
                    "id_user_resa" => $idUtilisateur,
                    "dateresa_resa" => $dateResa,
                    "debut_resa" => $debut,
                    "duree_resa" => $duree,
                    "date_resa" => $date,
                    "status_resa" => $statut
                ));
            }

            Mysql::closedb($db);
        }
        return $resa;
    }

    public static function getResaById($id)
    {
        $resa = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_resa` "
                    . "WHERE `id_resa` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $resa = new Resa(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $resa;
    }

    public static function getResas()
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa ORDER BY date_resa ASC, debut_resa ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public function getResasParIdUtilisateur($idUtilisateur)
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa WHERE `id_user_resa`=" . $idUtilisateur;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasFuturesParIdUtilisateur($idUtilisateur)
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa WHERE `id_user_resa`=" . $idUtilisateur . " AND `dateresa_resa`>'" . date("Y-m-d") . "' ";
        ;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasPasseesParIdUtilisateur($idUtilisateur)
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa WHERE `id_user_resa`=" . $idUtilisateur . " AND `dateresa_resa`<='" . date("Y-m-d") . "' ";
        ;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasParIdUtilisateurEtParMois($idUtilisateur, $mois, $annee)
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa "
                . "WHERE `id_user_resa`=" . $idUtilisateur . " "
                . "  AND MONTH(`dateresa_resa`) = " . $mois . " "
                . "  AND YEAR(`dateresa_resa`) = " . $annee . " "
                . "ORDER BY `dateresa_resa` DESC , `debut_resa` DESC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasParIdUtilisateurEtParPeriode($idUtilisateur, $dateDebut, $dateFin)
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa "
                . "WHERE `id_user_resa`=" . $idUtilisateur . " "
                . "  AND `dateresa_resa` BETWEEN '" . $dateDebut . "' AND '" . $dateFin . "' "
                . "ORDER BY `dateresa_resa` DESC , `debut_resa` DESC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasActives()
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa WHERE `status_resa` = '0' ORDER BY date_resa ASC, debut_resa ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasDuJour()
    {
        $resas = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_resa WHERE `status_resa` = '1' and date_resa = DATE( NOW() ) ORDER BY date_resa ASC, debut_resa DESC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasParJourEtParSalle($date, $idSalle)
    {
        $resas = null;

        $db = Mysql::opendb();

        $sql = "SELECT tab_resa.* "
                . "FROM `tab_resa`,`tab_computer` "
                . "WHERE `dateresa_resa`='" . $date . "' "
                . "  AND tab_resa.id_computer_resa=tab_computer.id_computer "
                . "  AND tab_computer.id_salle='" . $idSalle . "' "
                . "ORDER BY `debut_resa` ASC, id_computer_resa ASC";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getResasParJourEtParMateriel($date, $idMateriel)
    {
        $resas = null;

        $db = Mysql::opendb();

        $sql = "SELECT * FROM `tab_resa` "
                . "WHERE `id_computer_resa`='" . $idMateriel . "' "
                . "  AND `dateresa_resa`='" . $date . "' "
                . "ORDER BY `debut_resa` ASC";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            $resas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $resas[] = new Resa($row);
            }
            mysqli_free_result($result);
        }

        return $resas;
    }

    public static function getProchaineResasParJourEtParMateriel($date, $idMateriel, $heureDebut)
    {
        $resa = null;

        $db = Mysql::opendb();

        $sql = "SELECT * FROM `tab_resa` "
                . "WHERE `id_computer_resa`='" . $idMateriel . "' "
                . "  AND `dateresa_resa`='" . $date . "' "
                . "  AND debut_resa>" . $heureDebut . " "
                . "ORDER BY `debut_resa` ASC "
                . "LIMIT 1";
        //error_log($sql);
        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $resa = new Resa($row);
            mysqli_free_result($result);
        }

        return $resa;
    }

    public static function getStatResaParMois($mois, $annee, $idEspace)
    {
        $stats = array("nombre" => 0, "duree" => 0);

        $db = Mysql::opendb();
        $sql = "SELECT count(id_resa) AS nombre, SUM(duree_resa) AS duree
                FROM tab_resa, tab_computer,tab_salle
                WHERE dateresa_resa BETWEEN '" . $annee . "-" . $mois . "-01' AND '" . $annee . "-" . $mois . "-31'
                AND tab_computer.`id_salle` = tab_salle.id_salle
                AND tab_resa.`id_computer_resa` = tab_computer.id_computer
                AND `id_espace` ='" . $idEspace . "'
               ";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result != false) {
            $row = mysqli_fetch_array($result);
            $stats["nombre"] = $row["nombre"];
            $stats["duree"] = $row["duree"];
        }

        return $stats;
    }

    public static function getStatResaParJour($date, $idEspace)
    {
        $stats = array("nombre" => 0, "duree" => 0);

        $db = Mysql::opendb();

        $sql = "SELECT sum(duree_resa) AS duree, count(id_resa) AS nombre
            FROM tab_resa, tab_computer,tab_salle
            WHERE dateresa_resa='" . $date . "'
              AND tab_computer.`id_salle` = tab_salle.id_salle
              AND tab_resa.`id_computer_resa` = tab_computer.id_computer
              AND `id_espace` ='" . $idEspace . "'
            ";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result != false) {
            $row = mysqli_fetch_array($result);
            $stats['duree'] = $row['duree'];
            $stats['nombre'] = $row['nombre'];
        }

        return $stats;
    }

    //renvoi un tableau des heures de debut de resa pour un jour et une machine
    public static function getResaArray($idcomp, $dateResa, $unit)
    {
        $array = array();

        $db = Mysql::opendb();

        $sql = "SELECT debut_resa,duree_resa "
                . "FROM tab_resa "
                . "WHERE id_computer_resa=" . $idcomp . " "
                . "  AND dateresa_resa='" . $dateResa . "' "
                . "ORDER BY debut_resa";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                $array[] = $row['debut_resa'];
                // on cree un tableau contenant selon l'unite la liste des horaires utilise par la reservation
                $tmpArray = array();
                $tmpNb = ($row['duree_resa'] / $unit);
                $debutValue = $row['debut_resa'];
                for ($i = 1; $i <= ($tmpNb - 1); ++$i) {
                    $debutValue = $debutValue + $unit;
                    $tmpArray[] = $debutValue;
                }
                $array = array_merge($array, $tmpArray);
            }
        }
        return $array;
    }

    public static function getLastResaFromUtilisateur($idUtilisateur)
    {
        $resa = null;

        $db = Mysql::opendb();

        $sql = "SELECT * FROM `tab_resa` WHERE `id_user_resa`=" . $idUtilisateur . " AND dateresa_resa IN (select MAX(`dateresa_resa`) FROM `tab_resa` WHERE `id_user_resa`=" . $idUtilisateur . ")";

        $result = mysqli_query($db, $sql);

        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $resa = new Resa($row);
            mysqli_free_result($result);
        }

        return $resa;
    }
}
