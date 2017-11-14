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

//require_once("include/class/SessionSujet.class.php");
/*
  require_once("include/class/AtelierSujet.class.php");
  require_once("include/class/AtelierNiveau.class.php");
  require_once("include/class/AtelierCategorie.class.php");
 */
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
                <h3 class="box-title">Liste des sujets de session</h3>
                <div class="box-tools pull-right">
                    <a href="index.php?a=34"><button class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Ajouter"><i class="fa fa-plus"></i></button></a>
                </div>
            </div>

            <div class="box-body no-padding">
                <table class="table">
                    <thead> <tr> 
                            <th>Titre</th>
                            <th>D&eacute;tails</th>
                            <th>Niveau</th>
                            <th>Categorie</th>
                        </tr></thead>

                    <tbody>
                        <?php
                        $sessionSujets = SessionSujet::getSessionSujets();
                        if ($sessionSujets !== null) {
                            foreach ($sessionSujets as $sessionSujet) {
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($sessionSujet->getTitre()); ?></td>
                                    <td><?php echo htmlentities($sessionSujet->getDetail()); ?></td>
                                    <td><?php echo htmlentities($sessionSujet->getNiveau()->getNom()); ?></td>
                                    <td><?php echo htmlentities($sessionSujet->getCategorie()->getLabel()); ?></td>
                                    <td><a href="index.php?a=34&s=mod&idSujet=<?php echo htmlentities($sessionSujet->getId()); ?>"><button class="btn btn-success"  type="submit" value="modifier"><i class="fa fa-refresh"></i></button></a></td>
                                    <td><a href="index.php?a=34&s=del&idSujet=<?php echo htmlentities($sessionSujet->getId()); ?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td>
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
