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
/*
2013 Modification
Fichier servant à modifier/créer la programmation d'un atelier

*/

    include_once("include/class/Salle.class.php");
    include_once("include/class/Atelier.class.php");
    include_once("include/class/AtelierSujet.class.php");
    include_once("include/class/Utilisateur.class.php");
    include_once("include/class/Tarif.class.php");    
    
    if (isset($mess) && $mess != "") {
        echo $mess;
    }

    $idAtelier = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '';
    if ($m == 4) { // cas de la suppression faite en amont, il ne faut plus essayer de récupérer l'atelier supprimé
        $idAtelier = '';
    }
    //recupérer les sujets d'atelier
    $atelierSujets = AtelierSujet::getAtelierSujets();
    //recupérer les animateurs
    $animateurs    = Utilisateur::getAnimateurs();
    //récupérer les salles
    if ($_SESSION["status"] == 4 ) {
        $salles        = Salle::getSalles();
    } else {
        $salles        = Utilisateur::getUtilisateurById($_SESSION["iduser"])->getSallesAnim();    // Pas efficace !!! Fonctionne, mais à revoir
    }
    
    //recuperation des tarifs categorieTarif(5)=forfait atelier
    $tarifs        = Tarif::getTarifsByCategorie(5);


    if ($idAtelier == '') {
        // creation
        $post_url     = "index.php?a=12&m=1";
        $label_bouton = "Programmer" ;


        //statut de l'atelier
        $stateAtelier = array(
                0=> "En cours",
                1=> "En programmation"
                //2=> "Cloturé",
                //3=> "Annulé"
            );
        if (isset($_POST["submit_atelier"]) && $_POST["submit_atelier"] != "") {
            // error_log("POST = " . print_r($_POST, true));
            $idAnim  = $_POST["anim"];
            $public  = $_POST["public"];
            $date    = $_POST["date"];
            $heure   = $_POST["heure"];
            $nbplace = $_POST["nbplace"];
            $duree   = $_POST["duree"];
            $public  = $_POST["public"];
            $idSujet = $_POST["sujet"];
            $statut  = $_POST["statut"];
            $idSalle = $_POST["salle"];
            $idTarif = $_POST["tarif"];          
        } else {
            $idAnim  = $_SESSION["iduser"];
            $public  = "Tout public";
            $date    = '';
            $heure   = '';
            $nbplace = '';
            $duree   = '';
            $idSujet = '';
            $statut  = '';
            $idSalle = '';
            $idTarif = '';
        }
    } else {
        // modification
        $post_url = "index.php?a=14&m=2&idatelier=" .  $idAtelier;
        $label_bouton = "Modifier l'atelier" ;

        $atelierEnCours = Atelier::getAtelierById($idAtelier);
        //Informations matos
        $date    = $atelierEnCours->getJour();
        $heure   = $atelierEnCours->getHeure();
        $nbplace = $atelierEnCours->getNbPlaces();
        $duree   = $atelierEnCours->getDuree();
        $public  = $atelierEnCours->getPublic();
        $idAnim  = $atelierEnCours->getIdAnimateur();
        $idSujet = $atelierEnCours->getIdSujet();
        $statut  = $atelierEnCours->getStatut();
        $idSalle = $atelierEnCours->getIdSalle();
        $idTarif = $atelierEnCours->getIdTarif();

        //statut de l'atelier
        $stateAtelier = array(
            0=> "En cours",
            1=> "En programmation",
            2=> "Clotur&eacute;",
            3=> "Annul&eacute;"
        );
    }

    $dureesa = array(
            30=>"30 min",
            60=>"1h",
            90=>"1h30",
            120=>"2h",
            150=>"2h30",
            180=>"3h");

    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }

    //pas de programmation possible su aucun sujet d'atelier n'a été rentré
    if ($atelierSujets === null) {
?>
<div class="row">
    <div class="col-md-6">
        <div class="alert alert-warning alert-dismissable">
            <i class="fa fa-warning"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;Avant d'&eacute;tablir une programmation, vous devez cr&eacute;er au moins un sujet d'atelier.
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-info alert-dismissable">
            <i class="fa fa-warning"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;<a href="index.php?a=15">Cr&eacute;er un nouveau sujet</a>
        </div>
    </div>
</div>

<?php   
    } else {
?>

<form method="post" action="<?php echo $post_url; ?>" role="form">
    <div class="row">
        <!-- Left col -->
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">Planification d'un atelier</h3></div>

                <div class="box-body">
    
                    <div class="form-group">
                        <label><span class="text-red">Sujet*</span></label>
                        <select name="sujet" class="form-control">
<?php
        foreach ($atelierSujets AS $atelierSujet) {
            if ($idSujet == $atelierSujet->getId()) {
                echo "<option value=\"" . $atelierSujet->getId() . "\" selected>" . $atelierSujet->getLabel() . "</option>";
            } else {
                echo "<option value=\"" . $atelierSujet->getId() . "\">" . $atelierSujet->getLabel() . "</option>";
            }
        }
        
        ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <label><span class="text-red">Places disponibles*</span></label>
                            <div class="input-group">
                                <input type="text" name="nbplace" value="<?php echo $nbplace;?>" class="form-control">
                            </div>
                        </div>
    
                        <div class="col-lg-6">
                            <label>Dur&eacute;e</label>
                            <div class="input-group">
                                <select name="duree" class="form-control">
<?php 
        foreach ($dureesa AS $key=>$value) {
            if ($duree == $key) {
                echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
            } else {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
        ?>
                                </select>
                            </div>
                        </div>
                    </div><!-- .row -->
                    <br>
                    <div class="row">
                        <div class="col-lg-6">
                            <label><span class="text-red">Date*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"> <i class="fa fa-calendar"></i></span>
                                <input name="date" id="dt0" placeholder="Prenez une date"  value="<?php echo $date; ?>" class="form-control">
                            </div>
                        </div>
        
        
                        <div class="col-lg-6">
                            <label><span class="text-red">Heure*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                <input  id="dt1" name="heure" value="<?php echo $heure;?>" class="form-control" placeholder="10h">
                            </div><!-- /.input group -->
                        </div>
                    </div>

                </div><!-- /box-body -->
            </div><!-- /box --> 
        </div><!-- /col -->

        <div class="col-md-6">    
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title"></h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Public concern&eacute;</label>
                        <input type="text" name="public" value="<?php echo $public;?>" class="form-control">
                    </div>
        
                    <div class="form-group">
                        <label>Salle</label>
                        <select name="salle" class="form-control">
<?php
        foreach ($salles AS $salle) {
            if ($idSalle == $salle->getId()) {
                echo "<option value=\"" . $salle->getId() . "\" selected>" . htmlentities($salle->getNom()) . " (" . htmlentities($salle->getEspace()->getNom()) . ")</option>";
            } else {
                echo "<option value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom()). " (" . htmlentities($salle->getEspace()->getNom()) . ")</option>";
            }
        }
        
?>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label>Anim&eacute; par </label>
                        <select name="anim" class="form-control">
<?php
        foreach ($animateurs AS $animateur) {
            if ($idAnim == $animateur->getId()) {
                echo "<option value=\"" . $animateur->getId() . "\" selected>" . htmlentities($animateur->getPrenom()) . " " . htmlentities($animateur->getNom()) . "</option>";
            } else {
                echo "<option value=\"" . $animateur->getId() . "\">" . htmlentities($animateur->getPrenom()) . " " . htmlentities($animateur->getNom()) . "</option>";
            }
        }
        
?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tarif</label>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Si un atelier fait partie d'une tarification sp&eacute;ciale, choisissez-l&agrave; ici, sinon laissez le 'sans tarif' par d&eacute;faut, le d&eacute;compte des ateliers se fera en fonction de ce qui a &eacute;t&eacute; pay&eacute; par l'adh&eacute;rent."><i class="fa fa-info-circle"></i></button>
                        </div><!-- /. tools -->
    
                        <select name="tarif" class="form-control" >
<?php
        foreach ($tarifs AS $tarif) {
            if ($idTarif == $tarif->getId()) {
                echo "<option value=\"" . $tarif->getId() . "\" selected>" . htmlentities($tarif->getNom()) . "</option>";
            } else {
                echo "<option value=\"" . $tarif->getId()."\">" . htmlentities($tarif->getNom() . " (" . $tarif->getDonnee(). "€)" ) . "</option>";
            }
        }
?>
                        </select>
                    </div> 
    
    
                    <div class="form-group">
                        <label>Statut </label>
                        <select name="statut" class="form-control">
<?php
        foreach ($stateAtelier AS $key=>$value) {
            if ($statut == $key) {
                echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
            } else {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
        
    ?>
                        </select>
                    </div>
                </div><!-- .box-body -->

                <div class="box-footer">
                    <input type="submit" name="submit_atelier" value="<?php echo $label_bouton; ?>"  class="btn btn-primary">
                </div>
            </div><!-- /box --> 
        </div><!-- /col -->
    </div><!-- /row -->
</form>
    

<script src='rome-master/dist/rome.js'></script>
<script>
    var moment = rome.moment;

    rome(dt0, {time: false, weekStart: 1 });
    rome(dt1, {date: false, weekStart: 1 });

    var picker = rome(ind, options={"weekStart": moment().weekday(1).day()});

    if (toggle.addEventListener) {
      toggle.addEventListener('click', toggler);
    } else if (toggle.attachEvent) {
      toggle.attachEvent('onclick', toggler);
    } else {
      toggle.onclick = toggler;
    }

    function toggler () {
      if (picker.destroyed) {
        picker.restore();
      } else {
        picker.destroy();
      }
      toggle.innerHTML = picker.destroyed ? 'Restore <code>rome</code> instance!' : 'Destroy <code>rome</code> instance!';
    }
</script>


<?php } ?>