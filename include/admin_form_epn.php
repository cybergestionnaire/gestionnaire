<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

  include/admin_form_espace.php V0.1
*/

// formulaire de creation / modification d'espace
                            
  $id_espace = $_GET["idespace"];
$b=$_GET["b"];


    if (FALSE == isset($id_espace))
    {   // Parametre du formulaire pour la CREATION
        $post_url = "index.php?a=43&b=1&act=1";
        $label_bouton = "Cr&eacute;er l'Espace" ;
			$logo="logo.png";
			$forfait=0;
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=43&b=2&act=2&idespace=".$id_espace;
        $label_bouton = "Modifier l'espace" ;
        $resultespace = getEspace($id_espace);
				$row = mysqli_fetch_array($resultespace);

        //Informations matos
        $nom     = $row["nom_espace"];
        $adresse  = $row["adresse"];
        $ville = $row["id_city"];
				$telephone=$row["tel_espace"];
				$fax=$row["fax_espace"];
				$logo=$row["logo_espace"];
				$couleur=$row["couleur_espace"];
				$mail=$row["mail_espace"];
    }

	// recupere les villes
$town = getAllCityname();

//array logos
$filesLogoarray=array();
$filedir="./img/logo/";
$filesLogoarray = array_diff(scandir($filedir), array('..', '.')); //lister les logos dans le dossier
$filesLogoarray = array_values($filesLogoarray); //r&eacute;indexer le tableau après avoir enlever lignes vides
$nblogo=count($filesLogoarray);

//tableau des couleurs
$couleurArray=array(
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

//Affichage -----
echo $mess ;

?>
<div class="row">
<div class="col-md-6"><form method="post" action="<?php echo $post_url; ?>" role="form">
<div class="box box-primary">
	<div class="box-header"><h3 class="box-title">Cr&eacute;er et modifier un nouvel espace</h3></div>
	
		<div class="box-body">
	<div class="form-group">
		<label >Nom de l'EPN *: </label>
   		<input type="text" name="nom" value="<?php echo $nom;?>" class="form-control"></div>
     	<div class="form-group">
     		<label >Adresse : </label>
    		<textarea name="adresse" rows="3" class="form-control"><?php echo $adresse;?></textarea></div>
	<div class="form-group">
    		<label >Ville *:</label>
   		 <select name="ville" class="form-control" >
		<?php
			foreach ($town AS $key=>$value)
			{
				if ($ville == $key)
				{
					echo "<option value=\"".$key."\" selected>".$value."</option>";
				}
				else
				{
					echo "<option value=\"".$key."\">".$value."</option>";
				}
			}
		?>
		</select></div>
		
		 <div class="input-group"><div class="input-group-addon"><i class="fa fa-phone"></i></div>
    		<input name="telephone" type="text" class="form-control" value="<?php echo $telephone;?>" data-inputmask='"mask": "0112345678"' data-mask/></div>
			<br>
			
			 <div class="input-group"><div class="input-group-addon"><i class="fa fa-fax"></i></div>
    		<input name="fax" type="text" class="form-control" value="<?php echo $fax;?>" data-inputmask='"mask": "0112345678"' data-mask/></div>
    		<br>
    		 <div class="input-group"><div class="input-group-addon"><i class="fa fa-envelope"></i>*</div>
    		<input name="mail" type="text" class="form-control" value="<?php echo $mail;?>" ></div>
				
</div></div></div>				
				
	
<div class="col-md-6">	
<div class="box box-primary"><div class="box-body">
    <div class="form-group">
    		<label >Une couleur:</label>
   		 <select name="ecouleur" class="form-control">
		 <?php
			foreach ($couleurArray AS $key=>$value)
			{
				if ($couleur == $key)
				{
					echo "<option value=\"".$key."\" selected class=\"text-".$value."\">".$value."</option>";
				}
				else
				{
					echo "<option value=\"".$key."\" class=\"bg-".$value."\">".$value."</option>";
				}
			}
		?>
		 </select></div>
		 
	 <div class="form-group"><label>Le logo actuel de l'espace </label>
	 <?php 
			if ($b==2){
				echo '<img src="'.$filedir.$logo.'" >' ;
				
			}else{
			
				echo '<img src="./img/logo/logo.png" >' ;
				}
			?>
		<p class="help-block">png, jpeg ou gif, taille 220x50px.</p>
		
		<label>Autre logo ?</label>
		<p class="help-block"></p>
		 <?php 
	
	 for ($l=0;$l<$nblogo;$l++){
			 if (strcmp($logo,$filesLogoarray[$l])==0)  { 
				$check = "checked"; 
				} else {
				$check = ''; 
				}
			echo "<img src=".$filedir.$filesLogoarray[$l].">&nbsp;<input type=\"radio\" name=\"elogo\" value=".$filesLogoarray[$l]."  ".$check.">";
			
			
			}
	 		
	
	 ?>
		</div>
		
		
		
    </div>
     <div class="box-footer"><button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button></div>


</div></div>

</div>
</form>


