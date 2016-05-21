<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 */
// messages d'erreur et message de formulaire

// post_moncompte.php
$mes_0  = "<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i>
           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention!</b> Une erreur s'est produite lors de l'acc&egrave;s &agrave; la base</div>";
// admin_user.php
$mes_1  ="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i>
           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention!</b> Impossible de r&eacute;cup&eacute;rer les utilisateurs</div>";
// index.php
$mes_2  ="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i>
           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention!</b> Le fichier &agrave; inclure est introuvable</div>";
// index.php
$mes_3  ="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i>
           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention!</b> L'authentification a &eacute;chou&eacute;</div>";
// post_user.php
$mes_4  ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention</b> Veuillez remplir correctement les champs marqu&eacute;s d'une &eacute;toile</div>";
// post_user.php
$mes_5  ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Le login choisi existe d&eacute;j&agrave;</div>";
// admin_user.php
$mes_6 ="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun r&eacute;sultat correspondant &agrave; votre recherche</h4>";
//post_moncompte.php
$mes_7 ="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Les nouveaux mot de passe ne correspondent pas</div>";
//post_moncompte.php
$mes_8 ="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Votre mot de passe &agrave; &eacute;t&eacute; modifi&eacute; avec succ&egrave;s</div>";
//admin_materiel.php
$mes_9 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun mat&eacute;riel n'a &eacute;t&eacute; trouv&eacute;</div>";                      
//admin_materiel.php
$mes_10 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune br&egrave;ve n'a &eacute;t&eacute; trouv&eacute;</div>";
//admin_materiel.php
$mes_11 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Veuillez d'abord supprimer ou modifier la liste des adh&eacute;rents qui utilisent cette ville</div>";
//admin_inter.php
$mes_12 = "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune intervention pour le moment</div>";
//user_url.php
$mes_13 = "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Merci de remplir tous les champs</div>";
//admin_configuration
$mes_14 = "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Mise &agrave; jour effectu&eacute;e</div>" ;
//admin_configuration
$mes_15 = "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Le jour surlign&eacute; &agrave; &eacute;t&eacute; r&eacute;initialis&eacute;car il contenait une incompatibilit&eacute;.</div>" ;
// post_atelier -> form_atelier
$mes_16 = "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Le format de la date n'est pas valide</div>" ;
// pret de cable
$mes_17 = "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>aucun pr&ecirc;t de cable enregistr&eacute;</div>" ;
//admin form user
$mes_18= "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Nouvel adh&eacute;rent ins&eacute;r&eacute; dans la base</div>" ;
//table des resas
$mes_19= "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>L\'espace est ferm&eacute; ce jour la.</div>" ;


// post_atelier -> form_atelier
$mes_20 = "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Sujet d'atelier enregistr&eacute;</div>" ;
$mes_21="<div class=\"alert alert-danger alert-dismissable\"><i class=\"fa fa-ban\"></i>
           <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
           <b>Attention!</b> Inscription refus&eacute;e, l'adh&eacute;rent est d&eacute;j&agrave; inscrit.</div>";

$mes_22="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Sujet modifi&eacute;</div>" ;  	  
	  
$mes_23="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Sujet  ajout&eacute;</div>" ;  
	  
$mes_24="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Sujet  supprim&eacute;</div>" ;
	  
$mes_25="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription valid&eacute;e</div>" ;
	  
$mes_26="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription modifi&eacute;e</div>" ;
	  
$mes_27="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription supprim&eacute;e</div>" ;


$mes_30="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun EPN n'est inscrit dans la base</div>" ;

$mes_31="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune salle n'est inscrite dans la base</div>" ;


$mes_32="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Tarif ajout&eacute;</div>" ;

$mes_33="<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Tarif modifi&eacute;</div>" ;

$mes_34="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Tarif supprim&eacute;</div>" ;
///geston des forfaits
$mes_35="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Forfait supprimé du compte</div>" ;
///statistiques
$mes_36="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun adh&eacute;rent inscrit dans la base !</div>" ;
$mes_37="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune donn&eacute;e pour construire les statistiques !</div>" ;
	  

//admin_list_print
$mes_40 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune transaction enregistr&eacute;e</div>";   

$mes_41 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune pr&eacute;inscription en attente</div>";   

//admin form user
$mes_42= "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Fiche adh&eacute;rent modifi&eacute;e</div>" ;
//presence atelier/session
$mes_43= "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
	  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Pr&eacute;sences valid&eacute;es.</div>" ;	  
$mes_44 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Pr&eacute;sences d&eacute;j&agrave; valid&eacute;es</div>";   		  
//formulaire creation session
$mes_45 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Dates manquantes, veuillez toutes les remplir svp !</div>";   
//suppression d'une session par la liste
$mes_46= "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
	  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Session supprim&eacute;e</div>" ;	
//suppression d'une session par la liste
$mes_47= "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check-square\"></i>
	  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Adh&eacute;rent archiv&eacute; pour statistique</div>" ;	  
	  
//admin_materiel.php
$mes_48 ="<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun texte de courrier trouv&eacute; !</div>";	  
// form_preinscription
$mes_48= "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention</b> Le format du mail n'est pas valide</div>" ;
$mes_49= "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Attention</b> La date saisie n'est pas valide</div>" ;
      
	  
?>
