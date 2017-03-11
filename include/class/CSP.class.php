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

class CSP
{
    private $_id;
    private $_CSP;

    /**
     * constructeur privé : il est appelé uniquement par les méthodes statiques de la classe
     *
     * Il ne devrait pas y avoir de "new Ville()" ailleurs que dans la classe elle-même.
     * Charge à chaque fonction statique de renvoyer le ou les objets qui vont bien.
     *
     * @param ArrayObject $array Tableau associatif contenant les données d'initialisation de l'objet
     *                           les clés utilisées dépendent du nommage des champs dans la table "tab_city"
     */
    private function __construct($array) {
        $this->_id         = $array["id_csp"];
        $this->_CSP        = $array["csp"];
    }
    
    /*
    * Accesseurs basiques
    */
    
    public function getId() {
        return $this->_id;
    }
    public function getCSP() {
        return $this->_CSP;
    }

    /*
     * Fonctions de l'objet
     */
    
    public function modifier($csp) {
        $success = FALSE;
        $db = Mysql::opendb();
        
        $csp        = mysqli_real_escape_string($db, $csp);

        $sql = "UPDATE `tab_csp` "
            . "SET `csp`='" . $csp . "' "
            . "WHERE `id_csp`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_CSP         = $csp;
            
            $success = TRUE;
        }

        return $success;
    }
    
    public function supprimer() {
        $db = Mysql::opendb();
        $sql    = "DELETE FROM `tab_csp` WHERE `id_csp`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        
        Mysql::closedb($db);
        
        return $result;
    }


    /*
    * Fonctions statiques
    */
    
    public static function getCSPById($id) {

        $csp = null;
        
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_csp` "
                 . "WHERE `id_csp` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                $csp = new CSP(mysqli_fetch_assoc($result));
            }
        }
        
        return $csp;
    }
    
    public static function creerCSP($nom)
    {
        $csp = null;
        
        $db  = Mysql::opendb();
        
        $nom = mysqli_real_escape_string($db, $nom);

        $sql = "SELECT * FROM `tab_csp` WHERE `csp` = '" . $nom . "' ";

        $result = mysqli_query($db,$sql);

        if ($result && mysqli_num_rows($result) == 0) {
            // ok, on n'a pas de csp correspondante
            $sql = "INSERT INTO `tab_csp` (`csp`) VALUES ('" . $nom . "')";
        
            $result = mysqli_query($db,$sql);
        
            if ($result) {
                $csp = new CSP(array("id_csp" => mysqli_insert_id($db), "csp" => $nom));
            }
        }
        
        Mysql::closedb($db);

        return $csp;
    }
    
    
    public static function getCSPs() {

        $csps   = null;
    
        $db     = Mysql::opendb();
        $sql    = "SELECT * FROM `tab_csp` ORDER BY csp" ;
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $csps = array();
            while($row = mysqli_fetch_assoc($result)) {
                $csps[] = new CSP($row);
            }
        }
        
        return $csps;
    }
}