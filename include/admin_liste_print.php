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
 2012 florence DAUVERGNE

*/
/*
  
 Afficher la liste des comptes d'impression
 sélection d'un compte b=1

*/

    // admin --- Utilisateur
    $term = isset($_POST["term"]) ? $_POST["term"] : '';
    // affichage  -----------

    $categorieTarif = array(
        1=>"impression",
        2=>"adhesion",
        3=>"consommables",
        4=>"divers"
        );

    $statutPrint = array(
        0 =>"pas pay&eacute;",
        1 =>"pay&eacute;",
        );

?>
<div class="row">
    <section class="col-lg-4 connectedSortable">
 
        <div class="box box-primary box-solid">
            <div class="box-header with-border"><h3 class="box-title">Outils</h3></div>
            <div class="box-body">
                <p>Etat de caisse journalier&nbsp;&nbsp;<a href="courriers/csv_caisse-jour.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('Y-m-d')?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a></p>
                <p>Etat de caisse hebdomadaire&nbsp;&nbsp;<a href="courriers/csv_caisse-hebdo.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('Y-m-d') ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a></p>
                <p>Etat de caisse mensuel<br>
                    <a href="courriers/csv_caisse-mensuel.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('n') ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a>&nbsp;&nbsp;&nbsp;&nbsp;mois en cours</p>
                <p><a href="courriers/csv_caisse-mensuel.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo (date('n')-1) ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a>&nbsp;&nbsp;&nbsp;&nbsp;mois pr&eacute;c&eacute;dent</p>
            </div>
        </div>
 

 <?php
    $utilisateursAvecCredit = Utilisateur::getUtilisateursAvecCreditDImpression();

    if (count($utilisateursAvecCredit) > 0) {
?>
        <!-- SOLDE CREDITEUR -->
        <div class="box box-success box-solid">
            <div class="box-header with-border"><h3 class="box-title">Adh&eacute;rent &agrave; solde cr&eacute;diteur :</h3></div>
            <div class="box-body">
                <table class="table"> 
                    <thead><tr><th>&nbsp;</th><th>Nom</th><th>Pr&eacute;nom</th><th>solde</th></tr></thead>
                    <tbody> 
<?php

        foreach ($utilisateursAvecCredit as $utilisateurAvecCredit) {
  
?>            
                        <tr>
                            <td><a href="index.php?a=21&b=1&iduser=<?php echo $utilisateurAvecCredit["utilisateur"]->getId() ?>" class="btn bg-navy sm"><i class="fa fa-print"></i></a></td>
                            <td><?php echo htmlentities($utilisateurAvecCredit["utilisateur"]->getNom()) ?></td>
                            <td><?php echo htmlentities($utilisateurAvecCredit["utilisateur"]->getPrenom()) ?></td>
                            <td><?php echo number_format($utilisateurAvecCredit["credit"] - $utilisateurAvecCredit["debit"], 2,',', ' ') ?> &euro;</td>
                        </tr>
<?php
        }
?>
                
                    </tbody>
                </table>
            </div>
        </div><!-- .box -->
            
<?php 
    } 

    ///adherents a solde debiteur
    // $resultd = getPrintingUserswithdebt();
    // $nbd     = mysqli_num_rows($resultd);
    // if ($nbd > 0) {
    $utilisateursAvecDebit = Utilisateur::getUtilisateursAvecDebitDImpression();

    if (count($utilisateursAvecDebit) > 0) {        
        
        
?>

<!-- SOLDE DEBITEUR -->

        <div class="box box-danger box-solid">
            <div class="box-header with-border"><h3 class="box-title">Adh&eacute;rent &agrave; solde d&eacute;biteur :</h3></div>
            <div class="box-body">
                <table class="table"> 
                    <thead>
                        <tr><th></th><th>Nom</th><th>Pr&eacute;nom</th><th>solde</th></tr>
                    </thead>
                    <tbody> 
<?php
        foreach ($utilisateursAvecDebit as $utilisateurAvecDebit) {
            
        // for ($i = 0 ; $i < $nbd ; $i++) {
            // $arrayd = mysqli_fetch_array($resultd) ;
            // $nomd   = getuser($arrayd['print_user']);
?>           
                        <tr>
                            <td><a href="index.php?a=21&b=1&iduser=<?php echo $utilisateurAvecDebit["utilisateur"]->getId() ?>" class="btn bg-navy sm"><i class="fa fa-print"></i></a></td>
                            <td><?php echo htmlentities($utilisateurAvecDebit["utilisateur"]->getNom()) ?></td>
                            <td><?php echo htmlentities($utilisateurAvecDebit["utilisateur"]->getPrenom()) ?></td>
                            <td class="text-red"><?php echo number_format($utilisateurAvecDebit["credit"] - $utilisateurAvecDebit["debit"], 2,',', ' ') ?> &euro;</td>
                        </tr>
<?php
           }
           
?>
                    </tbody>
                </table>
            </div>
        </div>

<?php 
        }
?>

    </section><!-- /col -->
 
    <!-- RESULTATS DE LA RECHERCHE -->
    <section class="col-lg-8 connectedSortable">
<?php

    if (strlen($term) >= 2){
        // Recherche d'un adherent
        $result = searchUser($term);
        if (FALSE == $result OR mysqli_num_rows($result) == 0)     {
?>
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">R&eacute;sultats de la recherche: 0</h3>
                <div class="box-tools">
                    <div class="input-group">
                
                        <form method="post" action="index.php?a=21">
                            <div class="input-group input-group-sm">
                                <a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
                                <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                            </div>
                        </form>
                    </div><!-- /input-group -->
                </div>
            </div><!-- .box-header -->
            <div class="box-body">
<?php 
            
            echo  geterror(6);
?>
            </div>
        </div>
<?php
        } else {
            $nb  = mysqli_num_rows($result);
            if ($nb > 0) {
?>
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">R&eacute;sultats de la recherche: <?php echo $nb ?></h3>
                <div class="box-tools">
                    <div class="input-group">
                        <form method="post" action="index.php?a=21">
                            <div class="input-group input-group-sm">
                                <a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
                                <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                            </div>
                        </form>
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="box-body">
                <table class="table"> 
                    <thead><tr><th></th><th></th></thead> 
<?php 
        
                for ($i = 1 ; $i <= $nb ; $i++) {
                    $row = mysqli_fetch_array($result) ;
?>
                    <tr>
                        <td><a href="index.php?a=21&b=1&iduser=<?php echo $row['id_user'] ?>"><button type="button" class="btn bg-navy sm"><i class="fa fa-print"></i></button></a></td>
                        <td><?php echo $row['prenom_user'] . '&nbsp;' . $row['nom_user'] ?></td>
                    </tr>
<?php
                }
            }
?>
                </table>
            </div>
        </div>
<?php
        }
    } else {// si pas de recherche alors affichage classique
        
    ?>
        <div class="box box-primary">
            <div class="box-header"><h3 class="box-title">Les impressions du jour </h3>
                <div class="box-tools">
                    <div class="input-group">
            
                        <form method="post" action="index.php?a=21">
                            <div class="input-group input-group-sm">
                                <a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
                                <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                            </div>
                        </form>
                 
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="box-body">
<?php 
    // les adherents qui impriment récemment
        $result = getAllUserPrint();
    
        if (FALSE == $result) {
?>
                <br>
                <div class="col-xs-6">
                    <div class="alert alert-warning alert-dismissable">
                        <i class="fa fa-warning"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune transaction enregistr&eacute;e pour l'instant
                    </div>
                </div>
                    
<?php
        } else { // affichage du resultat
            $nb  = mysqli_num_rows($result);
    
            if ($nb > 0) {
?>
    
        
                <table class="table"> 
                    <thead> 
                        <tr> 
                            <th>&nbsp;</th>
                            <th>Nom</th>
                            <th>Pr&eacute;nom</th> 
                            <th>Date</th>
                            <th>debit(&euro;)</th>
                            <th></th>
                        </tr>   
                    </thead> 
            
<?php
             
                for ($i = 1 ; $i <= $nb ; $i++) {
                    $row = mysqli_fetch_array($result) ;
                
                    $tarif  = mysqli_fetch_array(getPrixFromTarif($row['print_tarif']));
                    $prix   = round(($row['print_debit'] * $tarif['donnee_tarif']),2);
                    $statut = $statutPrint[$row['print_statut']];
                    $totalprintday = $totalprintday + $prix;
?>                    
                    <tr>
                        <td><a href="index.php?a=21&b=1&iduser=<?php echo $row["print_user"] ?>"><button type="button" class="btn bg-navy sm" title="compte d'impression"><i class="fa fa-print"></i></button></a></td>
                        <td><?php echo $row["nom_user"] ?></td>
                        <td><?php echo $row["prenom_user"] ?></td>
                        <td><?php echo $row["print_date"] ?></td>
                        <td><?php echo $prix ?></td>
<?php
                    if ($row['print_statut'] == 0) {  
                        echo "<td><p class=\"text-red\">" . $statut . "</p></td> ";
                    } else {
                        echo "<td><p class=\"text-light-blue\">" . $statut . "</p></td> ";
                    }
?>
                    </tr>
<?php                        
                }
?>
                    <tr><td></td><td></td><td></td><td></td><td><?php echo $totalprintday ?> € (total jour)</td><td></td></tr>
                </table>
<?php
            }
        }
?>
            </div><!-- .box-body -->
        </div><!-- .box -->

<?php
    }
?>
    </section><!-- /col -->
</div><!-- /row -->
