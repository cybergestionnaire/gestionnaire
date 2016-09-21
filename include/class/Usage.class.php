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

class Usage
{
    
    private $_id;
    private $_nom;
    private $_type;
    
   
    public function __construct($array)
    {
        $this->_id   = $array["id_usage"];
        $this->_nom  = $array["nom_usage"];
        $this->_type = $array["type_usage"];
    }
    
    public function getId() {
        return $this->_id;
    }
    public function getNom() {
        return $this->_nom;
    }

    public function getType() {
        return $this->_type;
    }
    
    public function modifier($nom, $type = "public") {
        $success = FALSE;
        $db = Mysql::opendb();
        


        $sql = "UPDATE `tab_usage` "
            . "SET `nom_usage` = '" . $nom . "', `type_usage` = '" . $type . "' "
            . "WHERE `id_usage` = " . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_nom  = $nom;
            $this->_type = $type;
           
            $success = TRUE;
        }

        return $success;
    }
    
    public function supprimer() {

        $db     = Mysql::opendb();

        $sql    = "DELETE FROM `tab_usage` WHERE `id_usage`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        return $result;
    }
   
    
    public static function creerUsage($nom, $type = "public") {
        $usage = null;
        
        if ( $nom != ""
            && $type != ""
        ) {
            error_log("debut création ! nom = {$nom} / type = {$type}");
            $db = Mysql::opendb();
            
            $nom  = mysqli_real_escape_string($db, $nom);
            $type = mysqli_real_escape_string($db, $type);


            $sql = "INSERT INTO `tab_usage` (`nom_usage`,`type_usage`) "
                 . "VALUES ('" . $nom . "', '" . $type . "') " ;
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $usage = new Usage(array("id_usage" => mysqli_insert_id($db), "nom_usage" => $nom, "type_usage" => $type));
            }
            
            Mysql::closedb($db);
        }
        return $usage;
    }
    
    public static function getUsageById($id) {
        $usage = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_usage` "
                 . "WHERE `id_usage` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $usage = new Usage(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $usage;
    }
    public static function getUsages() {

        $usages  = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_usage order by `type_usage` DESC, `nom_usage`";
        $result  = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        if ($result) {
            $usages = array();
            while($row = mysqli_fetch_assoc($result)) {
                $usages[] = new Usage($row);
            }
            mysqli_free_result($result);
        }
        
        return $usages;
    
    }
    
}