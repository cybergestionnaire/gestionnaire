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
 2012 Florence DAUVERGNE

*/

// Formulaire de creation ou de modification d'un adherent

$id_user = $_GET["iduser"];
$type=$_GET["type"];
$b=$_GET["b"];

    if (FALSE == isset($id_user))
    {   // Parametre du formulaire pour la CREATION
	
	    $post_url = "index.php?a=1&b=1&act=1";
		$date     = date("Y-m-d");
        $label_bouton = "Cr&eacute;er l'adh&eacute;rent" ;
		$loginn="";
		$testb=1;
	
		
    }
    else
    {
    
	if(isset($_GET["sim"])){
	
	//
	//passer les infos pour le creer similaire
	
	$rowsim=getUser($_GET["iduser"]);
	$post_url = "index.php?a=1&b=1&act=1";
    $date     = date("Y-m-d");
    $label_bouton = "Cr&eacute;er l'adh&eacute;rent" ;
	$nom=stripslashes($rowsim["nom_user"]);
	$prenom="";
	$sexe     =  "";
	$jour     =  "";
	$mois     = "";
	$annee    = "";
	$adresse  =  stripslashes($rowsim["adresse_user"]);
	$ville    =  $rowsim["ville_user"];
	$tel      =  $rowsim["tel_user"];
	$mail     =  $rowsim["mail_user"];
	$loginn    = "";
	$statuss   =  $rowsim["status_user"]; 
	$csp   =  "";
	$equipementarr     =  $rowsim["equipement_user"];
	$equipement=array_map('intval',explode("-",$equipementarr));
	$utilisation     =  $rowsim["utilisation_user"];
	$connaissance     =  $rowsim["connaissance_user"];
	$info     =  stripslashes($rowsim["info_user"]);
	$tarif="";
	$epn=$rowsim["id_epn"];
	$newsletter=$rowsim["newsletter_user"];
          
	
	}else{
	  // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=1&b=2&act=2&iduser=".$id_user;
        $label_bouton = "Modifier l'adh&eacute;rent" ;
	$row = getUser($id_user);
	
	// Information Utilisateur
        $date     =  $row["date_insc_user"];
		$dateRen= $row["dateRen_user"];
        $nom      = stripslashes( $row["nom_user"]);
        $prenom   = stripslashes( $row["prenom_user"]);
        $sexe     =  $row["sexe_user"];
        $jour     =  $row["jour_naissance_user"];
        $mois     =  $row["mois_naissance_user"];
        $annee    =  $row["annee_naissance_user"];
        $adresse  =  stripslashes($row["adresse_user"]);
        $ville    =  $row["ville_user"];
        $tel      =  $row["tel_user"];
        $mail     =  $row["mail_user"];
        $rowtemps    =  getTransactemps($id_user);
        $temps=$rowtemps["id_tarif"];
        $loginn    = stripslashes( $row["login_user"]);
        $statuss   =  $row["status_user"]; 
		$csp   =  $row["csp_user"];
		$equipementarr     =  $row["equipement_user"];
		$equipement=array_map('intval',explode("-",$equipementarr));
		$utilisation     =  $row["utilisation_user"];
        $connaissance     =  $row["connaissance_user"];
        $info     =  stripslashes($row["info_user"]);
        $tarif=$row["tarif_user"];
		$epn=$row["id_epn"];
		$newsletter=$row["newsletter_user"];
		
		//coordonnees de l'espace
		$arraymail=getMailInscript();

		if (FALSE==$arraymail){
			
			$mailok=0;

		}else{
		$espacearray=mysqli_fetch_array(getEspace($_SESSION["idepn"]));
		$mail_epn=$espacearray["mail_espace"];
		$adresse_epn=$espacearray["adresse"];
		$nom_epn=$espacearray["nom_espace"];
		$tel_epn==$espacearray["tel_espace"];

		$arraymailtype=array(
		1=>"Introduction",
		2=>"Sujet/object",
		3=>"Corps du texte",
		4=>"Signature"
		);

		$mail_subject=$arraymail[2];
		$mail_body1=$arraymail[3];
		$mail_signature=$arraymail[4];

		$mail_body=$mail_body1." \r\n\r\n identifiant : ".$loginn."     Mot de passe : [indiquez le mot de passe de la personne]    \r\n  ".$mail_signature." \r\n\r\n".$nom_epn." \r\n".$adresse_epn." \r\n".$tel_epn.".";

		$mailok=1;
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
$town = getAllCityname();

//recupere la csp -- Ajout
$profession = getAllCsp();

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
$tarifs=getTarifsbyCat(2);
//recuperation des tarifs pour la consultation internet
$tariftemps=getTarifsTemps();

// retrouver les espaces
$espaces = getAllepn();

//modif creation uniquement des actifs/inactifs
$state = array(
           1=> "Actif",
           2=> "Inactif",
	       6=> "Archiv&eacute; (statistique)"
        );


//Affichage -----
$mesno=$_GET["mesno"];
if($mesno!=''){
	echo geterror($mesno);
}

?>


<div class="row">

<!-- colonne gauche -->
<div class='col-md-4'>
<?php
if ($b==2){

?>

  <div class="box box-primary"><div class="box-header"><h3 class="box-title">Actions</h3></div>
	 <div class="box-body">
	
	<a href="index.php?a=1&b=1&sim=1&iduser=<?php echo $id_user; ?>" class="btn btn-app bg-green"><i class="ion ion-person-add"></i>Cr&eacute;er similaire</a>
 <?php
		if($_SESSION['status']==4 )
		{	
		echo "<a href=\"".$_SERVER['REQUEST_URI']."&act=del\" class=\"btn btn-app bg-red\"><i class=\"fa fa-trash-o\"></i>Supprimer</a> ";
		}

		if(isset($id_user) AND $_GET["sim"]==''){ ?>
		  <a href="courriers/fiche.php?user=<?php echo $id_user ?>&epn=<?php echo $_SESSION["idepn"]?>" target="_blank" class="btn btn-app bg-blue" ><i class="fa fa-print"></i> Imprimer la fiche</a>
		 <?php
		
		   //Bouton d'envoi de mail de rappel
		 if($mailok==1){
			if($mail!=""){
		
		?>
		<a href="mailto:<?php echo $mail; ?>?SUBJECT=<?php echo $mail_subject; ?>&BODY=<?php echo $mail_body; ?>">
		<button class="btn btn-app bg-navy"><i class="fa fa-paper-plane"></i> Mail Id/Passw </button></a>
		<?php } 
		}
		}
		?>
		  
	
	
	</div></div>	

			

<?php 
}
?>

<form method="post" action="<?php echo $post_url; ?>" role="form">
<!-- div 4 : infos diverses-->	
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Informations compl&eacute;mentaires</h3></div>
	<div class="box-body">
	 <div class="form-group">
		<label>&Eacute;quipement personnel&nbsp;</label>
	 <?php
	
	for ($x=0;$x<5;$x++){
	
		if (in_array($x,$equipement))  { 
		$check = "checked"; 
		} else {
		$check = ''; 
		}
		
		echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"equipement[]\" value=".$x."  ".$check." >&nbsp;".$equipementarray[$x]."</div>";
	}
    ?>
	</div>
	<div class="form-group">
		<label>Connexion internet</label>
	 <?php
	  
		for ($x=5;$x<8;$x++){
		
		if (in_array($x,$equipement))  { 
		$check = "checked"; 
		} else {
		$check = ''; 
		}
		
		echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"equipement[]\" value=".$x."  ".$check.">&nbsp;".$equipementarray[$x]."</div>";
	}
    ?>
	</div>
	
	 <div class="form-group">
		<label>Lieu d'utilisation d'internet</label>
			 <?php
			foreach ($utilisationarray AS $keyutil=>$valueutil)
			{
			    if (strcmp ($utilisation,$keyutil)==0)
			    {
		       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=".$keyutil."  class=\"minimal\" checked>&nbsp;".$valueutil."  </label></div>";
			    }
			    else
			    {
		       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=".$keyutil." class=\"minimal\">&nbsp;".$valueutil." </label></div> ";
			    }
			}
		    ?>
	</div>
	<div class="form-group">
		<label>Le niveau en informatique</label>
		 <?php
        foreach ($connaissancearray AS $key=>$valuecon)
        {
            if (strcmp ($connaissance,$key)==0)
            {
       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=".$key." checked>&nbsp;".$valuecon."</label></div>";
            }
            else
            {
       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=".$key.">&nbsp;".$valuecon."</label></div>";
            }
        }
    ?>
    	</div>
    	
	
	</div><!-- box body -->
	
	
	
	</div><!--/box -->

</div><!-- ./col -->





<!--Colonne droite--> <div class="col-md-8">
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Fiche adh&eacute;rent</h3>
	<?php if($b==1){ ?>
	&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour mettre une photo, placez-la dans le dossier img/photo_profil, nommez-la en respectant la r&egrave;gle suivante : nomcompos&eacute;_prenom. Attention case sensitive !"><i class="fa fa-info"></i></small>
	<?php } ?>
	<?php if($row["status_user"]==6) { echo '&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-red">Adh&eacute;rent archiv&eacute; ! </small>'; } ?>
<?php 
if ($b==2){ ?>
	<div class="box-tools pull-right">
		<a href="index.php?a=6&iduser=<?php echo $id_user; ?>" class="btn  bg-yellow"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>					
		<a href="index.php?a=5&b=6&iduser=<?php echo $id_user; ?>" class="btn  bg-blue"  data-toggle="tooltip" title="Participation Ateliers"><i class="fa fa-keyboard-o"></i></a>					
		<a href="index.php?a=9&iduser=<?php echo $id_user; ?>" class="btn bg-maroon"  data-toggle="tooltip" title="Consultation internet"><i class="fa fa-globe"></i></a>
		<a href="index.php?a=21&b=1&iduser=<?php echo $id_user; ?>" class="btn bg-navy"  data-toggle="tooltip" title="Compte d'impression"><i class="fa fa-print"></i></a>
	</div>
<?php } ?>
</div>
	<div class="box-body no-padding">
       <table class="table table-condensed">
	 <tr><td width="50%">
		<table>
		<tr><td>
			<?php 
			
			if (FALSE != isset($id_user)){
				if(isset($_GET["sim"])){ //creer similaire
				echo '<img src="img/avatar/default.png" width="115px" class="img-circle">' ;
				}else{
				// tout le monde
				//detection existance fichier image pour la photo
					//enlever les espaces
					$nomSE=str_replace(CHR(32),"",$nom);
					$prenomSE=str_replace(CHR(32),"",$prenom);
					$filename = "img/photos_profil/".trim($nomSE)."_".trim($prenomSE).".jpg" ;
					if (file_exists($filename)) {
							echo  '<img src='.$filename.' width="115px" hspace="5" class="img-circle">';
						} else {
							//avatar pour personnes sans image					
							if ($sexe =="F"){
							echo '<img src="img/avatar/female.png" class="img-circle"  width="115px">' ;
							} else {
							echo '<img src="img/avatar/male.png" class="img-circle"  width="115px">' ;
							}
						}
					}
					
			}else{
			// creation avatar par defaut
			echo '<img src="img/avatar/default.png" width="60%">' ;
			}
				
			?>
			</td>
		<td>
	<div class="form-group"><label>Nom * :</label>
		<input type="text" name="nom" value="<?php echo $nom;?>" class="form-control"></div>
		
	<div class="form-group"><label>Pr&eacute;nom * :</label>
		<input type="text" name="prenom" value="<?php echo $prenom;?>" class="form-control"></div>
		
	<div><label>Sexe *:&nbsp;</label>
		<?php
			if (FALSE != isset($id_user))
			{
				
				if ($sexe =="F")
				{
				echo '<input type="radio" name="sexe" value="H">&nbsp;Homme&nbsp;&nbsp;
				<input type="radio" name="sexe" value="F" checked >&nbsp;Femme';
				}
				else
				{
				echo '<input type="radio" name="sexe" value="H" checked >&nbsp;Homme&nbsp;&nbsp;
				<input type="radio" name="sexe" value="F">&nbsp;Femme ';
				
				}
			}
			else
			{
			echo '<input type="radio" name="sexe" value="H" >&nbsp;Homme&nbsp;&nbsp;
			<input type="radio" name="sexe" value="F" >&nbsp;Femme ';
			
			}
		?>	
			</div>
				
	
			
			</td>
			
		</tr>
			
	   <tr><td colspan="2">
	   	<div class="form-group"><label>Date de Naissance *:</label>
			<table class="table" border="0">
			<tr><td>
			<select name="jour" tabindex="1" class="form-control">
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
					?></select></td>
				<td>
			<select name="mois" tabindex="2" class="form-control">
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
			?></select></td>
		   
			<td><input type="text" name="annee" tabindex="3" maxlength="4" value="<?php echo $annee;?>" size="2" class="form-control"></td></tr></table>
	 </div><!-- form group-->
	 </td></tr></table>
	 
	 </td>
	 
	 <td width="50%">
	 <div class="form-group"><label>Adresse *:</label>
		<textarea name="adresse" class="form-control" tabindex="4"><?php echo $adresse;?></textarea></div>
		
	<div class="form-group"><label>Ville *:</label>
		<select name="ville" class="form-control" tabindex="5">
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
		
	<div class="input-group"><span class="input-group-addon" tabindex="6"><i class="fa fa-phone"></i></span>
		<input type="tel" name="tel" value="<?php echo $tel;?>" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control" placeholder="01549875631" maxlength="10"/></div><br>
		
	<div class="input-group"><span class="input-group-addon" tabindex="7"><i class="fa fa-envelope"></i></span>
		<input type="email" name="mail" value="<?php echo $mail;?>" class="form-control"></div>
	 </td></tr></table>
	 
	</div>
	</div> <!--box -->
<!-- div 1 : /vie-->


<!-- div 3 : donnees base-->	
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Donn&eacute;es pour la base</h3></div>
<div class="box-body no-padding">
       <table class="table table-condensed">
	 <tr><td width="45%">
	
	<div class="form-group"><label for="exampleInputEmail1" tabindex="8">Login *</label>
		<input type="text" name="login" value="<?php echo $loginn;?>" class="form-control"></div>

	<div class="form-group"><label for="exampleInputPassword1" tabindex="9">Mot de passe </label>
    		<input type="text" name="passw" value="" class="form-control"></div>
	
	 <div class="row"><div class="col-xs-5">
	<div class="form-group"><label>Date de 1&egrave;re d'inscription</label>
		<input type="text" name="inscription" value="<?php echo $date;?>" class="form-control" <?php if($b==2){ echo 'disabled';}?>></div>
					</div>
		<?php 	if($b==2){	?>
		<div class="col-xs-5">
	<div class="form-group"><label>Date de renouvellement</label>
		<input type="text" name="renouvellement" value="<?php echo $dateRen;?>" class="form-control" <?php if($b==2){ echo 'disabled';}?>></div>
					</div> <?php } ?>
					</div>
	
	<div class="form-group"><label>Tarif de la consultation internet
	&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour modifier le tarif de la consultation, passez par les abonnements."><i class="fa fa-info"></i></small></label>
	<?php if ($b==2){$disabled="disabled";}else{$disabled="";} ?>
  	<select name="temps" class="form-control" <?php echo $disabled; ?> >
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
  	
  	<div class="form-group"><label>Tarif de l'adh&eacute;sion
  	&nbsp;&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour modifier le tarif de l'adh&eacute;sion, passez par les abonnements."><i class="fa fa-info"></i></small></label>
  	<?php if ($b==2){$disabled="disabled";}else{$disabled="";} ?>
  		<select name="tarif" class="form-control" <?php echo $disabled; ?> >
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
  	
</td>
<td></td>
<td width="50%">
		
	<div class="form-group"><label>Epn d'inscription </label>
		<select name="epn" class="form-control" ><?php
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
		?></select></div>
	
	</div>
	
	<div class="form-group"><label>Statut </label>
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
	
	<div class="form-group"><label>Notes</label><textarea  class="form-control" rows="3" name="info"><?php echo $info;?></textarea>	</div>
	
		
	 <div class="checkbox">
		<label>
		<?php 
		if($newsletter==0){ 
			echo ' <input type="checkbox" name="newsletter" value=""  />';
		}else{
			echo ' <input type="checkbox" name="newsletter" value=""  checked />';
		}
		?>
		<b>Newsletter</b></label></div>
	
	
	
	</td></tr></table>
</div>
<!-- div 2 : /adresse-->


<div class="box-footer">
		<?php 
		/*if ($_GET['type']=='anim' OR $_POST['type']=='anim')
		{
		echo '<input type="hidden" name="type" value="anim" />';
		}	*/	
		?>
		
		<input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success"></form>
		<!-- Bouton annuler revient aux resas direct-->
		<a href="index.php?m=3"><input type="submit" value="Annuler" name="submit" class="btn btn-default"></a>
		
		
		
		</div>
		
 </div><!-- /box-->
</div><!-- /col -->









</div><!-- ./row -->
