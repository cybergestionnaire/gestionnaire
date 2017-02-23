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
 DETAIL D'UN ATELIER MODIFICATION 2013
*/

    require_once("include/class/Espace.class.php");
    require_once("include/class/Atelier.class.php");
    require_once("include/class/Tarif.class.php");

    $b          = isset($_GET["b"]) ? $_GET["b"] : '';
    $idAtelier  = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '';
    $idUser     = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    $searchuser = isset($_POST["searchuser"]) ? $_POST["searchuser"] : '';
    $idStatut   = isset($_GET["idstatut"]) ? $_GET["idstatut"] : '';
    // $espaces    = getAllepn();
    $espaces    = Espace::getEspaces();
         
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }
        
    if ($b != "") {   // affichage d'un atelier ---------------------------------------

        $atelier   = Atelier::getAtelierById($idAtelier);
        $animateur = $atelier->getAnimateur();
        $sujet     = $atelier->getSujet();
        $salle     = $atelier->getSalle();
        $tarif     = $atelier->getTarif();
        
        //debut if0
        //recuperation des variables
        // $row      = getAtelier($idAtelier,0);
        // $idsujet  = $row["id_sujet"];
        // $result   = getSujetById($idsujet);
        // $rowsujet = mysqli_fetch_array($result);
        // $anim     = getUserName($row["anim_atelier"]);
        // $salle    = mysqli_fetch_array(getSalle($row["salle_atelier"]));
        // $nomsalle = $salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
        // $tarif    = getNomTarif($row["tarif_atelier"]);
        // $idtarif  = $row["tarif_atelier"];
        // $statut   = $row["statut_atelier"];
        // //variable pour les courriers
        // $idepn            = $salle['id_espace'];
        // $statusepnconnect = $row["status_atelier"];
        
        //actions du formulaire pour inscriptions
        if ($b == 2) {
            if ($atelier->inscrireUtilisateurAvecTarif($idUser, $tarif->getId())) {
                echo geterror(25);
            }
        }
        if ($b == 3) {
            if ($atelier->desinscrireUtilisateur($idUser)) {
            // if (FALSE != delUserAtelier($idAtelier, $idUser)) {
                echo geterror(27);
            }
        }
        
        if ($b == 4) {
            //test s'il reste une place ou non, si oui enlever de la liste d'attente
            //if (countPlace($idAtelier) < $row["nbplace_atelier"]) {
            if ($atelier->getNbPlacesRestantes() > 0) {
          
//                if (FALSE != ModifyUserAtelier($idAtelier, $idUser,0)) {
                if ($atelier->MAJStatutUtilisateur($idUser, 0)) {
                    echo geterror(26);
                }
            }
            else {
                echo "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-exclamation\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>L'atelier est d&eacute;j&agrave; complet, veuillez attendre qu'une place se lib&egrave;re !</div>" ;
            }
        }

        if ($b == 5) { // arrivée depuis le formulaire des présences
            echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Pr&eacute;sence valid&eacute;e</div>" ;
        }
        if ($b == 6) { // deplacer de l'inscription à la liste d'attente
//            if (FALSE != ModifyUserAtelier($idAtelier,$idUser,2)) {
            if ($atelier->MAJStatutUtilisateur($idUser, 2)) {
    
                echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription en liste d'attente valid&eacute;e</div>" ;
            }
            else {
                echo geterror(26);
            }
        }
    
        if ($b == 10) {// en liste d'attente
            //if (FALSE != addUserAtelierAttente($idAtelier,$idStatut,$idUser)) {
                // attention !! j'ai enlevé le statut ! A voir quand la variable $idStatut est renseignée ( --> _GET["idstatut"])
            if ($atelier->inscrireUtilisateurEnAttente($idUser)) {
                echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription en liste d'attente valid&eacute;e</div>" ;
            }
        }
    
        //libération pour epnconnect
        if ($b == 11) {
            if ($atelier->setStatus(1)) {
                echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;EpnConnect lib&egrave;re les postes pour l'atelier.</div>" ;
            }
        }
        
        //Cloture de l'atelier pour epnconnect
        if ($b == 12) {
            if ($atelier->setStatus(2) AND $atelier->setCloturer(1)) {
                echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;Atelier cl&ocirc;tur&eacute;, EpnConnect reprends le contr&ocirc;le !</div>" ;
            }
        }
    
            
        // if (countPlace($idAtelier) > $row["nbplace_atelier"]){
            // $nbplace = 0;
        // }
        // else {
            // $nbplace = $row["nbplace_atelier"] - countPlace($idAtelier);
        // }
        //adherent en attente
        //$rattente  = getAtelierUser($idAtelier, 2) ; 
        //$enattente = mysqli_num_rows($rattente);
        
        // $testTarifAtelier = TestTarifs();
        $testTarifAtelier = count(Tarif::getTarifsByCategorie('5'));
        //
        ////Envoi du mail de rappel
        //retrouver les mails de l'epn, les donnees texte subject/body
        //coordonnees de l'espace
        $arraymail = getMailRappel();

        if (FALSE == $arraymail){
            
            $mailok = 0;

        }
        else{
            // $espacearray = mysqli_fetch_array(getEspace($salle->getIdEspace()));
            // $mail_epn    = $espacearray["mail_espace"];
            // $adresse_epn = $espacearray["adresse"];
            // $nom_epn     = $espacearray["nom_espace"];
            // $tel_epn     = $espacearray["tel_espace"];
            
            $espace = $salle->getEspace();
            

            $arraymailtype = array(
                    1=>"Introduction",
                    2=>"Sujet/object",
                    3=>"Corps du texte",
                    4=>"Signature"
                );

            $mail_subject   = $arraymail[2];
            $mail_body1     = $arraymail[3];
            $mail_signature = $arraymail[4];

//            $mail_body      = $mail_body1."\r\n en date du ".getDayfr($row["date_atelier"])." &agrave; ".$row["heure_atelier"]." pour l'atelier ".$rowsujet["label_atelier"]."  anim&eacute; par ".$anim."  &agrave; ".$nomsalle.".\n\r D&eacute;tail de l'atelier : \r\n".stripslashes($rowsujet["content_atelier"]).". ".$mail_signature." \r\n\r\n".$nom_epn." \r\n".$adresse_epn." \r\n".$tel_epn.".";
            $mail_body  = $mail_body1."\r\n en date du " . getDayfr($atelier->getDate()) . " &agrave; " . $atelier->getHeure() . " pour l'atelier " . $sujet->getLabel()
                        . "  anim&eacute; par " . htmlentities($animateur->getPrenom(). ' ' . $animateur->getNom())
                        . "  &agrave; " . htmlentities($salle->getNom() . ' (' . $salle->getEspace()->getNom() . ')') 
                        . ".\n\r D&eacute;tail de l'atelier : \r\n" . htmlentities($sujet->getContent()) . ". " 
                        . $mail_signature . " \r\n\r\n" . htmlentities($espace->getNom()) . " \r\n" . htmlentities($espace->getAdresse()) . " \r\n" . htmlentities($espace->getTelephone()) . ".";

            $mailok = 1;
        }

        
    ?> 
    
<!-- DETAIL DE L'ATELIER-->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
 
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Atelier <?php echo htmlentities($sujet->getLabel());?></h3></div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Date</dt><dd>Le <?php echo getDayfr($atelier->getDate());?> &agrave; <?php echo $atelier->getHeure();?> </dd>
                    <dt>Anim&eacute; par</dt><dd> <?php echo htmlentities($animateur->getPrenom(). ' ' . $animateur->getNom());?></dd>
                    <dt>O&ugrave;</dt><dd> <?php echo htmlentities($salle->getNom() . ' (' . $salle->getEspace()->getNom() . ')');?> </dd>
                    <dt>Tarif</dt><dd> <?php echo htmlentities($tarif->getNom()) ;?></dd>
                    <dt>Places restantes</dt><dd> <?php echo $atelier->getNbPlacesRestantes() ;?> (Total : <?php echo $atelier->getNbPlaces();?> places ouvertes)</dd>
                    <dt>Adh&eacute;rents en attente </dt><dd><?php echo $atelier->getNbUtilisateursEnAttente() ;?></dd>
                    <dt>Description</dt><dd><?php echo htmlentities($sujet->getContent());?></dd>
                </dl>
            </div>
<?php 
        //test activation epnconnect pour les ateliers + si date du jour OK 
        if (date('Y-m-d') >= $atelier->getDate()) {
            if ($atelier->getStatus() > 0) {
                $class  = "disabled";
                $action = "#";
            }
            else {
                $class  = "";
                $action = "index.php?a=13&b=11&idatelier=" . $idAtelier;
            }
            
            if ($atelier->getStatus() == 2) {
                $class2 = "disabled";
            }
            else { 
                $class2 = "";
            }
?>
            <div class="box-footer">

                <a href="<?php echo $action; ?>"><button class="btn bg-red" type="submit"  <?php echo $class; ?>> <i class="fa fa-unlock"></i>&nbsp;&nbsp;D&eacute;sactiver EpnConnect</button></a>
                &nbsp;<a href="index.php?a=13&b=12&idatelier=<?php echo $idAtelier;?>"><button class="btn bg-green" type="submit" <?php echo $class2; ?>> <i class="fa fa-lock"></i>&nbsp;&nbsp;R&eacute;activer EpnConnect</button></a>
            </div>
<?php
        }
?>
            <div class="box-footer">
                <a href="index.php?a=11"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des ateliers</button></a>
            </div>
        </div><!-- .box -->

    
<!-- Fin DETAIL DE L'ATELIER-->
<!-- **********************liste des user inscrit a un atelier-->
<?php

        $utilisateursinscritsOuPresents = $atelier->getUtilisateursInscritsOuPresents();
    
        if (count($utilisateursinscritsOuPresents) > 0) {
            //tester la présence de tarifs ateliers
    
            if ($testTarifAtelier > 1) { 
                $tooltipinfo = "Inscriptions en cours / total d&eacute;pens&eacute;  sur total achet&eacute;";
            }
            else {
                $tooltipinfo = "Inscriptions en cours";
            }
?>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Abonn&eacute;s inscrits</h3>
                <div class="box-tools pull-right" >
                    <div class="btn-group" >
                        <small class="badge bg-blue" data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>"><i class="fa fa-info"></i></small>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead><th>Fiche</th><th>Nom, pr&eacute;nom</th><th><span data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>">Inscriptions/Forfait</span></th><th></th></thead>
<?php
            $bccusers = "";
            foreach ($utilisateursinscritsOuPresents as $utilisateur) {
            
                $nbASencours = getnbASUserEncours($utilisateur->getId(), 0) ; 
            
                // construction des BCCmail
                // if ($row2["mail_user"] <> '') {
                    // $bccusers = $bccusers . trim($row2["mail_user"]) . ";";
                // }
                if ($utilisateur->getMail() <> '') {
                    $bccusers = $bccusers . $utilisateur->getMail() . ";";
                }
            
                //mise en place tarification
                if ($testTarifAtelier > 1) { 
                
                    // 1= présence validée & depensé sur forfait en cours
                    $forfaitencours     = getForfaitUserEncours($utilisateur->getId());
                    $depenseactuel      = $forfaitencours["depense"]; //restant apres dépense
                    $nbactuelsurforfait = $forfaitencours["total_atelier"];
                    //nombre d'inscriptions validées hors forfait
                    $nbtotalforfait     = getForfaitAchete($utilisateur->getId(), 'adh'); //nbr total acheté !
                    $nbASpresent        = getnbASUservalidees($utilisateur->getId()); //nbr total validé
                    $nbhorsforfait      = $nbtotalforfait - $nbASpresent; //restant hors forfait
                
                    //debug($nbrestant);
            
                    if (FALSE == $forfaitencours) { //gestion hors forfait
                        if ($nbhorsforfait == 0) {
                            $depense = "0";
                        }
                        elseif ($nbhorsforfait < 0) {
                            $depense = "<span class=\"text-red\">" . abs($nbhorsforfait) . " Hors forfait</span>";
                        }
                        $affichage = $nbASencours . "/ " . $depense;
                    }
                    else {
                        //affichage avec forfait en cours
                        $affichage = $nbASencours . "/ " . $depenseactuel . " sur " . $nbactuelsurforfait;
                    }
                }
                else { // sans le forfait, affichage des autres inscriptions
                    $affichage = $nbASencours;
                }
?>
                    <tr>
                        <td><a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ;?>"  class="btn btn-default btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></a></td>
                        <td><span class="badge bg-yellow" data-toggle="tooltip" title="Date renouvellement adh&eacute;sion : <?php echo $utilisateur->getDateRenouvellement(); ?>">A</span>&nbsp;&nbsp;<?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?> </td>
                        <td><?php echo $affichage ?></td>
                        <td>
                            <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId() ;?>"  class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>
                            <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ;?>"  class="btn bg-blue btn-sm" data-toggle="tooltip" title="Autres inscriptions"><i class="fa fa-keyboard-o"></i></a>
                            <a href="courriers/lettre_atelier.php?user=<?php echo $utilisateur->getId();?>&epn=<?php echo $salle->getIdEspace(); ?>" target="_blank" class="btn bg-navy btn-sm" data-toggle="tooltip" title="imprimer les inscriptions"><i class="fa fa-envelope"></i></a>
             
<?php
                if ($statut == 0 ) {
?>
                            <a href="index.php?a=13&b=3&iduser=<?php echo $utilisateur->getId();?>&idatelier=<?php echo $idAtelier;?>"  class="btn bg-red btn-sm"  data-toggle="tooltip" title="D&eacute;sinscrire" ><i class="fa fa-trash-o"></i></a>
<?php
                }
?>
                            <a href="index.php?a=13&b=6&idatelier=<?php echo $idAtelier;?>&iduser=<?php echo $utilisateur->getId() ;?>"  class="btn bg-purple btn-sm"  data-toggle="tooltip" title="Mettre en liste d'attente"><i class="fa fa-repeat"></i></a>
            
                        </td>
                    </tr>
<?php
            }
?>
                </table>
            </div><!-- .box-body -->

            <div class="box-footer">
     
<?php
            if ($statut < 2 ) {
                //validation interdite si déjà faite !
                echo "<a href=\"index.php?a=16&b=4&act=0&idatelier=" . $idAtelier . "\"><input type=\"submit\" name=\"valider_presence\" value=\"Valider les Presences\" class=\"btn btn-success\"></a>";
            }
            else if ($statut == 2) {
                echo "<p class=\"text-red\">Cet atelier est pass&eacute; et clotur&eacute;, vous ne pouvez plus inscrire d'adh&eacute;rent</p>";
            } 
            //Bouton d'envoi de mail de rappel
            if ($mailok == 1) {
?>
                <a href="mailto:<?php echo $espace->getMail(); ?>?BCC=<?php echo $bccusers; ?>&SUBJECT=<?php echo $mail_subject; ?>&BODY=<?php echo $mail_body; ?>">
                <button class="btn bg-navy  pull-right"> <i class="fa fa-paper-plane"></i> Envoyer un rappel </button></a>
<?php
            }
?>
        
            </div>      
        </div><!-- .box -->
    
<?php
        }
?>
    </section>
<!--**********************inscrire un adherent à l'atelier-->
    <section class="col-lg-5 connectedSortable"> 

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Inscription</h3>
                <div class="box-tools">
                    <form method="POST" action="index.php?a=13&b=1&idatelier=<?php echo $idAtelier ;?> " role="form">
                        <div class="input-group">
                            <input type="text" name="searchuser" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Nom ou num&eacute;ro de carte"/>
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button> 
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive">
        
<?php
        //resultat de la recherche si -------------------------------------
        if ($searchuser != "" and strlen($searchuser) > 2) { // debut ifsearch
            // Recherche d'un adherent
            
            $utilisateursRecherche = Utilisateur::searchUtilisateurs($searchuser);
            $nb = count($utilisateursRecherche);
            if ($nb <= 0) {
                echo getError(6);
            }
            else {
                echo "<p>R&eacute;sultats de la recherche: " . $nb . "</p>";
?>
                <table class="table table-hover">
                    <thead><tr><th>Nom, Pr&eacute;nom</th><th>Inscriptions/Forfait</th><th></th></tr></thead>
                    <tbody>
<?php
                foreach ($utilisateursRecherche as $utilisateur) {

                    $statutuser = $utilisateur->getStatut();
                    //mise en place tarification
                    if ($testTarifAtelier > 1) { 
                        $nbASencours = getnbASUserEncours($utilisateur->getId(), 0) ; 
                        // 1= présence validée & depensé sur forfait en cours
                        $forfaitencours     = getForfaitUserEncours($utilisateur->getId());
                        $depenseactuel      = $forfaitencours["depense"]; //restant apres dépense
                        $nbactuelsurforfait = $forfaitencours["total_atelier"];
                        //nombre d'inscriptions validées hors forfait
                        $nbtotalforfait = getForfaitAchete($utilisateur->getId(), 'adh'); //nbr total acheté !
                        $nbASpresent    = getnbASUservalidees($utilisateur->getId()); //nbr total validé
                        $nbhorsforfait  = $nbtotalforfait - $nbASpresent; //restant hors forfait
        
                        //debug($nbrestant);
        
                        if (FALSE == $forfaitencours) { //gestion hors forfait
                            if ($nbhorsforfait == 0) {
                                $depense = "0";
                            }
                            elseif ($nbhorsforfait < 0) {
                                $depense = "<span class=\"text-red\">" . abs($nbhorsforfait) . " Hors forfait</span>";
                            }
                            $affichage = $nbASencours . "/ " . $depense;
                        }
                        else {
                            //affichage avec forfait en cours
                            $affichage = $nbASencours . "/ " . $depenseactuel . " sur " . $nbactuelsurforfait;
            
                        }
        
        
                    }
                    else { // sans le forfait, affichage des autres inscriptions
                        $affichage = $nbASencours;
                    }
            
                    //en grisé si adhérent inactif
                    if( $statutuser == 2) {
                        $classstatut = "text-muted";
                    }
                    else{
                        $classstatut = "";
                    }
                
                    if ($atelier->getNbPlacesRestantes() > 0) {
                        echo "<tr><td class=" . $classstatut . ">" . htmlentities($utilisateur->getNom() . " " . $utilisateur->getprenom()) . "</td>
                            <td>" . $affichage . "</td>
                            <td><a href=\"index.php?a=13&b=2&idstatut=0&idatelier=" . $idAtelier . "&iduser=" . $utilisateur->getId() . "\"><button type=\"button\" class=\"btn btn-success sm\"  data-toggle=\"tooltip\" title=\"Inscrire\"><i class=\"fa fa-check\"></i></button></a>
                            <a href=\"index.php?a=13&b=10&idstatut=2&idatelier=" . $idAtelier . "&iduser=" . $utilisateur->getId() . "\"><button type=\"button\" class=\"btn bg-purple sm\"   data-toggle=\"tooltip\" title=\"Mettre en liste d'attente\"><i class=\"fa fa-repeat\"></i></button></a></td>
                            </tr>";
                    }
                    else {
                        echo "<tr><td>" . htmlentities($utilisateur->getNom() . " " . $utilisateur->getprenom()) . "</td>
                            <td>" . $affichage . "</td>
                            <td><a href=\"index.php?a=13&b=10&idstatut=2&idatelier=" . $idAtelier . "&iduser=" . $utilisateur->getId() . "\"><button type=\"button\" class=\"btn btn-success sm\"><i class=\"fa fa-pause\" title=\"Mettre en liste d'attente\"></i></button></a></td>
                            </tr>";
                    }
                
                }
?>
                    </tbody>
                </table>
<?php
            }
        }
?>
            </div><!-- .box-body -->
        </div><!-- .box -->
    
<?php   



        //******************* liste des user en liste d'attente
        $utilisateursEnAttente = $atelier->getUtilisateursEnAttente();
        $nb = count ($utilisateursEnAttente);
        
        if ($nb > 0) {                  
?> 
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Abonn&eacute;s sur la liste d'attente   <small class="badge bg-blue" data-toggle="tooltip" title="Classement par ordre d'arriv&eacute;e, du plus ancien au plus r&eacute;cent"><i class="fa fa-info"></i></small></h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead>
                        <tr><th>&nbsp;</th><th>Nom, prenom</th><th>PDF</th></tr>
                    </thead>
                    <tbody>
<?php   
            foreach ($utilisateursEnAttente as $utilisateur) {
?>
                        <tr>
                            <td>
                                <a href="index.php?a=13&b=4&iduser=<?php echo $utilisateur->getId();?>&idatelier=<?php echo $idAtelier;?>"><button type="button" class="btn bg-green btn-sm"  data-toggle="tooltip"  title="Inscrire"><i class="fa fa-arrow-up"></i></button></a>
                                <a href="index.php?a=13&b=3&iduser=<?php echo $utilisateur->getId();?>&idatelier=<?php echo $idAtelier;?>"><button type="button" class="btn btn-warning btn-sm"  data-toggle="tooltip" title="D&eacute;sinscrire"><i class="fa fa-trash-o"></i></button></a>
                            </td>
                            <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                            <td><a href="lettre_atelier.php?user=<?php echo $utilisateur->getId();?>&epn=<?php echo $salle->getIdEspace(); ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-envelope"></i></button></a></td>
                        </tr>
<?php
            }
?>         
                    </tbody>
                </table>
            </div>
        </div><!-- .box -->
<?php
    }//FIN en Attente
?>
    </section>
<!-- retour de la validation-->
<?php
    if ($statut == 2) {
?>
    

    <section class="col-lg-5 connectedSortable"> 
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Atelier clotur&eacute;</h3></div>
            <div class="box-body">
                <p>Les pr&eacute;sences &agrave; cet atelier viennent d'&ecirc;tre valid&eacute;e. <br><p class="text-warning">Cliquez sur "r&eacute;activer EPNConnect" pour cloturer l'atelier.</p> 
                Les archives vous permettront de modifier une pr&eacute;sence en cas d'erreur !</p>
            </div>
        </div>
    </section>  
<?php
    }
?>

</div><!-- /row -->


<?php
}
?>
