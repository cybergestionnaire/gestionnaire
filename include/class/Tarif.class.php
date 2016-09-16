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

class Tarif
{
    
    private $_id;
    private $_nom;
    private $_donnee;
    private $_commentaire;
    private $_nbAtelierForfait;
    private $_categorie;
    private $_duree;
    private $_idsEspaces;
    
    
   
    public function __construct($array)
    {
        $this->_id               = $array["id_tarif"];
        $this->_nom              = $array["nom_tarif"];
        $this->_donnee           = $array["donnee_tarif"];
        $this->_commentaire      = $array["comment_tarif"];
        $this->_nbAtelierForfait = $array["nb_atelier_forfait"];
        $this->_categorie        = $array["categorie_tarif"];
        $this->_duree            = $array["duree_tarif"];
        $this->_idsEspaces       = $array["epn_tarif"];
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function getNom() {
        return $this->_nom;
    }

    public function getDonnee() {
        return $this->_donnee;
    }
    
    public function getCommentaire() {
        return $this->_commentaire;
    }

    public function getNbAtelierForfait() {
        return $this->_nbAtelierForfait;
    }

    public function getCategorie() {
        return $this->_categorie;
    }

    public function getDuree() {
        return $this->_duree;
    }

    public function getIdsEspaces() {
        return $this->_idsEspaces;
    }
    
    public function getIdsEspacesAsArray() {
        return explode('-', $this->_idsEspaces);
    }
    
    public function modifier($nom, $prix, $commentaire, $nbAteliers, $categorie, $duree, $idsEspaces) {
        $success = FALSE;

        $db = Mysql::opendb();

        if ( $nom != ""
            && $prix != ""
            && $categorie != ""
        ) {
            $db = Mysql::opendb();
            
            $nom         = mysqli_real_escape_string($db, $nom);
            $prix        = mysqli_real_escape_string($db, $prix);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $nbAteliers  = mysqli_real_escape_string($db, $nbAteliers);
            $categorie   = mysqli_real_escape_string($db, $categorie);
            $duree       = mysqli_real_escape_string($db, $duree);
            $idsEspaces  = mysqli_real_escape_string($db, $idsEspaces);
        

            $sql = "UPDATE `tab_tarifs` "
                . "SET nom_tarif= '" . $nom . "', "
                . "donnee_tarif='" . $prix . "', "
                . "comment_tarif='" . $commentaire . "', "
                . "nb_atelier_forfait='" . $nbAteliers . "', "
                . "categorie_tarif='" . $categorie . "', "
                . "duree_tarif= '" . $duree . "', "
                . "epn_tarif='" . $idsEspaces . "' "
                . "WHERE id_tarif='" . $this->_id . "'";

            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);

            if ($result) {
                $this->_nom         = $nom;
                $this->_prix        = $prix;
                $this->_commentaire = $commentaire;
                $this->_nbAteliers  = $nbAteliers;
                $this->_categorie   = $categorie;
                $this->_duree       = $duree;
                $this->_idsEspaces  = $idsEspaces;
                
                $success = TRUE;
            }
        }

        return $success;
    }
    
    public function supprimer() {
        $success = false;
        
        $db     = Mysql::opendb();
        $sql    = "DELETE FROM `tab_tarifs` WHERE `id_tarif`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }
        return $success;
    }
    
    public static function creerTarif($nom, $prix, $commentaire, $nbAteliers, $categorie, $duree, $idsEspaces) {
        $tarif = null;

        if ( $nom != ""
            && $prix != ""
            && $categorie != ""
        ) {
            $db = Mysql::opendb();
            
            $nom         = mysqli_real_escape_string($db, $nom);
            $prix        = mysqli_real_escape_string($db, $prix);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $nbAteliers  = mysqli_real_escape_string($db, $nbAteliers);
            $categorie   = mysqli_real_escape_string($db, $categorie);
            $duree       = mysqli_real_escape_string($db, $duree);
            $idsEspaces  = mysqli_real_escape_string($db, $idsEspaces);


            $sql = "INSERT INTO `tab_tarifs` (`nom_tarif`,`donnee_tarif`, `comment_tarif`, `nb_atelier_forfait`, `categorie_tarif`, `duree_tarif`, `epn_tarif`) "
                 . "VALUES ('" . $nom . "', '" . $prix."', '" . $commentaire . "', '" . $nbAteliers . "', '" . $categorie . "', '" . $duree . "', '" . $idsEspaces . "') " ;       
            
            error_log("requete SQL : {$sql}");
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $tarif = new Tarif(array(
                    "id_tarif" => mysqli_insert_id($db),
                    "nom_tarif" => $nom,
                    "donnee_tarif" => $idEspace,
                    "comment_tarif" => $commentaire,
                    "nb_atelier_forfait" => $nbAteliers,
                    "categorie_tarif" => $categorie,
                    "duree_tarif" => $duree,
                    "epn_tarif" => $idsEspaces
                ));
            }
            
            Mysql::closedb($db);
        }
        return $tarif;
    }
    
    

    public static function getTarifById($id) {
        $tarif = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_tarifs` "
                 . "WHERE `id_tarif` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $tarif = new Tarif(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $tarif;
    }
    
    public static function getTarifs() {

        $tarifs = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM tab_tarifs ORDER BY nom_tarif ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $tarifs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $tarifs[] = new Tarif($row);
            }
            mysqli_free_result($result);
        }
        
        return $tarifs;
    }
    
    public static function getTarifsByCategorie($categorie) {

        $tarifs = null;
    
        $db      = Mysql::opendb();
        
        // pourquoi enlever le tarif avec l'id 1 ???
        $sql     = "SELECT * FROM `tab_tarifs` WHERE `categorie_tarif`='" . $categorie . "' AND `id_tarif`>1 ORDER BY `id_tarif` ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $tarifs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $tarifs[] = new Tarif($row);
            }
            mysqli_free_result($result);
        }
        
        return $tarifs;
    }   
}