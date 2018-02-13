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


  include/conf.php V0.1
 */

// Fichier de configuration de l'espace utilisateur
// #############################################################################
// menu utilisateur
// #############################################################################
switch ($m) {
    default:
    case 1:
        $titre = "Accueil";
        $aide = "Bonjour et bienvenue sur l'EPN de l'AME pour toutes les suggestions ou questions merci de nous contacter.";
        $inc = "user_accueil.php";
        break;
    case 2:
        $titre = "Mon compte";
        $aide = "Acc&eacute;dez aux informations vous concernant, si vous souhaitez modifier vos informations hormis votre mot de passe, merci de vous adress&eacute; &agrave; votre animateur";
        include("include/post_moncompte.php");
        $inc = "user_compte.php";
        break;
    case 3:
        $titre = "Planning de r&eacute;servation des postes";
        $aide = "Vous pouvez consulter les postes occup&eacute;s, verifiez vous-m&ecirc;me une disponibilit&eacute; et r&eacute;server une machine.";
        $inc = "user_reservation.php";
        break;
    case 4:
        $titre = "Mes statistiques";
        $aide = "Consultez vos statistiques personnelles, c'est toujours int&eacute;ressant ;-)";
        $inc = "user_stat.php";
        break;
    case 5:
        $titre = "Mes liens favoris";
        $aide = "Vous pouvez stocker vos liens Internet et utiliser ceux par d&eacute;faut propos&eacute;s par vos animateurs";
        $inc = "user_url.php";
        break;
    case 6:
        $titre = "Les ateliers de formations";
        $aide = "Venez participer aux ateliers de l'espace et profitez des nombreuses formations propos&eacute;es";
        $inc = "user_atelier.php";
        break;
    case 7:
        $titre = "R&eacute;servation d'un poste";
        $aide = "Vous pouvez consulter les postes occup&eacute;s, verifiez vous m&ecirc;me une disponibilit&eacute; et r&eacute;server une machine.";
        include("include/post_reservation.php");
        $inc = "user_reservation_form.php";
        break;
    case 8:
        $titre = "Liste de mes r&eacute;servations";
        $aide = "Vous retrouvez ici les reservations que vous avez faites et qui ne sont pas encore pass&eacute;es.";
        $inc = "user_myresa.php";
        break;
    case 20:
        $titre = "Liste de mes impressions";
        $aide = "Vous retrouvez ici les impressions que vous avez faites et votre cr&eacute;dit.";
        $inc = "user_myprint.php";
        break;
}

if (isset($_SESSION["status"])) {
    switch ($_SESSION["status"]) {
        case 3:
            include('include/conf_admin.php');
            break;
        case 4:
            include('include/conf_admin.php');
            break;
    }
}
