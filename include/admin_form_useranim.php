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
	
        $post_url = "index.php?a=51&b=1&act=1";
        $temps    = "999"; //illimite
        $date     = date("Y-m-d");
        $label_bouton = "Cr&eacute;er" ;
	$loginn="";
	//$testb=1;
		
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=51&b=2&act=2&iduser=".$id_user;
        $label_bouton = "Modifier" ;
		$row = getUser($id_user);
// Information Utilisateur
        $date     =  $row["date_insc_user"];
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
        
        $loginn    = stripslashes( $row["login_user"]);
        $statuss   =  $row["status_user"]; 
	
        $info     =  stripslashes($row["info_user"]);
      //avatar
		$rowa = getAvatar($id_user);
		$avatar=$rowa["anim_avatar"];
	
	
		
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


// Tableau des status
if ($_SESSION['status']==4 )
{//animateur et admin
$state = array(
           
               3=> "Animateur",
               4=> "Administrateur",
	         5=> "Animateur Inactif"
        );
}else if($_SESSION['status']==3){// auutres, adherents actifs et inactifs
$state = array(
            
		3=> "Animateur",
		
        );

}

//Affichage -----
echo $mess ;

?>
<!-- Supprimer un animateur -->
<div class="row">
<?php 
 if ($b==2 AND $_SESSION['status']==4){

?>
    <div class="col-lg-3 col-xs-6"><div class="small-box bg-red">
       <div class="inner"><p>&nbsp;<br>supprimer</p></div>
	   <div class="icon"><i class="ion ion-alert"></i></div>
		 <?php
			echo "<a href=\"".$_SERVER['REQUEST_URI']."&act=del\" class=\"small-box-footer\">Supprimer&nbsp;<i class=\"fa fa-arrow-circle-right\"></i></a> ";
		
		?>
         </div>
	</div><!-- ./col -->
 <?php 
}
?> 
<div class="col-lg-3 col-xs-6">
<div class="small-box bg-blue"><div class="inner"><p>&nbsp;<br>Gestion des animateurs</p></div>
	<div class="icon"><i class="ion ion-alert"></i></div>
	<a href="index.php?a=23" class="small-box-footer"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Retour&nbsp;&nbsp;</a>
	</div></div>					
</div><!-- ./row -->


<form method="post" action="<?php echo $post_url; ?>" role="form">
<div class="row"><!-- left column --><div class="col-md-6">
<!-- div 1 : vie-->
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Fiche adh&eacute;rent</h3></div>
	 <div class="box-body">
		<table class="table">
		<tr><td>
			<?php 
			if (FALSE != isset($id_user)){
				if ($statuss==3){
					echo '<img src="img/avatar/'.$avatar.'" width="80%">' ;
					}else{
						if ($sexe =="F"){
						echo '<img src="img/avatar/female.png">' ;
						} else {
						echo '<img src="img/avatar/male.png">' ;
						}
					}
			}else{
				echo '<img src="img/avatar/default.png" width="60%">' ;
				}
			?>
			</td>
		<td>
	<div class="form-group"><label>Nom * :</label>
		<input type="text" name="nom" value="<?php echo $nom;?>" class="form-control"></div>
		
	<div class="form-group"><label>Pr&eacute;nom * :</label>
		<input type="text" name="prenom" value="<?php echo $prenom;?>" class="form-control"></div>
		
	<div><label>Sexe :&nbsp;</label>
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
			</td></tr>
			
	   <tr><td colspan="2">
	   	<div class="form-group"><label>Naissance *:</label>
			<table class="table">
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
		   
			<td><input type="text" name="annee" maxlength="4" value="<?php echo $annee;?>" size="2" tabindex="3" class="form-control"></td></tr></table>
	 </div><!-- form group-->
	 </td></tr></table>
	 
	</div>
	</div> <!--box -->
<!-- div 1 : vie-->
</div>


<!-- right column --><div class="col-md-6">
<!-- div 2 : adresse-->
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Coordonn&eacute;es</h3></div>
	<div class="box-body">
	<div class="form-group"><label>Adresse *:</label>
		<textarea name="adresse" class="form-control"><?php echo $adresse;?></textarea></div>
		
	<div class="form-group"><label>Ville *:</label>
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
		
	<div class="input-group"><span class="input-group-addon"><i class="fa fa-phone"></i></span>
		<input type="text" name="tel" value="<?php echo $tel;?>" class="form-control" placeholder="04 78 34 27 31, 06 83 57 43 00"/></div><br/>
		
	<div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
		<input type="text" name="mail" value="<?php echo $mail;?>" class="form-control"></div>
	
	</div>
</div>
<!-- div 2 : adresse-->
</div></div>


<!-- div 3 : données base-->	
<div class="row"><div class="col-md-6">

<div class="box box-success"><div class="box-header"><h3 class="box-title">Donn&eacute;es pour la base</h3></div>
<div class="box-body">
	
	<div class="form-group"><label for="exampleInputEmail1">Login *:</label>
		<input type="text" name="login" value="<?php echo $loginn;?>" class="form-control"></div>

	<div class="form-group"><label for="exampleInputPassword1">Mot de passe :</label>
    		<input type="text" name="passw" value="" class="form-control"></div>
	
	<div class="form-group"><label>Date d'inscription:</label>
			<input type="text" name="inscription" value="<?php echo $date;?>" class="form-control"></div>

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
				if ($value == "Animateur")
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

	
	
	<div class="form-group">
  	 <input type="hidden" name="temps" value="999" >
	 <input type="hidden" name="utilisation" value="0" >
	 <input type="hidden" name="connaissance" value="0" >
	 <input type="hidden" name="csp" value="2" >
	  <input type="hidden" name="tarif" value="0"> 
	  <input type="hidden" name="equipement" value="0">
	   <input type="hidden" name="epn" value="1"> 
	   <input type="hidden" name="newsletter" value="0">
	  
  		</div>
  	
  	
  	<div class="form-group"><label>Notes</label><textarea  class="form-control" rows="3" name="info"><?php echo $info;?></textarea>	</div>


</div><!--/box body -->

	<div class="box-footer">
		<?php 
		if ($_GET['type']=='anim' OR $_POST['type']=='anim')
		{
		echo '<input type="hidden" name="type" value="anim" />';
		}		
		?>
		
		<input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success"></form>
		<!-- Bouton annuler revient aux resas direct-->
		</div>
	
	</div><!--/box -->
</div></div><!-- /col /row -->

