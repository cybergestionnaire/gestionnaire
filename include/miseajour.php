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
 //declencher les MAJ, verifier la version dans la tab_config
 $versionactuelle=getMajConfigVersion($_SESSION["idepn"]);
 $versionew="1.2";

 
?>
<div class="row">
		
	<?php
	
	$error='';
	
	$testmaj=$_GET["testmaj"];
	//Sauvegarde de la base actuelle en fichier zippe
	//$bdd=backupbdd();
	
	$testbdd=getLastBackup();
	
//	debug($testbdd);
if($testmaj==''){
if($testbdd==FALSE){
		?>
		<div class="col-md-6">
 <div class="box box-danger"><div class="box-header"> 
	<i class="fa fa-warning"></i><h3 class="box-title">Mise à jour de version depuis la version 1.1 vers la <?php echo $versionew; ?></h3></div>

	<div class="box-body">
		<p>Cela fait un mois que la base de donnée n'a pas été sauvegardée, cliquez sur le bouton pour la lancer avant de faire toute mise à jour !</p>
	</div>
	 
	<div class="box-footer"><a href="index.php?a=62&maj=1"><input type="submit" name="sauvegarde" value="Lancer la sauvegarde" class="btn btn-danger"></a></div>

	
		<?php   //modifications de la base
	}else{
	?>
	<div class="col-md-6">
 <div class="box box-danger"><div class="box-header"> 
	<i class="fa fa-warning"></i><h3 class="box-title">Mise à jour de version depuis la version <?php echo $versionactuelle; ?> vers la <?php echo $versionew; ?></h3></div>
		<div class="box-body"><p class="text-blue"><b>Modifications de la base de données</b></p>
<?php 
//1. ajout des tables


	$row1=Tab_ins1(); 
		if ($row1=="echec"){
				$error.= "Echec impossible Modification de la table des preinscriptions (1)"." \r\n"; 
			echo "<p>* Modification de la table des preinscriptions (1) : echec</p>";
		}else{
		$current = 0;
		echo '<p>Modification de la table des preinscriptions (1)</p>';
		}
		
	$row2=Tab_ins2();
		if ($row2=="echec"){
				$error.= "Echec impossible Modification de la table des preinscriptions (2)"." \r\n"; 
			echo "<p>* Modification de la table des preinscriptions (2) : echec</p>";
		}else{
		$current = 0;
		echo '<p>Modification de la table des preinscriptions (2)</p>';
		}
	
	$row3=alterMessageMAJ();
		if ($row3=="echec"){
				$error.= "Echec impossible de Modication de la table des message"." \r\n"; 
			echo "<p>* Modication de la table des messages : echec</p>";
		}else{
		$current = 0;
		echo '<p>Modication de la table des messages</p>';
		}
	
		$row4=createtabinscriptMAJ();
		if ($row4=="echec"){
				$error.= "Echec Creation de la table de validation des preinscriptions"." \r\n"; 
			echo "<p>* Creation de la table de validation des preinscriptions: echec</p>";
		}else{
		$current = 0;
		echo '<p>Creation de la table de validation des preinscriptions</p>';
		}
	
	
		$row5=insertCapt();
		if ($row5=="echec"){
				$error.= "Echec impossible Insertion de donnees dans la table"." \r\n"; 
			echo "<p>* Insertion de donnees dans la table: Echec</p>";
		}else{
		$current = 0;
		echo '<p>Insertion de donnees dans la table</p>';
		}
		
	
	
		
		echo '</div></div></div>';
		$testmaj='ok';
	}
	}	

		
	if($testmaj=='ok'){

	//deuxieme etape attribution des forfaits pour les utilisateurs, optionnel ou pas....	
	
	?>
<div class="col-md-6">
 <div class="box box-danger"><div class="box-header"> 
	<i class="fa fa-warning"></i><h3 class="box-title">Mise à jour termin&eacute;e !</h3></div>
		<div class="box-body">
		<p>Dans cette mise à jour :</p>
		<ul><li>Modification et optimisation des pr&eacute;sincriptions : par epnconnect comme par la page de login via internet ou sans epnconnect</li>
			<li>Activation de la pr&eacute;inscription par la page login et mise en place du Recaptcha de google, voir l'aide pour plus de d&eacute;tail !</li>
			<li>Modification du syst&egrave;me de messagerie instantan&eacute;e : les adh&eacute;rents peuvent s&eacute;lectionner l'animateur et lui envoyer un message; les animateurs peuvent r&eacute;pondre directement (mais ils ne voient pas les messages envoy&eacute;s aux autres animateurs); les administrateurs voient tous les messages envoy&eacute;s.</li>
			<li>Modification d'affichage de la liste des ateliers/sessions : le lieu apparait maintenant.</li>
			<li>Refonte totale des pages Adh&eacute;nts</li>
			<li>Ajout sur la page Adh&eacute;nt d'un bouton pour l'envoi par mail des identifiant - mot de passe qui lui permette d'acc&eacute;der &agrave; son compte</li>
		</ul>

<?php		
		
	//****MODIFICATION NUMERO VERSION *** ///	//*Message de FIN **///
	$version=modifNumMAJ($versionew);
				
	if($version==TRUE){	
		echo '<p class="text-blue"><b>Modification du numero de version </b></p>';
		$finale=InsertLogMAJ('maj',$versionew,date('Y-m-d H:i'),"Mise à jour de version 1.2 effectuee");
		}else{
			echo '<p>Mise à jour partielle ou impossible, veuillez contacter votre responsable réseau ! Un fichier de log est disponible dans le dossier monapplication/logs </p>';
		}
	//****ecriture du fichier de log
	if ($finale==FALSE){ 
			$error.= "Echec impossible d'ecrire dans la table des logs"." \r\n"; 
	}else{
		//inscrire l'ensemble des erreurs dans le fichier log de la version 				
		if($error!=""){	gFilelog(addslashes($error),"log_majv1.2.txt");	}
	
			
		//vider les variables
		$error='';
		?>
		</div>
	<div class="box-footer"><a href="index.php"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour à l'accueil</button></a></div>
	</div></div>
<?php
	} //fin finale
	
	
	} //fin second div
?>

	
</div>
	





