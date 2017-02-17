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

    error_log("GET : " . print_r($_GET, true));
    error_log("POST : " . print_r($_POST, true));
    
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
                
                // error_log("--- sessionsdate --- " . print_r($sessiondates, true));
                
                //trier le tableau dates
                $arraydate = $sessiondates;
                usort($arraydate, "my_sort"); //de 0 à nbredates !

                // error_log("--- arraydate --- " . print_r($arraydate, true));
                //debug($arraydate);

                $dates = $arraydate[$resultat - 1]; //donner la derniere date comme date reference
                // error_log("--- dates --- " . print_r($dates, true));
        
            }
            else {
                $session = Session::getSessionById($idsession);
                
                $nbre_origin = $session->getNbDates();
                
                // $nbre_origin = getSessionNbreDates($idsession); //nombre initial entré lors de la premiere creation
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
                //debug($sessiondates);
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
                                    
                                    // insertDateSessions($idsession, $arraydate[$i], 0);
                                }
                                header("Location: ./index.php?a=37");
                            }                        
                            // $idsession = addSession($dates, $idTitre, $nbplace, $nbre_dates, $idAnim, $idSalle, $idTarif);    
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
                                    ******/
                                    
                                    // ATTENTION !!! On ne peut pas ré-utiliser une seule stat, vu qu'il y a un seul idSession pour chaque date !
                                    
                                    // $statSession = StatAtelierSession::getStatSessionByIdSession($sessionDates[$i - 1]->getId());
                                    // if ($statSession === null) {
                                        $statSession = StatAtelierSession::creerStatAtelierSession('s', $session->getId(), $sessionDates[$i - 1]->getDate(), $sessionDates[$i - 1]->getNbUtilisateursInscritsOuPresents(), 0, 0, $sessionDates[$i - 1]->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 2, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
                                    // }
                                    // else {
                                        // $statSession->modifier('s', $sessionDates[$i - 1]->getId(), $sessionDates[$i - 1]->getDate(), $sessionSate->getNbUtilisateursInscritsOuPresents(), 0, 0, $sessionSate->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 2, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
                                    // }

                                    
                                }
                                if (isset($_POST["statutdate" . $i]) && $_POST["statutdate" . $i] == "3" ) {
                                    // suppression
                                    $sessionDates[$i - 1]->supprimer();
                                }
                            }
                            
                            // s'il y a des dates supplémentaires
                            for ($i = $nbre_origin + 1 ; $i <= $nbre_dates ; $i++) {
                                $session->addSessionDate($_POST["date" . $i], $_POST["statutdate" . $i]);
                                // TODO : il faut inscrire à cette date les utilisateurs déjà inscrits à la session
                                
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
                                
                                
                                // if ($nbre_origin == $nbre_dates) {  ///en cas de suppression de dates ou modif
                                    // $o = 0;
                                    // //en cas de modification , modifier les nouvelles dates
                                    // for ($i = 1 ; $i <= $nbre_dates ; $i++) {
                                        // if (isset($_POST["statutdate" . $i])) {     
                                
                                            // //recuperer les status envoyés par les selects
                                            // if ($_POST["statutdate" . $i] == "3") {//suppression de la date
                                                // // $sup = deletedatesession($_POST["iddate" . $i]);
                                                // // //cloturer la session si les dates precedentes sont déjà cloturées
                                                // // if ($sup != FALSE) {
                                                    // // $nbrrestant = $nbre_origin - 1;
                                                    // // $nbrvalides = getDatesValidesbysession($idsession);
                                                    // // if ($nbrrestant == $nbrvalides) { 
                                                        // // updateSessionStatut($idsession);
                                                    // // }
                                                // // }
                                                // // //tester s'il y a des inscrits, et supprimer la relation date en cas de resultat positif                            
                                                // // if (FALSE != testrelsessiondate($idsession)) {
                                                    // // deleteRelsessionUser($idsession, $_POST["iddate" . $i]);
                                                // // }
                                                // // $o = $o + 1;
                                            // }
                                            // else {//modification de date 
                                                // modifDateSession($_POST["iddate" . $i], $sessiondates[$i], $_POST["statutdate" . $i]);
                                                // //cloturer la session si les dates precedentes sont déjà cloturées en cas de modif ==2
                                                // if ($_POST["statutdate" . $i] == "2") {//Annulation de la date
                                                    // //en cas d'aucune date en attente, valider la session et l'inscrire 
                                                    // $nbrrestant = $nbre_origin - 1;
                                                    // $nbrvalides = getDatesValidesbysession($idsession);
                                                    // if ($nbrrestant == $nbrvalides) {
                                                        // updateSessionStatut($idsession);
                                                    // }
                                                    // //inserer les stats aussi !!
                                                    // $arrayresult = getInscritpersession($idsession, $_POST["iddate" . $i]);
                                                    // InsertStatAS('s', $idsession, $sessiondates[$i], $arrayresult[0], 0, 0, $arrayresult[1], $nbplace, 2, $idAnim, $_SESSION["idepn"]);
                                                // }
                                    
                                    
                                            // }
                                        // }
                                    // }
                        
                                    //remettre le bon nombre pour $nbre_dates dans tab_session
                                    // if (($nbre_dates - $o) < $nbre_dates) {
                                        // $nbre_dates = $nbre_dates - $o;
                                        // updatenbredates($idsession, $nbre_dates);
                                    // }
                                    // $i = 0;
                                    // header("Location: ./index.php?a=37&mesno=14");
                        
                                    // //en cas d'ajout de date    
                                // }
                                // elseif ($nbre_origin < $nbre_dates) {
                                    // //changer le nombre de dates dans tab_session
                                    // updatenbredates($idsession,$nbre_dates);
                        
                                    // //insérer les nouvelles dates avec le statut s'il ya a lieu
                                    // for ($i = $nbre_origin + 1 ; $i <= $nbre_dates ; $i++) {
                                        // $result = insertDateSessions($idsession, $sessiondates[$i], 0);
                                        // //inserer la relation aussi
                                        // //retrouver la liste des inscrits
                                        // $listeu = getSessionUser($idsession, 0);
                                        // $c      = mysqli_num_rows($listeu);
                                        // $list   = mysqli_fetch_array($listeu);
                                        // if ((FALSE != testrelsessiondate($idsession)) AND FALSE != $result) {
                                            // for ($y = 0 ; $y < $c ; $y++) {
                                                // addUserSession($idsession, $list["id_user"], 0, $result);
                                            // }
                                        // }
                                    // }
                                    // $i = 0;
                                    // header("Location: ./index.php?a=37&mesno=14");
                                // }
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
            
            //supprimer les relations user concernées
            // $result = getSessionUser($idsession,0) ;
            // $nb = mysqli_num_rows($result) ;
            // if ($nb > 0) {
                // for ($i = 0 ; $i <= $nb ; $i++) {
                    // $row = mysqli_fetch_array($result) ;
                    // delUserSession($idsession, $row["id_user"]);
                // }
            // }
            // //supprimer les relations user concernées en liste d'attente aussi !
            // $result2 = getSessionUser($idsession,2) ;
            // $nb2 = mysqli_num_rows($result2) ;
            // if ($nb2>0) {
                // for ($i = 0 ; $i <= $nb2; $i++) {
                    // $row2 = mysqli_fetch_array($result2) ;
                    // delUserSession($idsession,$row2["id_user"]);
                // }
            // }
            header("Location: ./index.php?a=37&mesno=46");
            
        }
        else {
            header("Location: ./index.php?a=37&mesno=0");
        }
    }
?>
