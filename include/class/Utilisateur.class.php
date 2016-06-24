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
    private $_idVille;
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
    
    public function __construct()
    {
        $args = func_get_args();
        $numArgs = func_num_args();
        
        // valeur par défaut. Doit changer si l'initialisation a réussi,
        // sinon, ça veut dire que l'utilisateur n'existe pas.
        $this->_id = 0;    
        
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
    
    public function __constructId($id)
    {
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_user` "
                 . "WHERE `id_user` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                
                $row = mysqli_fetch_array($result);
                
                $this->_id              = $row["id_user"];
                $this->_dateInscription = $row["date_insc_user"];
                $this->_nom             = $row["nom_user"];
                $this->_prenom          = $row["prenom_user"];
                $this->_sexe            = $row["sexe_user"];
                $this->_dateNaissance   = date_create_from_format(
                                            "Y-m-d",
                                            $row["annee_naissance_user"]
                                            . "-" . $row["mois_naissance_user"]
                                            . "-" . $row["jour_naissance_user"]
                                           );
                $this->_adresse         = $row["adresse_user"];
                $this->_idVille         = $row["ville_user"];
                $this->_telephone       = $row["tel_user"];
                $this->_mail            = $row["mail_user"];
                $this->_temps           = $row["temps_user"];
                $this->_login           = $row["login_user"];
                $this->_motDePasse      = $row["pass_user"];
                $this->_statut          = $row["status_user"];
                $this->_derniereVisite  = $row["lastvisit_user"];
                $this->_csp             = $row["csp_user"];
                $this->_equipement      = $row["equipement_user"];
                $this->_utilisation     = $row["utilisation_user"];
                $this->_connaissance    = $row["connaissance_user"];
                $this->_info            = $row["info_user"];
                $this->_tarif           = $row["tarif_user"];
                $this->_dateRen         = $row["dateRen_user"];
                $this->_epn             = $row["epn_user"];
                $this->_newsletter      = $row["newsletter_user"];
            }
        }            

    }
    public function __constructLoginPassword($login, $password)
    {
        if ($login != "" && $password != "") {
            $db = Mysql::opendb();
            $login = mysqli_real_escape_string($db, $login);
            $sql = "SELECT * "
                 . "FROM `tab_user` "
                 . "WHERE `login_user` = '" . $login ."' "
                 . "AND `pass_user` = '" . md5($password) . "'";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                
                $row = mysqli_fetch_array($result);
                
                $this->_id              = $row["id_user"];
                $this->_dateInscription = $row["date_insc_user"];
                $this->_nom             = $row["nom_user"];
                $this->_prenom          = $row["prenom_user"];
                $this->_sexe            = $row["sexe_user"];
                $this->_dateNaissance   = date_create_from_format(
                                            "Y-m-d",
                                            $row["annee_naissance_user"]
                                            . "-" . $row["mois_naissance_user"]
                                            . "-" . $row["jour_naissance_user"]
                                          );
                $this->_adresse         = $row["adresse_user"];
                $this->_idVille           = $row["ville_user"];
                $this->_telephone       = $row["tel_user"];
                $this->_mail            = $row["mail_user"];
                $this->_temps           = $row["temps_user"];
                $this->_login           = $row["login_user"];
                $this->_motDePasse      = $row["pass_user"];
                $this->_statut          = $row["status_user"];
                $this->_derniereVisite  = $row["lastvisit_user"];
                $this->_csp             = $row["csp_user"];
                $this->_equipement      = $row["equipement_user"];
                $this->_utilisation     = $row["utilisation_user"];
                $this->_connaissance    = $row["connaissance_user"];
                $this->_info            = $row["info_user"];
                $this->_tarif           = $row["tarif_user"];
                $this->_dateRen         = $row["dateRen_user"];
                $this->_epn             = $row["epn_user"];
                $this->_newsletter      = $row["newsletter_user"];
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
        $sql = "UPDATE tab_user SET lastvisit_user='" . date("Y-m-d") . "' WHERE `id_user`=" . $this->_id ;
        $db = Mysql::opendb();
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
    }
    
}