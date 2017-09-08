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
  2012 Dauvergne florence pour les modifications

 */
//require_once("include/class/Session.class.php");
//require_once("include/class/StatAtelierSession.class.php");

//Fichier de gestion des archives.
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}

//parametres
$statusarray = array(
    0 => "Session en cours",
    1 => "(Valid&eacute;e)",
    2 => "(Annul&eacute;e)"
);

// classement par annee-
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// $result = getAncSession($_SESSION['idepn'],$year);
// $nb = mysqli_num_rows($result) ;
$sessionsArchivees = Session::getSessionsArchiveeByEspaceAndAnnee($_SESSION['idepn'], $year);
$nb = count($sessionsArchivees);
?>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Liste des sessions archiv&eacute;s </h3>
        <div class="box-tools pull-right">
            <div class="btn-group">
                <?php
                $annees = StatAtelierSession::getYearStatAtelierSessions();
                foreach ($annees as $annee) {
                    echo '<a href="index.php?a=36&year=' . $annee . '" > <button class="btn bg-yellow btn-sm">' . $annee . ' </button></a>';
                }

                // while ($ans = mysqli_fetch_array($rowanneesstat)) {
                // echo '<a href="index.php?a=36&year=' . $ans['Y'] . '" > <button class="btn bg-yellow btn-sm">' . $ans['Y'] . ' </button></a>';
                // }
                //annee en cours
                echo '<a href="index.php?a=36&year=' . date('Y') . '"> <button class="btn bg-yellow btn-sm"> Ann&eacute;e en cours</button></a>';
                ?>
            </div>
        </div>
    </div><!-- .box-header -->
    <div class="box-body no-padding">
        <?php
        if ($nb > 0) {
            ?>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Intitul&eacute;</th>
                        <th>Animateur</th>
                        <th>Salle</th>
                        <th>Inscrits</th>
                        <th>Horaires&nbsp;&nbsp;<small class="badge bg-blue"  data-toggle="tooltip" title="Cliquez sur une date pour modifier les pr&eacute;sences"><i class="fa fa-info"></i></small></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($sessionsArchivees as $session) {
                        $salle = $session->getSalle();
                        $espace = $salle->getEspace();
                        $animateur = $session->getAnimateur();
                        $sujet = $session->getSessionSujet();
                        $tarif = $session->getTarif();
                        $datesSession = $session->getSessionDates();
                        $nbrdates = $session->getNbDates(); ?>

                        <tr>
                            <td><?php echo htmlentities($sujet->getTitre()) ?></td>
                            <td><?php echo htmlentities($animateur->getPrenom() . " " . $animateur->getNom()) ?></td>
                            <td><?php echo htmlentities($salle->getNom()) . " (" . htmlentities($espace->getNom()) . ")" ?></td>
                            <td><?php echo $session->getNbutilisateursInscritsOuPresents() ?></td>

                            <td>
                                <?php
                                foreach ($datesSession as $dateSession) {
                                    if ($dateSession->getStatut() == 2) {
                                        echo getDatefr($dateSession->getDate()) . "&nbsp;&nbsp;" . $statusarray[$dateSession->getStatut()] . " </br>";
                                    } else {
                                        echo "<a href=\"index.php?a=32&act=1&idsession=" . $session->getId() . "&dateid=" . $dateSession->getId() . "\">" . getDatefr($dateSession->getDate()) . "</a>&nbsp;&nbsp;" . $statusarray[$dateSession->getStatut()] . " </br> ";
                                    }
                                } ?>    
                            </td>
                        </tr>
                        <?php
                    } ?>
                </tbody>
            </table>
        </div><!-- .box-body -->
        <div class="box-footer">
            <a href="index.php?a=37"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des sessions en cours</button></a>
        </div>
    </div><!-- .box -->

    <?php
        } else {
            ?>
    <div class="alert alert-info alert-dismissable">
        <i class="fa fa-info"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune session archiv&eacute;e pour l'ann&eacute;e en cours
    </div>
    <?php
        }
?>
          