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

 2006 Namont Nicolas (CyberGestionnaire)
 
*/
    require_once("include/class/Utilisateur.class.php");
    require_once("include/class/Transaction.class.php");

    $act    =  isset($_GET["act"]) ? $_GET["act"] : '';
    $idUser =  isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    //error_log(print_r($_POST, true));
    //error_log(print_r($_GET, true));
    
    // Choix de la fonction a utiliser
    if ($act != "") {
        //recuperation et traitement des variables
        $date           = isset($_POST["inscription"])  ? $_POST["inscription"] : '';
        $nom            = isset($_POST["nom"])          ? $_POST["nom"] : '';
        $prenom         = isset($_POST["prenom"])       ? $_POST["prenom"] : '';
        $sexe           = isset($_POST["sexe"])         ? $_POST["sexe"] : '';
        // debug($sexe);
        $jour           = isset($_POST["jour"])         ? $_POST["jour"] : '';
        $mois           = isset($_POST["mois"])         ? $_POST["mois"] : '';
        $annee          = isset($_POST["annee"])        ? $_POST["annee"] : '';
        $adresse        = isset($_POST["adresse"])      ? $_POST["adresse"] : '';
        $idVille        = isset($_POST["ville"])        ? $_POST["ville"] : '';
        $tel            = isset($_POST["tel"])          ? $_POST["tel"] : '';
        $mail           = isset($_POST["mail"])         ? $_POST["mail"] : '';
        $temps          = isset($_POST["temps"])        ? $_POST["temps"] : '';
        $loginn         = isset($_POST["login"])        ? $_POST["login"] : '';
        $pass           = isset($_POST["passw"])        ? $_POST["passw"] : '';
        $status         = isset($_POST["status"])       ? $_POST["status"] : '';
        $csp            = isset($_POST["csp"])          ? $_POST["csp"] : '';// ajout de la csp
    
        $utilisation    = isset($_POST["utilisation"])  ? $_POST["utilisation"] : '';
        $connaissance   = isset($_POST["connaissance"]) ? $_POST["connaissance"] : '';
        $info           = isset($_POST["info"])         ? $_POST["info"] : '';
        $tarif          = isset($_POST["tarif"])        ? $_POST["tarif"] : '';
     
        $equipement     = isset($_POST["equipement"])   ? implode("-", $_POST["equipement"]) : '';
    
        $newsletter     = isset($_POST["newsletter"])   ? $_POST["newsletter"] : '';
        if (isset($newsletter)) {
            $newsletter = 1; 
        }
        $idEspace       = isset($_POST["epn"])          ? $_POST["epn"] : '';
     
        //date de renouvellement adhesion automatiquement crée
        $daterenouv = date_create($date);
        date_add($daterenouv, date_interval_create_from_date_string('365 days'));
        $daterenouv = date_format($daterenouv, 'Y-m-d');
    
        $urlRedirect = "./index.php?a=1" ;
                
        // redirige sur la page animateur ou adherent selon l'origine du lien
        /*
        if ($_POST['type']=='anim' OR $_POST['type']=='admin'){
            $urlRedirect = "./index.php?a=23" ;
            
        } else{
            $urlRedirect = "./index.php?a=1" ;
        }
        */
        // suppression d'un user
        if ($act=='del' AND $_SESSION['status'] == 4) {
            $utilisateur = Utilisateur::getutilisateurById($idUser);
            $utilisateur->supprimer();
            //deluser($_GET['iduser']);
            header("Location:" . $urlRedirect . "");
        }
    
        //error_log("avant test"); 
        //error_log("nom = {$nom} / prenom = {$prenom} / annee = {$annee} / mois = {$mois} / adresse = {$adresse} / login = {$loginn} / sexe = {$sexe}");
        // Traitement des champs a insérer
        if ($nom == '' || $prenom == '' || $annee == '' || $adresse == '' || $loginn == '' || $sexe == '') {
            $mess = getError(4);
        }
        else {
            switch($act) {
                case 1:   // ajout d'un adherent
                    //  $urlRedirect = "./index.php?a=1&b=2" ;
                                
                    if (Utilisateur::existsLogin($loginn)) {
                        $mess = getError(5);
                    }
                    else {
                        if (checkDate($mois, $jour, $annee)) {
                            $dateNaissance = $annee . "-" . $mois . "-" . $jour;
                            
                            $utilisateur = Utilisateur::creerUtilisateur(
                                    $date,
                                    $nom,
                                    $prenom,
                                    $sexe,
                                    $dateNaissance,
                                    $adresse,
                                    intval($idVille),
                                    $tel,
                                    $mail,
                                    $temps,
                                    $loginn,
                                    $pass,
                                    intval($status),
                                    date('Y-m-d'),
                                    $csp,
                                    $equipement,
                                    $utilisation,
                                    $connaissance,
                                    $info,
                                    $tarif,
                                    $daterenouv,
                                    intval($idEspace),
                                    $newsletter);
                        
                            if ($utilisateur == null) {
                                header("Location:" . $urlRedirect . "&mesno=0");
                            }
                            else {
                                //enregistrement des transactions choisies
                                //addForfaitUser("temps", $utilisateur->getId(), $temps, 1, date('Y-m-d'), 1); //forfait temps
                                //addForfaitUser("adh", $utilisateur->getId(), $tarif, 1, date('Y-m-d'), 1); //adhésion
                                $transac1 = Transaction::creerTransaction("temps", intval($utilisateur->getId()), $temps, 1, date('Y-m-d'), 1); //forfait temps
                                $transac2 = Transaction::creerTransaction("adh", intval($utilisateur->getId()), $tarif, 1, date('Y-m-d'), 1); //adhésion
    
                                // if ($transac1 == null OR $transac2 == null) {
                                    // error_log("---- erreur lors de la création des transactions ! ----");
                                // }
                                
                                 //ajout de la relation forfait-consultation dans rel_forfait_user
                                if (FALSE == addrelconsultationuser(1, $temps, $utilisateur->getId())) {
                                    error_log("---- erreur lors de la création de la relation ! ----");
                                    header("Location:" . $urlRedirect . "&mesno=0");
                                }
                                else {
                                    header("Location:./index.php?a=1&b=3&mesno=18");
                                }
                            }
                        }
                    }
                break;
              
                case 2:   // modifie un adherent
                                
                    //Modification du status --> archivé, date de l'archivage==lastvisit_user
                    if ($status == 6) {
                        $lastvisit = date('Y-m-d');
                    }
                    else {
                        $lastvisit = '';
                    }
                    
                    $utilisateur = Utilisateur::getutilisateurById($idUser);
                    error_log("date = {$utilisateur->getDateInscription()}");
                    if ($utilisateur->canUpdateLogin($loginn)) {
                        if ($pass == '' ) {
                            $pass = $utilisateur->getMotDePasse();
                        }
                        if ($utilisateur->modifier(
                                    $utilisateur->getDateInscription(),
                                    $nom,
                                    $prenom,
                                    $sexe,
                                    $annee . "-" . $mois . "-" . $jour,
                                    $adresse,
                                    intval($idVille),
                                    $tel,
                                    $mail,
                                    $utilisateur->getIdTarifConsultation(),
                                    $loginn,
                                    $pass,
                                    intval($status),
                                    $lastvisit,
                                    $csp,
                                    $equipement,
                                    $utilisation,
                                    $connaissance,
                                    $info,
                                    $utilisateur->getIdTarifAdhesion(),
                                    $utilisateur->getDateRenouvellement(),
                                    intval($idEspace),
                                    $newsletter) ) {
                            header("Location:".$urlRedirect."&mesno=42");
                        }
                        else {
                            header("Location:".$urlRedirect."&mesno=0");
                        }
                    }
                    else {
                        $mess = getError(5);
                    }
            
                break;
            }
        }
    }
?>
