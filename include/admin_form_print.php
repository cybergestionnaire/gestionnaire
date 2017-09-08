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
// error_log('in admin_form_print.php');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));

//require_once('include/class/Utilisateur.class.php');
//require_once('include/class/Tarif.class.php');

//debug($tarifs);
// recuperation de l'identifiant utilisateur
$id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
$act = isset($_GET["act"]) ? $_GET["act"] : '';
$userext = isset($_GET['ext']) ? $_GET["ext"] : '';
$caisse = isset($_GET["caisse"]) ? $_GET["caisse"] : '';
//debug($caisse);


if ($id_user == "ext") { //en cas d'utilisateur qui n'est pas dans la base, utilisation de l'utilisateur externe créé dans la MAJ
    $userext = Utilisateur::getIduserexterne();
    if ($userext != false) {
        $id_user = $userext;
        $userext = 1;
    } else {
        echo "Attention l'utilisateur externe n'existe pas !";
    }
}

//recuperation des données utilisateur
// $rowp = getuser($id_user);
$utilisateur = Utilisateur::getUtilisateurById($id_user);

// initialisation des variables
if ($caisse == 0) {
    $date_p = date("Y-m-d H:i");
    $debit = 0;
    $transac = 0;
    $credit_p = 0;
}
if ($caisse == 1) {
    $date_p = isset($_POST["date"]) ? $_POST["date"] : '';
}

// boucler pour faire le total des dépenses et du credit
$totalprint = $utilisateur->getImpressionDebit();
$credituser = $utilisateur->getImpressionCredit();
//total credite
//$totalcredit=$credituser+$totalprint;
$totalrestant = $credituser - $totalprint;

//tableau des moyens de paiement
$paiementmoyen = array(
    1 => "Esp&egrave;ces",
    2 => "Ch&egrave;que",
    3 => "Carte Bleue");
?>

<div class="row">
    <!-- ajouter un credit au compte d'impression-->
    <div class="col-lg-3 col-xs-6">
        <form method="post" action="index.php?a=21&b=2&caisse=0&act=4&iduser=<?php echo $id_user; ?> ">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">Cr&eacute;diter le compte de&nbsp;<b><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ?></b></h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Valeur (en &euro;)</label>
                        <input type="hidden" name="datec" value="<?php echo $date_p; ?>">
                        <input type="text" name="credit" class="form-control">
                    </div>
                </div>

                <div class="box-footer"><input type="submit" value="Ajouter" name="submit" class="btn btn-success"></div>
            </div>
        </form>
    </div>


    <div class="col-lg-3 col-xs-6">
        <form method="post" action="index.php?a=21&b=2&caisse=1&act=&iduser=<?php echo $id_user; ?>&ext=<?php echo $userext; ?> ">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">Impressions de&nbsp;<b><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ?></b></h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Date :</label>
                        <input type="text" name="date" class="form-control" value="<?php echo $date_p ?>">
                    </div>

                    <?php
                    /// edition de la liste des tarifs concernant les impressions
                    // recuperation des tarifs disponibles
                    //
    //$tarifs = getTarifs(1); //1= impressions
                    //$nbt    = mysqli_num_rows($tarifs);

                    if ($caisse == 1) {
                        //envoi des codes de transaction pour le calcul
                        $transactions = isset($_POST["transact"]) ? $_POST["transact"] : '';
                        $debits = isset($_POST["debit"]) ? $_POST["debit"] : '';

                        $nbts = count($transactions);
                        //$donnees = array_chunk( $transactions ,5);
                        //debug($donnees);
                        for ($i = 0; $i < $nbts; $i++) {
                            // $tab_transac = $donnees[$i];

                            $idTarif = $transactions[$i];
                            $debit = $debits[$i];
                            $tarif = Tarif::getTarifById($idTarif);
                            // $nom_tarif    = $tab_transac[2];
                            // $donnee_tarif = $tab_transac[3];
                            ?>        
                            <div class="form-group">
                                <label><?php echo htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) ?> &euro;)</label>
                                <input type="hidden" name="transact[]" value="<?php echo $idTarif ?>" />
                                <input type="text"   name="debit[]" value="<?php echo $debit ?>" class="form-control">
                            </div>
                            <?php
                            //debug($date_p);
                        }
                    } else {
                        $tarifs = Tarif::getTarifsByCategorie(1);
                        // while($row = mysqli_fetch_array($tarifs)) {
                        foreach ($tarifs as $tarif) {
                            ?>
                            <div class="form-group">
                                <label><?php echo htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) ?> &euro;)</label>
                                <input type="hidden" name="transact[]" value="<?php echo $tarif->getId() ?>" />
                                <input type="text"   name="debit[]" value="<?php echo $debit ?>" class="form-control">
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div><!-- .box-body -->
                <div class="box-footer"><input type="submit" value="Calculer" name="submit" class="btn btn-success"></div>
            </div><!-- .box -->
        </form>
    </div><!-- /col -->




    <?php
    if ($caisse == 1) {
        ?>  
        <div class="col-md-6">
            <form method="post" action="index.php?a=21&b=2&caisse=0&act=1&iduser=<?php echo $id_user; ?>&ext=<?php echo $userext; ?>" enctype="multipart/form-data">
                <div class="box box-success">
                    <div class="box-header"><h3 class="box-title">Validation de la Transaction</h3></div>

                    <div class="box_body">
                        <table class="table">
                            <thead><th>Nom tarif</th><th>Nb de pages</th><th>Prix</th></thead>
                            <?php
                            $nbt = count($transactions);
        //$donnees = array_chunk( $transactions ,5);
        $total = 0;
        for ($i = 0; $i < $nbt; $i++) {
            $idTarif = $transactions[$i];
            $debit = $debits[$i];
            $tarif = Tarif::getTarifById($idTarif);
            // $tab_transac  = $donnees[$i];
            // $debit_p      = $tab_transac[0];
            // $tarif_p      = $tab_transac[1];
            // $nom_tarif    = $tab_transac[2];
            // $donnee_tarif = $tab_transac[3];
            // $statut_p     = $tab_transac[4];

            $prix = round(($debit * $tarif->getDonnee()), 2);
            $total = $total + $prix; ?>


                                <tr>
                                    <td><?php echo htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) ?> &euro;)</td>
                                    <td><?php echo $debit ?></td>
                                    <td><?php echo number_format($prix, 2, ',', ' ') ?> &euro;
                                        <input type="hidden" name="printUIdTarif[]" value="<?php echo $idTarif ?>" />
                                        <input type="hidden" name="printUDebit[]" value="<?php echo $debit ?>" />
                                        <input type="hidden" name="printUPrix[]" value="<?php echo $prix ?>" />
                                    </td>
                                </tr>
                                <?php
        } ?>
                            <tr>
                                <td>total</td>
                                <td></td>
                                <td><?php echo number_format($total, 2, ',', ' ') ?> &euro;</td>
                            </tr>
                            <tr>
                                <td>Reliquat sur le compte : </td>
                                <td><input type="hidden" name="date" value="<?php echo $date_p ?>"></td>
                                <td><?php echo number_format($totalrestant, 2, ',', ' '); ?>&nbsp;&euro;</td>
                            </tr>
                            <?php
                            // if (($total - $totalrestant) < 0) {
                            // } else {
                            // }
                            ///en cas de credit positif envoyer valeur
                            if ($totalrestant >= $total) {
                                $du = "0";
                                $paiement = 0;
                            } else {
                                $du = $total - $totalrestant;
                                $paiement = 1;
                            } ?>
                            <tr>
                                <td><b>Total d&ucirc; : </b></td>
                                <td></td>
                                <td><b><?php echo number_format($du, 2, ',', ' '); ?>&nbsp;&euro;<input type="hidden" name="du" value="<?php echo $du ?>" /></b></td>
                            </tr>

                            <tr>
                                <td>Moyen de paiement</td>
                                <td colspan="2">
                                    <select name="moyen_paiement" class="form-control">
                                        <?php
                                        foreach ($paiementmoyen as $key => $value) {
                                            echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                        } ?>
                                    </select>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Cr&eacute;dit : &nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Rentrez une somme diff&eacute;rente du total d&ucirc; si l'adh&eacute;rent paye plus ou moins selon le reliquat."><i class="fa fa-info"></i></small></td>
                                <td colspan="2">
                                    <input type="text" name="credit" value="<?php echo isset($_GET["credit"]) ? $_GET["credit"] : ""; ?>" class="form-control">
                                    <input type="hidden" name="paiement" value="<?php echo $paiement; ?>" >
                                </td>
                            </tr>
                            <?php
                            if ($_GET["ext"] == 1) {
                                ?>
                                <tr>
                                    <td>Nom pr&eacute;nom</td>
                                    <td colspan="2"><input type="text" name="nomuser"  placeholder="Veuillez entrer le nom et pr&eacute;nom" class="form-control"></td>
                                </tr>
                                <?php
                            } ?>

                        </table>
                    </div><!-- .box-body -->
                    <div class="box-footer">
                        <input type="submit" value="En attente" name="submit" class="btn bg-purple ">
                        <input type="submit" value="Encaisser" name="submit" class="btn btn-primary">
                        <a href="index.php?a=21&b=1&caisse=&act=&iduser=<?php echo $id_user; ?> " class="btn btn-default">Annuler</a>
                    </div>
                </div><!-- .box -->
            </form>


        </div><!-- .col-md-6 -->
    </div><!-- .row -->
    <?php
    }
?>