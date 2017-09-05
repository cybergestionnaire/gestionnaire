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
require_once("include/class/Salle.class.php");
require_once("include/class/Utilisateur.class.php");
require_once("include/class/Tarif.class.php");

//chargement parametres
$idsession = isset($_GET["idsession"]) ? $_GET["idsession"] : '';
$m = isset($_GET["m"]) ? $_GET["m"] : '';

$sessionSujets = SessionSujet::getSessionSujets();
if ($_SESSION["status"] == 4) {
    $salles = Salle::getSalles();
} else {
    $salles = Utilisateur::getUtilisateurById($_SESSION["iduser"])->getSallesAnim();    // Pas efficace !!! Fonctionne, mais à revoir
}
$animateurs = Utilisateur::getAnimateurs();
$tarifs = Tarif::getTarifsByCategorie(5);

//statuts des dates
$statusarray = array(
    0 => "Atelier En cours",
    2 => "Atelier Annul&eacute; / Annuler",
    3 => "Supprimer"
);

$nbr_date = 2; // valeur par défaut
$idTitre = "";
$nbplace = "";
$idAnim = "";
$idSalle = "";
$idTarif = "";

//verifications en cas d'envoi partiel avec fautes
if (isset($_SESSION['sauvegarde'])) {
    // $_POST    = $_SESSION["sauvegarde"] ;
    $idTitre = $_SESSION["sauvegarde"]["idTitre"];
    $nbr_date = $_SESSION["sauvegarde"]['nbre_dates'];
    $nbplace = $_SESSION["sauvegarde"]['nbplace'];
    $idSalle = $_SESSION["sauvegarde"]['idSalle'];
    $idAnim = $_SESSION["sauvegarde"]['idAnim'];
    $idTarif = $_SESSION["sauvegarde"]['idTarif'];
    $post_url = "index.php?a=31&m=1";
    $label_bouton = "Planifier";
    //debug($_POST);
    //dates à récuperer
    for ($i = 1; $i <= $nbr_date; $i++) {
        ${'date' . $i} = $_SESSION["sauvegarde"]["date" . $i];
        ${'statutdate' . $i} = $_SESSION["sauvegarde"]["statutdate" . $i];
    }

    // $date1  = $_POST["date1"];
    // $date2  = $_POST["date2"];
    // $date3  = $_POST["date3"];
    // $date4  = $_POST["date4"];
    // $date5  = $_POST["date5"];
    // $date6  = $_POST["date6"];
    // $date7  = $_POST["date7"];
    // $date8  = $_POST["date8"];
    // $date9  = $_POST["date9"];
    // $date10 = $_POST["date10"];
    // $date11 = $_POST["date11"];
    // $date12 = $_POST["date12"];
    // $date13 = $_POST["date13"];
    // $date14 = $_POST["date14"];
    // $date15 = $_POST["date15"];
    // $date16 = $_POST["date16"];
    // $date17 = $_POST["date17"];
    // $date18 = $_POST["date18"];
    // $date19 = $_POST["date19"];
    // $date20 = $_POST["date20"];

    unset($_SESSION['sauvegarde']);
} else {
    if ($m == 1) {  // creation
        $post_url = "index.php?a=31&m=1";
        $label_bouton = "Planifier";
        $idAnim = $_SESSION["iduser"];
        $dates = date('Y-m-d');
        //recuperation du nombre de dates à planifier
        $nbr_date = isset($_POST["nbre_dates_sessions"]) ? $_POST["nbre_dates_sessions"] : 2;

        for ($f = 1; $f <= $nbr_date; $f++) {
            ${'statutdate' . $f} = 0;
            ${'date' . $f} = '';
        }
    } elseif ($m == 2) { // modification
        $post_url = "index.php?a=31&m=2&idsession=" . $idsession;
        $label_bouton = "Modifier";

        $session = Session::getSessionById($idsession);
        $idTitre = $session->getIdSessionSujet();
        $nbplace = $session->getNbPlaces();
        $nbr_date = $session->getNbDates();
        $idAnim = $session->getIdAnimateur();
        $idSalle = $session->getIdSalle();
        $idTarif = $session->getIdTarif();

        //retrouver toutes les dates actives
        // $datesarray = getDatesSession($idsession);
        $sessionDates = SessionDate::getSessionDatesByIdSession($idsession);
        // $session = Session::getSessionById($idsession);
        // $sessionDates = $session->getSessionDates();

        for ($f = 1; $f <= $nbr_date; $f++) {
            // $row = mysqli_fetch_array($datesarray);
            ${'date' . $f} = $sessionDates[$f - 1]->getDate();
            ${'statutdate' . $f} = $sessionDates[$f - 1]->getStatut();
        }
        //recuperation du nombre de dates à planifier
        if (isset($_POST["nbre_dates_sessions"])) {
            if ($_POST["nbre_dates_sessions"] > $nbr_date) {
                for ($f = $nbr_date + 1; $f <= $_POST["nbre_dates_sessions"]; $f++) {
                    ${'statutdate' . $f} = 0;
                    ${'date' . $f} = '';
                }
            }
            $nbr_date = $_POST["nbre_dates_sessions"];
        }
    }
}

$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != "") {
    echo getError($mesno);
}

//pas de programmation possible su aucun sujet d'atelier n'a été rentré
if ($sessionSujets === null) {
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-warning alert-dismissable">
                <i class="fa fa-warning"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;Avant d'&eacute;tablir une programmation, vous devez cr&eacute;er au moins un sujet de session.
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info alert-dismissable">
                <i class="fa fa-warning"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;<a href="index.php?a=34">Cr&eacute;er un nouveau sujet</a>
            </div>
        </div>
    </div>

    <?php
} else {
        ?>
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
            <div class="box box-success">
                <div class="box-body">
                    <p class="text-blue">Commencez par choisir le nombre de dates de votre session, puis &agrave; saisir les dates. Elles seront automatiquement remise en ordre &agrave; la validation.
                        En modification, choisir "Supprimer" pour que la date soit retir&eacute;e de la liste, ne modifiez pas le nombre, il sera automatiquement rafraichit. </p>
                    <p class="text-blue">NB : L'annulation n'est pas une suppression, l'atelier restera dans les statistiques.</p>
                    <form method="post" action="#">
                        <div class="form-group">
                            <div class="input-group">
                                <label>Nombre de dates * : </label>
                                <select name="nbre_dates_sessions" class="input-sm" style="width: 150px;">
                                    <?php
                                    for ($i = $nbr_date; $i <= 20; $i++) {
                                        if ($i == $nbr_date) {
                                            echo "<option value=\"" . $i . "\" selected>" . $i . "</option>";
                                        } else {
                                            echo "<option value=\"" . $i . "\">" . $i . "</option>";
                                        }
                                    } ?>
                                </select>
                                <button  class="btn btn-default"><i class="fa fa-repeat"></i></button>
                            </div>
                        </div>
                    </form>
                </div><!-- .box-body -->
            </div>
        </section>
    </div>
    <div class="row">
        <form method="post" action="<?php echo $post_url; ?>">

            <section class="col-lg-6 connectedSortable">

                <div class="box box-success">
                    <div class="box-header"><h3 class="box-title">Programmation d'une session</h3></div>
                    <div class="box-body">

                        <div class="form-group">
                            <label>Titre *</label>
                            <select name="idTitre" class="form-control" >
                                <?php
                                foreach ($sessionSujets as $sessionSujet) {
                                    if ($idTitre == $sessionSujet->getId()) {
                                        echo "<option value=\"" . $sessionSujet->getId() . "\" selected>" . $sessionSujet->getTitre() . "</option>";
                                    } else {
                                        echo "<option value=\"" . $sessionSujet->getId() . "\">" . $sessionSujet->getTitre() . "</option>";
                                    }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nombre des membres*</label>
                            <input type="text" name="nbplace" value="<?php echo $nbplace; ?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Salle</label>
                            <select name="idSalle" class="form-control">
                                <?php
                                foreach ($salles as $salle) {
                                    if ($idSalle == $salle->getId()) {
                                        echo "<option value=\"" . $salle->getId() . "\" selected>" . htmlentities($salle->getNom()) . " (" . htmlentities($salle->getEspace()->getNom()) . ")</option>";
                                    } else {
                                        echo "<option value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom()) . " (" . htmlentities($salle->getEspace()->getNom()) . ")</option>";
                                    }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Anim&eacute; par </label>
                            <select name="idAnim" class="form-control">
                                <?php
                                foreach ($animateurs as $animateur) {
                                    if ($idAnim == $animateur->getId()) {
                                        echo "<option value=\"" . $animateur->getId() . "\" selected>" . htmlentities($animateur->getPrenom() . ' ' . $animateur->getNom()) . "</option>";
                                    } else {
                                        echo "<option value=\"" . $animateur->getId() . "\">" . htmlentities($animateur->getPrenom() . ' ' . $animateur->getNom()) . "</option>";
                                    }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tarif</label>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Si un atelier fait partie d'une tarification sp&eacute;ciale, choisissez-l&agrave; ici, sinon laissez le 'sans tarif' par d&eacute;faut, le d&eacute;compte des ateliers se fera en fonction de ce qui a &eacute;t&eacute; pay&eacute; par l'adh&eacute;rent."><i class="fa fa-info-circle"></i></button>
                            </div><!-- /. tools -->

                            <select name="idTarif" class="form-control" >
                                <?php
                                foreach ($tarifs as $tarif) {
                                    if ($idTarif == $tarif->getId()) {
                                        echo "<option value=\"" . $tarif->getId() . "\" selected>" . htmlentities($tarif->getNom() . " (" . $tarif->getDonnee() . "€)") . "</option>";
                                    } else {
                                        echo "<option value=\"" . $tarif->getId() . "\">" . htmlentities($tarif->getNom() . " (" . $tarif->getDonnee() . "€)") . "</option>";
                                    }
                                } ?>
                            </select>
                        </div> 
                    </div><!-- .box-body -->

                    <div class="box-footer"><input type="submit" name="submit_session" value="<?php echo $label_bouton; ?>" class="btn btn-primary"></div>

                </div><!-- .box -->
            </section>
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable">
                <div class="box box-success">
                    <div class="box-header"><h3 class="box-title">Dates de la session</h3></div>
                    <div class="box-body">

                        <div class="form-group">
                            <label>Dates &agrave; planifier *  </label><!--<p class="help-block">Cochez pour supprimer une date</p>-->
                            <input type="hidden" name="nbre_dates" value="<?php echo $nbr_date; ?>" >
                        </div>

                        <?php
                        for ($i = 1; $i <= $nbr_date; $i++) {
                            ?>                        
                            <div class="form-group">
                                <label><?php echo $i ?>.</label>
                                <?php
                                $nomVariable1 = "statutdate" . $i;
                            $nomVariable2 = "date" . $i;
                            if ($$nomVariable1 == 1) {
                                echo ' <span class="text-muted">' . $$nomVariable2 . '  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="' . $nomVariable2 . '" id="dt' . $i . '" class="hidden" value="' . $$nomVariable2 . '"><input name="' . $nomVariable1 . '" class="hidden" value="' . $$nomVariable1 . '">';
                            } elseif ($$nomVariable1 == 2) {
                                echo ' <span class="text-muted">' . $$nomVariable2 . '  &nbsp;&nbsp;&nbsp;Atelier annul&eacute; </span><input name="' . $nomVariable2 . '"  id="dt' . $i . '" class="hidden" value="' . $$nomVariable2 . '"><input name="' . $nomVariable1 . '" class="hidden" value="' . $$nomVariable1 . '">';
                            } else {
                                ?>                        
                                    <input name="date<?php echo $i ?>" class='input'  id="dt<?php echo $i ?>" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $$nomVariable2; ?>" >
                                    <select name="statutdate<?php echo $i ?>" >
                                        <?php
                                        foreach ($statusarray as $key => $value) {
                                            if ($$nomVariable1 == $key) {
                                                echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                            } else {
                                                echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                            }
                                        } ?>
                                    </select>                        
                                    <?php
                            } ?>
                            </div>                        
                            <?php
                        }

        //envoyer aussi son id
        if ($idsession != "") {
            $datesarray2 = getDatesSession($idsession);
            for ($y = 1; $y <= $nbr_date; $y++) {
                $row2 = mysqli_fetch_array($datesarray2);
                echo "<input type=\"hidden\" name=\"iddate" . $y . "\" value=\"" . $row2['id_datesession'] . "\">\r\n";
            }
        }
        //<input type=\"hidden\" name=\"statutdate".$y."\" value=".$row2['statut_datesession']." > ?>
                    </div><!-- .box-body -->
                </div><!-- .box -->

            </section>

        </form>
    </div>
    <script src='rome-master/dist/rome.min.js'></script>
    <script>
        var moment = rome.moment;

    <?php
    for ($i = 1; $i <= $nbr_date; $i++) {
        echo "rome(dt" . $i . ", { weekStart: 1 });";
    } ?>
        var picker = rome(ind, options = {"weekStart": moment().weekday(1).day()});

        if (toggle.addEventListener) {
            toggle.addEventListener('click', toggler);
        } else if (toggle.attachEvent) {
            toggle.attachEvent('onclick', toggler);
        } else {
            toggle.onclick = toggler;
        }

        function toggler() {
            if (picker.destroyed) {
                picker.restore();
            } else {
                picker.destroy();
            }
            toggle.innerHTML = picker.destroyed ? 'Restore <code>rome</code> instance!' : 'Destroy <code>rome</code> instance!';
        }
    </script>

<?php
    } ?>