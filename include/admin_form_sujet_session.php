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
require_once("include/class/SessionSujet.class.php");
require_once("include/class/AtelierNiveau.class.php");
require_once("include/class/AtelierCategorie.class.php");

$titre = '';
$detail = '';
$idNiveau = '';
$idCategorie = '';
$btnLabel = "Cr&eacute;er le sujet de session";
$action = "new"; //création par défaut   

$idSujet = isset($_GET["idSujet"]) ? $_GET["idSujet"] : '';
if ($idSujet != '' && $s != "del") {
    $sujet = SessionSujet::getSessionSujetById($idSujet);

    $titre = $sujet->getTitre();
    $detail = $sujet->getDetail();
    $idNiveau = $sujet->getIdNiveau();
    $idCategorie = $sujet->getIdCategorie();
    $btnLabel = "Modifier le sujet de session";
    $action = "mod"; //modification
}



//Affichage -----
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}

// if (isset($mess) && $mess != "") {
// echo $mess;
// }
?>


<!-- right column -->
<div class="col-md-8">
    <form method="post" action="index.php?a=34&s=<?php echo $action ?>" role="form">
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Formulaire d'enregistrement du sujet</h3></div>
            <div class="box-body">
                <div class="form-group">
                    <label>Sujet*</label>
                    <input type="text" name="label_session" value="<?php echo stripslashes($titre); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>D&eacute;tails</label>
                    <textarea name="content" class="form-control"><?php echo stripslashes($detail); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Niveau</label>
                    <select name="niveau" class="form-control">
                        <?php
                        $niveaux = AtelierNiveau::getAtelierNiveaux();
                        if ($niveaux !== null) {
                            foreach ($niveaux as $niveau) {
                                $selected = $niveau->getId() == $idNiveau ? "selected" : '';

                                echo "<option value=\"" . $niveau->getId() . "\" " . $selected . ">" . htmlentities($niveau->getNom()) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cat&eacute;gorie</label>
                    <select name="categorie" class="form-control" >
                        <?php
                        $categories = AtelierCategorie::getAtelierCategories();
                        if ($categories !== null) {
                            foreach ($categories as $categorie) {
                                $selected = $categorie->getId() == $idCategorie ? "selected" : '';
                                echo "<option value=\"" . $categorie->getId() . "\" " . $selected . ">" . htmlentities($categorie->getLabel()) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

            </div><!-- .box-body -->

            <!-- box content -->
            <div class="box-footer">
                <input type="hidden" name="idSujet" value="<?php echo $idSujet ?>">
                <input type="submit" name="submit_session" value="<?php echo $btnLabel ?>" class="btn btn-success">&nbsp;<a href="index.php?a=29" class="btn btn-danger">Annuler</a>
            </div>
        </div><!-- box -->
    </form>




