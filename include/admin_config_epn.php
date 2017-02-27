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

// Fichier de gestion des espaces ...

include("include/boites/menu-parametres.php");
?>

<div class="row">
    <section class="col-lg-3 connectedSortable"> 
<?php 

    if (getReseau() == FALSE) { 
?>
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="box-body">
                    <div class="alert alert-danger alert-dismissable">
                        <i class="fa fa-ban"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Attention!</b>
                        La mise &agrave; jour a &eacute;chou&eacute;, veuillez recommencer la proc&eacute;dure !
                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        $rowreseau = getReseau();
?>
        <div class="box box-solid box-primary">
            <div class="box-header"><h3 class="box-title">Votre r&eacute;seau</h3></div>
            <div class="box-body">
                <form method="post" action="index.php?a=43&b=4">
                    <div class="input-group input-group-sm">
                        <input class="form-control" type="text" name="reseau" value="<?php echo $rowreseau['res_nom']; ?>">
                        <span class="input-group-btn"><button class="btn btn-success btn-sm"  type="submit" value="modifier" data-toggle="tooltip" title="Modifier les param&egrave;tres de votre r&eacute;seau"><i class="fa fa-edit"></i></button></span>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

<?php } ?>

    </section>

    <section class="col-lg-9 connectedSortable">  
    <!-- liste des espaces existants-->
        <div class="box box-solid box-warning">
            <div class="box-header"><h3 class="box-title">Liste des espaces</h3>
                <div class="box-tools pull-right">
                    <a href="index.php?a=43&b=1"><button class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Ajouter"><i class="fa fa-plus"></i></button></a>
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table">
                    <thead> <tr> 
                        <th>Nom de l'espace</th> 
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Mail</th>
                        <th>&nbsp;</th>
                    </tr></thead>
                    <tbody>
<?php
    $espaces = Espace::getEspaces();
    if ($espaces == null || count($espaces) == 0)
    {
        echo getError(30) ;
    } else {
        foreach ($espaces as $espace) {
?>
                        <tr>
                            <td><?php echo htmlentities($espace->getNom()) ; ?></td>
                            <td><?php echo htmlentities($espace->getAdresse()) ; ?></td>
                            <td><?php echo htmlentities($espace->getVille()->getNom()) ; ?></td>
                            <td><?php echo htmlentities($espace->getMail()) ; ?></td>
                            <td ><a href="index.php?a=43&b=2&idespace=<?php echo htmlentities($espace->getId());?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier"><i class="fa fa-pencil-square-o"></i></button></a>
<?php
            if ($espace->getId() > 1 ) {  //suppression de l\'epn de reference impossible 
?>
                                <a href="index.php?a=43&b=3&act=3&idespace=<?php echo htmlentities($espace->getId());?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button></a>
<?php       }  ?>
                            </td>
                        </tr>
<?php
        }
    }
?>
                    <tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </section><!-- /.Left col -->

</div>
