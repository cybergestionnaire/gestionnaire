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

// error_log('in admin_modif_transac.php -------------------------');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));
// formulaire de modification de transaction

//require_once("include/class/Utilisateur.class.php");
//require_once("include/class/Tarif.class.php");
//require_once("include/class/Forfait.class.php");

// récuperation des variables

$id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
$b = isset($_GET["b"]) ? $_GET["b"] : '';
$typeTransac = isset($_GET["typetransac"]) ? $_GET["typetransac"] : '';
$transac = isset($_GET["idtransac"]) ? $_GET["idtransac"] : '';

// Information Utilisateur
// $row = getUser($id_user);
// $dateinsc     = $row["date_insc_user"];
// $daterenouv   = $row["dateRen_user"];
// $nom          = $row["nom_user"];
// $prenom       = $row["prenom_user"];
// $temps        = $row["temps_user"];

$utilisateur = Utilisateur::getUtilisateurById($id_user);
$dateinsc = $utilisateur->getDateInscription();
$daterenouv = $utilisateur->getDateRenouvellement();
$nom = $utilisateur->getNom();
$prenom = $utilisateur->getPrenom();
$temps = $utilisateur->getIdTarifConsultation();



switch ($typeTransac) {
    //les impressions
    case "p":

        $url_redirect = "index.php?a=21&b=3&idtransac=" . $transac . "&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        $annuler = "index.php?a=21&b=1&caisse=&act=&iduser=" . $id_user;
        $titre = "Modification d'une impression pour " . htmlentities($prenom . " " . $nom) . " ";

        break;
    //les adhesions
    case "adh":
        $url_redirect = "index.php?a=21&b=3&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        $annuler = "index.php?a=1&b=2&iduser=" . $id_user;
        if ($transac != '') {
            $titre = "Encaisser l'adh&eacute;sion";
        } else {
            $titre = "Renouveller ou modifier l'adh&eacute;sion";
        }

        $idTarif = $utilisateur->getIdTarifAdhesion();
        $adhesiontarif = $utilisateur->getTarifAdhesion();

        break;

    //les forfaits pour les ateliers
    case "forfait":
        $annuler = "index.php?a=6&iduser=" . $id_user;
        $tarifsAtelier = Tarif::getTarifsByCategorie(5);
        if ($transac != '') {
            //modification d'une transaction
            $titre = "Modifier/Encaisser un forfait pour " . htmlentities($nom) . " " . htmlentities($prenom);
            $transaction = Transaction::getTransactionById($transac);
            $nbrforfait = $transaction->getNombreForfait();
            $datef = $transaction->getDate();
            $forfait_user = $transaction->getIdTarif();
            // $rowf         = getForfait($transac);
            // $nbrforfait   = $rowf["nbr_forfait"];
            // $datef        = $rowf["date_transac"];
            // $forfait_user = $rowf["id_tarif"];
            $url_redirect = "index.php?a=21&b=3&idtransac=" . $transac . "&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        } else {
            //nouveau forfait a crediter
            $titre = "Encaisser un forfait pour " . htmlentities($nom) . " " . htmlentities($prenom);
            $forfait_user = 0; // inutile ?
            $datef = date("Y-m-d");
            $nbrforfait = 1;
            $url_redirect = "index.php?a=21&b=3&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        }

        break;

    //les forfaits temps consultation
    case "temps":
        $titre = "Ajouter du temps de consultation pour " . htmlentities($nom) . " " . htmlentities($prenom);
        $forfaits = Forfait::getForfaits();
        if ($transac != '') {
            $url_redirect = "index.php?a=21&b=3&idtransac=" . $transac . "&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        } else {
            $url_redirect = "index.php?a=21&b=3&typetransac=" . $typeTransac . "&iduser=" . $id_user;
        }
        $annuler = "index.php?a=6&iduser=" . $id_user;
        $forfait_user = $temps;

        $datef = date("Y-m-d");
        $nbrforfait = 1;

        break;
}
?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-success">
            <form method="post" action="<?php echo $url_redirect; ?> " role="form" >
                <div class="box-header"><h3 class="box-title"><?php echo $titre; ?></h3></div>
                <div class="box-body">
                    <?php
//tableau des moyens de paiement
                    $paiementmoyen = array(1 => "Esp&egrave;ces", 2 => "Ch&egrave;que", 3 => "Carte Bleue");
// Modification d'une impression
                    if ($typeTransac == "p") {
                        $impression = Impression::getImpressionById($transac);
                        // $print = mysqli_fetch_array(getPrintFromID($transac));
                        // Si l'utilisateur est externe, affichage du champs avec le nom
                        $userext = Utilisateur::getIduserexterne();
                        // if ($userext == $print["print_user"]){
                        $externe = 0;
                        if ($userext == $impression->getIdUtilisateur()) {
                            $externe = 1;
                        }

                        // $date_p     = $print["print_date"];
                        // $statut_p   = $print["print_statut"];
                        // $paiement_p = $print["print_paiement"];
                        $date_p = $impression->getDate();
                        $statut_p = $impression->getStatut();
                        $paiement_p = $impression->getPaiement();

                        // recuperation des tarifs disponibles
                        //1= impressions
                        $tarifs = Tarif::getTarifsByCategorie(1);
                        //le prix indicatif
                        //  debug($_POST);
                        //recalcul ?
                        if (isset($_POST["recalculer"])) {
                            $debit_p = $_POST["debitprint"];
                            $tarif_p = Tarif::getTarifById($_POST["tarifprint"]);
                            $credit_p = round(($_POST["debitprint"] * $tarif_p->getDonnee()), 2);
                            $prix = $credit_p;
                        } else {
                            $tarif_p = $impression->getTarif();
                            $credit_p = $impression->getCredit();
                            $debit_p = $impression->getNombreImpression();
                            $prix = round(($debit_p * $tarif_p->getDonnee()), 2);
                        }
                        /*
                          if($credit_p==0){
                          $credit_p=$prix;
                          }
                         */
                        //griser les rubrique si c'est du credit unique
                        if ($statut_p == 2) {
                            $disable = "disabled /";
                        } else {
                            $disable = "";
                        } ?>

                        <div class="form-group">
                            <label>Date</label>
                            <div class="row">
                                <div class="col-xs-4"><input type="text" name="date" value="<?php echo $date_p; ?>" class="form-control"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nombre de pages</label>
                            <div class="row">
                                <div class="col-xs-4"><input type="text" name="debitprint" value="<?php echo $debit_p; ?>" class="form-control" ></div>
                                <input class="btn bg-green" type="submit" value="recalculer" name="recalculer" action="index.php?a=21&b=3&typetransac=p&idtransac="<?php echo $transac; ?>"&iduser="<?php echo $id_user; ?>"">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tarif</label>
                            <div class="row">
                                <div class="col-xs-6">    
                                    <select name="tarifprint" class="form-control" <?php echo $disable; ?>>
                                        <?php
                                        // foreach ($tarifs AS $key => $value) {
                                        foreach ($tarifs as $tarif) {
                                            if ($tarif_p->getId() == $tarif->getId()) {
                                                echo "<option value=\"" . $tarif->getId() . "\" selected>" . htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) . " &euro;)</option>";
                                            } else {
                                                echo "<option value=\"" . $tarif->getId() . "\">" . htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) . " &euro;)</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Moyen de paiement</label>
                            <div class="row">
                                <div class="col-xs-6">
                                    <select name="moyen_paiement" class="form-control">
                                        <?php
                                        foreach ($paiementmoyen as $key => $value) {
                                            if ($paiement_p == $key) {
                                                echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                            } else {
                                                echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <?php
                        if ($externe == 1) {
                            ?>
                            <br>
                            <div class="form-group">
                                <label>Nom pr&eacute;nom</label>
                                <input type="text" name="nomuser"  placeholder="Veuillez entrer le nom et pr&eacute;nom" class="form-control" value="<?php echo $print["print_userexterne"]; ?>">
                            </div>
                            <?php
                        } ?>


                        <div class="form-group">
                            <label>Credit</label>
                            <div class="row">
                                <div class="col-xs-4">
                                    <!-- <input type="hidden" name="statutprint" value="<?php echo $statut_p; ?>">-->
                                    <input type="text" name="creditprint" value="<?php echo $credit_p; ?>" class="form-control">
                                </div>
                            </div>

                        </div>
                        <?php
                    }
///***fin modif transaction impression***///
// transaction adhesion ****//
                    if ($typeTransac == "adh") {
                        // $dateinsc   = $row["date_insc_user"];
                        // $daterenouv = $row["dateRen_user"];
                        $dateinsc = $utilisateur->getDateInscription();
                        $daterenouv = $utilisateur->getDateRenouvellement();
                        //date de renouvellement adhesion automatiquement crée
                        $today = date("Y-m-d");
                        $daterenouv2 = date_create($today);
                        date_add($daterenouv2, date_interval_create_from_date_string('365 days'));
                        $daterenouv2 = date_format($daterenouv2, 'Y-m-d');
                        $tarifsAdhesion = Tarif::getTarifsByCategorie(2); ?>
                        <div class="form-group">
                            <label>Date de 1er inscription</label>
                            <div class="row">
                                <div class="col-xs-4"><input type="text" value="<?php echo $dateinsc; ?>" class="form-control" disabled></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date de renouvellement</label>
                            <div class="row">
                                <div class="col-xs-4"><input type="text" value="<?php echo $daterenouv; ?>" class="form-control" disabled></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date de renouvellement prochain</label>
                            <div class="row">
                                <div class="col-xs-4">
                                    <input type="text" name="daterenouv" value="<?php echo $daterenouv2; ?>" class="form-control">
                                    <input name="date" value="<?php echo $today; ?>" hidden>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>renouvellement de l'adh&eacute;sion au tarif: </label>
                            <select name="tarif_adh" class="form-control" >
                                <?php
                                // foreach ($tarifadhs AS $key => $value) {
                                // if ($tarif == $key) {
                                // echo "<option value=\"".$key."\" selected>".$value."</option>";
                                // } else {
                                // echo "<option value=\"".$key."\">".$value."</option>";
                                // }
                                // }
                                foreach ($tarifsAdhesion as $tarif) {
                                    if ($tarif->getId() == $idTarif) {
                                        echo "<option value=\"" . $tarif->getId() . "\" selected>" . htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) . " &euro;)</option>";
                                    } else {
                                        echo "<option value=\"" . $tarif->getId() . "\">" . htmlentities($tarif->getNom() . ' (' . number_format($tarif->getDonnee(), 2, ',', ' ')) . " &euro;)</option>";
                                    }
                                } ?>
                            </select>
                        </div>
                        <?php
                        if (isset($transac)) {
                            echo '<div><input name="idtransac" value="' . $transac . '" hidden></div>';
                        } ?>



                        <?php
                    }
                    ///**Fin modification des adhesions
                    ///***Forfait atelier ou consultation
                    if (($typeTransac == "forfait") or ($typeTransac == "temps")) {
                        ?>

                        <div class="form-group">
                            <label>Tarif choisi </label>
                            <select name="tarif_forfait" class="form-control" >
                                <?php
                                // foreach ($tariforfaits AS $key=>$value) {
                                // if ($forfait_user == $key)
                                // {
                                // echo "<option value=\"".$key."\" selected>".$value."</option>";
                                // } else {
                                // echo "<option value=\"".$key."\">".$value."</option>";
                                // }
                                // }
                                if ($typeTransac == "forfait") {
                                    foreach ($tarifsAtelier as $forfait) {
                                        if ($forfait_user == $forfait->getId()) {
                                            echo "<option value=\"" . $forfait->getId() . "\" selected>" . htmlentities($forfait->getNom()) . " (" . $forfait->getDonnee() . "€) </option>";
                                        } else {
                                            echo "<option value=\"" . $forfait->getId() . "\">" . htmlentities($forfait->getNom()) . " (" . $forfait->getDonnee() . "€) </option>";
                                        }
                                    }
                                }
                        if ($typeTransac == "temps") {
                            foreach ($forfaits as $forfait) {
                                if ($forfait_user == $forfait->getId()) {
                                    echo "<option value=\"" . $forfait->getId() . "\" selected>" . htmlentities($forfait->getNom()) . " (" . $forfait->getPrix() . "€) </option>";
                                } else {
                                    echo "<option value=\"" . $forfait->getId() . "\">" . htmlentities($forfait->getNom()) . " (" . $forfait->getPrix() . "€) </option>";
                                }
                            }
                        } ?>
                            </select>
                        </div>
                        <?php
                        if ($typeTransac == "forfait") { // n'apparait pas pour les tarfis de la consultation ?>  
                            <div class="form-group">
                                <label>Combien ? </label>       
                                <input type="text" name="nbrf" value="<?php echo $nbrforfait; ?>" placeholder="3, 6...." class="form-control"> 
                            </div>
                            <?php
                        } ?>

                        <div class="form-group">
                            <label>Quand ? </label> 
                            <input type="text" name="date" value="<?php echo $datef; ?>" class="form-control">
                        </div>
                        <?php
                    }
                    ?>
                </div><!-- fin body-->

                <div class="box-footer">
                    <input type="submit" value="En attente" name="submit" class="btn bg-purple ">
                    <input type="submit" value="Encaisser" name="submit" class="btn btn-primary">
                    <a href="<?php echo $annuler; ?>"><input type="submit" value="Annuler" name="Annuler" class="btn btn-default"></a>
                </div><!-- fin footer-->
            </form>
        </div><!-- fin box-->
    </div><!-- /col -->
</div><!-- /row -->



