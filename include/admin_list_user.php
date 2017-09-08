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
  2012 Dauvergne Florence
 */
//require_once("include/class/Utilisateur.class.php");
//require_once("include/class/Tarif.class.php");

// admin --- Utilisateur
$term = isset($_POST["term"]) ? $_POST["term"] : '';
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';

if ($mesno != "") {
    echo getError($mesno);
}

// Tableau des unité d'affectation
$tab_unite_temps_affectation = array(
    1 => 1, //minutes
    2 => 60 //heures
);

// Tableau des fréquence d'affectation
$tab_frequence_temps_affectation = array(
    1 => "par Jour",
    2 => "par Semaine",
    3 => "par Mois"
);
?>

<div class="row"> 

    <!-- Resultats de la recherche -->
    <div class="col-xs-12">
        <?php
// Les adhérents // MODIF 2012 : liste des 25 derniers inscrits......
        $utilisateurs = Utilisateur::getUtilisateursByDateInsc(25);
        $nbUtilisateurs = count($utilisateurs);
        if ($utilisateurs == null or $nbUtilisateurs == 0) {
            ?>
            <br>
            <div class="row">
                <div class="col-xs-6">";
                    <?php echo getError(1); ?>

                </div>
                <div class="col-xs-6">
                    <div class="alert alert-info alert-dismissable">
                        <i class="fa fa-info"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        &nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1" >Cr&eacute;er un nouvel utilisateur ?</a>
                    </div>
                </div>
            </div>
            <?php
        } else { // affichage du resultat ?>

            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Liste des 25 derniers adh&eacute;rents inscrits</h3>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1"  class="btn btn-default"  data-toggle="tooltip" title="Ajouter un adh&eacute;rent"><i class="fa fa-plus"></i></a>
                    &nbsp;&nbsp; <a href="index.php?a=1" class="btn btn-default"  data-toggle="tooltip" title="Voir tous les adh&eacute;rents"><i class="fa fa-users"></i></a>
                    <div class="box-tools">

                        <div class="input-group">
                            <form method="post" action="index.php?a=1">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                    <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                                </div>
                            </form>
                        </div><!-- /input-group -->

                    </div><!-- .box-tool -->

                </div><!-- .box-header -->

                <div class="box-body no-padding">
                    <table class="table">
                        <thead>
                        <th></th>
                        <th>Nom</th>
                        <th>Pr&eacute;nom</th>
                        <th>Nom d'utilisateur</th>
                        <th>Age</th>
                        <th>Derni&egrave;re visite (r&eacute;sa)</th>
                        <th>Adh&eacute;sion  <span class="badge bg-primary"  data-toggle="tooltip" title="Vert = en cours, Jaune = adh&eacute;sion &agrave; renouveller dans la semaine"><i class="fa fa-info"></i></th>
                        <th>Forfait temps</th>
                        </thead> 
                        <tbody> 
                            <?php
                            foreach ($utilisateurs as $utilisateur) {
                                $age = $utilisateur->getAge();
                                //ADHESION
                                $tarif = Tarif::getTarifById($utilisateur->getIdTarifAdhesion());
                                $adhesion = $tarif != null ? $tarif->getNom() : '';
                                $aujourdhui = date_create(date('Y-m-d'));
                                $daterenouvellement = date_create($utilisateur->getDateRenouvellement());
                                //$interval = date_diff($aujourdhui,$daterenouvellement);
                                //debug($interval->format('%R%a'));
                                if ($utilisateur->getStatut() == 1) {
                                    if ($daterenouvellement <= $aujourdhui) {
                                        $classadh = 'label label-warning';
                                    } elseif ($daterenouvellement > $aujourdhui) {
                                        $classadh = 'label label-success';
                                    }
                                } elseif ($utilisateur->getStatut() == 2) {
                                    $classadh = 'label label-danger';
                                }

                                $forfaitConsultation = $utilisateur->getForfaitConsultation();

                                if ($forfaitConsultation != null) {
                                    $min = $tab_unite_temps_affectation[$forfaitConsultation->getUniteConsultation()];
                                    $tarifreferencetemps = $forfaitConsultation->getDureeConsultation() * $min;

                                    $restant = $utilisateur->getTempsrestant();
                                    $rapport = round(($restant / $tarifreferencetemps) * 100);
                                }
                                //dernière reservation
                                // $lasteresa = getLastResaUser($utilisateur->getId());

                                $lasteresa = $utilisateur->getLastResa();

                                if ($lasteresa == null) {
                                    $lasteresa = "NC";
                                } ?>
                                <tr>
                                    <td>
                                        <a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn bg-purple btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a>
                                        <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn bg-yellow btn-sm" data-toggle="tooltip" title="transactions"><i class="ion ion-bag"></i></button></a>
                                        <?php
                                        if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                                            ?>
                                            &nbsp;<a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn bg-primary btn-sm" data-toggle="tooltip" title="Inscriptions Ateliers"><i class="fa fa-keyboard-o"></i></button></a>
                                            <?php
                                        } ?>

                                    </td>
                                    <td><?php echo htmlentities($utilisateur->getNom()) ?></td>
                                    <td><?php echo htmlentities($utilisateur->getPrenom()) ?></td>
                                    <td><?php echo htmlentities($utilisateur->getLogin()) ?></td>
                                    <td><?php echo $age ?> ans</td>
                                    <td><?php echo $lasteresa == "NC" ? "Inconnu" : getDayfr($lasteresa->getDateResa()); ?></td>
                                    <td><span class="<?php echo $classadh ?>"><?php echo $adhesion ?></span></td>
                                    <td>
                                        <?php
                                        //statut actif
                                        if ($utilisateur->getStatut() == 1) {
                                            if ($forfaitConsultation != null) {
                                                ?>
                                                <span class="badge bg-blue"><?php echo htmlentities($forfaitConsultation->getNom()) ?></span> <?php echo getTime($restant) ?>
                                                <div class="progress">
                                                    <div class="progress progress-sm active">
                                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:" . $rapport . "%"; ?>"></div>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                echo '<span class="badge bg-red">NC</span> 0h';
                                            }
                                        } elseif ($utilisateur->getStatut() == 2) { //passer du statut inactif au statut archivé
                                            echo '<input type="checkbox" name="archiv_[]" class="minimal" value=' . $utilisateur->getId() . '>';
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                        </tbody> 
                    </table>
                </div><!-- .box-body -->
            </div><!-- .box -->
            <?php
        }
        ?>


    </div><!-- .col-xs-12 -->
</div><!-- .row -->

