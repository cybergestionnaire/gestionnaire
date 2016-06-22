<?php
include_once("Mysql.class.php");
class Utilisateur
{
    
    private $_id;
    private $_dateInscription;
    private $_nom;
    private $_prenom;
    private $_sexe;
    private $_dateNaissance;
    private $_adresse;
    private $_ville;
    private $_telephone;
    private $_mail;
    private $_temps;
    private $_login;
    private $_motDePasse;
    private $_statut;
    private $_derniereVisite;
    private $_csp;
    private $_equipement;
    private $_utilisation;
    private $_connaissance;
    private $_info;
    private $_tarif;
    private $_dateRen;
    private $_epn;
    private $_newsletter;
    
    public function __construct() {
        $args = func_get_args();
        $numArgs = func_num_args();

        $this->_id = 0;    // valeur par défaut. Doit changer si l'initialisation a réussi, sinon, ça veut dire que l'utilisateur n'existe pas.
        
        if ($numArgs === 1) {
            if (is_int($args[0]) ) {
                $this->__constructId($args[0]);
            }
        }
        if ($numArgs === 2) {
            if (is_string($args[0]) && is_string($args[1])) {
                $this->__constructLoginPassword($args[0], $args[1]);
            }
        }
        
    }
    
    public function __constructId($id) {
        $db = Mysql.opendb();
        
    }
    public function __constructLoginPassword($login, $password) {
        if ($login != "" && $password != "") {
            $db = Mysql::opendb();
            $login = mysqli_real_escape_string($db, $login);
            $sql = "SELECT *
                  FROM `tab_user`
                  WHERE `login_user` = '".$login."'
                  AND `pass_user` = '".md5($password)."'
                 ";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                
                $row = mysqli_fetch_array($result);
                
                $this->_id     = $row["id_user"];
                $this->_login  = $row["login_user"];
                $this->_statut = $row["status_user"];
                $this->_epn    = $row["epn_user"];
            }
        }            
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function getLogin() {
        return $this->_login;
    }
    
    public function getStatut() {
        return $this->_statut;
    }
    
    public function getEpn() {
        return $this->_epn;
    }
    
    public function MAJVisite() {
        $sql = "UPDATE tab_user SET lastvisit_user='".date("Y-m-d")."' WHERE `id_user`=".$this->_id ;
        $db = Mysql::opendb();
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
    }
    
}