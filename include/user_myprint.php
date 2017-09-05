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

 */

require_once("include/class/Utilisateur.class.php");

// fichier de gestion des impressions
$month = date('m');
$utilisateur = Utilisateur::getUtilisateurById($_SESSION['iduser']);

if ($utilisateur->hasPrint()) {
    // infos impressions
    $restant = $utilisateur->getImpressionCredit() - $utilisateur->getImpressionDebit();

    $statutPrint = array(
        0 => "pas pay&eacute;",
        1 => "pay&eacute;",
    ); ?>
    <div class="row">

        <section class="col-lg-4 connectedSortable">        

            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Mes impressions</h3></div>
                <div class="box-body">
                    <h4><b><?php echo number_format($utilisateur->getImpressionDebit(), 2, ',', ' ') ?></b>  &euro; ont &eacute;t&eacute; d&eacute;pens&eacute;s.</h4>
                    <h4><b><?php echo number_format($utilisateur->getImpressionCredit(), 2, ',', ' ') ?></b> &euro; ont &eacute;t&eacute; cr&eacute;dit&eacute;s.</h4>
                    <br>
                    <?php
                    if (($restant) > 0) {
                        echo '<h4><span class="text-green">cr&eacute;dit restant sur le compte : ' . number_format($restant, 2, ',', ' ') . ' &euro; </span></h4>';
                    } elseif (($restant) < 0) {
                        echo '<h4><span class="text-red">Le compte est d&eacute;biteur de ' . number_format($restant, 2, ',', ' ') . ' &euro; </span></h4>';
                    } elseif (($restant) == 0) {
                        echo '<h4>Aucun cr&eacute;dit restant sur le compte</h4>';
                    } ?>
                </div>
            </div>
        </section>

        <section class="col-lg-6 connectedSortable">
            <?php
            // ARCHIVES DES IMPRESSIONS

            $impressions = $utilisateur->getImpressions();
    if ($impressions !== null && count($impressions) > 0) {
        ?>
                <div class="box box-primary">
                    <div class="box-header"><h3 class="box-title">Historique de vos impressions</h3></div>
                    <div class="box-body">
                        <table class="table"> 
                            <thead><tr><th>Date</th><th>Nbre de pages</th><th>Tarif</th><th>Prix</th><th>Statut</th></tr></thead>
                            <tbody>
                                <?php
                                foreach ($impressions as $impression) {
                                    if ($impression->getStatut() != 2) {
                                        $tarif = $impression->getTarif(); ?>
                                        <tr>
                                            <td><?php echo $impression->getDate() ?></td>
                                            <td><?php echo $impression->getNombreImpression() ?></td>
                                            <td><?php echo number_format($tarif->getDonnee(), 2, ',', ' ') ?> &euro;</td>
                                            <td><?php echo number_format($impression->getNombreImpression() * $tarif->getDonnee(), 2, ',', ' ') ?> &nbsp;&euro;</td>
                                            <?php
                                            // pas possible puisque ce sont les impression d'un uitilisateur !!!
                                            // if ($externe == 1) {
                                            // echo '<td>' . $nomexterne . '</td>';
                                            // }

                                            if ($impression->getStatut() == 0) {
                                                echo "<td><p class=\"text-red\">" . $statutPrint[$impression->getStatut()] . "</p></td>";
                                            } else {
                                                // transaction enregistrée
                                                echo "<td><p class=\"text-light-blue\">" . $statutPrint[$impression->getStatut()] . "</p></td> <td>&nbsp;</td>";
                                            } ?>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <?php
    }
} else {
    echo "<h4 class=\"alert_info\">Pas d'historique d'impressions</h4>";
}
?>


<!--
<article class="module_help width_half"><header><h3>Aide</h3></header>
<div class="module_content">
    <p>Si vous d&eacute;sirez imprimer depuis internet, demandez conseil à l'animatrice.</p>
    <p>Les tarifs : <b>0.15&euro;</b> la page noir et blanc, <b>0.30&euro;</b> la page couleur.</p>
    <p>Par d&eacute;faut l'imprimante est param&eacute;tr&eacute;e pour imprimer en noir et blanc. Pour imprimer en couleur, cliquez sur l'imprimante <a href="#">Couleur</a> pour la s&eacute;lectionner dans le menu d&eacute;roulant des imprimantes. Lancer ensuite votre impression.</p>
</div></article>-->
