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
require_once("Salle.class.php");

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
        $this->_id                      = $array["id_computer"];
        $this->_nom                     = $array["nom_computer"];
        $this->_commentaire             = $array["comment_computer"];
        $this->_os                      = $array["os_computer"];
        $this->_usage                   = $array["usage_computer"];
        $this->_fonction                = $array["fonction_computer"];
        $this->_idSalle                 = $array["id_salle"];
        $this->_adresseMAC              = $array["adresse_mac_computer"];
        $this->_adresseIP               = $array["adresse_ip_computer"];
        $this->_nomHote                 = $array["nom_hote_computer"];
        $this->_dateDernierEtat         = $array["date_lastetat_computer"];
        $this->_dernierEtat             = $array["lastetat_computer"];
        $this->_configurationEPNConnect = $array["configurer_epnconnect_computer"];
    }
    
    public function getId() {
        return $this->_id;
    }
    public function getNom() {
        return $this->_nom;
    }
    public function getCommentaire() {
        return $this->_commentaire;
    }
    public function getOs() {
        return $this->_os;
    }
    public function getUsage() {
        return $this->_usage;
    }
    public function getFonction() {
        return $this->_fonction;
    }
    public function getIdSalle() {
        return $this->_idSalle;
    }
    public function getSalle() {
        return Salle::getSalleByID($this->_idSalle);
    }
    public function getAdresseMAC() {
        return $this->_adresseMAC;
    }
    public function getAdresseIP() {
        return $this->_adresseIP;
    }
    public function getDateDernierEtat() {
        return $this->_dateDernierEtat;
    }
    public function getDernierEtat() {
        return $this->_dernierEtat;
    }
    public function getConfigurationEPNConnect() {
        return $this->_configurationEPNConnect;
    }

/*    public function modifier($nom, $idEspace, $commentaire) {
        $success = FALSE;
        $db = Mysql::opendb();
        
        $nom         = mysqli_real_escape_string($db, $nom);
        $idEspace    = mysqli_real_escape_string($db, $idEspace);
        $commentaire = mysqli_real_escape_string($db, $commentaire);

        $sql = "UPDATE `tab_salle` "
            . "SET `nom_salle` = '" . $nom . "', `id_espace` = '" . $idEspace . "', `comment_salle` = '" . $commentaire . "' "
            . "WHERE `id_salle` = " . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_nom         = $nom;
            $this->_idEspace    = $idEspace;
            $this->_commentaire = $commentaire;
            
            $success = TRUE;
        }

        return $success;
    }*/
    
/*    public function supprimer() {

        // Verification avant suppression si il n'y a plus d'ordinateur attachés à la salle
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_computer` FROM `tab_computer` WHERE `id_salle` = '" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);


        if ($result == FALSE) {
            return 0; // echec de la requete
        }
        else {
            if (mysqli_num_rows($result) > 0 ) {
                return 1; // il reste des ordinateurs lies a la salle
            }
            else {
                // Suppression de la salle
                $sql2   = "DELETE FROM `tab_salle` WHERE `id_salle`=" . $this->_id;
                $result = mysqli_query($db, $sql2);
                if ($result == FALSE ) {
                    return 0;
                }
                else {
                    return 2;
                }
            }
        }
        Mysql::closedb($db);
    }*/
   
    
/*    public static function creerSalle($nom, $idEspace, $commentaire) {
        $salle = null;
        
        if ( $nom != ""
            && (is_int($idEspace) && $idEspace != 0)
        ) {
            $db = Mysql::opendb();
            
            $nom         = mysqli_real_escape_string($db, $nom);
            $idEspace    = mysqli_real_escape_string($db, $idEspace);
            $commentaire = mysqli_real_escape_string($db, $commentaire);


            $sql = "INSERT INTO `tab_salle` (`id_salle`,`nom_salle`,`id_espace`,`comment_salle`) "
                 . "VALUES ('','" . $nom . "', '" . $idEspace."', '" . $commentaire . "') " ;       
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $salle = new Salle(array("id_salle" => mysqli_insert_id($db), "nom_salle" => $nom, "id_espace" => $idEspace, "comment_salle" => $commentaire));
            }
            
            Mysql::closedb($db);
        }
        return $salle;
    }*/
    
    public static function getMaterielById($id) {
        $materiel = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_computer` "
                 . "WHERE `id_computer` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $materiel = new Materiel(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $materiel;
    }
    
    public static function getMateriels() {

        $materiels = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_computer ORDER BY nom_computer ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $materiels = array();
            while($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }
        
        return $materiels;
    
    }
    
    public static function getMaterielFromEspaceById($idEspace) {
        $materiels = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT tab_computer.* "
                . "FROM tab_computer , tab_salle "
                . "WHERE tab_computer.id_salle = tab_salle.id_salle "
                . "  AND id_espace = '" . $idEspace . "' "
                . "ORDER BY `usage_computer` , `nom_computer`";
        
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $materiels = array();
            while($row = mysqli_fetch_assoc($result)) {
                $materiels[] = new Materiel($row);
            }
            mysqli_free_result($result);
        }
        
        return $materiels; 
    }
    
    
}