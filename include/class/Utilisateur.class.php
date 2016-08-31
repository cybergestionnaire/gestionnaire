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
    
    public function __construct($array)
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

    /*
     * Accesseurs basiques
     */
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
    
    /**
     * Fonction de récupération des avatars.
     * A retravailler pour aller chercher les photos des usagers
     * OU
     * adapter la base de données pour plus de cohérence (mettre l'avatar dans l'enregitrement utlisateur...)
     */
     
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
        $sql    = "UPDATE tab_user SET lastvisit_user='" . date("Y-m-d") . "' WHERE `id_user`=" . $this->_id ;
        $db     = Mysql::opendb();
        $result = mysqli_query($db, $sql);
        if (result) {
            mysqli_free_result($result);
        }
        Mysql::closedb($db);
    }

    /*
     * Fonctions statiques
     */
     
    public static function creerUtilisateur(
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
        $utilisateur = null;
        
        if (date_create_from_format('Y-m-d', $dateInscription) !== FALSE 
            && $nom != ""
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
                    $utilisateur = new Utilisateur(
                        array(
                            "id_user"               => mysqli_insert_id($db),
                            "date_insc_user"        => $dateInscription,
                            "nom_user"              => $nom,
                            "prenom_user"           => $prenom,
                            "sexe_user"             => $sexe,
                            "jour_naissance_user"   => $jourNaissance,
                            "mois_naissance_user"   => $moisNaissance,
                            "annee_naissance_user"  => $anneeNaissance,
                            "adresse_user"          => $adresse,
                            "ville_user"            => $idVille,
                            "tel_user"              => $telephone,
                            "mail_user"             => $mail,
                            "temps_user"            => $temps,
                            "login_user"            => $login,
                            "pass_user"             => $motDePasse,
                            "status_user"           => $statut,
                            "lastvisit_user"        => $derniereVisite,
                            "csp_user"              => $csp,
                            "equipement_user"       => $equipement,
                            "utilisation_user"      => $utilisation,
                            "connaissance_user"     => $connaissance,
                            "info_user"             => $info,
                            "tarif_user"            => $tarif,
                            "dateRen_user"          => $dateRenouvellement,
                            "epn_user"              => $idEpn,
                            "newsletter_user"       => $newsletter
                        )
                    );
                }  
                mysqli_free_result($result2);
            }       
            mysqli_free_result($result);
            Mysql::closedb($db);
        }
    }
    
    public static function getUtilisateurById($id)
    {
        $utilisateur = null;
        
        if ($id != 0) {
            $db  = Mysql::opendb();
            $id  = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_user` "
                 . "WHERE `id_user` = " . $id . "";
            $result = mysqli_query($db,$sql);
            
            if (mysqli_num_rows($result) == 1) {
                $utilisateur = new Utilisateur(mysqli_fetch_assoc($result));
            }

            mysqli_free_result($result);
            Mysql::closedb($db);
        }            
        
        return $utilisateur;
    }
    
    public static function getUtilisateurByLoginPassword($login, $password)
    {
        $utilisateur = null;
        
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
                $utilisateur = new Utilisateur(mysqli_fetch_assoc($result));
            }

            mysqli_free_result($result);
        }
        
        return $utilisateur;
    }
    
    
    public static function getUtilisateursByVille($idVille) {

        $utilisateurs = null;
        
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
        }
        
        return $utilisateurs ;
    }
}