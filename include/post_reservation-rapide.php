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
 2013 Florence DAUVERGNE
 
*/

    require_once("include/class/Resa.class.php");
    //----
    //----------
    //------------ Validation de la resa rapide -------------------------------------------------------------------///
    $duree    = isset($_POST["duree"])      ? $_POST["duree"] : '';
    $heure    = isset($_POST["heure"])      ? $_POST["heure"] : '';
    $date     = isset($_POST["date"])       ? $_POST["date"] : '';
    $id_user  = isset($_POST["adh_submit"]) ? $_POST["adh_submit"] : '';
    $id_poste = isset($_POST["idcomp"])     ? $_POST["idcomp"] : '';
    $restant  = isset($_POST["restant"])    ? $_POST["restant"] : '';

    if(isset($_POST["pastresa"]) && $_POST["pastresa"] == 1){
        $heurepp = explode(':', $heure);
        $heure   = ($heurepp[0]*60) + $heurepp[1];
    }

    if (isset($_POST['resa_submit'])) {
        
        // choix de l'adherent et poste obligatoire
        if(isset($_POST['adh_submit']) AND isset($_POST['idcomp']))
        {
            //reservation du jour

            //chargement de la duree de la réservation en fonction de l'utilisateur et de son temps restant 
            //if ($duree>$restant){$duree=$restant;} //si le temps restant de l'utilisateur est inférieur à la durée légale.
            //$idresarel = addResa($id_poste, $id_user, $date, $heure, $duree);

            $resa  = Resa::creerResa($id_poste, $id_user, $date, $heure, $duree, date('Y-m-d'), '1');
            
            $messErr = '<div class="col-md-4"><div class="alert alert-success alert-dismissable"><i class="fa fa-check-square"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;&nbsp;&nbsp;</button>Reservation ajoutée</div></div>' ; 
        }
        else
        {
            $messErr = '<div class="col-md-4"><div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;&nbsp;&nbsp;</button>Vous devez s&eacute;lectionner un adh&eacute;rent et un poste</div></div>' ; 
        }

    }

    //----
    //----------
    //------------ Validation des messages -------------------------------------------------------------------///
    $message      = isset($_POST["chattxt_message"])  ? addslashes($_POST["chattxt_message"]) : '';
    $tags         = isset($_POST["tags_message"])     ? $_POST["tags_message"] :'';
    $id_user      = isset($_POST["chatadh"])          ? $_POST["chatadh"] :'';
    $date         = isset($_POST["chatdate"])         ? $_POST["chatdate"] :'';
    $destinataire = isset($_POST["chatdestinataire"]) ? $_POST["chatdestinataire"] :'';
    //debug($message);
    if (isset($_POST['message_submit'])) {
     
        if(isset($_POST['chatadh']) AND isset($_POST['chattxt_message'])) {
            addMessage($date, $id_user, $message, $tags, $destinataire);
            //echo 'message ajouté';
        } 
        else {
            $messErr = '<h4 class="alert_info">Vous devez entrer un texte !</h4>' ; 
        }
    }
?>