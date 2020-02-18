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
  
  Ajout 2020

 */


//require_once("Mysql.class.php");

class Courrier
{
    private $_id;
    private $_titrec;
    private $_texte;
    private $_name;
    private $_type;
   
   private $_newsletter;
 

    /**
     * Constructeur de la classe Intervention.
     * 
     * @param type $array tableau associatif de valeurs : id_inter, titre_inter, comment_inter, statut_inter, date_inter
     */
    private function __construct($array)
    {
        $this->_id = $array["id_courrier"];
        $this->_titrec = $array["courrier_titre"];
        $this->_texte = $array["courrier_text"];
        $this->_name = $array["courrier_name"];
        $this->_type = $array["courrier_type"];
       
		//$this->_newsletter=$array["newsletter"];

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
        return $this->_titrec;
    }

    public function getTexte()
    {
        return $this->_texte;
    }

    public function getName()
    {
        return $this->_name;
    }
    
    public function getType()
    {
        return $this->_type;
    }

	
    /*
     * Fonctions de l'objet
     */

    public function modifier($id, $titrec, $texte, $name, $type)
    {

        $success = false;
        $db = Mysql::opendb();

        $titrec = mysqli_real_escape_string($db, $titrec);
        $texte = mysqli_real_escape_string($db, $texte);
        $name = mysqli_real_escape_string($db, $name);
        $type = mysqli_real_escape_string($db, $type);    
        

        $sql = "UPDATE `tab_courriers` "
                . "SET `courrier_titre`='" . $titrec . "', `courrier_text`='" . $texte . "', `courrier_name`='" . $name . "', `courrier_type`='" . $type . "' "
                . "WHERE `id_courrier`=" . $id;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            
            $success = true;
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;
        $db = Mysql::opendb();

        $sql = "DELETE FROM `tab_courriers` WHERE `id_courrier`=" . $this->_id;
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

  public static function getCourrierById($id)
    {
        $courrier = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * FROM `tab_courriers` WHERE `id_courrier` = ".$id ;
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);
		
           
            if (mysqli_num_rows($result) == 1) {
				
                $courrier = new Courrier(mysqli_fetch_assoc($result));
            }
        }

        return $courrier;
    }

    public static function creerCourrier($titre, $texte, $name, $type)
    {
        $courrier = null;

        $db = Mysql::opendb();

        $titrec = mysqli_real_escape_string($db, $titrec);
        $texte = mysqli_real_escape_string($db, $texte);
        $name = mysqli_real_escape_string($db, $name);
        $type = mysqli_real_escape_string($db, $type);    
       

        $sql = "INSERT INTO `tab_courriers` (`courrier_titre`,`courrier_text`,`courrier_name`,`courrier_type`) "
             . "VALUES ('" . $titrec . "', '" . $texte . "', '" . $name . "', '" . $type . "') ";
        $result = mysqli_query($db, $sql);

        if ($result) {
            $courrier = new Courrier(array(
                "id_courrier" => mysqli_insert_id($db),
                "courrier_titre" => $titrec,
                "courrier_text" => $texte,
                "courrier_name" => $name,
                "courrier_type" => $type));
        }

        Mysql::closedb($db);

        return $courrier;
    }

    public static function getAllCourrier()
    {
        $courrier = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM `tab_courriers` ORDER BY `id_courrier`";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $courrier = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $courrier[] = new courrier($row);
            }
        }

        return $courrier;
    }

     //gestion de la newsletter
	public static function getNewsletterUsers()
	{
		$courrier = null;
				
		$db = Mysql::opendb();
		$sql = "SELECT `id_user` FROM `tab_user` WHERE `newsletter_user`=1";
		$result = mysqli_query($db, $sql);
		Mysql::closedb($db);
		
		$row=mysqli_num_rows($result);
		
		if ($result) {
			
			$courrier=$row;
           }else{
			$courrier="";
		}
			return $courrier;
		
	}
	
	//sur la page atelier, recuperer les infos du mail de rappel
	public static function getMailRappel()
	{
		$courrier=null;
		$db = Mysql::opendb();
		$sql = "SELECT `courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1";
		
		$result = mysqli_query($db, $sql);
		Mysql::closedb($db);
		
		if ($result) {
			
			$courrier = array();
			$nb = mysqli_num_rows($result);
			for ($i = 1; $i <= $nb; $i++) {
				$row = mysqli_fetch_array($result);
				$courrier[$row["courrier_type"]] = $row["courrier_text"];
			}
			return $courrier;
		}
	}
	
	//sur la page utilisateur, recuperer les infos du mail d'inscription
	public static function getMailInscript()
	{
		$courrier=null;
		$db = Mysql::opendb();
		$sql = "SELECT `courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1 AND `courrier_titre` LIKE '%inscription%' ";
		$result = mysqli_query($db, $sql);
		Mysql::closedb($db);
		
		if ($result == false) {
			return false;
		} else {
			$courrier = array();
			$nb = mysqli_num_rows($result);
			for ($i = 1; $i <= $nb; $i++) {
				$row = mysqli_fetch_array($result);
				$courrier[$row["courrier_type"]] = $row["courrier_text"];
			}
			return $courrier;
		}
	}
               
}
