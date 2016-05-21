<?php
// formulaire de modification de transaction

// r&eacute;cuperation des variables

$id_user = $_GET["iduser"];
$b=$_GET["b"];
$typeTransac=$_GET["typetransac"];

// Information Utilisateur
	$row = getUser($id_user);
	$dateinsc     =  $row["date_insc_user"];
	$daterenouv=$row["dateRen_user"];
        $nom      =  $row["nom_user"];
        $prenom   =  $row["prenom_user"];
		$temps=$row["temps_user"];

switch ($typeTransac)
{
//les impressions
case "p" : 

	$transac=$_GET["idtransac"];
	$url_redirect="index.php?a=21&b=3&idtransac=".$transac."&typetransac=".$typeTransac."&iduser=".$id_user ;
	$annuler="index.php?a=21&b=1&caisse=&act=&iduser=".$id_user;
	$titre="Modification d'une impression pour ".$prenom." ".$nom." ";
	
break;
//les adhesions
case "adh":
	

	$transac=$_GET["idtransac"];
	$url_redirect="index.php?a=21&b=3&typetransac=".$typeTransac."&iduser=".$id_user  ;
	$annuler="index.php?a=1&b=2&iduser=".$id_user;
		if (isset($transac)){
			$titre="Encaisser l'adh&eacute;sion";
		}else{
			$titre="Renouveller ou modifier l'adh&eacute;sion";
		}
	
	$tarif=$row["tarif_user"];
	 $tarifs=getTarifsbyCat(2);
	 $adhesiontarif=$tarifs[$tarif];
	
break;
	
//les forfaits pour les ateliers
case "forfait":
	$transac=$_GET["idtransac"];
	$annuler="index.php?a=6&iduser=".$id_user;
	if (isset($transac)){
			//modification d'une transaction
			$titre="Modifier/Encaisser un forfait pour ".$nom." ".$prenom;
			$tariforfaits=getTarifsbyCat(5);
			$rowf=getForfait($transac);
			$nbrforfait=$rowf["nbr_forfait"];
			$datef=$rowf["date_transac"];
			$forfait_user=$rowf["id_tarif"];
			$url_redirect="index.php?a=21&b=3&idtransac=".$transac."&typetransac=".$typeTransac."&iduser=".$id_user ;
			
		}else{
			//nouveau forfait a crediter
			$titre="Encaisser un forfait pour ".$nom." ".$prenom;
			$tariforfaits=getTarifsbyCat(5);
			$forfait_user=getAllForfaitUser($id_user,"for");
			$nbf=mysqli_num_rows($forfait_user);	
			$datef=date("Y-m-d");
			$nbrforfait=1;
			$url_redirect="index.php?a=21&b=3&typetransac=".$typeTransac."&iduser=".$id_user ;
		}
	
break;

//les forfaits temps consultation
case "temps":
	$transac=$_GET["idtransac"];
			$titre="Ajouter du temps de consultation pour ".$nom." ".$prenom;
			
			if (isset($transac)){
			$url_redirect="index.php?a=21&b=3&idtransac=".$transac."&typetransac=".$typeTransac."&iduser=".$id_user ;
			}else{
					$url_redirect="index.php?a=21&b=3&typetransac=".$typeTransac."&iduser=".$id_user ;
			}
		$annuler="index.php?a=6&iduser=".$id_user;
		$tariforfaits=getTarifsTemps();
		$forfait_user=$temps;
			
			$datef=date("Y-m-d");
			$nbrforfait=1;
	
		
break;
}


?>
<div class="col-md-6">
<div class="box box-success"><div class="box-header"><h3 class="box-title"><?php echo $titre ;?></h3></div>
	<div class="box-body"><form method="post" action="<?php echo $url_redirect ;?> " role="form" >
	<?php
	//tableau des moyens de paiement
	$paiementmoyen=array(1=>"Esp&egrave;ces", 2=>"Ch&egrave;que", 3=>"Carte Bleue");
// Modification d'une impression
	if ($typeTransac=="p"){
		
		$print=mysqli_fetch_array(getPrintFromID($transac));
		// Si l'utilisateur est externe, affichage du champs avec le nom
		$userext=getIduserexterne();
		if ($userext==$print["print_user"]){
			$externe=1;
		}

		$date_p=$print["print_date"];
		$statut_p=$print["print_statut"];
		$paiement_p=$print["print_paiement"];
		// recuperation des tarifs disponibles
		$tarifs=getTarifsbyCat(1); //1= impressions
		//le prix indicatif
		
	//	debug($_POST);
		//recalcul ?
		if(isset($_POST["recalculer"])){
			
			$debit_p=$_POST["debitprint"];
			$tarif_p=$_POST["tarifprint"];
			$tarif=mysqli_fetch_array(getPrixFromTarif($tarif_p));
			$credit_p=round(($_POST["debitprint"] * $tarif['donnee_tarif']),2);
			$prix=$credit_p;
			
		}else{
			$tarif_p=$print["print_tarif"];
			$credit_p=$print["print_credit"];
			$tarif=mysqli_fetch_array(getPrixFromTarif($tarif_p));
		
			$prix=round(($debit_p * $tarif['donnee_tarif']),2);
			$debit_p=$print["print_debit"];
		}
		/*
		if($credit_p==0){
		$credit_p=$prix;
		}
		*/
			//griser les rubrique si c'est du credit unique
			if($statut_p==2){
			$disable="disabled /";
			}else{
			$disable="";
			}
	?>
	
	<div class="form-group"><label>Date</label>
			 <div class="row"><div class="col-xs-4"><input type="text" name="date" value="<?php echo $date_p ;?>" class="form-control"></div></div>
		</div>
		
		<div class="form-group"><label>Nombre de pages</label>
			 <div class="row"><div class="col-xs-4"><input type="text" name="debitprint" value="<?php echo $debit_p ;?>" class="form-control" ></div>
								<input class="btn bg-green" type="submit" value="recalculer" name="recalculer" action="index.php?a=21&b=3&typetransac=p&idtransac="<?php echo $transac;?>"&iduser="<?php echo $id_user; ?>""></div>
		
		</div>
		<div class="form-group"><label>Tarif</label>
			 <div class="row"><div class="col-xs-6">	
			<select name="tarifprint" class="form-control" <?php echo $disable; ?>>
		<?php
			foreach ($tarifs AS $key=>$value)
			{
				if ($tarif_p == $key)
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
		<div class="form-group"><label>Moyen de paiement</label>
			 <div class="row"><div class="col-xs-6">	
			<select name="moyen_paiement" class="form-control">
		<?php
			foreach ($paiementmoyen AS $key=>$value)
			{
				if ($paiement_p == $key)
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
		
		
		<?php 	if ($externe==1){ ?>
			<br><div class="form-group"><label>Nom pr&eacute;nom</label>
			<input type="text" name="nomuser"  placeholder="Veuillez entrer le nom et pr&eacute;nom" class="form-control" value="<?php echo $print["print_userexterne"]; ?>"></div>
		<?php } ?>
		
		
			</div>
		<div class="form-group"><label>Credit</label>
			 <div class="row"><div class="col-xs-4">
			<!-- <input type="hidden" name="statutprint" value="<?php echo $statut_p; ?>">-->
			 
			 <input type="text" name="creditprint" value="<?php echo $credit_p ;?>" class="form-control"></div></div>
			
	</div>
	
	<?php
}
///***fin modif transaction impression***///

// transaction adhesion ****//
if ($typeTransac=="adh"){
	 $dateinsc     =  $row["date_insc_user"];
	$daterenouv=$row["dateRen_user"];
	//date de renouvellement adhesion automatiquement crée
	$today=date("Y-m-d");
	$daterenouv2 = date_create($today);
	date_add($daterenouv2, date_interval_create_from_date_string('365 days'));
	$daterenouv2=date_format($daterenouv2, 'Y-m-d');
	$tarifadhs=getTarifsbyCat(2);

?>
<div class="form-group"><label>Date de 1er inscription</label>
			 <div class="row"><div class="col-xs-4"><input type="text" value="<?php echo $dateinsc ;?>" class="form-control" disabled></div></div></div>
<div class="form-group"><label>Date de renouvellement</label>
			 <div class="row"><div class="col-xs-4"><input type="text" value="<?php echo $daterenouv ;?>" class="form-control" disabled></div></div></div>
<div class="form-group"><label>Date de renouvellement prochain</label>
			 <div class="row"><div class="col-xs-4"><input type="text" name="daterenouv" value="<?php echo $daterenouv2 ;?>" class="form-control">
			 <input name="date" value="<?php echo $today ;?>" hidden>
		
			 </div></div></div>
		
<div class="form-group"><label>renouvellement de l'adh&eacute;sion au tarif: </label>
		<select name="tarif_adh" class="form-control" >
		<?php
			foreach ($tarifadhs AS $key=>$value)
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
		</select></div></div>
		<?php	
		if (isset($transac))
		{
			echo '<div><input name="idtransac" value="'.$transac.'" hidden></div>';
		}
		?>

	</div><!-- fin body-->

	
<?php
}
///**Fin modification des adhesions


///***Forfait atelier ou consultation
if (($typeTransac=="forfait") OR ($typeTransac=="temps" )){

?>

<div class="form-group"><label>Tarif choisi </label>
		<select name="tarif_forfait" class="form-control" >
		<?php
			foreach ($tariforfaits AS $key=>$value)
			{
				if ($forfait_user == $key)
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
		<?php if($typeTransac=="forfait"){ // n'apparait pas pour les tarfis de la consultation ?>	
<div class="form-group"><label>Combien ? </label>		
	<input type="text" name="nbrf" value="<?php echo $nbrforfait; ?>" placeholder="3, 6...." class="form-control"> </div>
	<?php } ?>

	<div class="form-group"><label>Quand ? </label>	
		<input type="text" name="date" value="<?php echo $datef ;?>" class="form-control"></div>
	
	</div><!-- fin body-->


<?php
}
?>

<div class="box-footer">
		<input type="submit" value="En attente" name="submit" class="btn bg-purple ">
		<input type="submit" value="Encaisser" name="submit" class="btn btn-primary">
		</form>
	<a href="<?php echo $annuler; ?>">
	<input type="submit" value="Annuler" name="Annuler" class="btn btn-default"></a>
	</div><!-- fin footer-->
	</div><!-- fin box-->
</div><!-- /col -->



