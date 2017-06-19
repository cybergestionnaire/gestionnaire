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
Fichier POST du formulaire de validation des présences aux sessions
Ajout et modifications 2015

//

$statusarray=array(
    0=>"Atelier En cours",
    1=>"En programmation",
    2=>"Atelier Annulé / Annuler",
    3=>"Supprimer"
);
*/
    require_once("include/class/SessionDate.class.php");
    require_once("include/class/StatAtelierSession.class.php");
    
    error_log('in post_session_presence.php');
    error_log("---- POST ----");
	error_log(print_r($_POST, true));
	error_log("---- GET  ----");
	error_log(print_r($_GET, true));

    $act = isset($_GET["act"]) ? $_GET["act"] : '';

    if(isset($_POST['present_'])) {

        //retrouver les valeurs à insérer
        $idsession   = isset($_POST['idsession']) ? $_POST['idsession'] : '';
        $iddate      = isset($_POST["dateid"]) ? $_POST['dateid'] : '';
        $sessionDate = SessionDate::getSessionDateById($iddate);
        $session     = $sessionDate->getSession();
        
               
        // on remet tout le monde en inscrit
        foreach ($sessionDate->getUtilisateursPresents() as $utilisateur) {
            $sessionDate->inscrireUtilisateurInscrit($utilisateur->getId());
            $depense = getForfaitUserEncours($utilisateur->getId());
            if ($depense != FALSE) {
                DeleteOneFromForfait($depense["id_forfait"], $utilisateur->getId());
            }
        }
        
        $nbInscritsDepart = $sessionDate->getNbUtilisateursInscrits();
        
        foreach ($_POST['present_'] as $present) {
            $sessionDate->inscrireUtilisateurPresent($present);
            $depense = getForfaitUserEncours($present);
            if ($depense != FALSE) {
                if ($depense["depense"] + 1 == $depense["total_atelier"]) {
                    clotureforfaitUser($depense["total_atelier"], $depense["id_forfait"]);
                } else {
                    updateForfaitdepense($depense["id_forfait"]);
                }
            }
        }
        
        
        //modifier le statut de la date de la session
        $sessionDate->cloturer();
        //inscription dans la stats
        $nbAbsents = $nbInscritsDepart - $sessionDate->getNbutilisateursPresents();
        
        $statSessionDate = StatAtelierSession::getStatSessionByIdSessionAndDate($session->getId(), $sessionDate->getDate());
        if ($statSessionDate === null) {
            $statSessionDate = StatAtelierSession::creerStatAtelierSession('s', $session->getId(), $sessionDate->getDate(), $nbInscritsDepart, $sessionDate->getNbUtilisateursPresents(), $nbAbsents, $sessionDate->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 1, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
        } else {
            $statSessionDate->modifier('s', $session->getId(), $sessionDate->getDate(), $nbInscritsDepart, $sessionDate->getNbUtilisateursPresents(), $nbAbsents, $sessionDate->getNbUtilisateursEnAttente(), $session->getNbPlaces(), $session->getSessionSujet()->getIdCategorie(), 1, $session->getIdAnimateur(), $session->getSalle()->getIdEspace());
        }
    
        //en cas de session cloturée toutes dates finies changer son statut --> archives !
        if (!$session->hasSessionDatesNonValidees()) {
            $session->cloturer();
        }

        if ($act == 0) { //depuis le formulaire, la première fois
           //redirection
            header("Location:index.php?a=30&b=1&idsession=" . $idsession) ;
        
        } else {
            header("Location:index.php?a=36&mesno=14") ;
        }
        
    }
?>