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
 2012 Florence DAUVERGNE

*/
    
    // Formulaire de creation ou de modification d'un adherent

    require_once("include/class/Espace.class.php");
    require_once("include/class/Salle.class.php");
    
    $idAnim = isset($_GET["idanim"]) ? $_GET["idanim"] : '';

    $animateur = Utilisateur::getUtilisateurById($idAnim);
    if ($animateur == null) {
        getError(0);
    } else {
        if (!$animateur->hasParametresAnim()) {   // Parametre du formulaire pour la CREATION

            $post_url     = "index.php?a=50&b=1&idanim=" . $idAnim;
            $label_bouton = "Enregistrer les param&egrave;tres" ;
            $avatar       = "avatar.png";
            $idEspace     = "";
        } else {
            // Parametre du formulaire pour la MODIFICATION
            $post_url     = "index.php?a=50&b=2&idanim=" . $idAnim;
            $label_bouton = "Modifier les param&egrave;tres" ;
            // Information Utilisateur
            $avatar       = $animateur->getAvatar();
            $idEspace     = $animateur->getIdEspaceAnim();
            $sallesAnim   = explode(";", $animateur->getIdSallesAnim());

            //changer l'id de la session_epn pour l'admin connecte qui change ses params
            if ($_SESSION["iduser"] == $idAnim) {
                $_SESSION["idepn"] = $idEspace;
            }
        }
    }    

    // recupere les espaces
    // $espaces     = getAllepn();
    $espaces  = Espace::getEspaces();
    //recupere les salles
    $salles   = Salle::getSalles();

    //array avatars
    $filesavatararray = array();
    $filedir          = "./img/avatar/";
    $filesavatararray = array_diff(scandir($filedir), array('..', '.'));
    $nbavatar         = count($filesavatararray);
    $filesavatararray = array_values($filesavatararray); //réindexer le tableau après avoir enlever lignes vides


?>



<div class="row">
    <!-- left column -->
    <div class="col-md-4">
<?php 
    if (isset($_GET["mess"])) {
        if ($_GET["mess"] == "ok") {
            echo getError(14);
        }
    }
    if (isset($mess)) {
        echo $mess;
    }
?>
        <div class="small-box bg-blue">
            <div class="inner"><p>&nbsp;<br>Gestion des animateurs</p></div>
            <div class="icon"><i class="ion ion-alert"></i></div>
            <a href="index.php?a=23" class="small-box-footer"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Retour&nbsp;&nbsp;</a>
        </div>
    
        <!-- Presentation-->
        <div class="box box-primary">
            <div class="box-header"><h3 class="box-title">Fiche animateur</h3></div>
            <div class="box-body">
        
<?php 
    if ($animateur->hasParametresAnim()) {
        echo '<img src="' . $filedir . $avatar . '" width="30%">' ;
    } else {
        echo '<img src="./img/avatar/default.png" width="30%">' ;
    }
?>
            
                <div><label><?php echo $animateur->getPrenom();?> <?php echo $animateur->getNom();?></label></div>
        
     
            </div>
        </div> <!--box -->


    </div><!-- col -->


    <!-- right column -->
    <div class="col-md-8">
        <!-- div 2 : adresse-->
        <div class="box box-primary">
            <form method="post" action="<?php echo $post_url; ?>" role="form">  
                <div class="box-header"><h3 class="box-title">Param&egrave;tres</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>EPN de rattachement *</label>
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
        
                    <div class="form-group">
                        <label>Salles *</label>
                        <p >Indiquez la (ou les) salle(s) de la consultation internet qui sera surveill&eacute;e par l'animateur</p>
<?php 
    foreach ($salles as $salle) {
        if (isset($sallesAnim) && in_array($salle->getId(), $sallesAnim)) { 
            $check = "checked"; 
        } else {
            $check = ''; 
        }
        echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"salle_r[]\" value=" . $salle->getId() . "  " . $check . ">&nbsp;" . htmlentities($salle->getNom()) . " (" . htmlentities($salle->getEspace()->getNom()) . ")</div>";
    }
?>
    
                    </div>
                    <p>Choisissez parmi ces avatars</p>
                    <div class="form-group">
<?php 
    
    for ($v = 0 ; $v < $nbavatar ; $v++) {
        if (strcmp($avatar, $filesavatararray[$v]) == 0) { 
            $check = "checked"; 
        } else {
            $check = ''; 
        }
        echo "<img src=" . $filedir . $filesavatararray[$v] . " width=\"60px\" height=\"60px\">&nbsp;<input type=\"radio\" name=\"avatar_r\" value=" . $filesavatararray[$v] . "  " . $check . ">&nbsp;&nbsp;&nbsp;";
    }
?>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success">
                </div>
            </form>
        </div><!-- .box -->
    </div><!-- .col-md-8 -->
</div><!-- .row -->



