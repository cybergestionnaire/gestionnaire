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
 

  include/admin_form_espace.php V0.1
*/

// formulaire de creation / modification d'espace
                            
$idEspace  = isset($_GET["idespace"]) ? $_GET["idespace"] : '';
$b         = isset($_GET["b"]) ? $_GET["b"] : '';


if ($idEspace == '') {   // Parametre du formulaire pour la CREATION
    $post_url = "index.php?a=43&b=1&act=1";
    $label_bouton = "Cr&eacute;er l'Espace" ;
    $logo="logo.png";
    $forfait=0;
} else {
    // Parametre du formulaire pour la MODIFICATION
    $post_url = "index.php?a=43&b=2&act=2&idespace=".$idEspace;
    $label_bouton = "Modifier l'espace" ;
    $espaceAModifier = Espace::getEspaceById($idEspace);
}

	// recupere les villes
$villes = Ville::getVilles();

//tableau des couleurs
$couleurArray = array(
                    1=> "green",
                    2=> "blue",
                    3=> "yellow",
                    4=> "red",
                    //5=> "olive",
                    6=> "purple",
                    //7=> "orange",
                    //8=> "maroon",
                    9=> "black"
                );


//array logos
$filesLogoarray=array();
$filedir="./img/logo/";
$filesLogoarray = array_diff(scandir($filedir), array('..', '.')); //lister les logos dans le dossier
$filesLogoarray = array_values($filesLogoarray); //r&eacute;indexer le tableau après avoir enlever lignes vides
$nblogo=count($filesLogoarray);

//Affichage -----
echo isset($mess) ? $mess : '' ;

?>
<div class="row">
    <form method="post" action="<?php echo $post_url; ?>" role="form">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title">Cr&eacute;er et modifier un nouvel espace</h3></div>
                
                <div class="box-body">
                    <div class="form-group">
                        <label >Nom de l'EPN *: </label>
                        <input type="text" name="nom" value="<?php echo isset($espaceAModifier) ? htmlentities($espaceAModifier->getNom()) : '';?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label >Adresse *: </label>
                        <textarea name="adresse" rows="3" class="form-control"><?php echo isset($espaceAModifier) ? htmlentities($espaceAModifier->getAdresse()) : '';?></textarea>
                    </div>
                    <div class="form-group">
                        <label >Ville *:</label>
                        <select name="ville" class="form-control" >
<?php
    foreach ($villes AS $ville) {
        if (isset($espaceAModifier) && $ville->getId() == $espaceAModifier->getIdVille()) {
            echo "<option value=\"" . $ville->getId() . "\" selected>" . $ville->getNom() . "</option>";
        } else {
            echo "<option value=\"" . $ville->getId() . "\">" . $ville->getNom() . "</option>";
        }
    }
?>
                        </select>
                    </div>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                        <input name="telephone" type="text" class="form-control" value="<?php echo isset($espaceAModifier) ? htmlentities($espaceAModifier->getTelephone()) : '';;?>" data-inputmask='"mask": "0112345678"' data-mask/>
                    </div>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-fax"></i></div>
                        <input name="fax" type="text" class="form-control" value="<?php echo isset($espaceAModifier) ? htmlentities($espaceAModifier->getFax()) : '';;?>" data-inputmask='"mask": "0112345678"' data-mask/>
                    </div>
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-envelope"></i>*</div>
                        <input name="mail" type="text" class="form-control" value="<?php echo isset($espaceAModifier) ? htmlentities($espaceAModifier->getMail()) : '';;?>" >
                    </div>
                </div><!-- .box-body -->
            </div><!-- .box -->
        </div><!-- .col-md-6 -->

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label >Une couleur:</label>
                        <select name="ecouleur" class="form-control">
 <?php
    foreach ($couleurArray AS $key=>$value) {
        if (isset($espaceAModifier) && $espaceAModifier->getCodeCouleur() == $key) {
            echo "<option value=\"" . $key . "\" selected class=\"text-" . $value . "\">" . $value . "</option>";
        } else {
            echo "<option value=\"" . $key . "\" class=\"bg-" . $value . "\">" . $value . "</option>";
        }
    }
?>
                        </select>
                    </div>
     
                    <div class="form-group">
                        <label>Le logo actuel de l'espace </label>
 <?php 
    if ($b == 2) {
        echo '<img src="'.$filedir.$espaceAModifier->getLogo().'" >' ;
    } else {
        echo '<img src="./img/logo/logo.png" >' ;
    }
?>
                        <p class="help-block">png, jpeg ou gif, taille 220x50px.</p>
                        
                        <label>Autre logo ?</label>
                        <p class="help-block"></p>
 <?php 
    
    for ($l = 0 ; $l < $nblogo ; $l++) {
        if (isset($espaceAModifier) && strcmp($espaceAModifier->getLogo(), $filesLogoarray[$l]) == 0) {
            $check = "checked"; 
        } else {
            $check = ''; 
        }
        echo "<img src=".$filedir.$filesLogoarray[$l].">&nbsp;<input type=\"radio\" name=\"elogo\" value=".$filesLogoarray[$l]."  ".$check.">";
    }
 ?>
                    </div>
                </div><!-- .box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button>
                </div>
            </div><!-- .box -->
        </div><!-- .col-md6 -->
    </form>
</div><!-- .row -->


