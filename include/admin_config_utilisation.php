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

 2006 Namont Nicolas (CyberMin)
 
*/
    require_once("include/class/Utilisation.class.php");
    // gestion des fonctions des postes
                                
    // traitement des post
    $act            = isset($_GET["act"])           ? $_GET["act"] : '';
    $idUtilisation  = isset($_GET["idutilisation"]) ? $_GET["idutilisation"] : '';
    //$typemenu = $_GET["typemenu"];

    // type de menu d&eacute;fini
    $menuarray = array (
             0 => "Menu Principal",   
             1 => "Sous Menu"
    );

    // type de visibilit&eacute; d&eacute;fini
    $visiblearray = array (
             0 => "oui",   
             1 => "non"
    );


    switch ($act) {
        case 1: // creation
            $utilisation = isset($_POST["newutilisation"]) ? $_POST["newutilisation"] : '';
            $typemenu    = isset($_POST["typemenu"])       ? $_POST["typemenu"]       : '';
            $visible     = isset($_POST["visiblemenu"])    ? $_POST["visiblemenu"]    : '';
            if (Utilisation::creerUtilisation($utilisation,$typemenu, $visible) == null) {
                echo getError(0);
            }     
            break;
        case 2: // modification
            $utilisation = isset($_POST["utilisation"])  ? $_POST["utilisation"] : '';
            $typemenu    = isset($_POST["typemenu"])     ? $_POST["typemenu"]    : '';
            $visible     = isset($_POST["visiblemenu"])  ? $_POST["visiblemenu"] : '';
            $util        = Utilisation::getUtilisationById($idUtilisation);
            if (!$util->modifier($utilisation,$typemenu, $visible)) {
                echo getError(0);
            }
            break;
        case 3: // suppression
            $util        = Utilisation::getUtilisationById($idUtilisation);
            if (!$util->supprimer()) {
                echo getError(0);
            }
            break;
    }

    // affichage  -----------
    $utilisations = Utilisation::getUtilisations();
   
    include("include/boites/menu-parametres.php");
?>


<div class="box box-solid box-warning">
    <div class="box-header"><h3 class="box-title">Liste des utilisations</h3></div>
    <div class="box-body no-padding">
        <p><br>&nbsp; &nbsp;NB : Cette liste apparait sur le formulaire de pr&eacute;-inscription des usagers et sera utilis&eacute;e pour les statistiques.</p>
        <table class="table table-condensed">
            <thead><tr><th>Utilisation</th><th>Type</th><th>Visible</th><th>&nbsp;</th></tr></thead>
<?php
    foreach ($utilisations as $utilisation) {
?> 
            <form action="index.php?a=48&act=2&idutilisation=<?php echo $utilisation->getId(); ?>" method="post" role="form">
                <tr>
                    <td><input type="text" name="utilisation" value="<?php echo htmlentities($utilisation->getNom()); ?>" class="form-control"></td>
                    <td>
                        <select name="typemenu" id="typemenu" class="form-control">
<?php
        /*if(strcmp ($menuarray2[$key2],"")==0)
        {   
            echo "<option selected>Non d&eacute;fini</option>";
        }*/
        for ($b = 0 ; $b < 2 ; $b++) {
            if ($menuarray[$b] == $utilisation->getType()) {
                echo "<option value=\"" . $menuarray[$b] . "\" selected>" . $menuarray[$b] . "</option>";
            }
            else {
                echo "<option value=\"" . $menuarray[$b] . "\">" . $menuarray[$b] . "</option>";
            }
        }
?>
                        </select>
                    </td>
                    <td>
                        <select name="visiblemenu" id="visiblemenu" class="form-control">
<?php
        /*if(strcmp ($visiblearray2[$key2],"")==0)
        {   
            echo "<option selected>Non d&eacute;fini</option>";
        }*/
        for ($b = 0; $b < 2; $b++) {
            if ($visiblearray[$b] == $utilisation->getVisible()) {
                echo "<option value=\"" . $visiblearray[$b] . "\" selected>" . $visiblearray[$b] . "</option>";
            }
            else {
                echo "<option value=\"" . $visiblearray[$b] . "\">" . $visiblearray[$b] . "</option>";
            }
        }
?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" value="modifier" class="btn bg-green sm">
                        <a href="index.php?a=48&act=3&idutilisation=<?php echo $utilisation->getId(); ?>"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a>
                    </td>
                </tr>
            </form>
<?php
    }
?>
        </table>
    </div><!-- .box-body -->

    <div class="box-footer">
        <h4>Ajouter une autre utilisation</h4>
        <form method="post" action="index.php?a=48&act=1">  
            <div class="row">
                <div class="col-xs-5"><input type="text" name="newutilisation" class="form-control">  </div>
                <div class="col-xs-3">
                    <select name="typemenu" class="form-control" >
<?php
    foreach ($menuarray AS $key=>$value) {
        echo "<option value=\"" . $value . "\">" . $value . "</option>";
    }
?>
                    </select>
                </div>

                <div class="col-xs-2">
                    <select name="visiblemenu" class="form-control">
<?php
    foreach ($visiblearray AS $key6=>$value6) {       
        echo "<option value=\"" . $value6 . "\">" . $value6 . "</option>";
    }
?>
                    </select>
                </div>

                <div class="col-xs-2"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div>
            </div>
        </form>
    </div><!-- .box-footer -->
</div><!-- .box -->    



