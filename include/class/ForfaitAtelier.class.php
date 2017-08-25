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
require_once("Utilisateur.class.php");
require_once("Transaction.class.php");

class ForfaitAtelier
{
    
    private $_id;
    private $_idUtilisateur;
    private $_idTransaction;
    private $_total;
    private $_depense;
    private $_statut;
   
    public function __construct($array)
    {
        $this->_id            = $array["id_forfait"];
        $this->_idUtilisateur = $array["id_user"];
        $this->_idTransaction = $array["id_transac"];
        $this->_total         = $array["total_atelier"];
        $this->_depense       = $array["depense"];
        $this->_statut        = $array["statut_forfait"];
   
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function getIdUtilisateur() {
        return $this->_idUtilisateur;
    }

    public function getUtilisateur() {
        return Utilisateur::getUtilisateurById($this->_idUtilisateur);
    }

    public function getIdTransaction() {
        return $this->_idTransaction;
    }

    public function getTransaction() {
        return Transaction::getTransactionById($this->_idTransaction);
    }

    public function getTotal() {
        return $this->_total;
    }

    public function getDepense() {
        return $this->_depense;
    }

    public function getStatut() {
        return $this->_statut;
    }

    public function incremente() {
        $success = FALSE;

        $db  = Mysql::opendb();
        $sql = "UPDATE `rel_user_forfait` SET `depense`=`depense`+1 WHERE `id_forfait`=" . $this->_id . " ";

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if ($result) {
            $success = TRUE ;
        }
        return $success;
    }

    public function decremente() {
        $success = FALSE;

        $db  = Mysql::opendb();
        $sql = "UPDATE `rel_user_forfait` SET `depense`=`depense`-1 WHERE `id_forfait`=" . $this->_id . " ";

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if ($result) {
            $success = TRUE ;
        }
        return $success;
    }
    
    public function cloturer() {
        $success = FALSE;

        $db  = Mysql::opendb();
        
        $sql = "UPDATE `rel_user_forfait` SET `depense`=" . $this->_total . ", `statut_forfait`=2 WHERE `id_forfait`=" . $this->_id . " ";
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);

        if ($result) {
            $success = TRUE ;
        }
        return $success;
    }
    
    public function modifier(
                        $idUtilisateur,
                        $idTransaction,
                        $total,
                        $depense,
                        $statut
                    ) {
        $success = FALSE;

        $db = Mysql::opendb();

        if ( $idUtilisateur != "0" ) {
            $db = Mysql::opendb();
            
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $idTransaction = mysqli_real_escape_string($db, $idTransaction);
            $total         = mysqli_real_escape_string($db, $total);
            $depense       = mysqli_real_escape_string($db, $depense);
            $statut        = mysqli_real_escape_string($db, $statut);
      
            $sql = "UPDATE `rel_user_forfait` "
                . "SET `id_user`='" . $idUtilisateur . "', "
                . "`id_transac`='" . $idTransaction . "', "
                . "`total_atelier`='" . $total . "', "
                . "`depense`='" . $depense . "', "
                . "`statut_forfait`='" . $statut . "' "
                . "WHERE `id_forfait` =" . $this->_id . " ";


            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);

            if ($result) {
                $this->_idUtilisateur = $idUtilisateur;
                $this->_idTransaction = $idTransaction;
                $this->_total         = $total;
                $this->_depense       = $depense;
                $this->_statut        = $statut;
                
                $success = TRUE;
            }
        }

        return $success;
    }
    
    public function supprimer() {
        $success = false;
        
        $db = Mysql::opendb();
        // on efface d'abord les relations
        $sql    = "DELETE FROM `rel_user_forfait` WHERE `id_forfait`=" . $this->_id;
        $result = mysqli_query($db,$sql);

        if ($result) {
            $success = true;
        }

        Mysql::closedb($db);
        
        return $success;
    }
    
    public static function creerForfaitAtelier(
                                $idUtilisateur,
                                $idTransaction,
                                $total,
                                $depense,
                                $statut
                            ) {
        $forfaitAtelier = null;

        if ( $idUtilisateur != "0") {
            $db = Mysql::opendb();
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $idTransaction = mysqli_real_escape_string($db, $idTransaction);
            $total         = mysqli_real_escape_string($db, $total);
            $depense       = mysqli_real_escape_string($db, $depense);
            $statut        = mysqli_real_escape_string($db, $statut);
 
            $sql = "INSERT INTO `rel_user_forfait`(`id_user`, `id_transac`, `total_atelier`, `depense`, `statut_forfait`) "
            . "VALUES ('" . $idUtilisateur . "', '" . $idTransaction . "', '" . $total . "', '" . $depense . "', '" . $statut . "' )";

            $result = mysqli_query($db,$sql);
            
            if ($result)
            {
                $forfaitAtelier = new ForfaitAtelier(array(
                    "id_forfait"     => mysqli_insert_id($db),
                    "id_user"        => $idUtilisateur,
                    "id_transac"     => $idTransaction,
                    "total_atelier"  => $total,
                    "depense"        => $depense,
                    "statut_forfait" => $statut
                    ));
            }
            
            Mysql::closedb($db);
        }
        return $forfaitAtelier;
    }
    
    public static function getForfaitAtelierById($id) {
        $forfaitAtelier = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `rel_user_forfait` "
                 . "WHERE `id_forfait` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result) == 1) {
                $forfaitAtelier = new ForfaitAtelier(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }
        
        return $forfaitAtelier;
    }
    
    public static function getForfaitsAteliers() {

        $forfaitsAtelier = null;
    
        $db      = Mysql::opendb();
        $sql     = "SELECT * FROM rel_user_forfait ORDER BY id_forfait ASC";
        $result  = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result) {
            $forfaitsAtelier = array();
            while($row = mysqli_fetch_assoc($result)) {
                $forfaitsAtelier[] = new ForfaitAtelier($row);
            }
            mysqli_free_result($result);
        }
        
        return $forfaitsAtelier;
    }
    
    public static function getForfaitsAtelierByIdUtilisateur($idUtilisateur) {
        $forfaitsAtelier = null;

        if ($idUtilisateur != 0) {
            $db = Mysql::opendb();
            $idUtilisateur = mysqli_real_escape_string($db, $idUtilisateur);
            $sql = "SELECT * "
                 . "FROM `rel_user_forfait` "
                 . "WHERE `id_user` = " . $idUtilisateur . " ";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if ($result && mysqli_num_rows($result)> 0) {
                $forfaitsAtelier = array();
                while($row = mysqli_fetch_assoc($result)) {
                    $forfaitsAtelier[] = new ForfaitAtelier($row);
                }
                mysqli_free_result($result);
            }
        }
        
        return $forfaitsAtelier;
    }
    
}