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

 2006 Namont Nicolas (CyberMin)
 

  include/admin_config.php V0.1
*/

// Configuration de l'espace
require_once("include/class/Espace.class.php");
require_once("include/class/Config.class.php");

if (isset($_GET["mess"]) && $_GET["mess"] == "ok")
{
  echo '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Mise &agrave; jour effectu&eacute;e</div>';
  
}
// chargement des valeurs pour l'epn par d&eacute;faut

$idEspace = isset($_GET['epnr']) ? $_GET['epnr'] : $_SESSION['idepn'];

// Choix de l'epn   -------------------------------------
$espaces = Espace::getEspaces();
//debug($idEspace);
$dureearray = array("30" => "30 min", "60" => "1 heure", "90" => "1h30", "120" => "2 heures");


include("include/boites/menu-parametres.php");

?>



<div class="row">
    <!-- Colonne de gauche -->
    <div class="col-md-6">
        
        <!-- NOM ESPACE -->	
        <form action="index.php?a=42" method="post" role="form">
            <div class="box box-warning">
                <div class="box-header"><h3 class="box-title">Les horaires de l'espace choisi</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Choisissez l'espace :</label>
                        <select name="epn_r" class="form-control" >
<?php
    foreach ($espaces AS $espace) {
        if ($idEspace == $espace->getId()) {
            echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
        } else {
            echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
        }
    }
?>
                        </select>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="form" value="1">
                        <button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</button>
                    </div>
                </div><!-- .box-body -->
            </div><!-- .box -->
        </form>


        <!-- MODULE HORAIRES-->

        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Horaires d'ouverture</h3></div>
            <div class="box-body no-padding">
                <form method="post" action="index.php?a=42">
<?php
  
    if (isset($_GET["mess"]) && $_GET["mess"] == "Hwrong") {
        echo getError(15) ;
    } 



    $horaires = Espace::getEspaceById($idEspace)->getHoraires();
    
    $table = "
<table class=\"table\">
    <tr>
        <th style=\"width: 10px\">&nbsp;</th>
        <th>TRANCHE 1 (Matin)</th>
        <th>TRANCHE 2 (Apres-midi)</th>
    </tr>\r\n" ;

    // affichage
    
    //for ($i = 1 ; $i < 8 ; $i++) {
        
        //$row = getHoraire($i,$idEspace) ;
      
    foreach($horaires as $horaire) {
        if (isset($_GET["dayline"]) && $horaire->getIdJour() == $_GET["dayline"]) {
            $color = "#CC9999";
        } else {
            $color = "#FFFFFF";
        }
        
        $table .= "    <tr style=\"background-color:{$color}\">\r\n";
        $table .= "        <td>" . $horaire->getJour() . "</td>\r\n";
        $table .= "        <td class=\"selH\"><b>de </b>\r\n" ;
    // H1 Matin begin
    // tableau des heures
        $H = "" ;
        $H .= "<option value=\"\"></option>" ;
     // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
        for ($heure = 6 ; $heure <= 23 ; $heure++) {
            if (strlen($heure)<2) {
                $heure = "0".$heure ;
            }
            for ($minutes = 0 ; $minutes <= 59 ; $minutes = $minutes + 15) {
               if ($horaire->getHoraire1Debut() == Horaire::convertHoraire($heure."h".$minutes))
                  $select = "selected" ;
               else
                  $select = "";

               if ($minutes == 0 ) {
                  $minutes = "00" ;
               }
               $H .= "<option value=\"" . Horaire::convertHoraire($heure."h".$minutes) . "\" " . $select . ">" . $heure . "h" . $minutes . "</option>" ;
           }
       }

        $table .= "            <select name=\"" . $horaire->getIdJour() . "-h1begin\">" . $H . "</select>\r\n";
        $table .= "            <b> &agrave; </b>\r\n" ;
    // H1 Matin end
        
        
    // H1 Apres-midi begin
        $H = "" ;
        $H .= "<option value=\"\"></option>" ;
        // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
        for ($heure = 6 ; $heure <= 23 ; $heure++) {
            if (strlen($heure)<2) {
                $heure = "0".$heure ;
            }
            for ($minutes = 0 ; $minutes <= 59 ;$minutes = $minutes + 15) {
                if ($horaire->getHoraire1Fin() == Horaire::convertHoraire($heure."h".$minutes)) {
                    $select = "selected" ;
                } else {
                    $select = "";
                }

                if ($minutes == 0) {
                    $minutes = "00" ;
                }
                $H .= "<option value=\"" . Horaire::convertHoraire($heure."h".$minutes) . "\" " . $select . ">" . $heure . "h" . $minutes . "</option>" ;
            }
        }

        $table .= "            <select name=\"" . $horaire->getIdJour() . "-h1end\">".$H."</select>\r\n" ;
    // H1 Apres-midi end
        
        $table .= "        </td>
        <td class=\"selH\"><b>de </b>\r\n" ;
    
    // H2 Matin begin
        $H = "" ;
        $H .= "<option value=\"\"></option>" ;
      // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
        for ($heure = 6 ; $heure <= 23 ; $heure++) {
            if (strlen($heure) < 2) {
                $heure = "0".$heure ;
            }
            for ($minutes = 0 ; $minutes <= 59 ;$minutes = $minutes + 15) {
                if ($horaire->getHoraire2Debut() == Horaire::convertHoraire($heure."h".$minutes)) {
                    $select = "selected" ;
                } else {
                    $select = "";
                }

                if ($minutes == 0 ) {
                    $minutes  ="00" ;
                }
                $H .= "<option value=\"" . Horaire::convertHoraire($heure."h".$minutes) . "\" " . $select . ">" . $heure . "h" . $minutes . "</option>" ;
            }
        }

        $table .= "            <select name=\"" . $horaire->getIdJour() . "-h2begin\">".$H."</select>\r\n";
        $table .= "            <b> &agrave; </b>\r\n" ;
    // H2 Matin end
    
    // H2 Apres-midi begin    
        $H = "" ;
        $H .= "<option value=\"\"></option>" ;
//            $H .= "<option value=\"\">Ferm&eacute;</option>\r\n" ;
        for ($heure = 6 ; $heure <= 23 ; $heure++) {
            if (strlen($heure) < 2) {
                $heure = "0".$heure ;
            }
            for ($minutes = 0 ; $minutes <= 59 ;$minutes=$minutes+15) {
                if ($horaire->getHoraire2Fin() == Horaire::convertHoraire($heure."h".$minutes)) {
                    $select = "selected" ;
                } else {
                    $select = "";
                }

                if ($minutes == 0 ) {
                    $minutes  ="00" ;
                }
                $H .= "<option value=\"" . Horaire::convertHoraire($heure."h".$minutes) . "\" " . $select . ">" . $heure . "h" . $minutes . "</option>" ;
            }
        }

        $table .= "            <select name=\"" . $horaire->getIdJour() . "-h2end\">".$H."</select>\r\n" ;
    // H2 Apres-midi end
    
        $table .= "        </td>
    </tr>\r\n";
    }
    $table .= "
    <tr>
        <td colspan=\"3\">
            <span style=\"font-size:10px;\">* La modification des horaires peut entrainer des probl&egrave;mes au niveau des reservations de machines et des statistiques d'occupation.</span>
            <div class=\"box-footer\">
                <input type=\"hidden\" name=\"form\" value=\"2\">
                <input type=\"hidden\" name=\"epn_r\" value=\"" . $idEspace . "\">
                <button type=\"submit\" value=\"Valider * \" name=\"submit\" class=\"btn btn-primary\">Valider *</button>
            </div>
        </td>
    </tr>
</table>\r\n";

    echo $table ;
?>
                </form>
            </div><!-- .box-body -->
        </div><!-- .box -->
<?php
// tranche de reservation
    $config   = Config::getConfig($idEspace);
?>

    </div><!--/.col-md-6 (left) -->
                       
    <!-- right column -->
    <div class="col-md-6">	
 
        <div class="box box-warning">
            <form method="post" action="index.php?a=42">
                <div class="box-header"><h3 class="box-title">Param&egrave;trage des r&eacute;servations</h3></div>
                <div class="box-body">
                <!-- Param&eacute;trages du planning des r&eacute;servations -->
                    <div class="form-group">
                        <label>Unit&eacute; de temps (min): <small class="badge bg-blue" data-toggle="tooltip" title="Pour le planning des r&eacute;servations, la plus petite portion de temps &agrave; accorder par tranche de 5, 10, x minutes..."><i class="fa fa-info"></i></small></label>
                        <input type="text" value="<?php echo htmlentities($config->getTimeUnit()); ?>" name="unit" class="form-control" placeholder="Min">
                        <label>Dur&eacute;e maximum (min): <small class="badge bg-blue" data-toggle="tooltip" title="Dur&eacute;e maximum de la r&eacute;servation d'un poste par personne "><i class="fa fa-info"></i></small></label>
                        <input type="text" value="<?php echo htmlentities($config->getMaxTimeOrDefaultMaxTime()); ?>" name="maxtime" class="form-control" placeholder="Min">
                    </div>
                
                <!-- Param&eacute;trages de la r&eacute;servation rapide -->
                    <div class="form-group">
                        <label>Activer la r&eacute;servation rapide ?</label>
<?php
    
    if ($config->getResaRapide()) {
        $sel1 = "" ;
        $sel2 = "checked=\"checked\"";
    } else {
        $sel1 = "checked=\"checked\"" ;
        $sel2 = "";
    }
    
?>
                        <input type="radio"  value="0" name="resarapide" <?php echo $sel1; ?>> Non &nbsp;
                        <input type="radio"  value="1" name="resarapide" <?php echo $sel2; ?>> Oui
                    </div>

                    <div class="form-group">
                        <label>S&eacute;lectionnez la dur&eacute;e par d&eacute;faut pour la r&eacute;servation rapide</label>
                        <select class="form-control" name="duree_resarapide">
<?php
    $dureerr  = $config->getDureeResaRapideOrUnitDefault();
//    $dureerr  = getConfig("duree_resarapide", "unit_default_config", $idEspace);  // <--- ????? pourquoi revenir sur unit_default en cas d'échec ?

    foreach ($dureearray as $key=>$value) {
        if ($dureerr == $key) {
            echo "                        <option value=\"" . $key . "\" selected>" . $value . "</option>";
        } else {
            echo "                        <option value=\"" . $key . "\">" . $value . "</option>";
        }
    }
    
?>
                        </select>
                
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="form" value="3">
                    <input type="hidden" name="epn_r" value="<?php echo $idEspace; ?>">
                    <button type="submit" value="Valider" name="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div><!-- .box -->


    
        <!-- FERMETURES ANNUELLLES-->

        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Selection des jours ou l'espace sera ferm&eacute; en <?php echo date("Y");?> :</h3></div>
            <div class="box-body">
                <form method="get" action="index.php?a=42" role="form">
                    <input type="hidden" name="a" value="42">
                    <input type="hidden" name="epnr" value="<?php echo $idEspace; ?>">
                    
                    <div class="form-group">
                        <label>S&eacute;lectionnez la p&eacute;riode &agrave; afficher:</label>
                        <select name="display" class="form-control">                               
                            <option value="">Mois en cours</option>
                            <option value="all">Vue compl&egrave;te sur l'ann&eacute;e <?php echo date("Y");?></option>
                            <option value="1">Janvier <?php echo date("Y");?></option>
                            <option value="2">F&eacute;vrier <?php echo date("Y");?></option>
                            <option value="3">Mars <?php echo date("Y");?></option>
                            <option value="4">Avril <?php echo date("Y");?></option>
                            <option value="5">Mai <?php echo date("Y");?></option>
                            <option value="6">Juin <?php echo date("Y");?></option>
                            <option value ="7">Juillet <?php echo date("Y");?></option>
                            <option value="8">Aout <?php echo date("Y");?></option>
                            <option value="9">Septembre <?php echo date("Y");?></option>
                            <option value="10">Octobre <?php echo date("Y");?></option>
                            <option value="11">Novembre <?php echo date("Y");?></option>
                            <option value="12">D&eacute;cembre <?php echo date("Y");?></option>
                        </select>&nbsp;<input type="submit" name="submit" value="ok" class="alt_btn">
                        <br>
                        <div style="font-size:10px;">Cliquez sur un jour pour le rendre feri&eacute; (inaccessible au public) et vice versa pour le rendre ouvr&eacute;.</div>
                    </div>
	
                </form>

<?php
    if (isset($_GET["idday"])) {         // mise a jour d'un jour
        $check = checkDayOpen(intval($_GET["idday"]), intval(date("Y")), $idEspace);
        // debug(is_int(intval($_GET["idday"])));
        // debug(updateDay(intval($_GET["idday"]),intval(date("Y")),$idEspace)) ;

        if ($check == 0) {
            insertJourFerie(intval($_GET["idday"]), intval(date("Y")), $idEspace);
        } else {
            deleteJourFerie($check);
        }
    }

    $display = isset($_GET["display"]) ? $_GET["display"] : '';
    switch ($display) {
        case "all": // affichage par an
            for ($i = 1 ; $i < 13 ; $i++) {
                echo "<span style=\"float:left;width:32%;height:300px;\">" . getCalendarClose(date("Y"), $i, $idEspace) . "&nbsp;&nbsp;&nbsp;</span>" ;
            }
        break;
        default: //affichage du mois
            if ($display != "") {
                $month = $display;
            } else {
                $month = date("n");
            }
            echo getCalendarClose(date("Y"), $month, $idEspace) ;
        break;
    }
?>

            </div><!-- .box-body -->
        </div><!-- .box -->
    </div><!-- .col-md-6 (right) -->
</div><!-- .row -->
