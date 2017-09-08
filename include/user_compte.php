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
//require_once("include/class/Utilisateur.class.php");
//require_once("include/class/Tarif.class.php");
//require_once("include/class/Forfait.class.php");

// Page de gestion du compte d'un utilisateur
$utilisateur = Utilisateur::getUtilisateurById($_SESSION["iduser"]);
//recuperation des tarifs categorieTarif(2)=adhesion
$tarif = Tarif::getTarifById($utilisateur->getIdTarifAdhesion());

// $lasteresa    = getLastResaUser($utilisateur->getId());
$lasteresa = $utilisateur->getLastResa();



if ($lasteresa == null) {
    $lasteresa = "NC";
}

//TARIF CONSULTATION
$forfait = $utilisateur->getForfaitConsultation();
if ($forfait != null) {
    $uniteForfait = $forfait->getUniteConsultation();
    $tarifreferencetemps = $forfait->getDureeConsultation() * $uniteForfait;

    $restant = $utilisateur->getTempsRestant();
    $rapport = round(($restant / $tarifreferencetemps) * 100);
}

$imgprofile = "img/avatar/" . $utilisateur->getAvatar();

//Affichage d'une erreur si erreur il y a
if (isset($mess)) {
    echo $mess;
}
?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgprofile; ?>" alt="User profile picture">
                <h3 class="profile-username text-center"><?php echo htmlentities($utilisateur->getNom()); ?>&nbsp;<?php echo htmlentities($utilisateur->getPrenom()); ?></h3>
                <p class="text-muted text-center">Inscrit(e) depuis le <?php echo dateFr($utilisateur->getDateInscription()) ?></p>

                <hr>
                <strong><i class="fa fa-map-marker margin-r-5"></i> Adresse</strong>
                <p class="text-muted"><?php echo htmlentities($utilisateur->getAdresse()); ?> <br><?php echo htmlentities(Ville::getVilleById($utilisateur->getIdVille())->getnom()); ?></p>
                <hr>
                <strong><i class="fa fa-pencil margin-r-5"></i> Donn&eacute;es personnelles</strong>
                <p class="text-muted">n&eacute;(e) le <?php echo $utilisateur->getJourNaissance() . " " . getMonth($utilisateur->getMoisNaissance()) . " " . $utilisateur->getAnneeNaissance(); ?></p>
                <p class="text-muted"><i class="fa fa-phone margin-r-5"></i> <?php echo htmlentities($utilisateur->getTelephone()); ?></p>
                <p class="text-muted"><i class="fa fa-envelope margin-r-5"></i><?php echo htmlentities($utilisateur->getMail()); ?></p>
            </div><!-- .box-body -->
        </div><!-- .box -->
    </div><!-- .col-md-3 -->

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">Activit&eacute;</a></li>
                <li><a href="#settings" data-toggle="tab">Param&egrave;tres</a></li>
            </ul>

            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <dl class="dl-horizontal">
                        <dt>Derni&egrave;re consultation </dt><dd>le <?php echo $lasteresa == "NC" ? "NC" : getDayfr($lasteresa->getDateResa()); ?></dd>
                        <dt>Tarif / Temps restant<dt><dd><span class="badge bg-blue"><?php echo htmlentities($forfait->getNom()) ?></span>&nbsp;&nbsp;<?php echo getTime($restant); ?></dd>
                        <dt>Adh&eacute;sion </dt><dd>A renouveller le <?php echo getDayfr($utilisateur->getDateRenouvellement()); ?></dd>
                        <dt>Au tarif de </dt><dd><?php echo $tarif->getNom() ?> (<?php echo $tarif->getDonnee() ?> €)</dd>
                        <dt>Newsletter </dt>
                        <dd>
                            <?php
                            if ($utilisateur->getNewsletter() == 1) {
                                echo "Je suis abonn&eacute;";
                            } else {
                                echo "Je ne suis pas abonn&eacute; ";
                            }
                            ?>                      </dd>
                    </dl>

                </div><!-- .tab-pane -->

                <div class="tab-pane" id="settings">

                    <form method="post" action="index.php?m=2" class="form-horizontal">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Num&eacute;ro de carte </label>
                            <div class="col-sm-10"><input type="email" class="form-control" id="inputName" value="<?php echo htmlentities($utilisateur->getLogin()); ?>" disabled /></div>
                        </div>

                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Mot de passe </label>
                            <div class="col-sm-10"><input type="email" class="form-control" id="inputName" value="********" disabled /></div>
                        </div>

                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Modifiez votre mot de passe </label>
                            <div class="col-sm-10"><input type="password" class="form-control"  name="pass1"></div>
                        </div>

                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Confirmation </label>
                            <div class="col-sm-10"><input type="password" class="form-control"  name="pass2"></div>
                        </div>

                        <div class="form-group">
                            <label  class="col-sm-2 control-label">Abonnement newsletter </label>
                            <div class="col-sm-10">
                                <div class="radio icheck">
                                    <?php
                                    if ($utilisateur->getNewsletter() == 1) {
                                        echo '<input type="radio" name="newsletter" value="0"  /> non
            <input type="radio" name="newsletter" value="1"  checked /> oui ';
                                    } else {
                                        echo '<input type="radio" name="newsletter" value="0"  checked /> non
            <input type="radio" name="newsletter" value="1"  /> oui ';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="submit" value="modifier" name="submit" class="btn btn-primary">
                        </div>
                    </form>

                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- /.nav-tabs-custom -->
    </div><!-- /.col -->
</div><!-- .row -->