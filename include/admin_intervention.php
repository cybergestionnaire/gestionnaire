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

// error_log('in admin_intervention.php -------------------------');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));

//recuperation des get & post

$act = (string)filter_input(INPUT_GET, "act");
$idInter = (int)filter_input(INPUT_GET, "idinter");
$statut = (string)filter_input(INPUT_POST, "statut");

switch ($act) {
    case "mod":
        $inter = Intervention::getInterventionById($idInter);
        if ($statut == "0") {
            if ($inter->setInterventionEnCours()) {
                echo getError(14);
            } else {
                echo getError(0);
            }
        }

        if ($statut == "1") {
            if ($inter->setInterventionTerminee()) {
                echo getError(14);
            } else {
                echo getError(0);
            }
        }
            
        break;
    case "del":
        $inter = Intervention::getInterventionById($idInter);
        if ($inter->supprimer()) {
            echo getError(14);
        } else {
            echo getError(0);
        }
        break;
}

//$result = getAllInter();
$inters = Intervention::getInterventions();

$mesno = (string)filter_input(INPUT_GET, "mesno");
if ($mesno != "") {
    echo getError($mesno);
}
?>

<div class="row"> 
    <div class="col-md-9">
        <?php
            $nb = count($inters);
            if ($nb == 0) {
                echo getError(12);
            } else {
        ?>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Interventions archiv&eacute;es</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead> 
                        <tr> <th>Date</th><th>Poste</th><th>Type d'intervention</th><th>Commentaire</th><th>Statut</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php
                        // affichage des interventions
                        foreach ($inters as $inter) {
                            switch ($inter->getStatut()) {
                                case 0:
                                    $sel1 = "selected";
                                    $sel2 = "";
                                    break;

                                case 1:
                                    $sel1 = "";
                                    $sel2 = "selected";
                                    break;
                            }
                        ?>
                        <tr>
                            <td><?php echo $inter->getDate() ?></td>
                            <td>
                                <?php
                                $materiels = $inter->getMateriels();
                                if ($materiels != null) {
                                    $nb2 = count($materiels);
                                    if ($nb2 > 0) {
                                        $retour = '';
                                        foreach($materiels as $materiel) {
                                            if ($retour != '') {
                                                $retour .= ",&nbsp;";
                                            }
                                            $retour .= $materiel->getNom();
                                        }
                                        
                                        echo $retour;
                                    }
                                } ?>
                            </td>
                            <td><?php echo htmlentities($inter->getTitre()) ?></td>
                            <td><?php echo htmlentities($inter->getCommentaire()); ?></td>

                            <td >
                                <form action="index.php?a=3&act=mod&idinter=<?php echo $inter->getId(); ?>" method="post" role="form">
                                    <select name="statut" class="form-control" style="width:200px;">
                                        <option value="0" <?php echo $sel1; ?>>Intervention en cours</option>
                                        <option value="1" <?php echo $sel2; ?>>Intervention termin&eacute;e</option>
                                    </select>
                                    <input type="submit" value="Modifier le statut" class="form-control">
                                </form>
                            </td>
                            <td>
                                <a href="index.php?a=3&act=del&idinter=<?php echo $inter->getId(); ?>" class="btn bg-red sm"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div><!-- .box -->

        <?php
            } 
        ?>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-light-blue">
            <div class="inner"><h3>&nbsp;</h3><p>nouvelle intervention</p></div>
            <div class="icon"><i class="ion ion-wrench"></i></div>
            <a href="index.php?a=3&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


</div>