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
    private $_maxTimeDefault;
    private $_idEspace;
    private $_inscriptionUsagersAuto;
    private $_messageInscription;
    private $_nomEspace;
    private $_activationForfait;
    private $_resaRapide;
    private $_dureeResaRapide;
    
    public function __construct($array) {
        $this->_id                      = $array["id_config"];
        $this->_activerConsole          = $array["activer_console"];
        $this->_name                    = $array["name_config"];
        $this->_unitDefault             = $array["unit_default_config"];
        $this->_unit                    = $array["unit_config"];
        $this->_maxTime                 = $array["maxtime_config"];
        $this->_maxTimeDefault          = $array["maxtime_default_config"];
        $this->_idEspace                = $array["id_espace"];
        $this->_inscriptionUsagersAuto  = $array["inscription_usagers_auto"];
        $this->_messageInscription      = $array["message_inscription"];
        $this->_nomEspace               = $array["nom_espace"];
        $this->_activationForfait       = $array["activation_forfait"];
        $this->_resaRapide              = $array["resarapide"];
        $this->_dureeResaRapide         = $array["duree_resarapide"];
    }
    
    public function getResaRapide() {
        return $this->_resaRapide == "0" ? false : true;
    }

    public function getDureeResaRapide() {
        if ($this->_dureeResaRapide > 0) {
            return $this->_dureeResaRapide;
        }
        else {
            return $this->_unitDefault; // je ne comprends pas le pourquoi de cette conversion, mais je reproduis l'ancienne fonctionnalité.
            // cf admin_config_horaire ligne 314 et fonction.php ligne 2299
            // probablement un copier-coller pas terminé...
        }
    }
    
    public function getMaxTime() {
        if ($this->_maxTime > 0) {
            return $this->_maxTime;
        }
        else {
            return $this->_maxTimeDefault; 
        }
    }

    public function getDefaultMaxTime() {
        return $this->_maxTimeDefault;
    }
    
    public function getTimeUnit() {
        if ($this->_unit > 0) {
            return $this->_unit;
        }
        else {
            return $this->_unitDefault; 
        }
    }

    public function getDefaultTimeUnit() {
        return $this->_unitDefault;
    }
    
    

    public static function getConfig($idEspace) {
        $config = null;

        $db = Mysql::opendb();
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $sql = "SELECT * "
             . "FROM `tab_config` "
             . "WHERE id_espace = " . $idEspace;
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
            
        if ($result && mysqli_num_rows($result) == 1) {
            $config = new Config(mysqli_fetch_assoc($result));
            mysqli_free_result($result);
        }
        
        return $config;
    }
}