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
 2012 florence DAUVERGNE

 include/user_accueil.php V0.1
 Formulaire de mise à jour de version, détection et modifications dans la base de donnees
*/
 $maj=$_GET["maj"];
 

 if($maj==0){
 $urlredirect="index.php";
 $bouton="Retour &agrave; l'accueil";
 }else{
 $urlredirect="index.php?a=61";
 $bouton="Faire la mise &agrave; jour" ;
 }
 
?>
<div class="row"><div class="col-md-6">
 <div class="box box-danger"><div class="box-header"> 
	<i class="fa fa-warning"></i><h3 class="box-title">Sauvegarde de votre base de donn&eacute;es</h3></div>
	<div class="box-body">
	
	<?php
	
	$error="Initialisation";	
	
	
	//Sauvegarde de la base actuelle en fichier zippe
$bdd=backupbdd();
	//$bdd=TRUE;
		

if ($bdd==TRUE){
	echo '<p>* La base de donn&eacute;es a &eacute;t&eacute; sauvegard&eacute;e sur votre serveur. </p><br>
				<p>Pour r&eacute;cup&eacute;rer le fichier g&eacute;n&eacute;r&eacute;, ouvrez le dossier /sql de l\'application</p>
				';
}else{
		echo 'Impossible de faire la sauvegarde, veuillez v&eacute;rifier que votre base est accessible et ouverte en &eacute;criture !';
		$error.="mise &agrave; jour impossible, base sql inaccessible" ;
	}

if($error!=""){		gFilelog($error,"savebdd_".date('Y-m-d').".txt");	}


?>
	

	</div>
	<div class="box-footer">
		<a href="<?php echo $urlredirect; ?>"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> <?php echo $bouton; ?></button></a></div>

	</div>
</div>
</div>




