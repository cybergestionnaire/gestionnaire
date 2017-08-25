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
 2012 florence DAUVERGNE

*/

// Page d'accueil sur le compte animateur ou administrateur

    require_once("include/class/Resa.class.php");
    require_once("include/class/Atelier.class.php");
    require_once("include/class/SessionDate.class.php");

    // admin --- Utilisateur
    include("post_reservation-rapide.php");
    include("fonction_stat.php");

    function cmp($a, $b) {
        return strtotime($a->getDate()) - strtotime($b->getDate());
    }
    
    $term   = isset($_POST["term"]) ? $_POST["term"] : '';
    $mesno  = isset($_GET["mesno"]) ? $_GET["mesno"] : '';

    //Tous les utilisateurs, inscription de la connexion dans la tab_connexion(user,date,type=1=login,MACADRESS,Navigateur, System)
    $exploitation = operating_system_detection();
    $ua           = getBrowser();
    $navig        = $ua['name'] . " " . $ua['version'] ;
    $macadress    = "inconnue pour l\'instant";
    $cx           = enterConnexionstatus($_SESSION['iduser'], date('Y-m-d H:i:s'), 1, $macadress, $navig, $exploitation);

     //***** -------------fonctions pour administrateur & animateurs   
    if ($_SESSION["status"] == "3" OR $_SESSION["status"] == "4") {
        
        if ($mesno != "") {
            echo getError($mesno);
        }

?>

<div class="row">

<?php
        //*****   Mises &agrave; jour des adh&eacute;rents dont le forfait arrive a expiration ///
        include("boites/MAJ_adherents.php"); 

        //***** Fonctions administrateur ONLY MAJ + Backup *****///
        if ($_SESSION["status"] == "4") {
            include("boites/MAJ_version.php");
            include("boites/verifBackup.php");
        }
        //***** FIN Fonctions administrateur MAJ + Backup *****///
     
     
        //debug($_session["idepn"]);
?>
</div>
 
 <!-- Info boxes Statistiques-->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua" style="padding-top:18px"><i class="ion ion-ios-time"></i></span>
            <div class="info-box-content">
<?php 
        $rowresastatmois = Resa::getStatResaParMois(date('m'), date('Y'), $_SESSION["idepn"]);
        $resamois        = $rowresastatmois["nombre"];
        
        $datehier        = date('Y-m') . "-" . (date('d') - 1);
        $rowresahier     = Resa::getStatResaParJour($datehier, $_SESSION["idepn"]);
        $resahier        = $rowresahier["nombre"] . " (" . getTime($rowresahier["duree"]) . ")";
    
?>
                <span class="info-box-text">R&eacute;servations</span>
                <span class="info-box-number"><?php echo $resahier; ?><small> hier</small><br><?php echo $resamois; ?><small> ce mois</small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red" style="padding-top:18px"><i class="fa ion-university"></i></span>
            <div class="info-box-content">
<?php 
        $rownbateliersstat = getStatAtelierByMonth(date('Y'),date('m'),1,1);
        $nbateliersstat    = $rownbateliersstat["atelier"];
        $nbsessionstat     = getSessionbyMonth(date('Y'),date('m'));
?>
                <span class="info-box-text">Ateliers programm&eacute;s<br>(ce mois)</span>
                <span class="info-box-number"><?php echo $nbateliersstat; ?> <small>Ateliers</small>  /<?php echo $nbsessionstat; ?> <small>Sessions</small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green" style="padding-top:18px"><i class="ion ion-printer"></i></span>
            <div class="info-box-content">
<?php
        $rowstatimpression = getStatPages(date('m'),date('Y'),$_SESSION["idepn"]);
        $pages             = $rowstatimpression["pages"];
        $montant           = $rowstatimpression["montant"];
?>
                <span class="info-box-text">Impressions</span>
                <span class="info-box-number"><?php echo $pages; ?> pages (<?php echo $montant; ?> &euro;)</span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow" style="padding-top:18px"><i class="ion ion-ios-people"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Nouveaux membres<br>(Mois en cours)</span>
                <span class="info-box-number"><?php echo getNewMemberNum(); ?></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div><!-- /.col -->
</div><!-- /.row -->
 
 
<div class="row">
    <div class="col-md-6"> <!-- colonne 1-->
    <!-- DIV TIMELINE evenements de la semaine -->
        <div class="box">
            <div class="box-header"><h3 class="box-title">Au programme cette semaine &agrave; l'EPN</h3></div>
            <div class="box-body">
<?php
        $listeAteliers     = Atelier::getAteliersParSemaine(date('Y-m-d'), $_SESSION["idepn"]);
        $listeSessionDates = SessionDate::getSessionDatesParSemaine(date('Y-m-d'), $_SESSION["idepn"]);
        $listeGlobale      = array_merge($listeAteliers, $listeSessionDates );
        
        

        
        if (count($listeGlobale) > 0 ) { ?>
                <!-- The time line --> 
                <ul class="timeline">

<?php
            // tri des ateliers et des sessions en fonction de leur date
            // error_log(print_r($listeGlobale, true));
            

            usort($listeGlobale, 'cmp');
            // error_log(print_r($listeGlobale, true));
            foreach ($listeGlobale as $AS) {
                if ($AS instanceof Atelier) {
                    // error_log("Atelier à la date : " . $AS->getDate());
                    $titre    = $AS->getSujet()->getLabel();
                    $inscrits = $AS->getNbUtilisateursInscrits();
                    $salle    = $AS->getSalle();
                    $duree    = $AS->getDuree();
                    $anim     = $AS->getAnimateur();
                    $urlAS    = "index.php?a=13&b=1&idatelier=" . $AS->getId();
                    $type     = "Atelier";
                    $class    = "bg-green";
                } else {
                    // error_log("Session à la date : " . $AS->getDate());
                    $session  = $AS->getSession();
                    $titre    = $session->getSessionSujet()->getTitre();
                    $inscrits = $session->getNbUtilisateursInscrits();
                    $salle    = $session->getSalle();
                    $duree    = '60';    //TODO : rendre la duree des sessions configurables
                    $anim     = $session->getAnimateur();
                    $urlAS    = "index.php?a=30&b=1&idsession=" . $session->getId();
                    $type     = "Session";
                    $class    = "bg-blue";
                }
        
?>
                    <li class="time-label">
                        <span class="bg-red">&nbsp;<?php echo getDateFr($AS->getDate()); ?>&nbsp;&nbsp;<i class="fa fa-clock-o"></i></span>
                    </li><!-- /.timeline-label -->
                    <li>
                        <i class="fa fa-keyboard-o <?php echo $class ?>"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header"><?php echo htmlentities($type . " : " . $titre); ?></h3>
                            <div class="timeline-body">
                                <small class="badge bg-purple"><?php echo $inscrits; ?></small>&nbsp;&nbsp;Participants enregistr&eacute;s<br>
                                <small class="badge bg-purple"><i class="fa fa-map-marker"></i></small>&nbsp;&nbsp;<?php echo htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() .")") ; ?><br>
                                <small class="badge bg-purple"><i class="fa fa-hourglass"></i></small>&nbsp;&nbsp;<?php echo $duree; ?> min<br>
                                <small class="badge bg-purple"><i class="fa fa-user"></i></small>&nbsp;&nbsp;<?php echo htmlentities($anim->getPrenom() . " " . $anim->getNom()); ?>
                            </div>
                            <div class='timeline-footer'>
                                <a href="<?php echo $urlAS; ?>" class="btn btn-success btn-xs">Voir le d&eacute;tail</a>
                            </div>
                        </div>
                    </li> 
<?php 
            }
?>
                    
                    
                    
                    <li><i class="fa fa-clock-o"></i></li>
                </ul>
                
<?php
        } else {
            echo "<p>aucun &eacute;v&eacute;nement enregistr&eacute; pour cette semaine !</p>"; 
        }
?>
            </div><!-- .box-body -->
        </div><!-- .box -->
        <div class="box">
            <div class="box-header"><h3 class="box-title">Journal des connexions</h3></div>
            <div class="box-body">
<?php
        $temps = 0;
        $resasActives = Resa::getResasActives();
        if ($resasActives !== null) {
?>
                <h4>Connexions en cours</h4>
                <ul>
                
<?php
            foreach($resasActives as $resa) {
                $utilisateur = $resa->getUtilisateur();
                $materiel = $resa->getMateriel();
                $salle = $materiel->getSalle();
                $espace = $salle->getEspace();
                
                $heure     = floor($resa->getDebut() / 60);
                $minute    = $resa->getDebut() - $heure * 60;
                if ($minute < 10) {
                    $temp = $resa->getDate() . " " . $heure . ":0" . $minute;
                } else {
                    $temp = $resa->getDate() . " " . $heure . ":" . $minute;
                }                                
                $dateresa      = date_create_from_format("Y-m-d H:i",$temp);
                $diff          = time() - date_timestamp_get($dateresa); // difference en secondes
                $now           = new DateTime();
                $interval      = date_diff($dateresa, $now);
                $time          = $interval->format("%d j %hh%im");                       
                if ($diff < 60) {
                    $time = "<1mm" ;
                }

                
                
?>              
                <li><a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>"><?php echo htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom())?></a> depuis <?php echo $time ?><br /> sur <?php echo htmlentities($espace->getNom() . " / " . $salle->getNom() . " / " . $materiel->getNom()); ?></li>
<?php                
                
            }
?>
                </ul>
<?php
        }
?>
<?php
        $resasTerminees = Resa::getResasDuJour();
        if ($resasTerminees !== null) {
?>
                <h4>Connexions terminées aujourd'hui</h4>
                <ul>
                
<?php
            foreach($resasTerminees as $resa) {
                $utilisateur = $resa->getUtilisateur();
                $materiel = $resa->getMateriel();
                $salle = $materiel->getSalle();
                $espace = $salle->getEspace();
                
                $heure     = floor($resa->getDebut() / 60);
                $minute    = $resa->getDebut() - $heure * 60;
                if ($minute < 10) {
                    $temp = $heure . ":0" . $minute;
                } else {
                    $temp = $heure . ":" . $minute;
                }                                
                
?>              
                <li><a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>"><?php echo htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom())?></a> commenc&eacute;e &agrave; <?php echo $temp ?>, dur&eacute;e <?php echo $resa->getDuree() ?>mn<br /> sur <?php echo htmlentities($espace->getNom() . " / " . $salle->getNom() . " / " . $materiel->getNom()); ?></li>
<?php                
            }
?>
                </ul>
<?php
        }
?>          </div>
        </div>
    </div><!-- /colonne 1 -->

    <div class="col-md-6"> <!-- colonne 2-->

  
        <!-- message board -->
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <i class="fa fa-comments-o"></i><h3 class="box-title">Messages adh&eacute;rents</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="direct-chat-messages">
                <!-- chat item -->
           
<?php
        if ($_SESSION["status"] == 4) {
            $listeMessage = readMessage(); //tous les messages pour l'admin
        }
        else if ($_SESSION["status"] == 3){
            $listeMessage = readMyMessage($_SESSION["iduser"]); //messages pour les anims
        }
        $nb = mysqli_num_rows($listeMessage);
        $urlRedirect = "index.php";

        for ($i = 1 ; $i <= $nb ; $i++) {
            $rowmessage = mysqli_fetch_array($listeMessage) ;
            $auteur     = $rowmessage["mes_auteur"];
            $rowdest    = getUser($rowmessage["mes_destinataire"]);
            $rowauteur  = getUser($rowmessage["mes_auteur"]);
            $nomessage  = $rowauteur['prenom_user'] . " " . $rowauteur['nom_user'];
            
            if ($auteur == $_SESSION["iduser"]) {
                $classchat1  = "direct-chat-msg right";
                $classchat2  = 'direct-chat-name pull-right';
                $classchat3  = 'direct-chat-timestamp pull-left';
                $rowa        = getAvatar($_SESSION["iduser"]);
                $photoavatar ="img/avatar/".$rowa["anim_avatar"];
            } else {
                //reponse &agrave; droite
                $classchat1    = "direct-chat-msg";
                $classchat2    = 'direct-chat-name pull-left';
                $classchat3    = 'direct-chat-timestamp pull-right';
                $filenamephoto = "img/photos_profil/" . trim($rowauteur["nom_user"]) . "_" . trim($rowauteur["prenom_user"]) . ".jpg" ;
                if (file_exists($filenamephoto)) {
                    $photoavatar = $filenamephoto;
                } else {
                    if ($rowauteur["sexe_user"] == 'M') {
                        $photoavatar = "img/avatar/male.png";
                    } else {
                        $photoavatar = "img/avatar/female.png";
                    }
                }
            }
            
            $datemes = date_format(date_create($rowmessage['mes_date']), '\l\e d/m/y \&agrave; G:i ');
?>
        
                    <div class="<?php echo $classchat1; ?>">
                        <div class='direct-chat-info clearfix'>
                            <span class='<?php echo $classchat2; ?>'><?php echo $nomessage ;?></span>
                            <span class='<?php echo $classchat3; ?>'><?php echo $datemes;?> pour <?php echo $rowdest["nom_user"] . " " . $rowdest["prenom_user"]; ?> </span>
                        </div>
               
                        <img src="<?php echo $photoavatar; ?>"  class="direct-chat-img" />
                        <div class="direct-chat-text"><?php echo stripslashes($rowmessage['mes_txt']);?></div>
                    </div>
              
<?php 
        }
?>      
           
                </div><!-- /.chat -->
            </div><!-- .box-body -->
            <div class="box-footer">
                <form method="post" action="<?php echo $urlRedirect; ?>">
                    <div class="input-group">
                        <label>R&eacute;pondre A :</label>
                        <select name="chatdestinataire" class="form-control pull-right">
<?php
        if($_SESSION["status"] == 3) {
            $listeAdhreponse = getListReponse($_SESSION["iduser"]);
        } else {
            $listeAdhreponse = getListRepAdmin();
        }
            
        foreach ($listeAdhreponse AS $key=>$value) {
            // if ($adhreponse == $key) {
                // echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
            // }
            // else {
                 echo "<option value=\"" . $key . "\">" . $value . "</option>";
            // }
        }
        
?>
                        </select>
                    </div>
                    <div class="input-group">
                        <input value="test" type="hidden" name="tags_message">
                        <input value="<?php echo date('Y-m-d H:i:s'); ?>" type="hidden" name="chatdate">
                        <input value="<?php echo $_SESSION['iduser']; ?>" type="hidden" name="chatadh"> 
                        <input type="text" name="chattxt_message" class="form-control" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;"/>  
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-flat" type="submit" value="message_submit" name="message_submit"><i class="fa fa-paper-plane"></i>&nbsp;</button>
                        </div>
                    </div><!-- /.input group -->
                </form>
            </div><!-- /.box footer -->  
        </div><!-- /.box (chat box) -->

        <!-- RACCOURCIS RAPIDES -->
        <div class="box">
            <div class="box-header"><h3 class="box-title">Raccourcis</h3></div>
            <div class="box-body">
                <a class="btn btn-app" href="index.php?a=1"><i class="fa fa-group"></i>Adh&eacute;rents</a>
                <a class="btn btn-app" href="index.php?a=1&b=1"><i class="fa fa-user"></i>+ Adh&eacute;rent</a>
                <a class="btn btn-app" href="index.php?a=11"><i class="fa fa-keyboard-o"></i>Ateliers</a>
                <a class="btn btn-app" href="index.php?a=37"><i class="fa fa-ticket"></i>Sessions</a>
                <a class="btn btn-app" href="index.php?a=21"><i class="fa fa-print"></i>Impressions</a>
<?php
        if ($_SESSION["status"] == 4) {
            echo '<a class="btn btn-app" href="index.php?a=41"><i class="fa fa-gear"></i>Configuration</a>';
        }
?>
            </div>
        </div><!-- .box -->
    </div><!-- /colonne 2-->
    
</div><!-- /row -->

  


<!-- LES BREVES --->

<div class="row"> 
    <div class="col-md-8">


<?php

        //affichage breve admin anim
        $result = getAllBreve(0);  
        if ($result == FALSE) {
            echo getError(0);
        } else {
            $nb = mysqli_num_rows($result);
            if ($nb == 0) {
                //echo getError(10);
            } else {
                for ($i = 1 ; $i <= $nb ; $i++) {
                    $row = mysqli_fetch_array($result);
?>
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title"><?php echo $row["titre_news"] ?></h3></div>
            <div class="box-body">
                <p><?php echo $row["comment_news"] ?></p>
            </div>
        </div>
<?php
                }
            }
        }
?>
    </div>
</div>
<?php
    // *********************Page d'accueil sur le compte utilisateur
    //if ligne 49 !
    } else {

        //si inscrit -->vos prochains ateliers
        // bouton faire une reservation de postes
        // bouton consulter mon historique de resa
        // bouton consulter mon historique des ateliers
        // bouton envoyer message &agrave; animateur

        //charger la liste des evenements du reseau

        $arrayinscrip   = array(
                                2=>"Sur la liste d'attente",
                                0=>"Je suis d&eacute;j&agrave; inscrit"
                            );
        $arrayClass     = array(
                                2=>"bg-",
                                0=>"Je suis d&eacute;j&agrave; inscrit"
                            );

        $listeAteliers     = Atelier::getAteliersParSemaine(date('Y-m-d'), 0);
        $listeSessionDates = SessionDate::getSessionDatesParSemaine(date('Y-m-d'), $_SESSION["idepn"]);
        $listeGlobale      = array_merge($listeAteliers, $listeSessionDates );
                            
        usort($listeGlobale, 'cmp');                            
    //****UTILISATEUR ACTIF
 
        if ($_SESSION["status"] == "1") {
    
?>
<div class="row">
    <div class="col-md-4"> <!-- colonne 1-->
        <!-- DIV TIMELINE evenements de la semaine -->
        <div class="box">
            <div class="box-header"><h3 class="box-title">Au programme cette semaine &agrave; l'EPN</h3></div>
            <div class="box-body">
<?php

        if (count($listeGlobale) > 0 ) {
            foreach ($listeGlobale as $AS) {
                if ($AS instanceof Atelier) {
                    // error_log("Atelier à la date : " . $AS->getDate());
                    $titre    = $AS->getSujet()->getLabel();
                    $inscrits = $AS->getNbUtilisateursInscrits();
                    $salle    = $AS->getSalle();
                    $duree    = getTime($AS->getDuree());
                    $anim     = $AS->getAnimateur();
                    $urlAS    = "index.php?a=13&b=1&idatelier=" . $AS->getId();
                    $type     = "Atelier";
                    $class    = "bg-green";

                    if ($AS->isUtilisateurInscrit($_SESSION["iduser"])) {
                        $statut      = $AS->getStatutUtilisateur($_SESSION["iduser"]);
                        $urlAS       = "#";
                        $boutoninscr = $arrayinscrip[$statut];
                        $couleurb    = "btn btn-success btn-xs";    
                        if ($statut == 0) {
                            $couleurb    = "btn btn-success btn-xs";
                        } else {
                            $couleurb    = "btn btn-warning btn-xs";
                        }                        
                    } else {
                        $urlAS       = "index.php?m=6&b=1&idatelier=" . $AS->getId();
                        $boutoninscr = "s'inscrire";
                        $couleurb    = "btn btn-info btn-xs";
                    }
                } else {
                    // error_log("Session à la date : " . $AS->getDate());
                    $session  = $AS->getSession();
                    $titre    = $session->getSessionSujet()->getTitre();
                    $inscrits = $session->getNbUtilisateursInscrits();
                    $salle    = $session->getSalle();
                    $duree    = getTime('60');    //TODO : rendre la duree des sessions configurables
                    $anim     = $session->getAnimateur();
                    $urlAS    = "index.php?a=30&b=1&idsession=" . $session->getId();
                    $type     = "Session";
                    $class    = "bg-blue";
                    if ($AS->isUtilisateurInscrit($_SESSION["iduser"])) {
                        $statut      = $session->getStatutUtilisateur($_SESSION["iduser"]);
                        $urlAS       = "#";
                        $boutoninscr = $arrayinscrip[$statut];
                        if ($statut == 0) {
                            $couleurb    = "btn btn-success btn-xs";
                        } else {
                            $couleurb    = "btn btn-warning btn-xs";
                        }
                        
                    } else {
                        $urlAS       = "index.php?m=6&b=5&idsession=" . $session->getId();
                        $boutoninscr = "s'inscrire";
                        $couleurb    = "btn btn-info btn-xs";
                    }
                }
               
?>
                            <!-- timeline time label -->
                <ul class="timeline">
                    <li class="time-label"><span class="bg-red"> <?php echo getDateFr($AS->getDate()); ?></span></li><!-- /.timeline-label -->
                    <li>
                        <i class="fa fa-keyboard-o <?php echo $class?>"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header"><?php echo htmlentities($type . " : " . $titre); ?></h3>
                                
                            <div class="timeline-body">
                                <small class="badge bg-purple"><?php echo $inscrits; ?></small> participants enregistr&eacute;s<br>
                                <small class="badge bg-purple"><i class="fa fa-map-marker"></i></small> <?php echo htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() .")") ; ?><br>
                                <small class="badge bg-purple"><i class="fa fa-clock-o"></i></small> <?php echo $duree; ?><br>
                                <small class="badge bg-purple"><i class="fa fa-user"></i></small> Anim&eacute; par <?php echo htmlentities($anim->getPrenom() . " " . $anim->getNom()); ?>
                            </div>
                            <div class='timeline-footer'><a href="<?php echo $urlAS; ?>" class="<?php echo $couleurb;?>"><?php echo $boutoninscr; ?></a></div>
                        </div>
                    </li>
                    
<?php
                }
?>
                                            
                    <li><i class="fa fa-clock-o"></i></li>
                </ul>
<?php 
            } else {
                echo "<p>aucun &eacute;v&eacute;nement enregistr&eacute; pour cette semaine !</p>";
            }
?>
            </div><!-- .box-body -->
        </div><!-- .box -->
    </div><!-- /colonne 1 -->

    <div class="col-md-4"> <!-- colonne 2-->
<?php
            $result = getAllBreve(1);  
            if ($result == FALSE) {
                echo getError(0);
            } else {
                $nb = mysqli_num_rows($result);
                if ($nb == 0) {
                    echo getError(10);
                } else {
                    for ($i = 1 ; $i <= $nb ; $i++) {
                        $row = mysqli_fetch_array($result);
?>
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title"><?php echo $row["titre_news"] ?></h3></div>
            <div class="box-body">
                <p><?php $row["comment_news"] ?>"</p>
            </div>
        </div>
<?php 
                    }
                }
            }
?>
    
        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Acc&eacute;der &agrave; vos historiques</h3></div>
            <div class="box-body">
            <!--    <a class="btn btn-block btn-social bg-green" href="index.php?m=6"><i class="fa fa-graduation-cap"></i> Voir ma participation aux ateliers</a>-->
                <a class="btn btn-block btn-social btn-tumblr" href="index.php?m=20"><i class="fa fa-print"></i> Voir mes impressions</a>
                <a class="btn btn-block btn-social btn-foursquare" href="index.php?m=8"><i class="fa fa-clock-o"></i> Voir mes r&eacute;servations</a>
            </div>
  
        </div>
    </div><!-- /col -->
    <div class="col-md-4"> <!-- colonne 3-->
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <i class="fa fa-comments-o"></i><h3 class="box-title">Messages aux animateurs</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="direct-chat-messages">
                    <!-- chat item -->
<?php
            $animateurs   = Utilisateur::getAnimateurs();
            $listeMessage = readMyMessage($_SESSION["iduser"]);
            $nb           = mysqli_num_rows($listeMessage);
            $urlRedirect  = "index.php";
            
            for ($i = 0; $i < $nb ; $i++){
                $rowmessage = mysqli_fetch_array($listeMessage) ;
                $auteur     = $rowmessage["mes_auteur"];
                $rowdest    = getUser($rowmessage["mes_destinataire"]);
                $rowauteur  = getUser($rowmessage["mes_auteur"]);
                $nomessage  = $rowauteur['prenom_user']." ".$rowauteur['nom_user'];
                
                if ($auteur == $_SESSION["iduser"]) {
                    $classchat1    = "direct-chat-msg right";
                    $classchat2    = 'direct-chat-name pull-right';
                    $classchat3    = 'direct-chat-timestamp pull-left';
                    $filenamephoto = "img/photos_profil/" . trim($rowauteur["nom_user"]) . "_" . trim($rowauteur["prenom_user"]) . ".jpg" ;
                    if (file_exists($filenamephoto)) {
                        $photoavatar = $filenamephoto;
                    } else {
                        if ($rowauteur["sexe_user"] == 'M') {
                            $photoavatar = "img/avatar/male.png";
                        } else {
                            $photoavatar = "img/avatar/female.png";
                        }
                    }
                    
                } else {
                    //reponse &agrave; droite
                    $classchat1  = "direct-chat-msg";
                    $classchat2  = 'direct-chat-name pull-left';
                    $classchat3  = 'direct-chat-timestamp pull-right';
                    $rowa        = getAvatar($auteur);
                    $photoavatar ="img/avatar/".$rowa["anim_avatar"];
                }
                
                $datemes = date_format(date_create($rowmessage['mes_date']), '\l\e d/m/y \&agrave; G:i ');
?>
        
                    <div class="<?php echo $classchat1; ?>">
                        <div class='direct-chat-info clearfix'>
                            <span class='<?php echo $classchat2; ?>'><?php echo $nomessage ;?></span>
                            <span class='<?php echo $classchat3; ?>'><?php echo $datemes;?> pour <?php echo $rowdest["nom_user"] . " " . $rowdest["prenom_user"]; ?></span>
                        </div>
           
                        <img src="<?php echo $photoavatar; ?>"  class="direct-chat-img" />
                        <div class="direct-chat-text"><?php echo stripslashes($rowmessage['mes_txt']);?></div>
                    </div>
<?php
        }
?>      
       
                </div><!-- /.chat -->
            </div><!-- .box-body -->
            <div class="box-footer">
                <form method="post" action="<?php echo $urlRedirect; ?>">
                    <div class="input-group">
                        <label>A :
                            <select name="chatdestinataire" class="form-control">
<?php
            foreach ($animateurs AS $animateur) {
                echo "<option value=\"" . $animateur->getId() . "\">" . htmlentities($animateur->getNom()) ." ". htmlentities($animateur->getPrenom()) . "</option>";
            }
        
?>
                            </select>
                        </label>
                    </div>
                    <div class="input-group">
                        <input value=" " type="hidden" name="tags_message">
                        <input value="<?php echo date('Y-m-d H:i:s'); ?>" type="hidden" name="chatdate">
                        <input value="<?php echo $_SESSION['iduser']; ?>" type="hidden" name="chatadh"> 
                        <input type="text" name="chattxt_message" class="form-control" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;"/>  
                        <div class="input-group-btn"><button class="btn btn-primary btn-flat" type="submit" value="message_submit" name="message_submit"><i class="fa fa-paper-plane"></i>&nbsp;</button></div>
                    </div><!-- /.input group -->
                </form>
            </div><!-- /.box footer -->  
    
        </div><!-- /.box (chat box) -->
    </div><!-- .col-md-4 -->
</div> <!-- .row -->
<?php   
        }
        else if ($_SESSION["status"] == "2") { //***UTILISATEUR INACTIF
?>
        
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <i class="fa fa-exclamation-triangle"></i>
                <h3 class="box-title">Attention</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                  
                <div class="callout callout-warning">
                    <h4>Votre compte est d&eacute;sactiv&eacute; !</h4>
                    <p>Votre adh&eacute;sion n'est probablement plus &agrave; jour, veuillez vous rapprocher d'un animateur pour la renouveller.</p>
                </div>
    
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- .row -->
        
<?php
        }
    }
?>



