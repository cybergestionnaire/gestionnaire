
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


  include/admin_stat.php V0.1
  2013 Ajout de la librairy libchart (gnugpl)
  Modification du fichier fonction, ajout include fonction_stat.php

 */

//require_once('include/class/Utilisateur.class.php');

// affichage des statistiques
if (isset($mess) && $mess != "") {
    echo $mess;
}

//inclusion des graphiques
include("libchart/classes/libchart.php");

// recuperation de l'adherent
$id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
$act = isset($_GET["act"]) ? $_GET["act"] : '';


$utilisateur = Utilisateur::getUtilisateurById($id_user);
$row = getUser($id_user);

$nom = $utilisateur->getPrenom() . " " . $utilisateur->getNom();
$equip = $utilisateur->getEquipement();
$equipement = explode("-", $equip);
$utilisation = $utilisateur->getUtilisation();
$connaissance = $utilisateur->getConnaissance();
$infos = $utilisateur->getInfo();
$sexe = $utilisateur->getSexe();

// $nom          = $row["prenom_user"]." ".$row["nom_user"];
// $equip        = $row["equipement_user"];
// $equipement   = explode(";",$equip);
// $utilisation  = $row["utilisation_user"];
// $connaissance = $row["connaissance_user"];
// $info         = stripslashes($row["info_user"]);
// $sexe         = $row["sexe_user"];
// type d'&eacute;quipement défini
$equipementarray = array(
    0 => "Aucun &eacute;quipement",
    1 => "Ordinateur",
    2 => "Tablette",
    3 => "Smartphone",
    4 => "T&eacute;l&eacute;vision connect&eacute;e",
    5 => " Internet &agrave; la maison (ADSL, satellite ou fibre)",
    6 => " Internet mobile (3G, 4G+)",
    7 => "Pas de connexion Internet"
);


$equipements = '';
if ($equip == $equipement[0]) {
    // pas d'équippement !
    $equipements = 'Equipements non renseign&eacute;s';
} else {
    foreach ($equipement as $key => $value) {
        $equipements = $equipements . $equipementarray[$value] . " / ";
    }
}

// type d'utilisation défini
$utilisationarray = array(
    0 => "Aucun Lieu",
    1 => "A la maison",
    2 => "Au bureau ou &agrave; l'&eacute;cole",
    3 => "A la maison et au bureau ou &agrave; l'&eacute;cole"
);

// type de connaissance défini
$connaissancearray = array(
    0 => "D&eacute;butant",
    1 => "Interm&eacute;diaire",
    2 => "Confirm&eacute;"
);

// si b=3 desinscription a un atelier
if ($act == 1) {
    $atelier = Atelier::getAtelierById($idAtelier);
    $atelier->desinscrireUtilisateur($_SESSION["iduser"]);
    // delUserAtelier($_GET["idatelier"], $id_user) ;
    echo geterror();
}

$ListeAtelierInscrit = $utilisateur->getAteliersInscrit();
$ListeSessionInscrit = $utilisateur->getSessionsInscrit();

$ListeAtelierEnAttente = $utilisateur->getAteliersEnAttente();
$ListeSessionEnAttente = $utilisateur->getSessionsEnAttente();

//liste de tous les ateliers et session où l'dherent est inscrit et presence valid&eacute;e clotur&eacute; ==2 pour les ateliers, cloturé=1 pour les sessions !
//rappel getUserStatutAS($iduser,$statut,$type,$statutatelier) where $statut== présence, et $type==atelier ou session
$ListeAtelierPresent = $utilisateur->getAteliersPresent(); //ateliers passés
$ListeSessionPresent = $utilisateur->getSessionDatesPresent();
$nbpresentatelier = count($ListeAtelierPresent);
$nbpresentsession = count($ListeSessionPresent);
$nbtotalpresent = $nbpresentatelier + $nbpresentsession;


//liste de tous les ateliers et session où l'dherent est inscrit et non validée $statut==0 --> absent !
$ListeAtelierAbsent = $utilisateur->getAteliersAbsent();
$ListeSessionAbsent = $utilisateur->getSessionDatesAbsent();
$nbabsentatelier = count($ListeAtelierAbsent);
$nbabsentsession = count($ListeSessionAbsent);
$nbtotalabsent = $nbabsentatelier + $nbabsentsession;

$today = date('Y-m-d');
$year = date('Y');

//verification que le dossier images des stats existe.
$dossierimg = "img/chart/" . $year;
if (!is_dir($dossierimg)) {
    mkdir($dossierimg);
}
?>

<div class="row">
    <section class="col-lg-6 connectedSortable"> 
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Historique de l'adh&eacute;rent</h3>
                <div class="box-tools pull-right">
                    <a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-purple btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></a>
                    <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId(); ?>" class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>                    
                    <a href="index.php?a=9&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-maroon btn-sm"  data-toggle="tooltip" title="Consultation internet"><i class="fa fa-globe"></i></a>
                    <a href="index.php?a=21&b=1&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Compte d'impression"><i class="fa fa-print"></i></a>
                    <?php
                    if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                        ?>
                        <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-primary btn-sm" data-toggle="tooltip" title="Inscriptions Ateliers"><i class="fa fa-keyboard-o"></i></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <td>
                            <?php
                            if (isset($id_user)) {
                                if ($sexe == "F") {
                                    echo '<img src="img/avatar/female.png">';
                                } else {
                                    echo '<img src="img/avatar/male.png">';
                                }
                            } else {
                                echo '<img src="img/avatar/default.png" width="60%">';
                            }
                            ?>
                        </td>
                        <td>
                            <h4><?php echo $nom; ?></h4>
                            <p><b>Rappel des conditions informatiques : </b></p>
                            <p><?php echo $equipements; ?> <br> <?php echo $utilisationarray[$utilisation]; ?> / <?php echo $connaissancearray[$connaissance]; ?></p>
                            <p><b>Notes particuli&egrave;res : </b></p>
                            <p><?php
                                if ($infos != "") {
                                    echo $infos;
                                } else {
                                    echo "pas d'infos ! ";
                                }
                                ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div><!-- .box -->

        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">Taux de pr&eacute;sence</h3></div>
            <div class="box-body">
                <?php
                ///total des inscriptions --> % présence / %absences sur total inscriptions pour les sessions et les ateliers
                //ateliers
                $chartA = new PieChart(400, 280);
                $dataSetA = new XYDataSet();
                $dataSetA->addPoint(new Point("Présences", $nbpresentatelier));
                $dataSetA->addPoint(new Point("Absences", $nbabsentatelier));
                $chartA->setDataSet($dataSetA);
                $chartA->getPlot()->getPalette()->setPieColor(array(new Color(44, 173, 135), new Color(234, 42, 83)));
                $chartA->setTitle("Taux de présence aux ateliers (" . $year . ") ");
                $chartA->render("img/chart/" . $year . "/txpresencea_" . $id_user . ".png");
                //sessions
                $chartS = new PieChart(400, 280);
                $dataSetS = new XYDataSet();
                $dataSetS->addPoint(new Point("Présences", $nbpresentsession));
                $dataSetS->addPoint(new Point("Absences", $nbabsentsession));
                $chartS->setDataSet($dataSetS);
                $chartS->getPlot()->getPalette()->setPieColor(array(new Color(44, 173, 135), new Color(234, 42, 83)));
                $chartS->setTitle("Taux de présence aux session (" . $year . ") ");
                $chartS->render("img/chart/" . $year . "/txpresences_" . $id_user . ".png");
                ?>
                <img src="img/chart/<?php echo $year; ?>/txpresencea_<?php echo $id_user; ?>.png" >
                <img src="img/chart/<?php echo $year; ?>/txpresences_<?php echo $id_user; ?>.png" >
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Inscriptions actuelles aux ateliers</h3>
                <div class="box-tools pull-right">
                    <a href="courriers/lettre_atelier.php?user=<?php echo $id_user; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-print"></i></button></a>
                </div>
            </div>
            <div class="box-body">
                <?php
                if (count($ListeAtelierInscrit) > 0) {
                    ?>    
                    <table class="table">
                        <thead><tr><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead>
                        <tbody>
                            <?php
                            foreach ($ListeAtelierInscrit as $atelier) {
                                ?>
                                <tr>
                                    <td><?php echo getDayFR($atelier->getDate()) . " &agrave; " . $atelier->getHeure() ?></td>
                                    <td><?php echo $atelier->getSujet()->getLabel() ?></td>
                                    <td><a href="index.php?a=5&b=6&act=1&iduser=<?php echo $id_user ?>&idatelier=<?php echo $atelier->getId() ?>" class="btn bg-red sm"  data-toggle="tooltip" title=" D&eacute;sinscrire"><i class="fa fa-trash-o"></i></a></td>
                                </tr>
                                <?php
                            } ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    echo "<p>Pas d'inscription enregistr&eacute;e pour le moment</p>";
                }
                ?>


                <?php
//en attente
                if (count($ListeAtelierEnAttente) > 0) {
                    ?>
                    <div class="box-body">
                        <h4>Inscrit en liste d'attente  :</h4>
                        <table class="table">
                            <thead><tr><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead>
                            <tbody>
                                <?php
                                foreach ($ListeAtelierEnAttente as $atelier) {
                                    ?>
                                    <tr>
                                        <td><?php echo getDayFR($atelier->getDate()) . " &agrave; " . $atelier->getHeure() ?></td>
                                        <td><?php echo $atelier->getSujet()->getLabel() ?></td>
                                        <td><a href="index.php?a=5&b=6&act=1&iduser=<?php echo $id_user ?>&idatelier=<?php echo $atelier->getId() ?>" class="btn bg-red sm"><i class="fa fa-trash-o" data-toggle="tooltip" title=" D&eacute;sinscrire"></i></a></td>
                                    </tr>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    echo "<p class=\"text-info\">Pas d'inscription en liste d'attente pour le moment</p>";
                }
                ?>

            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Inscriptions actuelles aux sessions</h3>
                <div class="box-tools pull-right">
                    <a href="courriers/lettre_session.php?user=<?php echo $id_user; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-print"></i></button></a>
                </div>
            </div>
            <div class="box-body">
                <?php
                if (count($ListeSessionInscrit) > 0) {
                    ?>   
                    <table class="table">
                        <thead><tr><th>Date et heure</th><th>Nom de la session</th><th></th></tr></thead>
                        <tbody>
                            <?php
                            foreach ($ListeSessionInscrit as $session) {
                                foreach ($session->getSessionDates() as $sessionDate) {
                                    if ($sessionDate->getStatut() == 1) {
                                        $class = "text-muted";
                                        if ($sessionDate->isUtilisateurPresent($utilisateur->getid())) {
                                            $presence = "Pr&eacute;sent";
                                        } else {
                                            $presence = "Absent";
                                        }
                                    } else {
                                        $class = "";
                                        $presence = "";
                                    } ?>
                                    <tr class="<?php echo $class; ?>">
                                        <td><?php echo getDateFR($sessionDate->getDate()) ?></td>
                                        <td><?php echo $sessionDate->getSession()->getSessionSujet()->getTitre() ?> </td>
                                        <td><?php echo $presence ?></td>
                                    </tr>
                                    <?php
                                }
                            } ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<p>Pas d'inscription enregistr&eacute;e pour le moment</p>";
                }
                ?>


                <?php
                //en attente

                if (count($ListeSessionEnAttente) > 0) {
                    ?>
                    <div class="box-body">
                        <h4>Inscrit en liste d'attente  :</h4>
                        <table class="table">
                            <thead><tr><th>Date et heure</th><th>Nom de la session</th><th></th></tr></thead>
                            <tbody>
                                <?php
                                foreach ($ListeSessionInscrit as $session) {
                                    foreach ($session->getSessionDates() as $sessionDate) {
                                        ?>                    
                                        <tr>
                                            <td><?php echo getDateFR($sessionDate->getDate()) ?></td>
                                            <td><?php echo $sessionDate->getSession()->getSessionSujet()->getTitre() ?> </td>
                                            <td><a href="index.php?a=5&b=6&act=1&iduser=<?php echo $id_user ?>&idsession=<?php echo $sessionDate->getSession()->getId() ?>"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o" data-toggle="tooltip" title=" D&eacute;sinscrire"></i></button></a></td>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    echo "<p class=\"text-info\">Pas d'inscription en liste d'attente pour le moment</p>";
                }
                ?>

            </div>
        </div>
    </section>

    <section class="col-lg-6 connectedSortable"> 
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Participations pass&eacute;es (ann&eacute;e en cours)</h3>
                <div class="box-tools pull-right">
                    <a href="courriers/csv_historique.php?user=<?php echo $id_user; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-green btn-sm"  data-toggle="tooltip" title="T&eacute;l&eacute;charger le XLS"><i class="fa fa-download"></i></button></a>
                </div>
            </div>

            <div class="box-body">
                <p class="text-info">Attention les ateliers et la totalit&eacute; de dates d'une session doivent &ecirc;tre valid&eacute;es pour que la participation pass&eacute;e s'affiche !</p>
                <?php
                if ($nbtotalabsent > 0) {
                    ?>
                    <h4>Inscrit mais absent (<?php echo $nbtotalabsent; ?>)</h4>
                    <table class="table">
                        <thead><tr><th>Type</th><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead>
                        <tbody>
                            <?php
                            // for ($i = 1 ; $i <= $nbabsentatelier ; $i++) {
                            // //atelier
                            // $rowatelier   = mysqli_fetch_array($ListeAtelierAbsent);
                            // $arrayatelier = getAtelier($rowatelier["id_atelier"]);
                            // $rowsujetA    = mysqli_fetch_array($titrearray);
                            foreach ($ListeAtelierAbsent as $atelier) {
                                ?>
                                <tr>
                                    <td>Atelier</td>
                                    <td><?php echo getDayFR($atelier->getDate()) . " &agrave; " . $atelier->getHeure() ?></td>
                                    <td><?php echo $atelier->getSujet()->getLabel() ?></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                    //session

                    foreach ($ListeSessionAbsent as $sessionDate) {
                        ?>
                                <tr>
                                    <td>Session</td>
                                    <td><?php echo getDateFR($sessionDate->getDate()) ?></td>
                                    <td><?php echo $sessionDate->getSession()->getSessionSujet()->getTitre() ?> </td>
                                    <td></td>
                                </tr>
                                <?php
                    } ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<p>Pas d'absence pass&eacute;e enregistr&eacute;e</p>";
                }
                ?>

                <h4>Pr&eacute;sent (<?php echo $nbtotalpresent; ?>)</h4>
                <?php
                if ($nbtotalpresent > 0) {
                    ?>
                    <table class="table">
                        <thead><tr><th>Type</th><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead>
                        <tbody>
                            <?php
                            foreach ($ListeAtelierPresent as $atelier) {
                                ?>
                                <tr>
                                    <td>Atelier</td>
                                    <td><?php echo getDayFR($atelier->getDate()) . " &agrave; " . $atelier->getHeure() ?></td>
                                    <td><?php echo $atelier->getSujet()->getLabel() ?></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                    //session

                    foreach ($ListeSessionPresent as $sessionDate) {
                        ?>
                                <tr>
                                    <td>Session</td>
                                    <td><?php echo getDateFR($sessionDate->getDate()) ?></td>
                                    <td><?php echo $sessionDate->getSession()->getSessionSujet()->getTitre() ?> </td>
                                    <td></td>
                                </tr>
                                <?php
                    } ?>
                        </tbody>
                    </table>

                    <?php
                } else {
                    echo '<p>Aucune pr&eacute;sence valid&eacute;e n\'a &eacute;t&eacute; enregistr&eacute;e.</p>';
                }
                ?>

            </div>
        </div>
    </section><!-- ./col -->
</div><!-- ./row -->























