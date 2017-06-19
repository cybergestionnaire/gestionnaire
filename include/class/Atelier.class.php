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
require_once("AtelierSujet.class.php");
require_once("Utilisateur.class.php");
require_once("Salle.class.php");
require_once("Tarif.class.php");

class Atelier
{
    
    private $_id;
    private $_date;
    private $_heure;
    private $_duree;
    private $_idAnimateur;
    private $_idSujet;
    private $_nbPlaces;
    private $_public;
    private $_statut;
    private $_idSalle;
    private $_idTarif;
    private $_status; // ??????
    private $_cloturer;

   
    public function __construct($array)
    {
        $this->_id          = $array["id_atelier"];
        $this->_date        = $array["date_atelier"];
        $this->_heure       = $array["heure_atelier"];
        $this->_duree       = $array["duree_atelier"];
        $this->_idAnimateur = $array["anim_atelier"];
        $this->_idSujet     = $array["id_sujet"];
        $this->_nbPlaces    = $array["nbplace_atelier"];
        $this->_public      = $array["public_atelier"];
        $this->_statut      = $array["statut_atelier"];
        $this->_idSalle     = $array["salle_atelier"];
        $this->_idTarif     = $array["tarif_atelier"];
        $this->_status      = $array["status_atelier"];
        $this->_cloturer    = $array["cloturer_atelier"];

    }
    
    public function getId() {
        return $this->_id;
    }

    public function getJour() {
        return $this->_date;
    }

    public function getDate() {
        return $this->_date . " " . $this->_heure;
    }

    public function getHeure() {
        return $this->_heure;
    }
    
    public function getDuree() {
        return $this->_duree;
    }
    
    public function getIdAnimateur() {
        return $this->_idAnimateur;
    }

    public function getAnimateur() {
        return Utilisateur::getUtilisateurById($this->_idAnimateur);
    }
    
    public function getIdSujet() {
        return $this->_idSujet;
    }
    
    public function getSujet() {
        return AtelierSujet::getAtelierSujetById($this->_idSujet);
    }
    public function getNbPlaces() {
        return $this->_nbPlaces;
    }
    
    public function getPublic() {
        return $this->_public;
    }
    
    public function getStatut() {
        return $this->_statut;
    }

    public function archiver() {
        self::setStatut(2);
    }

    public function setStatut($statut) {
        $success = FALSE;

        $db = Mysql::opendb();        
        
        $sql = "UPDATE `tab_atelier` SET `statut_atelier`='" . $statut. "' 
                WHERE `id_atelier`='" . $this->_id. "'";

        $result = mysqli_query($db, $sql);
        if ($result) {
            $this->_statut = $statut;
            $success = TRUE;
        }
        
        return $success;
    }
    
    public function getIdSalle() {
        return $this->_idSalle;
    }
    
    public function getSalle() {
        return Salle::getSalleById($this->_idSalle);
    }
    
    public function getIdTarif() {
        return $this->_idTarif;
    }
    
    public function getTarif() {
        return Tarif::getTarifById($this->_idTarif);
    }
    
    public function getStatus() {
        return $this->_status;
    }
    
    public function setStatus($status) {
        $success = FALSE;

        $db = Mysql::opendb();        
        
        $sql = "UPDATE `tab_atelier` SET `status_atelier`='" . $status. "' 
                WHERE `id_atelier`='" . $this->_id. "'";

        $result = mysqli_query($db, $sql);
        if ($result) {
            $this->_status = $status;
            $success = TRUE;
        }
        
        return $success;
    }
    
    
    public function getCloturer() {
        return $this->_cloturer;
    }
    
    public function setCloturer($cloturer) {
        $success = FALSE;

        $db = Mysql::opendb();        
        
        $sql = "UPDATE `tab_atelier` SET `cloturer_atelier`='" . $cloturer. "' 
                WHERE `id_atelier`='" . $this->_id. "'";

        $result = mysqli_query($db, $sql);
        if ($result) {
            $this->_cloturer = $cloturer;
            $success = TRUE;
        }
        
        return $success;
    }
    
    public function isCloturer() {
        return $this->_cloturer != "0";
    }
    
    public function getNbPlacesPrises () {
        $nbPlaces = 0;
        
        $db = Mysql::opendb();

        $sql = "SELECT `id_rel_atelier_user` FROM `rel_atelier_user`  "
             . "WHERE `id_atelier`=" . $this->_id. " "
             . "AND (`status_rel_atelier_user`= 0 OR `status_rel_atelier_user`= 1)";
             
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $nbPlaces = mysqli_num_rows($result) ;
        }
        
        return $nbPlaces;
    }
    
    public function getNbPlacesRestantes() {
        return $this->_nbPlaces - self::getNbPlacesPrises();
    }
    
    function getUtilisateursInscrits() {
        return Utilisateur::getUtilisateursInscritsAtelier($this->_id);
    }
    
    function getNbUtilisateursInscrits() {
        return count(self::getUtilisateursInscrits());
    }    
    
    function getUtilisateursPresents() {
        return Utilisateur::getUtilisateursPresentsAtelier($this->_id);
    }
    
    function getNbUtilisateursPresents() {
        return count(self::getUtilisateursPresents());
    }  

    function getUtilisateursInscritsOuPresents() {
        return array_merge(Utilisateur::getUtilisateursInscritsAtelier($this->_id), Utilisateur::getUtilisateursPresentsAtelier($this->_id));
    }
    
    function getNbUtilisateursInscritsOuPresents() {
        return count(self::getUtilisateursInscritsOuPresents());
    }    
    
    function getUtilisateursEnAttente() {
        return Utilisateur::getUtilisateursEnAttenteAtelier($this->_id);
    }
    
    function getNbUtilisateursEnAttente() {
        return count(self::getUtilisateursEnAttente());
    }
    
    function inscrireUtilisateurAvecTarif($idUtilisateur, $idTarif) {
        $success = FALSE;
        if (!self::isUtilisateurInscrit($idUtilisateur) AND self::getNbPlacesRestantes() > 0) {

            $db  = Mysql::opendb();
            $sql = "INSERT INTO `rel_atelier_user` ( `id_atelier` , `id_user` , `status_rel_atelier_user` )
             VALUES ('" . $this->_id . "', '" . $idUtilisateur . "', '0');";

            $sql2 = "INSERT INTO `rel_user_forfait`(`id_forfait`, `id_user`, `id_tarif`, `id_atelier`,`id_session`, `statut_forfait`) 
            VALUES('','" . $idUtilisateur . "','" . $idTarif . "','" . $this->_id . "', '0', '0')";

            $db = opendb();
            $result  = mysqli_query($db, $sql);
            $result2 = mysqli_query($db, $sql2);
            Mysql::closedb($db);
            
            if ($result) { // TODO : vérifier que les 2 requetes ont réussies ! Nécéssite sans doute une transaction.

                $success = TRUE;
            }
        }
        
        return $success;
    }
    
    function inscrireUtilisateurInscrit($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '0');
    }

    function inscrireUtilisateurPresent($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '1');
    }

    function inscrireUtilisateurEnAttente($idUtilisateur) {
        return $this->inscrireUtilisateur($idUtilisateur, '2');
    }
    
    function inscrireUtilisateur($idUtilisateur, $statut) {
        $success = FALSE;

        $db  = Mysql::opendb();

        if (!self::isUtilisateurInscrit($idUtilisateur)) {
            $sql = "INSERT INTO `rel_atelier_user` (`id_atelier` , `id_user` , `status_rel_atelier_user` )
                    VALUES ('" . $this->_id . "', '" . $idUtilisateur."', '" . $statut . "')";
        } else {
            $sql = "UPDATE `rel_atelier_user` "
                 . "SET status_rel_atelier_user='" . $statut ."'"
                 . "WHERE `id_user`=" . $idUtilisateur . " AND `id_atelier`=" . $this->_id ;
        }

        $result = mysqli_query($db,$sql);
    
        Mysql::closedb($db);
        
        if ($result) {
            $success = TRUE;
        }
        return $success;
    }
    
    function isUtilisateurInscrit($idUtilisateur) {
        // verifie si le user n'est pas deja inscrit
        $success = FALSE;
        
        $db     = Mysql::opendb();
        $sql    = "SELECT * FROM `rel_atelier_user` WHERE `id_atelier` =" . $this->_id . " AND `id_user` =" . $idUtilisateur ;
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if (mysqli_num_rows($result) == 1) {
            $success = TRUE;
        }
        
        return $success;
    } 
    
    function desinscrireUtilisateur($idUtilisateur) {
        $success = FALSE;
        
        $db      = Mysql::opendb();
        $sql     = "DELETE FROM `rel_atelier_user` WHERE `id_user`=" . $idUtilisateur . " AND `id_atelier`=" . $this->_id ;
        $sql2    = "DELETE FROM `rel_user_forfait` WHERE `id_user`=" . $idUtilisateur . " AND `id_atelier`=" . $this->_id ;
        $result  = mysqli_query($db, $sql);
        $result2 = mysqli_query($db, $sql2);

        Mysql::closedb($db);

        if ($result) { // TODO : vérifier que les 2 requetes ont réussies ! Nécéssite sans doute une transaction.

            $success = TRUE;
        }
                
        return $success;
    }
    
    function MAJStatutUtilisateur($idUtilisateur, $statut) {
        $success = FALSE;
        
        $db  = Mysql::opendb();
        $sql = "UPDATE `rel_atelier_user` 
                SET status_rel_atelier_user=" . $statut . "
                WHERE `id_user`=" . $idUtilisateur . " AND `id_atelier`=" . $this->_id ;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $success = TRUE;
        }
        
        return $success;
    }

    public function getStatutUtilisateur($idUtilisateur) {
        $statut = null;
        
        $db  = Mysql::opendb();
        $sql = "SELECT `status_rel_atelier_user` FROM `rel_atelier_user` WHERE `id_atelier`='" . $this->_id . "' AND `id_user`='" . $idUtilisateur . "' ";
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result and mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $statut = $row["status_rel_atelier_user"];
            mysqli_free_result($result);
        }
        return $statut;
    }
    
    public function modifier($date, $heure, $duree, $idAnimateur, $idSujet, $nbPlaces, $public, $statut, $idSalle, $idTarif, $status, $cloturer) {
        $success = FALSE;
        $db = Mysql::opendb();
     
        $date        = mysqli_real_escape_string($db, $date);
        $heure       = mysqli_real_escape_string($db, $heure);
        $duree       = mysqli_real_escape_string($db, $duree);
        $idAnimateur = mysqli_real_escape_string($db, $idAnimateur);
        $idSujet     = mysqli_real_escape_string($db, $idSujet);
        $nbPlaces    = mysqli_real_escape_string($db, $nbPlaces);
        $public      = mysqli_real_escape_string($db, $public);
        $statut      = mysqli_real_escape_string($db, $statut);
        $idSalle     = mysqli_real_escape_string($db, $idSalle);
        $idTarif     = mysqli_real_escape_string($db, $idTarif);
        $status      = mysqli_real_escape_string($db, $status);
        $cloturer    = mysqli_real_escape_string($db, $cloturer);   

        $sql = "UPDATE `tab_atelier` "
            . "SET `date_atelier` = '" . $date . "', `heure_atelier` = '" . $heure . "', `duree_atelier` = '" . $duree . "', `anim_atelier` = '" . $idAnimateur . "', `id_sujet` = '" . $idSujet . "', `nbplace_atelier` = '" . $nbPlaces . "', `public_atelier` = '" . $public . "', `statut_atelier` = '" . $statut . "', `salle_atelier` = '" . $idSalle . "', `tarif_atelier` = '" . $idTarif . "', `status_atelier` = '" . $status . "', `cloturer_atelier` = '" . $cloturer . "' "
            . "WHERE `id_atelier` = " . $this->_id;


        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result) {
            $this->_date        = $date;
            $this->_heure       = $heure;
            $this->_duree       = $duree;
            $this->_idAnimateur = $idAnimateur;
            $this->_idSujet     = $idSujet;
            $this->_nbPlaces    = $nbPlaces;
            $this->_public      = $public;
            $this->_statut      = $statut;
            $this->_idSalle     = $idSalle;
            $this->_idTarif     = $idTarif;
            $this->_status      = $status;
            $this->_cloturer    = $cloturer;
            
            $success = TRUE;
        }

        return $success;
    }
    
    public function supprimer() {

        $success = false;

        $db     = Mysql::opendb();
        // TODO : utiliser une transaction pour éviter une suppression partielle !
        
        // on supprime d'abord les relations dans rel_atelier_computer, rel_user_forfait et rel_atelier_user
        
        $sql    = "DELETE FROM rel_atelier_computer WHERE id_atelier_rel='" . $this->_id . "'";
        $result = mysqli_query($db, $sql);
        
        if ($result) {
            
            $sql    = "DELETE FROM rel_atelier_user WHERE id_atelier='" . $this->_id . "'";
            $result = mysqli_query($db, $sql);
            
            if ($result) {

                $sql    = "DELETE FROM `rel_user_forfait` WHERE id_atelier`='" . $this->_id . "'";
                $result = mysqli_query($db, $sql);  
                
                if ($result) {
                
                    $sql    = "DELETE FROM `tab_atelier` WHERE `id_atelier`='" . $this->_id . "' ";
                    $result = mysqli_query($db, $sql);  
                    
                    if ($result) {
                        $success = true;
                    }
                }
            }
        }

        Mysql::closedb($db);


        return $success;
    }
   
    
    public static function creerAtelier($date, $heure, $duree, $idAnimateur, $idSujet, $nbPlaces, $public, $statut, $idSalle, $idTarif, $status, $cloturer) {
        $atelier = null;
        
        if ( $date != ""
            && $heure != ""
            && (is_int($idSujet) && $idSujet != 0)
            && (is_int($idAnimateur) && $idAnimateur != 0)
            && (is_int($idSalle) && $idSalle != 0)
            //TODO ! mieux vérifier les données entrées !
        ) {
            $db = Mysql::opendb();
            
            $date        = mysqli_real_escape_string($db, $date);
            $heure       = mysqli_real_escape_string($db, $heure);
            $duree       = mysqli_real_escape_string($db, $duree);
            $idAnimateur = mysqli_real_escape_string($db, $idAnimateur);
            $idSujet     = mysqli_real_escape_string($db, $idSujet);
            $nbPlaces    = mysqli_real_escape_string($db, $nbPlaces);
            $public      = mysqli_real_escape_string($db, $public);
            $statut      = mysqli_real_escape_string($db, $statut);
            $idSalle     = mysqli_real_escape_string($db, $idSalle);
            $idTarif     = mysqli_real_escape_string($db, $idTarif);
            $status      = mysqli_real_escape_string($db, $status);
            $cloturer    = mysqli_real_escape_string($db, $cloturer);  


            $sql = "INSERT INTO `tab_atelier` (`date_atelier`,`heure_atelier`,`duree_atelier`,`anim_atelier`,`id_sujet`,`nbplace_atelier`,`public_atelier`,`statut_atelier`,`salle_atelier`,`tarif_atelier`,`status_atelier`,`cloturer_atelier`) "
                 . "VALUES ('" . $date . "', '" . $heure."', '" . $duree . "', '" . $idAnimateur."', '" . $idSujet . "', '" . $nbPlaces."', '" . $public . "', '" . $statut."', '" . $idSalle . "', '" . $idTarif."', '" . $status . "', '" . $cloturer."') " ;       
            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $atelier = new Atelier(array("id_atelier" => mysqli_insert_id($db), "date_atelier" => $date, "heure_atelier" => $heure, "duree_atelier" => $duree, "duree_atelier" => $duree, "anim_atelier" => $idAnimateur, "id_sujet" => $idSujet, "nbplace_atelier" => $nbPlaces, "public_atelier" => $public, "statut_atelier" => $statut, "salle_atelier" => $idSalle, "tarif_atelier" => $idTarif, "status_atelier" => $status, "cloturer_atelier" => $cloturer));
            }
            
            Mysql::closedb($db);
        }
        return $atelier;
    }
    
    public static function getAtelierById($id) {
        $atelier = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_atelier` "
                 . "WHERE `id_atelier` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $atelier = new Atelier(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $atelier;
    }
    
    public static function getAteliers() {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        $sql      = "SELECT * FROM tab_atelier order by date_atelier, heure_atelier";
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }
    
    public static function getAteliersNonClotures() {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        $sql      = "SELECT * "
                  . "FROM `tab_atelier` "
                  . "WHERE statut_atelier < 2 "
                  . "  AND YEAR(`date_atelier`)=" . date('Y') . " "
                  . "ORDER BY `date_atelier` ASC";
                    
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }   
    
    public static function getAteliersParAnnee($annee) {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        $sql      = "SELECT * "
                  . "FROM `tab_atelier` "
                  . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                  . "ORDER BY `date_atelier` ASC";
                    
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }
    
    public static function getAteliersParAnneeEtParAnimateur($annee, $idAnimateur) {

        $ateliers = null;
    
        $db       = Mysql::opendb();

        $cetteAnnee = date('Y');                    

        if ($annee > $cetteAnnee) {
    
            $sql = "SELECT * "
                 . "FROM `tab_atelier` "
                 . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                 . "  AND anim_atelier=" . $idAnimateur . " "
                 . "ORDER BY `date_atelier` ASC";
    
        } else if ($annee == $cetteAnnee){
    
            $sql = "SELECT * "
                 . "FROM `tab_atelier` "
                 . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                 . "  AND MONTH( `date_atelier` ) >= MONTH( NOW( ))-1 "
                 . "  AND anim_atelier=" . $idAnimateur . " "
                 . "ORDER BY `date_atelier` ASC";

        }

        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }
    
    public static function getAteliersParAnneeEtParEspace($annee, $idEspace) {

        $ateliers = null;
    
        $db       = Mysql::opendb();

        $sql = "SELECT * "
             . "FROM `tab_atelier`, `tab_salle` "
             . "WHERE `salle_atelier`=`id_salle` "
             . "  AND tab_salle.`id_espace`=" . $idEspace . " "
             . "  AND YEAR(`date_atelier`)=" . $annee . " "
             . "  AND `statut_atelier`<2 "
             . "ORDER BY `date_atelier` ASC";

        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }   
    
    public static function getAteliersFutursByAnnee($annee) {
        $ateliers = null;
        
        $db    = Mysql::opendb();
        
        $annee = mysqli_real_escape_string($db, $annee);
        
        $cetteAnnee = date('Y');
    
        if ($annee > $cetteAnnee) {
    
            $sql = "SELECT * "
                 . "FROM `tab_atelier` "
                 . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                 . "ORDER BY `date_atelier` ASC";
    
        } else if ($annee == $cetteAnnee){
    
            $sql = "SELECT * "
                 . "FROM `tab_atelier` "
                 . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                 . "  AND MONTH( `date_atelier` ) >= MONTH( NOW( )) "
                 . "ORDER BY `date_atelier` ASC";

        }   

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);        

        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        return $ateliers;
    }
    
    public static function getAteliersArchivesParAnnee($annee) {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        $sql      = "SELECT * "
                  . "FROM `tab_atelier` "
                  . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                  . "AND statut_atelier = 2 "
                  . "ORDER BY `date_atelier` ASC";
                    
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }
    
    public static function getAteliersArchivesParAnneeEtParAnimateur($annee, $idAnimateur) {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        $sql      = "SELECT * "
                  . "FROM `tab_atelier` "
                  . "WHERE YEAR(`date_atelier`)=" . $annee . " "
                  . "AND `anim_atelier` =" . $idAnimateur . " "
                  . "AND statut_atelier = 2 "
                  . "ORDER BY `date_atelier` ASC";
                    
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $ateliers;
    
    }
    
    public static function getAteliersParSemaine($jour, $idEspace) {

        $ateliers = null;
    
        $db       = Mysql::opendb();
        if ($idEspace == 0) {
            //page utilisateur liste de tous les ateliers/sessions du reseau
            $sql = "SELECT tab_atelier.* "
                 . "FROM tab_atelier "
                 . "WHERE WEEK(`date_atelier`) = WEEK('" . $jour . "') "
                 . "  AND statut_atelier=0 "
                 . "ORDER BY date_atelier ASC";
        } else {
            //adapter donne les ID et le type d'atelier
            $sql = "SELECT  tab_atelier.* "
                 . "FROM  `tab_atelier` , tab_salle "
                 . "WHERE WEEK(`date_atelier`) = WEEK('".$jour."') "
                 . "  AND statut_atelier=0 "
                 . "  AND tab_salle.`id_espace` =" . $idEspace . " "
                 . "  AND tab_atelier.`salle_atelier` = tab_salle.id_salle "
                 . "ORDER BY date_atelier ASC ";
        }

                    
        $result   = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        return $ateliers;
    }
    
    public static function getAteliersParUtilisateurEtParStatut($idUtilisateur, $statut) {
        $ateliers = null;
    
        $db  = Mysql::opendb();
        $sql = "SELECT tab_atelier.* "
             . "FROM tab_atelier, `rel_atelier_user` "
             . "WHERE `rel_atelier_user`.`status_rel_atelier_user`=" . $statut . " "
             . "  AND rel_atelier_user.id_atelier = tab_atelier.id_atelier "
             . "  AND `id_user`=" . $idUtilisateur;

                    
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $ateliers = array();
            while($row = mysqli_fetch_assoc($result)) {
                $ateliers[] = new Atelier($row);
            }
            mysqli_free_result($result);
        }
        return $ateliers;
    }
    
    
}