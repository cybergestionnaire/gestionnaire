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
require_once("Config.class.php");
require_once("ConfigLogiciel.class.php");
require_once("Ville.class.php");
require_once("Horaire.class.php");

/**
 * La classe Espace permer "d'abstraire" les données venant de la table tab_espace.
 *
 * Toutes les manipulations sur la table tab_espace devrait passer par une fonction 
 * de cette classe.
 */

class Espace
{
    
    private $_id;
    private $_nom;
    private $_idVille;
    private $_adresse;
    private $_telephone;
    private $_fax;
    private $_logo;
    private $_couleur;
    private $_mail;
    
    //tableau des couleurs
    private $_couleurArray = array(
                                1=> "green",
                                2=> "blue",
                                3=> "yellow",
                                4=> "red",
                                //5=> "olive",
                                6=> "purple",
                                //7=> "orange",
                                //8=> "maroon",
                                9=> "black"
                            );
    
    public function __construct($array)
    {
        $this->_id          = $array["id_espace"];
        $this->_nom         = $array["nom_espace"];
        $this->_idVille     = $array["id_city"];
        $this->_adresse     = $array["adresse"];
        $this->_telephone   = $array["tel_espace"];
        $this->_fax         = $array["fax_espace"];
        $this->_logo        = $array["logo_espace"];
        $this->_couleur     = $array["couleur_espace"];
        $this->_mail        = $array["mail_espace"];
    }
    
    public function getId() {
        return $this->_id;
    }
    public function getNom() {
        return $this->_nom;
    }

    public function getIdVille() {
        return $this->_idVille;
    }
    
    public function getVille() {
        return Ville::getVilleById($this->_idVille);
    }

    public function getAdresse() {
        return $this->_adresse;
    }
    
    public function getTelephone() {
        return $this->_telephone;
    }
    
    public function getFax() {
        return $this->_fax;
    }
    
    public function getLogo() {
        return $this->_logo;
    }
    
    public function getCouleur() {
        return $this->_couleurArray[$this->_couleur];
    }

    public function getCodeCouleur() {
        return $this->_couleur;
    }
    
    public function getMail() {
        return $this->_mail;
    }
    
    public function getHoraires() {
        return Horaire::getHorairesByIdEspace(intval($this->_id));
    }
    
    public function getConfig() {
        return Config::getConfig($this->_id);
    }
    
    public function getConfigLogiciel() {
        return ConfigLogiciel::getConfigLogiciel($this->_id);
    }
    
    public function hasConfigLogiciel() {
        $success = FALSE;
        
        $db     = Mysql::opendb();
        $sql    = "SELECT  `id_config_logiciel` FROM  `tab_config_logiciel` WHERE  `id_espace` ='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if(mysqli_num_rows($result) == 1) {
            $success = TRUE ;
        }
        
        return $success;
    }
    
    public static function creerEspace($nom, $adresse, $idVille, $telephone, $fax, $logo, $couleur, $mail) {
        $espace = null;

        if ( $nom != ""
            && (is_int($idVille) && $idVille != 0)
            && filter_var($mail, FILTER_VALIDATE_EMAIL)
        ) {
            $db = Mysql::opendb();
            
            $nom        = mysqli_real_escape_string($db, $nom);
            $idVille    = mysqli_real_escape_string($db, $idVille);
            $adresse    = mysqli_real_escape_string($db, $adresse);
            $telephone  = mysqli_real_escape_string($db, $telephone);
            $fax        = mysqli_real_escape_string($db, $fax);
            $logo       = mysqli_real_escape_string($db, $logo);
            $couleur    = mysqli_real_escape_string($db, $couleur);
            $mail       = mysqli_real_escape_string($db, $mail);


            $sql = "INSERT INTO `tab_espace` (`id_espace`,`nom_espace`,`id_city`,`adresse`,`tel_espace`, `fax_espace`,`logo_espace`,`couleur_espace`,`mail_espace`) "
                 . "VALUES ('','" . $nom . "', '" . $idVille . "', '" . $adresse . "', '" .$telephone. "','" . $fax . "','" . $logo . "', '" . $couleur . "','" . $mail . "') " ;       
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $espace = new Espace(array("id_espace" => mysqli_insert_id($db), "nom_espace" => $nom, "id_city" => $idVille, "adresse" => $adresse, "tel_espace" => $telephone, "fax_espace" => $fax, "logo_espace" => $logo, "couleur_espace" => $couleur, "mail_espace" => $mail));
            }
            
            Mysql::closedb($db);
        }
        return $espace;
    }
    
    
    
    public function modifier($nom, $adresse, $idVille, $telephone, $fax, $logo, $couleur, $mail) {
        $success = FALSE;
        $db = Mysql::opendb();
        
        $nom        = mysqli_real_escape_string($db, $nom);
        $idVille    = mysqli_real_escape_string($db, $idVille);
        $adresse    = mysqli_real_escape_string($db, $adresse);
        $telephone  = mysqli_real_escape_string($db, $telephone);
        $fax        = mysqli_real_escape_string($db, $fax);
        $logo       = mysqli_real_escape_string($db, $logo);
        $couleur    = mysqli_real_escape_string($db, $couleur);
        $mail       = mysqli_real_escape_string($db, $mail);

        $sql = "UPDATE `tab_espace` "
            . "SET `nom_espace`='" . $nom . "', `id_city`='" . $idVille . "', `adresse`='" . $adresse . "', `tel_espace`='" . $telephone . "', `fax_espace`='" . $fax . "', `logo_espace`='" . $logo . "', `couleur_espace`='" . $couleur . "', `mail_espace`='" . $mail . "' "
            . "WHERE `id_espace`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_nom        = $nom;
            $this->_idVille    = $idVille;
            $this->_adresse    = $adresse;
            $this->_telephone  = $telephone;
            $this->_fax        = $fax;
            $this->_logo       = $logo;
            $this->_couleur    = $couleur;
            $this->_mail       = $mail;
            
            $success = TRUE;
        }

        return $success;
    }
    
    public function supprimer() {

        // Verification avant suppression si il n'y a plus de salle
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_salle` FROM `tab_salle` WHERE `id_espace`=" . $this->_id;
        $result = mysqli_query($db, $sql);


        if ($result == FALSE) {
            return 0; // echec de la requete
        } else {
            if (mysqli_num_rows($result) > 0 ) {
                return 1; // il reste des salles lies a l'espace
            } else {
                // Suppression de l'espace
                $sql1    = "DELETE FROM `tab_espace` WHERE `id_espace` = " . $this->_id;
                $sql2    = "DELETE FROM `tab_config` WHERE `id_espace` = " . $this->_id . "" ;
                $sql3    = "DELETE FROM `tab_config_logiciel` WHERE `id_espace` = " . $this->_id . "" ;
                $sql4    = "DELETE FROM `tab_horaire` WHERE `id_epn` = " . $this->_id . "" ;

                $result1 = mysqli_query($db, $sql1);
                $result2 = mysqli_query($db, $sql2);
                $result3 = mysqli_query($db, $sql3);
                $result4 = mysqli_query($db, $sql4);
                
                if ($result1 && $result2 && $result3 && $result4) {
                    return 2; //succes
                } else {
                    return 0;
                }
            }
        }
        Mysql::closedb($db);
    }

    //insertion des horaires du nouvel epn en copy de celui par défaut
    public function copyHoraires() {
        $db  = Mysql::opendb();        
        $sql = " INSERT INTO `tab_horaire`(`id_horaire`, `jour_horaire`, `hor1_begin_horaire`, `hor1_end_horaire`, `hor2_begin_horaire`, `hor2_end_horaire`, `unit_horaire`, `id_epn`) 
                SELECT '', `jour_horaire`, `hor1_begin_horaire`, `hor1_end_horaire`, `hor2_begin_horaire`, `hor2_end_horaire`, `unit_horaire`, '" . $this->_id . "' FROM `tab_horaire` WHERE `id_epn`=1
                ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        return $result;
    }

    public function copyConfig($forfait) {
        $db  = Mysql::opendb(); 
        $sql = "INSERT INTO `tab_config`(`id_config`, `activer_console`, `name_config`, `unit_default_config`, `unit_config`, `maxtime_config`, `maxtime_default_config`, `id_espace`, `inscription_usagers_auto`, `message_inscription`, `activation_forfait`, `nom_espace`,`resarapide`, `duree_resarapide`)
                SELECT '',`activer_console`, `name_config`, `unit_default_config`, `unit_config`, `maxtime_config`, `maxtime_default_config`,'" . $this->_id . "', `inscription_usagers_auto`, `message_inscription`,'" . $forfait . "',`nom_espace`,`resarapide`, `duree_resarapide` FROM `tab_config` WHERE `id_espace`=1
                ";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        return $result;
    }

    public function copyConfigLogiciel() {
        $db  = Mysql::opendb(); 
        $sql = "INSERT INTO `tab_config_logiciel`(`id_config_logiciel`, `id_espace`, `config_menu_logiciel`, `page_inscription_logiciel`, `page_renseignement_logiciel`, `connexion_anim_logiciel`, `bloquage_touche_logiciel`, `affichage_temps_logiciel`, `deconnexion_auto_logiciel`, `fermeture_session_auto`) VALUES ('','" . $this->_id . "','1','0','1','1','1','1','1','0')";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        return $result;
    }
    
    
    public static function getEspaceById($id) {
        $espace = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_espace` "
                 . "WHERE `id_espace` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $espace = new Espace(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $espace;
    }
    public static function getEspaces() {

        $espaces = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_espace ORDER BY nom_espace ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $espaces = array();
            while($row = mysqli_fetch_assoc($result)) {
                $espaces[] = new Espace($row);
            }
            mysqli_free_result($result);
        }
        
        return $espaces;
    
    }
    
}