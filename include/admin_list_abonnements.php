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


  //Liste des adhérents qui ont un forfait atelier
 */



// admin --- Utilisateur
$term = isset($_POST["term"]) ? $_POST["term"] : '';

$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}

$epn = $_SESSION["idepn"];
?>
<!--
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="box box-solid bg-yellow">
            <div class="box-header"><h3 class="box-title">Recherche</h3></div>
            <div class="box-body">
                <form method="post" action="index.php?a=8">
                    <div class="form-group">
                        <label>Entrez son nom ou pr&eacute;nom </label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="term" class="form-control">
                            <span class="input-group-btn">  <button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-xs-8">

        <?php
        // Les adhérents // MODIF 2012 : liste des 25 derniers inscrits......

        $utilisateurs = Utilisateur::getUtilisateursAvecUnForfaitAtelierEnCours();

        if ($utilisateurs === null) {
            echo getError(6);
        } else { // affichage du resultat
            $nb = count($utilisateurs);

            if ($nb > 0) {
                ?>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Liste des forfaits en cours pour les adh&eacute;rents inscrits</h3>
                        <div class="box-tools">
                            <div class="input-group">
                                <form method="post" action="index.php?a=8">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                        <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                                    </div>
                                </form>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Pr&eacute;nom</th>
                                    <th>Date d'achat</th>
                                    <th>Ateliers</th>
                                    <th>D&eacute;pense</th>
                                    <th>Restant</th>
                                    <th>En cours</th>
                                    <th>Hors forfait</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php
                                foreach ($utilisateurs as $utilisateur) {
                                    $forfaitAtelier = $utilisateur->getForfaitsAtelier()[0];
                                    $transaction = $forfaitAtelier->getTransaction();

                                    $nbASInscrit = $utilisateur->getNBAteliersEtSessionsInscrit();

                                    $nbASPresent = $utilisateur->getNBAteliersEtSessionsPresent();
                                    $nbForfait = $utilisateur->getNbForfaitsArchives();
                                    $nbrestant = $nbForfait - $nbASPresent;
                                    if ($nbrestant >= 0) {
                                        $nbHorsForfait = "0";
                                    } else {
                                        $nbHorsForfait = "<span class=\"text-red\">" . abs($nbrestant) . "&nbsp;&nbsp;</span><span class=\"btn bg-red btn-xs\" data-toggle=\"tooltip\" title=\"Ces ateliers n'ont pas &eacute;t&eacute; pay&eacute;s !\"><i class=\"fa fa-warning\"></i></span>";
                                    } ?>
                                    <tr class="<?php echo $class ?>">
                                        <td><?php echo htmlentities($utilisateur->getNom()) ?></td>
                                        <td><?php echo htmlentities($utilisateur->getPrenom()) ?></td>
                                        <td><?php echo $transaction->getDate() ?></td>
                                        <td><?php echo $forfaitAtelier->getTotal() ?></td>

                                        <td><?php echo $forfaitAtelier->getDepense() ?></td>
                                        <td><?php echo $forfaitAtelier->getTotal() - $forfaitAtelier->getDepense() ?></td>
                                        <td><?php echo $nbASInscrit ?></td>
                                        <td><?php echo $nbHorsForfait ?></td>
                                        <td>
                                            <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId() ?>" class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>
                                            <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ?>"  class="btn bg-green btn-sm"  data-toggle="tooltip" title="Historique ateliers"><i class="fa fa-keyboard-o"></i></a>
                                            <!--<a href="lettre_atelier.php?user=<?php echo $utilisateur->getId() ?>&epn=<?php echo $epn ?>" target="_blank"  class="btn btn-info sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa  fa-envelope"></i></a>-->
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </tbody> 
                        </table>
                    </div>
                </div>


                <?php
            } else {
                echo "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-warning\"></i>
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun forfait enregistr&eacute; pour l'instant</div>";
            }
        }
        ?>


    </div><!-- /col-->

    <?php
    $result = getAllUserAtelier($epn);
    $nbadh = mysqli_num_rows($result);

    if ($nbadh > 0) {
        ?>
        <!-- colonne 2 : la liste des adhérent inscrits à un atelier -->
        <div class="col-xs-4">
            <div class="box box-info">
                <div class="box-header"><h3 class="box-title">Export des adh&eacute;rents inscrits aux ateliers/session en cours</h3></div>
                <div class="box-body">
                    <!--<p>Cochez les adhérents dont vous souhaitez exporter les données mail et téléphone au format csv</p>-->
                    <form role="form" method="POST" action="courriers/csv_exportmail.php?epn=<?php echo $epn; ?>">
                        <table class="table">
                            <thead><tr><th>Nom</th><th>Pr&eacute;nom</th><!--<th>Export</th>--></thead>
                            <tbody>
                                <?php
                                for ($i = 1; $i <= $nbadh; $i++) {
                                    $rowadh = mysqli_fetch_array($result);
                                    //$titreatelier=get ?>
                                    <tr><td><?php echo $rowadh["nom_user"] ?></td>
                                        <td><?php echo $rowadh["prenom_user"] ?></td>

                <!--<td><input type="checkbox" name="export_[]" value="<?php echo $rowadh["id_user"]; ?>"></td>--></tr>

                                <?php
                                } ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="box-footer">
                    <input type="submit" class="btn bg-olive" value="G&eacute;n&eacute;rer la liste des coordonn&eacute;es">
                </div>
            </div>
        </div><!-- /col-->

        <?php
    }
    ?>

</div><!-- /row-->

