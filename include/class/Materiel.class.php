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
//require_once("Salle.class.php");

class Materiel
{
    private $_id;
    private $_nom;
    private $_commentaire;
    private $_os;
    private $_usage;
    private $_fonction;
    private $_idSalle;
    private $_adresseMAC;
    private $_adresseIP;
    private $_nomHote;
    private $_dateDernierEtat;
    private $_dernierEtat;
    private $_configurationEPNConnect;

    public function __construct($array)
    {
        $this->_id = $array["id_computer"];
        $this->_nom = $array["nom_computer"];
        $this->_commentaire = $array["comment_computer"];
        $this->_os = $array["os_computer"];
        $this->_usage = $array["usage_computer"];
        $this->_fonction = $array["fonction_computer"];
        $this->_idSalle = $array["id_salle"];
        $this->_adresseMAC = $array["adresse_mac_computer"];
        $this->_adresseIP = $array["adresse_ip_computer"];
        $this->_nomHote = $array["nom_hote_computer"];
        $this->_dateDernierEtat = $array["date_lastetat_computer"];
        $this->_dernierEtat = $array["lastetat_computer"];
        $this->_configurationEPNConnect = $array["configurer_epnconnect_computer"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getNom()
    {
        return $this->_nom;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
    }

    public function getOs()
    {
        return $this->_os;
    }

    public function getUsage()
    {
        return $this->_usage;
    }

    public function getFonction()
    {
        return $this->_fonction;
    }

    public function getIdSalle()
    {
        return $this->_idSalle;
    }

    public function getSalle()
    {
        return Salle::getSalleByID($this->_idSalle);
    }

    public function getAdresseMAC()
    {
        return $this->_adresseMAC;
    }

    public function getAdresseIP()
    {
        return $this->_adresseIP;
    }

    public function getNomHote()
    {
        return $this->_nomHote;
    }

    public function getDateDernierEtat()
    {
        return $this->_dateDernierEtat;
    }

    public function getDernierEtat()
    {
        return $this->_dernierEtat;
    }

    public function getConfigurationEPNConnect()
    {
        return $this->_configurationEPNConnect;
    }

    public function addUsageById($idUsage)
    {
        $success = false;

        $db = Mysql::opendb();

        $sql = "INSERT INTO `rel_usage_computer` (`id_computer`,`id_usage`) "
                . "VALUES ('" . $this->_id . "', '" . $idUsage . "' ) ";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }

        return $success;
    }

    public function modifier($nom, $os, $commentaire, $usage, $fonction, $idSalle, $adresseIP, $adresseMAC, $nomHote)
    {
        $success = false;
        if ($nom != "" && (is_int($idSalle) && $idSalle != 0)
        ) {
            $db = Mysql::opendb();

            $nom = mysqli_real_escape_string($db, $nom);
            $os = mysqli_real_escape_string($db, $os);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $usage = mysqli_real_escape_string($db, $usage);
            $fonction = mysqli_real_escape_string($db, $fonction);
            $idSalle = mysqli_real_escape_string($db, $idSalle);
            $adresseIP = mysqli_real_escape_string($db, $adresseIP);
            $adresseMAC = mysqli_real_escape_string($db, $adresseMAC);
            $nomHote = mysqli_real_escape_string($db, $nomHote);

            $sql = "UPDATE `tab_computer` "
                    . "SET `nom_computer`     = '" . $nom . "', "
                    . "`comment_computer`     = '" . $commentaire . "', "
                    . "`os_computer`          = '" . $os . "', "
                    . "`usage_computer`       = '" . $usage . "', "
                    . "`fonction_computer`    = '" . $fonction . "', "
                    . "`id_salle`             = '" . $idSalle . "', "
                    . "`adresse_ip_computer`  = '" . $adresseIP . "', "
                    . "`adresse_mac_computer` = '" . $adresseMAC . "', "
                    . "`nom_hote_computer`    = '" . $nomHote . "' "
                    . "WHERE `id_computer`    = " . $this->_id . "";

            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);
            if ($result) {
                $this->_nom = $nom;
                $this->_commentaire = $commentaire;
                $this->_os = $os;
                $this->_usage = $usage;
                $this->_fonction = $fonction;
                $this->_idSalle = $idSalle;
                $this->_adresseMAC = $adresseMAC;
                $this->_adresseIP = $adresseIP;
                $this->_nomHote = $nomHote;

                $success = true;
            }
        }
        return $success;
    }

    public function supprimer()
    {
        $success = false;

        $db = Mysql::opendb();

        //effacement des usages liés
        $sql = "DELETE FROM `rel_usage_computer` WHERE `id_computer`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        if ($result) {
            $sql2 = "DELETE FROM `tab_computer` WHERE `id_computer` = " . $this->_id . "";
            $result2 = mysqli_query($db, $sql2);
            if ($result2) {
                $success = true;
            }
        }

        Mysql::closedb($db);

        return $success;
    }

    public static function creerMateriel($nom, $os, $commentaire, $usage, $fonction, $idSalle, $adresseIP, $adresseMAC, $nomhote)
    {
        $materiel = null;

        if ($nom != "" && (is_int($idSalle) && $idSalle != 0)
        ) {
            $db = Mysql::opendb();

            $nom = mysqli_real_escape_string($db, $nom);
            $os = mysqli_real_escape_string($db, $os);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $usage = mysqli_real_escape_string($db, $usage);
            $fonction = mysqli_real_escape_string($db, $fonction);
            $idSalle = mysqli_real_escape_string($db, $idSalle);
            $adresseIP = mysqli_real_escape_string($db, $adresseIP);
            $adresseMAC = mysqli_real_escape_string($db, $adresseMAC);
            $nomhote = mysqli_real_escape_string($db, $nomhote);


            $sql = "INSERT INTO `tab_computer` (`nom_computer`,`comment_computer`,`os_computer`,`usage_computer`,`fonction_computer`,`id_salle`,`adresse_mac_computer`,`adresse_ip_computer`,`nom_hote_computer`,`date_lastetat_computer`,`lastetat_computer`,`configurer_epnconnect_computer`) "
                    . "VALUES ('" . $nom . "', '" . $commentaire . "', '" . $os . "', '" . $usage . "', '" . $fonction . "', '" . $idSalle . "', '" . $adresseMAC . "', '" . $adresseIP . "', '" . $nomhote . "', '', '', '0') ";
            $result = mysqli_query($db, $sql);

            if ($result) {
                $materiel = new Materiel(array(
                    "id_computer" => mysqli_insert_id($db),
                    "nom_computer" => $nom,
                    "comment_computer" => $commentaire,
                    "os_computer" => $os,
                    "usage_computer" => $usage,
                    "fonction_computer" => $fonction,
                    "id_salle" => $idSalle,
                    "adresse_mac_computer" => $adresseMAC,
                    "adresse_ip_computer" => $adresseIP,
                    "nom_hote_computer" => $nomhote,
                    "date_lastetat_computer" => '',
                    "lastetat_computer" => '',
                    "configurer_epnconnect_computer" => '0'));
            }

            Mysql::closedb($db);
        }
        return $materiel;
    }

    public static function getMaterielById($id)
    {
        $materiel = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_computer` "
                    . "WHERE `id_computer` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $materiel = new Materiel(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $materiel;
    }

    public static function getMateriels()
    {
        $materiels = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_computer ORDER BY nom_computer ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $materiels = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }

        return $materiels;
    }

    public static function getMaterielFromEspaceById($idEspace)
    {
        $materiels = null;

        $db = Mysql::opendb();
        $sql = "SELECT tab_computer.* "
                . "FROM tab_computer , tab_salle "
                . "WHERE tab_computer.id_salle = tab_salle.id_salle "
                . "  AND id_espace = '" . $idEspace . "' "
                . "ORDER BY `usage_computer` , `nom_computer`";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $materiels = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }

        return $materiels;
    }

    public static function getMaterielFromSalleById($idSalle)
    {
        $materiels = null;

        $db = Mysql::opendb();
        $sql = "SELECT tab_computer.* "
                . "FROM tab_computer "
                . "WHERE tab_computer.id_salle = '" . $idSalle . "' "
                . "ORDER BY `usage_computer` , `nom_computer`";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $materiels = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }

        return $materiels;
    }

    public static function getMaterielLibreFromSalleById($idSalle)
    {
        $materiels = null;

        $db = Mysql::opendb();

        $sql = "SELECT * FROM `tab_computer` "
                . "WHERE `id_salle` ='" . $idSalle . "' "
                . "  AND `usage_computer`=1  "
                . "  AND id_computer not in ( "
                . "    SELECT id_computer_resa FROM tab_resa "
                . "    WHERE status_resa ='1' "
                . "      AND duree_resa > 0 "
                . "      AND (dateresa_resa > CURRENT_DATE() OR "
                . "              (dateresa_resa = CURRENT_DATE() "
                . "            AND debut_resa >= ( floor( TIME_TO_SEC( CURRENT_TIME() ) /60) - duree_resa + 1)) "
                . "          ) "
                . "  ) "
                . "ORDER BY `nom_computer` ";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $materiels = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }

        return $materiels;
    }
}
