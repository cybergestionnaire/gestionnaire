<?php
include_once("Mysql.class.php");
class Ville
{
    private $_id;
    private $_nom;
    private $_codePostal;
    private $_pays;

    public function __construct()
    {
        $args = func_get_args();
        $numArgs = func_num_args();
        
        // valeur par défaut. Doit changer si l'initialisation a réussi,
        // sinon, ça veut dire que la ville n'existe pas.
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
                 . "FROM `tab_city` "
                 . "WHERE `id_city` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                
                $row = mysqli_fetch_array($result);
                
                $this->_id = $row["id_city"];
                $this->_nom = $row["nom_city"];
                $this->_codePostal = $row["code_postale_city"];
                $this->_pays = $row["`pays_city"];
            }
        }            
    }
    
    public function getId() {
        return $this->_id;
    }
    public function getNom() {
        return $this->_nom;
    }
    public function getCodePostal() {
        return $this->_codePostal;
    }
    public function getPays() {
        return $this->_pays;
    }

}