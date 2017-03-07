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

 
*/
    require_once("include/class/Session.class.php");
    require_once("include/class/Tarif.class.php");

    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }

    $b             = isset($_GET["b"]) ? $_GET["b"] : '';
    $idUtilisateur = isset($_GET["iduser"]) ? $_GET["iduser"] : '';
    $idstatut      = isset($_GET["idstatut"]) ? $_GET["idstatut"] : '';
    $idsession     = isset($_GET["idsession"]) ? $_GET["idsession"] : '';

    $searchuser = isset($_POST["searchuser"]) ? $_POST["searchuser"] : '';
    
    // affichage d'une session---------------------------------------

    $session       = Session::getSessionById($idsession);
    $sujet         = $session->getSessionSujet();
    $animateur     = $session->getAnimateur();
    $salle         = $session->getSalle();
    $espace        = $salle->getEspace();
    $tarif         = $session->getTarif();
    $datesSession  = $session->getSessionDates();
    $nbrdates      = $session->getNbDates();

    $listesDates = '';
    foreach ($datesSession as $dateSession) {
        $listesDates .= getDatefr($dateSession->getDate())."<br />";
    }    

    //tester la présence de tarifs ateliers
    $testTarifAtelier = count(Tarif::getTarifsByCategorie('5'));

    if ($b == 2) {
        //verification d'inscription
        
        if (!$session->isUtilisateurInscrit($idUtilisateur)) {
            if ($session->inscrireUtilisateur($idUtilisateur, $idstatut)) {
                echo geterror(25);
            }
        } else {
            echo geterror(21);
        }
    }
    
    if ($b == 3) {
        if ($session->desinscrireUtilisateur($idUtilisateur)) {
            echo geterror(27);
        }
    }
    
    if ($b == 4) {
    
        if ($session->inscrireUtilisateurInscrit($idUtilisateur)) {
            echo geterror(26);
        }
    }
 
    $placesrestantes = $session->getNbPlacesRestantes();

?> 
    
    
<!-- DETAIL DE LA SESSION-->
<div class="row">
    <section class="col-lg-7 connectedSortable">
 
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title"><?php echo htmlentities($sujet->getTitre());?></h3></div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Dates programm&eacute;es</dt><dd> <?php echo $listesDates;?></dd>
                    <dt>Salle</dt><dd><?php echo htmlentities($salle->getNom()) . " (" . htmlentities($espace->getNom()) . ")" ?></dd>
                    <dt>Places restantes</dt><dd> <?php echo $placesrestantes ;?> (Total : <?php echo $session->getNbPlaces();?>)</dd>
                    <dt>Adh&eacute;rents en attente</dt><dd> <?php echo $session->getNbUtilisateursEnAttente() ;?></dd>
                    <dt>Description</dt> <dd><?php echo htmlentities($sujet->getDetail());?></dd>
                    <dt>Tarif</dt> <dd><?php echo htmlentities($tarif->getNom()); ?></dd>
                    <dt>Anim&eacute; par</dt> <dd><?php echo htmlentities($animateur->getPrenom() . " " . $animateur->getNom()) ?></dd>
             
                </dl>
            </div>
            <div class="box-footer">
                <a href="index.php?a=37"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des sessions</button></a>
            </div>
        </div><!-- .box -->

<?php        
    // if ($sessionstatut == 0) {
    if ($session->getStatus() == 0 ) {
        ///si la session est encore valide, affichage des adhérents 
   
        // liste des user inscrit a une session
        $utilisateursInscrits = $session->getUtilisateursInscrits();
        $nb = count($utilisateursInscrits);

        if ($nb > 0) {     
            if ($testTarifAtelier > 1) { 
                $tooltipinfo = "Inscriptions en cours / total d&eacute;pens&eacute;  sur total achet&eacute;";
            } else {
                $tooltipinfo = "Inscriptions en cours ";
            }

?>
    
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Liste des participants</h3></div>
            <div class="box-body">
                <table class="table">
                    <thead>
                        <th></th>
                        <th>Nom, prenom</th>
                        <th>Inscriptions/Forfait <small class="badge bg-blue" data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>"><i class="fa fa-info"></i></small></th>
                        <th></th>
                    </thead>
                    <tbody>
            
<?php
            foreach ($utilisateursInscrits as $utilisateur) {
                // 0= inscription en cours non validée
                $nbASencours = getnbASUserEncours($utilisateur->getId(), 0) ; 
                
                //mise en place tarification
                if ($testTarifAtelier > 1) { 
                    
                    // 1= présence validée & depensé sur forfait en cours
                    $forfaitencours     = getForfaitUserEncours($utilisateur->getId());
                    $depenseactuel      = $forfaitencours["depense"]; //restant apres dépense
                    $nbactuelsurforfait = $forfaitencours["total_atelier"];

                    //nombre d'inscriptions validées hors forfait
                    $nbtotalforfait     = getForfaitAchete($utilisateur->getId(), 'for'); //nbr total acheté !
                    // $nbtotalforfait     = getForfaitAchete($row2['id_user']); //nbr total acheté !
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
                    } else {
                        //affichage avec forfait en cours
                        $affichage = $nbASencours . "/ " . $depenseactuel . " sur " . $nbactuelsurforfait;
                    }
                
                
                } else { // sans le forfait, affichage des autres inscriptions
                    $affichage = $nbASencours;
                }
?>
                        <tr>
                            <td><a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ;?>"><button type="button" class="btn btn-default  btn-sm"  data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a></td>
                            <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                            <td><?php echo $affichage ; ?></td>
                            <td>
                                <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ;?>"  class="btn bg-blue  btn-sm" data-toggle="tooltip" title="Autres inscriptions"><i class="fa fa-keyboard-o"></i></a>
                                <a href="courriers/lettre_session.php?user=<?php echo $utilisateur->getId();?>&epn=<?php echo $espace->getId(); ?>" target="_blank" class="btn bg-navy btn-sm" data-toggle="tooltip" title="envoyer un courrier"><i class="fa fa-envelope"></i></a>
                                <a href="index.php?a=30&b=3&iduser=<?php echo $utilisateur->getId();?>&idsession=<?php echo $idsession;?>" class="btn bg-red  btn-sm" data-toggle="tooltip" title="d&eacute;sinscrire"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
<?php
            }
?>
         
                    </tbody>
                </table>
            </div><!-- .box-body -->
            <!-- VALIDATION DES PRESENTS INSCRITS -->
            <div class="box-footer">
<?php
            foreach ($datesSession as $dateSession) {
                if ($dateSession->getStatut() == 0) {
                    echo '<p></p><a href="index.php?a=32&act=0&idsession=' . $session->getId() . '&dateid=' . $dateSession->getId() . ' "><input type="submit" value="Valider les pr&eacute;sences pour le ' . getDayFr($dateSession->getDate()) . '" class="btn btn-block bg-olive"></a>';
                }
                if ($dateSession->getStatut() == 1) {
                    echo '<p></p><input type="submit" value="Atelier du ' . getDayFr($dateSession->getDate()) . ' clotur&eacute;" class="btn btn-block bg-maroon" disabled/>';
                }
                if ($dateSession->getStatut() == 2) {
                    echo '<p></p><input type="submit" value="Atelier du ' . getDayFr($dateSession->getDate()) . ' Annul&eacute;" class="btn btn-block bg-orange" disabled/>';
                }
            }
?>
            </div>
        </div><!-- .box -->
<?php
        }
?>
    </section>
    <!--AIDE COLONNE 2-->

    <section class="col-lg-5 connectedSortable">
        <div class="box box-default collapsed-box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-info-circle"></i><h3 class="box-title">Aide</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
     
                <h4>Inscriptions</h4>
                <p>Attention, les inscriptions ne sont plus modifiables une fois toutes les dates de la session valid&eacute;es et clotur&eacute;es. </p>
        
       
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!--AIDE-->

        <!--RECHERCHE ADHERENT-->


        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Inscrire un adh&eacute;rent</h3>
                <div class="box-tools">
                    <form method="POST" action="index.php?a=30&b=1&idsession=<?php echo $idsession; ?>">
                        <div class="input-group">
                            <input type="text" name="searchuser" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Nom ou num&eacute;ro de carte"/>
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div> 
            </div><!-- /.box-header -->
            <div class="box-body">
   
<?php

       //resultat de la recherche si -------------------------------------
        if ($searchuser != "" and strlen($searchuser) > 2) {
            // Recherche d'un adherent

            $utilisateursRecherche = Utilisateur::searchUtilisateurs($searchuser);
            $nb = count($utilisateursRecherche);
            if ($nb <= 0) {
                echo getError(6);
            } else {
                echo "<p>R&eacute;sultats de la recherche : " . $nb . "</p>";
?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nom Pr&eacute;nom</th>
                            <th><span data-toggle="tooltip" title="Inscriptions en cours / total d&eacute;pens&eacute; sur total achet&eacute;">Inscriptions/Forfait</span></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
        
                foreach ($utilisateursRecherche as $utilisateur) {
        
                    if ($placesrestantes > 0) {
                
                        // 0= inscription en cours non validée
                        $nbASencours = getnbASUserEncours($utilisateur->getId(), 0) ; 
                
                            //mise en place tarification
                        if ($testTarifAtelier > 1) { 
                
                            // 1= présence validée & depensé sur forfait en cours
                            $forfaitencours     = getForfaitUserEncours($utilisateur->getId());
                            $depenseactuel      = $forfaitencours["depense"]; //restant apres dépense
                            $nbactuelsurforfait = $forfaitencours["total_atelier"];

                            //nombre d'inscriptions validées hors forfait
                            $nbtotalforfait = getForfaitAchete($utilisateur->getId(), 'for'); //nbr total acheté !
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
                            } else {
                                //affichage avec forfait en cours
                
                                $affichage = $nbASencours . "/ " . $depenseactuel . " sur " . $nbactuelsurforfait;
                
                            }
                        } else { // sans le forfait, affichage des autres inscriptions
                            $affichage = $nbASencours;
                        }
?>  
                        <tr>
                            <td>
                                <a href="index.php?a=30&b=2&idstatut=0&idsession=<?php echo $idsession ?>&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn btn-success sm" title="Inscrire"><i class="fa fa-check"></i></button></a>
                                <a href="index.php?a=30&b=2&idstatut=2&idsession=<?php echo $idsession ?>&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn bg-purple sm"  title="Mettre en liste d'attente"><i class="fa fa-repeat"></i></button></a>
                            </td>
                
                            <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ?></td>
                            <td><?php echo $affichage ?></td>
                        </tr>
<?php
                    } else {
?>
                        <tr>
                            <td></td>
                            <td><a href="index.php?a=30&b=2&idstatut=2&idsession=<?php echo $idsession ?>&iduser=<?php echo $utilisateur->getId() ?>"><button type="button" class="btn bg-purple sm"  title="Mettre en liste d'attente"><i class="fa fa-repeat"></i></button></a></td>
                            <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ?></td>
                            <td><?php echo $affichage ?></td>
                        </tr>
<?php                                
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
        // liste des user en liste d'attente
        
        $utilisateursEnAttente = $session->getUtilisateursEnAttente();
        if (count($utilisateursEnAttente) > 0) {    
?>
        
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Liste des participants en liste d'attente   <small class="badge bg-blue" data-toggle="tooltip" title="Classement par ordre d'arriv&eacute;e, du plus ancien au plus r&eacute;cent"><i class="fa fa-info"></i></small></h3></div>
            <div class="box-body">
                <table class="table">
                    <thead> 
                        <th></th> 
                        <th>Nom, prenom</th>
                        <!--<th>autres inscriptions (pdf)</th>-->
                    </thead>
                    <tbody>
<?php       
             foreach ($utilisateursEnAttente as $utilisateur) {
?>
                        <tr>
                            <td>
<?php
                if ($placesrestantes > 0 ) {
?>
                                <a href="index.php?a=30&b=4&iduser=<?php echo $utilisateur->getId() ;?>&idsession=<?php echo $idsession;?>"><button type="button" class="btn btn-success sm"><i class="fa fa-check"></i></button></a>
<?php
                }
?>
                                <a href="index.php?a=30&b=3&iduser=<?php echo $utilisateur->getId();?>&idsession=<?php echo $idsession;?>"><button type="button" class="btn btn-warning sm"><i class="fa fa-trash-o"></i></button></a>
                            </td>
                            <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                            <!--<td><a href="pdf_atelier.php?user=<?php echo $utilisateur->getId();?>" target="_blank"><button type="button" class="btn btn-info sm"><i class="fa fa-envelope"></i></button></a></td>-->
                        </tr>
<?php
            }
?>
                    </tbody>
                </table>
            </div>
        </div><!-- .box -->
<?php
        }
?>
    
    </section>  
    
<?php   
    
    } else {
//la session a ete cloturée
?>
    <div class="box box-success">
        <div class="box-header"><h3 class="box-title">Session clotur&eacute;e</h3></div>
        <div class="box-body">
            <p>Toutes les dates de cette session ont &eacute;t&eacute; clotur&eacute;es, vous ne pouvez plus modifier les inscriptions, pour modifier les pr&eacute;sences, rendez-vous aux archives !</p>
        </div>
        <div class="box-footer">
            <a href="index.php?a=36"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Acc&egrave;s aux archives</button></a></div>
        </div>

<?php 
    }
?>
    
</div><!-- /row -->