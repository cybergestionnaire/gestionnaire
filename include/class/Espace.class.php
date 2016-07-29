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

include_once("Mysql.class.php");

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
    public function getLogo() {
        return $this->_logo;
    }
    
    public function getCouleur() {
        return $this->_couleurArray[$this->_couleur];
    }
    public function getCodeCouleur() {
        return $this->_couleur;
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
            }
        }
        
        return $espace;
    }
    
    
}