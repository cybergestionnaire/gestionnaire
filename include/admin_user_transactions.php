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


 * ********* *///// Transactions de l'adhérent **********************/
// error_log('in admin_user_transactions.php -------------------------');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));

require_once("include/class/Utilisateur.class.php");
require_once("include/class/Forfait.class.php");

/* renouvellement adhésion
  lien vers impressions
  achat forfait atelier
  achat de forfait de consultation internet */

$id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : '';


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


// suppression d'un forfait d'un compte usager
if (isset($_GET["act"])) {
    $transac = isset($_GET["transac"]) ? $_GET["transac"] : '';
    $act = $_GET["act"];
    if ($act == "del") {
        $transaction = Transaction::getTransactionById($transac);
        if ($transaction->supprimer()) {
            $mesno = 35;
        } else {
            $mesno = 0;
        }
    }
}

// Information Utilisateur
$utilisateur = Utilisateur::getUtilisateurById($id_user);

$tarif = $utilisateur->getTarifAdhesion();
if ($tarif === null) {
    $prixtarif = "<span class=\"text-red\"><b>Aucune adh&eacute;sion associ&eacute;e !</b></span>";
} else {
    $prixtarif = "<span class=\"text-success\"><b>" . $tarif->getNom() . "(" . $tarif->getDonnee() . " &euro;)</b></span>";
}

// //paiements en attente adhesion
$transactionsEnAttente = $utilisateur->getTransactionsEnAttente();

//affichage du bouton renouvellement si -7 jours

$datetime1 = date_create($utilisateur->getDateRenouvellement());
$datetime2 = date_create(date("Y-m-d"));
$interval = date_diff($datetime2, $datetime1);

//Statut de la transaction
$forfaitArray = array(
    0 => "En attente de paiement",
    1 => "Pay&eacute;",
    2 => "Archiv&eacute;"
);
//statut des forfaits ateliers
$arraystatutforfait = array(
    1 => "En cours",
    2 => "Termin&eacute;"
);


// //nombre d'inscriptions en cours + lien vers historique atelier

$nbASencours = $utilisateur->getNBAteliersEtSessionsInscrit();

//nombre d'inscriptions validées hors forfait

$nbvalide = $utilisateur->getNBAteliersEtSessionsPresent(); // 1= total inscrit et validé

$nbForfait = $utilisateur->getNombreForfaitsAteliers();
$nbrestant = $nbForfait - $nbvalide; //restant apres dépense

$buyable = true;

if ($nbrestant >= 0) {
    $nbHorsForfait = "aucune";
} else {
    $nbHorsForfait = "<span class=\"text-red\">" . abs($nbrestant) . "&nbsp;&nbsp;</span><span class=\"btn bg-red btn-xs\" data-toggle=\"tooltip\" title=\"Ces ateliers n'ont pas &eacute;t&eacute; pay&eacute;s !\"><i class=\"fa fa-warning\"></i></span>";
}

//gestion des erreurs
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}
?>
<div class="row"> <!-- division en 3 rows par ligne -->

    <!--Adhesion et renouvellement -->
    <div class="col-md-6">
        <div class="box box-solid box-primary">
            <div class="box-header"><h3 class="box-title">Adh&eacute;sion</h3></div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Adh&eacute;rent </dt><dd><?php echo htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom()); ?> </dd>
                    <dt>Date de 1er adh&eacute;sion</dt><dd> le <span class="text-success"><b><?php echo dateFR($utilisateur->getDateInscription()) ?></b></span></dd>
                    <dt>Tarif</dt><dd><?php echo $prixtarif; ?></dd>
                    <dt>Renouvellement pr&eacute;vu </dt><dd> le <span class="text-red"><b><?php echo dateFR($utilisateur->getDateRenouvellement()); ?></b></span></dd>
                </dl>
                <?php
                // if (mysqli_num_rows($paiementAttente)) {
                // $row = mysqli_fetch_array($paiementAttente);
                // if (in_array($tarif,$row)) {
                // echo "<p class=\"lead\"><span class=\"text-red\">Attention le paiement est toujours en attente</span></p>
                // <a href=\"index.php?a=21&b=3&typetransac=adh&idtransac=".$row["id_transac"]."&iduser=".$id_user." \"><input type=\"submit\" value=\"Encaisser\" class=\"btn bg-default\"></a>
                // ";
                // }
                // }
                // code à vérifier !!!

                if ($transactionsEnAttente !== null) {
                    foreach ($transactionsEnAttente as $transactionEnAttente) {
                        if ($transactionEnAttente->getType() == "adh") {
                            echo "<p class=\"lead\"><span class=\"text-red\">Attention le paiement est toujours en attente</span></p>
                    <a href=\"index.php?a=21&b=3&typetransac=adh&idtransac=" . $transactionEnAttente->getId() . "&iduser=" . $id_user . " \"><input type=\"submit\" value=\"Encaisser\" class=\"btn bg-default\"></a>
                    ";
                        }
                    }
                }
                ?>

            </div><!-- .box-body -->
            <div class="box-footer">
                <?php
                if (($interval->format('%a')) < 10 or ($utilisateur->getDateRenouvellement() < date("Y-m-d"))) {
                    echo "<a href=\"index.php?a=21&b=3&typetransac=adh&iduser=" . $id_user . " \"><input type=\"submit\" value=\"Renouveller l'adh&eacute;sion\" class=\"btn bg-default\"></a>";
                }
                ?>

                <a href="index.php?a=1&b=2&iduser=<?php echo $id_user; ?>"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la fiche adh&eacute;rent</button></a> 
                &nbsp;<a href="index.php?a=21&b=3&typetransac=adh&iduser=<?php echo $id_user; ?>"><button type="submit" value="" class="btn bg-purple">Changer le tarif de l'adh&eacute;sion</button></a>

            </div>  
        </div><!-- .box -->
    </div><!-- .col-md-6 -->


    <!--Achat forfaits -->
    <!-- division en 3 rows par ligne -->
    <div class="col-md-6">
        <div class="box box-solid box-success">
            <div class="box-header"><h3 class="box-title">Forfait atelier</h3></div>
            <div class="box-body">
                <p>Inscription en cours et non valid&eacute;es : <b><?php echo $nbASencours; ?></b>
                    <?php
                    if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                        ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId(); ?>" class="btn btn-primary btn-xs">Voir les inscriptions</a></p>
                    <?php
                    }
                ?>
                <p>Inscription valid&eacute;es hors forfait : <b><?php echo $nbHorsForfait; ?></b></p>

                <?php
                /// forfait atelier utilisateur
                $forfaitsAtelier = $utilisateur->getForfaitsAtelier();

                if ($forfaitsAtelier === null) {
                    echo "<p>l'adh&eacute;rent n'a souscrit &agrave; aucun forfait atelier</p>";
                } else {
                    ?>

                    <div class="table">
                        <table class="table">
                            <thead>
                            <th>Nom du Tarif</th><th>Date d'achat</th><th>Nbr</th><th>D&eacute;pens&eacute;</th><th>Statut</th><th></th><th></th>
                            </thead>
                            <?php
                            foreach ($forfaitsAtelier as $forfaitAtelier) {
                                $transaction = $forfaitAtelier->getTransaction(); ?>
                                <tr>
                                    <td><?php echo $transaction->getTarif()->getNom(); ?></td>
                                    <td><?php echo $transaction->getDate(); ?></td>
                                    <td><?php echo $forfaitAtelier->getTotal(); ?></td>
                                    <td><?php echo $forfaitAtelier->getDepense(); ?></td>
                                    <td><?php echo $forfaitArray[$transaction->getStatut()]; ?></td>
                                    <td><?php echo $arraystatutforfait[$forfaitAtelier->getStatut()]; ?></td>
                                    <td>

                                        <?php
                                        if ($forfaitAtelier->getStatut() < 2) {
                                            // forfait atelier en cours, empecher un autre achat !
                                            $buyable = false; ?>
                                            <a href="index.php?a=21&b=3&typetransac=forfait&idtransac=<?php echo $transaction->getId(); ?>&iduser=<?php echo $id_user; ?>" ><button type="button"  data-toggle="tooltip"  title="Modifier/Encaisser" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button></a>
                                            <a href="index.php?a=6&act=del&transac=<?php echo $transaction->getId(); ?>&iduser=<?php echo $id_user; ?>"><button type="submit" name="submit" class="btn btn-warning btn-sm"  data-toggle="tooltip"  title="Supprimer"><i class="fa fa-trash-o"></i></button></a>

                                            <?php
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            if ($buyable) {
                ?>
                <div class="box-footer">
                    <a href="index.php?a=21&b=3&typetransac=forfait&iduser=<?php echo $id_user; ?>"><button type="submit" value="" class="btn btn-success"><i class="fa fa-cart-plus"></i>&nbsp; Ajouter un forfait</button></a>
                </div>    
                <?php
            }
            ?>
        </div><!-- .box -->
    </div><!-- .col-md-6 -->



    <!-- Forfait consultation pour epnconnect -->

    <div class="col-md-6">
        <div class="box box-solid box-danger">
            <div class="box-header"><h3 class="box-title">Forfait consultation</h3></div>
            <div class="box-body">

                <?php
// $transactemps = getTransactemps($id_user);

                $transaction = $utilisateur->getTransactionForfaitConsultation();
                $forfait = $utilisateur->getForfaitConsultation();

                if ($forfait !== null) {
                    $min = $tab_unite_temps_affectation[$forfait->getUniteConsultation()];
                    $tarifreferencetemps = $forfait->getDureeConsultation() * $min;

                    $restant = $utilisateur->getTempsrestant();
                    $rapport = round(($restant / $tarifreferencetemps) * 100); ?>

                    <div class="table">
                        <table class="table">
                            <thead><th>Nom</th><th>Date d'achat</th><th>Validit&eacute; </th><th>Statut</th><th></th></thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlentities($forfait->getNom()) . " (" . number_format($forfait->getPrix(), 2, ",", " "); ?> &euro;)</td>
                                    <td><?php echo $transaction->getDate(); ?> </td>
                                    <td>
                                        <?php echo $rapport . " % (" . getTime($restant) . ")"; ?>
                                        <div class="progress progress-sm active">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:" . $rapport . "%"; ?>"></div>
                                        </div>
                                    </td>
                                    <td><?php echo $forfaitArray[$transaction->getStatut()]; ?> </td>
                                    <td>
                                        <a href="index.php?a=21&b=3&typetransac=temps&idtransac=<?php echo $transaction->getId() ?>&iduser=<?php echo $id_user; ?>" ><button type="button"  data-toggle="tooltip"  title="Modifier" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button></a>
                                        &nbsp;<a href="index.php?a=6&act=del&type=temps&transac=<?php echo $transaction->getId(); ?>&iduser=<?php echo $id_user; ?>"><button type="submit" name="submit" class="btn btn-warning btn-sm"  data-toggle="tooltip"  title="Supprimer"><i class="fa fa-trash-o"></i></button></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!-- .box-body -->
                <?php
                } else {
                    ?>
                <p>Aucun achat de temps pour l'instant</p>
                <div class="box-footer"> 
                    <a href="index.php?a=21&b=3&typetransac=temps&iduser=<?php echo $id_user; ?>"><button type="submit" value="" class="btn bg-orange"><i class="fa fa-clock-o"></i>&nbsp;Ajouter du temps de consultation</button></a>
                </div>  
            </div><!-- .box -->
            <?php
                }
        ?>


    </div><!-- .col-md-6 -->


</div><!-- FIN ROW 1 -->

