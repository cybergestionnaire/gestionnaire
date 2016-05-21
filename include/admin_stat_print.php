	
<?php
/*
    Statistiques des impresions
	nombre d'impressions par mois
	repartition par type couleur / N&B
	nombre de recharges par mois + total cr&eacute;dit
	nombre d'abonn&eacute;s qui impriment en %
*/
$year = date('Y') ;
$month = date('m') ;


// recuperation des tarifs disponibles
$tarifs=getTarifs(1); //1= impressions
// chargement des valeurs pour l'epn par d&eacute;faut
$epn=$_SESSION['idepn'];
//si changment d'epn
 if (TRUE == isset($_POST['modifepn']))
 {
     $epn=$_POST['Pepn'];
	
  }

// Choix de l'epn   -------------------------------------
$espaces=getAllEPN();



$nbuserimprim = getstatimprim($epn);
$nbTotal = getadherenttotal($epn);
$varimp = round(($nbuserimprim*100)/$nbTotal);

//verification que le dossier images des stats existe.
$dossierimg = "img/chart/".$year ;
if(!is_dir($dossierimg)){
   mkdir($dossierimg);
}

?>

 <div class="row"><div class="col-md-6">
                     
<div class="box box-primary">
        <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Param&egrave;tres</h3></div>
	<div class="box-body">
	<form method="post" role="form">
	<div class="input-group"><label>Changer d'espace</label><select name="Pepn"  class="form-control pull-right" style="width: 210px;">
		<?php
		foreach ($espaces AS $key=>$value)
		{
		    if ($epn == $key)
		    {
			echo "<option  value=\"".$key."\" selected>".$value."</option>";
		    }
		    else
		    {
			echo "<option  value=\"".$key."\">".$value."</option>";
		    }
		}
			
	    ?></select>
		<div class="input-group-btn"><button type="submit" value="Rafraichir"  name="modifepn" class="btn btn-default" style="height: 34px;"><i class="fa fa-repeat"></i></button></div>
	</div></form>
	<br>
	<div class="input-group">
	<label>Changer d'ann&eacute;e&nbsp;&nbsp;&nbsp;</label>
		<?php 
		$rowanneesstat=getYearStatPrint();
		while($ans=mysqli_fetch_array($rowanneesstat)){
			echo '<a href="index.php?a=5&b=3&year='.$ans['Y'].'&month=12&day=365&jour=31" > <button class="btn bg-maroon">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=5&b=3&year='.date('Y').'&month='.date('m').'"> <button class="btn bg-maroon"> Ann&eacute;e en cours</button></a>';
		?>
        </div>
	</div><!-- /.box-body-->
</div><!-- /.box -->
 </div><!-- /.col -->
 
 <div class="col-md-6">
 
 <div class="box"><div class="box-header"><h3 class="box-title">Statistiques</h3></div>
		<div class="box-body">
			<a class="btn btn-app" href="index.php?a=5&b=1"><i class="fa fa-users"></i>Adh&eacute;rents<a>
			<a class="btn btn-app" href="index.php?a=5&b=2" /><i class="fa fa-clock-o"></i>R&eacute;servations</a>
			<a class="btn btn-app disabled" href="index.php?a=5&b=3"><i class="fa fa-print"></i>Impressions</a>
			<a class="btn btn-app" href="index.php?a=5&b=5" /><i class="fa fa-ticket"></i>Sessions</a>
			<a class="btn btn-app" href="index.php?a=5&b=4" /><i class="fa fa-keyboard-o"></i>Ateliers</a>
			
			
		</div><!-- /.box-body -->
</div><!-- /.box -->
 
 </div><!-- /.col -->
 </div><!--/row-->

 <!-- Small boxes (Stat box) -->
 <div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-aqua">
                <span class="info-box-icon"  style="padding-top:18px"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Abonn&eacute;s</span>
                  <span class="info-box-number"><?php echo getPourcent($nbuserimprim,$nbTotal); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width:<?php echo getPourcent($nbuserimprim,$nbTotal); ?>"></div>
                  </div>
                  <span class="progress-description">
                   <?php echo getPourcent($nbuserimprim,$nbTotal); ?> des abonn&eacute;s impriment 
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
 
 <?php // section comparaison n&b couleur

//retrouver l'ensemble des tarifs NB
$rowtarifsNB=selectPrintTarif(0);
$nbNB=mysqli_num_rows($rowtarifsNB);
for($x=0;$x<$nbNB;$x++){
	$tarif=mysqli_fetch_array($rowtarifsNB);
	$nbN=$nbN+getStatNC($tarif['id_tarif']);
	
	}
	
//retrouver l'ensemble des tarifs pour la couleur
$rowtarifscoul=selectPrintTarif(1);
$nbCou=mysqli_num_rows($rowtarifscoul);
for($x=0;$x<$nbCou;$x++){
	$tarif=mysqli_fetch_array($rowtarifscoul);
	$nbC=$nbC+getStatNC($tarif['id_tarif']);
	
	}
//total des impressions
$nbTotal=$nbN+$nbC;

	
?>
	<div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box bg-gray">
                <span class="info-box-icon" style="padding-top:18px"><i class="fa fa-print"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Noir & blanc </span>
                  <span class="info-box-number"><?php echo getPourcent($nbN,$nbTotal); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: <?php echo getPourcent($nbN,$nbTotal); ?>"></div>
                  </div>
                  <span class="progress-description">
                  
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
	</div><!-- ./col -->
 
	<div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-red">
                <span class="info-box-icon"  style="padding-top:18px"><i class="fa fa-print"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Couleur</span>
                  <span class="info-box-number"><?php echo getPourcent($nbC,$nbTotal); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: <?php echo getPourcent($nbC,$nbTotal); ?>"></div>
                  </div>
                  <span class="progress-description">
                   
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

 
</div><!--/row-->

<div class="row">
<div class="col-md-6">
<!-- Repartition des impressions par semaine -->
<?php
// Affichage du tableau de resultat tranche horaire par semaine et journee
// $mardi= somme d&eacute;pens&eacute;e ce jour-l&agrave; ! listes par journ&eacute;e
//$year="2013";
$mardi= statImprimJS('tuesday',$year);
$mercredi= statImprimJS('wednesday',$year);
$jeudi= statImprimJS('thursday',$year);
$vendredi= statImprimJS('friday',$year);
$samedi= statImprimJS('saturday',$year);
$totalAn=statImprimAn($year);
//debug($mardi);

?>

<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les impressions dans la semaine (<?php echo $year; ?>)</h3></div>
<div class="box-body">
	<div class="statBar">
				<div class="statText">Mardi :</div>
				<div class="statBarContainPurple">
					<div style="width:<?php echo getPourcent($mardi,$totalAn); ?>;" class="statBarPourcentRed">&nbsp;<?php echo getPourcent($mardi,$totalAn); ?></div>
				</div></div>
				<div class="clear"></div>
	<div class="statBar">
				<div class="statText">Mercredi :</div>
				<div class="statBarContainPurple">
					<div style="width:<?php echo getPourcent($mercredi,$totalAn); ?>;" class="statBarPourcentRed">&nbsp;<?php echo getPourcent($mercredi,$totalAn); ?></div>
				</div></div>
				<div class="clear"></div>
	<div class="statBar">
				<div class="statText">Jeudi :</div>
				<div class="statBarContainPurple">
					<div style="width:<?php echo getPourcent($jeudi,$totalAn); ?>;" class="statBarPourcentRed">&nbsp;<?php echo getPourcent($jeudi,$totalAn); ?></div>
				</div></div>
				<div class="clear"></div>
	<div class="statBar">
				<div class="statText">Vendredi :</div>
				<div class="statBarContainPurple">
					<div style="width:<?php echo getPourcent($vendredi,$totalAn); ?>;" class="statBarPourcentRed">&nbsp;<?php echo getPourcent($vendredi,$totalAn); ?></div>
				</div></div>
				<div class="clear"></div>
	<div class="statBar">
				<div class="statText">Samedi :</div>
				<div class="statBarContainPurple">
					<div style="width:<?php echo getPourcent($samedi,$totalAn); ?>;" class="statBarPourcentRed">&nbsp;<?php echo getPourcent($samedi,$totalAn); ?></div>
				</div></div>
			<div class="clear"></div>
</div>
</div>

 </div><!-- /.col -->
 
<div class="col-md-6">
<!-- impressions par mois -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Nombre d'impressions par mois ( en <?php echo $year; ?>)</h3></div>
	<div class="box-body">
	<table class="table"> 
		<thead><tr> 
			<th>&nbsp;</th>
			<th>Imprim&eacute;</th>
			<th >Cr&eacute;dit</th>
			<th >Noir & blanc</th>
			<th>Couleur</th></tr></thead>
		
<?php

//retrouver l'ensemble des tarifs NB
for ($i=1 ; $i<= $month;++$i)
{
	$row=getStatPages($i,$year,$epn);
	$pagesimprime=$row["pages"];
	$credit=$row["montant"];
	
//boucler sur les tarifs	
	$rowtarifsNB=selectPrintTarif(0);
	While($tarifn=mysqli_fetch_array($rowtarifsNB))
	{
		$mNoir=$mNoir+getStatNCbyM($i,$year,$tarifn['id_tarif'],$epn);
	}
	
//retrouver l'ensemble des tarifs pour la couleur
$rowtarifscoul=selectPrintTarif(1);
	While($tarifc=mysqli_fetch_array($rowtarifscoul)){
		$mcouleur=$mcouleur+getStatNCbyM($i,$year,$tarifc['id_tarif'],$epn);
		}
		
//calcul des totaux
$totalNoirBlanc=$totalNoirBlanc+$mNoir;
$totalcouleur=$totalcouleur+$mcouleur;
	$totalpages=$totalpages+$pagesimprime;
	$totalcredit=$totalcredit+$credit;
	
    echo '<tr><td align="right"><a href="">'.getMonth($i).'</a></b>&nbsp;&nbsp;</td>
              <td >'.$pagesimprime.'</td>
			  <td>'.$credit.' &euro;</td>
			<td>'.$mNoir.'</td>
			  <td>'.$mcouleur.'</td></tr>';
//vider pour la suite
	$pagesimprime='';
	$credit='';
	$mNoir='';
	$mcouleur='';
}

	
?>
	<tr ><td>Totaux</td>
				<td ><?php echo $totalpages; ?></td>
				<td><?php echo $totalcredit.' &euro;'?></td>
				<td ><?php echo $totalNoirBlanc; ?></td>
				<td ><?php echo $totalcouleur; ?></td></tr>

			
</table>
</div></div>
 </div><!--/row-->



