<?php
/* enregistrement des transactions dans la base */


//debug($tarifs);
// recuperation de l'identifiant utilisateur
$id_user = $_GET["iduser"];
$act=$_GET["act"];
$userext=$_GET['ext'];

if($id_user=="ext"){ //en cas d'utilisateur qui n'est pas dans la base, utilisation de l'utilisateur externe créé dans la MAJ
	$userext=getIduserexterne();
	if($userext!=FALSE){
		$id_user=$userext;
		$userext=1;
		} else {
		echo "Attention l'utilisateur externe n'existe pas !";
	}
}

//recuperation des données utilisateur
$rowp=getuser($id_user);

// recuperation des variables calcul encaissement
$caisse=$_GET["caisse"];
//debug($caisse);
//envoi des codes de transaction pour le calcul
$transaction_p=$_POST["transact"];
// initialisation des variables
if ($caisse==0){
	$date_p = date("Y-m-d H:i");
	$debit=0;
	$transac=0;
	$credit_p=0;
}
if($caisse==1){
$date_p=$_POST["date"];
}

// boucler pour faire le total des dépenses et du credit
	$totalprint=getDebitUser($id_user);
	$credituser=getCreditUser($id_user);
		//total credite
	//$totalcredit=$credituser+$totalprint;
	$totalrestant=$credituser-$totalprint;
	
	//tableau des moyens de paiement
	$paiementmoyen=array(1=>"Esp&egrave;ces", 2=>"Ch&egrave;que", 3=>"Carte Bleue");

?>

<div class="row">
<!-- ajouter un credit au compte d'impression-->
<div class="col-lg-3 col-xs-6">
<div class="box box-success"><div class="box-header"><h3 class="box-title">Cr&eacute;diter le compte de&nbsp;<b><?php echo $rowp['nom_user'];?>&nbsp;<?php echo $rowp['prenom_user'];?></b></h3></div>
	<div class="box-body"><form method="post" action="index.php?a=21&b=2&caisse=0&act=4&iduser=<?php echo $id_user ;?> ">
	<div class="form-group"><label>Valeur (en &euro;)</label>
	<input type="hidden" name="datec" value="<?php echo $date_p;?>">
	<input type="text" name="credit" class="form-control">
	</div></div>
	
	<div class="box-footer"><input type="submit" value="Ajouter" name="submit" class="btn btn-success"></div></form>
</div></div>


<div class="col-lg-3 col-xs-6">
<div class="box box-success"><div class="box-header"><h3 class="box-title">Impressions de&nbsp;<b><?php echo $rowp['nom_user'];?>&nbsp;<?php echo $rowp['prenom_user'];?></b></h3></div>
		<div class="box-body">
		<form method="post" action="index.php?a=21&b=2&caisse=1&act=&iduser=<?php echo $id_user ;?>&ext=<?php echo $userext ;?> ">
			
			  <div class="form-group"><label>Date :</label>
					<input type="text" name="date" class="form-control" value="<?php echo $date_p ?>"></div>
				
<?php
/// edition de la liste des tarifs concernant les impressions
// recuperation des tarifs disponibles
//
$tarifs=getTarifs(1); //1= impressions
$nbt=mysqli_num_rows($tarifs);

if($caisse==1){
		$nbts=count($transaction_p)/5;
		$donnees=array_chunk( $transaction_p ,5);
		//debug($donnees);
		for ($i=0;$i<$nbts;$i++){
				$tab_transac=$donnees[$i];
				
				$debit_p=$tab_transac[0];
				$tarif_p=$tab_transac[1];
				$nom_tarif=$tab_transac[2];
				$donnee_tarif=$tab_transac[3];
				
		echo '<div class="form-group"><label>'. $nom_tarif.' '.$donnee_tarif.'</label>
			
			<input type="text" name="transact[]" value="'.$debit_p.'" class="form-control">
			
			<input type="hidden" name="transact[]" value="'.$tarif_p.'" />
			<input type="hidden" name="transact[]" value="'.$nom_tarif.'" />
			<input type="hidden" name="transact[]" value="'.$donnee_tarif.'" />
			<input type="hidden" name="transact[]" value="0" />
			</div>
	';
			//debug($date_p);	
		}
	} else {
		while($row = mysqli_fetch_array($tarifs))
			{
			//
			echo '<div class="form-group"><label>'. $row['nom_tarif'].' '.$row['donnee_tarif'].'</label>
					
					<input type="text" name="transact[]" value="'.$debit.'" class="form-control">
					
					<input type="hidden" name="transact[]" value="'.$row['id_tarif'].'" />
					<input type="hidden" name="transact[]" value="'.$row['nom_tarif'].'" />
					<input type="hidden" name="transact[]" value="'.$row['donnee_tarif'].'" />
					<input type="hidden" name="transact[]" value="0" />
					</div>
			';
			}
			
}
	
?>
  </div>
<div class="box-footer"><input type="submit" value="Calculer" name="submit" class="btn btn-success"></div>
  
</form>
</div>
</div><!-- /col -->




<?php

if ($caisse==1)
{

?>	
	<div class="col-md-6">
	<div class="box box-success"><div class="box-header"><h3 class="box-title">Validation de la Transaction</h3></div>
		
		<div class="box_body">
			<table class="table">
			<thead> <th>Nom tarif</th><th>Nb de pages</th><th>Prix</th><th></th></thead>
			<form method="post" action="index.php?a=21&b=2&caisse=0&act=1&iduser=<?php echo $id_user ;?>&ext=<?php echo $userext ;?>" enctype="multipart/form-data">
		<?php
		$nbt=count($transaction_p)/5;
		$donnees=array_chunk( $transaction_p ,5);
		
		for ($i=0;$i<$nbt;$i++){
				$tab_transac=$donnees[$i];
				$debit_p=$tab_transac[0];
				$tarif_p=$tab_transac[1];
				$nom_tarif=$tab_transac[2];
				$donnee_tarif=$tab_transac[3];
				$statut_p=$tab_transac[4];
			
		$prix=round(($debit_p* $donnee_tarif),2);
		$total=$total+$prix;
		
			
		
		echo '<tr><td>'.$nom_tarif.' &nbsp('.$donnee_tarif.')</td>
					<td>'.$debit_p.'</td>
					<td>'.$prix.' &euro;</td>
					
				<td>
					<input type="hidden" name="printU[]" value="'.$tarif_p.'" />
					<input type="hidden" name="printU[]" value="'.$debit_p.'" />
					<input type="hidden" name="printU[]" value="'.$prix.'" />
				</td></tr>';
			}
		
		echo '<tr><td>total</td><td></td><td>'.$total.' &euro;</td></tr>';
		
		
?>	
	<tr><td>Reliquat sur le compte : </td><td><input type="hidden" name="date" value="<?php echo $date_p ?>"></td><td><?php echo $totalrestant; ?>&nbsp;&euro;</td></tr>
<?php

 if (($total-$totalrestant)<0) 
 {
	$du= "0"; 
 } else {
	$du=$total-$totalrestant;
 }
 ///en cas de credit positif envoyer valeur
if ($totalrestant>=$total)
{
$paiement=0;
 } else {
$paiement=1;
}

?>
	<tr><td>Total d&ucirc; : </td><td></td><td><?php echo $du; ?>&nbsp;&euro;</td></tr>
	
	<tr><td>Moyen de paiement</td><td colspan="2"><select name="moyen_paiement" class="form-control">
														<?php 
														foreach ($paiementmoyen AS $key=>$value ){
															echo "<option value=\"".$key."\" selected>".$value."</option>";
														}
															?></select></td></tr>
			
	<tr><td>Cr&eacute;dit : &nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Rentrez une somme diff&eacute;rente du total si l'adh&eacute;rent paye plus ou moins selon le reliquat."><i class="fa fa-info"></i></small></td><td></td>
		<td><input type="text" name="credit" value="<?php echo $_GET["credit"]; ?>" class="form-control">
			
			<input type="hidden" name="paiement" value="<?php echo $paiement; ?>" ></td></tr>
<?php
	
		if($_GET["ext"]==1){
		
			echo '<tr><td>Nom pr&eacute;nom</td><td colspan="2"><input type="text" name="nomuser"  placeholder="Veuillez entrer le nom et pr&eacute;nom" class="form-control"></td></tr>';
		}
		
?>
	
</table>
<div class="box-footer">
<input type="submit" value="En attente" name="submit" class="btn bg-purple ">
<input type="submit" value="Encaisser" name="submit" class="btn btn-primary">
</form>
<a href="index.php?a=21&b=1&caisse=&act=&iduser=<?php echo $id_user ;?> "><input type="submit" value="Annuler" name="Annuler" class="btn btn-default"></a>

</div>
	
</div>

</div></div>
<?php
}
?>





