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

// fichier de gestion des breves

$mesno = (string)filter_input(INPUT_GET, "mesno");
if ($mesno != "") {
    echo getError($mesno);
}
?>

<div class="row">
<?php
//chargement des breves

$breves = Breve::getBreves();
if ($breves === null) {
        echo getError(0);
} else {
    if (count($breves) == 0) {
?>        
    <div class="col-md-8">
        <?php echo getError(10) ?>
    </div>
<?php 

    } else {
?>
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">Liste des br&egrave;ves</h3></div>
            <div class="box-body no-padding">
                <table class="table">
                    <thead><tr><th>Titre</th><th>Date de publication</th><th></th></tr></thead>
                    <tbody>
<?php
        foreach($breves as $breve) {
?>
                        <tr>
                            <td><?php echo $breve->getTitre()?></td>
                            <td><?php echo $breve->getDatePublication()?></td>
                            <td>
                                <a href="index.php?a=4&b=2&idbreve=<?php echo $breve->getId() ?>" class="btn bg-green sm"><i class="fa fa-edit"></i></a>
                                <a href="index.php?a=4&b=3&act=3&idbreve=<?php echo $breve->getId() ?>" class="btn bg-red sm"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
<?php
        }
?>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
<?php
    }
?>        
    <div class="col-md-4">
        <div class="small-box bg-light-blue">
            <div class="inner"><h3>&nbsp;</h3><p>Nouvelle Br&egrave;ve</p></div>
            <div class="icon"><i class="ion ion-clipboard"></i></div>
            <a href="index.php?a=4&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
<?php
}
?>
</div>