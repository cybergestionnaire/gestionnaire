<?php

// fichier d'affichage du compte d'impression selectionne

$id_user = $_GET["iduser"];
$act=$_GET["act"];
//$month=date('m');
$idprint=$_GET['idprint'];
$statutPrint=array(
	0 =>"pas pay&eacute;",
	1 =>"pay&eacute;",
	);

//supprimer la transaction
if(isset($act) AND $act==3){
	 if (FALSE == supPrint($idprint))
	  {
		  header("Location: ./index.php?a=21&b=1&iduser=".$id_user."&mesno=0");
	  }
	  else
	  {
		  header("Location: ./index.php?a=21&b=1&iduser=".$id_user."&mesno=45");
		  $act="";
	  }
}

$mesno  = $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}	
//------------------------------------------------
//Affichage historique d'impression et credit de l'utilisateur
if (TRUE==checkPrint($id_user))
	{
		// infos utilisateur
	$rown=getuser($id_user);
	
	$totalprint=getDebitUser($id_user);
	$credituser=getCreditUser($id_user);	
	$restant=$credituser-$totalprint;
	
	// Si l'utilisateur est externe, affichage du champs avec le nom
	$userext=getIduserexterne();
	if ($userext==$id_user){
	$externe=1;
		
	}
	
	
	?>
		
		
<div class="row"><section class="col-lg-4 connectedSortable">
	<div class="box box-primary">
		<div class="box-header"><h3 class="box-title">Compte des impressions de <?php echo $rown['prenom_user'] ?>&nbsp;<?php echo $rown['nom_user'] ?></h3></div>
		<div class="box-body"><h4><b><?php echo $totalprint ?></b>  &euro; ont &eacute;t&eacute; d&eacute;pens&eacute;s.</h4>
			<h4><b><?php echo $credituser; ?></b> &euro; ont &eacute;t&eacute; cr&eacute;dit&eacute;s.</h4>
			<br>
		<?php if( ($credituser-$totalprint)>0){
			echo '<h4><span class="text-green">cr&eacute;dit restant sur le compte : '.$restant.' &euro; </span></h4>';
		}else if( ($credituser-$totalprint)<0){
			echo '<h4><span class="text-red">Le compte est d&eacute;biteur de  '.$restant.' &euro; </span></h4>';
		}else if(($credituser-$totalprint)==0){
			echo '<h4>Aucun cr&eacute;dit restant sur le compte</h4>';
		}
		?>
			</div>
		<div class="box-footer"><a href="index.php?a=21&b=2&act=&caisse=0&iduser=<?php echo $id_user;?>"><input type="submit" value="Ajouter une transaction" class="btn btn-primary"></a></div>
		 <div class="box-footer"><a href="index.php?a=21"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des transactions</button></a></div>
		</div>
</section>
		
		
<?php 	
	
// ARCHIVES DES impressions

	$result = getPrintById($id_user) ;
	//debug(mysql_fetch_array($result));
	$credituser=getCreditPrintId($id_user);
	$nbc=mysqli_num_rows($credituser);
	if(($result!=FALSE) OR ($nbc>0))
	{
		// affichage
	?>
	<section class="col-lg-6 connectedSortable">
	<div class="box box-primary">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs pull-right">
				<li class="active"><a href="#tab_1-1" data-toggle="tab">D&eacute;bit</a></li>
				<li><a href="#tab_2-2" data-toggle="tab">cr&eacute;dit</a></li>
				<li class="pull-left header">Mouvements archiv&eacute;s (12 derniers mois):</li>
			</ul>
		<div class="tab-content">
		<div class="tab-pane active" id="tab_1-1">
			<table class="table">
					<thead><th>Date</th><th>Nbre de pages</th><th>Tarif</th><th>Prix</th>
					<?php if($externe==1){ echo "<th>Nom Pr&eacute;nom</th>";} ?>
					<th>statut</th><th></th></thead>
		
	<?php			   
		while($row = mysqli_fetch_array($result))
		{
		// retrouver le tarif
		$tarif=mysqli_fetch_array(getPrixFromTarif($row['print_tarif']));
		$prix=round(($row['print_debit'] * $tarif['donnee_tarif']),2);
		$statut=$statutPrint[$row['print_statut']];
		///ajout utilisateur externe
		if($externe==1){$nomexterne=$row['print_userexterne'];}
				
		
		echo "<tr><td>".$row['print_date']."</td>
				  <td>".$row['print_debit']."</td>
				  <td>".$tarif['donnee_tarif']."</td>
				  <td>".$prix." &nbsp;&euro;</td>";
			
			if($externe==1){
				echo '<td>'.$nomexterne.'</td>';
			}
			
			if($row['print_statut']==0){ 
				//modification autoris&eacute;e tant que la transaction n'est pas encaissée
					echo "<td><p class=\"text-red\">".$statut."</p></td> 
					<td>
								<a href=\"index.php?a=21&b=3&typetransac=p&idtransac=".$row['id_print']."&iduser=".$id_user."\"><button type=\"button\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-edit\"></i></button></a>
                <a href=\"index.php?a=21&b=1&act=3&idprint=".$row['id_print']."&iduser=".$id_user."\"><button type=\"button\" class=\"btn btn-warning btn-sm\"><i class=\"fa fa-trash-o\"></i></button></a></td>
				 					
					";
				}else{
					// transaction enregistrée
					echo "<td><p class=\"text-light-blue\">".$statut."</p></td> <td>&nbsp;</td>";
				}
			echo " </tr>";
		}
		?>
		</table>
		</div><!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2-2">
		<table class="table">
					<thead><tr><th>Date</th><th>Cr&eacute;dit ajout&eacute;</th><th></th></tr></thead>
		<?php
		
		if($nbc>0){
		while($row2=mysqli_fetch_array($credituser))
		{
			echo "<tr>	<td>".$row2['print_date']."</td>
						<td>".$row2['print_credit']."</td>
						<td><a href=\"index.php?a=21&b=3&typetransac=p&idtransac=".$row2['id_print']."&iduser=".$id_user."\"><button type=\"button\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-edit\"></i></button></a>
                  <a href=\"index.php?a=21&b=1&act=3&idprint=".$row2['id_print']."&iduser=".$id_user."\"><button type=\"button\" class=\"btn btn-warning btn-sm\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr>
				  ";
		
		}
		}
		?>
		
		</table>
		 </div><!-- /.tab-pane -->
         </div><!-- /.tab-content -->
         </div><!-- nav-tabs-custom -->
		</div>
		</section></div>
<?php
	}
	
}else 
	{
// si le compte d'impression est vide
// arrivee depuis la page des resas
	$rown=getuser($id_user);
		$nom_p=$rown['nom_user'];
		$prenom_p=$rown['prenom_user'];
	
	echo "  <div class=\"col-md-3 col-sm-6 col-xs-12\">";
	
	echo geterror(40);
	echo "</div>";
	?>	
<div class="row"><section class="col-lg-6 connectedSortable">
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Compte des impressions de <?php echo $prenom_p; ?>&nbsp;<?php echo $nom_p; ?></h3></div>
	<div class="box-body"><p>Entrez une transaction pour initialiser le compte d'impression</p></div>
	<div class="box-footer"><a href="index.php?a=21&b=2&act=&caisse=0&iduser=<?php echo $id_user; ?>"><input type="submit" value="Ajouter une transaction" class="btn btn-primary"></a></div>
	<div class="box-footer"><a href="index.php?a=21"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des transactions</button></a></div>
</div></section></div>
<?php
	}		

?>
