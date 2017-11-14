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


  include/admin_form_inter.php V0.1
 */

// Formulaire de creation d'une intervention

$mesno = (string)filter_input(INPUT_GET, "mesno");
if ($mesno != "") {
    echo getError($mesno);
}

//$error = (int)filter_input(INPUT_GET, "error");
//
//switch ($error) {
//    case 1:
//        echo getError(4);
//        break;
//    case 2:
//        echo getError(0);
//        break;
//}
?>
<div class="row">
    <section class="col-lg-7 connectedSortable">

        <form method="post" action="index.php?a=3" role="form">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">D&eacute;tail de l'intervention</h3></div>
                <div class="box-body">

                    <div class="form-group">
                        <label>Titre*</label>
                        <input type="text" name="titre" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Date*</label>
                        <input type="text" name="date" style="width:100px;" maxlength="10" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Commentaire*</label>
                        <textarea name="comment" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>R&eacute;servation*</label>
                        <input type="radio" name="dispo" value="0" checked>Possible (Le poste reste disponible)<br>
                        <input type="radio" name="dispo" value="1">Impossible (Le poste devient indisponible)
                    </div>

                    <div class="form-group">
                        <label>Postes concern&eacute;s*</label>
                        <input type="checkbox" name="all" value="all"> Tous<br><br>
                        <?php
                        $materiels = Materiel::getMateriels();
                        if (count($materiels) > 0) {
                            foreach($materiels as $materiel) {
                                echo "<input type=\"checkbox\" name=\"" . $materiel->getId() . "\">" . $materiel->getNom() . " (" . $materiel->getOs() . ")<br>";
                            }
                        }
                        ?>
                    </div>

                    <div class="box-footer">
                        <input type="submit" name="submit" value="Cr&eacute;er une intervention"  class="btn btn-primary">
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>