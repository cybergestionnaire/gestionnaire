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

 include/admin_breve.php V0.1
*/
//Fichier de gestion des archives.
 //statut de l'atelier
/*
$stateAtelier = array(
    0=> "En cours",
    1=> "En programmation",
    2=> "Cloturé",
    3=> "Annulé"
                );
    
*/ 

    include_once("include/class/StatAtelierSession.class.php");
 
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }
    
    
    // classement par annee-
    if (isset($_GET['year'])) {
        $year = $_GET['year'];
    } else {
        $year = date('Y');
    }

    //affichage admin
    if ($_SESSION["status"] == 4) {
        $statAteliers = StatAtelierSession::getStatAteliersArchivesParAnnee($year);
    }
    if ($_SESSION["status"] == 3) {
        $anim = $_SESSION["iduser"];
        $statAteliers = StatAtelierSession::getStatAteliersArchivesParAnneeEtParAnimateur($year, $_SESSION["iduser"]);
    }

    $nb2 = count($statAteliers);  
?>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Liste des ateliers archiv&eacute;s pour <?php echo $year; ?></h3>
        <div class="box-tools pull-right">
            <div class="btn-group">
<?php 
    $annees = StatAtelierSession::getYearStatAtelierSessions();
    foreach ($annees as $annee) {
        echo '<a href="index.php?a=18&year=' . $annee . '&month=12&day=365&jour=31" > <button class="btn bg-yellow btn-sm">' . $annee . ' </button></a>'; 
    }

    //annee en cours
    echo '<a href="index.php?a=18&year=' . date('Y') . '"> <button class="btn bg-yellow btn-sm"> Ann&eacute;e en cours</button></a>';

?>
            </div>
        </div>
    </div><!-- .box-header -->
    <div class="box-body">
<?php
    if ($nb2 > 0) {
?>
        <table class="table"> 
            <thead><tr><th>Date</th><th>Titre</th><th>Inscrits</th><th>Pr&eacute;sents</th><th>Absents</th><th>En Attente</th></tr></thead>
            <tbody>
<?php
        foreach ($statAteliers as $statAtelier) {
            $sujet = $statAtelier->getAtelierSession()->getSujet();
?>
                <tr>
                    <td><?php echo getDatefr($statAtelier->getdateAtelierSession()) ?></td>
                    <td><a href="index.php?a=16&b=4&act=1&idatelier=<?php echo $statAtelier->getidAtelierSession() ?>"><?php echo htmlentities($sujet->getlabel()) ?></a></td>
                    <td><?php echo $statAtelier->getNbInscrits() ?></td>
                    <td><?php echo $statAtelier->getNbPresents() ?></td>
                    <td><?php echo $statAtelier->getNbAbsents() ?></td>
                    <td><?php echo $statAtelier->getNbEnAttente() ?></td>
                </tr>
<?php 
        }
?>
            </tbody>
        </table>
    </div><!-- .box-body -->
<?php
    } else {
?>
        <div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune formation archiv&eacute;e cette ann&eacute;e</div>
<?php
    }  
?>
</div><!-- .box -->
