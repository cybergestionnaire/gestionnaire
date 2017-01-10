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
require_once("Espace.class.php");

class Salle
{
    
    private $_id;
    private $_nom;
    private $_idEspace;
    private $_commentaire;
    
   
    public function __construct($array)
    {
        $this->_id          = $array["id_salle"];
        $this->_nom         = $array["nom_salle"];
        $this->_idEspace    = $array["id_espace"];
        $this->_commentaire = $array["comment_salle"];
    }
    
    public function getId() {
        return $this->_id;
    }
    public function getNom() {
        return $this->_nom;
    }

    public function getIdEspace() {
        return $this->_idEspace;
    }
    
    public function getEspace() {
        return Espace::getEspaceById($this->_idEspace);
    }

    public function getCommentaire() {
        return $this->_commentaire;
    }
    
    public function getNbPostes() {
        $nbPostes = null;

        $db = Mysql::opendb();
        $sql="SELECT count(`nom_computer`) as nbPostes FROM tab_computer WHERE id_salle = " . $this->_id . ";";
        $result = mysqli_query($db,$sql);
            
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $nbPostes = $row["nbPostes"];
            mysqli_free_result($result);
        }
        
        Mysql::closedb($db);
        return $nbPostes;
    }
    
    public function modifier($nom, $idEspace, $commentaire) {
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
    }
    
    public function supprimer() {

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
    }
   
    
    public static function creerSalle($nom, $idEspace, $commentaire) {
        $salle = null;
        
        if ( $nom != ""
            && (is_int($idEspace) && $idEspace != 0)
        ) {
            $db = Mysql::opendb();
            
            $nom         = mysqli_real_escape_string($db, $nom);
            $idEspace    = mysqli_real_escape_string($db, $idEspace);
            $commentaire = mysqli_real_escape_string($db, $commentaire);


            $sql = "INSERT INTO `tab_salle` (`nom_salle`,`id_espace`,`comment_salle`) "
                 . "VALUES ('" . $nom . "', '" . $idEspace."', '" . $commentaire . "') " ;       
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $salle = new Salle(array("id_salle" => mysqli_insert_id($db), "nom_salle" => $nom, "id_espace" => $idEspace, "comment_salle" => $commentaire));
            }
            
            Mysql::closedb($db);
        }
        return $salle;
    }
    
    public static function getSalleById($id) {
        $salle = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_salle` "
                 . "WHERE `id_salle` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $salle = new Salle(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $salle;
    }
    public static function getSalles() {

        $salles = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_salle ORDER BY nom_salle ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $salles = array();
            while($row = mysqli_fetch_assoc($result)) {
                $salles[] = new Salle($row);
            }
            mysqli_free_result($result);
        }
        
        return $salles;
    
    }
    
}