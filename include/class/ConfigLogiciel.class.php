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

class ConfigLogiciel {

    private $_id;
    private $_idEspace;
    private $_configMenu;
    private $_pageInscription;
    private $_pageRenseignement;
    private $_connexionAnim;
    private $_bloquageTouche;
    private $_affichageTemps;
    private $_deconnexionAuto;
    private $_fermetureSession;

    public function __construct($array) {
        $this->_id = $array["id_config_logiciel"];
        $this->_idEspace = $array["id_espace"];
        $this->_configMenu = $array["config_menu_logiciel"];
        $this->_pageInscription = $array["page_inscription_logiciel"];
        $this->_pageRenseignement = $array["page_renseignement_logiciel"];
        $this->_connexionAnim = $array["connexion_anim_logiciel"];
        $this->_blocageTouche = $array["bloquage_touche_logiciel"];
        $this->_affichageTemps = $array["affichage_temps_logiciel"];
        $this->_deconnexionAuto = $array["deconnexion_auto_logiciel"];
        $this->_fermetureSession = $array["fermeture_session_auto"];
    }

    public function getId() {
        return $this->_id;
    }

    public function getIdEspace() {
        return $this->_idEspace;
    }

    public function hasConfigMenu() {
        return $this->_configMenu == "0" ? false : true;
    }

    public function hasPageInscription() {
        return $this->_pageInscription == "0" ? false : true;
    }

    public function hasPageRenseignement() {
        return $this->_pageRenseignement == "0" ? false : true;
    }

    public function hasConnexionAnim() {
        return $this->_connexionAnim == "0" ? false : true;
    }

    public function hasBlocageTouche() {
        return $this->_blocageTouche == "0" ? false : true;
    }

    public function hasAffichageTemps() {
        return $this->_affichageTemps == "0" ? false : true;
    }

    public function hasDeconnexionAuto() {
        return $this->_deconnexionAuto == "0" ? false : true;
    }

    public function hasFermetureSession() {
        return $this->_fermetureSession == "0" ? false : true;
    }

    function setConfigLogiciel($configMenu, $pageInscription, $pageRenseignement, $connexionAnim, $bloquageTouche, $affichageTemps, $deconnexionAuto, $fermetureSession) {
        $success = false;

        $db = Mysql::opendb();

        $configMenu = mysqli_real_escape_string($db, $configMenu);
        $pageInscription = mysqli_real_escape_string($db, $pageInscription);
        $pageRenseignement = mysqli_real_escape_string($db, $pageRenseignement);
        $connexionAnim = mysqli_real_escape_string($db, $connexionAnim);
        $bloquageTouche = mysqli_real_escape_string($db, $bloquageTouche);
        $affichageTemps = mysqli_real_escape_string($db, $affichageTemps);
        $deconnexionAuto = mysqli_real_escape_string($db, $deconnexionAuto);
        $fermetureSession = mysqli_real_escape_string($db, $fermetureSession);

        $sql = "UPDATE `tab_config_logiciel` SET "
                . "`config_menu_logiciel`='" . $configMenu . "', "
                . "`page_inscription_logiciel`='" . $pageInscription . "', "
                . "`page_renseignement_logiciel`='" . $pageRenseignement . "', "
                . "`connexion_anim_logiciel`='" . $connexionAnim . "', "
                . "`bloquage_touche_logiciel`='" . $bloquageTouche . "', "
                . "`affichage_temps_logiciel`='" . $affichageTemps . "', "
                . "`deconnexion_auto_logiciel`='" . $deconnexionAuto . "',  "
                . "`fermeture_session_auto`='" . $fermetureSession . "' "
                . "WHERE `id_config_logiciel`='" . $this->_id . "' ";

        $result = mysqli_query($db, $sql);

        if ($result != FALSE) {
            $success = TRUE;
        }

        Mysql::closedb($db);

        return $success;
    }

    public static function getConfigLogiciel($idEspace) {
        $configLogiciel = null;

        $db = Mysql::opendb();
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $sql = "SELECT * "
                . "FROM `tab_config_logiciel` "
                . "WHERE id_espace = " . $idEspace;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result && mysqli_num_rows($result) == 1) {
            $configLogiciel = new ConfigLogiciel(mysqli_fetch_assoc($result));
            mysqli_free_result($result);
        }

        return $configLogiciel;
    }

}
