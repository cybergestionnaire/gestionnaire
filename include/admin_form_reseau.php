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
require_once("include/class/Ville.class.php");
// formulaire de creation / modification d'espace
                            

$b=$_GET["b"];


// Parametre du formulaire pour la MODIFICATION
$post_url = "index.php?a=43&b=4&act=4";
$label_bouton = "Modifier le r&eacute;seau" ;
$rowreseau = getReseau();

//Informations matos
$nom        = $rowreseau["res_nom"];
$adresse    = $rowreseau["res_adresse"];
$idVille    = $rowreseau["res_ville"];
$telephone  = $rowreseau["res_tel"];
$logo       = $rowreseau["res_logo"];
$mail       = $rowreseau["res_mail"];
$courrier   = $rowreseau["res_courrier"];
$activation = $rowreseau["res_activation"];

	// recupere les villes
$villes = Ville::getVilles();

//array logos
$filesLogoarray = array();
$filedir        = "./img/logo/";
$filesLogoarray = array_diff(scandir($filedir), array('..', '.')); //lister les logos dans le dossier
$filesLogoarray = array_values($filesLogoarray); //r&eacute;indexer le tableau après avoir enlever lignes vides
$nblogo         = count($filesLogoarray);


//Affichage -----
echo $mess ;

?>
<div class="row">
<div class="col-md-6"><form method="post" action="<?php echo $post_url; ?>" role="form">
<div class="box box-primary">
	<div class="box-header"><h3 class="box-title">Modifier les parametres du reseau</h3></div>
	
		<div class="box-body">
	<div class="form-group">
		<label >Nom du r&eacute;seau *: </label>
   		<input type="text" name="nomreseau" value="<?php echo $nom;?>" class="form-control"></div>
     	<div class="form-group">
     		<label >Adresse *: </label>
    		<textarea name="adressereseau" rows="3" class="form-control"><?php echo $adresse;?></textarea></div>
	<div class="form-group">
    		<label >Ville *:</label>
   		 <select name="villereseau" class="form-control" >
		<?php
			foreach ($villes AS $ville)
			{
				if ($idVille == $ville->getid())
				{
					echo "<option value=\"".$ville->getId()."\" selected>".$ville->getNom()."</option>";
				} else {
					echo "<option value=\"".$ville->getId()."\">".$ville->getNom()."</option>";
				}
			}
		?>
		</select></div>
		
		 <div class="input-group"><div class="input-group-addon"><i class="fa fa-phone"></i></div>
    		<input name="telreseau" type="text" class="form-control" value="<?php echo $telephone;?>" data-inputmask='"mask": "0112345678"' data-mask/></div>
			<br>
			
			 <div class="input-group"><div class="input-group-addon"><i class="fa fa-envelope"></i>*</div>
    		<input name="mailreseau" type="text" class="form-control" value="<?php echo $mail;?>" ></div>
				
</div></div></div>				
				
	
<div class="col-md-6">	
<div class="box box-primary"><div class="box-body">
   <div class="form-group">
		<label >Activer les fonctionnalit&eacute;s r&eacute;seau ? </label>
		<?php 
		if($activation==0){ 
			echo ' <input type="radio" name="activation" value="0"  checked/> Non
							<input type="radio" name="activation" value="1"  /> Oui';
		} else {
			echo '<input type="radio" name="activation" value="0"  /> Non 
						<input type="radio" name="activation" value="1"  checked /> Oui';
		}
		?>
			
		</div>
		
		<div class="form-group">
		<label >Activer l'adresse r&eacute;seau sur les courriers ? </label>
		<?php 
		if($courrier==0){ 
			echo ' <input type="radio" name="courriers" value="0"  checked/> Non
							<input type="radio" name="courriers" value="1"  /> Oui';
		} else {
			echo '<input type="radio" name="courriers" value="0"  /> Non 
						<input type="radio" name="courriers" value="1"  checked /> Oui';
		}
		?>
		
		</div>
		 
	 <div class="form-group"><label>Le logo actuel du reseau </label>
	 <?php 
			if ($logo==""){
				echo  '<img src="./img/logo/logo.png" width="120px">';
				
			} else {
			
				echo '<img src="'.$filedir.$logo.'" width="120px" >' ;
				}
			?>
		<p class="help-block">(redimensionner votre logo en png, jpeg ou gif en taille 220x50px)</p>
		
		<label>Autre logo ?</label>
		
		 <?php 
	
	 for ($l=0;$l<$nblogo;$l++){
			 if (strcmp($logo,$filesLogoarray[$l])==0)  { 
				$check = "checked"; 
				} else {
				$check = ''; 
				}
			echo "<img src=".$filedir.$filesLogoarray[$l]." width=\"120px\">&nbsp;<input type=\"radio\" name=\"logoreseau\" value=".$filesLogoarray[$l]."  ".$check.">";
			
			
			}
	 		
	
	 ?>
		</div>
		
		
		
    </div>
     <div class="box-footer"><button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button></div>


</div></div>

</div>
</form>


