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
//require_once("include/class/CSP.class.php");
//require_once("include/class/Ville.class.php");

if (isset($mess) && $mess != "") {
    echo $mess;
}

// Fichier de statistiques de l'application
// chargement des valeurs pour l'epn par défaut
$idEspace = $_SESSION['idepn'];
//si changment d'epn
if (isset($_POST['modifepn'])) {
    $idEspace = (string) filter_input(INPUT_POST, 'Pepn');
}
$espace = Espace::getEspaceById($idEspace);

// Choix de l'epn   -------------------------------------
//$espaces = getAllEPN();
$espaces = Espace::getEspaces();

$nbActifs = count(Utilisateur::getUtilisateursByStatut(1));
$nbInactifs = count(Utilisateur::getUtilisateursByStatut(2));
$nbArchives = count(Utilisateur::getUtilisateursByStatut(6));
$nbTotal = count(Utilisateur::getUtilisateurs());

// repartition homme/femme ------------
$countSexe = Utilisateur::statSexe($idEspace);
$nbH = $countSexe["H"];
$nbF = $countSexe["F"];
$nbI = $nbActifs - ($nbH + $nbF); // cas ou le sexe ne serait pas ou mal renseigné
// Total d'adherents

$villeActifCount = Utilisateur::statVilleActifs($idEspace);
$villeTotalCount = Utilisateur::statVilleTotal($idEspace);

$CSPCount = Utilisateur::statCSP($idEspace);

// repartition par tranche d'age -------
$nbTr1 = Utilisateur::statTranche(0, 6, $idEspace);
$nbTr2 = Utilisateur::statTranche(7, 13, $idEspace);
$nbTr3 = Utilisateur::statTranche(14, 17, $idEspace);
$nbTr4 = Utilisateur::statTranche(18, 25, $idEspace);
$nbTr5 = Utilisateur::statTranche(26, 45, $idEspace);
$nbTr6 = Utilisateur::statTranche(46, 65, $idEspace);
$nbTr7 = Utilisateur::statTranche(66, 75, $idEspace);
$nbTr8 = Utilisateur::statTranche(76, 110, $idEspace);


$month = date('m');
$year = date('Y');
//verification que le dossier images des stats existe.
$dossierimg = "img/chart/" . $year;
if (!is_dir("img")) {
    mkdir("img");
}
if (!is_dir("img/chart")) {
    mkdir("img/chart");
}
if (!is_dir($dossierimg)) {
    mkdir($dossierimg);
}

// Affichage
?>

<!-- NOM ESPACE --> 


<div class="row">
    <div class="col-md-6">
        <!-- DIV accès direct aux autres paramètres-->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Statistiques</h3>
                <div class="box-tools">
                    <form method="post" role="form">
                        <div class="input-group">

                            <select name="Pepn"  class="form-control pull-right" style="width: 200px;">
                            <?php
                            foreach ($espaces as $espace) {
                                if ($idEspace == $espace->getId()) {
                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                } else {
                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                }
                            }
                            ?></select>
                            <div class="input-group-btn">
                                <button type="submit" value="Rafraichir"  name="modifepn" class="btn btn-default" style="height: 34px;"><i class="fa fa-repeat"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <a class="btn btn-app disabled" href="index.php?a=5&b=1"><i class="fa fa-users"></i>Adhérents</a>
                <a class="btn btn-app" href="index.php?a=5&b=2" /><i class="fa fa-clock-o"></i>Réservations</a>
                <a class="btn btn-app" href="index.php?a=5&b=3"><i class="fa fa-print"></i>Impressions</a>
                <a class="btn btn-app" href="index.php?a=5&b=5" /><i class="fa fa-ticket" ></i>Sessions</a>
                <a class="btn btn-app" href="index.php?a=5&b=4" /><i class="fa fa-keyboard-o" ></i>Ateliers</a>
            </div><!-- /.box-body -->
        </div><!-- /.box -->   

        <?php
        if ($nbTotal > 0) { // condition pour affichage si pas d'adherents, message ! ?>

        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i>
                <h3 class="box-title">R&eacute;partition Homme / Femme (<?php echo htmlentities($espace->getNom()); ?>)</h3>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Actifs</dt><dd><span class="text-red"><?php echo $nbActifs ?></span></dd>
                    <dt>Inactifs</dt><dd><?php echo $nbInactifs; ?></dd>
                    <dt>Archivés</dt><dd><?php echo $nbArchives; ?></dd>
                </dl>



                <div class="statBar">
                    <div class="statText">Hommes </div>
                    <div class="statBarContainPurple">
                        <div style="width:<?php echo getPourcent($nbH, $nbActifs); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbH, $nbActifs); ?></div>
                    </div> (<?php echo $nbH ?>)
                </div>
                <div class="clear"></div>
                <div class="statBar">
                    <div class="statText">Femmes</div>
                    <div class="statBarContainPurple">
                        <div style="width:<?php echo getPourcent($nbF, $nbActifs); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbF, $nbActifs); ?></div>
                    </div> (<?php echo $nbF ?>)
                </div>
<?php if ($nbI > 0) { ?>
                <div class="clear"></div>
                <div class="statBar">
                    <div class="statText">Non renseign&eacute;s</div>
                    <div class="statBarContainPurple">
                        <div style="width:<?php echo getPourcent($nbI, $nbActifs); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbI, $nbActifs); ?></div>
                    </div> (<?php echo $nbI ?>)
                </div>
<?php } ?>
            </div>
        </div>



            <!-- REPARTITION PAR TRANCHE D AGE-->

        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i>
                <h3 class="box-title">R&eacute;partition par tranche d'&acirc;ge (<?php echo htmlentities($espace->getNom()); ?>)</h3>
            </div>
            <div class="box-body">

                <div class="statBar">
                    <div class="statText">0  &agrave;   6 :</div>
                    <div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr1, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr1, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr1 ?>)
                </div>
                <div class="clear"></div>
 
                <div class="statBar">
                    <div class="statText">7  &agrave;  11 : </div>
                    <div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr2, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr2, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr2 ?>)
                </div>
                <div class="clear"></div>

                <div class="statBar">
                    <div class="statText">12  &agrave;  17 :  </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr3, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr3, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr3 ?>)
                </div>
                <div class="clear"></div>
                
                <div class="statBar">
                    <div class="statText">18  &agrave;  25 : </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr4, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr4, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr4 ?>)
                </div>
                <div class="clear"></div>
                
                <div class="statBar">
                    <div class="statText">25  &agrave;  45 :  </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr5, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr5, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr5 ?>)
                </div>
                <div class="clear"></div>
                
                <div class="statBar">
                    <div class="statText">46  &agrave;  65 : </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr6, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr6, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr6 ?>)
                </div>
                <div class="clear"></div>
                
                <div class="statBar">
                    <div class="statText">66  &agrave; 75 :  </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr7, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr7, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr7 ?>)
                </div>
                <div class="clear"></div>
                
                <div class="statBar">
                    <div class="statText">75  et + :  </div><div class="statBarContainDBlue">
                        <div style="width:<?php echo getPourcent($nbTr8, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr8, $nbActifs); ?></div>
                    </div> (<?php echo $nbTr8 ?>)
                </div>
                <div class="clear"></div>
            </div>
        </div>

            <!-- REPARTITION PAR VILLE-->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Actifs / ville</a></li>
                <li><a href="#tab_2-2" data-toggle="tab">Total adh / ville</a></li>
                <li class="pull-left header">R&eacute;partition par ville</li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                        <?php

                        if (count($villeActifCount) > 0) {
                            foreach ($villeActifCount as $ville => $nombre) {
                                
                    ?>

                    <div class="statBar">
                        <div class="statTextVille"><?php echo htmlentities($ville) . "  <small>(" . $nombre . " adh)</small>"; ?> </div>
                        <div class="statBarContainBlue">
                            <div style="width:<?php echo getPourcent($nombre, $nbActifs); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nombre, $nbActifs); ?></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                                <?php
                            }
                        } ?>

                </div>
                <div class="tab-pane" id="tab_2-2">
                        <?php
                        if (count($villeTotalCount) > 0) {
                            foreach ($villeTotalCount as $ville => $nombre) { 
                                ?>

                    <div class="statBar">
                        <div class="statTextVille"><?php echo htmlentities($ville) . "  <small>(" . $nombre . " adh)</small>"; ?> </div>
                        <div class="statBarContainBlue">
                            <div style="width:<?php echo getPourcent($nombre, $nbTotal); ?>" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nombre, $nbTotal); ?></div>

                        </div>
                    </div>
                    <div class="clear"></div>
                    <?php
                }
            } ?>

                </div>




            </div><!-- content -->


        </div><!--/panel -->
    </div><!-- /col 1 -->



    <div class="col-md-6">

            <!-- REPARTITION PAR CSP-->
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i><h3 class="box-title">R&eacute;partition par CSP (<?php echo htmlentities($espace->getNom()); ?>)</h3>
            </div>
            <div class="box-body">
                    <?php
                    
                    if (count($CSPCount) > 0) {
                        foreach ($CSPCount as $csp => $nb) {
                    ?>

                <div class="statBar">
                    <div class="statTextVille"><?php echo htmlentities($csp); ?></div>
                    <div class="statBarContainPurple">
                        <div style="width:<?php echo getPourcent($nb, $nbActifs); ?>" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nb, $nbActifs); ?></div></div>
                </div>
                <div class="clear"></div>

                <?php
                }
            } ?>
            </div>
        </div>


            <!--repartition des inscrits par mois et par annee --->
        <div class="box box-primary">
            <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Nouveaux inscrits par mois (<?php echo htmlentities($espace->getNom()); ?>)</h3></div>
            <div class="box-body">
                <table class="table"> 
                    <thead><tr><th></th><th>Nouveaux inscrits</th><th>Dont actifs</th></tr></thead>
                    <tbody>
                        <?php
                        for ($i = 1; $i <= $month; ++$i) {
                            $nbNewAdhActifs = statInscription($i, 1, $idEspace);
                            $nbNewAdhInactifs = statInscription($i, 2, $idEspace);
                            //debug($nbNewAdhInactifs);
                            $totalNewadh = $nbNewAdhActifs + $nbNewAdhInactifs;
                            //debug($nbNewAdh);
                            echo '<tr><td >' . getMonth($i) . '</td>
              <td >' . $totalNewadh . '</td>
              <td >' . $nbNewAdhActifs . ' (' . getPourcent($nbNewAdhActifs, $nbTotal) . ')</td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>


            <?php
        } else {
            echo geterror(36);
        }
        ?>


    </div>
</div><!-- /col /row -->
