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

$idanim = $_GET["idanim"];
$b=$_GET["b"];


    if ($b==1)
    {   // Parametre du formulaire pour la CREATION
	
       $post_url = "index.php?a=50&b=1&idanim=".$idanim;
       $label_bouton = "Enregistrer les param&egrave;tres" ;
	   $nom=getUserName($idanim);
	   $avatar="avatar.png";
	
		
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=50&b=2&idanim=".$idanim;
        $label_bouton = "Modifier les param&egrave;tres" ;
		$row = getAnimateur($idanim);
		$nom=getUserName($idanim);
		// Information Utilisateur
		$avatar=$row["anim_avatar"];
		$epn_r=$row['id_epn'];
		$salles=explode(";",$row["id_salle"]);
		
    }
    

// recupere les espaces
$espaces = getAllepn();
   //recupere les salles
	$sallesarray = getAllsalles();
	$nbsalle=count($sallesarray);
	
//array avatars
$filesavatararray=array();
$filedir="./img/avatar/";
$filesavatararray = array_diff(scandir($filedir), array('..', '.'));
$nbavatar=count($filesavatararray);
$filesavatararray = array_values($filesavatararray); //réindexer le tableau après avoir enlever lignes vides

//Affichage -----
echo $mess ;

?>



<div class="row"><!-- left column --><div class="col-md-4">
<?php 
if ($_GET["mess"] == "ok")
{
  echo '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Mise &agrave; jour effectu&eacute;e</div>';
  
}
?>
<div class="small-box bg-blue"><div class="inner"><p>&nbsp;<br>Gestion des animateurs</p></div>
	<div class="icon"><i class="ion ion-alert"></i></div>
	<a href="index.php?a=23" class="small-box-footer"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Retour&nbsp;&nbsp;</a>
	</div>
	
<!-- Presentation-->
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Fiche animateur</h3></div>
	 <div class="box-body">
		
			<?php 
			if ($b==2){
				echo '<img src="'.$filedir.$avatar.'" width="30%">' ;
				
			}else{
			
				echo '<img src="./img/avatar/default.png" width="30%">' ;
				}
			?>
			
		<div><label><?php echo $nom;?></label></div>
		
	 
	</div>
	</div> <!--box -->


</div><!-- col -->


<!-- right column --><div class="col-md-8">
<!-- div 2 : adresse-->
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Param&egrave;tres</h3></div>
	<div class="box-body">
		<form method="post" action="<?php echo $post_url; ?>" role="form">	
	<div class="form-group"><label>EPN de rattachement *</label>
  		<select name="epn_r" class="form-control" >
		<?php
			foreach ($espaces AS $key=>$value)
			{
				if ($epn_r == $key)
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
		
	<div class="form-group"><label>Salles *</label>
	<p >Indiquez la (ou les) salle(s) de la consultation internet qui sera surveill&eacute;e par l'animateur</p>
  	 <?php 
     
	for ($x=1;$x<=$nbsalle;$x++){
	
	if (in_array($x,$salles))  { 
	$check = "checked"; 
	} else {
	$check = ''; 
	}
		echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"salle_r[]\" value=".$x."  ".$check.">&nbsp;".$sallesarray[$x]."&nbsp;(".getEpnSalle($x).") </div>";
	}
	 ?>
    
    </div>
	<p>Choisissez parmi ces avatars</p>
	 <div class="form-group">
	 <?php 
	
	 for ($v=0;$v<$nbavatar;$v++){
			 if (strcmp($avatar,$filesavatararray[$v])==0)  { 
				$check = "checked"; 
				} else {
				$check = ''; 
				}
			echo "<img src=".$filedir.$filesavatararray[$v]." width=\"60px\" height=\"60px\">&nbsp;<input type=\"radio\" name=\"avatar_r\" value=".$filesavatararray[$v]."  ".$check.">&nbsp;&nbsp;&nbsp;";
			
			}
	 		
	
	 ?>
	</div>
	
	
</div>
<div class="box-footer">
		<input type="submit" value="<?php echo $label_bouton ;?>" name="submit" class="btn btn-success">
		</div>
	</form>

</div></div>



