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

  2006 Namont Nicolas (Cybermin)


 */
//require_once("include/class/Espace.class.php");
//require_once("include/class/ConfigLogiciel.class.php");

// Configuration des options du logiciel
$mess = isset($_GET["mess"]) ? $_GET["mess"] : '';
if ($mess == "ok") {
    echo getError(14);
} elseif ($mess != "") {
    echo getError($_GET["mess"]);
}

//si changment d'epn
$idEspace = isset($_GET['epnr']) ? $_GET['epnr'] : $_SESSION["idepn"];
$espace = Espace::getEspaceById($idEspace);

if ($espace != null) {
    if ($espace->hasConfiglogiciel()) {
        $bouton = "valider les modifications";
    } else {
        $bouton = "cr&eacute;er la configuration";
    }

    $posturl = "index.php?a=25&act=0&idepn=" . $espace->getId();
    $configLogiciel = $espace->getConfigLogiciel();
    $config = $espace->getConfig();
}

// Choix de l'epn   -------------------------------------
$espaces = Espace::getEspaces();

include("include/boites/menu-parametres.php");
?>


<div class="row">
    <!-- Colonne de gauche -->
    <div class="col-md-4">
        <div class="box">
            <div class="box-header"><h3 class="box-title">Installer sur les postes clients</h3></div>
            <div class="box-body">
                <?php
                if (file_exists("./epnconnect/EPN-Connectv1.0.zip")) {
                    ?>    
                    T&eacute;l&eacute;charger le logiciel EPN-Connect en cliquant sur le lien ci-dessous :<br />
                    <a href="./epnconnect/EPN-Connectv1.0.zip">T&eacute;l&eacute;charger EPN-Connect</a>
                    <?php
                } else {
                    ?>    
                    EPNConnect doit &ecirc;tre mis dans le r&eacute;pertoire /epnconnect/ sur le serveur avec le nom "EPN-Connectv1.0.zip".
                    <?php
                }
                ?>    

            </div>
        </div>

        <!-- NOM ESPACE --> 
        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Choisissez l'espace</h3></div>
            <form action="index.php?a=25" method="post" role="form">
                <div class="box-body">
                    <div class="form-group">
                        <select name="epn_r" class="form-control" >
                            <?php
                            foreach ($espaces as $espaceAffichage) {
                                if ($espaceAffichage->getId() == $espace->getId()) {
                                    echo "<option value=\"" . $espaceAffichage->getId() . "\" selected>" . htmlentities($espaceAffichage->getNom()) . "</option>";
                                } else {
                                    echo "<option value=\"" . $espaceAffichage->getId() . "\">" . htmlentities($espaceAffichage->getNom()) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="form" value="1">
                    <button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</button>
                </div>


            </form>
        </div><!-- .box -->



        <!-- activation mode console ou non -->
        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Activation du mode console</h3></div>
            <form method="post" action="index.php?a=25">
                <div class="box-body">
                    <p class="text-light-blue">L'activation du mode console permet l'autoaffection des postes avec l'epnconnect. Si vous ne pouvez pas utiliser cet outil (si votre serveur n'est pas local par exemple), vous n'aurez pas besoin de la console, elle disparait du menu.
                        Cet outil est activable &agrave; tout moment dans cette page, cela n'affectera pas les statistiques !</p>
                    <?php
                    if ($config->hasActiverConsole()) {
                        $sel1 = "checked=\"checked\"";
                        $sel2 = "";
                    } else {
                        $sel1 = "";
                        $sel2 = "checked=\"checked\"";
                    }
                    ?>
                    <input type="radio"  value="1" name="console" <?php echo $sel1; ?>> Oui &nbsp;
                    <input type="radio"  value="0" name="console" <?php echo $sel2; ?>> Non 

                </div><!-- .box-body -->
                <div class="box-footer">
                    <input type="hidden" name="form" value="4">
                    <input type="hidden" name="epn_r" value="<?php echo $espace->getId(); ?>">
                    <button type="submit" value="Valider" name="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div><!-- .box -->
    </div><!-- .col-md-4 -->



    <!-- Colonne de droite -->
    <div class="col-md-8">
        <div class="box">
            <div class="box-header"><h3 class="box-title">Options du logiciel EPN-Connect </h3></div>
            <form action="<?php echo $posturl; ?>" method="post" role="form">
                <div class="box-body">

                    <div class="form-group"><label> Validez les options ci-dessous pour activer les fonctionnalit&eacute;s d'EPN-Connect</label></div>
                    <input type="hidden" name="idconfig" value="<?php echo $configLogiciel->getId(); ?>">
                    <input type="hidden" name="epn" value="<?php echo $espace->getId(); ?>">
                    <div class="form-group">
                        <input type="checkbox" name="insclog" value="1" <?php echo $configLogiciel->hasPageInscription() ? "checked=\"checked\" " : ''; ?>>Affichage de la page d'inscription
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="renslog" value="1" <?php echo $configLogiciel->hasPageRenseignement() ? "checked=\"checked\" " : ''; ?>>Affichage de la page de renseignements
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="bloclog" value="1" <?php echo $configLogiciel->hasBlocageTouche() ? "checked=\"checked\" " : ''; ?>>Arret du logiciel par combinaison de touches
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="tempslog" value="1" <?php echo $configLogiciel->hasAffichageTemps() ? "checked=\"checked\" " : ''; ?>>Affichage temps restant
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="decouselog" value="1" <?php echo $configLogiciel->hasDeconnexionAuto() ? "checked=\"checked\" " : ''; ?>>D&eacute;connexion automatique
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="fermersessionlog" value="1" <?php echo $configLogiciel->hasFermetureSession() ? "checked=\"checked\" " : ''; ?>>Fermeture automatique de la session Windows
                    </div>
                    <div class="form-group">
                        <label>Activer les forfaits consultation dans l'epn ? </label>
                        <?php
                        if ($config->hasActivationForfait()) {
                            $sel1 = "checked=\"checked\"";
                            $sel2 = "";
                        } else {
                            $sel1 = "";
                            $sel2 = "checked=\"checked\"";
                        }
                        ?>
                        <div class="radio">
                            <label><input type="radio" name="forfait" value="1"   <?php echo $sel1; ?>>oui</label>
                            <label><input type="radio" name="forfait" value="0"  <?php echo $sel2; ?>>non</label>
                        </div>
                    </div> 

                    <h4>Autres Options</h4>
                    <?php
                    if ($config->hasInscriptionUsagersAuto()) {
                        $sel1 = "checked=\"checked\"";
                        $sel2 = "";
                    } else {
                        $sel1 = "";
                        $sel2 = "checked=\"checked\"";
                    }
                    ?>
                    <div class="form-group">
                        <label>Activer l'inscription automatique par les adh&eacute;rents ? </label>
                        <div class="radio">
                            <label><input type="radio" name="inscrip_auto" value="1"   <?php echo $sel1; ?>>oui</label>
                            <label><input type="radio" name="inscrip_auto" value="0"  <?php echo $sel2; ?>>non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Si non, &eacute;crivez le message :</label>
                        <textarea class="form-control" rows="3" name="message_inscrip"><?php echo htmlentities($config->getMessageInscription()); ?></textarea>
                    </div>

                </div><!-- .box-body -->
                <div class="box-footer">
                    <input type="hidden" name="form" value="2">
                    <input type="submit" name="submit" value="Valider" class="btn btn-success">
                </div>

            </form>

        </div><!-- .box -->
    </div><!-- .col-md-8 -->
</div><!-- .row -->


