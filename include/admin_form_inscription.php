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
 

  include/admin_form_user.php V0.1
*/

// Formulaire de creation ou de modification d'un adherent

$id_user = $_GET["iduser"];

// Parametre du formulaire pour la MODIFICATION
//$post_url = "index.php?a=24&b=1&act=1&iduser=".$id_user;
$label_bouton = "Valider l'inscription" ;

$row = getUserInsc($id_user);
// Information Utilisateur

	$date     =  $row["date_inscription_user"];
	$nom      =  $row["nom_inscription_user"];
	$prenom   =  $row["prenom_inscription_user"];
	$sexe     =  $row["sexe_inscription_user"];
	$jour     =  $row["jour_naissance_inscription_user"];
	$mois     =  $row["mois_naissance_inscription_user"];
	$annee    =  $row["annee_naissance_inscription_user"];
	$adresse  =  $row["adresse_inscription_user"];

	$codepostal = $row["code_postal_inscription"];
	$commune = $row["commune_inscription_autres"];

	$ville    =  $row["ville_inscription_user"];
	$pays    =  $row["quartier_inscription_user"];

	$status   =  $row["status_inscription_user"]; 
	$tel      =  $row["tel_inscription_user"];
	$telport  =  $row["tel_port_inscription_user"];
	$mail     =  $row["mail_inscription_user"];
	$csp     =  $row["csp_inscription_user"];
	$equipementarr     =  $row["equipement_inscription_user"];
		$equipement=array_map('intval',explode("-",$equipementarr));

	$utilisation     =  $row["utilisation_inscription_user"];
	$connaissance     = $row["connaissance_inscription_user"];
	$info     =  $row["info_inscription_user"];
	$loginn    =  $row["login_inscription_user"];
	$passw    =  $row["pass_inscription_user"];
	$dernierevisit    =  $row["lastvisit_inscription_user"];
	$epn=$row["id_inscription_computer"];
    
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
               12=> "D&eacute;cembre",
        );
		


// type d'equipement defini
$equipementarray = array (
         0 => "Aucun &eacute;quipement",   
         1 => "Ordinateur",
         2 => "Tablette",
		 3 => "Smartphone",
		 4 => "T&eacute;l&eacute;vision connect&eacute;e",
		5 => " Internet &agrave; la maison (ADSL, satellite ou fibre)",
		6 => " Internet mobile (3G, 4G,...)",
		7 => "Pas de connexion Internet"
		);
		
		// type d'utilisation d&eacute;fini
$utilisationarray = array (
         0 => "Aucun lieu", 
         1 => "A la maison",   
         2 => "Au bureau ou &agrave; l'&eacute;cole",
         3 => "A la maison et au bureau ou &agrave; l'&eacute;cole"
);
		
		// type de connaissance d&eacute;fini
$connaissancearray = array (
         0 => "D&eacute;butant",   
        1 => "Interm&eacute;diaire",
         2 => "Confirm&eacute;"
);

// recupere les villes
$town = getAllCityname();
$resultville=array_put_to_position($town, 'Autre commune', 0,'0' );

//tarif connexion internet
 $rowtemps    =  getTransactemps($id_user);
$temps=$rowtemps["id_tarif"];
$tariftemps=getTarifsTemps();

// Les status
$state = array(
               1=> "Actif",
               2=> "Inactif",
               3=> "Animateur",
               4=> "Administrateur"
        );
		
//recuperation des tarifs categorieTarif(2)=adhesion
$tarifs=getTarifsbyCat(2);
//recupere la csp -- Ajout
$profession = getAllCsp();
// retrouver les espaces
$espaces = getAllepn();

//Affichage -----
echo $mess ;



?>
<form name="formcompte" method="post" action="">
<!-- infos utilisateur -->
<div class="row"> <div class="col-lg-5">
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Informations personnelles</h3></div>
	 <div class="box-body">
<div class="form-group"><label>Date d'inscription :</label><input type="hidden" name="date_inscription" value="<?php echo $date;?>">
    <p><?php echo $row["date_inscription_user"] ; ?></p></div>
<div class="form-group"><label>Civilit&eacute; :</label>
    
    <?php
    if (FALSE != isset($id_user))
    {
        if ($sexe =="F")
        {
			?>
        	<div class="radio icheck"><input type="radio" name="sexe" value="H">Monsieur&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="sexe" value="F" checked>Madame</div>
        	<?php 
		}
        else
        {
			?>
        	<input type="radio" name="sexe" value="H" checked>Monsieur&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="sexe" value="F">Madame
        	<?php
        }
    }
    else
    {
		?>
    	<input type="radio" name="sexe" value="H" checked>Monsieur&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="sexe" value="F">Madame
    	<?php
    }
    ?>
    </div>
<div class="form-group"><label>Nom :</label>
    <input type="text" name="nom" value="<?php echo $nom;?>" onChange="javascript:this.value=this.value.toUpperCase();" class="form-control"></div>
<div class="form-group"><label>Pr&eacute;nom :</label>
    <input type="text" name="prenom" value="<?php echo $prenom;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();" class="form-control"></div>

<div class="form-group"><label>Date de naissance :</label>
	<select name="jour">
        <?php
        for ($i=1 ; $i<32 ; $i++)
        {
            if ($i == $jour)
            {
                echo "<option value=\"".$i."\" selected>".$i."</option>";
            }
            else
            {
                echo "<option value=\"".$i."\">".$i."</option>";
            }
        }
        ?>
    </select>
    <select name="mois">
    <?php
        foreach ($month AS $key=>$value)
        {
            if ($mois == $key)
            {
                echo "<option value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option value=\"".$key."\">".$value."</option>";
            }
        }
    ?>


    </select>
    <input type="text" name="annee" maxlength="4" value="<?php echo $annee;?>" style="width:50px;"></div>

<div class="form-group"><label>Adresse :</label>
    <textarea name="adresse" class="form-control"><?php echo $adresse;?></textarea></div>

    <div class="form-group"><label>Ville :</label>
     <select name="ville" class="form-control">
    <?php
   
	foreach ($resultville AS $key=>$value)
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
    <?php
    if($ville==0)
	{	
		?>
		 <div class="form-group"><label>Ajout d'une ville non r&eacute;pertori&eacute; :</label>
		 <small class="badge bg-blue" data-toggle="tooltip" title="Si ces cases sont toutes remplies une nouvelle ville sera ajout&eacute;e dans la liste"><i class="fa fa-info"></i></small>
		 <div class="row">	
 <div class="col-xs-5"><input type="text" placeholder="Code postal" style="width: 100px;" name="codepostal" value="<?php echo $codepostal;?>" class="form-control"/></div>
	<div class="col-xs-6"><input type="text" maxlength="50" placeholder="Autre Commune" style="width: 150px;" name="commune" value="<?php echo $commune;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();" class="form-control"/></div>
	</div></div>
	
	 <div class="form-group">	<input type="text" maxlength="50" placeholder="Pays" style="width: 150px;" name="pays" class="form-control" value="<?php echo $pays;?>" onchange="this.value = this.value.toUpperCase();"/></div>
		
   <?php
	}
	
	?>

<div class="form-group"><label>T&eacute;l&eacute;phone Fixe :</label><input type="text" name="tel" value="<?php echo $tel;?>" class="form-control"></div>
<div class="form-group"><label>T&eacute;l&eacute;phone Portable :</label><input type="text" name="telport" value="<?php echo $telport;?>" class="form-control"></div>
<div class="form-group"><label>E-Mail :</label><input type="text" name="mail" value="<?php echo $mail;?>" class="form-control"></div>

<div class="form-group"><label>Tarif de la consultation internet
	&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Par defaut sans tarif, &agrave; changer selon vos propres tarifs"><i class="fa fa-info"></i></small></label>
	
  	<select name="temps" class="form-control" >
		<?php
			foreach ($tariftemps AS $key=>$value)
			{
				if ($temps == $key)
				{
					echo "<option value=\"".$key."\" selected>".$value."</option>";
				}
				else
				{
					echo "<option value=\"".$key."\">".$value."</option>";
				}
			}
		?>
		</select>
  		</div>

<div class="form-group"><label>Statut :</label>
		    	<select name="status"  class="form-control">
			<?php
			 if ($id_user !="")
			{
			    foreach ($state AS $key=>$value)
			    {
				if ($statuss == $key)
				{
				    echo "<option value=\"".$key."\" selected>".$value."</option>";
				}
				else
				{
				    echo "<option value=\"".$key."\">".$value."</option>";
				}
			    }
			}
			// ajout
			else{
			    foreach ($state AS $key=>$value)
			    {
				if ($value == "Actif")
				{
				    echo "<option value=\"".$key."\" selected>".$value."</option>";
				}
				else
				{
				    echo "<option value=\"".$key."\">".$value."</option>";
				}
			    }
			}
		    ?>
    	</select></div>
		
	<div class="form-group"><label>Tarif de l'adh&eacute;sion</label>
  		<select name="tarif" class="form-control" >
		<?php
			foreach ($tarifs AS $key=>$value)
			{
				if ($tarif == $key)
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
</div>
</div>
</div>

<div class="col-lg-7">
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Information de typologie : </h3></div>
	<div class="box-body">
<div class="form-group">
		<label>&Eacute;quipement personnel&nbsp;</label>
	<?php
      for ($x=0;$x<8;$x++){
	
		if (in_array($x,$equipement))  { 
		$check = "checked"; 
		} else {
		$check = ''; 
		}
	?>	
		<div class="checkbox"><input type="checkbox" name="equipement[]" value="<?php echo $x; ?>"  <?php echo $check; ?> >&nbsp; <?php echo $equipementarray[$x]; ?></div>
<?php	}
    ?>
	</div>
	
	 <div class="form-group">
		<label>Lieu d'utilisation d'internet</label>
			 <?php
			foreach ($utilisationarray AS $keyutil=>$valueutil)
			{
			    if (strcmp ($utilisation,$keyutil)==0)
			    {
		       			echo "<div class=\"radio icheck\"><input type=\"radio\" name=\"utilisation\" value=".$keyutil." checked>&nbsp;".$valueutil."  </div>";
			    }
			    else
			    {
		       			echo "<div class=\"radio icheck\"><input type=\"radio\" name=\"utilisation\" value=".$keyutil.">&nbsp;".$valueutil." </div> ";
			    }
			}
		    ?>
	</div>
	<div class="form-group">
		<label>Le niveau en informatique</label>
		 <?php
		
		foreach ($connaissancearray AS $key=>$valuecon)
        {
          
            if ($connaissance==$key)
            {
       				echo "<div class=\"radio icheck\"><input type=\"radio\" name=\"connaissance\" value=".$key." checked >&nbsp;".$valuecon."</div>";
            }
            else
            {
       			echo "<div class=\"radio icheck\"><input type=\"radio\" name=\"connaissance\" value=".$key.">&nbsp;".$valuecon."</div>";
            }
        }
    ?>
    	</div>
    	<div class="form-group"><label>Cat&eacute;gorie Socio-professionnelle</label>
    	 <select name="csp" class="form-control">
	    <?php
		foreach ($profession AS $key=>$value)
		{
		    if ($csp == $key)
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

<div class="form-group"><label>Information compl&eacute;mentaire :</label><textarea name="info" class="form-control" rows="5"><?php echo $info;?></textarea></div>

<div class="form-group"><label>Login :</label><input type="text" name="login" value="" class="form-control"></div>
<div class="form-group"><label>Mettre/Changer le mot de passe :</label><input type="text" name="passw" class="form-control">

<div class="form-group"><label>L'epn d'inscription *:</label>
		<select name="epn" class="form-control" >
		<?php
			foreach ($espaces AS $key=>$value)
			{
				if ($epn == $key)
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

		</div>
<input type="hidden" name="iduser" value="<?php echo $id_user;?>">

</div><!-- //box body-->
<div class="box-footer"><input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success"></div>
 </div>
</form></div></div>