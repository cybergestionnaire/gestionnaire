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

 2006 Namont Nicolas (CyberGestionnaire)
 
*/
    require_once("include/class/Materiel.class.php");
    require_once("include/class/Usage.class.php");
// formulaire de creation / modification de materiel
                            
    $idMateriel = isset($_GET["idmat"]) ? $_GET["idmat"] : '';
    $idEspace   = isset($_GET["epnr"]) ? $_GET["epnr"] : '';

    if ($idMateriel == '') {
        // Parametre du formulaire pour la CREATION
        $post_url      = "index.php?a=2&b=1&act=1";
        $label_bouton  = "Cr&eacute;er le poste" ;
        
        //Informations matos
        $nom           = '';
        $os            = '';
        $usage         = '';
        $comment       = '';
        $idSalle       = '';
        $adresseIP     = '';
        $adresseMAC    = '';
        $nomhote       = '';
        $fonctionarray = '';
        $fonctions     = '';
    }
    else {
        // Parametre du formulaire pour la MODIFICATION
        $post_url      = "index.php?a=2&act=2&idmat=" . $idMateriel;
        $label_bouton  = "Modifier le poste" ;
        $materiel      = Materiel::getMaterielById($idMateriel);

        //Informations matos
        $nom           = $materiel->getNom();
        $os            = $materiel->getOs();
        $usage         = $materiel->getUsage();
        $comment       = $materiel->getCommentaire();
        $idSalle       = $materiel->getIdSalle();
        $adresseIP     = $materiel->getAdresseIP();
        $adresseMAC    = $materiel->getAdresseMAC();
        $nomhote       = $materiel->getNomHote();
        $fonctions     = explode(";",$materiel->getFonction());
    }
    
    $usages   = Usage::getUsages();
    $nbUsages = count($usages);


    // type d'os d&eacute;fini
    $osarray = array (
             0 => "Windows 8",   
             1 => "Windows 7",
             2 => "Windows Vista",
             3 => "Windows XP",
             4 => "Mac OSX",
             5 => "Ubuntu",
             6 => "Linux",
             7 => "Windows server",
             8 => "Windows 10"
    );

    $allSalles = Salle::getSalles();
    

    include("include/boites/menu-parametres.php");
?>

<div class="row">
    <div class="col-md-7">

        <div class="box box-primary">
            <div class="box-header"><h3 class="box-title"><?php echo $label_bouton; ?></h3></div>
            <div class="box-body">
                <form method="post" action="<?php echo $post_url; ?>" role="form">
                    <div class="form-group">
                        <label >Nom du poste *: </label>
                        <input type="text" name="nom" value="<?php echo htmlentities($nom);?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label >Syst&egrave;me :</label>
                        <select name="os" class="form-control">
<?php
    foreach ($osarray AS $key=>$value) {
        if ($os == $value) {
            echo "<option value=\"".$value."\" selected>".$value."</option>";
        }
        else {
            echo "<option value=\"".$value."\">".$value."</option>";
        }
    }
?>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label >Salle *:</label>
   
                        <select name="salle" class="form-control">
<?php
    foreach ($allSalles AS $salle) {
        if ($salle->getId() == $idSalle) {
            echo "<option value=\"" . $salle->getId() . "\" selected>" . htmlentities($salle->getNom()) . "</option>";
        }
        else {
            echo "<option value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom()) . "</option>";
        }
    }
?>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label >Commentaires : </label>
                        <textarea name="comment" class="form-control"><?php echo htmlentities($comment);?></textarea>
                    </div>
                    <div class="form-group">
                        <label >Accessible &agrave; la r&eacute;servation : </label>

<?php
    switch ($usage) {
        case 1:
            $sel1 = "checked=\"checked\"" ;
            $sel2 = "";
            $sel3 = "";
            break;
        case 2:       
            $sel1 = "" ;
            $sel2 = "checked=\"checked\"";
            $sel3 = "";
            break;
        case 3:       
            $sel1 = "" ;
            $sel2 = "";
            $sel3 = "checked=\"checked\"";
            break;
        default:
            $sel1 = "checked=\"checked\"" ;
            $sel2 = "";
            $sel3 = "";
            break;
        }
?>
                        <div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="usage" value="1" <?php echo $sel1; ?>><label>Oui (usage public)</label></div>
                        <div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="usage" value="2" <?php echo $sel2; ?>><label>Non (usage interne)</label></div>
                        <div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="usage" value="3" <?php echo $sel3; ?>><label>Non (usage public sans r&eacute;servation)</label></div>
                    </div>
                    <div class="form-group">
                        <label >Fonctions</label>
<?php
    if ($idMateriel != "" ) {   
        foreach ($usages AS $usage) {
            if (in_array($usage->getId(), $fonctions)) { 
                $check = "checked"; 
            }
            else {
                $check = ''; 
            }
            echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fonction[]\" value=" . $usage->getId() . "  " . $check . ">&nbsp;&nbsp;" . htmlentities($usage->getNom()) . "</div>\r\n";
        }
    }
    else {   // creation d'un poste
    
        foreach ($usages AS $usage) {
            echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fonction[]\" value=" . $usage->getId() . ">&nbsp;&nbsp;" . htmlentities($usage->getNom()) . "</div>\r\n" ;
        }
    }
    ?>
                    </div>
    
                    <!-- IP mask -->
                    <div class="form-group">
                        <label>Adresse IP</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-laptop"></i>
                            </div>
                            <input type="text" name="adresseIP" class="form-control" value="<?php echo htmlentities($adresseIP);?>" placeholder="192.168.0.1" data-inputmask="'alias': 'ip'" data-mask/>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->

                    <div class="form-group">
                        <label>Adresse MAC</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-laptop"></i>
                            </div>
                            <input type="text" name="adresseMAC" class="form-control" value="<?php echo htmlentities($adresseMAC);?>" placeholder="AA-00-B2-12...."/>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
    
    
                    <div class="form-group">
                        <label>Nom H&ocirc;te</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-laptop"></i>
                            </div>
                            <input type="text" name="nomhotecomputer" class="form-control" value="<?php echo htmlentities($nomhote);?>" placeholder="\\Poste..." />
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
    
                    <div class="box-footer"><button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button></div>
                </form>
            </div><!-- .box-body -->
        </div><!-- .box -->
    </div><!-- .col-md-7 -->
</div><!-- .row -->
