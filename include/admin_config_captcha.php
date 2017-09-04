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

// Configuration de la preinscription en ligne par utilisateur

require_once("include/class/Captcha.class.php");

$captcha = Captcha::getCaptcha();

if (isset($_POST["submit"])) {


    $valid = isset($_POST["preinc"]) ? $_POST["preinc"] : '';
    $code = isset($_POST["code"]) ? $_POST["code"] : '';

    switch ($valid) {
        case "N":
            if ($captcha->modifier($valid, '')) {
                echo getError(14);
            } else {
                echo getError(0);
            }
            break;

        case "Y":
            if ($code == '') {
                echo getError(4);
            } else {
                if ($captcha->modifier($valid, $code)) {
                    echo getError(14);
                } else {
                    echo getError(0);
                }
            }
            break;
    }
}

//$rowprinscription = getPreinsmode();

if ($captcha == null) {
    $preinscmode = 'N';
    $capt_code = "";
} else {
    $preinscmode = $captcha->getActivation();
    $capt_code = $captcha->getCode();
}

include("include/boites/menu-parametres.php");
?>


<div class="row">
    <form role="form" method="POST"  action="">
        <div class="col-lg-6">
            <div class="box box-info">
                <div class="box-header"><h3 class="box-title">Activation des pr&eacute;inscriptions</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Activer la pr&eacute;inscription par les utilisateurs ?</label>
                        <?php
                        switch ($preinscmode) {
                            case 'N':
                                $sel1 = "checked=\"checked\"";
                                $sel2 = "";
                                break;

                            case 'Y':
                                $sel1 = "";
                                $sel2 = "checked=\"checked\"";
                                break;
                        }
                        ?>
                        <input type="radio"  value="N" name="preinc" <?php echo $sel1; ?>> Non &nbsp;
                        <input type="radio"  value="Y" name="preinc" <?php echo $sel2; ?>> Oui
                    </div>

                    <p class="text-blue">En cas d'activation, les utilisateurs peuvent se pr&eacute;inscrire en ligne, vous serez averti par une notification de la demande en cours. Après validation de l'inscription par un animateur ou un administrateur, il sera possible d'envoyer un mail avec les identifiants et mots de passe &agrave; l'utilisateur.</p>
                    <p class="text-blue">Pour v&eacute;rifier que l'inscription est bien faite par un &ecirc;tre humain vous devrez ins&eacute;rer le code Recaptcha fourni par Google lors de votre inscription sur leur service.</p>
                    <p><a href="doc/index.php"><strong>Plus sur la doc !</strong></a></p>

                </div><!-- .box-body -->
            </div><!-- .box -->
        </div><!-- .col-lg-6 -->





        <div class="col-lg-6">
            <!-- liste des categories existantantes pour modification-->
            <div class="box box-warning">
                <div class="box-header"><h3 class="box-title">Gestion du captcha</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Ins&eacute;rez le code *:</label>
                        <input name="code" value="<?php echo $capt_code; ?>" class="form-control">
                        <p class="help-block">Entrez le code contenu dans la balise <code>div</code> le sitekey, sans les "" <code> data-sitekey="lecodesuperlongetsansespaces" </code> <br>donc :<code>lecodesuperlongetsansespaces</code></p>
                    </div>

                </div>
                <div class="box-footer">
                    <input type="submit" name="submit" class="btn btn-success" value="valider">
                </div>
            </div><!-- .box -->
        </div><!-- .col-lg-6 -->
    </form>
</div><!-- .row -->



