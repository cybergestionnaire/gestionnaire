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



  Creation des ateliers dans la base
 */
require_once("include/class/AtelierSujet.class.php");
require_once("include/class/AtelierNiveau.class.php");
require_once("include/class/AtelierCategorie.class.php");

$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}
?>
<div class="row">
    <section class="col-lg-9 connectedSortable">

        <!-- liste des salles existants-->
        <div class="box box-solid box-warning">
            <div class="box-header">
                <h3 class="box-title">Liste des Sujets</h3>
                <div class="box-tools pull-right">
                    <a href="index.php?a=15"><button class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Ajouter"><i class="fa fa-plus"></i></button></a>
                </div>
            </div>

            <div class="box-body no-padding">
                <table class="table">
                    <thead> <tr> 
                            <th>Nom</th>
                            <th>Contenu</th>
                            <th>Ressource</th>
                            <th>Categorie</th>
                            <th>Niveau</th>
                        </tr></thead>

                    <tbody>
                        <?php
                        $atelierSujets = AtelierSujet::getAtelierSujets();
                        if ($atelierSujets !== null) {
                            foreach ($atelierSujets as $atelierSujet) {
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($atelierSujet->getLabel()); ?></td>
                                    <td><?php echo htmlentities($atelierSujet->getContent()); ?></td>
                                    <td><?php echo htmlentities($atelierSujet->getRessource()); ?></td>
                                    <td><?php echo htmlentities($atelierSujet->getNiveau()->getNom()); ?></td>
                                    <td><?php echo htmlentities($atelierSujet->getCategorie()->getLabel()); ?></td>
                                    <td><a href="index.php?a=15&idSujet=<?php echo htmlentities($atelierSujet->getId()); ?>"><button class="btn btn-success"  type="submit" value="modifier"><i class="fa fa-refresh"></i></button></a></td>
                                    <td><a href="index.php?a=17&b=13&idSujet=<?php echo htmlentities($atelierSujet->getId()); ?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td>
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
</div><!-- .row -->
