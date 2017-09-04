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


  include/user_reservation_form.php V0.1
 */
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));
// error_log("----      ----");

require_once("include/class/Horaire.class.php");
require_once("include/class/Config.class.php");
require_once("include/class/Resa.class.php");

// fonctions additionnelles
//renvoi l'affichage du form de reseravtion pour une machine
// @param1 : etape 1 ou 2
// @param2 : id du computer
// @param3 : date du jour de la reseravtion
// @param4 : select a afficher
// @return : renvoi l
function getResaComp($step, $idcomp, $date_resa, $select)
{
    switch ($step) {
        case 1:// step 1
            $table = "<form method=\"post\" action=\"" . $_SERVER["REQUEST_URI"] . "\">";
            $table .= "<table><tr><td>";
            // $table .= "<thead><th>D&eacute;but de la reservation</th></thead>";
            $table .= $select;
            $table .= "</td><td valign=\"top\"><input type=\"hidden\" name=\"step\" value=\"1\">
                           <input type=\"submit\" class=\"btn btn-success\" name=\"submit1\" value=\"valider l'&eacute;tape 1\">";
            $table .= "</td></tr></table></form>";
            break;
        case 2: //step 2
            $table = "<form method=\"post\" action=\"" . $_SERVER["REQUEST_URI"] . "\">";
            $table .= "<table><tr><td>";
            // $table .= "<div>Durée de la reservation </div>";
            $table .= $select;
            $table .= "</td><td valign=\"top\"><input type=\"hidden\" name=\"step\" value=\"2\">
                                   <!--<input type=\"submit\" class=\"btn btn-default\" name=\"retour\" value=\"<<\">-->
                                   <input type=\"submit\" name=\"submit2\" class=\"btn btn-success\" value=\"valider l'&eacute;tape 2 >>\">";
            $table .= "</td></tr></table></form>";
            break;
    }
    return $table;
}

// renvoi un select contenant les horaires de reservation
// @param1 : unité
// @param2 : Heure d'ouverture matin
// @param3 : Heure de fermeture matin
// @param4 : Heure d'ouverture de l'apres midi
// @param5 : Heure de fermeture de l'apres midi
function getHorDebutSelect($unit, $h1begin, $h1end, $h2begin, $h2end, $idcomp, $dateResa, $hselected)
{
    //renvoi le tableau des valeurs deja reservées
    $arrayResa = Resa::getResaArray($idcomp, $dateResa, $unit);
    // on boucle pour afficher
    // $heureX=strftime("%H",time());

    $hselected = Horaire::convertHoraire(strftime("%H", time())) + 30; //affichage de l'heure en cours
    //debug($hselected);

    $select = "";
    if ($h1begin == $h1end) {
        $h1begin = $h2begin;
    }
    if ($h2end < $h1end) {
        $h2end = $h1end;
    }

    for ($i = $h1begin; $i < $h2end; $i = $i + $unit) {
        /*
          if ($i<$h1end OR $i>=$h2begin)
          { */
        if (in_array($i, $arrayResa) or ($i >= $h1end and $i < $h2begin)) {
            $select .= "<option value=\"" . $i . "\" disabled style=\"background-color:#EEEEEE\">" . getTime($i) . "</option>";
        } elseif ($i == $hselected) {
            $select .= "<option value=\"" . $i . "\" selected>" . getTime($i) . "</option>";
        } else {
            $select .= "<option value=\"" . $i . "\">" . getTime($i) . "</option>";
        }
        // }
    }
    if ($select != "") {
        $select = "<select name=\"debut\" size=\"15\" >" . $select . "</select>";
    }

    return $select;
}

function getHorDureeSelect($unit, $h1begin, $h1end, $h2begin, $h2end, $idcomp, $dateResa, $hselected, $idEspace)
{
    // maxtime = initialisation du temps maximum de reseravtion a partir de l'heure donnee pour la date et la machine demande
    //requete pour definir la duree maximum par rapport au reservation en base
    $sql = "SELECT debut_resa
              FROM tab_resa
              WHERE dateresa_resa='" . $dateResa . "'
              AND id_computer_resa=" . $idcomp . "
              AND debut_resa>" . $hselected . "
              ORDER BY debut_resa ASC
              LIMIT 1";

    $db = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);

    // on verifie l'existence d'une reservation apres celle demandee
    if (mysqli_num_rows($result) > 0) {// si oui on calcul l'ecart
        $row = mysqli_fetch_array($result);
        $maxtimedb = $row['debut_resa'] - $hselected;
    } else {
        $maxtimedb = 9999999;
    }

    // duree maximum d'une reservation dans le fichier config
    $config = Config::getConfig($idEspace);
    $maxtime = $config->getMaxTimeOrDefaultMaxTime();

    // on verifie si on se trouve dans l'interval du matin
    if ($hselected < $h1end) {
        $delta = $h1end - $hselected;
    } elseif ($hselected >= $h2begin) {
        $delta = $h2end - $hselected;
    }

    //temps maximum determine par la config
    if ($maxtimedb < $maxtime) {
        $maxtime = $maxtimedb;
    }

    if (isset($delta) && $delta < $maxtime) {
        $maxtime = $delta;
    }

    //select
    $select = "";

    // on boucle
    for ($i = $unit; $i <= $maxtime; $i = $i + $unit) {
        if (isset($_SESSION["duree"]) && $i == $_SESSION["duree"]) {
            $select .= "<option value=\"" . $i . "\" selected>" . getTime($i) . "</option>";
        } else {
            $select .= "<option value=\"" . $i . "\">" . getTime($i) . "</option>";
        }
    }
    if ($select != "") {
        $select = "<select name=\"duree\" size=\"15\" multiple>" . $select . "</select> ";
    }

    return $select;
}

$semaine = get_lundi_dimanche_from_week(date('W'));
$date1 = strftime("%Y-%m-%d", $semaine[0]);
$date2 = strftime("%Y-%m-%d", $semaine[1]);
$idEspace = isset($_GET["idepn"]) ? $_GET["idepn"] : $_SESSION["idepn"];
$etape = isset($_POST["step"]) ? $_POST["step"] : 0;

// error_log("etape = $etape");
// affichage de form de reservation
if (is_numeric($_GET["idcomp"])) {
    // initialisation
    $step1 = 'step';
    $step2 = 'step';
    $step3 = 'step';

    //l'affectation depuis la console à été désactivée dans cette version !
    // if (isset($_GET["debut"]) and $step == 0) { // cas de l'affectation depuis la console
    // $_SESSION['resa']['idcomp']    = $_GET['idcomp'];
    // $_SESSION['resa']['nomcomp']   = $_GET['nomcomp'] ;
    // $_SESSION['resa']['materiel']  = getMateriel($_GET['idcomp']);
    // $_SESSION['resa']['date']      = $_GET["date"];
    // $_SESSION['debut']             = $_GET["debut"];
    // $step = 2;
    // }
    //  affichage des etapes

    $dayNum = date("N", strtotime($_SESSION['resa']['date']));
    $horaires = Horaire::getHorairesByIdEspace($idEspace);


    switch ($etape) {
        case 1: // etape 2 durée
            $step1 = 'previousStep';
            $step2 = 'currentStep';
            $titre = 'Choix de la dur&eacute;e de la r&eacute;servation';

            $select = getHorDureeSelect(
                    $config->getTimeUnit(),
                $horaires[$dayNum - 1]->getHoraire1Debut(),
                $horaires[$dayNum - 1]->getHoraire1Fin(),
                $horaires[$dayNum - 1]->getHoraire2Debut(),
                $horaires[$dayNum - 1]->getHoraire2Fin(),
                $_SESSION['resa']['idcomp'],
                $_SESSION['resa']['date'],
                $_SESSION['debut'],
                $idEspace
            );

            if ($select != "") {
                $step = getResaComp(
                        2,
                    $_SESSION['resa']['idcomp'],
                    $_SESSION['resa']['date'],
                    $select
                );
            } else {
                $step = "Pas d'horaires disponibles !";
            }
            break;

        case 2: // etape 3
            $step1 = 'previousStep';
            $step2 = 'previousStep';
            $step3 = 'currentStep';
            $titre = 'Confirmation de votre r&eacute;servation';

            // affichage
            if (isset($_SESSION['other_user'])) {
                $utilisateur = Utilisateur::getUtilisateurById($_SESSION['other_user']);
                $reserve = '<dt>R&eacute;servation pour : </dt><dd> ' . htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom()) . '</dd>';
            } else {
                $utilisateur = Utilisateur::getUtilisateurById($_SESSION['iduser']);
                $reserve = '<dt>R&eacute;servation par : </dt><dd> ' . htmlentities($utilisateur->getPrenom() . " " . $utilisateur->getNom()) . '<dd>';
            }
            $step = '<dl class="dl-horizontal">' . $reserve . '
                    <dt>prevue le : </dt><dd> ' . dateFr($_SESSION['resa']['date']) . '<dd>
                    <dt>De </dt><dd>' . getTime($_SESSION["debut"]) . ' &agrave; ' . getTime($_SESSION["debut"] + $_SESSION["duree"]) . '
                    (Dur&eacute;e : ' . getTime($_SESSION["duree"]) . ')</dd>                    
                     <dt>Ordinateur s&eacute;lectionn&eacute; : </dt><dd>' . $_SESSION['resa']['nomcomp'] . '</dd>
                    </dl>';


            $test = "";
            //choix de l'utilisateur si on est autorise
            if ($_SESSION['status'] == 4 or $_SESSION['status'] == 3) {
                $searchuser = isset($_POST['adh']) ? $_POST['adh'] : "";
                $step .= '<form method="post" action="' . $_SERVER["REQUEST_URI"] . '" role="form">
                        <p class="lead">Entrez un adh&eacute;rent (nom ou num&eacute;ro de carte):</p> 
                        <div class="input-group input-group-sm">  <input type="text" name="adh" class="form-control">
                        <input type="hidden" name="step" value="2">
                        <span class="input-group-btn"><button type="submit" value="Rechercher" name="adh_submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
                        </form>
                    ';
                //affichage du resultat de la recherche
                if ($searchuser != "" and strlen($searchuser) > 2) {
                    // Recherche d'un adherent

                    $utilisateursRecherche = Utilisateur::searchUtilisateurs($searchuser);
                    $nbUtilisateursRecherche = count($utilisateursRecherche);

                    if ($utilisateursRecherche == null or $nbUtilisateursRecherche == 0) {
                        echo getError(6);
                    } else {
                        if ($nbUtilisateursRecherche > 0) {
                            $test = "<b>R&eacute;sultats de la recherche: " . $nbUtilisateursRecherche . "</b>";
                            $test .= '<table class="table"><thead>
                                    <tr><th>&nbsp;</th><th>Nom, Pr&eacute;nom</th><th>Login</th><th>Temps restant</th><th>Infos</th></tr></thead><tbody>';
                            foreach ($utilisateursRecherche as $utilisateurRecherche) {
                                $dateadhesion = strtotime($utilisateurRecherche->getDateRenouvellement());
                                $aujourdhui = strtotime(date('Y-m-d'));

                                if ($utilisateurRecherche->getStatut() == 2) {
                                    $class = "text-muted";
                                    if ($dateadhesion < $aujourdhui) {
                                        $info = '<small class="badge bg-blue" data-toggle="tooltip" title="adh&eacute;sion &agrave; renouveller"><i class="fa fa-info"></i></small> ';
                                    } else {
                                        $info = '<small class="badge bg-blue" data-toggle="tooltip" title="compte inactif"><i class="fa fa-info"></i></small>';
                                    }
                                } else {
                                    $class = "";
                                    $info = "";
                                }

                                $test .= "<form method=\"post\" role=\"form\" >
                                        <input type=\"hidden\" name=\"step\" value=\"2\">
                                        <tr>
                                        <td><input type=\"hidden\" value=\"" . $utilisateurRecherche->getId() . "\" name=\"choose\"/>
                                        <button type=\"submit\" class=\"btn btn-success sm\" value=\"S&eacute;lectionner\" name=\"choose_adh\"/> <i class=\"fa fa-check\"></i></button></td>
                                  
                                        <td><a href=\"index.php?a=1&b=2&iduser=" . $utilisateurRecherche->getId() . "\" data-toggle=\"tooltip\" title=\"Fiche adh&eacute;rent\"><span class=" . $class . ">" . htmlentities($utilisateurRecherche->getNom()) . " " . htmlentities($utilisateurRecherche->getPrenom()) . "</span></a></td>
                                        <td><span class=" . $class . ">" . $utilisateurRecherche->getLogin() . "</span></td>
                                        <td>" . getTime($utilisateurRecherche->getTempsrestant()) . "</td>
                                        <td>" . $info . "</td>
                                        </tr></form>";
                            }

                            $test .= '</tbody></table>';
                        }
                    }
                }
            }

            $step .= '<br>' . $test . '<form method="post" action="' . $_SERVER["REQUEST_URI"] . '" role="form">
                <!--<input type="submit" name="retour" class="btn btn-default btn-flat" value=" <<">-->
                <input type="submit" class="btn btn-success btn-flat" name="valider" value="Valider la r&eacute;servation">
                     </form>
                    ';

            break;

        default: // etape 1: choix de l'heure de debut
            $step1 = 'currentStep';
            $submit = 'Etape suivante';
            //recuperation des GET
            $_SESSION['resa']['idcomp'] = $_GET['idcomp'];
            $_SESSION['resa']['nomcomp'] = $_GET['nomcomp'];
            $_SESSION['resa']['materiel'] = getMateriel($_GET['idcomp']);


            $titre = 'Choix de l\'heure de d&eacute;but de la r&eacute;servation';
            $select = getHorDebutSelect(
                    $config->getTimeUnit(),
                $horaires[$dayNum - 1]->getHoraire1Debut(),
                $horaires[$dayNum - 1]->getHoraire1Fin(),
                $horaires[$dayNum - 1]->getHoraire2Debut(),
                $horaires[$dayNum - 1]->getHoraire2Fin(),
                $_SESSION['resa']['idcomp'],
                $_SESSION['resa']['date'],
                    //$_SESSION['debut']
                    0
            );

            if ($select != "") {
                $step = getResaComp(
                        1,
                    $_SESSION['resa']['idcomp'],
                    $_SESSION['resa']['date'],
                    $select
                );
            } else {
                $step = "Pas de reservations pour ce jour !";
            }
            break;
    }
}
//affichage

if (checkInter($_SESSION['resa']['idcomp'])) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> <b>ATTENTION</b> </h4>Une intervention est en cours sur cette machine, veuillez vous adresser &agrave;
        votre animateur afin qu'il vous confirme la possibilit&eacute; de r&eacute;server cette machine
    </div>
<?php
} ?>

<div class="row">
    <section class="col-lg-7 connectedSortable"> 

        <div class="box">
            <div class="box-header"><h3 class="box-title">R&eacute;servation</h3></div>
            <div class="box-body">

                <a class="<?php echo $step1; ?> btn btn-default">Etape 1 / 3</a>
                <a class="<?php echo $step2; ?> btn btn-default">Etape 2 / 3</a>
                <a class="<?php echo $step3; ?> btn btn-default">Etape 3 / 3</a>

            </div>
        </div>


        <div class="box">
            <div class="box-header"><h3 class="box-title"><?php echo $titre; ?></h3></div>
            <div class="box-body">

                <?php
                if (isset($messErr)) {
                    echo '<div class="callout callout-danger"><h4>' . $messErr . '</h4></div>';
                }
                ?>

                <?php echo $step; ?>


            </div>

            <div class="box-footer">
                <a href="<?php echo $_SESSION['resa']['url']; ?>"><input type="submit" name="annuler" value="Annuler la r&eacute;servation"  class="btn btn-warning"></a>
            </div>

        </div>


    </section>
</div>

