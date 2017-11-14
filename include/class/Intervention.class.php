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


//require_once("Mysql.class.php");

class Intervention
{
    private $_id;
    private $_titre;
    private $_commentaire;
    private $_statut;
    private $_date;

    /**
     * Constructeur de la classe Intervention.
     * 
     * @param type $array tableau associatif de valeurs : id_inter, titre_inter, comment_inter, statut_inter, date_inter
     */
    private function __construct($array)
    {
        $this->_id = $array["id_inter"];
        $this->_titre = $array["titre_inter"];
        $this->_commentaire = $array["comment_inter"];
        $this->_statut = $array["statut_inter"];
        $this->_date = $array["date_inter"];
    }

    /*
     * Accesseurs basiques
     */

    public function getId()
    {
        return $this->_id;
    }

    public function getTitre()
    {
        return $this->_titre;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
    }

    public function getStatut()
    {
        return $this->_statut;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setInterventionEnCours() {
        return $this->setStatut(0);
    }

    public function setInterventionTerminee() {
        return $this->setStatut(1);
    }    

    public function setStatut($statut) {
    
        $success = false;
        
        $db = Mysql::opendb();          
        $sql = "UPDATE `tab_inter` SET `statut_inter`='" . $statut . "' WHERE `id_inter`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        
        Mysql::closedb($db);
       
        if ($result) {
            $success = true;
        }
        
        return $success;
    }
    
    /*
     * Fonctions de l'objet
     */

    public function getMateriels()
    {
        return Materiel::getMaterielFromInterventionById($this->_id);
    }
    
    public function addMateriel($idMateriel) {
        $success = false;

        $db = Mysql::opendb();          
        $sql = "INSERT INTO `rel_inter_computer` (`id_inter_computer`, `id_inter`, `id_computer`) VALUES ('','" . $this->_id . "', '" . $idMateriel . "')";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $success = true;
        }
        
        return $success;
    }

    public function modifier($titre, $commentaire, $statut, $date)
    {
        $success = false;
        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $commentaire = mysqli_real_escape_string($db, $commentaire);
        $statut = mysqli_real_escape_string($db, $statut);
        $date = mysqli_real_escape_string($db, $date);    

        $sql = "UPDATE `tab_inter` "
                . "SET `titre_inter`='" . $titre . "', `comment_inter`='" . $commentaire . "', `statut_inter`='" . $statut . "', `date_inter`='" . $date . "' "
                . "WHERE `id_inter`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_titre = $titre;
            $this->_commentaire = $commentaire;
            $this->_statut = $statut;
            $this->_date = $date;

            $success = true;
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;
        $db = Mysql::opendb();

        $sql = "DELETE FROM `tab_inter` WHERE `id_inter`=" . $this->_id;
        $result = mysqli_query($db, $sql);
        if ($result) {
            $success = true;
        }
        Mysql::closedb($db);
        
        return $success;
    }

    /*
     * Fonctions statiques
     */

    public static function getInterventionById($id)
    {
        $inter = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_inter` "
                    . "WHERE `id_inter` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $inter = new Intervention(mysqli_fetch_assoc($result));
            }
        }

        return $inter;
    }

    public static function creerIntervention($titre, $commentaire, $statut, $date)
    {
        $inter = null;

        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $commentaire = mysqli_real_escape_string($db, $commentaire);
        $statut = mysqli_real_escape_string($db, $statut);
        $date = mysqli_real_escape_string($db, $date);

        $sql = "INSERT INTO `tab_inter` (`titre_inter`,`comment_inter`,`statut_inter`,`date_inter`) "
             . "VALUES ('" . $titre . "', '" . $commentaire . "', '" . $statut . "', '" . $date . "') ";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $inter = new Intervention(array(
                "id_inter" => mysqli_insert_id($db),
                "titre_inter" => $titre,
                "comment_inter" => $commentaire,
                "statut_inter" => $statut,
                "date_inter" => $date));
        }

        Mysql::closedb($db);

        return $inter;
    }

    public static function getInterventions()
    {
        $inters = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM `tab_inter` ORDER BY date_inter";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $inters = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $inters[] = new Intervention($row);
            }
        }

        return $inters;
    }
    
}
