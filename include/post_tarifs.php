<?php
/*
     This file is part of Cybergestionnaire.

    Cybergestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybergestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybergestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas (cybermin)
 

 include/post_epn.php V0.1
*/
require_once("include/class/Tarif.class.php");
require_once("include/class/Forfait.class.php");
require_once("include/class/Config.class.php");


$actarif   = isset($_GET["actarif"])   ? $_GET["actarif"]   : '' ;
$typetarif = isset($_GET["typetarif"]) ? $_GET["typetarif"] : '' ;

$idtarif   = isset($_GET["idtarif"])   ? $_GET["idtarif"]   : '' ;
$espaces   = isset($_POST['espace'])   ? implode('-',$_POST['espace']) : '';

switch ($actarif)
{
    case 1: // creation des tarifs
    
        switch($typetarif) {
            
            case 1: //impressions et divers
                $nomtarif      = isset($_POST["newnomtarif"]) ? $_POST["newnomtarif"] : '';
                $prixtarif     = (isset($_POST["newprixtarif"]) && is_numeric($_POST["newprixtarif"])) ? $_POST["newprixtarif"] : "";
                $comment       = isset($_POST["newdescriptiontarif"]) ? $_POST["newdescriptiontarif"] : '';
                $categoryTarif = isset($_POST["catTarif"]) ? $_POST["catTarif"] : '';
                $duree         = "0"; //0=illimité

                if ($nomtarif == '' || $prixtarif == '') {
                    $mess = getError(4) ; //autres champs manquants
                }
                else {
                    if (Tarif::creerTarif($nomtarif, $prixtarif, $comment, 0, $categoryTarif, $duree, $espaces) == null) {
                        echo getError(0);
                    }
                    else {
                        header("Location:index.php?a=47&catTarif={$categoryTarif}&mesno=32") ;
                    }
                }
                
                break;
             
            case 2: //ateliers
                $nomtarif      = isset($_POST["newnomtarifa"]) ? $_POST["newnomtarifa"] : '';
                $prixtarif     = (isset($_POST["newprixtarifa"]) && is_numeric($_POST["newprixtarifa"])) ? $_POST["newprixtarifa"] : "";
                //$prixtarif=preg_replace("/[^0-9]/","",$prixtarif0); 
                $comment       = isset($_POST["newdescriptiontarifa"]) ? $_POST["newdescriptiontarifa"] : '';
                $categoryTarif = 5;
                $typeduree     = isset($_POST["typedureetarifa"]) ? $_POST["typedureetarifa"] : ''; //0=illimite
                if ($typeduree > 0) {
                    if (isset($_POST["dureetarifa"]) && isset($_POST["typedureetarifa"])) {
                        $duree = $_POST["dureetarifa"] . '-' . $_POST["typedureetarifa"];
                    }
                }
                $nbatelier     = isset($_POST['newnumbertarifa']) ? $_POST['newnumbertarifa'] : '';
                
                if ($nomtarif == '' || $prixtarif == '' || $typeduree == '' || $nbatelier == '') {
                    $mess = getError(4) ; //autres champs manquants
                }
                else {
                 
                    if (Tarif::creerTarif($nomtarif, $prixtarif, $comment, $nbatelier, $categoryTarif, $duree, $espaces) == null) {
                        echo getError(0);
                    }
                    else {
                        header("Location:index.php?a=47&catTarif=5&mesno=32") ;
                    }
                }
            
                break;
            
            case 3: //consult
                $nom_forfait        = isset($_POST["nom_forfait"]) ? $_POST["nom_forfait"] : '';
                $prix_forfait       = isset($_POST["prix_forfait"]) ? $_POST["prix_forfait"] : '' ;
                $critere_forfait    = "";
                $comment_forfait    = isset($_POST["comment_forfait"]) ? $_POST["comment_forfait"] : '' ;
                $date_debut_forfait = date("d/m/Y");
                $date_creat_forfait = date("d/m/Y");
            
                $nombre_atelier_forfait         = 0;
                $status_forfait                 = 1;
                $type_forfait                   = 1; // type affectation normal
                $temps_affectation_occasionnel  = 0;
                $nombre_temps_affectation       = isset($_POST["nombre_temps_affectation"]) ? $_POST["nombre_temps_affectation"] : '' ;
                $unite_temps_affectation        = isset($_POST["unite_temps_affectation"]) ? $_POST["unite_temps_affectation"] : '' ;
                $frequence_temps_affectation    = isset($_POST["frequence_temps_affectation"]) ? $_POST["frequence_temps_affectation"] : '' ;
            
            /*
            //pour une affectation occasionnelle
            if($_POST["temps_affectation_occasionnel"]>0){
                $temps_affectation_occasionnel=$_POST["temps_affectation_occasionnel"];
                $type_forfait = 4; // type affectation occasionnel
                    $nombre_temps_affectation =0;
                    $unite_temps_affectation =0;
                $frequence_temps_affectation= 0;
            
            }else{
                $type_forfait = 1; // type affectation normal
                $temps_affectation_occasionnel=0;
                $nombre_temps_affectation = $_POST["nombre_temps_affectation"];
                $unite_temps_affectation = $_POST["unite_temps_affectation"];
                $frequence_temps_affectation= $_POST["frequence_temps_affectation"];
                
                }
            */
            //validité et duree des tarifs
            //$unite_duree_forfait0= $_POST["unite_duree_forfait"]; //duree illimitée du forfait ou pas
            
            if ( isset($_POST["unite_duree_forfait"]) && $_POST["unite_duree_forfait"] == 4) {
                $temps_forfait_illimite = 1;
                $nombre_duree_forfait   = 0;
                $unite_duree_forfait    = 0;
            }
            else {
                $temps_forfait_illimite = 0;
                $nombre_duree_forfait   = isset($_POST["nombre_duree_forfait"]) ? $_POST["nombre_duree_forfait"] : '' ;
                $unite_duree_forfait    = isset($_POST["unite_duree_forfait"]) ? $_POST["unite_duree_forfait"] : '' ;
            }
                
                        
            $espacesselected = isset($_POST['espace']) ? $_POST["espace"] : '' ;
            
            debug($espacesselected);
            
            // AJOUT DU FORFAIT
            if ($nom_forfait == '' || $nombre_temps_affectation == '') {
                $mess = getError(4) ; //autres champs manquants
            }
            else {
                $forfait = Forfait::creerForfait(
                        $date_creat_forfait,
                        $type_forfait,
                        $nom_forfait,
                        $prix_forfait,
                        $critere_forfait,
                        $comment_forfait,
                        $nombre_duree_forfait,
                        $unite_duree_forfait,
                        $temps_forfait_illimite,
                        $date_debut_forfait,
                        $status_forfait,
                        $nombre_temps_affectation,
                        $unite_temps_affectation,
                        $frequence_temps_affectation,
                        $temps_affectation_occasionnel,
                        $nombre_atelier_forfait
                    );
                        
                if ($forfait == null) {
                    echo getError(0);
                }
                else {
                    $success = true;
                    //inserer la relation pour les epn
                    foreach($espacesselected as $key => $idEspace ){
                        if (!$forfait->attachEspaceById($idEspace) ){
                            $success = false;
                        }
                    }
                    
                    if ($success) {
                        header("Location:index.php?a=47&catTarif=6&mesno=32") ;
                    }
                    else {
                        echo getError(0);
                    }
                }
            }
            break;
        }
    
        break;

    
    case 2: // modification
        $categoryTarif = isset($_POST["catTarif"]) ? $_POST["catTarif"] : '';
    
        if ($categoryTarif < 6) {
            //categories 1 à 5 impression et ateliers
            $nomtarif  = isset($_POST["nomtarif"]) ? $_POST["nomtarif"] : '';
            $prixtarif = isset($_POST["prixtarif"]) ? $_POST["prixtarif"] : '';
            $desctarif = isset($_POST["descriptiontarif"]) ? $_POST["descriptiontarif"] : '';
            $typeduree = isset($_POST["typedureetarif"]) ? $_POST["typedureetarif"] : ''; //0=illimite
            $duree     = '';
            
            if ($typeduree > 0) {
                if (isset($_POST["dureetarif"]) && isset($_POST["typedureetarif"])) {
                    $duree = $_POST["dureetarif"].'-'.$_POST["typedureetarif"];
                }
            }
            $nbatelier = isset($_POST['numberatelier']) ? $_POST['numberatelier'] : '';
            
            if ($nomtarif == '' || $prixtarif == '') {
                $mess= getError(4) ; //champs manquants
            }
            else {
                $tarif = Tarif::getTarifbyId($idtarif);
                if ($tarif->modifier($nomtarif, $prixtarif, $desctarif, $nbatelier, $categoryTarif, $duree, $espaces)) {
                    header("Location:index.php?a=47&catTarif=".$categoryTarif."&mesno=33") ;
                }
                else {
                    echo getError(0);
                }
            }
            
                
        }
        else {
            //categorie 6 : la consultation
            
            /* criteres non modifiés
            $nombre_atelier_forfait = 0;
            $status_forfait=1;
            $critere_forfait= "";
            $date_creat_forfait     = date("d/m/Y");
            $date_debut_forfait = date('d/m/Y');
            */
        
            $idforfait       = isset($_POST["id_forfait"]) ? $_POST["id_forfait"] : '';
            $nom_forfait     = isset($_POST["nom_forfait"]) ? $_POST["nom_forfait"] : '';
            $prix_forfait    = isset($_POST["prix_forfait"]) ? $_POST["prix_forfait"] : '';
            $comment_forfait = isset($_POST["commentaire_forfait"]) ? $_POST["commentaire_forfait"] : '';
                                    
            //pour une affectation occasionnelle
            if (isset($_POST["temps_affectation_occasionnel"]) && $_POST["temps_affectation_occasionnel"] > 0) {
                $temps_affectation_occasionnel  = $_POST["temps_affectation_occasionnel"];
                $type_forfait                   = 4; // type affectation occasionnel
                $nombre_temps_affectation       = 0;
                $unite_temps_affectation        = 0;
                $frequence_temps_affectation    = 0;
            }
            else {
                $type_forfait                   = 1; // type affectation normal
                $temps_affectation_occasionnel  =0;
                $nombre_temps_affectation       = isset($_POST["nombre_temps_affectation"]) ? $_POST["nombre_temps_affectation"] : '';
                $unite_temps_affectation        = isset($_POST["unite_temps_affectation"]) ? $_POST["unite_temps_affectation"] : '';
                $frequence_temps_affectation    = isset($_POST["frequence_temps_affectation"]) ? $_POST["frequence_temps_affectation"] : '';
            }
            
            //validité et duree des tarifs
        //  $unite_duree_forfait0= $_POST["unite_duree_forfait"];
            if (isset($_POST["unite_duree_forfait"]) && $_POST["unite_duree_forfait"] == 4) {
            
                $temps_forfait_illimite = 1;
                $nombre_duree_forfait   = 0;
                $unite_duree_forfait    = 0;
            }
            else {
                $temps_forfait_illimite = 0;
                $nombre_duree_forfait   = isset($_POST["nombre_duree_forfait"]) ? $_POST["nombre_duree_forfait"] : '';
                $unite_duree_forfait    = isset($_POST["unite_duree_forfait"]) ? $_POST["unite_duree_forfait"] : '';
            }

            
            $espacesselected = isset($_POST['espace']) ? $_POST["espace"] : '';
            
            ///Modification des données dans la base
            if ($idforfait == '' || $prix_forfait == '' || $type_forfait == '') {
                $mess = getError(4) ; //champs manquants
            }
            else {
                $success = true;
                
                $forfait = Forfait::getForfaitById($idforfait);
            
                if ($forfait->modifier($type_forfait, $nom_forfait, $prix_forfait, $comment_forfait, $nombre_duree_forfait, $unite_duree_forfait, $temps_forfait_illimite, $nombre_temps_affectation, $unite_temps_affectation, $frequence_temps_affectation, $temps_affectation_occasionnel)){
                    // vider les liaisons espace & tab config
                    
                    if ( $forfait->detachAllEspaces() ) {
                        //inserer la relation pour les epn
                        foreach($espacesselected as $key => $idEspace ){
                            if (!$forfait->attachEspaceById($idEspace) ){
                                $success = false;
                            }
                        }
                        
                        // remise en état de la configuration pour tous les espaces...
                        $espacesAVerifier = Espace::getEspaces();
                        
                        foreach ($espacesAVerifier as $espaceAVerifier) {
                            $espaceAVerifier->getConfig()->updateActivationForfait();
                        }
                    }
                    else {
                        $success = false;
                    }
                }
                else {
                    $success = false;
                }
                
                if ($success) {
                    header("Location:index.php?a=47&catTarif=6&mesno=33") ;
                }
                else {
                    echo getError(0);
                }
            }
        }
        
        break;
  
    case 3: // suppression
    
        if ($typetarif < 3) {
            $tarif = Tarif::getTarifbyId($idtarif);
            if ($tarif->supprimer()) {
                header("Location:index.php?a=47&mesno=34") ;
            }
            else {
                echo getError(0);
            }
        }
        else {
            $forfait = Forfait::getForfaitById($idtarif);
            if ($forfait->supprimer()) {
                // remise en état de la configuration pour tous les espaces...
                $espacesAVerifier = Espace::getEspaces();
                
                foreach ($espacesAVerifier as $espaceAVerifier) {
                    $espaceAVerifier->getConfig()->updateActivationForfait();
                }
                header("Location:index.php?a=47&catTarif=6&mesno=34") ;
            }
            else {
                echo getError(0);
            }
        }

    break; 
}
?>