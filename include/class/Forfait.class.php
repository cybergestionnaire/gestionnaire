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
require_once("Espace.class.php");
require_once("Config.class.php");

class Forfait
{
    private $_id;
    private $_dateCretation;
    private $_type;
    private $_nom;
    private $_prix;
    private $_critere;
    private $_commentaire;
    private $_dureeValidite;
    private $_uniteValidite;
    private $_tempsForfaitIllimite;
    private $_dateDebut;
    private $_statut;
    private $_dureeConsultation;
    private $_uniteConsultation;
    private $_frequenceConsultation;
    private $_tempsAffectationOccasionnel;
    private $_nombreAtelier;

    public function __construct($array)
    {
        $this->_id = $array["id_forfait"];
        $this->_dateCretation = $array["date_creation_forfait"];
        $this->_type = $array["type_forfait"];
        $this->_nom = $array["nom_forfait"];
        $this->_prix = $array["prix_forfait"];
        $this->_critere = $array["critere_forfait"];
        $this->_commentaire = $array["commentaire_forfait"];
        $this->_dureeValidite = $array["nombre_duree_forfait"];
        $this->_uniteValidite = $array["unite_duree_forfait"];
        $this->_tempsForfaitIllimite = $array["temps_forfait_illimite"];
        $this->_dateDebut = $array["date_debut_forfait"];
        $this->_statut = $array["status_forfait"];
        $this->_dureeConsultation = $array["nombre_temps_affectation"];
        $this->_uniteConsultation = $array["unite_temps_affectation"];
        $this->_frequenceConsultation = $array["frequence_temps_affectation"];
        $this->_tempsAffectationOccasionnel = $array["temps_affectation_occasionnel"];
        $this->_nombreAtelier = $array["nombre_atelier_forfait"];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getDateCretation()
    {
        return $this->_dateCretation;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getNom()
    {
        return $this->_nom;
    }

    public function getPrix()
    {
        return $this->_prix;
    }

    public function getCritere()
    {
        return $this->_critere;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
    }

    public function getDureeValidite()
    {
        return $this->_dureeValidite;
    }

    public function getUniteValidite()
    {
        return $this->_uniteValidite;
    }

    public function getTempsForfaitIllimite()
    {
        return $this->_tempsForfaitIllimite;
    }

    public function getDateDebut()
    {
        return $this->_dateDebut;
    }

    public function getStatut()
    {
        return $this->_statut;
    }

    public function getDureeConsultation()
    {
        return $this->_dureeConsultation;
    }

    public function getUniteConsultation()
    {
        return $this->_uniteConsultation;
    }

    public function getFrequenceConsultation()
    {
        return $this->_frequenceConsultation;
    }

    public function getTempsAffectationOccasionnel()
    {
        return $this->_tempsAffectationOccasionnel;
    }

    public function getNombreAtelier()
    {
        return $this->_nombreAtelier;
    }

    public function attachEspaceById($idEspace)
    {
        $success = false;

        $db = Mysql::opendb();

        $sql = "INSERT INTO `rel_forfait_espace` (`id_forfait`, `id_espace` ) "
                . "VALUES ('" . $this->_id . "', '" . $idEspace . "' ) ";

        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $config = Config::getConfig($idEspace);
            if ($config->activerForfait()) {
                $success = true;
            }
        }

        return $success;
    }

    public function detachAllEspaces()
    {
        $success = false;

        $db = Mysql::opendb();
        $sql = "DELETE FROM `rel_forfait_espace` WHERE `id_forfait`='" . $this->_id . "' ";

        $result = mysqli_query($db, $sql);

        if ($result) {
            $success = true;
        }
        Mysql::closedb($db);

        return $success;
    }

    public function modifier(
    $type,
        $nom,
        $prix,
        $commentaire,
        $dureeValidite,
        $uniteValidite,
        $tempsForfaitIllimite,
        $dureeConsultation,
        $uniteConsultation,
        $frequenceConsultation,
        $tempsAffectationOccasionnel
    ) {
        $success = false;

        $db = Mysql::opendb();

        if ($nom != "" && $prix != "" && $dureeValidite != "" && $dureeConsultation != ""
        ) {
            $db = Mysql::opendb();

            $type = mysqli_real_escape_string($db, $type);
            $nom = mysqli_real_escape_string($db, $nom);
            $prix = mysqli_real_escape_string($db, $prix);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $dureeValidite = mysqli_real_escape_string($db, $dureeValidite);
            $uniteValidite = mysqli_real_escape_string($db, $uniteValidite);
            $tempsForfaitIllimite = mysqli_real_escape_string($db, $tempsForfaitIllimite);
            $dureeConsultation = mysqli_real_escape_string($db, $dureeConsultation);
            $uniteConsultation = mysqli_real_escape_string($db, $uniteConsultation);
            $frequenceConsultation = mysqli_real_escape_string($db, $frequenceConsultation);
            $tempsAffectationOccasionnel = mysqli_real_escape_string($db, $tempsAffectationOccasionnel);

            $sql = "UPDATE `tab_forfait` "
                    . "SET `type_forfait`='" . $type . "', "
                    . "`nom_forfait`='" . $nom . "', "
                    . "`prix_forfait`='" . $prix . "', "
                    . "`commentaire_forfait`='" . $commentaire . "', "
                    . "`nombre_duree_forfait`='" . $dureeValidite . "', "
                    . "`unite_duree_forfait`='" . $uniteValidite . "', "
                    . "`temps_forfait_illimite`='" . $tempsForfaitIllimite . "', "
                    . "`nombre_temps_affectation`='" . $dureeConsultation . "', "
                    . "`unite_temps_affectation`='" . $uniteConsultation . "', "
                    . "`frequence_temps_affectation`='" . $frequenceConsultation . "', "
                    . "`temps_affectation_occasionnel`='" . $tempsAffectationOccasionnel . "' "
                    . "WHERE `id_forfait` =" . $this->_id . " ";


            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result) {
                $this->_nom = $nom;
                $this->_prix = $prix;
                $this->_commentaire = $commentaire;
                $this->dureeValidite = $dureeValidite;
                $this->uniteValidite = $uniteValidite;
                $this->tempsForfaitIllimite = $tempsForfaitIllimite;
                $this->dureeConsultation = $dureeConsultation;
                $this->uniteConsultation = $uniteConsultation;
                $this->frequenceConsultation = $frequenceConsultation;
                $this->tempsAffectationOccasionnel = $tempsAffectationOccasionnel;

                $success = true;
            }
        }

        return $success;
    }

    public function supprimer()
    {
        $success = false;

        $db = Mysql::opendb();
        // on efface d'abord les relations
        $sql = "DELETE FROM `rel_forfait_espace` WHERE `id_forfait`=" . $this->_id;
        $result = mysqli_query($db, $sql);

        if ($result) {
            // si ça marche, on efface le forfait
            $sql2 = "DELETE FROM `tab_forfait` WHERE `id_forfait`=" . $this->_id;
            $result2 = mysqli_query($db, $sql2);

            if ($result2) {
                $success = true;
            }
        }

        Mysql::closedb($db);

        return $success;
    }

    public static function creerForfait(
    $dateCreation,
        $type,
        $nom,
        $prix,
        $critere,
        $commentaire,
        $dureeValidite,
        $uniteValidite,
        $tempsForfaitIllimite,
        $dateDebut,
        $statut,
        $dureeConsultation,
        $uniteConsultation,
        $frequenceConsultation,
        $tempsAffectationOccasionnel,
        $nombreAtelier
    ) {
        $forfait = null;

        if ($nom != "" && $prix != "" && $dureeValidite != "" && $dureeConsultation != ""
        ) {
            $db = Mysql::opendb();
            $dateCreation = date_create_from_format("d/m/Y", $dateCreation)->format("Y-m-d");
            $type = mysqli_real_escape_string($db, $type);
            $nom = mysqli_real_escape_string($db, $nom);
            $prix = mysqli_real_escape_string($db, $prix);
            $critere = mysqli_real_escape_string($db, $critere);
            $commentaire = mysqli_real_escape_string($db, $commentaire);
            $dureeValidite = mysqli_real_escape_string($db, $dureeValidite);
            $uniteValidite = mysqli_real_escape_string($db, $uniteValidite);
            $tempsForfaitIllimite = mysqli_real_escape_string($db, $tempsForfaitIllimite);
            $dateDebut = date_create_from_format("d/m/Y", $dateDebut)->format("Y-m-d");
            $statut = mysqli_real_escape_string($db, $statut);
            $dureeConsultation = mysqli_real_escape_string($db, $dureeConsultation);
            $uniteConsultation = mysqli_real_escape_string($db, $uniteConsultation);
            $frequenceConsultation = mysqli_real_escape_string($db, $frequenceConsultation);
            $tempsAffectationOccasionnel = mysqli_real_escape_string($db, $tempsAffectationOccasionnel);
            $nombreAtelier = mysqli_real_escape_string($db, $nombreAtelier);

            $sql = "INSERT INTO `tab_forfait`(`date_creation_forfait`, `type_forfait`, `nom_forfait`, `prix_forfait`, `critere_forfait`, `commentaire_forfait`, `nombre_duree_forfait`, `unite_duree_forfait`, `temps_forfait_illimite`, `date_debut_forfait`, `status_forfait`, `nombre_temps_affectation`, `unite_temps_affectation`, `frequence_temps_affectation`, `temps_affectation_occasionnel`, `nombre_atelier_forfait`) "
                    . "VALUES ('" . $dateCreation . "', '" . $type . "', '" . $nom . "', '" . $prix . "', '" . $critere . "', '" . $commentaire . "', '" . $dureeValidite . "', '" . $uniteValidite . "', '" . $tempsForfaitIllimite . "', '" . $dateDebut . "', '" . $statut . "', '" . $dureeConsultation . "', '" . $uniteConsultation . "', '" . $frequenceConsultation . "', '" . $tempsAffectationOccasionnel . "', '" . $nombreAtelier . "' )";

            $result = mysqli_query($db, $sql);

            if ($result) {
                $forfait = new Forfait(array(
                    "id_forfait" => mysqli_insert_id($db),
                    "date_creation_forfait" => $dateCreation,
                    "type_forfait" => $type,
                    "nom_forfait" => $nom,
                    "prix_forfait" => $prix,
                    "critere_forfait" => $critere,
                    "commentaire_forfait" => $commentaire,
                    "nombre_duree_forfait" => $dureeValidite,
                    "unite_duree_forfait" => $uniteValidite,
                    "temps_forfait_illimite" => $tempsForfaitIllimite,
                    "date_debut_forfait" => $dateDebut,
                    "status_forfait" => $statut,
                    "nombre_temps_affectation" => $dureeConsultation,
                    "unite_temps_affectation" => $uniteConsultation,
                    "frequence_temps_affectation" => $frequenceConsultation,
                    "temps_affectation_occasionnel" => $tempsAffectationOccasionnel,
                    "nombre_atelier_forfait" => $nombreAtelier
                ));
            }

            Mysql::closedb($db);
        }
        return $forfait;
    }

    public static function getForfaitById($id)
    {
        $forfait = null;

        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                    . "FROM `tab_forfait` "
                    . "WHERE `id_forfait` = " . $id . "";
            $result = mysqli_query($db, $sql);
            Mysql::closedb($db);

            if ($result && mysqli_num_rows($result) == 1) {
                $forfait = new Forfait(mysqli_fetch_assoc($result));
                mysqli_free_result($result);
            }
        }

        return $forfait;
    }

    public static function getForfaits()
    {
        $forfaits = null;

        $db = Mysql::opendb();
        $sql = "SELECT * FROM tab_forfait ORDER BY id_forfait ASC";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $forfaits = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $forfaits[] = new Forfait($row);
            }
            mysqli_free_result($result);
        }

        return $forfaits;
    }

    public function getIdsEspacesAsArray()
    {
        $idsEspaces = null;
        $db = Mysql::opendb();
        $sql = "SELECT `id_espace` FROM `rel_forfait_espace` WHERE `id_forfait`='" . $this->_id . "' ";
        $result = mysqli_query($db, $sql);
        Mysql::closedb($db);

        if ($result) {
            $idsEspaces = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $idsEspaces[] = $row["id_espace"];
            }
            mysqli_free_result($result);
        }

        return $idsEspaces;
    }
}
