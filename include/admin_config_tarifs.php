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


 */

require_once("include/class/Espace.class.php");
require_once("include/class/Tarif.class.php");
require_once("include/class/Forfait.class.php");
// affichage  -----------
//chargement du tarif
/* $tarif_r = isset($_POST['ptarif'])  ? $_POST['ptarif']  : ''; //bouton deroulant
  $ptarif  = isset($_GET['catTarif']) ? $_GET['catTarif'] : ''; //bouton modification

  if(isset($ptarif)) {
  $tarif_r = $ptarif;
  }

  if (isset($tarif_r)) {
  $tarif = $tarif_r;
  } else {
  $tarif   = 1; //tarif par defaut
  $tarif_r = 1;
  $ptarif  = 1;
  } */

$tarif = isset($_POST['ptarif']) ? $_POST['ptarif'] : (isset($_GET['catTarif']) ? $_GET['catTarif'] : 1);
$espaces = Espace::getEspaces();

// tableau unites pour les ateliers
$dureetype = array(
    0 => 'Illimit&eacute;e',
    1 => 'An(s)',
    2 => 'Mois',
    3 => 'Jour(s)'
);


// Tableau des unité de durée forfait
$tab_unite_duree_forfait = array(
    1 => "Jour",
    2 => "Semaine",
    3 => "Mois",
    4 => "Illimit&eacute;e"
);

// Tableau des unité d'affectation
$tab_unite_temps_affectation = array(
    1 => "Minutes",
    2 => "Heures"
);

// Tableau des fréquence d'affectation
$tab_frequence_temps_affectation = array(
    1 => "par Jour",
    2 => "par Semaine",
    3 => "par Mois"
);

$categorieTarif = array(
    1 => "impression",
    2 => "adhesion",
    3 => "consommables",
    4 => "Divers"
);

include("include/boites/menu-parametres.php");
?>

<div class="row">
    <!-- Accordeon sur les nouveaux tarifs  --> 
    <div class="col-md-4">
        <div class="box box-solid box-warning">
            <div class="box-header with-border">
                <i class="glyphicon glyphicon-plus"></i><h3 class="box-title">Nouveau Tarif</h3>
                <div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> </div>
            </div>
            <div class="box-body">

                <!-- id 1 : les impressions -->
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#1impression"> Impressions / Adh&eacute;sion / Divers</a></h4>
                        </div>
                        <div id="1impression" class="panel-collapse collapse">
                            <form method="post" action="index.php?a=47&actarif=1&typetarif=1" class="form">
                                <div class="box-body">
                                    <div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="newnomtarif"></div>
                                    <div class="form-group"><input type="text" class="form-control" placeholder="prix*  0.15" name="newprixtarif"></div>
                                    <div class="form-group"><textarea class="form-control" placeholder="description" name="newdescriptiontarif"></textarea></div>
                                    <div class="form-group">
                                        <label >Cat&eacute;gorie *:</label>
                                        <select name="catTarif" class="form-control">
                                            <?php
                                            foreach ($categorieTarif as $key => $value) {
                                                if ($tarif == $key) {
                                                    echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label >Espace *:</label>
                                        <select name="espace[]" multiple class="form-control">
                                            <?php
                                            foreach ($espaces as $espace) {
                                                if ($_SESSION['idepn'] == $espace->getId()) {
                                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div><!-- .box-body -->
                                <div class="box-footer">
                                    <a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a>
                                </div>
                            </form>
                        </div><!-- .1impression -->
                    </div><!-- panel -->
                </div><!-- accordeon -->
                <!-- FIN IMPRESSIONS -->

                <!-- id 2 : les Ateliers -->
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#2atelier"> Ateliers</a></h4>
                        </div>
                        <div id="2atelier" class="panel-collapse collapse">
                            <form method="post" action="index.php?a=47&actarif=1&typetarif=2" class="form">
                                <div class="box-body">
                                    <div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="newnomtarifa"></div>
                                    <div class="form-group"><textarea rows="2" class="form-control" placeholder="description" name="newdescriptiontarifa"></textarea></div>
                                    <div class="form-group"><input type="text" class="form-control" placeholder="prix*  0.15" name="newprixtarifa"></div>
                                    <div class="form-group"><label>Nombre d'ateliers*</label><input type="number" class="form-control" value="0" min="0" name="newnumbertarifa"></div>
                                    <div class="form-group">
                                        <label>Limite de validit&eacute;*</label>
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <input type="number" class="form-control"  value="0" min="0" name="dureetarifa">
                                            </div>
                                            <div class="col-xs-5">
                                                <select type="text" class="form-control"  name="typedureetarifa">
                                                    <?php
                                                    foreach ($dureetype as $key => $value) {
                                                        if (isset($duree) && ($duree == $key)) {
                                                            echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                        } else {
                                                            echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                        }
                                                    }
                                                    ?>  
                                                </select>
                                            </div>
                                        </div><!-- .row -->
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <label >Espace *:</label>
                                        <select name="espace[]" multiple class="form-control">
                                            <?php
                                            foreach ($espaces as $espace) {
                                                if ($_SESSION['idepn'] == $espace->getId()) {
                                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div><!-- .box-body -->
                                <div class="box-footer"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div>
                            </form>
                        </div><!-- 2atelier -->
                    </div><!-- .panel -->
                </div><!-- .box-group -->



                <!-- id 3 : les consultations -->
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#3consult"> Consultation</a></h4>
                        </div>
                        <div id="3consult" class="panel-collapse collapse">
                            <form method="post" action="index.php?a=47&actarif=1&typetarif=3" class="form">
                                <div class="box-body">
                                    <div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="nom_forfait"></div>
                                    <div class="form-group"><input type="text" class="form-control" placeholder="prix*  0=gratuit" name="prix_forfait"></div>
                                    <div class="form-group"><textarea class="form-control" placeholder="Description" name="comment_forfait"></textarea></div>
                                    <div class="form-group">
                                        <label>Limite de validit&eacute; du forfait *</label>
                                        <div class="row">
                                            <div class="col-xs-5"><input type="number" value="0" min="0" class="form-control"  name="nombre_duree_forfait"></div>
                                            <div class="col-xs-5">
                                                <select  class="form-control"  name="unite_duree_forfait">
                                                    <?php
                                                    foreach ($tab_unite_duree_forfait as $key => $value) {
//        if ($unite == $key) {
//            echo "<option value=\"".$key."\" selected>".$value."</option>";
//        }
//        else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
//        }
                                                    }
                                                    ?>  
                                                </select>
                                            </div>
                                        </div><!-- .row -->
                                        <br>
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <label>Dur&eacute;e de la consultation *</label>
                                        <div class="row">
                                            <div class="col-xs-3"><input class="form-control" type="number" value="0" min="0" name="nombre_temps_affectation"></div>
                                            <div class="col-xs-4">
                                                <select type="text" class="form-control"  name="unite_temps_affectation">
                                                    <?php
                                                    foreach ($tab_unite_temps_affectation as $key => $value) {
//        if ($unite == $key) {
//            echo "<option value=\"".$key."\" selected>".$value."</option>";
//        }
//        else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
//        }
                                                    }
                                                    ?>  

                                                </select>
                                            </div>
                                            <div class="col-xs-5">
                                                <select  class="form-control"  name="frequence_temps_affectation">
                                                    <?php
                                                    foreach ($tab_frequence_temps_affectation as $key => $value) {
//        if ($freq == $key) {
//            echo "<option value=\"".$key."\" selected>".$value."</option>";
//        }
//        else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
//        }
                                                    }
                                                    ?>  
                                                </select>
                                            </div>
                                        </div><!-- .row -->
                                    </div><!-- .form-group -->
                                    <!--
                                    <div class="form-group"><label> ou Affect. occasionelle (en min)&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour la consultation de type one shot ! La validit&eacute; est expir&eacute;e après d&eacute;pense."><i class="fa fa-info"></i></small>
                                    </label><input type="number" value="" min="0" class="form-control" name="temps_affectation_occasionnel"></div>  -->

                                    <div class="form-group">
                                        <label >Espace *:</label>
                                        <select name="espace[]" multiple class="form-control">
                                            <?php
                                            foreach ($espaces as $espace) {
                                                if ($_SESSION['idepn'] == $espace->getId()) {
                                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div> 
                                </div><!-- .box-body -->
                                <div class="box-footer"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div>
                            </form>
                        </div><!-- 3consult -->
                    </div><!-- .panel -->
                </div><!-- .box-group -->
            </div><!-- .box-body -->
        </div><!-- .box -->
    </div><!-- .col-md-4 -->


    <!-- MODIFICATION DES TARIFS -->

    <div class='col-md-8'>
        <div class="box box-default">
            <div class="box-header with-border"><h3 class="box-title">Tous les tarifs par cat&eacute;gorie </h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <form action="index.php?a=47" method="post" role="form" >
                            <div class="input-group input-group-sm">
                                <select name="ptarif"  class="form-control pull-right" style="width: 200px;">
                                    <?php
                                    $categorieTarif = array(
                                        1 => "Impression",
                                        2 => "Adh&eacute;sion",
                                        5 => "Atelier",
                                        6 => "Consultation",
                                        3 => "Consommables",
                                        4 => "Divers"
                                    );

                                    foreach ($categorieTarif as $key => $value) {
                                        if ($tarif == $key) {
                                            echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                        } else {
                                            echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                                <span class="input-group-btn"><button type="submit" name="submit" value="Valider" class="btn btn-default"><i class="fa fa-repeat"></i></button></span>
                            </div>
                        </form>

<!--<button class="btn bg-blue btn-sm"  data-toggle="tooltip" title="Si votre EPN fait payer les ateliers, déclarez le tarif correspondant, le décompte sera automatiquement effectué en fonction des achats de vos adhérents."><i class="fa fa-info"></i></button>-->

                    </div><!-- .has-feedback -->
                </div><!-- .box-tools -->
            </div><!-- .box-header -->
        </div><!-- .box -->
    </div><!-- .col-md-8 -->


    <?php
///*** gestion des tarifs AJOUT 2014
    if ($tarif < 6) {
        $tarifsByCat = Tarif::getTarifsByCategorie($tarif);

        $nbt = count($tarifsByCat);


        if ($nbt == 0) {
            ?>
            <div class="col-md-6">
                <div class="alert alert-info alert-dismissable">
                    <i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Pas de tarifs encore !</b>
                </div>
            </div>
            <?php
        } else {
            $categorieTarif = array(
                1 => "Impression",
                2 => "Adhesion",
                3 => "Consommables",
                4 => "Divers",
                5 => "Forfait Atelier"
            );

            /* for ($i = 0 ; $i < $nbt ; $i++) {
              $row       = mysqli_fetch_array($tarifbycat);
              $catTarif  = $row['categorie_tarif'];
              $id_tarif  = $row['id_tarif'];
              $idEspaces = explode('-',$row['epn_tarif']);
              //  debug($espace);
             */
            foreach ($tarifsByCat as $tarifByCat) {
                $catTarif = $tarifByCat->getCategorie();
                $id_tarif = $tarifByCat->getId();
                $idEspaces = $tarifByCat->getIdsEspacesAsArray(); ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="box box-warning">
                        <form  method="post" class="form" action="index.php?a=47&actarif=2&idtarif=<?php echo $tarifByCat->getId(); ?>" >
                            <div class="box-header  with-border"><h3 class="box-title"><?php echo htmlentities($tarifByCat->getNom()); ?></h3></div>
                            <div class="box-body">


                                <?php
                                if ($catTarif == 5) {
                                    if ($tarifByCat->getDuree() == "0") {
                                        $dureenumarray = explode('-', "0-0");
                                    } else {
                                        $dureenumarray = explode('-', $tarifByCat->getDuree());
                                    }
                                    $duree2 = $dureenumarray[1]; ?>

                                    <div class="form-group" ><label>Libell&eacute;</label>
                                        <input type="hidden" name="catTarif" value="<?php echo $catTarif; ?>"><input type="hidden" name="ptarif" value="<?php echo $catTarif; ?> ">
                                        <input type="text" class="form-control" name="nomtarif" value="<?php echo htmlentities($tarifByCat->getNom()); ?> ">
                                    </div>

                                    <div class="form-group" ><label>Prix</label><input type="text"  class="form-control" name="prixtarif" value="<?php echo htmlentities($tarifByCat->getDonnee()); ?>"></div>
                                    <div class="form-group" ><label>Nbre d'ateliers</label><input type="text" class="form-control" name="numberatelier" value="<?php echo htmlentities($tarifByCat->getNbAtelierForfait()) ?>"></div>

                                    <div class="form-group" ><label>Commentaire</label><textarea rows="2" class="form-control" name="descriptiontarif" ><?php echo htmlentities($tarifByCat->getCommentaire()); ?></textarea></div>
                                    <div class="form-group" >
                                        <label>Limite de validit&eacute;</label><br>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <div class="form-group" >
                                                    <input type="number"  name="dureetarif" value="<?php echo $dureenumarray[0] ?>" style="width:50px;">
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <select type="text"   name="typedureetarif">
                                                    <?php
                                                    foreach ($dureetype as $key => $value) {
                                                        if ($key == $duree2) {
                                                            echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                        } else {
                                                            echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div><!-- .row -->
                                    </div>  
                                    <div class="form-group" >
                                        <label>Espaces</label>
                                        <select name="espace[]" multiple class="form-control">
                                            <?php
                                            foreach ($espaces as $espace) {
                                                if (in_array($espace->getId(), $idEspaces)) {
                                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                                }
                                            } ?>
                                        </select>
                                    </div>


                                    <?php
                                } else {
                                    ?>

                                    <div class="form-group"><label>Nom</label><input type="hidden" name="catTarif" value="<?php echo $catTarif; ?>"><input type="text" class="form-control" name="nomtarif" value="<?php echo htmlentities($tarifByCat->getNom()); ?> "></div>
                                    <div class="form-group"><label>Prix</label><input type="text"  class="form-control" name="prixtarif" value="<?php echo htmlentities($tarifByCat->getDonnee()); ?>"></div>
                                    <div class="form-group"><label>Description</label><textarea rows="2" class="form-control" name="descriptiontarif" ><?php echo htmlentities($tarifByCat->getCommentaire()); ?></textarea></div>
                                    <div class="form-group">
                                        <label>Espaces</label>
                                        <select name="espace[]" multiple class="form-control">
                                            <?php
                                            foreach ($espaces as $espace) {
                                                if (in_array($espace->getId(), $idEspaces)) {
                                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                                } else {
                                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                                }
                                            } ?>
                                        </select>
                                    </div>

                                    <?php
                                } ?>

                            </div>  

                            <div class="box-footer">
                                <button class="btn btn-success btn-sm"  type="submit" value="modifier"><i class="fa fa-refresh"></i>&nbsp;Modifier</button>
                                &nbsp;<a href="index.php?a=47&actarif=3&idtarif=<?php echo $tarifByCat->getId(); ?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>&nbsp;Supprimer</button></a>
                            </div>

                        </form>
                    </div><!-- .box -->
                </div><!-- .col-md-3 -->

                <?php
            } // end FOR cat tarif 1 to 5
        }
    } else {
        // forfaits ! à voir dans la table tab_forfait...
        // Affichage du tarif consultation (6)
        $forfaits = Forfait::getForfaits();
        $nbc = count($forfaits);

        if ($nbc == 0) {
            ?>
            <br>
            <div class="col-md-6">
                <div class="alert alert-info alert-dismissable">
                    <i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Pas de tarifs encore !</b>
                </div>
            </div>
            <?php
        } else {
            /// affichage de l'array des consultations

            foreach ($forfaits as $forfait) {
                $nombre_temps_affectation = $forfait->getDureeConsultation();
                $unite_temps_affectation = $forfait->getUniteConsultation();
                $frequence_temps_affectation = $forfait->getFrequenceConsultation();
                if ($forfait->getTempsForfaitIllimite() == '1') {
                    $unite_duree_forfait = 4;
                } else {
                    $unite_duree_forfait = $forfait->getUniteValidite();
                }

                $epnC = $forfait->getIdsEspacesAsArray();

                //for ($y = 0 ; $y < $nbc ; $y++) {
                //$rowconsult = mysqli_fetch_array($consultation);
                //debug($rowconsult);
                //$nombre_temps_affectation = $rowconsult["nombre_temps_affectation"];
                //$unite_temps_affectation = $rowconsult["unite_temps_affectation"];
                //$frequence_temps_affectation = $rowconsult["frequence_temps_affectation"];
                //if ($rowconsult["temps_forfait_illimite"]=='1') {
                //    $unite_duree_forfait = 4;
                //}
                //else {
                //    $unite_duree_forfait = $rowconsult["unite_duree_forfait"];
                //}
                //$epnC = getAllRelForfaitEspace($rowconsult['id_forfait']);
                //debug($epnC); ?>
                <div class="col-md-4">
                    <form  method="post" class="form" action="index.php?a=47&actarif=2&idtarif=<?php echo $forfait->getId(); ?>" >
                        <div class="box box-warning">
                            <div class="box-header"><h3 class="box-title"><?php echo htmlentities($forfait->getNom()); ?></h3></div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Libell&eacute;</label>
                                    <input type="hidden" name="catTarif" value="6">
                                    <input type="hidden" name="id_forfait" value="<?php echo $forfait->getId(); ?>">
                                    <input type="hidden" name="ptarif" value="6">
                                    <input type="text" class="form-control" name="nom_forfait" value="<?php echo htmlentities($forfait->getNom()); ?> ">
                                </div>
                                <div class="form-group"><label>Prix (&euro;)</label><input type="text"  class="form-control" name="prix_forfait" value="<?php echo htmlentities($forfait->getPrix()); ?>"></div>
                                <div class="form-group"><label>Description</label><textarea rows="2" class="form-control" name="commentaire_forfait"><?php echo htmlentities($forfait->getCommentaire()); ?></textarea></div>

                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <label>Validit&eacute;</label>
                                            <input type="number" min="0" class="form-control"  value="<?php echo htmlentities($forfait->getDureeValidite()); ?>" name="nombre_duree_forfait">
                                        </div>
                                        <div class="col-xs-4">
                                            <label>&nbsp;</label>
                                            <select  class="form-control"  name="unite_duree_forfait">
                                                <?php
                                                foreach ($tab_unite_duree_forfait as $key => $value) {
                                                    if ($unite_duree_forfait == $key) {
                                                        echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                    } else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                    }
                                                } ?>  
                                            </select>
                                        </div>
                                        <!--<div class="col-xs-5"><label>Affect. occasionelle (en min)&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour la consultation de type one shot ! La validit&eacute; est expir&eacute;e après d&eacute;pense."><i class="fa fa-info"></i></small></label>
                                        <input type="number" min="0" class="form-control" placeholder="en min" name="temps_affectation_occasionnel" value="<?php echo htmlentities($forfait->getTempsAffectationOccasionnel()); ?>">-->
                                    </div>
                                </div><!-- .input-group -->

                                <div class="input-group">
                                    <label>Dur&eacute;e de la consultation</label>
                                    <div class="row">
                                        <div class="col-xs-4"><input class="form-control" type="number" min="0" name="nombre_temps_affectation" value="<?php echo htmlentities($forfait->getDureeConsultation()); ?>"></div>
                                        <div class="col-xs-4">
                                            <select type="text" class="form-control"  name="unite_temps_affectation">
                                                <?php
                                                foreach ($tab_unite_temps_affectation as $key => $value) {
                                                    if ($unite_temps_affectation == $key) {
                                                        echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                    } else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                    }
                                                } ?>  
                                            </select>
                                        </div>

                                        <div class="col-xs-4">
                                            <select  class="form-control"  name="frequence_temps_affectation">
                                                <?php
                                                foreach ($tab_frequence_temps_affectation as $key => $value) {
                                                    if ($frequence_temps_affectation == $key) {
                                                        echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                                    } else {
                                                        echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                                    }
                                                } ?>  
                                            </select>
                                        </div>
                                    </div><!-- .row -->
                                </div><!-- .input-group -->


                                <div class="form-group">
                                    <label>Espaces</label>
                                    <select name="espace[]" multiple class="form-control">
                                        <?php
                                        foreach ($espaces as $espace) {
                                            if (in_array($espace->getId(), $epnC)) {
                                                echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                            } else {
                                                echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div> 
                            </div><!-- .box-body -->
                            <div class="box-footer">
                                <button class="btn btn-success btn-sm"  type="submit" value="modifier"><i class="fa fa-edit"></i>&nbsp; Modifier</button>
                                &nbsp;<a href="index.php?a=47&actarif=3&typetarif=3&idtarif=<?php echo $forfait->getId(); ?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>&nbsp;Supprimer</button></a>
                            </div>
                        </div><!-- .box -->
                    </form>
                </div><!-- .col-md-4 -->

                <?php
            }
        }
    }
    ?>
</div><!-- .row -->

