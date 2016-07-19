<?php
include_once("Mysql.class.php");
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
    
    public function __construct()
    {
        $args    = func_get_args();
        $numArgs = func_num_args();
        
        // valeur par défaut. Doit changer si l'initialisation a réussi,
        // sinon, ça veut dire que l'espace n'existe pas.
        $this->_id = 0;    

        if ($numArgs === 1) {
            if (is_int($args[0]) ) {
                $this->__constructId($args[0]);
            }
        }
    }
    
    public function __constructId($id)
    {
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_espace` "
                 . "WHERE `id_espace` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                
                $row = mysqli_fetch_array($result);
                
                $this->_id = $row["id_espace"];
                $this->_nom = $row["nom_espace"];
                $this->_idVille = $row["id_city"];
                $this->_adresse = $row["adresse"];
                $this->_telephone = $row["tel_espace"];
                $this->_fax = $row["fax_espace"];
                $this->_logo = $row["logo_espace"];
                $this->_couleur = $row["couleur_espace"];
                $this->_mail = $row["mail_espace"];
            }
        }            
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
    
    
}