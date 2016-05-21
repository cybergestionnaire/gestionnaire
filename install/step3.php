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

 2006-2008 Namont Nicolas

*/

session_start() ;
/***************************
 *
 * CREATION DU FICHIER DE CONFIGURATION
 *
 **/
$handle = @fopen('../connect_db.php','a+');
$string = (string)'
$host = "'.$_SESSION['db']['db_host'].'" ;
$port = "'.$_SESSION['db']['db_port'].'"; 
$database = "'.$_SESSION['db']['db_name'].'" ;
$userdb = "'.$_SESSION['db']['db_user'].'" ;
$passdb = "'.$_SESSION['db']['db_pass'].'" ;
?>' ;

if ( FALSE == @fwrite($handle, $string))
{
    $config_class = 'error';
    $config = '<span class="error">Une erreur s\'est produite lors de la cr&eacute;ation du fichier de configuration</span>'; 
}
else{
    $config_class = 'writable' ;
    $config = 'Cr&eacute;ation du fichier de configuration de la base de donn&eacute;es'; 
}
@fclose($handle) ;

/***************************
 *
 * CREATION DE LA BASE DE DONNEES
 *
 **/
//
// Structure de la table `rel_atelier_computer`
//
$query[] = "DROP TABLE IF EXISTS `rel_atelier_computer`"; 
$query[] = "CREATE TABLE `rel_atelier_computer` (
  `id_atelier_computer` int(11) NOT NULL AUTO_INCREMENT,
  `id_atelier_rel` int(11) NOT NULL,
  `id_computer_rel` int(11) NOT NULL,
  PRIMARY KEY (`id_atelier_computer`)
)  ENGINE=MyISAM "; 

//
// Structure de la table `rel_forfait_espace`
//
$query[] = "DROP TABLE IF EXISTS `rel_forfait_espace`"; 
$query[] = "CREATE TABLE  `rel_forfait_espace` (
  `id_forfait_espace` int(11) NOT NULL AUTO_INCREMENT,
  `id_forfait` int(11) NOT NULL,
  `id_espace` int(11) NOT NULL,
  PRIMARY KEY (`id_forfait_espace`)
) ENGINE=MyISAM "; 


//
// Structure de la table `rel_forfait_user`
//
$query[] = "DROP TABLE IF EXISTS `rel_forfait_user`"; 
$query[] = "CREATE TABLE `rel_forfait_user` (
  `id_rel_forfait_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_forfait` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_forfait_user`)
)ENGINE=MyISAM "; 


//
// Structure de la table `rel_inscription_forfait_user`
//
$query[] = "DROP TABLE IF EXISTS `rel_inscription_forfait_user`"; 
$query[] = "CREATE TABLE `rel_inscription_forfait_user` (
  `id_rel_forfait_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_forfait` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_rel_forfait_user`)
) ENGINE=MyISAM "; 


// table rel_atelier_user
$query[] = "DROP TABLE IF EXISTS `rel_atelier_user`"; 
$query[] = "CREATE TABLE `rel_atelier_user` (
  `id_rel_atelier_user` int(11) NOT NULL auto_increment,
  `id_atelier` int(11) NOT NULL default '0',
  `id_user` int(11) NOT NULL default '0',
  `status_rel_atelier_user` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id_rel_atelier_user`)
) ENGINE=MyISAM ;"; 

// table rel_inter_computer
$query[] = "DROP TABLE IF EXISTS `rel_inter_computer`";
$query[] = "CREATE TABLE `rel_inter_computer` (
  `id_inter_computer` int(11) NOT NULL auto_increment,
  `id_inter` int(11) NOT NULL default '0',
  `id_computer` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_inter_computer`)
) ENGINE=MyISAM ";

// table rel_level_usage_user
$query[] = "DROP TABLE IF EXISTS `rel_level_usage_user`";
$query[] = "CREATE TABLE `rel_level_usage_user` (
  `id_level_usage_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_level_usage_user`)
) ENGINE=MyISAM";

// table rel_session_user
$query[] = "DROP TABLE IF EXISTS `rel_session_user`"; 
$query[] = "CREATE TABLE `rel_session_user` (
 `id_rel_session` int(11) NOT NULL AUTO_INCREMENT,
  `id_session` int(11) NOT NULL DEFAULT '0',
  `id_datesession` int(11) NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `status_rel_session` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id_rel_session`)
) ENGINE=MyISAM "; 

// table rel_url_rub
$query[] = "DROP TABLE IF EXISTS `rel_url_rub`";
$query[] = "CREATE TABLE `rel_url_rub` (
  `id_url_rub` int(11) NOT NULL auto_increment,
  `id_url` int(11) NOT NULL,
  `id_rub` int(11) NOT NULL,
  PRIMARY KEY  (`id_url_rub`)
) ENGINE=MyISAM";

// table rel_usage_computer
$query[] = "DROP TABLE IF EXISTS `rel_usage_computer`";
$query[] = "CREATE TABLE `rel_usage_computer` (
  `id_usage_computer` int(11) NOT NULL auto_increment,
  `id_computer` int(11) NOT NULL default '0',
  `id_usage` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_usage_computer`)
) ENGINE=MyISAM";

//table rel_user_anim
$query[]="DROP TABLE IF EXISTS `rel_user_anim`";
$query[]="CREATE TABLE `rel_user_anim` (
  `id_useranim` int(11) NOT NULL AUTO_INCREMENT,
  `id_animateur` int(11) NOT NULL,
  `id_epn` int(11) NOT NULL,
  `id_salle` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `anim_avatar` varchar(500) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_useranim`)
) ENGINE=MyISAM ";

//table `rel_user_forfait`
$query[] ="DROP TABLE IF EXISTS `rel_user_forfait`";
$query[] ="CREATE TABLE IF NOT EXISTS `rel_user_forfait` (
 `id_forfait` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_transac` int(11) NOT NULL,
  `total_atelier` int(11) NOT NULL,
  `depense` int(11) NOT NULL,
  `statut_forfait` int(11) NOT NULL,
  PRIMARY KEY (`id_forfait`)
) ENGINE=MyISAM";


// table rel_utilisation_user
$query[] = "DROP TABLE IF EXISTS `rel_utilisation_user`";
$query[] = "CREATE TABLE  `rel_utilisation_user` (
`id_utilisation_user` int(11) NOT NULL auto_increment,
  `id_utilisation` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_utilisation_user`)
) ENGINE=MyISAM";

//
// Structure de la table `tab_as_stat`
$query[] = "DROP TABLE IF EXISTS `tab_as_stat` ";
$query[] = "CREATE TABLE `tab_as_stat` (
  `id_stat` int(11) NOT NULL AUTO_INCREMENT,
  `type_AS` varchar(11) COLLATE latin1_general_ci DEFAULT NULL,
  `id_AS` int(11) NOT NULL,
  `date_AS` datetime DEFAULT NULL,
  `inscrits` int(11) NOT NULL,
  `presents` int(11) NOT NULL,
  `absents` int(11) NOT NULL,
  `attente` int(11) NOT NULL,
  `nbplace` int(11) NOT NULL,
	`id_categorie` int(11) NOT NULL,
  `statut_programmation` int(11) NOT NULL,
   `id_anim` int(11) NOT NULL,
  `id_epn` int(11) NOT NULL,
  PRIMARY KEY (`id_stat`)
) ENGINE=MyISAM  ";


// table atelier
$query[] = "DROP TABLE IF EXISTS `tab_atelier`";
$query[] = "CREATE TABLE `tab_atelier` (
  `id_atelier` int(11) NOT NULL AUTO_INCREMENT,
  `date_atelier` date NOT NULL,
  `heure_atelier` varchar(25) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `duree_atelier` int(11) NOT NULL DEFAULT '0',
  `anim_atelier` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `id_sujet` int(5) NOT NULL DEFAULT '0',
  `nbplace_atelier` int(11) NOT NULL DEFAULT '0',
  `public_atelier` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `statut_atelier` int(11) NOT NULL,
  `salle_atelier` int(11) NOT NULL,
  `tarif_atelier` int(11) NOT NULL,
  `status_atelier` enum('0','1','2')  NOT NULL,
  `cloturer_atelier` enum('0','1')  NOT NULL,
  PRIMARY KEY (`id_atelier`)
) ENGINE=MyISAM";

// table tab_atelier_categorie
$query[] = "DROP TABLE IF EXISTS `tab_atelier_categorie`";
$query[] = "CREATE TABLE `tab_atelier_categorie` (
  `id_atelier_categorie` int(11) NOT NULL auto_increment,
  `label_categorie` varchar(250) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_atelier_categorie`)
) ENGINE=MyISAM";
$query[] = "INSERT INTO `tab_atelier_categorie` (`id_atelier_categorie`, `label_categorie`) 
VALUES 
(1, 'G&eacute;n&eacute;ral'),
(2, 'Bureautique'),
(3, 'Syst&egrave;me d\'exploitation'),
(4, 'Imagerie num&eacute;rique'),
(5, 'Internet, web'),
(6, 'Messagerie'),
(7, 'Vid&eacute;o'),
(8, 'Jeunesse')";

// table atelier_sujet
$query[] = "DROP TABLE IF EXISTS `tab_atelier_sujet`";
$query[] = "CREATE TABLE `tab_atelier_sujet` (
  `id_sujet` int(11) NOT NULL AUTO_INCREMENT,
  `label_atelier` varchar(100) NOT NULL,
  `content_atelier` varchar(500) NOT NULL,
  `ressource_atelier` varchar(200) NOT NULL,
  `niveau_atelier` int(2) NOT NULL,
  `categorie_atelier` int(2) NOT NULL,
  PRIMARY KEY (`id_sujet`)
) ENGINE=MyISAM";


//tab captcha pour les inscriptions
$query[] = "DROP TABLE IF EXISTS `tab_captcha`";
$query[]="CREATE TABLE `tab_captcha` (
  `id_captcha` int(11) NOT NULL AUTO_INCREMENT,
  `capt_activation` ENUM('N', 'Y') NOT NULL,
  `capt_code` varchar(500) COLLATE latin1_general_ci NOT NULL,
  
  PRIMARY KEY (`id_captcha`)
) ENGINE=MyISAM ";
$query[]="INSERT INTO `tab_captcha`(`id_captcha`, `capt_activation`, `capt_code`) VALUES (1,'N','')";


// table city
$query[] = "DROP TABLE IF EXISTS `tab_city`";
$query[] = "CREATE TABLE `tab_city` (
  `id_city` int(11) NOT NULL auto_increment,
  `nom_city` varchar(50) collate latin1_general_ci NOT NULL default '',
  `code_postale_city` varchar(20) collate latin1_general_ci NOT NULL default '',
  `pays_city` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_city`)
) ENGINE=MyISAM";
$query[] = "INSERT INTO `tab_city`  (`id_city`, `nom_city`, `code_postale_city`, `pays_city`) VALUES ('1','Paris','75000','FRANCE')";
 
// table computer
$query[] = "DROP TABLE IF EXISTS `tab_computer`";
$query[] = "CREATE TABLE `tab_computer` (
  `id_computer` int(11) NOT NULL AUTO_INCREMENT,
  `nom_computer` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `comment_computer` text COLLATE latin1_general_ci NOT NULL,
  `os_computer` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `usage_computer` int(11) NOT NULL DEFAULT '0',
  `fonction_computer` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_salle` int(11) NOT NULL,
  `adresse_mac_computer` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `adresse_ip_computer` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nom_hote_computer` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `date_lastetat_computer` date NOT NULL,
  `lastetat_computer` mediumint(11) NOT NULL,
  `configurer_epnconnect_computer` enum('0','1') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_computer`)
) ENGINE=MyISAM";


// table tab_config
$query[] = "DROP TABLE IF EXISTS `tab_config`";
$query[] = "CREATE TABLE `tab_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `activer_console` int(11) NOT NULL,
  `name_config` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `unit_default_config` int(2) NOT NULL,
  `unit_config` int(2) NOT NULL,
  `maxtime_config` int(11) NOT NULL,
  `maxtime_default_config` int(11) NOT NULL,
	 `id_espace` int(11) NOT NULL,
  `inscription_usagers_auto` enum('0','1') COLLATE latin1_general_ci NOT NULL,
  `message_inscription` text COLLATE latin1_general_ci NOT NULL,
 `nom_espace` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `activation_forfait` enum('0','1') COLLATE latin1_general_ci NOT NULL,
  `resarapide` enum('0','1') COLLATE latin1_general_ci NOT NULL,
  `duree_resarapide` int(11) NOT NULL,
  PRIMARY KEY (`id_config`)
) ENGINE=MyISAM";
$query[] = "INSERT INTO `tab_config`(`id_config`, `activer_console`, `name_config`, `unit_default_config`, `unit_config`, `maxtime_config`, `maxtime_default_config`, `inscription_usagers_auto`, `message_inscription`, `id_espace`, `nom_espace`, `activation_forfait`, `resarapide`, `duree_resarapide`) VALUES (1,1,'1.1',15,15,120,120,1,'message par defaut',1,'EPN Test',1,1,60)";


//table config logiciels
$query[] = "DROP TABLE IF EXISTS `tab_config_logiciel`";
$query[] = "CREATE TABLE `tab_config_logiciel` (
   `id_config_logiciel` int(11) NOT NULL AUTO_INCREMENT,
  `id_espace` int(11) NOT NULL,
  `config_menu_logiciel` int(11) NOT NULL,
  `page_inscription_logiciel` int(11) NOT NULL,
  `page_renseignement_logiciel` int(11) NOT NULL,
  `connexion_anim_logiciel` int(11) NOT NULL,
  `bloquage_touche_logiciel` int(11) NOT NULL,
  `affichage_temps_logiciel` int(11) NOT NULL,
  `deconnexion_auto_logiciel` int(11) NOT NULL,
  `fermeture_session_auto` int(11) NOT NULL,
  PRIMARY KEY (`id_config_logiciel`)
) ENGINE=MyISAM";


$query[] = "DROP TABLE IF EXISTS `tab_connexion`";
$query[] = "CREATE TABLE `tab_connexion` (
`id_connexion` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_cx` datetime NOT NULL,
  `type_cx` int(11) NOT NULL,
  `macasdress_cx` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `navigateur_cx` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `system_cx` varchar(200) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM " ;


$query[] = "DROP TABLE IF EXISTS `tab_courriers`";
$query[] = "CREATE TABLE `tab_courriers` (
  `id_courrier` int(11) NOT NULL AUTO_INCREMENT,
  `courrier_titre` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `courrier_text` varchar(800) COLLATE latin1_general_ci NOT NULL,
  `courrier_name` int(11) NOT NULL,
  `courrier_type` int(11) NOT NULL,
  PRIMARY KEY (`id_courrier`)
) ENGINE=MyISAM " ;

$query[]="INSERT INTO `tab_courriers` (`id_courrier`, `courrier_titre`, `courrier_text`, `courrier_name`, `courrier_type`) VALUES
(1, 'rappel', 'Piqure de rappel', 1, 2),
(2, 'rappel', 'Vous &ecirc;tes inscrit(e) &agrave; un atelier :', 1, 3),
(3, 'rappel', 'N''h&eacute;sitez pas &agrave; nous recontacter aux coordonn&eacute;es suivantes', 1, 4);";





//table CSP
$query[] = "DROP TABLE IF EXISTS `tab_csp`";
$query[] = "CREATE TABLE `tab_csp` (
  `id_csp` int(11) NOT NULL AUTO_INCREMENT,
  `csp` varchar(50) NOT NULL,
  PRIMARY KEY (`id_csp`)
) ENGINE=MyISAM" ;

$query[] = "INSERT INTO `tab_csp` (`id_csp`, `csp`) 
VALUES(1, 'Retrait&eacute;'),
(2, 'Employ&eacute;'),
(3, 'Scolaire'),
(4, 'Demandeur d''emploi'),
(5, 'M&egrave;re/P&egrave;re au foyer'),
(6, 'Lyc&eacute;en'),
(7, 'Etudiant'),
(8, 'Artisans/Prof. Lib'),
(9, 'Instituteurs'),
(10, 'Agriculteur'),
(11, 'Fonctionnaires'),
(12, 'Divers'),
(13, 'Coll&eacute;gien'),
(14,'Non renseign&eacute;e'),
(15,'Professions interm&eacute;diaires'),
(16, 'Ouvrier'),
(17,'Cadres ')
";


// table tab_days_closed
$query[] = "DROP TABLE IF EXISTS `tab_days_closed`";
$query[] = "CREATE TABLE `tab_days_closed` (
  `id_days_closed` int(11) NOT NULL auto_increment,
  `year_days_closed` int(4) NOT NULL,
  `num_days_closed` int(3) NOT NULL,
  `state_days_closed` varchar(1) collate latin1_general_ci NOT NULL,
    `id_epn` int(11) NOT NULL,
  PRIMARY KEY  (`id_days_closed`)
) ENGINE=MyISAM";

// table des espaces
$query[] = "DROP TABLE IF EXISTS `tab_espace`";
$query[] = "CREATE TABLE `tab_espace` (
`id_espace` int(11) NOT NULL auto_increment,
  `nom_espace` varchar(50) NOT NULL,
  `id_city` int(11) NOT NULL,
  `adresse` varchar(300) NOT NULL,
   `tel_espace` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `fax_espace` varchar(150) COLLATE latin1_general_ci NOT NULL,
   `logo_espace` varchar(200) COLLATE latin1_general_ci NOT NULL,
   `couleur_espace` int(11) NOT NULL,
   `mail_espace` VARCHAR( 300 ) COLLATE latin1_general_ci NOT NULL,
 PRIMARY KEY (`id_espace`)
) ENGINE=MyISAM";
$query[] ="INSERT INTO `tab_espace`(`id_espace`, `nom_espace`, `id_city`, `adresse`, `tel_espace`, `fax_espace`, `logo_espace`, `couleur_espace`,`mail_espace`) VALUES(1, 'Epn Test', 1, '45, rue franklin roosevelt','0011223344','5566778899', 1, 1,'mail@mail.com');";

//
// Structure de la table `tab_forfait`
$query[] = "DROP TABLE IF EXISTS `tab_forfait`";
$query[] = "CREATE TABLE `tab_forfait` (
  `id_forfait` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation_forfait` date NOT NULL,
  `type_forfait` int(11) NOT NULL,
  `nom_forfait` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `prix_forfait` int(11) NOT NULL,
  `critere_forfait` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `commentaire_forfait` text COLLATE latin1_general_ci NOT NULL,
  `nombre_duree_forfait` int(11) NOT NULL,
  `unite_duree_forfait` int(11) NOT NULL,
  `temps_forfait_illimite` enum('0','1') COLLATE latin1_general_ci NOT NULL,
  `date_debut_forfait` date NOT NULL,
  `status_forfait` int(11) NOT NULL,
  `nombre_temps_affectation` int(11) NOT NULL,
  `unite_temps_affectation` int(11) NOT NULL,
  `frequence_temps_affectation` int(11) NOT NULL,
  `temps_affectation_occasionnel` int(11) NOT NULL,
  `nombre_atelier_forfait` int(11) NOT NULL,
  PRIMARY KEY (`id_forfait`)
) ENGINE=InnoDB " ;


// table tab_horaire
$query[] = "DROP TABLE IF EXISTS `tab_horaire`";
$query[] = "CREATE TABLE `tab_horaire` (
  `id_horaire` int(11) NOT NULL auto_increment,
  `jour_horaire` int(1) NOT NULL,
  `hor1_begin_horaire` int(4) NOT NULL,
  `hor1_end_horaire` int(4) NOT NULL,
  `hor2_begin_horaire` int(4) NOT NULL,
  `hor2_end_horaire` int(4) NOT NULL,
  `unit_horaire` int(3) NOT NULL,
  `id_epn` int(3) NOT NULL,
  PRIMARY KEY  (`id_horaire`)
) ENGINE=MyISAM ";

$query[] = "INSERT INTO `tab_horaire` (`id_horaire`, `jour_horaire`, `hor1_begin_horaire`, `hor1_end_horaire`, `hor2_begin_horaire`, `hor2_end_horaire`, `unit_horaire`,`id_epn`) VALUES 
(1, 1, 0, 0, 0, 0, 0, 1),
(2, 2, 600, 720, 780, 1140, 0, 1),
(3, 3, 600, 720, 780, 1140, 0, 1),
(4, 4, 600, 720, 780, 1140, 0, 1),
(5, 5, 600, 720, 780, 1140, 0, 1),
(6, 6, 600, 720, 780, 1140, 0, 1),
(7, 7, 0, 0, 0, 0, 0, 1)";

//table `tab_inscription_user`
$query[] = "DROP TABLE IF EXISTS `tab_inscription_user`";
$query[] = "CREATE TABLE `tab_inscription_user` (
  `id_inscription_user` int(11) NOT NULL AUTO_INCREMENT,
  `date_inscription_user` varchar(10) NOT NULL,
  `nom_inscription_user` varchar(50) NOT NULL,
  `prenom_inscription_user` varchar(50) NOT NULL,
  `sexe_inscription_user` char(1) NOT NULL,
  `jour_naissance_inscription_user` int(2) NOT NULL,
  `mois_naissance_inscription_user` int(2) NOT NULL,
  `annee_naissance_inscription_user` int(4) NOT NULL,
  `adresse_inscription_user` varchar(100) NOT NULL,
  `quartier_inscription_user` varchar(100) NOT NULL,
  `code_postal_inscription` int(5) NOT NULL,
  `commune_inscription_autres` varchar(100) NOT NULL,
  `ville_inscription_user` int(11) NOT NULL,
  `tel_inscription_user` varchar(18) NOT NULL,
  `tel_port_inscription_user` varchar(18) NOT NULL,
  `mail_inscription_user` varchar(100) NOT NULL,
  `temps_inscription_user` int(11) NOT NULL,
  `login_inscription_user` varchar(20) NOT NULL,
  `pass_inscription_user` varchar(50) NOT NULL,
  `status_inscription_user` int(2) NOT NULL,
  `lastvisit_inscription_user` date NOT NULL,
  `csp_inscription_user` int(11) NOT NULL,
  `equipement_inscription_user` varchar(50) NOT NULL,
  `utilisation_inscription_user` int(11) NOT NULL,
  `connaissance_inscription_user` int(11) NOT NULL,
  `info_inscription_user` text NOT NULL,
  `id_inscription_computer` int(11) NOT NULL,
  PRIMARY KEY (`id_inscription_user`)
) ENGINE=MyISAM";

// table tab_inter
$query[] = "DROP TABLE IF EXISTS `tab_inter`";
$query[] = "CREATE TABLE `tab_inter` (
  `id_inter` int(11) NOT NULL auto_increment,
  `titre_inter` varchar(100) collate latin1_general_ci NOT NULL default '',
  `comment_inter` text collate latin1_general_ci NOT NULL,
  `statut_inter` tinyint(1) NOT NULL default '0',
  `date_inter` varchar(10) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_inter`)
) ENGINE=MyISAM";

// table tab_level
$query[] = "DROP TABLE IF EXISTS `tab_level`";
$query[] = "CREATE TABLE `tab_level` (
  `id_level` tinyint(4) NOT NULL auto_increment,
  `code_level` tinyint(1) NOT NULL default '0',
  `nom_level` varchar(80) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_level`)
) ENGINE=MyISAM";
$query[] = "INSERT INTO `tab_level` (`id_level`, `code_level`, `nom_level`) VALUES 
(1, 1, 'Debutant'),
(2, 2, 'Apprenti'),
(3, 3, 'Autonome'),
(4, 4, 'Confirm&eacute;'),
(5, 5, 'Expert'),
(6, 6, 'Administrateur')";


 //Structure de la table `tab_logs`
//
$query[] = "DROP TABLE IF EXISTS `tab_logs`";
$query[] = "CREATE TABLE `tab_logs` (
`id_log` int(11) NOT NULL auto_increment,
 `log_type` varchar(10) COLLATE latin1_general_ci NOT NULL,
`log_date` datetime,
  `log_MAJ` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `log_valid` int(11) NOT NULL,
  `log_comment` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=MyISAM ";
$query[] = "INSERT INTO `tab_logs` (`id_log`,  `log_type`,`log_date`, `log_MAJ`,`log_valid`,`log_comment`) 
VALUES (1,'maj',NOW(),'1.0',1,'Installation de la version 1.0 par la procedure installation complete'),
(2,'bac',NOW(),'1.0',1,'creation de la base de donnee')";

//table messages epn connect
$query[] = "DROP TABLE IF EXISTS `tab_message_epnconnect`";
$query[] = "CREATE TABLE `tab_message_epnconnect` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `corp_message` text NOT NULL,
  `status_message` int(11) NOT NULL,
  `espace_message` int(11) NOT NULL,
  `date_message` date NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM";


// table tab_messages
$query[] = "DROP TABLE IF EXISTS `tab_messages`";
$query[] = "CREATE TABLE `tab_messages` (
  `id_messages` int(11) NOT NULL auto_increment,
  `mes_date` datetime NOT NULL,
  `mes_auteur` int(11) NOT NULL,
  `mes_txt` varchar(500) collate latin1_general_ci NOT NULL default '',
  `mes_tag` varchar(300) collate latin1_general_ci NOT NULL default '',
  `mes_destinataire` int(11) NOT NULL,
  PRIMARY KEY  (`id_messages`)
) ENGINE=MyISAM";

// table tab_news
$query[] = "DROP TABLE IF EXISTS `tab_news`";
$query[] = "CREATE TABLE `tab_news` (
  `id_news` int(11) NOT NULL auto_increment,
  `titre_news` varchar(200) collate latin1_general_ci NOT NULL default '',
  `comment_news` text collate latin1_general_ci NOT NULL,
  `visible_news` tinyint(1) NOT NULL default '0',
   `type_news` int(11) NOT NULL,
  `date_publish` DATETIME ,
  `date_news` DATETIME,
  `id_epn` int(5),
  PRIMARY KEY  (`id_news`)
) ENGINE=MyISAM";


// table print
$query[] = "DROP TABLE IF EXISTS `tab_print`";
$query[] = "CREATE TABLE `tab_print` (
  `id_print` int(11) NOT NULL AUTO_INCREMENT,
  `print_date` datetime NOT NULL,
  `print_user` int(11) NOT NULL,
  `print_debit` int(11) NOT NULL,
  `print_tarif` int(11) NOT NULL,
  `print_statut` int(11) NOT NULL,
  `print_credit` decimal(10,2) NOT NULL,
  `print_userexterne` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `print_epn` int(11) NOT NULL,
  `print_caissier` int(11) NOT NULL,
  `print_paiement` int(11) NOT NULL,
  PRIMARY KEY (`id_print`)
) ENGINE=MyISAM";


//table tab_resa
$query[] = "DROP TABLE IF EXISTS `tab_resa`";
$query[] = "CREATE TABLE `tab_resa` (
  `id_resa` int(11) NOT NULL auto_increment,
  `id_computer_resa` int(11) NOT NULL,
  `id_user_resa` int(11) NOT NULL,
  `dateresa_resa` date NOT NULL,
  `debut_resa` int(11) NOT NULL,
  `duree_resa` int(11) NOT NULL,
  `date_resa` date NOT NULL,
  `status_resa` enum('0','1','2') collate latin1_general_ci NOT NULL default '0',
  
  PRIMARY KEY  (`id_resa`)
) ENGINE=MyISAM";

//table des salles
$query[] = "DROP TABLE IF EXISTS `tab_salle` ";
$query[] = "CREATE TABLE `tab_salle` (
`id_salle` int(11) NOT NULL auto_increment,
  `nom_salle` varchar(50) NOT NULL,
  `id_espace` int(11) NOT NULL,
  `comment_salle` text NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=MyISAM";
$query[] = "INSERT INTO `tab_salle` (`id_salle`, `nom_salle`, `id_espace`, `comment_salle`) VALUES(1, 'Espace Consultation', 1, 'Vous pouvez changer le nom de cette salle !'); ";

//table tab_session
$query[] = "DROP TABLE IF EXISTS `tab_session`";
$query[] = "CREATE TABLE `tab_session` (
  `id_session` int(11) NOT NULL auto_increment,
  `date_session` date NOT NULL,
  `nom_session`  int(11),
   `nbplace_session` int(11) NOT NULL,
  `nbre_dates_sessions` int(11) NOT NULL,
  `status_session` int(11) NOT NULL,
  `id_anim` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL,
`id_tarif` int(11) NOT NULL,
  PRIMARY KEY  (`id_session`)
) ENGINE=MyISAM";

//table tab_session_dates : lister les dates pour une session
$query[] = "DROP TABLE IF EXISTS `tab_session_dates`";
$query[]= "CREATE TABLE `tab_session_dates` (
`id_datesession` int(11) NOT NULL AUTO_INCREMENT,
  `id_session` int(11) NOT NULL,
  `date_session` datetime NOT NULL,
  `statut_datesession` int(11) NOT NULL,
  PRIMARY KEY (`id_datesession`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;
";

//table tab_session_sujet
$query[] = "DROP TABLE IF EXISTS `tab_session_sujet`";
$query[] = "CREATE TABLE `tab_session_sujet` (
  `id_session_sujet` int(11) NOT NULL auto_increment,
  `session_titre`  varchar(300) collate latin1_general_ci NOT NULL default '',
  `session_detail` varchar(500) collate latin1_general_ci NOT NULL default '',
  `session_niveau`  int(11) NOT NULL,
  `session_categorie` int(11) NOT NULL,
   PRIMARY KEY  (`id_session_sujet`)
) ENGINE=MyISAM";

// table tarif
$query[] = "DROP TABLE IF EXISTS `tab_tarifs`";
$query[] = "CREATE TABLE `tab_tarifs` (
  `id_tarif` int(11) NOT NULL AUTO_INCREMENT,
  `nom_tarif` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `donnee_tarif` decimal(10,2) NOT NULL,
  `comment_tarif` varchar(300) COLLATE latin1_general_ci NOT NULL,
  `nb_atelier_forfait` int(11) NOT NULL,
  `categorie_tarif` int(11) NOT NULL,
  `duree_tarif` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `epn_tarif` varchar(200) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_tarif`)
) ENGINE=MyISAM";
$query[]="INSERT INTO `tab_tarifs`(`id_tarif`, `nom_tarif`, `donnee_tarif`, `comment_tarif`, `nb_atelier_forfait`, `categorie_tarif`, `duree_tarif`, `epn_tarif`) VALUES(1, 'sans tarif-illimité ', '0 ', 'default--ne pas enlever merci', 0, 5,0,1);";

//table des transactions
$query[] = "DROP TABLE IF EXISTS `tab_transactions`";
$query[] = "CREATE TABLE  `tab_transactions`(
  `id_transac` int(11) NOT NULL AUTO_INCREMENT,
  `type_transac` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_tarif` int(11) NOT NULL,
  `nbr_forfait` int(11) NOT NULL,
  `date_transac` date NOT NULL,
  `status_transac` int(11) NOT NULL,
  PRIMARY KEY (`id_transac`)
) ENGINE=MyISAM";



//table des URL favory
$query[] = "DROP TABLE IF EXISTS `tab_url`";
$query[] = "CREATE TABLE `tab_url` (
  `id_url` int(11) NOT NULL auto_increment,
  `iduser_url` int(11) NOT NULL default '0',
  `titre_url` varchar(150) collate latin1_general_ci NOT NULL default '',
  `url_url` varchar(250) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_url`)
) ENGINE=MyISAM";

//tables tab_url_rub
$query[] = "DROP TABLE IF EXISTS `tab_url_rub`";
$query[] = "CREATE TABLE `tab_url_rub` (
  `id_url_rub` int(11) NOT NULL auto_increment,
  `iduser_url_rub` int(11) NOT NULL,
  `label_url_rub` varchar(250) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id_url_rub`)
) ENGINE=MyISAM";

//table usage des postes
$query[] = "DROP TABLE IF EXISTS `tab_usage`";
$query[] = "CREATE TABLE `tab_usage` (
  `id_usage` int(11) NOT NULL auto_increment,
  `nom_usage` varchar(80) collate latin1_general_ci NOT NULL default '',
  `type_usage` varchar(20) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id_usage`)
) ENGINE=MyISAM";
$query[] = "
INSERT INTO `tab_usage` (`id_usage`, `nom_usage`, `type_usage`) VALUES 
(1, 'Capture/Montage video', 'public'),
(2, 'Scanner', 'public'),
(3, 'Gravure CD/DVD', 'public'),
(4, 'Capture/Montage audio', 'public'),
(5, 'jeux vid&eacute;o', 'public'),
(6, 'Lecture cartes', 'public'),
(7, 'impression', 'public'),
(8, 'Navigation Internet', 'public'),
(9, 'Messagerie Instantan&eacute;e ', 'public')";

//table des adherents
$query[] = "DROP TABLE IF EXISTS `tab_user`";
$query[] = "CREATE TABLE `tab_user` (
   `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `date_insc_user` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `nom_user` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `prenom_user` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sexe_user` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `jour_naissance_user` int(2) NOT NULL DEFAULT '0',
  `mois_naissance_user` int(2) NOT NULL DEFAULT '0',
  `annee_naissance_user` int(4) NOT NULL DEFAULT '0',
  `adresse_user` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ville_user` int(11) NOT NULL DEFAULT '0',
  `tel_user` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `mail_user` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `temps_user` int(11) NOT NULL DEFAULT '0',
  `login_user` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `pass_user` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `status_user` int(2) NOT NULL DEFAULT '0',
  `lastvisit_user` date NOT NULL,
  `csp_user` int(11) NOT NULL DEFAULT '0',
  `equipement_user` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `utilisation_user` int(11) NOT NULL,
  `connaissance_user` int(11) NOT NULL,
  `info_user` text COLLATE latin1_general_ci NOT NULL,
  `tarif_user` int(11) NOT NULL,
  `dateRen_user` date NOT NULL,
  `epn_user` int(11) NOT NULL,
  `newsletter_user` int(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `date_user` (`date_insc_user`)
) ENGINE=MyISAM";

$query[] = "INSERT INTO `tab_user` (`id_user`, `date_insc_user`, `nom_user`, `prenom_user`, `sexe_user`, `jour_naissance_user`, `mois_naissance_user`, `annee_naissance_user`, `adresse_user`, `ville_user`, `tel_user`, `mail_user`, `temps_user`, `login_user`, `pass_user`, `status_user`, `lastvisit_user`, `csp_user`, `equipement_user`, `utilisation_user`, `connaissance_user`, `info_user`, `tarif_user`, `dateRen_user`,`epn_user`,`newsletter_user`) VALUES(1, '2014-01-01', 'admin', 'administrateur', 'H', 1, 1, 1977, 'rue du libre', 1, '', '', 999, 'admin', MD5('admin'), 4, '2014-11-09', 5, 0, 0, '0', '0', 0, '0000-00-00',1,0),
(2, '2014-01-01', 'Externe', 'De passage', 'H', 1, 1, 1977, 'rue du libre', 1, '', '', 999, 'compte_imprim', MD5('compte_imprim'), 4, '2014-11-09', 5, 0, 0, '0', '0', 0, '0000-00-00',1,0)
;";

//
// Ajout table utilisation
$query[] = "DROP TABLE IF EXISTS `tab_utilisation`";
$query[] = "CREATE TABLE `tab_utilisation` (
`id_utilisation` int(11) NOT NULL auto_increment,
  `nom_utilisation` varchar(75) NOT NULL,
  `type_menu` varchar(50) NOT NULL,
  `visible` varchar(3) NOT NULL,
  PRIMARY KEY (`id_utilisation`)
) ENGINE=MyISAM";
$query[] = "
INSERT INTO `tab_utilisation` (`id_utilisation`, `nom_utilisation`, `type_menu`, `visible`) VALUES 
(1,  'Bureautique', 'Menu Principal', 'oui'),
(2, 'Consultation/actualisation du dossier de demandeur d\'emploi', 'Sous Menu', 'oui'),
(3, 'Gravure CD/DVD', 'Menu Principal', 'oui'),
(4, 'Recherche d\'information', 'Menu Principal', 'oui'),
(5, 'CV, Lettre de motivation', 'Sous Menu', 'oui'),
(6,'D&eacute;marches de creation ou reprise d\'entreprise', 'Sous Menu', 'oui'),
(7,'Suivi de candidatures', 'Sous Menu', 'oui'),
(8,'Internet', 'Menu Principal', 'oui'),
(9, 'Vie quotidienne, loisirs, sport, vacances', 'Sous Menu', 'oui'),
(10, 'Enseignement, formation', 'Sous Menu', 'oui'),
(11, 'Recherches et consultations d''offres, candidature', 'Sous Menu', 'oui'),
(12, 'E-administration', 'Sous Menu', 'oui'),
(13, 'E-commerce', 'Sous Menu', 'oui'),
(14, 'Services numeriques scolaires', 'Sous Menu', 'oui'),
(15, 'Autres', 'Menu Principal', 'oui');";


$link = mysqli_connect($_SESSION['db']['db_host'],$_SESSION['db']['db_user'],$_SESSION['db']['db_pass'], $_SESSION['db']['db_name']); 

foreach ($query AS $value)
{
if (mysqli_query($link, utf8_decode($value)) == FALSE) {
       $error = 1 ;
        break ;   
    }
}
mysqli_close($link) ;

if (TRUE == isset($error))
{   
    $db_class = 'error' ;
    $db = 'Une erreur s\'est produite lors de la cr&eacute;ation de la base de donn&eacute;es'; 
}
else
{
    $db_class = 'writable' ;
    $db = 'Cr&eacute;ation de la base de donn&eacute;es'; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"  class="lockscreen">
<head>
    <title>Installation de Cyber-Gestionnaire</title>
   <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <script type="text/javascript" src="script.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
          <!-- Bootstrap 3.3.2 -->
    <link href="../template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="../template/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
</head>
<body class="register-page">
    <div class="register-box">
<div class="register-logo">Installation Etape 3 / 3</div>
     
     <div class="register-box-body"> 
     
        <h4>Fin de l'installation</h4>
        <ul>
            <li class="<?php echo $config_class ;?>"><?php echo $config ;?></li>
            <li class="<?php echo $db_class ;?>"><?php echo $db ;?></li>
        </ul>  
        <p class="login-box-msg">
        <strong>Bravo !! Votre installation est d&eacute;sormais termin&eacute;e.</strong><br/><br/>
        Vous pouvez vous connecter &agrave; l'application. Utilisez les login/mot de passe suivants:
        </p>
        <ul>
        <li>Login : <strong>admin</strong></li>
        <li>Mot de passe : <strong>admin</strong></li>
        </ul>
       </p>
       
        <a href="../index.php">	<button  type="submit" class="btn bg-blue btn-block" /> Accedez &agrave; Cyber-Gestionnaire&nbsp; <i class="fa fa-arrow-circle-right"></i></button></a>
        <br/>
        <p class="login-box-msg">
        Par mesure de s&eacute;curit&eacute; nous vous conseillons de supprimer le dossier install, et de changer le mot de passe du compte "admin"
        </p> 
    
    </div></div>
</body>
</html>
