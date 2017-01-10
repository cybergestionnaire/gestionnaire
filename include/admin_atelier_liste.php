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
/*
  LISTE DES ATELIERS REMANIEMENT 2013
 include/admin_atelier.php V0.1
 b=1 : detail de l'atelier (a=13)
 a=14 : modifier programmation / $m==4 supprimer progra
 
*/
    include_once("include/class/Espace.class.php");
    include_once("include/class/Atelier.class.php");
    
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }

    //debug($_GET["idstatut"]);
    $b = isset($_GET['b']) ? $_GET["b"] : '';

    //$c==variable pour le changement de vue d'atelier
    $c = isset($_GET['c']) ? $_GET["c"] : '';

     //statut de l'atelier
    $stateAtelier = array(
            0=> "En cours",
            1=> "En programmation",
            2=> "Clotur&eacute;",
            3=> "Annul&eacute;"
        );

    //$espaces = getAllepn();
    $espaces = Espace::getEspaces();
                
                
//-------------ATELIERS POUR L'ANNEE EN COURS ---------------:::
    if ($_SESSION["status"] == 4) {
        // $result1 = getFutAtelier(date('Y'));
        //$ateliers = Atelier::getAteliersParAnnee(date('Y'));
        $ateliers = Atelier::getAteliers();
    }
    if ($_SESSION["status"] == 3) {
        $anim = $_SESSION["iduser"];
    
    
        if (isset($c)) {
            switch($c){
                case 1:
                    $result1 = getFutAtelierbyanim(date('Y'), $anim);
                break;
                
                case 2:
                    $result1 = getFutAtelierbyepn(date('Y'), $_SESSION["idepn"]);
                break;
                
                case 3:
                    $result1 = getFutAtelier(date('Y'));
                break;
            }

        }
        else {
            $c = 1;
            $result1 = getFutAtelierbyanim(date('Y'), $anim);
        }
    }

    // $nba = mysqli_num_rows($result1);
    $nbAteliers = count($ateliers);
?>

<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Liste des ateliers propos&eacute;s<!-- pour <?php echo date('Y') ; ?>--></h3>
        <div class="box-tools pull-right">
            <a href="index.php?a=12"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="planifier"><i class="fa fa-calendar-o"></i></button></a>
            <a href="index.php?a=15"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="cr&eacute;er un sujet"><i class="fa  fa-plus"></i></button></a>
            <a href="index.php?a=17"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="modifier un sujet"><i class="fa fa-edit"></i></button></a>
            <a href="index.php?a=18"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="archives"><i class="fa fa-inbox"></i></button></a>
<?php
    if ($_SESSION["status"] == 3) { 
?>  
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm" data-toggle="dropdown" title="vue"><i class="fa fa-eye"></i></button>
            
                <ul class="dropdown-menu" role="menu">
                    <li><a href="index.php?a=11&c=1">Mes ateliers</a></li>
                    <li><a href="index.php?a=11&c=2">Ateliers de l'epn</a></li>
                    <li><a href="index.php?a=11&c=3">Ateliers du r&eacute;seau</a></li>
                </ul>
            </div>
<?php } ?>
        
        
        </div><!-- .box-tools -->
    </div><!-- .box-header -->

    <div class="box-body table-responsive">
<?php
    // if ($nba > 0) {
    if ($nbAteliers > 0) {
?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nom de l'atelier &nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Cliquez sur un intitul&eacute; pour inscrire un adh&eacute;rent"><i class="fa fa-info"></i></small></th>
                    <th>Lieu</th>
                    <th>Places</th>
                    <th>Attente</th>
                    <th>Statut</th>
                    <th>Animateur</th>
                    <th>Infos</th>
                    <th></th>
                    <th></th>

                </tr>
            </thead>
            <tbody>
<?php
        foreach($ateliers as $atelier) {
            $animateur = $atelier->getAnimateur();
            $salle     = $atelier->getSalle();
            $espace    = $salle->getEspace();

            if ($atelier->getStatut() > 1 ) {
                $class = "text-muted";
                $classpan = "label label-primary";
            }
            else {
                $class = "";
                $classpan = "label label-success";
            }
            //infos de l'atelier
            if ($atelier->getStatut() == 2 ) {
                $info = "<small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"Cet atelier a &eacute;t&eacute; clotur&eacute;, rdv aux archives pour modifier les pr&eacute;sences\"><i class=\"fa fa-info\"></i></small>";
            }
            elseif (($atelier->getStatut() == 0) AND (strtotime($atelier->getDate()) < strtotime(date('Y-m-d')))) {
                $info = "<small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"Cet  atelier n'a pas encore &eacute;t&eacute; clotur&eacute;, veuillez valider les pr&eacute;sences rapidement\"><i class=\"fa fa-info\"></i></small>";
            }
            else {
                $info = "";
            }
                    
            // if ($atelier->nbPlacesRestantes() < 0) {  // ce n'est pas censé arriver ??
                // $nbplace = 0;
            // }
                    
            echo "<tr class='" . $class . "'> 
                     <td>" . datefr($atelier->getDate()) . "</td>
                     <td>" . htmlentities($atelier->getHeure()) . "</td>
                     ";
            // en cas d'atelier cloture aucun acces possible -> archives !
            if ($atelier->getStatut() > 1 ) {
                echo "<td>" . htmlentities($atelier->getSujet()->getLabel()) . " ";
            }
            else {
                echo " <td><a href=\"index.php?a=13&b=1&idatelier=" . $atelier->getId() . "\" data-toggle=\"tooltip\" title=\"Inscrire un adherent\">" . htmlentities($atelier->getSujet()->getLabel()) . "</a>";
            }
            //Ajout mention "complet"
            if ($atelier->getNbPlacesRestantes() == 0) {
                echo "&nbsp;&nbsp;&nbsp;<b>COMPLET</b>";
            }

            echo "</td>
                <td>" . htmlentities($salle->getNom()) . " (" . htmlentities($espace->getNom()) . ")</td>
                <td>" . $atelier->getNbPlacesPrises() . " / " . $atelier->getNbPlaces() . "</td>
                <td>" . $atelier->getNbUtilisateursEnAttente() . "</td>
                <td><span class=\"" . $classpan . "\">" . $stateAtelier[$atelier->getStatut()] . "</span></td>
                <td>" . htmlentities($animateur->getPrenom()) . " " . htmlentities($animateur->getNom()) . "</td>
                <td>" . $info . "";
                
            if ($atelier->getStatut() <= 1) {
                echo "<a href=\"index.php?a=14&idatelier=" . $atelier->getId() . "\"><button type=\"button\" class=\"btn bg-green btn-sm\" data-toggle=\"tooltip\" title=\"Modifier la programmation\"><i class=\"fa fa-edit\"></i></button></a>
                    &nbsp;<a href=\"index.php?a=14&m=4&idatelier=" . $atelier->getId() . "\"><button type=\"button\" class=\"btn bg-red btn-sm\" data-toggle=\"tooltip\" title=\"D&eacute;programmer\"><i class=\"fa fa-trash-o\"></i></button></a>";
            }   
            echo    "</td></tr>";
                     
        }
?>
            </tbody>
        </table>
<?php                 
    }
    else {
?>
        <br>
        <div class="alert alert-info alert-dismissable">
            <i class="fa fa-warning"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>aucune formation programm&eacute;e
        </div>
        </br>
<?php
    }
?>
    </div><!-- .box-body -->
</div><!-- .box -->
<?php     
        
    // ANNEE SUIVANTE   
    
    if ($_SESSION["status"] == 4) {
        $ateliers2 = Atelier::getAteliersParAnnee(date('Y') + 1);
        
    }
    elseif($_SESSION["status"] == 3) {
        $anim = $_SESSION["iduser"];
        
        if ($c != "") {
            switch($c){
                case 1:
                    $result2 = getFutAtelierbyanim((date('Y')+1), $anim);
                break;
                
                case 2:
                    $result2 = getFutAtelierbyepn(date('Y')+1, $_SESSION["idepn"]);
                break;
                
                case 3:
                    $result2 = getFutAtelier(date('Y')+1);
                break;
            }

        }
        else{
            $c = 1;
            $result2 = getFutAtelierbyanim((date('Y')+1), $anim);
        }
    }
    
    //$nbas = mysqli_num_rows($result2);
    $nbAteliers = count($ateliers2);    
    if ($nbAteliers > 0) {
?>  
<div class="box box-success"> 
    <div class="box-header">
        <h3 class="box-title">Liste des ateliers propos&eacute;s pour <?php echo date('Y')+1 ; ?></h3>
        <div class="box-tools pull-right">
            <a href="index.php?a=12"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="planifier"><i class="fa fa-calendar-o"></i></button></a>
            <a href="index.php?a=15"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="cr&eacute;er un sujet"><i class="fa  fa-plus"></i></button></a>
            <a href="index.php?a=17"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="modifier les sujets"><i class="fa fa-edit"></i></button></a>
            <a href="index.php?a=18"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="archives"><i class="fa fa-inbox"></i></button></a>
        
<?php
        if ($_SESSION["status"] == 3) { 
?>  
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm" data-toggle="dropdown" title="vue"><i class="fa fa-eye"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="index.php?a=11&c=1">Mes ateliers</a></li>
                    <li><a href="index.php?a=11&c=2">Ateliers de l'epn</a></li>
                    <li><a href="index.php?a=11&c=3">Ateliers du r&eacute;seau</a></li>
                </ul>
            </div>
<?php
        } 
?>
        </div><!-- .box-tools -->
    </div><!-- .box-header -->
    <div class="box-body">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nom de l'atelier &nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Cliquez sur un intitul&eacute; pour inscrire un adh&eacute;rent"><i class="fa fa-info"></i></small></th>
                    <th>Lieu</th>
                    <th>Places</th>
                    <th>Attente</th>
                    <th>Statut</th>
                    <th>Animateur</th>
                    <th>Infos</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php
        foreach ($ateliers2 as $atelier) {
            $animateur = $atelier->getAnimateur();
            $salle     = $atelier->getSalle();
            $espace    = $salle->getEspace();    

            // if ($atelier->nbPlacesRestantes() < 0) {  // ce n'est pas censé arriver ??
                // $nbplace=0;
            // }
            if ($atelier->getStatut() > 1 ) {
                $class = "text-muted";
                $classpan = "label label-primary";
            }
            else {
                $class = "";
                $classpan = "label label-success";
            }

            echo "<tr> 
                <td>" . datefr($atelier->getDate()) . "</td>
                <td>" . htmlentities($atelier->getHeure()) . "</td>
                <td><a href=\"index.php?a=13&b=1&idatelier=" . $atelier->getId() . "\" data-toggle=\"tooltip\" title=\"Inscrire un adherent\" >" . htmlentities($atelier->getSujet()->getLabel()) . "</a>";
                
            //Ajout mention "complet"
            if ($atelier->getNbPlacesRestantes() == 0) {
                echo "<b>      <span class=\"text-red\">COMPLET</span></b>";
            }
            echo"</td>
                <td>" . htmlentities($salle->getNom()) . " (" . htmlentities($espace->getNom()) . "</td>
                <td>" . $atelier->getNbPlacesPrises() . " / " . $atelier->getNbPlaces() . "</td>
                <td>" . $atelier->getNbUtilisateursEnAttente() . "</td>
                <td><span class=\"" . $classpan . "\">" . $stateAtelier[$atelier->getStatut()] . "</span></td>
                <td>" . htmlentities($animateur->getPrenom()) . " " . htmlentities($animateur->getNom()) . "</td>
                <td><a href=\"index.php?a=14&idatelier=" . $atelier->getId() . "\"><button type=\"button\" class=\"btn bg-green btn-sm\" data-toggle=\"tooltip\" title=\"Modifier la programmation\" ><i class=\"fa fa-edit\"></i></button></a>
                <a href=\"index.php?a=14&m=4&idatelier=" . $atelier->getId() . "\"><button type=\"button\" class=\"btn bg-red btn-sm\" data-toggle=\"tooltip\" title=\"D&eacute;programmer\"><i class=\"fa fa-trash-o\"></i></button></a></td>                 
                </tr>";
        }
?>
            </tbody>
        </table>
    </div><!-- .box-body -->
</div><!-- .box -->
<?php
    }
    else {
        if (date('m') >= 8) {
            echo "<div class=\"alert alert-info alert-dismissable\" id=\"nextyear\" class=\"fade\"><i class=\"fa fa-warning\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>aucune formation programm&eacute;e pour l'ann&eacute;e prochaine</div>";
        }
    }
?>
