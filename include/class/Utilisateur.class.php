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
require_once("Forfait.class.php");

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
    private $_idTarif;
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
    private $_idEspace;
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
        $this->_idTarifConsultation = $array["temps_user"];
        $this->_login               = $array["login_user"];
        $this->_motDePasse          = $array["pass_user"];
        $this->_statut              = $array["status_user"];
        $this->_derniereVisite      = $array["lastvisit_user"];
        $this->_csp                 = $array["csp_user"];
        $this->_equipement          = $array["equipement_user"];
        $this->_utilisation         = $array["utilisation_user"];
        $this->_connaissance        = $array["connaissance_user"];
        $this->_info                = $array["info_user"];
        $this->_idTarifAdhesion     = $array["tarif_user"];
        $this->_dateRenouvellement  = $array["dateRen_user"];
        $this->_idEspace            = $array["epn_user"];
        $this->_newsletter          = $array["newsletter_user"];
    }

    /*
     * Accesseurs basiques
     */
    public function getId() {
        return $this->_id;
    }
    
    public function getDateInscription() {
        return $this->_dateInscription;
    }
    
    public function getNom() {
        return $this->_nom;
    }

    public function getPrenom() {
        return $this->_prenom;
    }
    
    public function getSexe() {
        return $this->_sexe;
    }
    
    public function getJourNaissance() {
        return date_format($this->_dateNaissance, 'd');
    }
    
    public function getMoisNaissance() {
        return date_format($this->_dateNaissance, 'n');
    }
    
    public function getAnneeNaissance() {
        return date_format($this->_dateNaissance, 'Y');
    }
    
    public function getAdresse() {
        return $this->_adresse;
    }
    
    public function getIdVille() {
        return $this->_idVille;
    }
    
    public function getTelephone() {
        return $this->_telephone;
    }
    
    public function getMail() {
        return $this->_mail;
    }
    
    public function getIdTarifConsultation() {
        return $this->_idTarifConsultation;
    }
    
    public function getLogin() {
        return $this->_login;
    }

    public function getMotDePasse() {
        return $this->_motDePasse;
    }
    
    public function getStatut() {
        return $this->_statut;
    }

    public function getDerniereVisite() {
        return $this->_derniereVisite;
    }

    public function getCSP() {
        return $this->_csp;
    }

    public function getEquipement() {
        return $this->_equipement;
    }

    public function getUtilisation() {
        return $this->_utilisation;
    }

    public function getConnaissance() {
        return $this->_connaissance;
    }

    public function getInfo() {
        return $this->_info;
    }

    public function getIdTarifAdhesion() {
        return $this->_idTarifAdhesion;
    }

    public function getDateRenouvellement() {
        return $this->_dateRenouvellement;
    }

    public function getidEspace() {
        return $this->_idEspace;
    }

    public function getNewsletter() {
        return $this->_newsletter;
    }
    
    public function getAge() {

        $annee = date_format($this->_dateNaissance, 'Y');
        $mois = date_format($this->_dateNaissance, 'n');
        $jour = date_format($this->_dateNaissance, 'd');

        $today['mois'] = date('n');
        $today['jour'] = date('d');
        $today['annee'] = date('Y');

        $annees = $today['annee'] - $annee;
        if ($today['mois'] <= $mois) {
            if ($mois == $today['mois']) {
                if ($jour > $today['jour'])
                    $annees--;
            }
            else
              $annees--;
        }
        return $annees;
    }

    //public function getSalles() {}

    
    /**
     * Fonction de récupération des avatars.
     * A retravailler pour aller chercher les photos des usagers
     * OU
     * adapter la base de données pour plus de cohérence (mettre l'avatar dans l'enregistrement utilisateur...)
     */
     
    public function getAvatar() {
        $avatar = "";

        $sql = "SELECT `anim_avatar` FROM `rel_user_anim` WHERE `id_animateur`='" . $this->_id . "'";
        $db = Mysql::opendb();
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if($result != FALSE)
        {
            $row = mysqli_fetch_array($result) ;
            $avatar = $row["anim_avatar"];
            mysqli_free_result($result);
        }
        
        if ($avatar == "") {
            if ($this->_sexe = "H") {
                $avatar = "male.png";
            } else {
                $avatar = "female.png";
            }
        }

        return $avatar;
    }

    
    public function MAJVisite() {
        $success = FALSE;
        $db      = Mysql::opendb();
        $sql     = "UPDATE tab_user SET lastvisit_user='" . date("Y-m-d") . "' WHERE `id_user`=" . $this->_id ;
        $result  = mysqli_query($db, $sql);
        if ($result) {
            $success = TRUE;
        }
        Mysql::closedb($db);
        
        return $success;
    }
    
    public function updateMotDePasse($motDePasse) {
        $success = FALSE;
        $db      = Mysql::opendb();

        $sql     = "UPDATE `tab_user` SET `pass_user` ='" . md5($motDePasse) . "' WHERE `id_user` =" . $this->_id . " LIMIT 1 ;";
        $result  = mysqli_query($db, $sql);
        if ($result) {
            $success = TRUE;
        }
        Mysql::closedb($db);
        
        return $success;
    }
    
    
    public function canUpdateLogin($login) {
        $success = false;
        if ($login == $this->_login || !self::existsLogin($login)) {
            //si le login ne change pas OU si le login n'existe pas déjà
            $success = true;
        }
        
        return $success;
    }
    
    public function hasParametresAnim() {
        $success = FALSE;
        
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_useranim` FROM `rel_user_anim` WHERE `id_animateur`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if(mysqli_num_rows($result) == 1) {
            $success = TRUE ;
        }
        
        return $success;
    }
    
    public function getIdEspaceAnim() {
        $idEspaceAnim = '';
        
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_epn` FROM `rel_user_anim` WHERE `id_animateur`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if($result == FALSE)
        {
        } else {
            $row = mysqli_fetch_array($result) ;
            
            $idEspaceAnim = $row["id_epn"];
            mysqli_free_result($result);
        }
        return $idEspaceAnim;
    }
    
    public function getIdSallesAnim() {
        $idEspaceAnim = '';
        
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_salle` FROM `rel_user_anim` WHERE `id_animateur`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if($result == FALSE)
        {
        } else {
            $row = mysqli_fetch_array($result) ;
            
            $idEspaceAnim = $row["id_salle"];
            mysqli_free_result($result);
        }
        return $idEspaceAnim;
    }
    
    function setParametresAnim($idEspace, $salles, $avatar) {
        $success = false;
        
        $db       = Mysql::opendb();
        
        $idEspace = mysqli_real_escape_string($db, $idEspace);
        $salles   = mysqli_real_escape_string($db, $salles);
        $avatar   = mysqli_real_escape_string($db, $avatar);
        
        if ($this->hasParametresAnim()) {
            $sql  = "UPDATE `rel_user_anim` SET `id_epn`='" . $idEspace . "', `id_salle`='" . $salles . "', `anim_avatar`='" . $avatar . "' WHERE id_animateur='" . $this->_id . "' ";
        } else {
            $sql  = "INSERT INTO `rel_user_anim`(`id_animateur`, `id_epn`, `id_salle`, `anim_avatar`) VALUES ('" . $this->_id . "', '" . $idEspace . "', '" . $salles . "', '" . $avatar . "')";
        }
        //mettre l'epn dans la tab user aussi
        $sql2     = "UPDATE tab_user SET epn_user='" . $idEspace . "' WHERE id_user='" . $this->_id . "' ";

        $result = mysqli_query($db, $sql);

        if ($result != FALSE) {
            $result2 = mysqli_query($db, $sql2);
            $success = TRUE ;
        }
        
        Mysql::closedb($db);

        return $success;
    }
    
    
    public function getForfaitConsultation() {

        return Forfait::getForfaitById($this->_idTarifConsultation);

    }

    public function modifier( $dateInscription,
                            $nom,
                            $prenom,
                            $sexe,
                            $dateNaissance,
                            $adresse,
                            $idVille,
                            $telephone,
                            $mail,
                            $idTarifConsultation,
                            $login,
                            $motDePasse,
                            $statut,
                            $derniereVisite,
                            $csp,
                            $equipement,
                            $utilisation,
                            $connaissance,
                            $info,
                            $idTarifAdhesion,
                            $dateRenouvellement,
                            $idEspace,
                            $newsletter
                        )
    {
        $success = FALSE;
        // error_log("debut modif -----------------------------------------------");
        
        // error_log("dateinscription = {$dateInscription} /  test : " . (date_create_from_format('Y-m-d', $dateInscription) !== FALSE));
        // error_log("nom = {$nom}");
        // error_log("prenom = {$prenom}");
        // error_log("sexe = {$sexe}");
        // error_log("dateNaissance = {$dateNaissance} / test : " . (date_create_from_format('Y-m-d', $dateNaissance) !== FALSE));
        // error_log("adresse = {$adresse}");
        // error_log("idVille = {$idVille}");
        // error_log("mail = {$mail}");
        // error_log("login = {$login}");
        // error_log("motDePasse = {$motDePasse}");
        // error_log("statut = {$statut}");
        // error_log("idEspace = {$idEspace}");

        
        if (date_create_from_format('Y-m-d', $dateInscription) !== FALSE 
            && $nom != ""
            && $prenom != ""
            && ($sexe == "H" || $sexe == "F")
            && date_create_from_format('Y-m-d', $dateNaissance) !== FALSE
            && $adresse != ""
            && (is_int($idVille) && $idVille != 0)
            && (filter_var($mail, FILTER_VALIDATE_EMAIL) || $mail == "")
            && $login != ""
            && $motDePasse != ""
            && (is_int($statut) && $statut > 0 && $statut < 5)
            && (is_int($idEspace) && $idEspace > 0)
        ) {
            // error_log("tests ok !");
            // vérification des champs ok
            $db = Mysql::opendb();
            
            $dateInscription    = mysqli_real_escape_string($db, $dateInscription);
            $nom                = mysqli_real_escape_string($db, $nom);
            $prenom             = mysqli_real_escape_string($db, $prenom);
            $sexe               = mysqli_real_escape_string($db, $sexe);
            // $dateNaissance      = mysqli_real_escape_string($db, $dateNaissance);
            $dateNaissance      = date_create($dateNaissance);
            $jourNaissance      = date_format($dateNaissance,'d');
            $moisNaissance      = date_format($dateNaissance,'n');
            $anneeNaissance     = date_format($dateNaissance,'Y');
            $adresse            = mysqli_real_escape_string($db, $adresse);
            $idVille            = mysqli_real_escape_string($db, $idVille);
            $telephone          = mysqli_real_escape_string($db, $telephone);
            $mail               = mysqli_real_escape_string($db, $mail);
            $idTarifConsultation = mysqli_real_escape_string($db, $idTarifConsultation);
            $login              = mysqli_real_escape_string($db, $login);
            $motDePasse         = md5($motDePasse);
            $statut             = mysqli_real_escape_string($db, $statut);
            $derniereVisite     = mysqli_real_escape_string($db, $derniereVisite);
            $csp                = mysqli_real_escape_string($db, $csp);
            $equipement         = mysqli_real_escape_string($db, $equipement);
            $utilisation        = mysqli_real_escape_string($db, $utilisation);
            $connaissance       = mysqli_real_escape_string($db, $connaissance);
            $info               = mysqli_real_escape_string($db, $info);
            $idTarifAdhesion    = mysqli_real_escape_string($db, $idTarifAdhesion);
            $dateRenouvellement = mysqli_real_escape_string($db, $dateRenouvellement);
            $idEspace           = mysqli_real_escape_string($db, $idEspace);
            $newsletter         = mysqli_real_escape_string($db, $newsletter);

            if (self::canUpdateLogin($login)) {
                //si le login ne change pas OU si le login n'existe pas déjà
                $sql = "UPDATE `tab_user` "
                . "SET `date_insc_user`='" . $dateInscription . "', "
                . "`nom_user`='" . $nom . "', "
                . "`prenom_user`='" . $prenom . "', "
                . "`sexe_user`='" . $sexe . "', "
                . "`jour_naissance_user`='" . $jourNaissance . "', "
                . "`mois_naissance_user`='" . $moisNaissance . "', "
                . "`annee_naissance_user`='" . $anneeNaissance . "', "
                . "`adresse_user`='" . $adresse . "', "
                . "`ville_user`='" . $idVille . "', "
                . "`tel_user`='" . $telephone . "', "
                . "`mail_user`='" . $mail . "', "
                . "`temps_user`='" . $idTarifConsultation . "', "
                . "`login_user`='" . $login . "', "
                . "`pass_user`='" . $motDePasse . "', "
                . "`status_user`='" . $statut . "', "
                . "`lastvisit_user`='" . $derniereVisite . "', "
                . "`csp_user`='" . $csp . "', "
                . "`equipement_user`='" . $equipement . "', "
                . "`utilisation_user`='" . $utilisation . "', "
                . "`connaissance_user`='" . $connaissance . "', "
                . "`info_user`='" . $info . "', "
                . "`tarif_user`='" . $idTarifAdhesion . "', "
                . "`dateRen_user`='" . $dateRenouvellement . "', "
                . "`epn_user`='" . $idEspace . "', "
                . "`newsletter_user`='" . $newsletter . "' "
                . "WHERE `id_user` = " . $this->_id . " ";

                error_log("sql = {$sql}");

                $result = mysqli_query($db,$sql);
                Mysql::closedb($db);
                
                
                if ($result) {
                    $this->_dateInscription     = $dateInscription;
                    $this->_nom                 = $nom;
                    $this->_prenom              = $prenom;
                    $this->_sexe                = $sexe;
                    $this->_dateNaissance       = $dateNaissance;
                    $this->_adresse             = $adresse;
                    $this->_idVille             = $idVille;
                    $this->_telephone           = $telephone;
                    $this->_mail                = $mail;
                    $this->_idTarifConsultation = $idTarifConsultation;
                    $this->_login               = $login;
                    $this->_motDePasse          = $motDePasse;
                    $this->_statut              = $statut;
                    $this->_derniereVisite      = $derniereVisite;
                    $this->_csp                 = $csp;
                    $this->_equipement          = $equipement;
                    $this->_utilisation         = $utilisation;
                    $this->_connaissance        = $connaissance;
                    $this->_info                = $info;
                    $this->_idTarifAdhesion     = $idTarifAdhesion;
                    $this->_dateRenouvellement  = $dateRenouvellement;
                    $this->_idEspace            = $idEspace;
                    $this->_newsletter          = $newsletter;
                    
                    $success = TRUE;
                }  
            }       
        }
        return $success;
    }
    
    public function supprimer() {
        $success = false;
        // TODO : supprimer toutes les relations liées à l'utilisateur avant de le supprimer !!!
        // néanmoins, garder les lignes tab_resa pour les statistiques
        
        $db = Mysql::opendb();
        $sql    = "DELETE FROM `tab_user` WHERE `id_user`=" . $this->_id . " LIMIT 1 " ;
        $result = mysqli_query($db,$sql);

        if ($result) {
            $success = true;
        }

        Mysql::closedb($db);
        
        return $success;
    }
    
    /*
     * Fonctions statiques
     */
     
    public static function creerUtilisateur(
                            $dateInscription,   // format Y-m-d
                            $nom,
                            $prenom,
                            $sexe,
                            $dateNaissance,   // format Y-m-d
                            $adresse,
                            $idVille,
                            $telephone,
                            $mail,
                            $idTarifConsultation,
                            $login,
                            $motDePasse,
                            $statut,
                            $derniereVisite,
                            $csp,
                            $equipement,
                            $utilisation,
                            $connaissance,
                            $info,
                            $idTarifAdhesion,
                            $dateRenouvellement,
                            $idEspace,
                            $newsletter
                        )
    {
        $utilisateur = null;
        
//        error_log(print_r(func_get_args(), true));
        
        
        
        if (date_create_from_format('Y-m-d', $dateInscription) !== FALSE 
            && $nom != ""
            && $prenom != ""
            && ($sexe == "H" || $sexe == "F")
            && date_create_from_format('Y-m-d', $dateNaissance) !== FALSE
            && $adresse != ""
            && (is_int($idVille) && $idVille != 0)
            && (filter_var($mail, FILTER_VALIDATE_EMAIL) || $mail == "")
            && $login != ""
            && $motDePasse != ""
            && (is_int($statut) && $statut > 0 && $statut < 5)
            && (is_int($idEspace) && $idEspace > 0)
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
            $moisNaissance      = date_format($dateNaissance,'n');
            $anneeNaissance     = date_format($dateNaissance,'Y');
            $adresse            = mysqli_real_escape_string($db, $adresse);
            $idVille            = mysqli_real_escape_string($db, $idVille);
            $telephone          = mysqli_real_escape_string($db, $telephone);
            $mail               = mysqli_real_escape_string($db, $mail);
            $idTarifConsultation = mysqli_real_escape_string($db, $idTarifConsultation);
            $login              = mysqli_real_escape_string($db, $login);
            $motDePasse         = mysqli_real_escape_string($db, $motDePasse);
            $statut             = mysqli_real_escape_string($db, $statut);
            $derniereVisite     = mysqli_real_escape_string($db, $derniereVisite);
            $csp                = mysqli_real_escape_string($db, $csp);
            $equipement         = mysqli_real_escape_string($db, $equipement);
            $utilisation        = mysqli_real_escape_string($db, $utilisation);
            $connaissance       = mysqli_real_escape_string($db, $connaissance);
            $info               = mysqli_real_escape_string($db, $info);
            $idTarifAdhesion    = mysqli_real_escape_string($db, $idTarifAdhesion);
            $dateRenouvellement = mysqli_real_escape_string($db, $dateRenouvellement);
            $idEspace           = mysqli_real_escape_string($db, $idEspace);
            $newsletter         = mysqli_real_escape_string($db, $newsletter);
            
            
            // vérification de l'unicité du login
            //$sql = "select id_user from tab_user where login_user = " . $login;
            //$result = mysqli_query($db,$sql);
            if (!self::existsLogin($login)) {
                // ok, pas de login déjà existant
                
                // devrait on vérifier la présence du même nom, même prénom, même adresse ?
                // je crois que c'est le rôle de l'admin, pas du programme...

                $sql = "INSERT INTO `tab_user`( `date_insc_user`,  `nom_user`,   `prenom_user`,   `sexe_user`,   `jour_naissance_user`,  `mois_naissance_user`,  `annee_naissance_user`,  `adresse_user`,   `ville_user`,     `tel_user`,         `mail_user`,   `temps_user`,   `login_user`,   `pass_user`,              `status_user`,   `lastvisit_user`,        `csp_user`,   `equipement_user`,   `utilisation_user`,   `connaissance_user`,   `info_user`,   `tarif_user`,   `dateRen_user`,              `epn_user`,   `newsletter_user`) 
                                       VALUES ( '" . $dateInscription . "', '" . $nom . "', '" . $prenom . "', '" . $sexe . "', '" . $jourNaissance . "', '" . $moisNaissance . "', '" . $anneeNaissance . "', '" . $adresse . "', '" . $idVille . "', '" . $telephone . "', '" . $mail . "', '" . $idTarifConsultation . "', '" . $login . "', '" . md5($motDePasse) . "', '" . $statut . "', '" . $derniereVisite . "', '" . $csp . "', '" . $equipement . "', '" . $utilisation . "', '" . $connaissance . "', '" . $info . "', '" . $idTarifAdhesion . "', '" . $dateRenouvellement . "', '" . $idEspace . "', '" . $newsletter . "') ";
                                       

                $result = mysqli_query($db,$sql);
 
                if ($result) {
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
                            "temps_user"            => $idTarifConsultation,
                            "login_user"            => $login,
                            "pass_user"             => $motDePasse,
                            "status_user"           => $statut,
                            "lastvisit_user"        => $derniereVisite,
                            "csp_user"              => $csp,
                            "equipement_user"       => $equipement,
                            "utilisation_user"      => $utilisation,
                            "connaissance_user"     => $connaissance,
                            "info_user"             => $info,
                            "tarif_user"            => $idTarifAdhesion,
                            "dateRen_user"          => $dateRenouvellement,
                            "epn_user"              => $idEspace,
                            "newsletter_user"       => $newsletter
                        )
                    );
                }  
            }
            //mysqli_free_result($result);
            Mysql::closedb($db);
        }
        
        return $utilisateur;
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
        } else {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ;
    }
    
    public static function getAnimateurs() {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM `tab_user` "
             . "WHERE  status_user = 3 "
             . "ORDER BY `nom_user` ASC ";
             
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        return $utilisateurs ;
    }
    
    public static function getAdministrateurs() {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM `tab_user` "
             . "WHERE  status_user = 4 "
             . "ORDER BY `nom_user` ASC ";
             
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ;
    }
    
    public static function getUtilisateursByStatut($statut) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM `tab_user` "
             . "WHERE  status_user = " . $statut. " "
             . "ORDER BY `nom_user` ASC ";
             
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ;
    }
    
    public static function getUtilisateursByDateInsc($nb) {
        $utilisateurs = null;
        
        $db  = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM tab_user "
             . "WHERE status_user < 3 "
             . "ORDER BY `date_insc_user` DESC LIMIT " . $nb . "";
             
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ;
    }

    public static function searchUtilisateurs($exp) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT *
                FROM `tab_user`
                WHERE  `status_user`< 3 
                AND ( `nom_user` LIKE '%" . $exp . "%'
                OR `prenom_user` LIKE '%" . $exp . "%'
                OR `login_user` LIKE '%" . $exp . "%' )
                ORDER BY `status_user` ASC, `nom_user` ASC";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ;
    }
    
    
    public static function existsLogin($login) {
        $exists = FALSE;
        $db = Mysql::opendb();
        
        $login = mysqli_real_escape_string($db, $login);
        $sql = "SELECT `id_user` FROM tab_user WHERE `login_user`='" . $login . "'" ;

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        
        $nb = mysqli_num_rows($result);
        if ($nb > 0) {
            $exists = TRUE;
        }
        
        return $exists;
    }
    
    //  en lien avec la classe Atelier
    public static function getUtilisateursInscritsAtelier($idAtelier) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_atelier_user
                WHERE rel_atelier_user.id_user = tab_user.id_user
                AND rel_atelier_user.id_atelier = " . $idAtelier ."
                AND rel_atelier_user.status_rel_atelier_user = 0";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    public static function getUtilisateursPresentsAtelier($idAtelier) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_atelier_user
                WHERE rel_atelier_user.id_user = tab_user.id_user
                AND rel_atelier_user.id_atelier = " . $idAtelier ."
                AND rel_atelier_user.status_rel_atelier_user = 1";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    public static function getUtilisateursEnAttenteAtelier($idAtelier) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_atelier_user
                WHERE rel_atelier_user.id_user = tab_user.id_user
                AND rel_atelier_user.id_atelier = " . $idAtelier ."
                AND rel_atelier_user.status_rel_atelier_user = 2";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    //  les mêmes pour les sessions
    public static function getUtilisateursInscritsSession($idSession) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT DISTINCT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_session = " . $idSession ."
                AND rel_session_user.status_rel_session = 0";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    public static function getUtilisateursPresentsSession($idSession) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT DISTINCT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_session = " . $idSession ."
                AND rel_session_user.status_rel_session = 1";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }
    
        public static function getUtilisateursInscritsOuPresentsSession($idSession) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT DISTINCT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_session = " . $idSession ."
                AND (rel_session_user.status_rel_session = 0 OR rel_session_user.status_rel_session = 1)";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }
    

    public static function getUtilisateursEnAttenteSession($idSession) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT DISTINCT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_session = " . $idSession ."
                AND rel_session_user.status_rel_session = 2";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }    
    //  encore les mêmes pour les dates de sessions
    public static function getUtilisateursInscritsSessionDate($idSessionDate) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_datesession = " . $idSessionDate ."
                AND rel_session_user.status_rel_session = 0";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    public static function getUtilisateursPresentsSessionDate($idSessionDate) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_datesession = " . $idSessionDate ."
                AND rel_session_user.status_rel_session = 1";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    }

    public static function getUtilisateursEnAttenteSessionDate($idSessionDate) {
        $utilisateurs = null;
        
        $db = Mysql::opendb();

        $sql = "SELECT tab_user.*
                FROM tab_user, rel_session_user
                WHERE rel_session_user.id_user = tab_user.id_user
                AND rel_session_user.id_datesession = " . $idSessionDate ."
                AND rel_session_user.status_rel_session = 2";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $utilisateurs = array();
            while($row = mysqli_fetch_assoc($result)) {
                $utilisateurs[] = new Utilisateur($row);
            }
        }
        
        return $utilisateurs ; 
    } 
    
    
}