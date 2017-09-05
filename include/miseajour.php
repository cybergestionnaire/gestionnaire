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
  2012 florence DAUVERGNE

  Formulaire de mise à jour de version, détection et modifications dans la base de donnees
 */
require_once("include/class/Config.class.php");

//declencher les MAJ, verifier la version dans la tab_config
// $versionActuelle = getMajConfigVersion($_SESSION["idepn"]);
$config = Config::getConfig($_SESSION["idepn"]);
$versionActuelle = $config->getName($_SESSION["idepn"]);

$versionew = "1.9";
?>
<div class="row">

    <?php
    $error = '';

    $testmaj = isset($_GET["testmaj"]) ? $_GET["testmaj"] : '';
//Sauvegarde de la base actuelle en fichier zippe
//$bdd=backupbdd();
//  debug($testbdd);
    if ($testmaj == '') {
        $testbdd = getLastBackup();
        if ($testbdd == false) {
            ?>
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header"> 
                        <i class="fa fa-warning"></i><h3 class="box-title">Mise à jour de version depuis la version <?php echo $versionActuelle; ?> vers la <?php echo $versionew; ?></h3>
                    </div>

                    <div class="box-body">
                        <p>Cela fait un mois que la base de donnée n'a pas été sauvegardée, cliquez sur le bouton pour la lancer avant de faire toute mise à jour !</p>
                    </div>

                    <div class="box-footer">
                        <a href="index.php?a=62&maj=1"><input type="submit" name="sauvegarde" value="Lancer la sauvegarde" class="btn btn-danger"></a>
                    </div>
                </div>
            </div>

            <?php
            //modifications de la base
        } else {
            ?>
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header"> 
                        <i class="fa fa-warning"></i><h3 class="box-title">Mise à jour de version depuis la version <?php echo $versionActuelle; ?> vers la <?php echo $versionew; ?></h3>
                    </div>

                    <div class="box-body">
                        <?php
                        if (floatval($versionActuelle) >= 1.0) {
                            ?>
                            <p class="text-blue"><b>Modifications de la base de données</b></p>
                            <?php
                            $error = "";
                            //1. ajout des tables
                            if ($versionActuelle == "1.0") {
                                $row1 = AddTab_courrier();
                                if ($row1 != "OK") {
                                    $error .= "Echec impossible d'ajouter la table des courriers" . " \r\n";
                                    echo "<p>* Ajout de la table des courriers : Echec</p>";
                                } else {
                                    echo '<p>Ajout de la table des courriers</p>';
                                }

                                $row2 = add_courriertest();
                                if ($row2 != "OK") {
                                    $error .= "Echec impossible d'ajouter un exemple à la table des courriers" . " \r\n";
                                    echo "<p>* Ajout d'exemples à table des courriers : Echec</p>";
                                } else {
                                    echo '<p>Ajout exemples à la table des courriers</p>';
                                }

                                $row3 = alterEspace();
                                if ($row3 != "OK") {
                                    $error .= "Echec impossible de modifier la tab_espace" . " \r\n";
                                    echo "<p>* modifier la tab_espace: Echec</p>";
                                } else {
                                    echo '<p>modification de la tab_espace: ajout du mail</p>';
                                }


                                if ($error == "") {
                                    if ($config->setName("1.1")) {
                                        $versionActuelle = $config->getName();
                                        $finale = InsertLogMAJ('maj', $versionew, date('Y-m-d H:i'), "Mise à jour de version 1.1 effectuée");
                                        echo '<p class="text-blue"><b>Modification du numero de version : ' . $versionActuelle . '</b></p>';
                                    }
                                } else {
                                    echo "erreur lors de la mise à jour depuis la version 1.0 vers la version 1.1 : " . $error;
                                }
                            }



                            if ($versionActuelle == "1.1") {
                                $row1 = Tab_ins1();
                                if ($row1 != "OK") {
                                    $error .= "Echec impossible Modification de la table des preinscriptions (1)" . " \r\n";
                                    echo "<p>* Modification de la table des preinscriptions (1) : echec</p>";
                                } else {
                                    echo '<p>Modification de la table des preinscriptions (1)</p>';
                                }

                                $row2 = Tab_ins2();
                                if ($row2 != "OK") {
                                    $error .= "Echec impossible Modification de la table des preinscriptions (2)" . " \r\n";
                                    echo "<p>* Modification de la table des preinscriptions (2) : echec</p>";
                                } else {
                                    echo '<p>Modification de la table des preinscriptions (2)</p>';
                                }

                                $row3 = alterMessageMAJ();
                                if ($row3 != "OK") {
                                    $error .= "Echec impossible de Modication de la table des message" . " \r\n";
                                    echo "<p>* Modication de la table des messages : echec</p>";
                                } else {
                                    echo '<p>Modication de la table des messages</p>';
                                }

                                $row4 = createtabinscriptMAJ();
                                if ($row4 != "OK") {
                                    $error .= "Echec Creation de la table de validation des preinscriptions" . " \r\n";
                                    echo "<p>* Creation de la table de validation des preinscriptions: echec</p>";
                                } else {
                                    echo '<p>Creation de la table de validation des preinscriptions</p>';
                                }

                                $row5 = insertCapt();
                                if ($row5 != "OK") {
                                    $error .= "Echec impossible Insertion de donnees dans la table" . " \r\n";
                                    echo "<p>* Insertion de donnees dans la table: Echec</p>";
                                } else {
                                    echo '<p>Insertion de donnees dans la table</p>';
                                }
                                if ($error == "") {
                                    if ($config->setName("1.2")) {
                                        $versionActuelle = $config->getName();
                                        $finale = InsertLogMAJ('maj', $versionew, date('Y-m-d H:i'), "Mise à jour de version 1.2 effectuée");
                                        echo '<p class="text-blue"><b>Modification du numero de version : ' . $versionActuelle . '</b></p>';
                                    }
                                } else {
                                    echo "erreur lors de la mise à jour depuis la version 1.1 vers la version 1.2 : " . $error;
                                }
                            }

                            if ($versionActuelle == "1.2" or $versionActuelle == "1.3") {
                                include("upgrade-database.php");

                                if ($config->setName("1.9")) {
                                    $versionActuelle = $config->getName();
                                    $finale = InsertLogMAJ('maj', $versionew, date('Y-m-d H:i'), "Mise à jour de version 1.9 effectuée");
                                    echo '<p class="text-blue"><b>Modification du numero de version : ' . $versionActuelle . '</b></p>';
                                }
                            }
                            if ($versionActuelle == "1.9") {
                                $testmaj = "ok";
                            }
                        } else {
                            ?>
                            <p>Erreur : la mise &agrave; jour n'est possible que depuis la version 1.0 de Cybergestionnaire ou sup&eacute;rieure.
                                Vous devez faire d'abord la mise à jour vers la version 1.0 avant de tenter la mise à jour vers cette version.</p>
                            <?php
                        } ?>


                    </div>
                </div>
            </div><!-- .col-md-6 -->
            <?php
        }
    }


    if ($testmaj == 'ok') {

        //deuxieme etape attribution des forfaits pour les utilisateurs, optionnel ou pas....
        ?>
        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header"> 
                    <i class="fa fa-warning"></i><h3 class="box-title">Mise à jour termin&eacute;e !</h3>
                </div>
                <div class="box-body">
                    <p>Dans cette mise à jour :</p>
                    <ul>
                        <li>Refonte de la console</li>
                        <li>Modifications de l'ordre des menus ateliers et sessions</li>
                        <li>Travail de ré-écriture en objet : sont faits
                            <ul>
                                <li>toutes les pages "configuration"</li>
                                <li>les pages adhérents</li>
                                <li>les pages de réservation</li>
                                <li>la gestion des ateliers et des sessions</li>
                                <li>La gestion des transactions (à vérifier quand même...</li>)
                            </ul>
                        </li>
                        <li>reste donc à faire :
                            <ul>
                                <li>Les statistiques</li>
                                <li>les pages "gestion de l'espaces (courriers/breves/interventions/Liens)</li>
                            </ul>
                        </li>
                        <li>A été fait également sous le capot :
                            <ul>
                                <li>Changement du codage des caractères : tout passe en UTF-8 (non-terminé)<br />Cela devrait éviter l'apparition d'artefacts au lieu des caractères accentués.</li>
                                <li>ré-écriture en mode objet pour séparer le modèle de la vue (non terminé)</li>
                                <li>suppression des "PHP Notice" (non terminé)</li>
                                <li>multiples corrections de bugs, et multiples inclusions de nouveaux bugs...</li>
                            </ul>
                        </li>
                    </ul>

                    <?php
                    //****ecriture du fichier de log
                    if ($finale == false) {
                        $error .= "Echec impossible d'ecrire dans la table des logs" . " \r\n";
                    } else {
                        //inscrire l'ensemble des erreurs dans le fichier log de la version
                        if ($error != "") {
                            gFilelog(addslashes($error), "log_majv1.9.txt");
                        }


                        //vider les variables
                        $error = ''; ?>
                    </div>
                    <div class="box-footer">
                        <a href="index.php"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour à l'accueil</button></a>
                    </div>
                </div><!-- .box -->
            </div><!-- .col-md-6 -->
            <?php
                    } //fin finale
    } //fin second div
    ?>


</div><!-- .row -->






