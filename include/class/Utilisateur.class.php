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
    private $_dateRenouvellement;
    private $_idEpn;
    private $_newsletter;
    
    public function __construct()
    {
        $args    = func_get_args();
        $numArgs = func_num_args();
        
        // valeur par défaut. Doit changer si l'initialisation a réussi,
        // sinon, ça veut dire que l'utilisateur n'existe pas.
        $this->_id = 0;    
        
        if ($numArgs === 1) {
            if (is_int($args[0]) ) {
                $this->__constructId($args[0]);
            }
            
            if (is_array($args[0]) && count($args[0]) == 26) {
                $this->__constructArray($args[0]);
            }
        }
        if ($numArgs === 2) {
            if (is_string($args[0]) && is_string($args[1])) {
                $this->__constructLoginPassword($args[0], $args[1]);
            }
        }
        
        if ($numArgs === 23) {
            $this->__constructUtilisateur(
                $args[0],   // dateInscription (format Y-m-d)
                $args[1],   // nom
                $args[2],   // prenom
                $args[3],   // sexe
                $args[4],   // dateNaissance (format Y-m-d)
                $args[5],   // adresse
                $args[6],   // idVille
                $args[7],   // telephone
                $args[8],   // mail
                $args[9],   // temps
                $args[10],  // login
                $args[11],  // motDePasse
                $args[12],  // statut
                $args[13],  // derniereVisite (format Y-m-d)
                $args[14],  // csp
                $args[15],  // equipement
                $args[16],  // utilisation
                $args[17],  // connaissance
                $args[18],  // info
                $args[19],  // tarif
                $args[20],  // dateRenouvellement (format Y-m-d)
                $args[21],  // idEpn
                $args[22]   // newsletter
                ); 
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
                
                $this->_id                  = $row["id_user"];
                $this->_dateInscription     = $row["date_insc_user"];
                $this->_nom                 = $row["nom_user"];
                $this->_prenom              = $row["prenom_user"];
                $this->_sexe                = $row["sexe_user"];
                $this->_dateNaissance       = date_create_from_format(
                                                "Y-m-d",
                                                $row["annee_naissance_user"]
                                                . "-" . $row["mois_naissance_user"]
                                                . "-" . $row["jour_naissance_user"]
                                                );
                $this->_adresse             = $row["adresse_user"];
                $this->_idVille             = $row["ville_user"];
                $this->_telephone           = $row["tel_user"];
                $this->_mail                = $row["mail_user"];
                $this->_temps               = $row["temps_user"];
                $this->_login               = $row["login_user"];
                $this->_motDePasse          = $row["pass_user"];
                $this->_statut              = $row["status_user"];
                $this->_derniereVisite      = $row["lastvisit_user"];
                $this->_csp                 = $row["csp_user"];
                $this->_equipement          = $row["equipement_user"];
                $this->_utilisation         = $row["utilisation_user"];
                $this->_connaissance        = $row["connaissance_user"];
                $this->_info                = $row["info_user"];
                $this->_tarif               = $row["tarif_user"];
                $this->_dateRenouvellement  = $row["dateRen_user"];
                $this->_idEpn               = $row["epn_user"];
                $this->_newsletter          = $row["newsletter_user"];
            }
            mysqli_free_result($result);
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
                
                $this->_id                  = $row["id_user"];
                $this->_dateInscription     = $row["date_insc_user"];
                $this->_nom                 = $row["nom_user"];
                $this->_prenom              = $row["prenom_user"];
                $this->_sexe                = $row["sexe_user"];
                $this->_dateNaissance       = date_create_from_format(
                                                "Y-m-d",
                                                $row["annee_naissance_user"]
                                                . "-" . $row["mois_naissance_user"]
                                                . "-" . $row["jour_naissance_user"]
                                              );
                $this->_adresse             = $row["adresse_user"];
                $this->_idVille             = $row["ville_user"];
                $this->_telephone           = $row["tel_user"];
                $this->_mail                = $row["mail_user"];
                $this->_temps               = $row["temps_user"];
                $this->_login               = $row["login_user"];
                $this->_motDePasse          = $row["pass_user"];
                $this->_statut              = $row["status_user"];
                $this->_derniereVisite      = $row["lastvisit_user"];
                $this->_csp                 = $row["csp_user"];
                $this->_equipement          = $row["equipement_user"];
                $this->_utilisation         = $row["utilisation_user"];
                $this->_connaissance        = $row["connaissance_user"];
                $this->_info                = $row["info_user"];
                $this->_tarif               = $row["tarif_user"];
                $this->_dateRenouvellement  = $row["dateRen_user"];
                $this->_idEpn               = $row["epn_user"];
                $this->_newsletter          = $row["newsletter_user"];
            }
            mysqli_free_result($result);
        }
    }

    public function __constructArray($array)
    {
        $this->_id                  = $array["id_user"];
        $this->_dateInscription     = $array["date_insc_user"];
        $this->_nom                 = $array["nom_user"];
        $this->_prenom              = $array["prenom_user"];
        $this->_sexe                = $array["sexe_user"];
        $this->_dateNaissance       = date_create_from_format(
                                        "Y-m-d",
                                        $array["annee_naissance_user"]
                                        . "-" . $array["mois_naissance_user"]
                                        . "-" . $array["jour_naissance_user"]
                                      );
        $this->_adresse             = $array["adresse_user"];
        $this->_idVille             = $array["ville_user"];
        $this->_telephone           = $array["tel_user"];
        $this->_mail                = $array["mail_user"];
        $this->_temps               = $array["temps_user"];
        $this->_login               = $array["login_user"];
        $this->_motDePasse          = $array["pass_user"];
        $this->_statut              = $array["status_user"];
        $this->_derniereVisite      = $array["lastvisit_user"];
        $this->_csp                 = $array["csp_user"];
        $this->_equipement          = $array["equipement_user"];
        $this->_utilisation         = $array["utilisation_user"];
        $this->_connaissance        = $array["connaissance_user"];
        $this->_info                = $array["info_user"];
        $this->_tarif               = $array["tarif_user"];
        $this->_dateRenouvellement  = $array["dateRen_user"];
        $this->_idEpn               = $array["epn_user"];
        $this->_newsletter          = $array["newsletter_user"];
    }
    
    public function __constructUtilisateur(
                            $dateInscription,
                            $nom,
                            $prenom,
                            $sexe,
                            $dateNaissance,
                            $adresse,
                            $idVille,
                            $telephone,
                            $mail,
                            $temps,
                            $login,
                            $motDePasse,
                            $statut,
                            $derniereVisite,
                            $csp,
                            $equipement,
                            $utilisation,
                            $connaissance,
                            $info,
                            $tarif,
                            $dateRenouvellement,
                            $idEpn,
                            $newsletter
                        )
    {

        if (date_create_from_format('Y-m-d', $dateInscription) !== FALSE 
            && nom != ""
            && $prenom != ""
            && ($sexe == "H" || $sexe == "F")
            && date_create_from_format('Y-m-d', $dateNaissance) !== FALSE
            && $adresse != ""
            && (is_int($idVille) && $idVille != 0)
            && filter_var($mail, FILTER_VALIDATE_EMAIL)
            && $login != ""
            && $motDePasse != ""
            && (is_int($statut) && $statut > 0 && $statut < 5)
            && (is_int($idEpn) && $idEpn > 0)
        ) {
            // vérification des champs ok
            $db = Mysql::opendb();
            
            $dateInscription    = mysqli_real_escape_string($db, $dateInscription);
            $nom                = mysqli_real_escape_string($db, $nom);
            $prenom             = mysqli_real_escape_string($db, $prenom);
            $sexe               = mysqli_real_escape_string($db, $sexe);
            // $dateNaissance      = mysqli_real_escape_string($db, $dateNaissance);
            $dateNaissance      = date_create($dateNaissance);
            $jourNaissance      = date_format($dateNaissance,'d');
            $moisNaissance      = date_format($dateNaissance,'m');
            $anneeNaissance     = date_format($dateNaissance,'Y');
            $adresse            = mysqli_real_escape_string($db, $adresse);
            $idVille            = mysqli_real_escape_string($db, $idVille);
            $telephone          = mysqli_real_escape_string($db, $telephone);
            $mail               = mysqli_real_escape_string($db, $mail);
            $temps              = mysqli_real_escape_string($db, $temps);
            $login              = mysqli_real_escape_string($db, $login);
            $motDePasse         = mysqli_real_escape_string($db, $motDePasse);
            $statut             = mysqli_real_escape_string($db, $statut);
            $derniereVisite     = mysqli_real_escape_string($db, $derniereVisite);
            $csp                = mysqli_real_escape_string($db, $csp);
            $equipement         = mysqli_real_escape_string($db, $equipement);
            $utilisation        = mysqli_real_escape_string($db, $utilisation);
            $connaissance       = mysqli_real_escape_string($db, $connaissance);
            $info               = mysqli_real_escape_string($db, $info);
            $tarif              = mysqli_real_escape_string($db, $tarif);
            $dateRenouvellement = mysqli_real_escape_string($db, $dateRenouvellement);
            $idEpn              = mysqli_real_escape_string($db, $idEpn);
            $newsletter         = mysqli_real_escape_string($db, $newsletter);
            
            
            // vérification de l'unicité du login
            $sql = "select id_user from tab_user where login_user = " . $login;
            $result = mysqli_query($db,$sql);
            if ($result && mysqli_num_rows($result) == 0) {
                // ok, pas de login déjà existant
                
                // devrait on vérifier la présence du même nom, même prénom, même adresse ?
                // je crois que c'est le rôle de l'admin, pas du programme...

                $sql = "INSERT INTO `tab_user`( `id_user`, `date_insc_user`,  `nom_user`,   `prenom_user`,   `sexe_user`,   `jour_naissance_user`,  `mois_naissance_user`,  `annee_naissance_user`,  `adresse_user`,   `ville_user`,     `tel_user`,         `mail_user`,   `temps_user`,   `login_user`,   `pass_user`,              `status_user`,   `lastvisit_user`,        `csp_user`,   `equipement_user`,   `utilisation_user`,   `connaissance_user`,   `info_user`,   `tarif_user`,   `dateRen_user`,              `epn_user`,   `newsletter_user`) 
                                       VALUES ( '', " . $dateInscription . ", " . $nom . ", " . $prenom . ", " . $sexe . ", " . $jourNaissance . ", " . $moisNaissance . ", " . $anneeNaissance . ", " . $adresse . ", " . $idVille . ", " . $telephone . ", " . $mail . ", " . $temps . ", " . $login . ", " . md5($motDePasse) . ", " . $statut . ", " . $derniereVisite . ", " . $csp . ", " . $equipement . ", " . $utilisation . ", " . $connaissance . ", " . $info . ", " . $tarif . ", " . $dateRenouvellement . ", " . $idEpn . ", " . $newsletter . "') ";
                $result2 = mysqli_query($db,$sql);
 
                if ($result2) {
                    $this->_id                  = mysqli_insert_id($db);
                    $this->_dateInscription     = $dateInscription;
                    $this->_nom                 = $nom;
                    $this->_prenom              = $prenom;
                    $this->_sexe                = $sexe;
                    $this->_dateNaissance       = $dateNaissance;
                    $this->_adresse             = $adresse;
                    $this->_idVille             = $idVille;
                    $this->_telephone           = $telephone;
                    $this->_mail                = $mail;
                    $this->_temps               = $temps;
                    $this->_login               = $login;
                    $this->_motDePasse          = $motDePasse;
                    $this->_statut              = $statut;
                    $this->_derniereVisite      = $derniereVisite;
                    $this->_csp                 = $csp;
                    $this->_equipement          = $equipement;
                    $this->_utilisation         = $utilisation;
                    $this->_connaissance        = $connaissance;
                    $this->_info                = $info;
                    $this->_tarif               = $tarif;
                    $this->_dateRenouvellement  = $dateRenouvellement;
                    $this->_idEpn               = $idEpn;
                    $this->_newsletter          = $newsletter;
                }
                mysqli_free_result($result2);
            }
            mysqli_free_result($result);
            Mysql::closedb($db);
        }
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function getLogin() {
        return $this->_login;
    }
    
    public function getNom() {
        return $this->_nom;
    }
    public function getPrenom() {
        return $this->_prenom;
    }
    
    public function getStatut() {
        return $this->_statut;
    }
    public function getAvatar() {
        $sql = "SELECT `anim_avatar` FROM `rel_user_anim` WHERE `id_animateur`='" . $this->_id . "'";
        $db = Mysql::opendb();
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if($result == FALSE)
        {
            $avatar = "default.png";
        }
        else
        {
            $row = mysqli_fetch_array($result) ;
            $avatar = $row["anim_avatar"];
            if (!isset($avatar) || $avatar == "") {
                $avatar = "default.png";
            }
        }
        mysqli_free_result($result);
        return $avatar;
    }
    
    public function getDateInscription() {
        return $this->_dateInscription;
    }
    
    public function getIdEpn() {
        return $this->_idEpn;
    }
    
    public function MAJVisite() {
        $sql = "UPDATE tab_user SET lastvisit_user='" . date("Y-m-d") . "' WHERE `id_user`=" . $this->_id ;
        $db = Mysql::opendb();
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        mysqli_free_result($result);
    }
    
    public static function getUtilisateursParVille($idVille) {

        $db = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM `tab_user` "
             . "WHERE `ville_user` = " . $idVille . " "
             . "AND `status_user` < 3 "
             . "ORDER BY `nom_user` ASC ";
             
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result == FALSE ) {
            return FALSE ;
        }
        else {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
            return $utilisateurs ;
        }
    }
}