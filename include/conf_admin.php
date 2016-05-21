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

// Fichier de configuration de l'espace utilisateur
// #############################################################################
// menu niveau 2 : ADMINISTRATEUR
// #############################################################################
if ($_SESSION["status"]==4)
{
  switch ($a)
  {
    case 1:  // ADHERENT
         switch ($b)
         {
             case 1:  // Creation d'un adh&eacute;rent
                  $titre="Cr&eacute;ation d'un adh&eacute;rent" ;
                  $aide="Cr&eacute;ation d'un adh&eacute;rent, n'oubliez pas de pr&eacute;cisez le statut afin de permettre &agrave; l'utilisateur de r&eacute;servez une machine";
                  include ("include/post_user.php");
                  $inc="admin_form_user.php";
             break;
             case 2:  // Modification d'un adh&eacute;rent
                  $titre="Modifier un adh&eacute;rent";
                  include ("include/post_user.php");
                  $inc="admin_form_user.php";
             break;
						case 3:
									$titre="Liste des deniers adh&eacute;rents";
                  $aide="pour une recherche plus rapide vous pouvez utiliser le moteur de recherche, attention 3 lettres minimum sinon pas de recherche !"  ;
                 $inc="admin_list_user.php";
             break;
	     
	     
             default: // Liste des adh&eacute;rents
                  $titre="Liste des adh&eacute;rents";
                  $aide="pour une recherche plus rapide vous pouvez utiliser le moteur de recherche, attention 3 lettres minimum sinon pas de recherche !"  ;
                 
                    $inc="admin_list_fulluser.php";
             break;
         }
    break;
	
    case 2:   // materiel
         switch ($b)
         {
             case 1:  // Creation d'un materiel
                      $titre="Cr&eacute;ation d'un poste";
                      $aide="Cr&eacute;er un poste permet a un adh&eacute;rent de r&eacute;server ce poste si vous cocher la case \"NON (usage interne)\" dans ce cas le poste existe mais uniquement pour les interventions de maintenance de mat&eacute;riel";
                      include ("include/post_materiel.php");
                      $inc="admin_form_materiel.php";
             break;
             case 2:  // Modification d'un materiel
                      $titre="Modification d'un poste";
                      include ("include/post_materiel.php");
                      $inc="admin_form_materiel.php";
             break;
             default: // Liste du materiel
                      $titre="Liste du mat&eacute;riel informatique";
                      $aide="Affichage de tous les postes utilisables par les adh&eacute;rents ainsi que tous les postes internes";
                      include ("include/post_materiel.php");
                      $inc="admin_config_materiel.php";
             break;
         }
    break;
    case 3:    //Intervention
        switch($b)
        {
            case 1: // cr&eacute;ation d'une intervention
                $titre  ="Cr&eacute;er une intervention";
                $aide   ="Cr&eacute;er une intervention permet de signaler un probleme ou une p&eacute;riode de maintenance dans l'espace et ainsi bloquer les reservations de materiel";
                include ("include/post_inter.php");
                $inc    ="admin_form_inter.php";
            break;
            default: // liste des interventions
                $titre  ="Liste des interventions";        
                $aide  = "Consulter la liste des interventions, en gris les interventions termin&eacute;s et en orange les interventions non trait&eacute;es";
                include ("include/post_inter.php");
                $inc= "admin_intervention.php";
            break;
                    
         }
    break;
    case 4:    // breve
         switch ($b)
         {
             case 1:  // Creation d'une breve
                      $titre="Cr&eacute;ation d'une br&egrave;ve";
                      $aide="Communiquez avec vos adh&eacute;rents ou entre animateurs/administrateurs c'est possible il suffit bien cocher la case public ou interne";
                      include ("include/post_breve.php");
                      $inc="admin_form_breve.php";
             break;
             case 2:  // Modification d'une breve
                      $titre="Modification d'une br&egrave;ve";
                      include ("include/post_breve.php");
                      $inc="admin_form_breve.php";
             break;
             default:
                      $titre="Liste des br&egrave;ves";
                      $aide ="Les breves sur fond gris sont visibles par tout le monde, les autres seulement par les administrateurs et animateurs";
                      include ("include/post_breve.php");
                      $inc="admin_breve.php";
             break;
         }
    break;
	
	//** Les statistiques **///
    case 5:
		switch ($b)
         {
			case 1: // statistique utilisateurs
				$titre="Statistiques sur les Adh&eacute;rents";
				include ("fonction_stat.php");
				$inc="admin_stat_user.php";
			break;
			case 2://statistique reservations
				$titre="Statistiques sur les R&eacute;servations";
				include ("fonction_stat.php");
				$inc="admin_stat_resa.php";
			break;
			case 3 : //statistique impressions
				$titre="Statistiques d'Impressions";
				include ("fonction_stat.php");
				$inc="admin_stat_print.php";
			break;
			case 4 : //statistique ateliers
				$titre="Statistiques sur les Ateliers";
				include ("fonction_stat.php");
				$inc="admin_stat_atelier.php";
			break;
			case 5 ://statistique Session
				$titre="Statistiques sur les Sessions";
				include ("fonction_stat.php");
				$inc="admin_stat_session.php";
			break;
			case 6:
				$titre="Historique et statistique atelier d'un adh&eacute;rent";
				include ("fonction_stat.php");
				$inc="admin_user_historique.php";
				
			break;
			
			default:
				$titre="Statistiques globales";
				include ("fonction_stat.php");
				$inc="admin_statistic.php";
			break;
		}
    break;
	
	case 6:
	  $titre="Les transactions de l'adh&eacute;rent";
         $aide="Mouvements archiv&eacute;s sur le compte de l'adh&eacute;rent : impressions, ateliers, achats divers";
        // include("include/post_url.php") ;
         $inc="admin_user_transactions.php";
	break;
	
	//parametrages animateurs influant sur les stats : categories ateliers
	case 7:
	$titre="Param&eacute;trages annexes des ateliers et sessions";
	$inc="admin_config_atelier.php";
	break;
	
	
	///LES abonnements enregistr&eacute;s
	case 8:
	$titre="Liste des adh&eacute;rents avec forfait atelier";
	include ("fonction_stat.php");
	$inc="admin_list_abonnements.php";
	break;
	
	/// Liste des resrvation d'un adh&eacute;rent page statistique + liste
	case 9:
	$titre="Liste des r&eacute;servations d'un adh&eacute;rent ";
	$inc="admin_resa_user.php";
	include("include/fonction_stat.php");
	break;
	
	
	// Les url depos&eacute;es par les adh&eacute;rents
    case 10:
         $titre="Gestion des liens favoris";
         $aide="Ins&eacute;rer des liens utiles &agrave; vos adh&eacute;rents";
         include("include/post_url.php") ;
         $inc="admin_url.php";
    break;
	
	
	//*** GESTION DES ATELIERS **///
	case 11:
         $titre="Liste des ateliers";
         $aide="Creer, et administrer des ateliers de formations pour vos adh&eacute;rents";
         include ("include/post_atelier.php");
		 $inc="admin_atelier_liste.php";
    break;
	 case 12:
         $titre="Planification d'un atelier";
         $aide="Creer, et administrer des ateliers de formations pour vos adh&eacute;rents";
         include ("include/post_atelier.php");
         $inc="admin_form_atelier.php";
    break;
    case 13:
         $titre="D&eacute;tail d'un atelier";
         $aide="G&eacute;rer les participants &agrave; l'atelier";
          include ("include/post_atelier_presence.php");
		  $inc="admin_atelier.php";
    break;
   
   	case 14:
         $titre="Modification la planification d'un atelier";
         $aide="Modifier des ateliers de formations pour vos adh&eacute;rents";
         include ("include/post_atelier.php");
         $inc="admin_form_atelier.php";
    break;
	case 15:
	     $titre="Cr&eacute;ation d'un sujet d'atelier";
         $aide="Cr&eacute des ateliers de formations pour vos adh&eacute;rents";
         include ("include/post_sujetatelier.php");
         $inc="admin_form_sujet_atelier.php";
    break;
	case 16:
         $titre="Gestion des pr&eacute;sences &agrave; un atelier";
         $aide="gestion des ateliers de formations";
		 include ("include/post_atelier_presence.php");
         $inc="admin_atelier_presence.php";
		
    break;
	
	case 17: 
			$titre="Modification d'un sujet d'atelier ";
			$aide="Modification des intitul&eacute;s";
			$inc="admin_atelier_modif.php";
			include ("include/post_sujetatelier.php");
	break;
		
	// les archives
	case 18:
		$titre="Archives des Ateliers";
         $aide="Archives des Ateliers";
         $inc="admin_atelier_archive.php";
		 include ("include/fonction_stat.php");
	break;
		
	case 19:
		$titre="Ajout de r&eacute;servation pass&eacute;es";
         $aide="Ajoutez une r&eacute;servation pass&eacute;e rapidement dans la base";
         $inc="admin_resa_ajout.php";
		 include ("include/post_reservation-rapide.php");
	break;
	
	
	case 20:
         $titre="Calendrier des ateliers";
         $aide="";
         $inc="atelier_calendar.php";
    break;
	
	//ajout des impressions
	case 21:
		switch($b)
			{
			case 1: // D&eacute;tail Compte d'imression d'un adh&eacute;rent
				$titre  ="Historique d'impression de l'adh&eacute;rent";
				$aide   ="Historique des impressions d'un adh&eacute;rent, mettre de l'argent sur un compte adh&eacute;rent";
				// include ("include/post_print.php");	
				$inc    ="admin_print.php";
			    break;
			case 2:  // cr&eacute;diter d'un compte d'impression
				$titre="Cr&eacute;diter ou d&eacute;biter d'un compte d'impression";
				$aide="Modifier les entr&eacute;es d'un cr&eacute;dit ou d&eacute;bit.";
			    include ("include/post_print.php");
				$inc="admin_form_print.php";
			break;
			case 3:
			     $titre="modifier une transaction";
			      $aide="Transactions diverses";
			     include ("include/post_transac.php");
			     $inc="admin_modif_transac.php";
			break;
			     
			 default: // liste des impressions
				$titre="Comptes d'impressions";
				$aide="G&eacute;rer les impressions des adh&eacute;rents";
				
				$inc="admin_liste_print.php";
			    break;
			 }
	break;
	
	
	
	//Gestion anim & admin
	 case 23:
         $titre="Administrateurs et des animateurs";
         $aide="Cr&eacute;er, g&eacute;rer vos administrateurs et les animateurs de l'espace";
         $inc="admin_config_anim.php";
    break;
    
     case 24:  // PRE-INSCRIPTION
         switch ($b)
         {
             case 1:  // Creation d'un adh&eacute;rent
                  $titre="Validation d'une pr&eacute;-inscription" ;
                  $aide="Cr&eacute;ation d'un adh&eacute;rent, n'oubliez pas de pr&eacute;cisez le statut afin de permettre a l'utilisateur de r&eacute;servez une machine";
                  include ("include/post_inscription.php");
                  $inc="admin_form_inscription.php";
             break;
             default: // Liste des pr&eacute;inscription
                  $titre="Liste des pr&eacute;-inscriptions";
                  $aide="pour une recherche plus rapide vous pouvez utiliser le moteur de recherche, attention 3 lettres minimum sinon pas de recherche !"  ;
                  $inc="admin_inscription.php";
             break;
         }
    break;
    
    //**Parametrages EPNConnect **///
    
     case 25:
         include ("include/post_config_epnconnect.php");
         $titre="Configuration d'EPN-Connect ";
         $aide="Permet de modifier les param&egrave;tres de l'application EPN-Connect";
         $inc="admin_config_epnconnect.php";
    break;
		
	//**GESTION DES SESSIONS **///
	case 30:
         $titre="D&eacute;tail d'une session";
         $aide="gestion des inscriptions aux sessions";
         $inc="admin_session.php";
	include ("include/post_session.php");
	break;
	
	case 31:
         $titre="Planifier une session";
         $aide="Planifier une session";
         $inc="admin_form_session.php";
		include ("include/post_session.php");
		break;	
	case 32:
         $titre="Validation des pr&eacute;sences &agrave; une session";
         $aide="gestion des sessions de formations";
         $inc="admin_session_presence.php";
	 include("include/post_session_presence.php");
	break;	
    
    //courriers
	case 33:
		
			case 1:
         $titre="Gestion des courriers";
         $aide="Gestion des courriers pour les adh&eacute;rents";
         $inc="atelier_courrier.php";
      
    break;
    
    //suite sessions
	    case 34:
		 $titre="cr&eacute;ation des sujets des sessions";
		 $aide="Les sujets des sessions";
		 $inc="admin_form_sujet_session.php";
		include ("include/post_sujetsession.php");
	    break;	
	case 35:
		$titre="Modification du sujet d'une session ";
		$aide="Modification des intitul&eacute;s";
		$inc="admin_session_modif.php";
		include ("include/post_sujetsession.php");
				
	    break;	
	
	case 36:
         $titre="Archives des sessions";
         $aide="Consulter les sessions archiv&eacute;es";
         $inc="admin_session_archive.php";
		 include("include/fonction_stat.php");
	break;	
	
	case 37:
	  $titre="Liste des sessions programm&eacute;es";
         $aide="Consulter les sessions ";
         $inc="admin_session_list.php";
		 include ("include/post_session.php");
	 break;
	
	//**GESTION DES CONFIGURATIONS **///
	
	//villes
    case 41:
         $titre="Gestion des villes";
         $aide ="Si vous souhaitez ajouter modifier ou supprimer une ville dans les adh&eacute;rents c'est ici que ca se passe";
         $inc="admin_config_city.php";
    break;
	
    // Horaire de l'epn principal //
    case 42:
         include ("include/post_config_horaires.php");
         $titre="Configuration de cybermin et de votre espace ";
         $aide="Permet de modifier les param&egrave;tres de l'application Cybermin";
         $inc="admin_config_horaires.php";
    break;
	//EPN
	case 43:
		switch ($b)
         {
             case 1:  // Creation d'un EPN
                      $titre="Cr&eacute;ation d'un EPN";
                      $aide="";
                      include ("include/post_epn.php");
                      $inc="admin_form_epn.php";
             break;
             case 2:  // Modification d'un EPN
                      $titre="Modification d'un EPN";
                      include ("include/post_epn.php");
                      $inc="admin_form_epn.php";
             break;
			
						case 4: //modification du reseau
							$titre="Modification du r&eacute;seau";
								$aide="";
								include ("include/post_epn.php");
								$inc="admin_form_reseau.php";
							break;
				
             default: // Liste des EPN
                      $titre="Liste des EPN";
                      $aide="Affichage de tous les salles";
                      include ("include/post_epn.php");
                      $inc="admin_config_epn.php";
             break;
         };
	 break;
	 
	case 44: // SALLES
		switch ($b)
			{
				 case 1:  // Creation d'une salle
						  $titre="Cr&eacute;ation d'une salle";
						  $aide="";
						  include ("include/post_salle.php");
						  $inc="admin_form_salle.php";
				 break;
				 case 2:  // Modification d'une salle
						  $titre="Modification d'une salle";
						  include ("include/post_salle.php");
						  $inc="admin_form_salle.php";
				 break;
				 default: // Liste des salles
						  $titre="Liste des salles";
						  $aide="Affichage de tous les salles";
						  include ("include/post_salle.php");
						  $inc="admin_config_salle.php";
				 break;
			}
	 break;
	 
	 case 45:
         $titre="Console";
         $aide="G&eacute;rer vos postes";
       // include ("include/console.php");
         $inc="admin_console.php";
    break;
	 case 46:
         $titre="Usages disponibles pour les Adh&eacute;rents";
         $aide="Vous pouvez g&eacute;rer les usages des postes tel que l'acces Internet, la gravure, la possibilit&eacute; d'imprimer, une webcam, etc. ";
         $inc="admin_config_usage.php";
    break;
    
	 case 47 :
		$titre="Gestion des tarifs";
		$aide="Remplissez les tarifs proatiqu&eacute;s dans vos espaces";
		$inc="admin_config_tarifs.php";
		 include ("include/post_tarifs.php");
	break;
	
	 case 48:
         $titre="Utilisation disponibles pour les postes";
         $aide="Vous pouvez g&eacute;rer les utilisations des postes tel que l'acces Internet, la gravure, la possibilit&eacute; d'imprimer, une webcam, etc. ";
         $inc="admin_config_utilisation.php";
    break;
    
	 
	case 49:
         $titre="Gestion de la base de donn&eacute;es";
         $aide="N'oubliez de sauvegarder r&eacute;gulieremnt vos donn&eacute;es car on n'est jamais a l'abris d'une mauvaise manip. ou d'un crash machine.<br>Pour restaurer merci de vous adresser a votre responsable technique";
         include ("include/class/class_db.php");
         include ("include/post_bdd.php");
         $inc="admin_bdd.php";
    break;
	
	
	case 50:
	$titre="Gestion des profils animateurs";
	$aide="indiquez l'epn de rattachement de l'animateur et ses salles";
	$inc="admin_form_configanim.php";
	include ("include/post_animateurs.php");
    break;
    
    case 51: //cr&eacute;ation d'un animateur form simplifi&eacute; // modification des profils anim
	switch ($b)
	{
		case 1:
		 $titre="Cr&eacute;ation d'un animateur" ;
                  $aide="Cr&eacute;ation d'un animateur";
                  include ("include/post_user.php");
                  $inc="admin_form_useranim.php";
             break;
		case 2:
		$titre="Modification d'un animateur" ;
                  $aide="Modification d'un animateur";
                  include ("include/post_user.php");
                  $inc="admin_form_useranim.php";
             break;
	     
	}
	break;
	
	//gestion des courriers aux adh&eacute;rents dispo anim aussi
	case 52:
	switch($b)
			{
				case 1: //cr&eacute;ation
				$titre="Gestion des courriers";
				$aide="Remplissez les textes de vos envois de courriers";
				include("include/post_courrier.php");
				$inc="admin_form_courrier.php";
				break;
				
				case 2: //modification
				$titre="Gestion des courriers";
				$aide="Remplissez les textes de vos envois de courriers";
				include("include/post_courrier.php");
				$inc="admin_form_courrier.php";
				break;
				
			default :
				$titre="Gestion des courriers";
				$aide="G&eacute;rez les textes de vos envois de courriers";
			include("include/post_courrier.php");
				$inc="admin_courrier.php";
				break;
			}
		
	break;
	
	case 53:
		$titre="Gestion des pr&eacute;inscriptions";
				$aide="";
				//include("include/post_courrier.php");
				$inc="admin_config_captcha.php";
		break;
	
	
	
 //credits et remerciements !
  case 60:
 $titre="Cr&eacute;dits d&eacute;veloppement et remerciement";
 $aide="";
 $inc="credits.php";
 break;

//mises &agrave; jour
case 61:
$titre="Mise &agrave; jour de version";
 include ("fonction_maj.php");
  
include ("include/class/backup.php");
 $inc="miseajour.php";
break;


//Sauvegarde de la base
case 62:
$titre="Sauvegarde de la base de donn&eacute;es CyberGestionnaire";
 include ("fonction_maj.php");
include ("include/class/backup.php");
 $inc="bdd_save.php";
break;
	

  }
 
  
}
?>
