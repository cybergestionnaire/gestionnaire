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
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));
// error_log("----      ----");

require_once("include/class/Salle.class.php");
require_once("include/class/Materiel.class.php");
require_once("include/class/Config.class.php");
require_once("include/class/Resa.class.php");

$idEspace = $_SESSION["idepn"];
$config = Config::getConfig($idEspace);

// renvoi la largeur en % par unité de temps
// $nbtot = int en mn
// $unit  = int en mn
function getWidthPerUnit($nbTotM, $unit) {
    return (10 * $unit) / (6 * $nbTotM);
}

//
function getWidth($duree, $nbtot, $unit) {
    return $duree * (getWidthPerUnit($nbtot, $unit));
}

// renvoi le decalage en % par rapport a la position en min
function getPosition($debutresa, $h1begin, $wu) {
    return (($debutresa - $h1begin) * $wu);
}

// renvoi un graf de temps en fonction des horaires matin(h1) et apm(h2)
function getPlanning($dotd, $h1begin, $h1end, $h2begin, $h2end, $epn, $salle) {

    if ($h1begin == 0 AND $h2begin > 0) //si fermé le matin
        $h1begin = $h2begin;

    if ($h2end == 0 AND $h1begin > 0)   //si ferm&eacute; l'apres midi
        $h2end = $h1end;

    if ($h1begin == 0 AND $h2end == 0) {
        return FALSE;
        exit;
    }

    // Initialisation des variables

    $graf = "";

    $config = Config::getConfig($_SESSION["idepn"]);
    $unit = $config->getDureeResaRapideOrUnitDefault();
    $unitLabel = 30;                  // echelle de division du temps pour les labels des heures

    $h1begin = (floor($h1begin / 60) * 60); // on recupere l"heure de debut ex : 9h15 =>9h => 540mn
    if ($h2end != (floor($h2end / 60) * 60))
        $h2end = ((floor($h2end / 60) * 60) + 60); // on recupere l"heure de fin ex : 19h15 =>20h

    $nbTotM = $h2end - $h1begin; // nombre total de minute d'ouverture
    $widthPause = getWidth(($h2begin - $h1end), $nbTotM, $unit) * (60 / $unit);
    $positionPause = (getPosition($h1end, $h1begin, getWidthPerUnit($nbTotM, $unit))) * (60 / $unit);

    // selection des machines par salle
    $postesLibres = Materiel::getMaterielFromSalleById($salle);

    // affichage du resultat
    //if (mysqli_num_rows($result) < 1) {
    if (count($postesLibres) < 1) {
        $graf = "Aucun ordinateur dans la salle s&eacute;lection&eacute;e, veuillez choisir une autre salle";
    } else {
        // Creation du tableau
        $graf .= "<table  class=\"table table-condensed\">";

        // ligne des horaires - echelle au dessus des reservations
        $graf .= "<tr><td></td><td >";
        for ($i = 0; $i < ($nbTotM / $unitLabel); $i++) {
            if ($i == ( ($nbTotM / $unitLabel) - 1)) {// correction bug I.E ...
                $largeur = getWidth(60, $nbTotM, $unitLabel) - 2;
                if (strlen(getTime($h1begin + ($i * $unitLabel))) <= 3)
                    $graf .= "<div class=\"labelHor\" style=\"width:" . $largeur . "%;\">|" . getTime($h1begin + ($i * $unitLabel)) . "</div>";
                else
                    $graf .= "<div class=\"labelHor1\" style=\"width:" . $largeur . "%;\">|30</div>";
            } else { // sinon normal
                $time = getTime($h1begin + ($i * $unitLabel));
                if (strlen(getTime($h1begin + ($i * $unitLabel))) <= 3)
                    $graf .= "<div class=\"labelHor\" style=\"width:" . getWidth(60, $nbTotM, $unitLabel) . "%;\">|" . $time . "</div>";
                else
                    $graf .= "<div class=\"labelHor1\" style=\"width:" . getWidth(60, $nbTotM, $unitLabel) . "%;\">|30</div>";
            }
        }
        $graf .= "</td></tr>";

        //affichage des machines + liste des reservations
        foreach ($postesLibres as $poste) {

            //old function affichage par usage//
            // if ($row['NB'] == "")
            $nbCritere = '';
            // else
            // $nbCritere=' ('.$row['NB'].')' ;
            ///

            if (strtotime($dotd) < strtotime(date("Y-m-d"))) { // pas de reservation sur les dates pass&eacute;es
                $graf .= "<tr><td class=\"computer\" >" . htmlentities($poste->getNom()) . "</td>
                            <td class=\"horaire\">";
            } else {
                if (!checkInter($poste->getId())) { //si pas d'intervention
                    $graf .= "<tr><td class=\"computer\"><a href=\"index.php?m=7&idepn=" . $epn . "&idcomp=" . $poste->getId() . "&nomcomp=" . htmlentities($poste->getNom()) . "&date=" . $dotd . "\">" . htmlentities($poste->getNom()) . "" . $nbCritere . "</a></td>
                                <td class=\"horaire\">";
                } else {
                    $graf .= "<tr><td class=\"computer\"><span data-toggle=\"tooltip\" title=\"Une intervention est en cours sur ce poste, pas de r&eacute;servation possible !\" class=\"text-red\">" . htmlentities($poste->getNom()) . "</span></td>
                                <td class=\"horaire\">";
                }
            }

            // affichage des horaires et des occupations
            //$result2   = getResa($poste->getId(), $dotd, $salle)   ;

            $resas = Resa::getResasParJourEtParMateriel($dotd, $poste->getId());
            $width = 0;
            $position = 0;
            $widthTmp = 0;
            $widthTmp2 = 0;
            $i = 0;
            foreach ($resas as $resa) {
                // while ($row2 = mysqli_fetch_array($result2)) {

                $i = 0;

                // largeur en % du div representant la resa
                $width = getWidth($resa->getDuree(), $nbTotM, $unit) * (60 / $unit);

                // recupere la position absolue dans le tableau
                $positionTmp = getPosition($resa->getDebut(), $h1begin, getWidthPerUnit($nbTotM, $unit));

                // position en % du div en cours (represente l'ecart avec celui de devant)
                $position = ($positionTmp - $widthTmp2) * (60 / $unit) - (($unit / 60) * $i);
                if ($position < 0) {
                    $position = 0;
                }

                $utilisateur = $resa->getUtilisateur();
                // Affichage de la ligne d'une machine;
                $urlGraf = "#p" . $resa->getIdUtilisateur(); //Ajout lien vers ancre dans la liste//$_SERVER['REQUEST_URI'] ; // . "&idResa=" . $row2['id_resa'];
                if ($_SESSION['status'] == 3 OR $_SESSION['status'] == 4) { // comment d'admin et d'anim
                    $altGraf = "(" . htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) . " - " . getTime($resa->getDebut()) . " &agrave; " . getTime($resa->getDebut() + $resa->getDuree()) . ")";
                } else { // comment d'utilisateur
                    $altGraf = "(" . getTime($resa->getDebut()) . " &agrave; " . getTime($resa->getDebut() + $resa->getDuree()) . ")";
                }
                $graf .= "<div class=\"unitbusy\" style=\"width:" . $width . "%;left:" . $position . "%;\">
                                        <a href=\"" . $urlGraf . "\" alt=\"" . $altGraf . "\" title=\"" . $altGraf . "\">" . htmlentities(substr($utilisateur->getPrenom(), 0, 1)) . " . &nbsp;" . htmlentities($utilisateur->getNom()) . "</a>
                                    </div>";
                $widthTmp = $widthTmp + $width;
                $widthTmp2 = $widthTmp / (60 / $unit);
                ++$i;
                //echo $position.'% = (PA:'.$positionTmp.'-W'.$widthTmp.')*(60/'.$unit.') -- width:'.$width.'% -- nbTotM:'.$nbTotM.'<br/>';
            }
            // fin de l'affichage des horaires et des occupations
            $graf .= "</td></tr>";
        }

        // ligne des horaires - echelle en dessous du tableau de reservation 2
        $graf .= "<tr><td></td><td >";
        for ($i = 0; $i < ($nbTotM / $unitLabel); $i++) {
            if ($i == (($nbTotM / $unitLabel) - 1)) { // correction bug I.E ...
                if (strlen(getTime($h1begin + ($i * $unitLabel))) <= 3)
                    $graf .= "<div class=\"labelHor\" style=\"width:" . (getWidth(60, $nbTotM, $unitLabel) - 2) . "%;\">|" . getTime($h1begin + ($i * $unitLabel)) . "</div>";
                else
                    $graf .= "<div class=\"labelHor1\" style=\"width:" . (getWidth(60, $nbTotM, $unitLabel) - 2) . "%;\">|30</div>";
            } else {// sinon normal
                if (strlen(getTime($h1begin + ($i * $unitLabel))) <= 3)
                    $graf .= "<div class=\"labelHor\" style=\"width:" . getWidth(60, $nbTotM, $unitLabel) . "%;\">|" . getTime($h1begin + ($i * $unitLabel)) . "</div>";
                else
                    $graf .= "<div class=\"labelHor1\" style=\"width:" . getWidth(60, $nbTotM, $unitLabel) . "%;\">|30</div>";
            }
        }

        $graf .= "</td></tr>";
        $graf .= "</table>";
    }

    return $graf;
}

// renvoi les horaires d'ouverture sous forme d'une phrase.
function getHoraireTexte($horaire) {

    if ($horaire->getHoraire1Debut() != 0 AND $horaire->getHoraire1Fin() != 0)
        $retour = "matin : " . getTime($horaire->getHoraire1Debut()) . " &agrave; " . getTime($horaire->getHoraire1Fin());
    else
        $retour = "Ferm&eacute; le matin ";

    if ($horaire->getHoraire2Debut() != 0 AND $horaire->getHoraire2Fin() != 0)
        $retour .= ", apr&egrave;s midi " . getTime($horaire->getHoraire2Debut()) . " &agrave; " . getTime($horaire->getHoraire2Fin());
    else
        $retour .= ", Ferm&eacute; l'apr&egrave;s midi";

    if ($horaire->getHoraire1Debut() != "" AND $horaire->getHoraire1Fin() == 0 AND $horaire->getHoraire2Debut() == 0 AND $horaire->getHoraire2Fin() != "")
        $retour = getTime($horaire->getHoraire1Debut()) . " &agrave; " . getTime($horaire->getHoraire2Fin());

    return $retour;
}

///pour modifier la duree d'une résa en cours
function getHorDureeSelect2($duree, $hbegin, $dateResa, $idMateriel, $epn) {

    // duree maximum d'une reservation dans le fichier config
    $config = Config::getConfig($epn);
    $maxtime = $config->getMaxTimeOrDefaultMaxTime();
    $unit = $config->getTimeUnit();


    $prochaineResa = Resa::getProchaineResasParJourEtParMateriel($dateResa, $idMateriel, $hbegin);

    // // on verifie l'existence d'une reservation apres celle demandee
    // si oui on calcul l'ecart
    if ($prochaineResa !== null) {
        $maxtimedb = $prochaineResa->getDebut() - $hbegin;
        if ($maxtime > $maxtimedb) {
            $maxtime = $maxtimedb;
        }
    }

    //select
    $select = "<select name=\"duree\">";

    for ($i = $unit; $i <= $maxtime; $i = $i + $unit) {
        if ($i == $duree) {
            $select .= '<option value="' . $i . '" selected="selected">' . getTime($i) . '</option>';
        } else {
            $select .= '<option value="' . $i . '">' . getTime($i) . '</option>';
        }
    }
    $select .= "</select> ";
    return $select;
}

// On met a jour la duree de la resa
include ("post_reservation-rapide.php");
$term = isset($_POST["term"]) ? $_POST["term"] : '';

if (isset($_POST['modify_duration'])) {
    $idResa = $_POST['idResa'];
    $duree = $_POST['duree'];
    $resa = Resa::getResaById($idResa);
    $resa->setDuree($duree);

    //updateDureeResa($_POST);
}

// Affichage des reservations par utilisateur
if ($_SESSION['status'] == 3 OR $_SESSION['status'] == 4) {
    if (isset($_GET['del']) && is_numeric($_GET['del'])) {
        $resa = Resa::getResaById($_GET['del']);
        if ($resa !== null) {
            $resa->supprimer();
        }
        //delResa2($_GET['del']) ;
    }
}

// re initialisation des variables debut et duree
if (isset($_SESSION['debut'])) {
    unset($_SESSION['debut']);
    unset($_SESSION['duree']);
}

// Fichier de reservation d'un poste
//recuperation des get
$jour = isset($_GET["jour"]) ? $_GET["jour"] : date('d');
$mois = isset($_GET["mois"]) ? $_GET["mois"] : date('n');
$annee = isset($_GET["annee"]) ? $_GET["annee"] : date('Y');


if (isset($_SESSION["idSalle_reservation"])) {
    $idSalle = $_SESSION["idSalle_reservation"];
} else {
    $idSalle = '';
}

if (isset($_POST['modifsalle'])) {
    $idSalle = $_POST['Psalle'];
    $_SESSION["idSalle_reservation"] = $idSalle;
}


if (!checkDate($mois, $jour, $annee)) {
    $_GET["jour"] = 1;
    $jour = 1;
}

//Affichage de la salle
// on recupere le num du jour de la semaine ˆ, 1, 2, ...7 
$dayNum = date("N", mktime(0, 0, 0, $mois, $jour, $annee));
$dayArr = array("", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");


// affichage du titre et des horaires d'ouverture en fonction de la salle choisie
if ($_SESSION["status"] == 3) {
    $arraysalles = getSallesbyAnim($_SESSION["iduser"]);
    $listesalles = explode(";", $arraysalles["id_salle"]);
    $nbsalles = count($listesalles);
    $allsalles = array();
    for ($i = 0; $i < $nbsalles; $i++) {
        $allsalles[$listesalles[$i]] = getNomsalleforAnim($listesalles[$i]);
    }

    if (!isset($idSalle)) {
        $idSalle = $listesalles[0];
    }
}

if ($_SESSION["status"] == 4 OR $_SESSION["status"] == 1) {

    $salles = Salle::getSalles();
    // $idEspace  = $_SESSION["idepn"];
    if ($idSalle == '' and ! is_null($salles)) {
        $idSalle = $salles[0]->getId();  //premiere salle par défaut, il serait sans doute opportun de vérifier l'espace d'appartenance pour l'utilisateur,
        // et de lui affecter une salle de cet espace
    }
}

//Affichage -----
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
if ($mesno != '') {
    echo geterror($mesno);
}
?>
<div class="row">
    <!--calendrier resa future -->

    <div class="col-md-3">
        <div class="box">
            <?php include ("include/calendrier.php"); ?>
        </div>
    </div>
    <?php
//** page utilisateur Aide + acces archives
    if ($_SESSION["status"] == 1) {
        ?>
        <div class="col-md-4">
            <div class="box box-default box-solid">
                <div class="box-header with-border"><h3 class="box-title">Aide</h3></div>
                <div class="box-body">
                    <p>Vous pouvez faire une r&eacute;servation pour le jour d'ouverture correspondant &agrave; la structure que vous d&eacute;sirez en cliquant sur un poste. La demande sera enregistr&eacute;e automatiquement.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="box">
                <div class="box-header"><h3 class="box-title">Actions</h3></div>
                <div class="box-body">
                    <a class="btn btn-app" href="index.php?m=8"><i class="fa fa-inbox"></i> Archives</a>
                    <a class="btn btn-app"><i class="fa fa-save"></i> Enregistrer</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <?php
    }
    ?>


    <?php
///pas de resa rapide dans le futur ou le passe
    $dateget = $annee . '-' . $mois . '-' . $jour;

    if ($dateget == date('Y-n-d')) {
        ///Affichage ou non de la resa rapide selon la configuration
        // $resamode = getConfigConsole($idEspace, "resarapide");
        if ($config->hasResaRapide() AND ( $_SESSION["status"] == 3 OR $_SESSION["status"] == 4)) {
            ?>


            <!-- Reservation rapide -->

            <div class="col-md-3">
                <div class="box">
                    <div class="box-header"><h3 class="box-title">R&eacute;servation rapide par adh&eacute;rent</h3></div>
                    <div class="box-body">
                        <form method="post"  role="form">
                            <div class="input-group input-group-sm">
                                <input type="text" name="term" class="form-control pull-right"  placeholder="Entrez un nom ou un pr&eacute;nom">
                                <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                            <form method="post"  role="form">
                                <div class="box-body">
                                    <h4><?php echo "R&eacute;sultats de la recherche: " . $nb; ?></h4>
                                    <table class="table">
                                        <thead><tr><th>Nom</th><th>Pr&eacute;nom</th><th>Age</th><th>Temps disponible</th><th>Resa</th></thead> 
                                        <tbody> 
                                            <?php
                                            $duree = 0; //duree de la resa determinée par config_horaire


                                            foreach ($utilisateursRecherche as $utilisateur) {
                                                if ($utilisateur->getStatut() == 1) {
                                                    $class = "";
                                                } else {
                                                    $class = "inactif";
                                                }
                                                $restant = $utilisateur->getTempsRestant();

                                                if ($restant <= $config->getDureeResaRapide() AND $restant > $duree) { // FONCTIONNEMENT A REVOIR !!
                                                    $duree = $restant;
                                                }

                                                $disabled = $restant <= 0 ? "disabled" : "";
                                                echo "<tr class=\"" . $class . "\">
                            <td>" . htmlentities($utilisateur->getNom()) . "</td>
                            <td>" . htmlentities($utilisateur->getPrenom()) . "</td>
                            <td>" . $utilisateur->getAge() . " ans</td>
                            <td>" . getTime($restant) . "</td>
                            <td><input type=\"radio\" name=\"adh_submit\" value=" . $utilisateur->getId() . " " . $disabled . ">
                            </td>
                             
                             </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    $minute = str_split(date('i'));
                                    $min = 0;
                                    if ($minute[1] >= 0 and $minute[1] < 4) {
                                        $min = substr_replace(date('i'), "0", 1, 1);
                                    } else if ($minute[1] > 3 and $minute[1] < 8) {
                                        $min = substr_replace(date('i'), "5", 1, 1);
                                    } else if ($minute[1] > 7) {
                                        $minu = ($minute[0] + 1) . "0";
                                        $min = substr_replace(date('i'), $minu, 0, 2);
                                    }

                                    $heure = date('G') * 60 + $min;


                                    $postesLibres = Materiel::getMaterielLibreFromSalleById($idSalle);
                                    ?>


                                    <div class="input-group">
                                        <label>Poste : </label>
                                        <select name="idcomp">
                                            <?php
                                            foreach ($postesLibres as $poste) {
                                                echo "<option value=\"" . $poste->getId() . "\">" . htmlentities($poste->getNom()) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="input-group">
                                        <label>Heure d'arriv&eacute;e :</label>
                                        &nbsp;maintenant (<?php echo date('G') . ":" . $min; ?>)&nbsp;<input value="<?php echo $heure; ?>" type="hidden" name="heure">
                                    </div>
                                    <div class="input-group">
                                        <label>Dur&eacute;e :</label>&nbsp;<?php echo $duree; ?> min 
                                        <input value="<?php echo $duree; ?>" type="hidden" name="duree">
                                        <input value="<?php echo $restant; ?>" type="hidden" name="restant">
                                        <input value="0" type="hidden" name="pastresa">
                                        <input value="<?php echo (date('Y') . "-" . date('m') . "-" . date('d')); ?>" type="hidden" name="date">
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <input type="submit" name="resa_submit" value="valider la reservation"  class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div><!-- /col-->

                    <?php
                }
            }
        }
    }
    ?>

</div>
<!-- /row-->

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Planning par postes</h3>
                <div class="box-tools">
                    <form method="post" role="form">
                        <div class="input-group">
                            <select name="Psalle"  class="form-control pull-right" style="width: 200px;">
                                <?php
                                foreach ($salles as $salle) {
                                    if ($idSalle == $salle->getId()) {
                                        echo "<option  value=\"" . $salle->getId() . "\" selected>" . htmlentities($salle->getNom() . ' (' . $salle->getEspace()->getNom() . ')') . "</option>";
                                    } else {
                                        echo "<option  value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom() . ' (' . $salle->getEspace()->getNom() . ')') . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div class="input-group-btn">
                                <button type="submit" value="Rafraichir"  name="modifsalle" class="btn btn-default" style="height: 34px;"><i class="fa fa-repeat"></i></button>
                            </div>
                        </div>
                    </form>
                </div><!-- .box-tools -->
            </div>
            <div class="box-body">

                <?php
                $horaires = Horaire::getHorairesByIdEspace($idEspace);

                echo "<h4>" . $dayArr[$dayNum] . " " . $jour . " " . getMonthName($mois) . " " . $annee . "    <small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"S&eacute;lectionnez un poste pour y faire une r&eacute;servation\"><i class=\"fa fa-info\"></i></small></h4>";
                echo "<p>Horaires d'ouverture : " . getHoraireTexte($horaires[$dayNum - 1]) . "</p>";


                // Affichage des machines
                // et affichage du planning
                // on efface d'eventuel session restante
                unset($_SESSION['resa']);

                // on garde l'url d'origine pour la redirection a la fin de l'enregistrement de la resa
                $_SESSION['resa']['url'] = $_SERVER['REQUEST_URI'];

                // on stocke la date du jour de la resa
                $_SESSION['resa']['date'] = $annee . "-" . $mois . "-" . $jour;

                //$row   = getHoraire($dayNum, $idEspace) ;
                // $table = getPlanning($_SESSION['resa']['date'], $row["hor1_begin_horaire"], $row["hor1_end_horaire"], $row["hor2_begin_horaire"], $row["hor2_end_horaire"], $idEspace, $idSalle) ;
                $table = getPlanning($_SESSION['resa']['date'], $horaires[$dayNum - 1]->getHoraire1Debut(), $horaires[$dayNum - 1]->getHoraire1Fin(), $horaires[$dayNum - 1]->getHoraire2Debut(), $horaires[$dayNum - 1]->getHoraire2Fin(), $idEspace, $idSalle);

                if ($table != FALSE) {
                    // affichage du planning
                    echo $table;
                    ?>
                </div>
            </div><!-- .box -->
            <?php
            // Affichage des reservations par utilisateur pour les admins
            if ($_SESSION['status'] == 3 OR $_SESSION['status'] == 4) {
                // $resultresa = getResa('All', $_SESSION['resa']['date'], $idSalle) ;

                $resas = Resa::getResasParJourEtParSalle($_SESSION['resa']['date'], $idSalle);
                ?>
                <div class="box box-info">
                    <div class="box-header"><h3 class="box-title">R&eacute;servation par utilisateur</h3></div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nom de l'adh&eacute;rent</th> 
                                    <th>Machine</th>
                                    <th>D&eacute;but</th>
                                    <th>Fin</th>
                                    <th>Dur&eacute;e </th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                foreach ($resas as $resa) {

                                    $utilisateur = $resa->getUtilisateur();

                                    $tarifAdhesion = $utilisateur->getTarifAdhesion();
                                    if ($tarifAdhesion != null) {
                                        $adhesion = $tarifAdhesion->getNom();
                                    } else {
                                        $adhesion = '';
                                    }
                                    if ($utilisateur->getStatut() == 2) {
                                        $class = "text-muted";
                                        $info = "<span class=\"badge bg-primary\" title=\"Renouvellement de l'adh&eacute;sion depuis le " . dateFr($utilisateur->getDaterenouvellement()) . " au tarif : " . htmlentities($adhesion) . "\" data-toggle=\"tooltip\"><i class=\"fa fa-info\"></i></span>";
                                    } else {
                                        $class = "";
                                        $info = "";
                                    }
                                    ?>
                                    <tr>
                                <form method="post" role="form">
                                    <td>
                                        <a href="index.php?a=1&b=2&iduser=<?php echo $resa->getIdUtilisateur() ?>"><button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="    Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a>
                                        <a href="index.php?a=21&b=1&iduser=<?php echo $resa->getIdUtilisateur() ?>"><button type="button" class="btn bg-navy sm" data-toggle="tooltip" title="Ajouter impressions"><i class="fa fa-print"></i></button></a>
                                    </td>

                                    <td>
                                        <h5 id="p<?php echo $resa->getIdUtilisateur() ?>" class='<?php echo $class ?>'><?php echo htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom()) ?>&nbsp;&nbsp;<?php echo $info ?></h5>
                                    </td>
                                    <td>
                                        <h5><?php echo htmlentities($resa->getMateriel()->getNom()) ?></h5>
                                    </td>
                                    <td>
                                        <h5><?php echo getTime($resa->getDebut()) ?></h5>
                                    </td>
                                    <td>
                                        <h5><?php echo getTime($resa->getDebut() + $resa->getDuree()) ?></h5>
                                    </td>
                                    <td>
                                        <?php echo getHorDureeSelect2($resa->getDuree(), $resa->getDebut(), $_SESSION['resa']['date'], $resa->getIdMateriel(), $idEspace) ?>
                                        <!--<input type="checkbox" name="free" '.$check.'/>-->
                                        <input type="submit" value="mod . " name="modify_duration" class="btn btn-sm"/>
                                        <input type="hidden" name="idResa" value="<?php echo $resa->getId() ?>"/>
                                    </td>
                                    <td>
                                        <a href="<?php echo $_SERVER['REQUEST_URI'] ?>&del=<?php echo $resa->getId() ?>"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a>
                                    </td>
                                </form>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div><!-- .box-body -->
                </div><!-- .box -->
                <?php
            }
        } else {// si le jour n'est pas ouvr&eacute;
            echo geterror(19);
        }
        ?>
    </div><!-- .col-md-12 -->
</div><!-- .row -->