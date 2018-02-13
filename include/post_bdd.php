<?php

/*
  This file is part of CyberGestionnaire.

  CyberGestionnaire is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  CyberGestionnaire is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with CyberGestionnaire; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  2006 Namont Nicolas


  include/post_bdd.php V0.1
 */

//
// addUser()
// ajoute un adherent
function addUser($date, $nom, $prenom, $sexe, $jour, $mois, $annee, $adresse, $ville, $tel, $mail, $temps, $login, $pass, $status, $lastvisit, $csp, $equipement, $utilisation, $connaissance, $info, $tarif, $daterenouv, $epn, $newsletter)
{
    $sql = "INSERT INTO `tab_user`(`id_user`, `date_insc_user`, `nom_user`, `prenom_user`, `sexe_user`, `jour_naissance_user`, `mois_naissance_user`, `annee_naissance_user`, `adresse_user`, `ville_user`, `tel_user`, `mail_user`, `temps_user`, `login_user`, `pass_user`, `status_user`, `lastvisit_user`, `csp_user`, `equipement_user`, `utilisation_user`, `connaissance_user`, `info_user`, `tarif_user`, `dateRen_user`, `epn_user`, `newsletter_user`) 
                VALUES ('', '" . $date . "', '" . $nom . "', '" . $prenom . "', '" . $sexe . "', '" . $jour . "', '" . $mois . "', '" . $annee . "', '" . $adresse . "',  '" . $ville . "', '" . $tel . "', '" . $mail . "', '" . $temps . "', '" . $login . "', '" . passwd($pass) . "', '" . $status . "', '" . $lastvisit . "','" . $csp . "','" . $equipement . "','" . $utilisation . "','" . $connaissance . "','" . $info . "','" . $tarif . "','" . $daterenouv . "','" . $epn . "','" . $newsletter . "') ";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    $iduser = mysqli_insert_id($db);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        return $iduser;
    }
}



function getAllEPN()
{
    $sql = "SELECT `id_espace`, `nom_espace` FROM `tab_espace`  ORDER BY `nom_espace` asc";
    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (false == $result) {
        return false;
    } else {
        $epn = array();
        $nb = mysqli_num_rows($result);
        for ($i = 1; $i <= $nb; $i++) {
            $row = mysqli_fetch_array($result);
            $epn[$row["id_espace"]] = $row["nom_espace"];
        }
        return $epn;
    }
}

// Fichier de post, sauvegarde des données

$act = isset($_GET["act"]) ? $_GET["act"] : '';


switch ($act) {
    // sauvegarde de la base
    case "save":
        $fichier = $_POST["fichier"];
        $chemin = "./sql/";
        $action = $_POST["action"];
        $db = new db;

        switch ($action) {
            case 1:   // affichage de la sauvegarde
                $save = $db->dumpDB();
                break;

            case 2:   // ecriture dans le fichier
                if (false != is_writable($chemin)) {
                    $save = $db->dumpDB();
                    $fp = fopen($chemin . $fichier, "w+");
                    fputs($fp, $save);
                    fclose($fp);
                    header("Location:./telecharger.php?fichier=" . $fichier . "&chemin=" . $chemin . "");
                } else {
                    $mess = 1;
                }
                break;
        }

        break;

    // cette fonction permet de restaurer la bdd usagers cybanim

    case "restore":  //restauration de la base
        //recuperation des variables envoiyées depuis le formulaire
        if (isset($_POST["mdp"])) {
            $type_mdp = $_POST["mdp"];
        } else {
            $type_mdp = 1;
        }

        if (isset($_POST["tarif"])) {
            $tarif = $_POST["tarif"];
        } else {
            $tarif = 1;
        }

        if (isset($_POST["temps_user"]) and $_POST["temps_user"] != "") {
            $temps = $_POST["temps_user"];
        }
        //parametre des entrees de tables numeriques
        // type de csp défini
        $profession = array(
            1 => "Retraité",
            2 => "Employé",
            3 => "Scolaire",
            4 => "Demandeur d''emploi",
            5 => "Mère/Père au foyer",
            6 => "Lycéen",
            7 => "Etudiant",
            8 => "Artisans/Prof. Lib",
            9 => "Instituteurs",
            10 => "Agriculteur",
            11 => "Fonctionnaires",
            12 => "Divers",
            13 => "Collégien",
            14 => "Non renseignée",
            15 => "Professions intermédiaires",
            16 => "Ouvrier",
            17 => "Cadres"
        );


        // type d'utilisation défini
        $utilisationarray = array(
            0 => "Aucun",
            1 => "A la maison",
            2 => "Au bureau ou à l''école",
            3 => "Maison et Bureau/Ecole"
        );
        $connaissancearray = array(
            0 => "Débutant",
            1 => "Intermédiaire",
            2 => "Confirmé"
        );

        if ($_FILES["restore_file"]["name"] != "") {
            //variable de test
            $rowresult = 0;
            $rownonresult = 0;
            $rowactifresult = 0;

            rename($_FILES['restore_file']['tmp_name'], $_FILES['restore_file']['name']);
            $filename = $_FILES['restore_file']['name'];
            ini_set('auto_detect_line_endings', true);
            //debug($filename);
            $arrayimport = array();
            $delimiter = ';';
            //transformerle csv en array multiple pour traiter les données
            $arrayimport = csv_to_array($filename, $delimiter);
            //debug($arrayimport);
            //***TRAITEMENT DES DONNEES*****///

            $nblignesimport = count($arrayimport);

            for ($x = 0; $x < $nblignesimport; $x++) {
                $ligne = $arrayimport[$x];
                $lignerror = $ligne;
                //debug($ligne);
                //$ligne = array_values($ligne); //reassigner les keys
                //assignation des variables pour la table
                $nom = stripslashes($ligne['Nom*']);
                $prenom = stripslashes($ligne['Prénom*']);

                if ($ligne['Statut*'] == "actif") {
                    $status = 1;
                } else {
                    $status = 2;
                }
                if ($ligne['Archivé*'] == "oui") {
                    $status = 6;
                }
                if ($ligne['Login*'] == "") {
                    $loginn = "loginvide";
                } else {
                    $loginn = $ligne['Login*'];
                }

                $passs = "1";

                if ($ligne['Civilité* (M Mme Mlle)'] == "M") {
                    $sexe = "H";
                } else {
                    $sexe = "F";
                }

                //CSP transformé en chiffre
                if (in_array($ligne['CSP'], $profession)) {
                    $csp = array_search($ligne['CSP'], $profession);
                } else {
                    $csp = 14;
                }


                $adresse = stripslashes($ligne['Adresse ligne 1'] . " " . $ligne['Adresse ligne 2'] . " " . $ligne['Code postal'] . "  " . $ligne['Ville']);

                // recupere les villes
                $resultville = getAllCityname();
                $town = $_POST['ville'];
                //VILLE transforme en chiffre
                if (in_array($ligne['Commune de provenance'], $resultville)) {
                    $ville = array_search($ligne['Commune de provenance'], $resultville);
                } else {
                    $ville = $town; //premiere ville par defaut ou bien mettre un champs "autre ville"
                }


                //date de naissance à splitter
                $date_naissance = $ligne['Date de naissance*'];
                $tmp = explode("/", $date_naissance);
                $jour = $tmp[0];
                $mois = $tmp[1];
                $annee = $tmp[2];

                //travail sur le mot de passe
                switch ($type_mdp) {
                    case 1: //nom.prenom
                        $passs = $ligne['Nom*'] . $ligne['prenom*'];
                        break;

                    case 2: //AAAAnom
                        $passs = $annee . $ligne['Nom*'];
                        break;

                    case 3: //AAAAMMDD
                        $passs = $annee . $mois . $jour;
                        break;
                }


                $mail = $ligne['Mail'];
                $tel = $ligne['Téléphone fixe'] . " / " . $ligne['Téléphone portable'];

                $date_inscription = $ligne['inscription'];
                $tempdate = explode("/", $date_inscription);
                $date = $tempdate[2] . "-" . $tempdate[1] . "-" . $tempdate[0];
                $tempdate = "";


                //  les espaces
                $espaces = getAllepn();
                //EPN transformé en chiffre
                if (in_array($ligne['Espace'], $espaces)) {
                    $epn = array_search($ligne['Espace'], $espaces);
                } else {
                    $epn = 1;
                }

                //connaissances
                if (in_array($ligne['Connaissance informatique'], $connaissancearray)) {
                    $connaissance = array_search($ligne['Connaissance informatique'], $connaissancearray);
                } else {
                    $connaissance = 0;
                }


                //utilisation
                if (in_array($ligne['Utilisation informatique'], $utilisationarray)) {
                    $utilisation = array_search($ligne['Utilisation informatique'], $utilisationarray);
                } else {
                    $utilisation = 0;
                }

                if ($ligne['Equipement'] == "Aucun équipement") {
                    $equipement = 0;
                } elseif ($ligne['Equipement'] == "Ordinateur et Internet") {
                    $equipement = "1-5";
                } elseif ($ligne['Equipement'] == "Ordinateur seul") {
                    $equipement = 1;
                }



                $info = stripslashes($ligne['Commentaires Cybanim'] . "\n" . $ligne['Autres informations']);

                $lastvisit0 = $ligne['Date dernière visite (non modifiable)'];
                $tempdate = explode("/", $lastvisit0);
                $lastvisit = $tempdate[2] . "-" . $tempdate[1] . "-" . $tempdate[0];
                $tempdate = "";

                //date de renouvellement adhesion automatiquement crée
                $tempdate = explode("/", $date_inscription);
                if ($status = 1) {
                    $daterenouv = date('Y') . "-" . $tempdate[1] . "-" . $tempdate[0];
                } else {
                    $daterenouv = $tempdate[2] . $tempdate[1] . "-" . $tempdate[0];
                }

                if ($ligne['Inscrit à la liste de diffusion'] == "oui") {
                    $newsletter = 1;
                } else {
                    $newsletter = 0;
                }




                ///*** fin d'assignation des variables**///
                //dialogue avec la base insertion des données
                if (!$nom || !$prenom || !$annee || !$date || !$loginn) {
                    //il manque trop de parametres on n'insert pas et entre une erreur
                    $rownonresult = $rownonresult + 1;
                    //on cree un fichier avec la ligne d'erreur
                    $error = implode(";", $lignerror);
                    gFilelog($error, "log_bdd.txt") . "\r\n";
                } else {
                    //var_dump($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$ville,$tel,$mail,$temps,$loginn,$passs,$status,$lastvisit,$csp,$equipement,$utilisation,$connaissance, $info,$tarif,$daterenouv,$epn,$newsletter);

                    if (false != addUser($date, $nom, $prenom, $sexe, $jour, $mois, $annee, $adresse, $ville, $tel, $mail, $temps, $loginn, $passs, $status, $lastvisit, $csp, $equipement, $utilisation, $connaissance, $info, $tarif, $daterenouv, $epn, $newsletter)) {
                        $rowresult = $rowresult + 1;
                        if ($status == 1) {
                            $rowactifresult = $rowactifresult + 1;
                        }
                    }
                }
            }
        }


        $rowinactifresult = $rowresult - $rowactifresult;

        $_POST["total_usagers"] = $rowresult;
        $_POST["total_echec"] = $rownonresult;
        $_POST["total_actif"] = $rowactifresult;
        $_POST["total_inactif"] = $rowinactifresult;
        break;
}
?>

