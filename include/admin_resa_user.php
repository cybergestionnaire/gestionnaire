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
 2012 Florence DAUVERGNE

*/
    require_once('include/class/Utilisateur.class.php');
    require_once('include/class/Resa.class.php');

    $id_user   = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    $datedebut = isset($_POST["datedebut"]) ? $_POST["datedebut"] : '';
    $datefin   = isset($_POST["datefin"]) ? $_POST["datefin"] : '';
    //debug($date1);
    //debug($date2);

    //retrouver les infos utilisateur
    if ($id_user != '') {
        $utilisateur = Utilisateur::getUtilisateurById($id_user);
    }
    $week    = date('W')+1;
    $semaine = get_lundi_dimanche_from_week($week);
    $date1   = $semaine[0];
    $date2   = $semaine[1];

    // Tableau des mois
    $month = array(
           1=> "Janvier",
           2=> "F&eacute;vrier",
           3=> "Mars",
           4=> "Avril",
           5=> "Mai" ,
           6=> "Juin",
           7=> "Juillet",
           8=> "Aout",
           9=> "Septembre",
           10=> "Octobre",
           11=> "Novembre",
           12=> "D&eacute;cembre"
    );

    $annees = array(
        1=> "2010",
        2=> "2011",
        3=> "2012",
        4=> "2013",
        5=> "2014",
        6=> "2015"
        

    );


?>

<div class="row">
    <!-- left column -->
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Réservations de <?php echo htmlentities($utilisateur->getPrenom() . "  " . $utilisateur->getNom()) ;?></h3>
                <div class="box-tools pull-right">
                    <a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-purple btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></a>
                    <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId(); ?>" class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>                    
                    <!--<a href="index.php?a=9&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-maroon btn-sm"  data-toggle="tooltip" title="Consultation internet"><i class="fa fa-globe"></i></a>-->
                    <a href="index.php?a=21&b=1&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Compte d'impression"><i class="fa fa-print"></i></a>
<?php
    if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                    //if (chechUserAS($utilisateur->getId()) == TRUE) {
?>
                    <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-primary btn-sm" data-toggle="tooltip" title="Inscriptions Ateliers"><i class="fa fa-keyboard-o"></i></a>
<?php
    }           
?>
                </div>
            </div>
            <div class="box-body">
     

    <?php
    $resasFutures = Resa::getResasFuturesParIdUtilisateur($id_user);
    $resasPassees = Resa::getResasParIdUtilisateurEtParMois($id_user, date("m"), date('Y'));

    if ($resasFutures !== null || $resasPassees !== null) {


        if ($resasFutures !== null) {
?>
                <p>R&eacute;servations à venir</p>
                <table class="table">
                    <thead>
                        <tr><th>Date</th><th>Heure de debut</th><th>Heure de fin</th><th>Dur&eacute;e</th><th>Machine r&eacute;serv&eacute;e</th></tr>
                    </thead>
                    <tbody>
<?php
        foreach ($resasFutures as $resa) {
?>            
                    <tr>
                        <td><?php echo dateFr($resa->getDateResa())?></td>
                        <td><?php echo getTime($resa->getDebut())?></td>
                        <td><?php echo getTime($resa->getDebut() + $resa->getDuree())?></td>
                        <td><?php echo getTime($resa->getDuree())?></td>
                        <td><?php echo htmlentities($resa->getMateriel()->getNom())?></td>
                    </tr>
<?php
        }

?>
                    </tbody>
                </table>
<?php
    } else {
        echo "Aucune réservation enregistrée pour les jours prochains</br>";
    }

        // ARCHIVES DES RESERVATIONS

        // affichage
        if ($resasPassees !== null) {
?>
                <p>R&eacute;servations archiv&eacute;s (mois en cours)</p>
                <table class="table">
                    <thead>
                        <tr><th>Date</th><th>Heure de debut</th><th>Heure de fin</th><th>Dur&eacute;e</th><th>Machine r&eacute;serv&eacute;e</th></tr>
                    </thead>
                    <tbody>
<?php
        foreach ($resasPassees as $resa) {
?>            
                    <tr>
                        <td><?php echo dateFr($resa->getDateResa())?></td>
                        <td><?php echo getTime($resa->getDebut())?></td>
                        <td><?php echo getTime($resa->getDebut() + $resa->getDuree())?></td>
                        <td><?php echo getTime($resa->getDuree())?></td>
                        <td><?php echo htmlentities($resa->getMateriel()->getNom())?></td>
                    </tr>
<?php
        }

?>
                    </tbody>
                </table>
<?php
        } else {
            echo "Aucune réservation enregistrée pour le mois en cours </br>";
        }

    } else {
        echo "Vous n'avez pas de r&eacute;servations enregistrée" ;
        
    }
?>

            </div><!-- .box-body -->
        </div><!-- .box -->



        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">Chercher dans les r&eacute;servations</h3></div>
            <div class="box-body">
                <form method="POST" role="form" >
                    <div class="form-group">
                        <label>Choisissez la p&eacute;riode</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="datedebut" class='input'  id="left" placeholder="de la date ....">
                            <input name="datefin" class='input'  id="right" placeholder="....à la date">
                            <button class="btn btn-default" type="submit" name="submit" value="periode"><i class="fa fa-search"></i></button>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
                </form>     
    
<?php
        // debug($_POST["submit"]);
    if (isset($_POST["submit"])) {

        
        if( $datedebut != "" AND $datefin != "") {
            $resasPeriode = Resa::getResasParIdUtilisateurEtParPeriode($id_user, $datedebut, $datefin) ;

            if ($resasPeriode !== null) {
                echo " <p>R&eacute;servations entre ".getDateFr($datedebut)." et ".getDateFr($datefin)."</p>" ;
?>
                <table class="table">
                    <thead>
                        <tr> 
                            <th>Date</th>
                            <th>Heure de debut</th>
                            <th>Heure de fin</th>
                            <th>Dur&eacute;e</th>
                            <th>Machine r&eacute;serv&eacute;e</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                foreach ($resasPeriode as $resa) {
?>            
                    <tr>
                        <td><?php echo dateFr($resa->getDateResa())?></td>
                        <td><?php echo getTime($resa->getDebut())?></td>
                        <td><?php echo getTime($resa->getDebut() + $resa->getDuree())?></td>
                        <td><?php echo getTime($resa->getDuree())?></td>
                        <td><?php echo htmlentities($resa->getMateriel()->getNom())?></td>
                    </tr>
<?php
                }

?>
                    </tbody>
                </table>
<?php
            } else {
                echo "Aucune r&eacute;servation enregistr&eacute;e pour la p&eacute;riode demand&eacute;e</br>";
            }
            
        } else {
            echo "Veuillez entrer 2 dates pour définir la période SVP!";
        }
    }
?>
            </div>

            <div class="box-footer clearfix no-border">
                <a href="courriers/csv_resa-user.php?user=<?php echo $id_user?>&date1=<?php echo $datedebut ?>&date2=<?php echo $datefin ?>&epn=<?php echo $_SESSION["idepn"]; ?>" class="btn btn-success pull-right"><i class="fa fa-download"></i>&nbsp;&nbsp;Générer le CSV</a>
            </div>
        </div><!-- .box -->
    </div><!-- .col-md-8 -->


<!-- right column -->
    <div class="col-md-4">
<!--
<div class="box"><div class="box-header"><h3 class="box-title">Infos sur le compte</h3></div>
  <div class="box-body">
<?php

    // if (TRUE==checkResaSemaine($id_user,strftime("%Y-%m-%d",$date1), strftime("%Y-%m-%d",$date2)))
    // {
        // $row=getTempsCredit($id_user, strftime("%Y-%m-%d",$date1), strftime("%Y-%m-%d",$date2));
        // //debug(strftime("%Y-%m-%d",$date2));
        // debug($row['temps_user']);
        // if ($row['total']==999)
        // {
            // $total = 'illimit&eacute;' ;
            // $reste = $total ;
        // } else {
            // $total = getTime($row['total']);
            // $utilise=$row['util'];
            // $reste = getTime($row['total']-$utilise) ;
            
            // //$reste = getTime($row['total']-$row['util']) ;
        // }
    // } else {
        // $total=getTime(300);
        // $utilise=0;
        // $reste=$total;
    // }
        // echo " 
                // <h5>Semaine du ".strftime("%d/%m/%Y",$date1)." au ".strftime("%d/%m/%Y",$date2)." </h5>
                // <p>Cr&eacute;dit temps total par semaine :<b> ".$total." </b></p>
                // <p>Cr&eacute;dit temps utilis&eacute; cette semaine : ".getTime($utilise)."</p>
                // <p>Cr&eacute;dit temps restant cette semaine : <b>".$reste." </b></p>
                
                
              // ";
    ?>
    </div>
</div>  
-->
<?php
    //Calcul statistique de la consultation par mois
    $resasPassees = Resa::getResasPasseesParIdUtilisateur($id_user, date("Y-m-d"));
    if ($resasPassees !== null) {
    //debug(checkResa($id_user));
?>
        <div class="box">
            <div class="box-header"><h3 class="box-title">Statistiques</h3></div>
            <div class="box-body">
                 
                <H5>Cumul par mois (année en cours)</h5>
                <table class="table table-condensed">
                    <thead>
                        <tr> 
                            <th>Mois</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
        // 
        $month = date('n');
        for ($i=1 ; $i<= $month;++$i){   
            $resasPassees = Resa::getResasParIdUtilisateurEtParMois($id_user, $i, date('Y'));
    //        $result = getUserResabyMonth($id_user,$i,date('Y'));
            $dureeTotale = 0;
            if ($resasPassees !== null ) {
                foreach($resasPassees as $resa) {
                    $dureeTotale += $resa->getDuree();
                }
            }
            echo "<tr><td>" . getMonth($i) . "</td><td>" . getTime($dureeTotale) . "</td></tr>" ;
                
        }
?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }
?>

<!--
<div class="box"><div class="box-header"><h3 class="box-title">Statistiques</h3></div>
  <div class="box-body">
    <p>Moyenne de la consultation par semaine : (en h)</p>
    
    
    <p>Postes utilisés</p>
    
    </div>
</div>
-->

    </div>


</div><!-- /row -->

 <!-- Daterange picker -->
<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/historique.js'></script>