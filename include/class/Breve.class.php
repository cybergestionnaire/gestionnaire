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

class Breve
{
    private $_id;
    private $_titre;
    private $_commentaire;
    private $_visible;
    private $_type;
    private $_datePublication;
    private $_dateBreve;
    private $_idEspace;

    /**
     * Constructeur de la classe Intervention.
     * 
     * @param type $array tableau associatif de valeurs : id_inter, titre_inter, comment_inter, statut_inter, date_inter
     */
    private function __construct($array)
    {
        $this->_id = $array["id_news"];
        $this->_titre = $array["titre_news"];
        $this->_commentaire = $array["comment_news"];
        $this->_visible = $array["visible_news"];
        $this->_type = $array["type_news"];
        $this->_datePublication = $array["date_publish"];
        $this->_dateBreve = $array["date_news"];
        $this->_idEspace = $array["id_epn"];

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

    public function getVisible()
    {
        return $this->_visible;
    }
    
    public function getType()
    {
        return $this->_type;
    }

    public function getDatePublication()
    {
        return $this->_datePublication;
    }

    public function getDateBreve()
    {
        return $this->_dateBreve;
    }

    public function getIdEspace() {
        return $this->_idEspace;
    }

    public function getEspace() {
        return Espace::getEspaceById($this->_idEspace);
    }

    
    /*
     * Fonctions de l'objet
     */

    public function modifier($titre, $commentaire, $visible, $type, $datePublication, $dateBreve, $idEspace)
    {

        $success = false;
        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $commentaire = mysqli_real_escape_string($db, $commentaire);
        $visible = mysqli_real_escape_string($db, $visible);
        $type = mysqli_real_escape_string($db, $type);    
        $datePublication = mysqli_real_escape_string($db, $datePublication);    
        $dateBreve = mysqli_real_escape_string($db, $dateBreve);    
        $idEspace = mysqli_real_escape_string($db, $idEspace);    

        $sql = "UPDATE `tab_news` "
                . "SET `titre_news`='" . $titre . "', `comment_news`='" . $commentaire . "', `visible_news`='" . $visible . "', `type_news`='" . $type . "', `date_publish`='" . $datePublication . "', `date_news`='" . $dateBreve . "', `id_epn`='" . $idEspace . "' "
                . "WHERE `id_news`=" . $this->_id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_titre = $titre;
            $this->_commentaire = $commentaire;
            $this->_visible = $visible;
            $this->_type = $type;
            $this->_datePublication = $datePublication;
            $this->_dateBreve = $dateBreve;
            $this->_idEspace = $idEspace;

            $success = true;
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;
        $db = Mysql::opendb();

        $sql = "DELETE FROM `tab_news` WHERE `id_news`=" . $this->_id;
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

    public static function getBreveById($id)
    {
        $breve = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_news` "
                    . "WHERE `id_news` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if (mysqli_num_rows($result) == 1) {
                $breve = new Breve(mysqli_fetch_assoc($result));
            }
        }

        return $breve;
    }

    public static function creerBreve($titre, $commentaire, $visible, $type, $datePublication, $dateBreve, $idEspace)
    {
        $breve = null;

        $db = Mysql::opendb();

        $titre = mysqli_real_escape_string($db, $titre);
        $commentaire = mysqli_real_escape_string($db, $commentaire);
        $visible = mysqli_real_escape_string($db, $visible);
        $type = mysqli_real_escape_string($db, $type);    
        $datePublication = mysqli_real_escape_string($db, $datePublication);    
        $dateBreve = mysqli_real_escape_string($db, $dateBreve);    
        $idEspace = mysqli_real_escape_string($db, $idEspace);    

        $sql = "INSERT INTO `tab_news` (`titre_news`,`comment_news`,`visible_news`,`type_news`,`date_publish`,`date_news`,`id_epn`) "
             . "VALUES ('" . $titre . "', '" . $commentaire . "', '" . $visible . "', '" . $type . "', '" . $datePublication . "', '" . $dateBreve . "', '" . $idEspace . "') ";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $breve = new Breve(array(
                "id_news" => mysqli_insert_id($db),
                "titre_news" => $titre,
                "comment_news" => $commentaire,
                "visible_news" => $visible,
                "type_news" => $type,
                "date_publish" => $datePublication,
                "date_news" => $dateBreve,
                "id_epn" => $idEspace));
        }

        Mysql::closedb($db);

        return $breve;
    }

    public static function getBreves()
    {
        $breves = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM `tab_news` ORDER BY date_news";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $breves = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $breves[] = new Breve($row);
            }
        }

        return $breves;
    }

    public static function getBrevesUtilisateur()
    {
        $breves = null;

        $db = Mysql::opendb();
        $sql = "SELECT * "
             . "FROM `tab_news` "
             . "WHERE `visible_news`= 0 "
             . "ORDER BY `id_news` ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $breves = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $breves[] = new Breve($row);
            }
        }

        return $breves;
    }
    
    
               
}
