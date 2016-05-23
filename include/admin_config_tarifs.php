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
 

*/

// affichage  -----------

//chargement du tarif
$tarif_r=$_POST['ptarif']; //bouton d&eacute;roulant
$ptarif=$_GET['catTarif']; //bouton modification

if(isset($ptarif)){
		$tarif_r=$ptarif;
		}
		
if (isset($tarif_r)){
	$tarif=$tarif_r;
}else{
	$tarif=1; //tarif par d&eacute;faut 
	$tarif_r=1;
	$ptarif=1;
	}

$espaces = getAllepn();
// tableau unit&eacute;s pour les ateliers
$dureetype=array(0=>'Illimit&eacute;e', 
1=>'An(s)', 
2=>'Mois', 
3=>'Jour(s)' );


// Tableau des unité de durée forfait
    $tab_unite_duree_forfait = array(
           1=> "Jour",
           2=> "Semaine",
           3=> "Mois",
					 4=> "Illimit&eacute;e"
    );
	
	// Tableau des unité d'affectation
    $tab_unite_temps_affectation = array(
           1=> "Minutes",
           2=> "Heures"
    );
	
	// Tableau des fréquence d'affectation
    $tab_frequence_temps_affectation = array(
           1=> "par Jour",
           2=> "par Semaine",
           3=> "par Mois"
    );


?>
<!-- DIV acces direct aux autres parametres-->
 <div class="row">
   <div class='col-md-12'>
 <div class="box collapsed-box">
		<div class="box-header ">
			<h3 class="box-title">Param&eacute;trages</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> </div>
			</div>
		
		<div class="box-body">
			
			<?php 
			//debug($_GET["a"]);
			echo configBut($_GET["a"]) ;
		
			?>
			
		</div><!-- /.box-body -->
</div><!-- /.box -->
<?php
$mesno= $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

if($mess!=""){
echo $mess;
}
?>

</div>
</div>
 <div class="row">
<!-- Accordeon sur les nouveaux tarifs  -->	
<div class="col-md-4">
	<div class="box box-solid box-warning">
		<div class="box-header with-border"><i class="glyphicon glyphicon-plus"></i><h3 class="box-title">Nouveau Tarif</h3>
			<div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> </div>
			</div>
		<div class="box-body">
		
		<!-- id 1 : les impressions -->
		<div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
			<div class="panel box box-primary">
			  <div class="box-header with-border">
				<h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#1impression"> Impressions / Adh&eacute;sion / Divers</a></h4>
			  </div>
			  <div id="1impression" class="panel-collapse collapse">
				<div class="box-body">
				 <form method="post" action="index.php?a=47&actarif=1&typetarif=1" class="form">
				<div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="newnomtarif"></div>
				<div class="form-group"><input type="text" class="form-control" placeholder="prix*  0.15" name="newprixtarif"></div>
				<div class="form-group"><textarea class="form-control" placeholder="description" name="newdescriptiontarif"></textarea></div>
				<div class="form-group"><label >Cat&eacute;gorie *:</label><select name="catTarif" class="form-control">
						
						<?php
						$categorieTarif=array(
							1=>"impression",
							2=>"adhesion",
							3=>"consommables",
							4=>"Divers"
							);
							
							foreach ($categorieTarif AS $key=>$value)
							{
								if ($catTarif == $key)
								{
									echo "<option value=\"".$key."\" selected>".$value."</option>";
								}
								else
								{
									echo "<option value=\"".$key."\">".$value."</option>";
								}
							}
							
						?></select></div>
					<div class="form-group">
						<label >Espace *:</label>
						<select name="espace[]" multiple class="form-control">
						<?php
								foreach ($espaces AS $key=>$value)
								{
									if ($espace == $key)
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
				<div class="box-footer"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div></form></div>
			  </div><!-- panel -->
        </div><!-- accordeon -->
		<!-- FIN IMPRESSIONS -->
		
		<!-- id 2 : les Ateliers -->
		<div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
		<div class="panel box box-success">
		  <div class="box-header with-border">
			<h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#2atelier"> Ateliers</a></h4>
		  </div>
		  <div id="2atelier" class="panel-collapse collapse">
                        <div class="box-body"><form method="post" action="index.php?a=47&actarif=1&typetarif=2" class="form">
			 <div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="newnomtarifa"></div>
			 <div class="form-group"><textarea rows="2" class="form-control" placeholder="description" name="newdescriptiontarifa"></textarea></div>
			<div class="form-group"><input type="text" class="form-control" placeholder="prix*  0.15" name="newprixtarifa"></div>
			<div class="form-group"><label>Nombre d'ateliers*</label><input type="number" class="form-control" value="0" min="0" name="newnumbertarifa"></div>
				<div class="form-group"><label>Limite de validit&eacute;*</label>
					 <div class="row">
						<div class="col-xs-5">
							<input type="number" class="form-control"  value="0" min="0" name="dureetarifa"></div>
						<div class="col-xs-5">
							<select type="text" class="form-control"  name="typedureetarifa">
							<?php
								foreach ($dureetype AS $key=>$value)
						{
							if ($duree == $key)
							{
								echo "<option value=\"".$key."\" selected>".$value."</option>";
							}
							else
							{
								echo "<option value=\"".$key."\">".$value."</option>";
							}
						}
							?>	
							</select></div></div>
				</div>
			<div class="form-group">
				<label >Espace *:</label>
				<select name="espace[]" multiple class="form-control">
				<?php
						foreach ($espaces AS $key=>$value)
						{
							if ($espace == $key)
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
		 <div class="box-footer"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div></form>
	   </div>
	  </div>
	</div>
		
		
		
		<!-- id 3 : les consultations -->
		<div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                    <div class="panel box box-danger">
					<div class="box-header with-border">
                        <h4 class="box-title"><a data-toggle="collapse" data-parent="#accordion" href="#3consult"> Consultation</a></h4>
                      </div>
                      <div id="3consult" class="panel-collapse collapse">
                        <div class="box-body">
						<form method="post" action="index.php?a=47&actarif=1&typetarif=3" class="form">
							<div class="form-group"><input type="text" class="form-control" placeholder="Nom du tarif*" name="nom_forfait"></div>
							<div class="form-group"><input type="text" class="form-control" placeholder="prix*  0=gratuit" name="prix_forfait"></div>
							<div class="form-group"><textarea class="form-control" placeholder="Description" name="comment_forfait"></textarea></div>
							<div class="form-group"><label>Limite de validit&eacute; du forfait *</label>
								 <div class="row">
									<div class="col-xs-5">
										<input type="number" value="0" min="0" class="form-control"  name="nombre_duree_forfait"></div>
									<div class="col-xs-5">
										<select  class="form-control"  name="unite_duree_forfait">
											<?php
													foreach ($tab_unite_duree_forfait AS $key=>$value)
											{
												if ($unite == $key)
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
								</div><br>
								</div>
							<div class="form-group"><label>Dur&eacute;e de la consultation *</label>
								 <div class="row">
									<div class="col-xs-3">
										<input class="form-control" type="number" value="0" min="0" name="nombre_temps_affectation"></div>
									<div class="col-xs-4">
										<select type="text" class="form-control"  name="unite_temps_affectation">
											<?php
													foreach ($tab_unite_temps_affectation AS $key=>$value)
											{
												if ($unite == $key)
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
									
									<div class="col-xs-5">
										<select  class="form-control"  name="frequence_temps_affectation">
											<?php
													foreach ($tab_frequence_temps_affectation AS $key=>$value)
											{
												if ($freq == $key)
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
								<!--
								<div class="form-group"><label> ou Affect. occasionelle (en min)&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour la consultation de type one shot ! La validit&eacute; est expir&eacute;e après d&eacute;pense."><i class="fa fa-info"></i></small>
								</label><input type="number" value="" min="0" class="form-control" name="temps_affectation_occasionnel"></div>	-->
								
								<div class="form-group">
									<label >Espace *:</label>
									<select name="espace[]" multiple class="form-control">
									<?php
											foreach ($espaces AS $key=>$value)
											{
												if ($espace == $key)
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
						<div class="box-footer"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div>
						</form> </div>
                    </div>
		</div>
		</div>
				
		</div>
</div>


<!-- MODIFICATION DES TARIFS -->
 
 <div class='col-md-8'>
 
  <div class="box box-default">
  <div class="box-header with-border"><h3 class="box-title">Tous les tarifs par cat&eacute;gorie </h3>
  <div class="box-tools pull-right">
      <div class="has-feedback"><form action="index.php?a=47" method="post" role="form" >
				<div class="input-group input-group-sm">
			<select name="ptarif"  class="form-control pull-right" style="width: 200px;">
       	<?php
        $categorieTarif=array(
							1=>"Impression",
							2=>"Adh&eacute;sion",
							5=>"Atelier",
							6=>"Consultation",
							3=>"Consommables",
							4=>"Divers"
							);
							
							foreach ($categorieTarif AS $key=>$value)
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
							</select>
	
		<span class="input-group-btn"><button type="submit" name="submit" value="Valider" class="btn btn-default"><i class="fa fa-repeat"></i></button></span>
	</form></div>
	
	<!--<button class="btn bg-blue btn-sm"  data-toggle="tooltip" title="Si votre EPN fait payer les ateliers, déclarez le tarif correspondant, le décompte sera automatiquement effectué en fonction des achats de vos adhérents."><i class="fa fa-info"></i></button>-->
		
		</div></div></div></div></div>
	
 
<?php
///*** gestion des tarifs AJOUT 2014
if($tarif<6)
{

$tarifbycat=getTarifs($tarif);
$nbt=mysqli_num_rows($tarifbycat);

 
if ($nbt==0)
    {
        echo '<div class="col-md-6">
				<div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <b>Pas de tarifs encore !</b></div></div>' ;
    }else{
	
	$categorieTarif=array(
	1=>"Impression",
	2=>"Adhesion",
	3=>"Consommables",
	4=>"Divers",
	5=>"Forfait Atelier"
	);
			
			for ($i=0 ; $i<$nbt ; $i++)
			{
			$row=mysqli_fetch_array($tarifbycat);
			$catTarif=$row['categorie_tarif'];
			$id_tarif=$row['id_tarif'];
			$espace=explode('-',$row['epn_tarif']);
		//	debug($espace);
			
			?>
	 <div class="col-md-3 col-sm-6 col-xs-12">
	<div class="box box-warning">
	<div class="box-header  with-border"><h3 class="box-title"><?php echo $row['nom_tarif']; ?></h3></div>
	 <div class="box-body">
		<form  method="post" class="form" action="index.php?a=47&actarif=2&idtarif=<?php echo $row['id_tarif'] ; ?>" >
			
<?php 
	if($catTarif==5){
		$dureenumarray=explode('-',$row["duree_tarif"]);
		$duree2=$dureenumarray[1];
		?>
		
		<div class="form-group" ><label>Libell&eacute;</label>
			<input type="hidden" name="catTarif" value="<?php echo $catTarif; ?>"><input type="hidden" name="ptarif" value="<?php echo $catTarif; ?> ">
			<input type="text" class="form-control" name="nomtarif" value="<?php echo $row['nom_tarif']; ?> "></div>
		 
		<div class="form-group" ><label>Prix</label><input type="text"  class="form-control" name="prixtarif" value="<?php echo $row['donnee_tarif']; ?>  "></div>
		<div class="form-group" ><label>Nbre d'ateliers</label><input type="text" class="form-control" name="numberatelier" value="<?php echo  $row["nb_atelier_forfait"] ?>"></div>
	
		<div class="form-group" ><label>Commentaire</label><textarea rows="2" class="form-control" name="descriptiontarif" ><?php echo stripslashes($row['comment_tarif']); ?></textarea></div>
			<label>Limite de validit&eacute;</label><br>
			<div class="row"><div class="col-xs-3">
					<div class="form-group" >
							<input type="number"  name="dureetarif" value="<?php echo $dureenumarray[0] ?>" style="width:50px;"></div></div>
					<div class="col-xs-3">			<select type="text"   name="typedureetarif">
								<?php		
							foreach ($dureetype AS $key=>$value)
									{
								
										if ($key==$duree2)
										{
											echo "<option value=\"".$key."\" selected>".$value."</option>";
										}
										else
										{
											echo "<option value=\"".$key."\">".$value."</option>";
										}
									} ?>
							</select></div></div>
							
				<div class="form-group" ><label>Espaces</label><select name="espace[]" multiple class="form-control">
							<?php
								
									foreach ($espaces AS $key=>$value)
									{
										
										if (in_array($key,$espace))
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
	
	 } else {	?>
	
	<div class="form-group"><label>Nom</label><input type="hidden" name="catTarif" value="<?php echo $catTarif; ?>"><input type="text" class="form-control" name="nomtarif" value="<?php echo $row['nom_tarif']; ?> "></div>
	<div class="form-group"><label>Prix</label><input type="text"  class="form-control" name="prixtarif" value="<?php echo $row['donnee_tarif']; ?>  "></div>
	<div class="form-group"><label>Description</label><textarea rows="2" class="form-control" name="descriptiontarif" ><?php echo stripslashes($row['comment_tarif']); ?></textarea></div>
	<div class="form-group"><label>Espaces</label><select name="espace[]" multiple class="form-control">
							<?php
								
									foreach ($espaces AS $key=>$value)
									{
										
										if (in_array($key,$espace))
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
									
	<?php } 	?>
		
	</div>	
	
	<div class="box-footer">
	<button class="btn btn-success btn-sm"  type="submit" value="modifier"><i class="fa fa-refresh"></i>&nbsp;Modifier</button>
	&nbsp;<a href="index.php?a=47&actarif=3&idtarif=<?php echo $row['id_tarif']; ?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>&nbsp;Supprimer</button></a></div>
	
</form>
</div></div>
					 
			<?php		 
			} // end FOR cat tarif 1 to 5
		}
		
	}else{
		// Affichage du tarif consultation (6)
		$consultation=getTarifConsult();
		$nbc=mysqli_num_rows($consultation);
	
		if($nbc==0)
    {
        echo '<br><div class="col-md-6">
				<div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <b>Pas de tarifs encore !</b></div></div>' ;
    }else{
			/// affichage de l'array des consultations
			for($y=0;$y<$nbc;$y++){
			$rowconsult=mysqli_fetch_array($consultation);
			//debug($rowconsult);
		//$nombre_temps_affectation = $rowconsult["nombre_temps_affectation"];
		$unite_temps_affectation = $rowconsult["unite_temps_affectation"];
		$frequence_temps_affectation= $rowconsult["frequence_temps_affectation"];
			
		if ($rowconsult["temps_forfait_illimite"]=='1'){
				$unite_duree_forfait = 4;
		}else{
				$unite_duree_forfait= $rowconsult["unite_duree_forfait"];
		}
		
		$epnC=getAllRelForfaitEspace($rowconsult['id_forfait']);
		//debug($epnC);
		
			?>
 <div class="col-md-4">
	<div class="box box-warning"><div class="box-header"><h3 class="box-title"><?php echo $rowconsult['nom_forfait']; ?></h3></div>
	 <div class="box-body"><form  method="post" class="form" action="index.php?a=47&actarif=2&idtarif=<?php echo $rowconsult['id_forfait'] ; ?>" >
		<div class="form-group"><label>Libell&eacute;</label>
							<input type="hidden" name="catTarif" value="6">
							<input type="hidden" name="id_forfait" value="<?php echo $rowconsult['id_forfait']; ?>">
							<input type="hidden" name="ptarif" value="6">
							<input type="text" class="form-control" name="nom_forfait" value="<?php echo $rowconsult['nom_forfait']; ?> "></div>
		<div class="form-group"><label>Prix (&euro;)</label><input type="text"  class="form-control" name="prix_forfait" value="<?php echo $rowconsult['prix_forfait']; ?>  "></div>
		<div class="form-group"><label>Description</label><textarea rows="2" class="form-control" name="commentaire_forfait"><?php echo $rowconsult['commentaire_forfait']; ?></textarea></div>
		
		<div class="input-group">
			<div class="row"><div class="col-xs-4"><label>Validit&eacute;</label><input type="number" min="0" class="form-control"  value="<?php echo $rowconsult['nombre_duree_forfait']; ?>" name="nombre_duree_forfait"></div>
							<div class="col-xs-4"><label>&nbsp;</label>
										<select  class="form-control"  name="unite_duree_forfait">
											<?php
													foreach ($tab_unite_duree_forfait AS $key=>$value)
											{
												if ($unite_duree_forfait == $key)
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
							<!--<div class="col-xs-5"><label>Affect. occasionelle (en min)&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Pour la consultation de type one shot ! La validit&eacute; est expir&eacute;e après d&eacute;pense."><i class="fa fa-info"></i></small></label>
							<input type="number" min="0" class="form-control" placeholder="en min" name="temps_affectation_occasionnel" value="<?php echo $rowconsult['temps_affectation_occasionnel']; ?>">-->
		</div></div>
								
		<div class="input-group"><label>Dur&eacute;e de la consultation</label>
			<div class="row"><div class="col-xs-4"><input class="form-control" type="number" min="0" name="nombre_temps_affectation" value="<?php echo $rowconsult['nombre_temps_affectation']; ?>"></div>
							<div class="col-xs-4"><select type="text" class="form-control"  name="unite_temps_affectation">
											<?php
													foreach ($tab_unite_temps_affectation AS $key=>$value)
											{
												if ($unite_temps_affectation == $key)
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
									
							<div class="col-xs-4">
										<select  class="form-control"  name="frequence_temps_affectation">
											<?php
													foreach ($tab_frequence_temps_affectation AS $key=>$value)
											{
												if ($frequence_temps_affectation == $key)
												{
													echo "<option value=\"".$key."\" selected>".$value."</option>";
												}
												else
												{
													echo "<option value=\"".$key."\">".$value."</option>";
												}
											}
												?>	
										</select></div></div></div>
							
								
				<div class="form-group"><label>Espaces</label><select name="espace[]" multiple class="form-control">
						<?php foreach ($espaces AS $key=>$value)
									{
										
										if (in_array($key,$epnC))
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
	<div class="box-footer"><button class="btn btn-success btn-sm"  type="submit" value="modifier"><i class="fa fa-edit"></i>&nbsp; Modifier</button>
	&nbsp;<a href="index.php?a=47&actarif=3&typetarif=3&idtarif=<?php echo $rowconsult['id_forfait']; ?>"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>&nbsp;Supprimer</button></a>
		</div>
		</form>
	</div></div>
	
	<?php
			}
		
		}
	
	}

?>


</div>

