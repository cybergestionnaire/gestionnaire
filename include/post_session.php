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

    // error_log("GET : " . print_r($_GET, true));
    // error_log("POST : " . print_r($_POST, true));
    
    require_once("include/class/Session.class.php");
    require_once("include/class/SessionDate.class.php");
    include_once("include/class/StatAtelierSession.class.php");
    
    $idsession = isset($_GET["idsession"]) ? $_GET["idsession"] : '';
    $m         = isset($_GET["m"]) ? $_GET["m"] : '';

    //
    /*
    $statusarray=array(
        0=>"Atelier En cours",
        1=>"En programmation",
        2=>"Atelier Annulé / Annuler",
        3=>"Supprimer"
    );*/


    if (isset($_POST["submit_session"]) && $_POST["submit_session"] != "") {  // si le formulaire est posté
        //recuperation et traitement des variables
       
        $nbplace    = isset($_POST["nbplace"])    ? $_POST["nbplace"] : '';
        $idTitre    = isset($_POST["idTitre"])    ? $_POST["idTitre"] : '';
        $nbre_dates = isset($_POST["nbre_dates"]) ? $_POST["nbre_dates"] : '';
        $idAnim     = isset($_POST["idAnim"])     ? $_POST["idAnim"] : '';
        $idSalle    = isset($_POST["idSalle"])    ? $_POST["idSalle"] : '';
        $idTarif    = isset($_POST["idTarif"])    ? $_POST["idTarif"] : '';
        
        
        
        //////////////////classer par ordre num les dates
        function my_sort($f, $g) {
            if (strtotime($f) == strtotime($g))
                return 0;
            return (strtotime($f) < strtotime($g)) ? -1 : 1;
        }

        
        if ($m != "" AND $m != 3) { // verifier si on envoie la creation ou la modification
            ////Compulser les dates
            if ($m == 1) {
                //verification du nombre de dates....
                for ($i = 1 ; $i <= $nbre_dates ; $i++) {
                    $d = $_POST["date" . $i];
                    if ($d <> '') {
                        $sessiondates[] = $d;
                    }
                }
                
                $resultat = count($sessiondates); // variable pour comparaison nombre
                
                //trier le tableau dates
                $arraydate = $sessiondates;
                usort($arraydate, "my_sort"); //de 0 à nbredates !

                $dates = $arraydate[$resultat - 1]; //donner la derniere date comme date reference
        
            }
            else {
                $session = Session::getSessionById($idsession);
                
                $nbre_origin = $session->getNbDates();
                
                //recompiler les dates mais sans les ranger
                for ($i = 1 ; $i <= $nbre_dates ; $i++) {
                    $d = $_POST["date" . $i];
                    if ($d <> '') {
                        $sessiondates[] = $d;
                    }
                }
                $resultat = count($sessiondates); // variable pour comparaison nombre
                $dates = $sessiondates[1]; //variable la première pour la liste d'affichage
                $arraydate = [];
            }
            
            ///entrer les données dans la base     
            //1 s'il manque des dates
            if ($resultat < $nbre_dates) {
            
                $_SESSION['sauvegarde'] = $_POST;
                header('Location: ./index.php?a=31&m=' . $m . '&idsession=' . $idsession . '&mesno=45');
                exit;   
            }
            else {
         
                if ($idTitre == "" || $nbre_dates == "" || $nbplace == "") {
                    $mess = getError(4) ; //autres champs manquants
                }
                else {
            
                    //Insertion des données
                    switch($m) {
                        case 1:   // ajout planification d'une session
                            $session = Session::creerSession($dates, $idTitre, $nbplace, $nbre_dates, 0, $idAnim, $idSalle, $idTarif);
                        
                            if ($session !== null) {
                                for ($i = 0 ; $i < $nbre_dates ; $i++) {
                                    $session->addSessionDate($arraydate[$i], 0);
                                }
                                header("Location: ./index.php?a=37");
                            }                        
                            else {
                                header("Location: ./index.php?a=37&mesno=0");
                            }
                    
                        break;
                
                        case 2:   // modifie programmation session
                
                            //recuperation des dates pour modification/suppression
                            //$session = Session::getSessionById($idsession);  // théoriquement, $session est déjà initialisé plus haut, mais je veus etre sur...
                            
                            // on commence par traiter les dates pour avoir quel nombre final on doit avoir.
                            $sessionDates = $session->getSessionDates();
                            
                            for ($i = 1 ; $i <= $nbre_origin ; $i++) {
                                
                                if (isset($_POST["statutdate" . $i]) && $_POST["statutdate" . $i] == "0") {
                                    // modification des dates existantes
                                    $sessionDates[$i - 1]->modifier($session->getId(), $_POST["date" . $i], $_POST["statutdate" . $i]);                                    
                                }
                                if (isset($_POST["statutdate" . $i]) && $_POST["statutdate" . $i] == "2") {
                                    // modification des dates existantes
                                    $sessionDates[$i - 1]->modifier($session->getId(), $_POST["date" . $i], $_POST["statutdate" . $i]);
                                    
                                    /******
                                       dans le code d'origine, lors de l'annulation d'une date, on inscrivait les stats
                                       comme si tout le monde était là... Pour mémoire :
                                        // $arrayresult = getInscritpersession($idsession, $_POST["iddate" . $i]);
                                        // InsertStatAS('s', $idsession, $sessiondates[$i], $arrayresult[0], 0, 0, $arrayresult[1], $nbplace, 2, $idAnim, $_SESSION["idepn"]);
                                       sauf que la stat n'était pas enlevée si on remettait la date active !
                                       je reproduis pour le moment ce schéma, en attendant de mieux comprendre les tenants et aboutissants.
                                       
                                       MAJ : j'a traité le problème en n'ajoutant pas systématiquement une stat, mais en regardant d'abord s'il en existe une.
                                       ATTENTION : Si on a 2 sessionDate à la même date (bizarre, mais possible dans le code...), ça ne va pas marcher
                                       (la fonction getStatSessionByIdSessionAndDate attend une ligne unique dans la base)
                                    ******/
                                    
                                    
                                    $statSession = StatAtelierSession::getStatSessionByIdSessionAndDate($session->getId(), $sessionDates[$i - 1]->getDate());
                                    if ($statSession === null) {
                                        $statSession = StatAtelierSession::creerStatAtelierSession('s', $session->getId(), $sessionDates[$i - 1]->getDate(), $sessionDates[$i - 1]->getNbUtilisateursInscritsOuPresents(), 0, 0, $sessionDates[$i - 1]->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 2, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
                                    }
                                    else {
                                        $statSession->modifier('s', $sessionDates[$i - 1]->getId(), $sessionDates[$i - 1]->getDate(), $sessionSate->getNbUtilisateursInscritsOuPresents(), 0, 0, $sessionSate->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 2, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
                                    }

                                    
                                }
                                if (isset($_POST["statutdate" . $i]) && $_POST["statutdate" . $i] == "3" ) {
                                    // suppression
                                    $sessionDates[$i - 1]->supprimer();
                                }
                            }
                            
                            // s'il y a des dates supplémentaires
                            for ($i = $nbre_origin + 1 ; $i <= $nbre_dates ; $i++) {
                                $session->addSessionDate($_POST["date" . $i], $_POST["statutdate" . $i]);
                                // les utilisateurs sont automatiquement ajoutés par la fonction addSessionDate
                                
                            }
                            
                            
                            //on recompte pour mettre en ordre...
                            $datesSession = $session->getSessionDates();
                            $nbre_dates = count($datesSession);
                            
                            // on regarde s'il reste des dates avec status = 0, sinon, la session doit être close
                            $status = 1; // on ferme par défaut, si on trouve une date ouverte, on remet le status d'origine.
                            foreach ($datesSession as $dateSession) {
                                if ($dateSession->getStatut() == 0) {
                                    $status = $session->getStatus();
                                }
                            }
                            
                            if ($session->modifier($dates, $idTitre, $nbplace, $nbre_dates, $status, $idAnim, $idSalle, $idTarif)) {
                                header("Location: ./index.php?a=37&mesno=14");
                            }
                            else {
                                //modification echouée
                                header("Location: ./index.php?a=37&mesno=0");
                            }

                        break;
                    
                    } // switch  
                }   
            }
        }
    }

    // Si le bouton supprimé est posté
    if ($m == 4) {
        $session = Session::getSessionById($idsession);
        if ($session->supprimer()) {
            // les relations sont désormais gérées par la fonction supprimer
            header("Location: ./index.php?a=37&mesno=46");
        }
        else {
            header("Location: ./index.php?a=37&mesno=0");
        }
    }
?>
