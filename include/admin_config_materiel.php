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
 
*/
    require_once("include/class/Materiel.class.php");
// chargement des valeurs pour l'epn par défaut
/*$epn=$_SESSION['idepn'];
//si changment d'epn
$epn_r=$_GET['epnr'];
if (isset($epn_r)){
    $epn=$epn_r;
}*/

    $idEspace = isset($_GET['epnr']) ? $_GET['epnr'] : $_SESSION['idepn'];

    $espaces = Espace::getEspaces();
    
    $usagesMateriel = array(
        1=>"Mat&eacute;riel accessible au public",
        2=>"Mat&eacute;riel interne non accessible au public",
        3=>"Autre mat&eacute;riel"
    );

    include("include/boites/menu-parametres.php");

?>

<!-- section 1 - 3 -->
<div class="row">
    <section class="col-lg-8 connectedSortable"> 
<?php
    //$result=getAllMateriel();
    //$result=getMaterielFromEpn($epn);
    $materiels = Materiel::getMaterielFromEspaceById($idEspace);
    
    $nbMateriels = count($materiels);
    if ($nbMateriels == 0)
    {
        echo getError(9);
    }
    else
    {
        foreach($usagesMateriel as $usage=>$nom) {
?>
        <div class="box box-solid box-warning">
            <div class="box-header"><h3 class="box-title"><?php echo $nom; ?></h3></div>
            <div class="box-body no-padding">
                <table class="table">
                    <thead><tr>
                        <th>Nom</th><th>OS</th><th>Salle</th><th>Commentaires</th><th>&nbsp;</th><th>&nbsp;</th>
                    </tr></thead>
                    <tbody>
<?php

            foreach($materiels as $materiel) {
                if ($materiel->getUsage() == $usage) {
?>
                <tr>
                <td><?php echo htmlentities($materiel->getNom()); ?></td>
                <td><?php echo htmlentities($materiel->getOs()); ?></td>
                <td><?php echo htmlentities($materiel->getSalle()->getNom()); ?></td>
                <td><?php echo htmlentities($materiel->getCommentaire()); ?></td>
                <td><a href="index.php?a=2&b=2&idmat=<?php echo $materiel->getId();?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
                <td><a href="index.php?a=2&b=3&act=3&idmat=<?php echo $materiel->getId();?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td></tr>
<?php
                }
            }
?>

                    </tbody>
                </table>
            </div><!-- .box-body -->
        </div><!-- .box -->
<?php
        }
    }
?>

    </section>

<!-- Left col -->
    <section class="col-lg-4 connectedSortable">
        <form action="index.php?a=2" method="post" role="form"> 
            <div class="box box-primary">
                <div class="box-header"><i class="ion ion-home"></i><h3 class="box-title">Changer l'espace</h3></div>
                <div class="box-body">
                    <div class="input-group">
                        <select name="epn_r"  class="form-control input-sm" >
<?php
    foreach ($espaces AS $espace)
    {
        if ($idEspace == $espace->getId()) {
            echo "<option value=\"" . $espace->getId() . "\" selected>" . $espace->getNom() . "</option>";
        }
        else
        {
            echo "<option value=\"" . $espace->getId() . "\">" . $espace->getNom() . "</option>";
        }
    }
                
?>
                        </select>
                        <div class="input-group-btn">
                            <button type="submit" name="submit" value="Valider" class="btn btn-primary"><i class="fa fa-repeat"></i></button>
                        </div>
                    </div><!-- .input-group -->
            <!--<div class="box-footer">
                <input type="hidden" name="form" value="1">
                <button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</div>-->
                </div><!-- .box-body -->
            </div><!-- .box -->
        </form>
    
        <div class="small-box bg-light-blue">
            <div class="inner"><h3>&nbsp;</h3><p>Nouveau Materiel</p></div>
            <div class="icon"><i class="ion ion-laptop"></i></div>
            <a href="index.php?a=2&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </section>
</div>
