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
//require_once("include/class/Usage.class.php");
// gestion des fonctions des postes
// traitement des post
$act = isset($_GET["act"]) ? $_GET["act"] : '';
$idUsage = isset($_GET["idusage"]) ? $_GET["idusage"] : '';

switch ($act) {
    case 1: // creation
        $usage = isset($_POST["newusage"]) ? $_POST["newusage"] : '';
        if (Usage::creerUsage($usage) == null) {
            $mess = getError(0);
        }

        break;

    case 2: // modification
        $usage = isset($_POST["usage"]) ? $_POST["usage"] : '';
        $usageAModifier = Usage::getUsageById($idUsage);
        if (!$usageAModifier->modifier($usage)) {
            $mess = getError(0);
        }

        break;

    case 3: // suppression
        $usageASupprimer = Usage::getUsageById($idUsage);
        if (!$usageASupprimer->supprimer()) {
            $mess = getError(0);
        }
        break;
}

// affichage  -----------
$usages = usage::getUsages();

include("include/boites/menu-parametres.php");
?>

<div class="row">
    <div class="col-md-8">
        <div class="box box-solid box-warning">
            <div class="box-header"><h3 class="box-title">Gestion des fonctions pour les postes</h3></div>
            <div class="box-body no-padding">
                <table class="table">
                    <thead> 
                        <tr><th>Fonctions</th><th></th><tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($usages as $usage) {
                            ?>
                        <form action="index.php?a=46&act=2&idusage=<?php echo $usage->getId(); ?>" method="post">
                            <tr>
                                <td><input type="text" name="usage" value="<?php echo htmlentities($usage->getNom()); ?>" class="form-control"></td>
                                <td>
                                    <input type="submit" value="modifier" class="btn bg-green sm">
                                    <a href="index.php?a=46&act=3&idusage=<?php echo $usage->getId(); ?>"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a>
                                </td>
                            </tr>
                        </form>
                        <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div><!-- .box-body -->
            <div class="box-footer">
                <h4>Nouvelle fonction</h4>
                <form method="post" action="index.php?a=46&act=1">
                    <div class="input-group input-group-sm">
                        <label></label>
                        <input type="text" name="newusage" class="form-control" placeholder="Nom">
                        <span class="input-group-btn" ><button class="btn btn-primary btn-flat" type="submit" >Cr&eacute;er</button> </span>
                        <!--<input type="submit" value="Cr&eacute;er"  class="alt_btn">-->
                    </div>
                </form>
            </div>
        </div><!-- .box -->
    </div><!-- .col-md-8 -->
</div><!-- .row -->