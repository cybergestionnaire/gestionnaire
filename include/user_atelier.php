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

 */

//require_once("include/class/Espace.class.php");
require_once("include/class/Atelier.class.php");
require_once("include/class/Session.class.php");

$b = isset($_GET["b"]) ? $_GET["b"] : '';
$idAtelier = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '';
$idSession = isset($_GET["idsession"]) ? $_GET["idsession"] : '';


//statut de l'atelier
$stateAtelier = array(
    1 => "Programm&eacute;",
    2 => "En programmation",
    3 => "Annul&eacute;");

// si b =2 inscription a un atelier
if ($b == 2) {
    $atelier = Atelier::getAtelierById($idAtelier);
    if (!$atelier->isUtilisateurInscrit($_SESSION["iduser"])) {
        if ($atelier->getNbPlacesRestantes() > 0) {
            if ($atelier->inscrireUtilisateurInscrit($_SESSION["iduser"])) {
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info alert-dismissable"> 
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-info"></i>Inscription valid&eacute;e</h4>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {

            // plus de place disponible --> en attente
            if ($atelier->inscrireUtilisateurEnAttente($_SESSION["iduser"])) {
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info alert-dismissable"> 
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-info"></i>Inscription sur liste d'attente valid&eacute;e</h4>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    } else {
        echo geterror(21);
    }
}

// si b=3 desinscription a un atelier
if ($b == 3) {
    // delUserAtelier($idAtelier, $_SESSION["iduser"]) ;
    $atelier = Atelier::getAtelierById($idAtelier);
    if ($atelier->desinscrireUtilisateur($_SESSION["iduser"])) {
        ?>
        <div class="row"><div class="col-md-6"> <div class="alert alert-info alert-dismissable"> 
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i>D&eacute;sinscription effectu&eacute;e</h4></div></div></div>
        <?php
    }
}

//si b==6 inscription a une session
if ($b == 6) {
    //verification d'inscription
    $session = Session::getSessionById($idSession);

    if (!$session->isUtilisateurInscrit($_SESSION["iduser"])) {
        if ($session->getNbPlacesRestantes() > 0) {
            if ($session->inscrireUtilisateurInscrit($_SESSION["iduser"])) {
                echo geterror(25);
            }
        } else {
            if ($session->inscrireUtilisateurEnAttente($_SESSION["iduser"])) {
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info alert-dismissable"> 
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-info"></i>Inscription sur liste d'attente valid&eacute;e</h4>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    } else {
        echo geterror(21);
    }
}

// si b=7 desinscription a une session
if ($b == 7) {
    $session = Session::getSessionById($idSession);
    if ($session->desinscrireUtilisateur($_SESSION["iduser"])) {
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-info alert-dismissable"> 
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i>D&eacute;sinscription effectu&eacute;e</h4>
                </div>
            </div>
        </div>
        <?php
    }
}

//*************** si b =1 affichage de l'atelier en detail

if ($b == 1 or $b == 5) {
    switch ($b) {
        case 1:
            $atelier = Atelier::getAtelierById($idAtelier);
            $animateur = $atelier->getAnimateur();
            $sujet = $atelier->getSujet();
            $salle = $atelier->getSalle();
            $tarif = $atelier->getTarif();

            $dateheure = "Le " . getDayfr($atelier->getJour()) . " &agrave; " . $atelier->getHeure();
            $anim = $animateur->getPrenom() . " " . $animateur->getNom();
            $duree = $atelier->getDuree();
            $nomsalle = $salle->getNom() . " (" . $salle->getEspace()->getNom() . ")";
            if ($tarif->getDonnee() == 0) {
                $prix = "Gratuit";
            } else {
                $prix = htmlentities($tarif->getDonnee()) . "&euro;";
            }
            $titre = $sujet->getLabel();
            $detail = $sujet->getContent();

            $nbplaceatelier = $atelier->getNbPlaces();
            $nbplacerestantes = $atelier->getNbPlacesRestantes();

            $pluriel = false;

            $posturl = "index.php?m=6&b=2&idatelier=" . $idAtelier;
            if ($nbplacerestantes > 0) {
                $bouton = "s'inscrire &agrave; cet atelier";
            } else {
                $bouton = "se mettre sur liste d'attente";
            }

            break;

        case 5:
            $session = Session::getSessionById($idSession);
            $animateur = $session->getAnimateur();
            $sujet = $session->getSessionSujet();
            $salle = $session->getSalle();
            $datesSession = $session->getSessionDates();

            $titre = $sujet->getTitre();
            $detail = $sujet->getDetail();
            $anim = $animateur->getPrenom() . " " . $animateur->getNom();
            $duree = "60"; //TODO : rendre ça configurable !!
            $nomsalle = $salle->getNom() . " (" . $salle->getEspace()->getNom() . ")";
            $prix = $session->getTarif()->getNom();

            $nbplaceatelier = $session->getNbPlaces();
            $nbplacerestantes = $session->getNbPlacesRestantes();

            $pluriel = count($datesSession) > 1 ? true : false;
            $dateheure = "";
            foreach ($datesSession as $dateSession) {
                $dateheure = $dateheure . getDatefr($dateSession->getDate()) . " <br>";
            }

            $posturl = "index.php?m=6&b=6&idsession=" . $idSession;
            if ($nbplacerestantes > 0) {
                $bouton = "s'inscrire &agrave; cette session";
            } else {
                $bouton = "se mettre sur liste d'attente";
            }

            break;
    } ?>

    <div class="row">
        <section class="col-lg-7 connectedSortable">
            <div class="box box-success">
                <div class="box-header"> <h3 class="box-title"><?php echo htmlentities($titre); ?></h3></div>
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <dt>Date<?php
                            if ($pluriel) {
                                echo "s";
                            } ?></dt><dd><?php echo $dateheure; ?></dd>
                        <dt>Anim&eacute; par </dt><dd><?php echo htmlentities($anim); ?></dd>
                        <dt>Dur&eacute;e </dt><dd><?php echo htmlentities($duree); ?> minutes</dd>
                        <dt>Lieu </dt><dd> <?php echo htmlentities($nomsalle); ?></dd>
                        <dt>Tarif</dt><dd><?php echo $prix; ?></dd>
                        <dt>Places ouvertes</dt><dd><?php echo $nbplacerestantes; ?> (Au total : <?php echo $nbplaceatelier; ?> places)</dd>
                        <dt>D&eacute;tail</dt><dd><?php echo htmlentities($detail); ?></dd>
                    </dl>
                </div>

                <div class="box-footer">
                    <a href="index.php?m=6"><input type="submit" name="" value="Retour" class="btn btn-primary  pull-right"></a>
                    <form method="post" action="<?php echo $posturl; ?>">
                        <input type="submit" name="submit" value="<?php echo $bouton; ?>" class="btn btn-success ">
                    </form>
                </div>
            </div>
        </section>
        <section class="col-lg-5 connectedSortable">
            <div class="box box-solid box-primary">
                <div class="box-header"><h3 class="box-title">Aide</h3></div>
                <div class="box-body">
                    <p class="lead">Si vous souhaitez vous inscrire, cliquez sur le bouton "s'inscrire", pour quitter la page cliquez sur "retour &agrave; la liste"</p>

                </div>
            </div>
        </section>

    </div><!-- /row-->

    <?php
}
//***FIN DETAIL ATELIERS //****
else {


    //**** INSCRIPTIONS DE l'adh&eacute;rent ****//
    /*
      $result = getMyAtelier($_SESSION["iduser"],1,0)  ;
      $nb     = mysqli_num_rows($result);

      $ListeSessionEnCours=getMySession($_SESSION["iduser"]);
      $numSession=mysqli_num_rows($ListeSessionEnCours);

     */

    //************** Affichage de la liste des ateliers affichage par defaut ****///
    //La liste des ateliers
    $listeAtelier = getMyFutAtelier(date('Y'), date('n'), date('d'));
    $nba = mysqli_num_rows($listeAtelier);
    // la liste des sessions

    $ateliers = Atelier::getAteliersFutursByAnnee(date('Y'));
    $sessions = Session::getSessionsFuturesByAnnee(date('Y'));
    $nbAteliers = count($ateliers);
    $nbSessions = count($sessions); ?> 
    <div class="row">
        <!-- criteres de choix -->
        <div class="col-md-3">
            <div class="box">
                <div class="box-header"><h3 class="box-title">Crit&egrave;res (non-impl&eacute;ment&eacute; / TODO)</h3> </div>
                <div class="box-body">
                    <form role="form" method="POST" action="#">
                        <div class="form-group">
                            <label>Cat&eacute;gories</label>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Jeunesse</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Syst&egrave;me d'exploitation</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Web</div>
                        </div>
                        <div class="form-group"><label>Niveau</label>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">D&eacute;butant</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Autonome</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Admin</div>
                        </div>
                        <div class="form-group"><label>Dates</label>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Mois en cours</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Mois suivant</div>
                            <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">6 mois</div>
                        </div>
                    </form>
                </div>
            </div><!-- .box -->
        </div><!-- .col-md-3 -->

        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Liste des ateliers propos&eacute;s pour <?php echo date('Y'); ?></h3></div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="<?php
    if ($b == '' or $b == 2 or $b == 3) {
        echo "active";
    } ?>"><a href="#tab_3" data-toggle="tab">Les ateliers (<?php echo $nbAteliers; ?>)</a></li>
                            <li class="<?php
                            if ($b == 5 or $b == 6 or $b == 7) {
                                echo "active";
                            } ?>"><a href="#tab_4" data-toggle="tab">Les sessions (<?php echo $nbSessions; ?>)</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane <?php
                             if ($b == '' or $b == 2 or $b == 3) {
                                 echo "active";
                             } ?>" id="tab_3">
                                    <?php
                                    //if ($nba > 0) {
                                    if ($nbAteliers > 0) {
                                        ?>
                                    <table class="table table-condensed">
                                        <tr><th>Date</th><th>Heure</th><th>Dur&eacute;e</th><th>Titre</th><th>Niveau</th><th>Lieu</th><th>Places restantes</th><th>Inscription</th> </tr>               
                                        <?php
                                        foreach ($ateliers as $atelier) {
                                            $sujet = $atelier->getSujet();
                                            $salle = $atelier->getSalle();

                                            $nbPlaces = $atelier->getNbPlacesRestantes();
                                            if ($nbPlaces <= 0) {
                                                $nbPlaces = '<span class="badge bg-purple">COMPLET</span>';
                                            }

                                            if ($atelier->getStatut() == 3) {
                                                $boutoninscr = "<small class=\"badge bg-yellow\">Annul&eacute;</small>";
                                            } else {
                                                $statutUtilisateur = $atelier->getStatutUtilisateur($_SESSION["iduser"]);

                                                if ($statutUtilisateur === null) {
                                                    if ($nbPlaces <= 0) {
                                                        $boutoninscr = "<a href=\"index.php?m=6&b=1&idatelier=" . $atelier->getId() . "\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire sur liste d'attente</small></a>";
                                                    } else {
                                                        $boutoninscr = "<a href=\"index.php?m=6&b=1&idatelier=" . $atelier->getId() . "\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire</small></a>";
                                                    }
                                                } else {
                                                    if ($statutUtilisateur == 0) {
                                                        $boutoninscr = "<small class=\"badge bg-green\">d&eacute;j&agrave; inscrit</small>&nbsp; 
                                    <a href=\"index.php?m=6&b=3&idatelier=" . $atelier->getId() . "\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
                                                    } elseif ($statutUtilisateur == 2) {
                                                        $boutoninscr = "<small class=\"badge bg-orange\">en liste d'attente</small>&nbsp; 
                                    <a href=\"index.php?m=6&b=3&idatelier=" . $atelier->getId() . "\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
                                                    }
                                                }
                                            } ?>
                                            <tr>
                                                <td><?php echo datefr($atelier->getJour()) ?></td>
                                                <td><?php echo htmlentities($atelier->getHeure()) ?></td>
                                                <td><?php echo getTime($atelier->getDuree()) ?></td>
                                                <td><?php echo htmlentities($sujet->getLabel()) ?></td>
                                                <td><?php echo htmlentities($sujet->getNiveau()->getNom()); ?></td>
                                                <td><?php echo htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() . ")"); ?></td>
                                                <td><?php echo $nbPlaces; ?></td>
                                                <td><?php echo $boutoninscr; ?></td>
                                            </tr>
            <?php
                                        } ?>
                                    </table>
                                    <?php
                                    } else {
                                        ?>
                                    <div class="alert alert-info alert-dismissable">
                                        <i class="fa fa-info"></i>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucun atelier programm&eacute; pour le moment
                                    </div>
                                     <?php
                                    } ?>
                            </div><!-- .tab-pane -->

                            <div class="tab-pane <?php
                                    if ($b == 5 or $b == 6 or $b == 7) {
                                        echo "active";
                                    } ?>" id="tab_4">
                                    <?php
                                    if ($nbSessions > 0) {
                                        ?>
                                    <table class="table table-condensed">
                                        <tr><th>Dates</th><th>Titre</th><th>Lieu</th><th>Places restantes</th><th></th></tr>
                                        <?php
                                        foreach ($sessions as $session) {
                                            $sujet = $session->getSessionSujet();
                                            $salle = $session->getSalle();
                                            $datesSession = $session->getSessionDates();
                                            $listeDates = "";
                                            foreach ($datesSession as $dateSession) {
                                                $listeDates = $listeDates . date_format(date_create($dateSession->getDate()), "Y/m/d H:i") . "<br />";
                                            }
                                            $nbPlaces = $session->getNbPlacesRestantes();
                                            if ($nbPlaces <= 0) {
                                                $nbPlaces = '<span class="badge bg-purple">COMPLET</span>';
                                            }

                                            if ($session->getStatus() == 3) {
                                                $boutoninscr = "<small class=\"badge bg-yellow\">Annul&eacute;</small>";
                                            } else {
                                                $statutUtilisateur = $session->getStatutUtilisateur($_SESSION["iduser"]);

                                                if ($statutUtilisateur === null) {
                                                    if ($nbPlaces <= 0) {
                                                        $boutoninscr = "<a href=\"index.php?m=6&b=5&idsession=" . $session->getId() . "\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire sur liste d'attente</small></a>";
                                                    } else {
                                                        $boutoninscr = "<a href=\"index.php?m=6&b=5&idsession=" . $session->getId() . "\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire</small></a>";
                                                    }
                                                } else {
                                                    if ($statutUtilisateur == 0) {
                                                        $boutoninscr = "<small class=\"badge bg-green\">d&eacute;j&agrave; inscrit</small>&nbsp; 
                                    <a href=\"index.php?m=6&b=7&idsession=" . $session->getId() . "\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
                                                    } elseif ($statutUtilisateur == 2) {
                                                        $boutoninscr = "<small class=\"badge bg-orange\">en liste d'attente</small>&nbsp; 
                                    <a href=\"index.php?m=6&b=7&idsession=" . $session->getId() . "\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
                                                    }
                                                }
                                            } ?>
                                            <tr> 
                                                <td><small><?php echo $listeDates ?></small></td>
                                                <td><span class="text-muted"><?php echo htmlentities($sujet->getTitre()) ?></span></td>
                                                <td><?php echo htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() . ")"); ?></td>                     
                                                <td><?php echo $nbPlaces ?></td>
                                                <td><?php echo $boutoninscr ?></td>
                                            </tr>
            <?php
                                        } ?>

                                    </table>
        <?php
                                    } else {
                                        ?>
                                    <div class="alert alert-info alert-dismissable">
                                        <i class="fa fa-info"></i>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune session programm&eacute;e
                                    </div>
        <?php
                                    } ?>

                            </div><!--/ tab_pane -->
                        </div><!--/ tab_content -->
                    </div><!-- /nav-tab-->
                </div><!-- / box body-->
            </div><!-- / box-->
        </div><!-- / col -->
    </div><!-- /row -->

    <?php
}
?>
