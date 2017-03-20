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
   
Formulaire de validation de présence aux ateliers
renvoie le nombre d'inscrits, le nombre de présents et l'id des présents (pour les stats personnelles)
 include/admin_atelier.php V0.1
*/
    require_once("include/class/Espace.class.php");
    require_once("include/class/Atelier.class.php");

    $idAtelier = isset($_GET["idatelier"]) ? $_GET["idatelier"] : '';
    $act       = isset($_GET["act"]) ? $_GET["act"] : '';

    $atelier   = Atelier::getAtelierById($idAtelier);
    $animateur = $atelier->getAnimateur();
    $sujet     = $atelier->getSujet();
    $salle     = $atelier->getSalle();
    $tarif     = $atelier->getTarif();


?> 

<div class="row">
    <!-- DETAIL DE L'ATELIER-->
    <div class=" col-xs-6">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Validation des pr&eacute;sences &agrave; l'Atelier</h3>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Titre</dt><dd> <?php echo htmlentities($sujet->getLabel());?></dd>
                    <dt>Date</dt><dd> <?php echo getDayfr($atelier->getDate());?> &agrave; <?php echo $atelier->getHeure();?> </dd>
                    <dt>Anim&eacute; par</dt><dd> <?php echo htmlentities($animateur->getPrenom(). ' ' . $animateur->getNom());?></dd>
                    <dt>O&ugrave;</dt><dd> <?php echo htmlentities($salle->getNom() . ' (' . $salle->getEspace()->getNom() . ')');?> </dd>
                    <dt>Tarif</dt><dd> <?php echo htmlentities($tarif->getNom()) ;?></dd>
                    <dt>Places restantes</dt><dd> <?php echo $atelier->getNbPlacesRestantes() ;?> (Total : <?php echo $atelier->getNbPlaces();?> places ouvertes)</dd>
                    <dt>Adh&eacute;rents en attente </dt><dd><?php echo $atelier->getNbUtilisateursEnAttente() ;?></dd>
                    <dt>Description</dt><dd><?php echo htmlentities($sujet->getContent());?></dd>
                </dl>
            </div>
            <div class="box-footer">
<?php 
    if ($act == 0) {
?>
                <a href="index.php?a=13&b=1&idatelier=<?php echo $idAtelier; ?>"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; l'atelier</button></a>
<?php 
    } else {
?>
                <a href="index.php?a=18"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour aux archives</button></a>
<?php
    }
?>
            </div>
        </div><!-- .box -->
    </div><!-- .col-xs-6 -->

    <!-- Fin DETAIL DE L'ATELIER-->
    <div class="col-xs-6">
<?php
    // liste des user inscrit a un atelier
    if ($act == 0) {
//        $utilisateursPresents
        
        $action = "index.php?a=16&b=5&idatelier=" . $idAtelier . " ";
        $bouton = "Valider les pr&eacute;sences";
    
    }
    else if ($act == 1) { //venue depuis les archives pour modification
        $action = "index.php?a=16&b=4&act=1&idatelier=" . $idAtelier . " ";
        $bouton = "Modifier les pr&eacute;sences";
    }
    $utilisateursInscrits = $atelier->getUtilisateursInscrits();
    $utilisateursPresents = $atelier->getUtilisateursPresents();
    
    $nb = count($utilisateursInscrits) + count($utilisateursPresents) ;
    
    if ($nb > 0) {                  
?>
        <form method="post" action="<?php echo $action; ?>">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">Liste des participants &agrave; cet atelier</h3></div>
                <div class="box-body">
                    <table class="table"> 
                        <thead>
                            <tr><th>Nom, prenom</th><th>Pr&eacute;sence</th><th></th></tr>
                        </thead>
                        <tbody>
            
<?php
        foreach ($utilisateursInscrits as $utilisateur) {
?>
                            <tr>
                                <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                                <td><input type="checkbox" name="present_[]" value="<?php echo $utilisateur->getId(); ?>" ></td>
            
                                <td>
                                    <input type="hidden" value="<?php echo $atelier->getId(); ?>" name="idatelier">
                                </td>
                            </tr>
         
<?php
        }
?><?php
        foreach ($utilisateursPresents as $utilisateur) {
?>
                            <tr>
                                <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                                <td><input type="checkbox" name="present_[]" value="<?php echo $utilisateur->getId(); ?>"  checked ></td>
            
                                <td>
                                    <input type="hidden" value="<?php echo $atelier->getId(); ?>" name="idatelier">
                                </td>
                            </tr>
         
<?php
        }
?>
                        </tbody>
                    </table>
                </div><!-- .box-body -->
                <div class="box-footer"><input type="submit" name="valider_presence" value="<?php echo $bouton; ?>" class="btn bg-olive"></div>
            </div><!-- .box -->
        </form>
<?php
    }
?>
    </div><!-- .col-xs-6 -->
</div><!-- .row -->