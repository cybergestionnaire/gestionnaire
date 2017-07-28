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


***********///// Transactions de l'adhérent **********************/

    require_once("include/class/Utilisateur.class.php");

    /* renouvellement adhésion
    lien vers impressions
    achat forfait atelier 
    achat de forfait de consultation internet */

    $id_user = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    //gestion des erreurs
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }

// Tableau des unité d'affectation
    $tab_unite_temps_affectation = array(
           1=> 1, //minutes
           2=> 60 //heures
    );
    
    // Tableau des fréquence d'affectation
    $tab_frequence_temps_affectation = array(
           1=> "par Jour",
           2=> "par Semaine",
           3=> "par Mois"
    );

        
    // suppression d'un forfait d'un compte usager
    if (isset($_GET["act"])) {
        $transac = $_GET["transac"];
        $act     = $_GET["act"];
        if ($act == "del") {
            if (FALSE == delForfait($transac)) {
                header("Location: ./index.php?a=6&mesno=0&iduser=".$id_user);
            } else {
                //
                if ($_GET["type"] == "temps") {
                    delreluserforfaittemps($id_user);
                }
                header("Location: ./index.php?a=6&mesno=35&iduser=".$id_user);
            }
        }
    }

    // Information Utilisateur
    $utilisateur = Utilisateur::getUtilisateurById($id_user);
    
    $tarif       = $utilisateur->getTarifAdhesion();
    if ($tarif === null) {
        $prixtarif = "<span class=\"text-red\"><b>Aucune adh&eacute;sion associ&eacute;e !</b></span>";
    } else {
        $prixtarif = "<span class=\"text-success\"><b>" . $tarif->getNom() . "(" . $tarif->getDonnee() . " &euro;)</b></span>";
    }
    // $row        = getUser($id_user);
    // $dateinsc   = $row["date_insc_user"];
    // $daterenouv = $row["dateRen_user"];
    // $nom        = $row["nom_user"];
    // $prenom     = $row["prenom_user"];
    // $temps=$row["temps_user"];
    // $tarif      = $row["tarif_user"];
    // $tarifs     = getTarifsbyCat(2);
    // $prixtarif  = $tarifs[$tarif];
            
    // //paiements en attente adhesion
    $paiementAttente = Testpaiement($id_user);
    //affichage du bouton renouvellement si -7 jours

    $datetime1 = date_create($utilisateur->getDateRenouvellement());
    $datetime2 = date_create(date("Y-m-d"));
    $interval   = date_diff($datetime2, $datetime1);

    //Statut de la transaction
    $forfaitArray = array(
        0=>"En attente de paiement",
        1=>"Pay&eacute;",
        2=>"Archiv&eacute;"
        );
    //statut des forfaits ateliers  
    $arraystatutforfait = array(
        1=>"En cours",
        2=>"Termin&eacute;"
        );
        
    //recuperation des tarifs pour la consultation internet
    $tariftemps = getTarifsTemps();

        
    // //nombre d'inscriptions en cours + lien vers historique atelier
    // $ateliersInscrit = $utilisateur->getAteliersInscrit();
    // $sessionsInscrit = $utilisateur->getSessionsInscrit();
    
    // $nbASencours = count($ateliersInscrit) + count($sessionsInscrit);

    // $ateliersPresent = $utilisateur->getAteliersPresent();
    // $sessionsPresent = $utilisateur->getSessionsPresent();
    
    // $nbvalide = count($ateliersPresent) + count($sessionsPresent);
    
    $nbASencours = getnbASUserEncours($id_user,0) ; // 0= inscription en cours non validée
    //nombre d'inscriptions validées hors forfait
    $nbvalide    = getnbASUservalidees($id_user); // 1= total inscrit et validé
    $nbForfait   = getForfaitAchete($id_user,"for"); // total des ateliers achetés
    $nbrestant   = $nbForfait - $nbvalide; //restant apres dépense

     if ($nbrestant >= 0) {
        $nbHorsForfait = "aucune";
    } else {
        $nbHorsForfait= "<span class=\"text-red\">".abs($nbrestant)."&nbsp;&nbsp;</span><span class=\"btn bg-red btn-xs\" data-toggle=\"tooltip\" title=\"Ces ateliers n'ont pas &eacute;t&eacute; pay&eacute;s !\"><i class=\"fa fa-warning\"></i></span>";
    }

?>
<div class="row"> <!-- division en 3 rows par ligne -->

    <!--Adhesion et renouvellement -->
    <div class="col-md-6">
        <div class="box box-solid box-primary">
            <div class="box-header"><h3 class="box-title">Adh&eacute;sion</h3></div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Adh&eacute;rent </dt><dd><?php echo htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom()) ;?> </dd>
                    <dt>Date de 1er adh&eacute;sion</dt><dd> le <span class="text-success"><b><?php echo dateFR($utilisateur->getDateInscription()) ?></b></span></dd>
                    <dt>Tarif</dt><dd><?php echo $prixtarif ; ?></dd>
                    <dt>Renouvellement pr&eacute;vu </dt><dd> le <span class="text-red"><b><?php echo dateFR($utilisateur->getDateRenouvellement()) ;?></b></span></dd>
                </dl>
<?php
    if (mysqli_num_rows($paiementAttente)) {
        $row = mysqli_fetch_array($paiementAttente);
        if (in_array($tarif,$row)) {
            echo "<p class=\"lead\"><span class=\"text-red\">Attention le paiement est toujours en attente</span></p>
                 <a href=\"index.php?a=21&b=3&typetransac=adh&idtransac=".$row["id_transac"]."&iduser=".$id_user." \"><input type=\"submit\" value=\"Encaisser\" class=\"btn bg-default\"></a>
                ";
        }
    }
?>
        
            </div><!-- .box-body -->
            <div class="box-footer">
<?php 
    if (($interval->format('%a')) < 10 OR ($utilisateur->getDateRenouvellement() < date("Y-m-d"))) {
        echo "<a href=\"index.php?a=21&b=3&typetransac=adh&iduser=".$id_user." \"><input type=\"submit\" value=\"Renouveller l'adh&eacute;sion\" class=\"btn bg-default\"></a>";
    }
?>
        
                <a href="index.php?a=1&b=2&iduser=<?php echo $id_user ; ?>"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la fiche adh&eacute;rent</button></a> 
                &nbsp;<a href="index.php?a=21&b=3&typetransac=adh&iduser=<?php echo $id_user ; ?>"><button type="submit" value="" class="btn bg-purple">Changer le tarif de l'adh&eacute;sion</button></a>
        
            </div>  
        </div><!-- .box -->
    </div><!-- .col-md-6 -->


    <!--Achat forfaits -->
    <!-- division en 3 rows par ligne -->
    <div class="col-md-6">
        <div class="box box-solid box-success">
            <div class="box-header"><h3 class="box-title">Forfait atelier</h3></div>
            <div class="box-body">
                <p>Inscription en cours et non valid&eacute;es : <b><?php echo $nbASencours ; ?></b>
<?php
    if (chechUserAS($utilisateur->getId()) == TRUE) {
?>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId();?>" class="btn btn-primary btn-xs">Voir les inscriptions</a></p>
<?php
    }
?>
                <p>Inscription valid&eacute;es hors forfait : <b><?php echo $nbHorsForfait ; ?></b></p>
    
<?php
    /// forfait atelier utilisateur
    // $tariforfaits = getTarifsbyCat(5);
    
    $rowtransaction = getAllForfaitUser($id_user,"for");
    $nbf = mysqli_num_rows($rowtransaction);
    
    if ($nbf == 0) {
        echo "<p>l'adh&eacute;rent n'a souscrit &agrave; aucun forfait atelier</p>";
    } else {
?>
    
                <div class="table">
                    <table class="table">
                        <thead>
                            <th>Nom du Tarif</th><th>Date d'achat</th><th>Nbr</th><th>D&eacute;pens&eacute;</th><th>Statut</th><th></th><th></th>
                        </thead>
<?php
    
        for ($f = 0 ; $f < $nbf ; $f++) {
            $rowf = mysqli_fetch_array($rowtransaction);
        
            $nomTarif = getNomTarif($rowf["id_tarif"]);
        
            //**** le chiffre issu de la table rel
            $rowforfait    = getForfaitDonnesbyID($rowf['id_transac'],$id_user);
            $achete        = $rowforfait["total_atelier"];
            $depense       = $rowforfait["depense"];
            $statutachat   = $forfaitArray[$rowf['status_transac']];
            $statutforfait = $arraystatutforfait[$rowforfait["statut_forfait"]];
                
?>
                        <tr>
                            <td><?php echo $nomTarif; ?></td>
                            <td><?php echo $rowf["date_transac"]; ?></td>
                            <td><?php echo $achete; ?></td>
                            <td><?php echo $depense; ?></td>
                            <td><?php echo $statutachat ; ?></td>
                            <td><?php echo $statutforfait ; ?></td>
                            <td>
            
<?php
            if ($rowf['status_transac'] < 2) {
?>
                                <a href="index.php?a=21&b=3&typetransac=forfait&idtransac=<?php echo $rowf["id_transac"]; ?>&iduser=<?php echo $id_user; ?>" ><button type="button"  data-toggle="tooltip"  title="Modifier/Encaisser" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button></a>
                                <a href="index.php?a=6&act=del&transac=<?php echo $rowf["id_transac"]; ?>&iduser=<?php echo $id_user; ?>"><button type="submit" name="submit" class="btn btn-warning btn-sm"  data-toggle="tooltip"  title="Supprimer"><i class="fa fa-trash-o"></i></button></a>
            
<?php
            } 
?>
                            </td>
                        </tr>
    
    
<?php
        }
?>
                    </table>
                </div>
<?php
    }
?>
            </div>
<?php
    if ($depense == $achete) {
?>
            <div class="box-footer">
                <a href="index.php?a=21&b=3&typetransac=forfait&iduser=<?php echo $id_user ; ?>"><button type="submit" value="" class="btn btn-success"><i class="fa fa-cart-plus"></i>&nbsp; Ajouter un forfait</button></a>
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
    $transactemps = getTransactemps($id_user);

    if (TRUE == $transactemps) {
        //TARIF CONSULTATION
        $tarifTemps          = getForfaitConsult($id_user);
        $min                 = $tab_unite_temps_affectation[$tarifTemps["unite_temps_affectation"]];
        $tarifreferencetemps = $tarifTemps["nombre_temps_affectation"]*$min;
                
        //modifier le temps comptabilisé en fonction de la frequence_temps_affectation
        if($tarifTemps["frequence_temps_affectation"]==1){ 
                //par jour
                $date1 = date('Y-m-d');
                $date2 = $date1;
        }else if($tarifTemps["frequence_temps_affectation"]==2){ 
                //par semaine;
                $semaine = get_lundi_dimanche_from_week(date('W'));
                $date1   = strftime("%Y-%m-%d",$semaine[0]);
                $date2   = strftime("%Y-%m-%d",$semaine[1]);
        
        }else if($tarifTemps["frequence_temps_affectation"]==3){ 
                //par mois
                $date1 = date('Y-m')."-01";
                $date2 = date('Y-m')."-31";
        }
            
        //debug($tarifreferencetemps);
        $resautilise = getTempsCredit($id_user,$date1,$date2);
        $restant     = $tarifreferencetemps-$resautilise['util'];
        $rapport     = round(($restant/$tarifreferencetemps)*100);

?>
    
                <div class="table">
                    <table class="table">
                        <thead><th>Nom</th><th>Date d'achat</th><th>Validit&eacute; </th><th>Statut</th><th></th></thead>
                        <tbody>
                            <tr>
                                <td><?php echo $tariftemps[ $transactemps["id_tarif"]] ; ?> </td>
                                <td><?php echo $transactemps["date_transac"] ; ?> </td>
                                <td>
                                    <?php echo $rapport." % (".getTime($restant).")"; ?>
                                    <div class="progress progress-sm active">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:".$rapport."%"; ?>"></div>
                                    </div>
                                </td>
                                <td><?php echo $forfaitArray[$transactemps["status_transac"]] ; ?> </td>
                                <td>
                                    <a href="index.php?a=21&b=3&typetransac=temps&idtransac=<?php echo $transactemps["id_transac"] ?>&iduser=<?php echo $id_user; ?>" ><button type="button"  data-toggle="tooltip"  title="Modifier" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></button></a>
                                    &nbsp;<a href="index.php?a=6&act=del&type=temps&transac=<?php echo $transactemps["id_transac"]; ?>&iduser=<?php echo $id_user; ?>"><button type="submit" name="submit" class="btn btn-warning btn-sm"  data-toggle="tooltip"  title="Supprimer"><i class="fa fa-trash-o"></i></button></a>
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
                <a href="index.php?a=21&b=3&typetransac=temps&iduser=<?php echo $id_user ; ?>"><button type="submit" value="" class="btn bg-orange"><i class="fa fa-clock-o"></i>&nbsp;Ajouter du temps de consultation</button></a>
            </div>  
        </div><!-- .box -->
<?php
    }
?>
        
    
    </div><!-- .col-md-6 -->

    
</div><!-- FIN ROW 1 -->

