<?php
/*
    This file is part of CyberGestionnaire.

    CyberGestionnaire is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire.  If not, see <http://www.gnu.org/licenses/>
*/
    require_once("include/class/Session.class.php");
    
    /*
    LISTE DES SESSIONS PROGRAMMES
    */
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }

    //$c==variable pour le changement de vue d'atelier
    $c = isset($_GET['c']) ? $_GET['c'] : '';
    //$espaces = getAllepn();
    //$espaces = Espace::getEspaces();
    
    //CHERCHER LES SESSIONS


    if ($_SESSION["status"] == 4) {
        // $result = getFutsessions();
        $sessions = Session::getSessionsNonCloturees();
    }
    if ($_SESSION["status"] == 3) {
        $anim = $_SESSION["iduser"];
    
    
        if (isset($c)) {
            switch ($c) {
                case 1:
                    $result = getFutsessionsbyanim($anim);
                break;
                
                case 2:
                    $result = getFutsessions($_SESSION["idepn"]);
                break;
                
                case 3:
                    $result = getFutsessions(0);
                break;
            }

        }
        else {
            $c = 0;
            $result = getFutsessions(0);
        }
    
    }



    // $nbsessionsprog = mysqli_num_rows($result);
    $nbsessionsprog = count($sessions);
              
    if ($nbsessionsprog > 0) {
 ?>
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title">Liste des sessions programm&eacute;es</h3>
            <div class="box-tools pull-right">
                <a href="index.php?a=31&m=1"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="planifier"><i class="fa fa-calendar-o"></i></button></a>
                <a href="index.php?a=34"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="cr&eacute;er un sujet"><i class="fa  fa-plus"></i></button></a>
                <a href="index.php?a=29"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="modifier un sujet"><i class="fa fa-edit"></i></button></a>
                <a href="index.php?a=36"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="archives"><i class="fa fa-inbox"></i></button></a>
<?php
    if ($_SESSION["status"] == 3) {
?>  
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="dropdown" title="vue"><i class="fa fa-eye"></i></button>
                
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="index.php?a=37&c=1">Mes sessions</a></li>
                        <li><a href="index.php?a=37&c=2">Sessions de l'epn</a></li>
                        <li><a href="index.php?a=37&c=3">Sessions du r&eacute;seau</a></li>
                           
                    </ul>
                </div>
<?php } ?>
        
        
            </div>
        </div>
                
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <th>Dates</th>
                    <th>Intitule</th><th>Lieu</th>
                    <th>Places</th>
                    <th>Attente</th>
                    <th>Dates</th>
                    <th>Animateur</th>
                    <th></th>
                </thead>
<?php
        
    // for ($j = 1 ; $j <= $nbsessionsprog ; $j++) {
        // $row = mysqli_fetch_array($result) ;
    foreach ($sessions as $session) {
        
        //elements                  
        // $titresession = getTitreSession($row["nom_session"]);
        

        //test de validation en cours des dates de la session pour permettre ou non la suppression
        // $testvalidation = getSessionvalidees($row["id_session"]);
        // $salle = mysqli_fetch_array(getSalle($row["id_salle"]));
        $salle = $session->getSalle();
        $espace = $salle->getEspace();
        $animateur = $session->getAnimateur();
                    
        // if ($row["date_session"] < date('Y-m-d')) {
        if ($session->getDate() < date('Y-m-d')) {
            // $statutaffiche = $row["nbre_dates_sessions"] . "&nbsp;&nbsp;<small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"Des dates de la session n'ont pas encore &eacute;t&eacute; valid&eacute;es !\"><i class=\"fa fa-info\"></i></small>";
            $statutaffiche = $session->getNbDates() . "&nbsp;&nbsp;<small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"Des dates de la session n'ont pas encore &eacute;t&eacute; valid&eacute;es !\"><i class=\"fa fa-info\"></i></small>";
            $class = "text-red" ;
        }
        else {
            $class = "" ;
            $statutaffiche = $session->getNbDates();
        }
        
        //affichage de toutes les dates de la session
        // $datesarray = getDatesSession($row["id_session"]);
        $datesSession = $session->getSessionDates();
        
        $nbrdates = $session->getNbDates();

        $listedatess = '';

        // for ($f=0; $f<$nbrdates ; $f++){
            // $rowdates=mysqli_fetch_array($datesarray);
            // $dd=date_create($rowdates["date_session"]);
            // $listedatess=$listedatess.date_format($dd,"Y/m/d H:i")."</br>";
        // }
        
        foreach ($datesSession as $dateSession) {
            $listedatess .= date_format(date_create($dateSession->getDate()),"d/m/Y H:i")."</br>";
        }
        
                        
        //nombre de places pour la session
        // $placesoccupee = countPlaceSession($row["id_session"],0);
        // $nbplace       = $row["nbplace_session"];
        // $enattente     = countPlaceSession($row["id_session"],2);
        $placesOccupees = $session->getNbUtilisateursInscritsOuPresents();
        $nbPlaces       = $session->getNbPlaces();
        $enAttente      = $session->getNbUtilisateursEnAttente();
        
        
        //$placesrestantes=$nbplace-$placesoccupee;
        //debug($datesSession);
?>        
                <tr class="<?php echo $class ?>"> 
                    <td><small><?php echo $listedatess?></small></td>
                    <td>
                        <a href="index.php?a=30&b=1&idsession=<?php echo $session->getId() ?>"><?php echo htmlentities($session->getSessionSujet()->getTitre()) ?></a>
<?php
        if ($nbPlaces == $placesOccupees) { echo "&nbsp;&nbsp;<b>COMPLET</b>";}
?>
                    </td>
                    <td><?php echo htmlentities($salle->getNom()) . " <br>(" . htmlentities($espace->getNom()) . ")" ?></td>
                    <td><?php echo $placesOccupees . " / " . $nbPlaces ?></td>
                    
                    <td><?php echo $enAttente ?></td>
                    <td><?php echo $statutaffiche ?></td>
                    <td><?php echo htmlentities($animateur->getPrenom() . " " . $animateur->getNom()) ?></td>
                    <td>
                        <a href="index.php?a=31&m=2&idsession=<?php echo $session->getId() ?>"><button type="button" class="btn bg-green btn-sm" data-toggle="tooltip" title="modifier"><i class="fa fa-edit"></i></button></a>
<?php
            //bouton supprimer ne s'affiche que si la totalité des dates n'a pas été validée.
            if (!$session->hasSessionDatesValidees()) {
?>
                        &nbsp;<a href="index.php?a=37&m=4&idsession=<?php echo $session->getId() ?>"><button type="button" class="btn bg-red btn-sm" data-toggle="tooltip" title="supprimer" value=<?php echo $session->getId() ?> OnClick="return confirm(' Des adh&eacute;rents sont inscrits à cette session, voulez-vous vraiment la supprimer ?');"><i class="fa fa-trash-o"></i></button></a>
<?php 
            }
?>        
                    </td>
                </tr>
<?php 
        }
?>
                 
            </table>
        </div><!-- .box-body -->
    </div><!-- .box -->
<?php
    }
    else {
?>
    <div class="alert alert-info alert-dismissable">
        <i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune session programm&eacute;e
    </div>
    <!-- Afficher les boutons pour programmer une session -->
    <div class="row">
        <!-- acces aux archives>-->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner"><h3>&nbsp;</h3><p></p></div>
                <div class="icon"><i class="ion ion-archive"></i></div>
                <a href="index.php?a=36" class="small-box-footer">Acc&eacute;der aux archives&nbsp;&nbsp;<i class="fa fa-arrow-circle-left"></i></a>
            </div><!-- /box -->
        </div><!-- /col -->
        <!-- nouvelle programmation-->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner"><h3>&nbsp;</h3><p></p></div>
                <div class="icon"><i class="ion ion-calendar"></i></div>
                <a href="index.php?a=31&m=1" class="small-box-footer">Programmer une session <i class="fa fa-arrow-circle-right"></i></a>
            </div><!-- /box -->
        </div><!-- /col -->
        <!-- nouveau sujet -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner"><h3>&nbsp;</h3><p></p></div>
                <div class="icon"><i class="ion ion-briefcase"></i></div>
                <a href="index.php?a=34" class="small-box-footer">Ajouter un sujet <i class="fa fa-arrow-circle-right"></i></a>
            </div><!-- /box -->
        </div><!-- /col -->
    </div><!-- /row -->
<?php
    }
?>
