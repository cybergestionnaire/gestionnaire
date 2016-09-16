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
 

  include/admin_materiel.php V0.1
*/

// Fichier de gestion des salles ...
    require_once("include/class/Salle.class.php");

    include("include/boites/menu-parametres.php");
?>

<div class="row">
<section class="col-lg-9 connectedSortable">

    <!-- liste des salles existants-->
    <div class="box box-solid box-warning">
        <div class="box-header">
            <h3 class="box-title">Liste des Salles</h3>
            <div class="box-tools pull-right">
                <a href="index.php?a=44&b=1"><button class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Ajouter"><i class="fa fa-plus"></i></button></a>
            </div>
        </div>

        <div class="box-body no-padding">
            <table class="table">
                <thead> <tr> 
                    <th>Nom</th>
                    <th>EPN li&eacute;</th>
                    <th>Nombre de postes</th>
                    <th>Commentaires</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr></thead>
                
                <tbody>
<?php
    $salles = Salle::getSalles();
    if ($salles == null or count($salles) == 0)
    {
        echo getError(31) ;
    }
    else
    {
        foreach ($salles as $salle) {
?>
                    <tr>
                        <td><?php echo htmlentities($salle->getNom()) ; ?></td>
                        <td><?php echo htmlentities($salle->getEspace()->getNom()) ; ?></td>
                        <td><?php echo htmlentities($salle->getNbPostes()) ; ?></td>
                        <td><?php echo htmlentities($salle->getCommentaire()) ; ?></td>
                        <td><a href="index.php?a=44&b=2&idsalle=<?php echo htmlentities($salle->getId()) ;?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
                        <td><a href="index.php?a=44&act=3&idsalle=<?php echo htmlentities($salle->getId()) ;?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td>
                    </tr>
<?php
        }
    }
?>
                </tbody>
            </table>
        </div><!-- .box-body -->
    </div><!-- .box -->
</section>

<!-- bouton nouvelle salle-->
<section class="col-lg-3 connectedSortable"> 


<!-- AIDE -->
<div class="box">
    <div class="box-header"><h3 class="box-title">Conseil</h3></div>
    <div class="box-body"><p>Pour le mat&eacute;riel hors salle de consultation et salle d'atelier et accessible au public, pensez &agrave; cr&eacute;er une salle "mat&eacute;riel public" ou "salle consommables"...</p></div>
</div>
</section>
</div><!-- .row -->

