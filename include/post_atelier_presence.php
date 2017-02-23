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
/*
   Validation des inscriptions aux ateliers pour les statistiques AJOUT 2013
 include/post_atelier_presence.php V0.1
*/
    // error_log("---- _POST ----");
    // error_log(print_r($_POST, true));
    // error_log("---- _GET ----");
    // error_log(print_r($_GET, true));

    require_once("include/class/Atelier.class.php");
    require_once("include/class/StatAtelierSession.class.php");

//$idAtelier  = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '' ;

    if (isset($_POST["valider_presence"])) {  // si le formulaire est posté

        //recuperation des variables
        $idAtelier      = isset($_POST['idatelier']) ? $_POST["idatelier"] : '' ;
        $act            = isset($_GET["act"]) ? $_GET["act"] : 0 ;
    
        $atelier = Atelier::getAtelierById($idAtelier);
        $utilisateursPresents = $atelier->getUtilisateursPresents();
        
       
        // on remet les présents en mode "inscrit", on compte pour avoir le nombre de départ
        // puis on regarde le nouveau tableau des présents pour déterminer le nombre d'absents
        
        foreach ($utilisateursPresents as $utilisateur) {
            $atelier->inscrireUtilisateurInscrit($utilisateur->getId());
            $depense = getForfaitUserEncours($utilisateur->getId());
            DeleteOneFromForfait($depense["id_forfait"], $utilisateur->getId());
        }      
        
        $inscritsAuDepart = $atelier->getNbUtilisateursInscrits();
        
        $utilisateursAtelier = $atelier->getUtilisateursInscritsOuPresents();
        
        switch ($act) {
            //1er validation
            case 0 : 
            
                foreach ($utilisateursAtelier as $utilisateur) {
            
                    if (in_array($utilisateur->getId(), $_POST['present_'])) {
                        // error_log("inscription présent de " . $utilisateur->getNom());
                        $atelier->inscrireUtilisateurPresent($utilisateur->getId());
                        $depense = getForfaitUserEncours($utilisateur->getId());
                        if ($depense["depense"]+1 == $depense["total_atelier"]) {
                            clotureforfaitUser($depense["total_atelier"], $depense["id_forfait"]);
                        }
                        else {
                            updateForfaitdepense($depense["id_forfait"]);
                        }
                    }
        
                }   
        
                $atelier->archiver();
                //entrer les stats
                $absents = $inscritsAuDepart - $atelier->getNbUtilisateursPresents();
                $statAtelier = StatAtelierSession::getStatAtelierByIdAtelier($atelier->getId());
                if ($statAtelier === null) {
                    $statAtelier = StatAtelierSession::creerStatAtelierSession('a', $idAtelier, $atelier->getDate(), $inscritsAuDepart, $atelier->getNbUtilisateursPresents(), $absents, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 1, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                }
                else {
                    $statAtelier->modifier('a', $idAtelier, $atelier->getDate(), $inscritsAuDepart, $atelier->getNbUtilisateursPresents(), $absents, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 1, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                }
                header("Location:index.php?a=13&b=1&idatelier=" . $idAtelier) ; //vers l'atelier pour reactiver epnconnect
            break;
    
    
            //modification depuis les archives
            case 1:
                
                 foreach ($utilisateursAtelier as $utilisateur) {
                   
                    if (in_array($utilisateur->getId(), $_POST['present_'])) {
                        $atelier->inscrireUtilisateurPresent($utilisateur->getId());

                        $depense = getForfaitUserEncours($utilisateur->getId());
                        if($depense["depense"] + 1 == $depense["total_atelier"]) {
                            clotureforfaitUser($depense["total_atelier"], $depense["id_forfait"]);
                        }
                        else {
                            updateForfaitdepense($depense["id_forfait"]);
                        }
                    }
                }
                //modifier dans les stats !
                $absents = $inscritsAuDepart - $atelier->getNbUtilisateursPresents();
                $statAtelier = StatAtelierSession::getStatAtelierByIdAtelier($atelier->getId());
                if ($statAtelier === null) {
                    $statAtelier = StatAtelierSession::creerStatAtelierSession('a', $idAtelier, $atelier->getDate(), $inscritsAuDepart, $atelier->getNbUtilisateursPresents(), $absents, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 1, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                }
                else {
                    $statAtelier->modifier('a', $idAtelier, $atelier->getDate(), $inscritsAuDepart, $atelier->getNbUtilisateursPresents(), $absents, $atelier->getNbUtilisateursEnAttente(), $atelier->getNbPlaces(), $atelier->getSujet()->getIdCategorie(), 1, $atelier->getIdAnimateur(), $atelier->getSalle()->getIdEspace());
                }
                
                header("Location:index.php?a=18&mesno=43"); //vers les archives 
            break;
    
        }
    
    }
?>