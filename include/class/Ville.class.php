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
        $args    = func_get_args();
        $numArgs = func_num_args();
        
        // valeur par défaut. Doit changer si l'initialisation a réussi,
        // sinon, ça veut dire que la ville n'existe pas.
        $this->_id = 0;    

        if ($numArgs === 1) {
            if (is_int($args[0]) ) {
                $this->__constructId($args[0]);
            }
            if (is_array($args[0]) && count($args[0]) == 4) {
                $this->__constructArray($args[0]);
            }
        }
        
        if ($numArgs === 3) {
            if (is_string($args[0]) && is_string($args[1]) && is_string($args[2]) ) {
                $this->__constructVille($args[0], $args[1], $args[2]);
            }
        }
        
    }
    
    private function __constructId($id)
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
                
                $this->_id         = $row["id_city"];
                $this->_nom        = $row["nom_city"];
                $this->_codePostal = $row["code_postale_city"];
                $this->_pays       = $row["pays_city"];
            }
        }            
    }
    
    private function __constructArray($array)
    {
        $this->_id         = $array["id_city"];
        $this->_nom        = $array["nom_city"];
        $this->_codePostal = $array["code_postale_city"];
        $this->_pays       = $array["pays_city"];
    }
    
    private function __constructVille($nom, $codePostal, $pays)
    {
        $db = Mysql::opendb();
        
        $nom        = mysqli_real_escape_string($db, $nom);
        $codePostal = mysqli_real_escape_string($db, $codePostal);
        $pays       = mysqli_real_escape_string($db, $pays);

        // est ce que la ville existe déjà ?
        $sql = "SELECT * FROM `tab_city` WHERE `nom_city` = '" . $nom . "' AND `code_postale_city` = '" . $codePostal . "' AND `pays_city` = '" . $pays . "'";

        $result = mysqli_query($db,$sql);

        if ($result && mysqli_num_rows($result) == 0) {
            // ok, on n'a pas de ville correspondante
            $sql = "INSERT INTO `tab_city` (`id_city`,`nom_city`, `code_postale_city`, `pays_city`) VALUES ('','" . $nom . "','" . $codePostal . "','" . $pays . "')";
        
            $result = mysqli_query($db,$sql);
        
            if ($result)
            {
                $this->_id         = mysqli_insert_id($db);
                $this->_nom        = $nom;
                $this->_codePostal = $codePostal;
                $this->_pays       = $pays;
            }
        }
        // est ce qu'on devrait créer l'objet si une ville existante est trouvée ??? Pour le moment, non, mais à réfléchir.
        
        Mysql::closedb($db);
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

    public function modifier($nom, $codePostal, $pays) {
        $success = FALSE;
        $db = Mysql::opendb();
        
        $nom_escape        = mysqli_real_escape_string($db, $nom);
        $codePostal_escape = mysqli_real_escape_string($db, $codePostal);
        $pays_escape       = mysqli_real_escape_string($db, $pays);

        $sql = "UPDATE `tab_city` "
            . "SET `nom_city`='" . $nom_escape . "', `code_postale_city`='" . $codePostal_escape . "', `pays_city`='" . $pays_escape . "' "
            . "WHERE `id_city`=" . $this->_id;

        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        if ($result == FALSE ) {
            $success = FALSE;
        }
        else {
            $this->_nom        = $nom;
            $this->_codePostal = $codePostal;
            $this->_pays       = $pays;
            
            $success = TRUE;
        }

        return $success;
    }
    
    public function supprimer() {

        // Verification avant suppression si il n'y a plus d'adherents
        $db     = Mysql::opendb();
        $sql    = "SELECT `id_user` FROM `tab_user` WHERE `ville_user`=" . $this->_id ;
        $result = mysqli_query($db, $sql);


        if ($result == FALSE) {
            return 0; // echec de la requete
        }
        else {
            ;
            if (mysqli_num_rows($result) > 0 ) {
                return 1; // il reste des utilisateurs lies a la ville
            }
            else {
                // Suppression de la ville
                $sql2   = "DELETE FROM `tab_city` WHERE `id_city`=" . $this->_id;
                $result = mysqli_query($db, $sql2);
                if ($result == FALSE ) {
                    return 0;
                }
                else {
                    return 2;
                }
            }
        }
        Mysql::closedb($db);
    }

    public function nbAdherents()
    {                              
     // $ville =addslashes($ville) ;
        $db = Mysql::opendb();

        $sql = "SELECT count(`id_user`) AS nb FROM `tab_user` "
             . "WHERE `ville_user` = '" . $this->_id . "' "
             . "AND `status_user` < 3";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);
        if ($result) {
          $row = mysqli_fetch_array($result)  ;
          return intval($row['nb']);
        } 
        else {
            return 0;
        }
    }


    
    public static function getVilles() {

        $db     = Mysql::opendb();
        $sql    = "SELECT `id_city`,`nom_city`,`code_postale_city`,`pays_city` FROM `tab_city` ORDER BY nom_city" ;
        $result = mysqli_query($db,$sql);
        Mysql::closedb($db);
        
        if ($result == FALSE) {
            return FALSE;
        }
        else {
            $villes = array();
            while($row = mysqli_fetch_assoc($result)) {
                $villes[] = new Ville($row);
            }
            return $villes ;
        }
    }
}