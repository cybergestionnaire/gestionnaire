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
require_once("include/class/AtelierNiveau.class.php");
require_once("include/class/AtelierCategorie.class.php");
    
class AtelierSujet
{
    private $_id;
    private $_label;
    private $_content;
    private $_ressource;
    private $_idNiveau;
    private $_idCategorie;

    private function __construct($array) {
        $this->_id          = $array["id_sujet"];
        $this->_label       = $array["label_atelier"];
        $this->_content     = $array["content_atelier"];
        $this->_ressource   = $array["ressource_atelier"];
        $this->_idNiveau    = $array["niveau_atelier"];
        $this->_idCategorie = $array["categorie_atelier"];
    }
    
    /*
    * Accesseurs basiques
    */
    
    public function getId() {
        return $this->_id;
    }

    public function getLabel() {
        return $this->_label;
    }
    
    public function getContent() {
        return $this->_content;
    }
    
    public function getRessource() {
        return $this->_ressource;
    }
    
    public function getIdNiveau() {
        return $this->_idNiveau;
    }

    public function getNiveau() {
        return AtelierNiveau::getAtelierNiveauById($this->_idNiveau);
    }
    
    public function getIdCategorie() {
        return $this->_idCategorie;
    }

    public function getCategorie() {
        return AtelierCategorie::getAtelierCategorieById($this->_idCategorie);
    }
    /*
     * Fonctions de l'objet
     */
    
    public function modifier($label, $content, $ressource, $idNiveau, $idCategorie) {
        $success = FALSE;
        $db = Mysql::opendb();
        
        $label       = mysqli_real_escape_string($db, $label);
        $content     = mysqli_real_escape_string($db, $content);
        $ressource   = mysqli_real_escape_string($db, $ressource);
        $idNiveau    = mysqli_real_escape_string($db, $idNiveau);
        $idCategorie = mysqli_real_escape_string($db, $idCategorie);

        $sql = "UPDATE `tab_atelier_sujet` "
            . "SET `label_atelier`='" . $label . "', "
            . "`content_atelier`='" . $content . "', "
            . "`ressource_atelier`='" . $ressource . "', "
            . "`niveau_atelier`='" . $idNiveau . "', "
            . "`categorie_atelier`='" . $idCategorie . "' "
            . "WHERE `id_sujet`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_label       = $label;
            $this->_content     = $content;
            $this->_ressource   = $ressource;
            $this->_idNiveau    = $idNiveau;
            $this->_idCategorie = $idCategorie;
            $success = TRUE;
        }

        return $success;
    }

    public function supprimer() {
        $db = Mysql::opendb();
        $sql    = "DELETE FROM `tab_atelier_sujet` WHERE `id_sujet`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        
        Mysql::closedb($db);
        
        return $result;
    }


    /*
    * Fonctions statiques
    */
    
    public static function getAtelierSujetById($id) {

        $atelierSujet = null;
        
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_atelier_sujet` "
                 . "WHERE `id_sujet` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                $atelierSujet = new AtelierSujet(mysqli_fetch_assoc($result));
            }
        }
        
        return $atelierSujet;
    }
    
    public static function creerAtelierSujet($label, $content, $ressource, $idNiveau, $idCategorie)
    {
        $atelierSujet = null;
        
        $db  = Mysql::opendb();
        
        $label       = mysqli_real_escape_string($db, $label);
        $content     = mysqli_real_escape_string($db, $content);
        $ressource   = mysqli_real_escape_string($db, $ressource);
        $idNiveau    = mysqli_real_escape_string($db, $idNiveau);
        $idCategorie = mysqli_real_escape_string($db, $idCategorie);

        $sql = "INSERT INTO `tab_atelier_sujet` (`label_atelier`,`content_atelier`,`ressource_atelier`,`niveau_atelier`,`categorie_atelier`) VALUES ('" . $label . "', '" . $content . "', '" . $ressource . "', '" . $idNiveau . "', '" . $idCategorie . "')";
        
        $result = mysqli_query($db,$sql);
        
        if ($result) {
            $atelierSujet = new AtelierSujet(array("id_sujet" => mysqli_insert_id($db), "label_categorie" => $label, "content_atelier" => $content, "ressource_atelier" => $ressource, "niveau_atelier" => $idNiveau, "categorie_atelier" => $idCategorie));
        }
        
        Mysql::closedb($db);

        return $atelierSujet;
    }
    
    
    public static function getAtelierSujets() {
        $atelierSujets = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_atelier_sujet order by label_atelier";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $atelierSujets = array();
            while($row = mysqli_fetch_assoc($result)) {
                $atelierSujets[] = new AtelierSujet($row);
            }
            mysqli_free_result($result);
        }
        
        return $atelierSujets;

    }
}