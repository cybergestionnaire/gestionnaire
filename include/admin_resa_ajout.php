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
  2012 Dauvergne Florence

  include/admin_user.php V0.1
 */
//require_once("include/class/Materiel.class.php");
//require_once("include/class/Salle.class.php");

// admin --- Utilisateur
$term = isset($_POST["term"]) ? $_POST["term"] : '';
$idSalle = isset($_POST["salle"]) ? $_POST["salle"] : '1';

$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}

$salles = Salle::getSalles();

$epn = $_SESSION["idepn"];
//date par default : jour
$date = date('Y-m-d');
//duree par default : 1 heure
$duree = 60;
// heure par default
$heure = "12:00";
?>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <form method="post"  role="form">
                <div class="box-header"><h3 class="box-title">R&eacute;servation par adh&eacute;rent</h3></div>
                <div class="box-body">
                    <div class="input-group">
                        <label>Indiquez la salle :&nbsp;</label>
                        <select name="salle"  class="form-control pull-right">
                            <?php
                            foreach ($salles as $salle) {
                                if ($salle->getId() == $idSalle) {
                                    echo "<option  value=\"" . $salle->getId() . "\" selected>" . htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() . ")") . "</option>";
                                } else {
                                    echo "<option  value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom() . " (" . $salle->getEspace()->getNom() . ")") . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="input-group">
                        <label>Trouvez un adh&eacute;rent par son nom ou pr&eacute;nom :&nbsp;</label>
                        <input type="text" name="term">
                    </div>
                </div>
                <div class="box-footer"><input type="submit" value="Rechercher" class="btn btn-sm bg-yellow"></div>
            </form>

        </div><!-- .box -->
    </div><!-- .col-md-4 -->
    <?php
    if (isset($messErr)) {
        echo $messErr;
    }

    if (strlen($term) >= 2) {
        // Recherche d'un adherent
        $utilisateursRecherche = Utilisateur::searchUtilisateurs($term);
        $nb = count($utilisateursRecherche);
        if ($nb <= 0) {
            echo "<div class=\"col-md-6\">";
            echo getError(6);
            echo "</div>";
        } else {
            ?>
            <div class="col-md-6">
                <div class="box">
                    <form method="post" role="form">
                        <div class="box-body">
                            <h4><?php echo "R&eacute;sultats de la recherche: " . $nb; ?></h4>
                            <table class="table">
                                <thead><tr><th>Nom</th><th>Pr&eacute;nom</th><th>Age</th><th>Temps disponible</th><th>Resa</th></thead> 
                                <tbody> 
                                    <?php
                                    foreach ($utilisateursRecherche as $utilisateur) {
                                        if ($utilisateur->getStatut() == 1) {
                                            $class = "";
                                        } else {
                                            $class = "inactif";
                                        }
                                        $restant = $utilisateur->getTempsRestant();
                                        $tarifAdhesion = Tarif::getTarifById($utilisateur->getIdTarifAdhesion());
                                        if ($tarifAdhesion != null) {
                                            $adhesion = $tarifAdhesion->getNom();
                                        } else {
                                            $adhesion = '';
                                        }

                                        echo "<tr class=\"" . $class . "\">
                        <td>" . htmlentities($utilisateur->getNom()) . "</td>
                        <td>" . htmlentities($utilisateur->getPrenom()) . "</td>
                        <td>" . htmlentities($utilisateur->getAge()) . " ans</td>
                        <td>" . getTime($restant) . "</td>
                        <td><input type=\"radio\" name=\"adh_submit\" value=" . $utilisateur->getId() . ">
                        </td>
                         </tr>";
                                    } ?>
                                </tbody>
                            </table>
                            <?php
                            $materiels = Materiel::getMaterielLibreFromSalleById($idSalle); ?>
                            <div class="input-group">
                                <label>Poste : </label>
                                <select name="idcomp">
                                    <?php
                                    foreach ($materiels as $materiel) {
                                        echo "<option value=\"" . $materiel->getId() . "\">" . htmlentities($materiel->getNom()) . "</option>";
                                    } ?>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>Dur&eacute;e (en min):</label>
                                <input value="<?php echo $duree; ?>" name="duree" class="form-control">
                                <input value="1" type="hidden" name="pastresa">
                            </div>

                            <div class="input-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"> <i class="fa fa-calendar"></i></span>
                                            <input name="date" id="dt0" placeholder="Prenez une date"  value="<?php echo $date; ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Heure</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            <input  id="dt1" name="heure" value="<?php echo $heure; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.input group -->

                        </div><!-- /box body-->
                        <div class="box-footer"><input type="submit" name="resa_submit" value="valider la reservation"  class="btn btn-primary"></div>
                    </form>

                </div><!-- /box-->
            </div><!-- /col-->

            <?php
        }
    }
    ?>
</div>
<!-- /row-->
<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/atelier.js'></script>