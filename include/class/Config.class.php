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

class Config
{
    private $_id;
    private $_activerConsole;
    private $_name;
    private $_unitDefault;
    private $_unit;
    private $_maxTime;
    private $_defaultMaxTime;
    private $_idEspace;
    private $_inscriptionUsagersAuto;
    private $_messageInscription;
    private $_nomEspace;
    private $_activationForfait;
    private $_resaRapide;
    private $_dureeResaRapide;

    public function __construct($array)
    {
        $this->_id = $array["id_config"];
        $this->_activerConsole = $array["activer_console"];
        $this->_name = $array["name_config"];
        $this->_unitDefault = $array["unit_default_config"];
        $this->_unit = $array["unit_config"];
        $this->_maxTime = $array["maxtime_config"];
        $this->_defaultMaxTime = $array["maxtime_default_config"];
        $this->_idEspace = $array["id_espace"];
        $this->_inscriptionUsagersAuto = $array["inscription_usagers_auto"];
        $this->_messageInscription = $array["message_inscription"];
        $this->_nomEspace = $array["nom_espace"];
        $this->_activationForfait = $array["activation_forfait"];
        $this->_resaRapide = $array["resarapide"];
        $this->_dureeResaRapide = $array["duree_resarapide"];
    }

    public function getActiverConsole()
    {
        return $this->_activerConsole;
    }

    public function hasActiverConsole()
    {
        return $this->_activerConsole == "0" ? false : true;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $success = false;

        $db = Mysql::opendb();
        $name = mysqli_real_escape_string($db, $name);

        $sql = "UPDATE `tab_config` "
                . " SET `name_config` = " . $name . " ";
        // note : on met la table à jour pour TOUS les espaces
//              . " WHERE `id_espace`='" . $this->_idEspace . "';";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $this->_name = $name;
            $success = true;
        }

        return $success;
    }

    public function getResaRapide()
    {
        return $this->_resaRapide;
    }

    public function getIdEspace()
    {
        return $this->_idEspace;
    }

    public function getNomEspace()
    {
        return $this->_nomEspace;
    }

    public function hasResaRapide()
    {
        return $this->_resaRapide == "0" ? false : true;
    }

    public function getInscriptionUsagersAuto()
    {
        return $this->_inscriptionUsagersAuto;
    }

    public function hasInscriptionUsagersAuto()
    {
        return $this->_inscriptionUsagersAuto == "0" ? false : true;
    }

    public function getMessageInscription()
    {
        return $this->_messageInscription;
    }

    public function getDureeResaRapide()
    {
        return $this->_dureeResaRapide;
    }

    public function getDureeResaRapideOrUnitDefault()
    {
        if ($this->_dureeResaRapide > 0) {
            return $this->_dureeResaRapide;
        } else {
            return $this->_unitDefault; // je ne comprends pas le pourquoi de cette conversion, mais je reproduis l'ancienne fonctionnalité.
            // cf admin_config_horaire ligne 314 et fonction.php ligne 2299
            // probablement un copier-coller pas terminé...
        }
    }

    public function getMaxTime()
    {
        return $this->_maxTime;
    }

    public function getMaxTimeOrDefaultMaxTime()
    {
        if ($this->_maxTime > 0) {
            return $this->_maxTime;
        } else {
            return $this->_defaultMaxTime;
        }
    }

    public function getDefaultMaxTime()
    {
        return $this->_defaultMaxTime;
    }

    public function getUnit()
    {
        return $this->_unit;
    }

    public function getTimeUnit()
    {
        if ($this->_unit > 0) {
            return $this->_unit;
        } else {
            return $this->_unitDefault;
        }
    }

    public function getDefaultTimeUnit()
    {
        return $this->_unitDefault;
    }

    public function hasActivationForfait()
    {
        return $this->_activationForfait == "0" ? false : true;
    }

    public function getActivationForfait()
    {
        return $this->_activationForfait;
    }

    public function activerForfait()
    {
        $success = false;

        $db = Mysql::opendb();

        $sql = "UPDATE `tab_config` "
                . " SET `activation_forfait` = '1' "
                . " WHERE `id_espace`='" . $this->_idEspace . "';";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $this->_activationForfait = '1';
            $success = true;
        }

        return $success;
    }

    public function desactiverForfait()
    {
        $success = false;

        $db = Mysql::opendb();

        $sql = "UPDATE `tab_config` "
                . " SET `activation_forfait` = '0' "
                . " WHERE `id_espace`='" . $this->_idEspace . "';";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $this->_activationForfait = '0';
            $success = true;
        }

        return $success;
    }

    public function updateActivationForfait()
    {
        $success = false;

        $db = Mysql::opendb();

        $sql = "SELECT id_forfait_espace from rel_forfait_espace where id_espace = " . $this->_idEspace . "";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $nbForfaitsAttaches = mysqli_num_rows($result);
            if ($nbForfaitsAttaches > 0) {
                $success = $this->activerForfait();
            } else {
                $success = $this->desactiverForfait();
            }
        }

        return $success;
    }

    public function modifier(
    $activerConsole,
        $name,
        $unitDefault,
        $unit,
        $maxTime,
        $defaultMaxTime,
        $idEspace,
        $inscriptionUsagerAuto,
        $messageInscription,
        $nomEspace,
        $activationForfait,
        $resaRapide,
        $dureeResaRapide
    ) {
        $success = false;
        $db = Mysql::opendb();

        $activerConsole = mysqli_real_escape_string($db, $activerConsole);
        $name = mysqli_real_escape_string($db, $name);
        $unitDefault = mysqli_real_escape_string($db, $unitDefault);
        $unit = mysqli_real_escape_string($db, $unit);
        $maxTime = mysqli_real_escape_string($db, $maxTime);
        $defaultMaxTime = mysqli_real_escape_string($db, $defaultMaxTime);
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $inscriptionUsagerAuto = mysqli_real_escape_string($db, $inscriptionUsagerAuto);
        $messageInscription = mysqli_real_escape_string($db, $messageInscription);
        $nomEspace = mysqli_real_escape_string($db, $nomEspace);
        $activationForfait = mysqli_real_escape_string($db, $activationForfait);
        $resaRapide = mysqli_real_escape_string($db, $resaRapide);
        $dureeResaRapide = mysqli_real_escape_string($db, $dureeResaRapide);

        $sql = "UPDATE `tab_config` "
                . "SET `activer_console`='" . $activerConsole . "', "
                . "`name_config`='" . $name . "', "
                . "`unit_default_config`='" . $unitDefault . "', "
                . "`unit_config`='" . $unit . "', "
                . "`maxtime_config`='" . $maxTime . "', "
                . "`maxtime_default_config`='" . $defaultMaxTime . "', "
                . "`id_espace`='" . $idEspace . "', "
                . "`inscription_usagers_auto`='" . $inscriptionUsagerAuto . "', "
                . "`message_inscription`='" . $messageInscription . "', "
                . "`nom_espace`='" . $nomEspace . "', "
                . "`activation_forfait`='" . $activationForfait . "', "
                . "`resarapide`='" . $resaRapide . "', "
                . "`duree_resarapide`='" . $dureeResaRapide . "' "
                . "WHERE `id_config` = " . $this->_id . " ";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_activerConsole = $activerConsole;
            $this->_name = $name;
            $this->_unitDefault = $unitDefault;
            $this->_unit = $unit;
            $this->_maxTime = $maxTime;
            $this->_defaultMaxTime = $defaultMaxTime;
            $this->_idEspace = $idEspace;
            $this->_inscriptionUsagersAuto = $inscriptionUsagerAuto;
            $this->_messageInscription = $messageInscription;
            $this->_nomEspace = $nomEspace;
            $this->_activationForfait = $activationForfait;
            $this->_resaRapide = $resaRapide;
            $this->_dureeResaRapide = $dureeResaRapide;

            $success = true;
        }

        return $success;
    }

    public static function getConfig($idEspace)
    {
        $config = null;

        $db = Mysql::opendb();
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $sql = "SELECT * "
                . "FROM `tab_config` "
                . "WHERE id_espace = " . $idEspace;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) == 1) {
            $config = new Config(mysqli_fetch_assoc($result));
            mysqli_free_result($result);
        }

        return $config;
    }
}
