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
 2012 Florence DAUVERGNE

*/
    require_once("include/class/CSP.class.php");
    require_once("include/class/Ville.class.php");
    require_once("include/class/Espace.class.php");
    require_once("include/class/Tarif.class.php");
    require_once("include/class/Forfait.class.php");

    // Formulaire de creation ou de modification d'un adherent

    $idUser = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    $type   = isset($_GET["type"])   ? $_GET["type"]   : '';
    $b      = isset($_GET["b"])      ? $_GET["b"]      : '';
    $sim    = isset($_GET["sim"])    ? $_GET["sim"]    : '';
    $act    = isset($_GET["act"])   ? $_GET["act"]     : '';
    
    if ($act == "del" ){
        $idUser = '';
    }
    
    
    $date           = isset($date)         ? $date         : '';
    $dateRen        = isset($dateRen)      ? $dateRen      : '';
    $nom            = isset($nom)          ? $nom          : '';
    $prenom         = isset($prenom)       ? $prenom       : '';
    $sexe           = isset($sexe)         ? $sexe         : '';
    $jour           = isset($jour)         ? $jour         : '';
    $mois           = isset($mois)         ? $mois         : '';
    $annee          = isset($annee)        ? $annee        : '';
    $adresse        = isset($adresse)      ? $adresse      : '';
    $idVille        = isset($idVille)      ? $idVille      : '';
    $tel            = isset($tel)          ? $tel          : '';
    $mail           = isset($mail)         ? $mail         : '';
    $temps          = isset($temps)        ? $temps        : '';
    $loginn         = isset($loginn)       ? $loginn       : '';
    $pass           = isset($pass)         ? $pass         : '';
    $status         = isset($status)       ? $status       : '';
    $csp            = isset($csp)          ? $csp          : '14'; // par défaut sur "non renseigné"
    $equipement     = isset($equipement) && $equipement != ''   ? array_map('intval', explode("-", $equipement))   : array();
    $utilisation    = isset($utilisation)  ? $utilisation  : '';
    $connaissance   = isset($connaissance) ? $connaissance : '';
    $info           = isset($info)         ? $info         : '';
    $idTarif        = isset($idTarif)      ? $idTarif      : '';
    $idEspace       = isset($idEspace)     ? $idEspace     : '';
    $newsletter     = isset($newsletter)   ? $newsletter   : '';
    $mailok         = isset($mailok)       ? $mailok       : '';
     
    if ($idUser == '') {   // Parametre du formulaire pour la CREATION
    
        $post_url     = "index.php?a=1&b=1&act=1";
        $date         = date("Y-m-d");
        $label_bouton = "Cr&eacute;er l'adh&eacute;rent" ;
        $testb        = 1;
        
    } else {
    
        if ($sim != '') {
    
            //passer les infos pour le creer similaire
            
            $similaire = Utilisateur::getUtilisateurById($idUser);
            
            $post_url     = "index.php?a=1&b=1&act=1";
            $date         = date("Y-m-d");
            $label_bouton = "Cr&eacute;er l'adh&eacute;rent" ;

            $nom            = $similaire->getNom();
            $prenom         = "";
            $sexe           = "";
            $jour           = "";
            $mois           = "";
            $annee          = "";
            $adresse        = $similaire->getAdresse();
            $idVille        = $similaire->getIdVille();
            $tel            = $similaire->getTelephone();
            $mail           = $similaire->getMail();
            $loginn         = "";
            $status         = $similaire->getStatut(); 
            $csp            = "";
            $equipementarr  = $similaire->getEquipement();
            $equipement     = array_map('intval',explode("-",$equipementarr));
            $utilisation    = $similaire->getUtilisation();
            $connaissance   = $similaire->getConnaissance();
            $info           = $similaire->getInfo();
            $idTarif          = "";
            $idEspace       = $similaire->getIdEspace();
            $newsletter     = $similaire->getNewsletter();
              
        
        } else {
            // Parametre du formulaire pour la MODIFICATION
            $post_url = "index.php?a=1&b=2&act=2&iduser=" . $idUser;
            $label_bouton = "Modifier l'adh&eacute;rent" ;

            $utilisateur = Utilisateur::getUtilisateurById($idUser);
    
            // Information Utilisateur
            $date           = $utilisateur->getDateInscription();
            $dateRen        = $utilisateur->getDateRenouvellement();
            $nom            = $utilisateur->getNom();
            $prenom         = $utilisateur->getPrenom();
            $sexe           = $utilisateur->getSexe();
            $jour           = $utilisateur->getJourNaissance();
            $mois           = $utilisateur->getMoisNaissance();
            $annee          = $utilisateur->getAnneeNaissance();
            $adresse        = $utilisateur->getAdresse();
            $idVille        = $utilisateur->getIdVille();
            $tel            = $utilisateur->getTelephone();
            $mail           = $utilisateur->getMail();
            $rowtemps       = getTransactemps($idUser);
            $temps          = $rowtemps["id_tarif"];
            $loginn         = $utilisateur->getLogin();
            $status         = $utilisateur->getStatut(); 
            $csp            = $utilisateur->getCSP();
            $equipementarr  = $utilisateur->getEquipement();
            $equipement     = array_map('intval', explode("-", $equipementarr));
            $utilisation    = $utilisateur->getUtilisation();
            $connaissance   = $utilisateur->getConnaissance();
            $info           = $utilisateur->getInfo();
            $idTarif        = $utilisateur->getIdTarifAdhesion();
            $idEspace       = $utilisateur->getIdEspace();
            $newsletter     = $utilisateur->getNewsletter();

            //coordonnees de l'espace
            $arraymail = getMailInscript();

            if (FALSE == $arraymail) {
                
                $mailok = 0;

            } else {
                $espacearray    = mysqli_fetch_array(getEspace($_SESSION["idepn"]));
                $mail_epn       = $espacearray["mail_espace"];
                $adresse_epn    = $espacearray["adresse"];
                $nom_epn        = $espacearray["nom_espace"];
                $tel_epn        = $espacearray["tel_espace"];

                $arraymailtype  = array(
                                        1=>"Introduction",
                                        2=>"Sujet/object",
                                        3=>"Corps du texte",
                                        4=>"Signature"
                                    );

                $mail_subject   = $arraymail[2];
                $mail_body1     = $arraymail[3];
                $mail_signature = $arraymail[4];

                $mail_body      = $mail_body1 . " \r\n\r\n identifiant : " . $loginn . "     Mot de passe : [indiquez le mot de passe de la personne]    \r\n  " . $mail_signature . " \r\n\r\n" . $nom_epn . " \r\n" . $adresse_epn . " \r\n" . $tel_epn . ".";

                $mailok         = 1;
            }
        }
    }
   
    // Tableau des mois
    $month = array(
           1=> "Janvier",
           2=> "F&eacute;vrier",
           3=> "Mars",
           4=> "Avril",
           5=> "Mai" ,
           6=> "Juin",
           7=> "Juillet",
           8=> "Aout",
           9=> "Septembre",
           10=> "Octobre",
           11=> "Novembre",
           12=> "D&eacute;cembre"
    );
    // recupere les villes
    $villes = Ville::getVilles();

    //recupere la csp -- Ajout
    $professions = CSP::getCSPs();

    // type d'equipement defini
    $equipementarray = array (
            0 => "Aucun &eacute;quipement",   
            1 => "Ordinateur",
            2 => "Tablette",
            3 => "Smartphone",
            4 => "T&eacute;l&eacute;vision connect&eacute;e",
            5 => " Internet &agrave; la maison (ADSL, satellite ou fibre)",
            6 => " Internet mobile (3G, 4G)",
            7 => "Pas de connexion Internet"
            );


    // type d'utilisation defini
    $utilisationarray = array (
            0 => "Aucun Lieu",
            1 => "A la maison",   
            2 => "Au bureau ou &agrave; l'&eacute;cole",
            3 => "A la maison et au bureau ou &agrave; l'&eacute;cole"
    );
        
    // type de connaissance defini
    $connaissancearray = array (
            0 => "D&eacute;butant",   
            1 => "Interm&eacute;diaire",
            2 => "Confirm&eacute;"
    );


    //recuperation des tarifs categorieTarif(2)=adhesion
    $tarifs     = Tarif::getTarifsbyCategorie(2);
    //recuperation des tarifs pour la consultation internet
    $forfaits   = Forfait::getForfaits();

    // retrouver les espaces
    // $espaces    = getAllepn();
    $espaces    = Espace::getEspaces();

    //modif creation uniquement des actifs/inactifs
    $state = array(
               1=> "Actif",
               2=> "Inactif",
               6=> "Archiv&eacute; (statistique)"
            );


    //Affichage -----
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != ''){
        echo geterror($mesno);
    }
    if (isset($mess) && $mess != "") {
        echo $mess;
    }
?>


<div class="row">
    <form method="post" action="<?php echo $post_url; ?>" role="form">
        <!--Colonne gauche-->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Fiche adh&eacute;rent</h3>
<?php 
    if ($b == 1) {
?>
                    &nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour mettre une photo, placez-la dans le dossier img/photo_profil, nommez-la en respectant la r&egrave;gle suivante : nomcompos&eacute;_prenom. Attention case sensitive !"><i class="fa fa-info"></i></small>
<?php
    } 
    
//    if ($row["status_user"] ==6 ) {
    if ($utilisateur->getStatut() == 6 ) {
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-red">Adh&eacute;rent archiv&eacute; ! </small>';
    }
    
    if ($b == 2) {
?>
                    <div class="box-tools pull-right">
                        <a href="index.php?a=6&iduser=<?php echo $idUser; ?>" class="btn  bg-yellow"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>                    
                        <a href="index.php?a=5&b=6&iduser=<?php echo $idUser; ?>" class="btn  bg-blue"  data-toggle="tooltip" title="Participation Ateliers"><i class="fa fa-keyboard-o"></i></a>                  
                        <a href="index.php?a=9&iduser=<?php echo $idUser; ?>" class="btn bg-maroon"  data-toggle="tooltip" title="Consultation internet"><i class="fa fa-globe"></i></a>
                        <a href="index.php?a=21&b=1&iduser=<?php echo $idUser; ?>" class="btn bg-navy"  data-toggle="tooltip" title="Compte d'impression"><i class="fa fa-print"></i></a>
                    </div>
<?php } ?>
                </div><!-- .box-header -->
                <div class="box-body no-padding">
                    <table class="table table-condensed">
                        <tr>
                            <td width="50%">
                                <table>
                                    <tr>
                                        <td>
<?php 
            
    if ($idUser != '') {
        if ($sim != '') { //creer similaire
            echo '<img src="img/avatar/default.png" width="115px" class="img-circle">' ;
        } else {
            // tout le monde
            //detection existance fichier image pour la photo
            //enlever les espaces
            $nomSE    = str_replace(CHR(32), "", $nom);
            $prenomSE = str_replace(CHR(32), "", $prenom);
            $filename = "img/photos_profil/" . trim($nomSE) . "_" . trim($prenomSE) . ".jpg" ;
            if (file_exists($filename)) {
                    echo  '<img src=' . $filename . ' width="115px" hspace="5" class="img-circle">';
                } else {
                    //avatar pour personnes sans image                  
                    if ($sexe == "F"){
                        echo '<img src="img/avatar/female.png" class="img-circle"  width="115px">' ;
                    } else {
                        echo '<img src="img/avatar/male.png" class="img-circle"  width="115px">' ;
                    }
                }
            }
            
        } else {
        // creation avatar par defaut
        echo '<img src="img/avatar/default.png" width="60%">' ;
    }
                
?>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label>Nom * :</label>
                                                <input type="text" name="nom" value="<?php echo htmlentities($nom);?>" class="form-control">
                                            </div>
        
                                            <div class="form-group">
                                                <label>Pr&eacute;nom * :</label>
                                                <input type="text" name="prenom" value="<?php echo htmlentities($prenom);?>" class="form-control">
                                            </div>
        
                                            <div>
                                                <label>Sexe *:&nbsp;</label>
                                                <input type="radio" name="sexe" value="H" <?php echo $sexe == "H" ? "checked" : ""; ?>>&nbsp;Homme&nbsp;&nbsp;
                                                <input type="radio" name="sexe" value="F" <?php echo $sexe == "F" ? "checked" : ""; ?>>&nbsp;Femme
                                            </div>
                                        </td>
                                    </tr>
            
                                    <tr>
                                        <td colspan="2">
                                            <div class="form-group">
                                                <label>Date de Naissance *:</label>
                                                <table class="table" border="0">
                                                    <tr>
                                                        <td>
                                                            <select name="jour" tabindex="1" class="form-control">
<?php
    for ($i = 1 ; $i < 32 ; $i++) {
        if ($i == $jour) {
            echo "<option value=\"" . $i . "\" selected>" . $i . "</option>";
        } else {
            echo "<option value=\"" . $i . "\">" . $i . "</option>";
        }
    }
?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="mois" tabindex="2" class="form-control">
<?php
    foreach ($month AS $key=>$value) {
        if ($mois == $key) {
            echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
        } else {
            echo "<option value=\"" . $key . "\">" . $value . "</option>";
        }
    }
?>
                                                            </select>
                                                        </td>
           
                                                        <td>
                                                            <input type="text" name="annee" tabindex="3" maxlength="4" value="<?php echo htmlentities($annee);?>" size="2" class="form-control">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div><!-- form group-->
                                        </td>
                                    </tr>
                                </table>
                            </td>
     
                            <td width="50%">
                                <div class="form-group">
                                    <label>Adresse *:</label>
                                    <textarea name="adresse" class="form-control" tabindex="4"><?php echo htmlentities($adresse); ?></textarea>
                                </div>
        
                                <div class="form-group">
                                    <label>Ville *:</label>
                                    <select name="ville" class="form-control" tabindex="5">
<?php
    foreach ($villes AS $ville) {
        if ($idVille == $ville->getId()) {
            echo "<option value=\"" . $ville->getId() . "\" selected>" . htmlentities($ville->getNom()) . "</option>";
        } else {
            echo "<option value=\"" . $ville->getId() . "\">" . htmlentities($ville->getNom()) . "</option>";
        }
    }
?>
                                    </select>
                                </div>
        
                                <div class="input-group">
                                    <span class="input-group-addon" tabindex="6"><i class="fa fa-phone"></i></span>
                                    <input type="tel" name="tel" value="<?php echo htmlentities($tel);?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control" placeholder="01549875631" maxlength="10"/>
                                </div>
                                <br>
        
                                <div class="input-group">
                                    <span class="input-group-addon" tabindex="7"><i class="fa fa-envelope"></i></span>
                                    <input type="email" name="mail" value="<?php echo htmlentities($mail);?>" class="form-control">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div><!-- .box-body -->
            </div> <!--box -->
            <!-- div 1 : /vie-->


            <!-- div 3 : donnees base-->    
            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Donn&eacute;es pour la base</h3></div>
                <div class="box-body no-padding">
                    <table class="table table-condensed">
                        <tr>
                            <td width="45%">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" tabindex="8">Login *</label>
                                    <input type="text" name="login" value="<?php echo htmlentities($loginn);?>" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1" tabindex="9">Mot de passe *</label>
                                    <input type="text" name="passw" value="<?php echo htmlentities($pass);?>" class="form-control">
                                </div>
    
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="form-group">
                                            <label>Date de 1&egrave;re d'inscription</label>
                                            <input type="text" name="inscription" value="<?php echo htmlentities($date);?>" class="form-control" <?php if ($b == 2) { echo 'disabled';}?>>
                                        </div>
                                    </div>
<?php   
    if ($b == 2) {
?>
                                    <div class="col-xs-5">
                                        <div class="form-group">
                                            <label>Date de renouvellement</label>
                                            <input type="text" name="renouvellement" value="<?php echo htmlentities($dateRen);?>" class="form-control" <?php if ($b == 2) { echo 'disabled';}?>>
                                        </div>
                                    </div>
<?php } ?>
                                </div><!-- .row -->
    
                                <div class="form-group">
                                    <label>Tarif de la consultation internet
                                    &nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour modifier le tarif de la consultation, passez par les abonnements."><i class="fa fa-info"></i></small></label>
<?php if ($b == 2) { $disabled = "disabled"; } else { $disabled = ""; } ?>
                                    <select name="temps" class="form-control" <?php echo $disabled; ?> >
<?php
    foreach ($forfaits AS $forfait) {
        if ($temps == $forfait->getId()) {
            echo "<option value=\"" . $forfait->getId() . "\" selected>" . htmlentities($forfait->getNom()) . " (" . htmlentities($forfait->getPrix()) . " €)</option>";
        } else {
            echo "<option value=\"" . $forfait->getId() . "\">" . htmlentities($forfait->getNom()) . " (" . htmlentities($forfait->getPrix()) . " €)</option>";
        }
    }
?>
                                    </select>
                                </div>
    
                                <div class="form-group">
                                    <label>Tarif de l'adh&eacute;sion
                                    &nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour modifier le tarif de l'adh&eacute;sion, passez par les abonnements."><i class="fa fa-info"></i></small></label>
<?php if ($b == 2) { $disabled = "disabled"; } else { $disabled = ""; } ?>
                                    <select name="tarif" class="form-control" <?php echo $disabled; ?> >
<?php
    foreach ($tarifs AS $tarif) {
        if ($idTarif == $tarif->getId()) {
            echo "<option value=\"" . $tarif->getId() . "\" selected>" . htmlentities($tarif->getNom()) . " (" . htmlentities($tarif->getDonnee()) . " €)</option>";
        } else {
            echo "<option value=\"" . $tarif->getId() . "\">" . htmlentities($tarif->getNom()) . " (" . htmlentities($tarif->getDonnee()) . " €)</option>";
        }
    }
?>
                                    </select>
                                </div>
    
                            </td>
                            <td></td> <!-- ????? -->
                            <td width="50%">
        
                                <div class="form-group">
                                    <label>Epn d'inscription </label>
                                    <select name="epn" class="form-control" >
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
                                    <label>Statut </label>
                                    <select name="status"  class="form-control">
<?php
        foreach ($state AS $key=>$value) {
            if ($status == $key) {
                echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
            } else {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
?>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <label>Cat&eacute;gorie Socio-professionnelle</label>
                                    <select name="csp" class="form-control">
<?php
    foreach ($professions AS $profession) {
        if ($csp == $profession->getId()) {
            echo "<option value=\"" . $profession->getId() . "\" selected>" . htmlentities($profession->getCSP()) . "</option>";
        } else {
            echo "<option value=\"" . $profession->getId() . "\">" . htmlentities($profession->getCSP()) . "</option>";
        }
    }
?>
                                    </select>
                                </div>
    
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea  class="form-control" rows="3" name="info"><?php echo  htmlentities($info);?></textarea>
                                </div>
        
                                <div class="checkbox">
                                    <label>
<?php 
    if ($newsletter == '') { 
        echo ' <input type="checkbox" name="newsletter" value="1" />';
    } else {
        echo ' <input type="checkbox" name="newsletter" value="1" checked />';
    }
?>
                                    <b>Newsletter</b></label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div><!-- .box-body -->
                <!-- div 2 : /adresse-->


                <div class="box-footer">
<?php 
/*if ($_GET['type']=='anim' OR $_POST['type']=='anim')
{
echo '<input type="hidden" name="type" value="anim" />';
}   */  
?>
        
                    <input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success">
                    <!-- Bouton annuler revient aux resas direct--><!-- pourquoi ?? on revient plutot à la liste des adherents -->
                    <a href="index.php?a=1" class="btn btn-default">Annuler</a>
                </div><!-- .box-footer -->
        
            </div><!-- .box -->
        </div><!-- .col-md-8 -->
        <!-- colonne droite -->
        <div class='col-md-4'>
<?php
    if ($b == 2) {
?>

            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Actions</h3></div>
                <div class="box-body">
                    <a href="index.php?a=1&b=1&sim=1&iduser=<?php echo $idUser; ?>" class="btn btn-app bg-green"><i class="ion ion-person-add"></i>Cr&eacute;er similaire</a>
<?php
        if ($_SESSION['status'] == 4) {   
            echo "<a href=\"" . $_SERVER['REQUEST_URI'] . "&act=del\" class=\"btn btn-app bg-red\"><i class=\"fa fa-trash-o\"></i>Supprimer</a> ";
        }

        if(isset($idUser) AND $sim == '') {
?>
                    <a href="courriers/fiche.php?user=<?php echo $idUser ?>&epn=<?php echo $_SESSION["idepn"]?>" target="_blank" class="btn btn-app bg-blue" ><i class="fa fa-print"></i> Imprimer la fiche</a>
<?php
        
            //Bouton d'envoi de mail de rappel
            if ($mailok == 1) {
                if ($mail != "") {
?>
                    <a href="mailto:<?php echo $mail; ?>?SUBJECT=<?php echo $mail_subject; ?>&BODY=<?php echo $mail_body; ?>"><button class="btn btn-app bg-navy"><i class="fa fa-paper-plane"></i> Mail Id/Passw </button></a>
<?php           } 
            }
        }
?>
                </div><!-- .box-body -->
            </div><!-- .box -->

<?php 
    }
?>


            <!-- div 4 : infos diverses-->  
            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Informations compl&eacute;mentaires</h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>&Eacute;quipement personnel&nbsp;</label>
<?php
    
    for ($x = 0 ; $x < 5 ; $x++) {
    
        if (in_array($x, $equipement)) { 
            $check = "checked"; 
        } else {
            $check = ''; 
        }
        
        echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"equipement[]\" value=" . $x . "  " . $check . " >&nbsp;" . $equipementarray[$x] . "</div>";
    }
?>
                    </div>
                    <div class="form-group">
                        <label>Connexion internet</label>
<?php
      
    for ($x = 5 ; $x < 8 ; $x++) {
        if (in_array($x, $equipement)) { 
            $check = "checked"; 
        } else {
            $check = ''; 
        }
        
        echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"equipement[]\" value=" . $x . "  " . $check . ">&nbsp;" . $equipementarray[$x] . "</div>";
    }
?>
                    </div>
                    
                    <div class="form-group">
                        <label>Lieu d'utilisation d'internet</label>
<?php
    foreach ($utilisationarray AS $keyutil=>$valueutil) {
        if (strcmp ($utilisation, $keyutil) == 0) {
            echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=" . $keyutil . "  class=\"minimal\" checked>&nbsp;" . $valueutil . "  </label></div>";
        } else {
            echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=" . $keyutil . " class=\"minimal\">&nbsp;" . $valueutil . " </label></div> ";
        }
    }
?>
                    </div>
                    <div class="form-group">
                        <label>Le niveau en informatique</label>
<?php
    foreach ($connaissancearray AS $key=>$valuecon) {
        if (strcmp ($connaissance,$key) == 0) {
            echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=" . $key . " checked>&nbsp;" . $valuecon . "</label></div>";
        } else {
            echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=" . $key . ">&nbsp;" . $valuecon . "</label></div>";
        }
    }
?>
                    </div>
                </div><!-- .box-body -->
            </div><!-- .box -->
        </div><!-- ./col -->
        
    </form>
</div><!-- ./row -->
