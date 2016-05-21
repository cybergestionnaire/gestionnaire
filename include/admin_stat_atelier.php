<?php
include("libchart/classes/libchart.php");
///***** Statistiques d'atelier *** ////
/*
Rajout 2014.
A completer !


*/

// repartition des inscrits par mois et par annee ---
if (TRUE == isset($_GET['month']) AND TRUE==is_numeric($_GET['month']) AND $_GET['month']>0 AND $_GET['month']<13)
	{
		$month = $_GET['month'] ;
		$year =$_GET['year'];
	}
	else
	{
	   $month = date('m');
	   $year=date('Y');
	}
// chargement des valeurs pour l'epn par d&eacute;faut
$epn=$_SESSION['idepn'];
//si changment d'epn
 if (TRUE == isset($_POST['modifepn']))
 {
     $epn=$_POST['pepn'];
	
  }

// Choix de l'epn   -------------------------------------
$espaces=getAllEPN();
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
		
		$rowanneesstat=getYearStatAtelierSessions();
		while($ans=mysqli_fetch_array($rowanneesstat)){
			echo '<a href="index.php?a=5&b=4&year='.$ans['Y'].'&month=12&day=365&jour=31" > <button class="btn bg-maroon">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=5&b=4&year='.date('Y').'&month='.date('m').'"> <button class="btn bg-maroon"> Ann&eacute;e en cours</button></a>';
		?>
        </div>
	</div><!-- /.box-body-->
</div><!-- /.box -->
 </div><!-- /.col -->
 <div class="col-md-6">
<!-- DIV acc&egrave;s direct aux autres param&egrave;tres-->
 <div class="box">
		<div class="box-header">
			<h3 class="box-title">Statistiques</h3>
		</div>
		<div class="box-body">
			<a class="btn btn-app" href="index.php?a=5&b=1"><i class="fa fa-users"></i>Adh&eacute;rents<a>
			<a class="btn btn-app" href="index.php?a=5&b=2"><i class="fa fa-clock-o"></i>R&eacute;servations</a>
			<a class="btn btn-app" href="index.php?a=5&b=3"><i class="fa fa-print"></i>Impressions</a>
			<a class="btn btn-app" href="index.php?a=5&b=5"><i class="fa fa-ticket"></i>Sessions</a>
			<a class="btn btn-app disabled" href="index.php?a=5&b=4"><i class="fa fa-keyboard-o"></i>Ateliers</a>
			
			
		</div><!-- /.box-body -->
</div><!-- /.box -->
 </div><!-- /.col -->
 </div><!--/row-->

 <div class="row"><div class="col-md-6">

<!-- Statistiques pour la declaration les inscrits -->
<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Total des inscrits aux ateliers (<?php echo $year; ?>)</h3></div>
	<div class="box-body">
	<?php 
		$TotInscrits=mysqli_fetch_array(getStatInscrits("a",$year,$epn,1)); //statut==1 ateliers programm&eacute;s
		//$JeunesInscrits=mysqli_fetch_array(getStatJInscrits($year,$epn));
		$AdultesInscrits=$TotInscrits["presents"];
		
	?>
	Nombre total des pr&eacute;sents pour l'ann&eacute;e <?php echo $year; ?> aux ateliers adultes : <b><?php echo $AdultesInscrits; ?></b>. <br>
	Nombre total des pr&eacute;sents pour l'ann&eacute;e <?php echo $year; ?> aux ateliers Jeunes : <b><?php echo $JeunesInscrits["presents"]; ?></b>. <br>
	Pourcentage total des pr&eacute;sents : <?php echo $TotInscrits["presents"];?> pr&eacute;sents sur <?php echo $TotInscrits["inscrits"]; ?> inscrits.
	
	<div class="statBar">
		<div class="statTextVille">Inscriptions</div>
		<div class="statBarContainPurple"><div style="width:<?php echo getPourcent($TotInscrits["presents"],$TotInscrits["inscrits"]); ?>" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($TotInscrits["presents"],$TotInscrits["inscrits"]); ?></div></div>
   
	 </div>
	 </br>
    </div><!-- /box body-->
	
</div><!-- /box-->



<!-- Statistiques pour la declaration les ateliers -->
<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les ateliers pour l'ann&eacute;e(<?php echo $year; ?>)</h3></div>
	<div class="box-body">
	<?php 
	$toutatelier=$TotInscrits["nbateliers"];
	//$jeuneatelier=statAtelierAn($year,1);
	$adulteatelier=$toutatelier;
	//debug($toutatelier);
	?>
	Nombre des ateliers adultes programm&eacute;s : <b><?php echo $adulteatelier; ?></b></br>
	Nombre des ateliers jeunes programm&eacute;s : <b><?php echo $jeuneatelier; ?></b>
	
	<div class="statBar">
		<div class="statTextVille">Jeunes</div>
		<div class="statBarContainPurple">
			<div style="width:<?php echo getPourcent($jeuneatelier,$toutatelier); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($jeuneatelier,$toutatelier); ?></div></div>
	</div>
	</br>
	<div class="statBar">
		<div class="statTextVille">Adultes</div>
		<div class="statBarContainPurple">
	<div style="width:<?php echo getPourcent($adulteatelier,$toutatelier); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($adulteatelier,$toutatelier); ?></div></div>
    </div>
</br>
	</div><!-- /box body-->
	
</div><!-- /box-->



<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Graphique du taux de pr&eacute;sence mensuel (<?php echo $year; ?>)</h3></div>
	<div class="box-body">
	<img src="img/chart/<?php echo $year; ?>/presenceAtelier.png">
	</div><!-- /box body-->
	
</div><!-- /box-->

</div><!-- /col 1 -->

<div class="col-md-6"><!-- col 2 -->
<!-- TAUX DE PRESENCE  -->
<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Pr&eacute;sence moyenne aux ateliers par mois pour <?php echo $year; ?></h3></div>
	<div class="box-body">
		<table class="table table-condensed"> 
				<thead><tr>
					<th></th> 
					<th>Taux de pr&eacute;sence</th></tr></thead><tbody>
		<?php	
		//initialisation des graphiques
		$chart = new VerticalBarChart(425, 300);
		$dataSet = new XYDataSet();
		
		for ($i=1 ; $i<= 12;++$i)
		{
			$rownb=getStatPresents($i,$year,$epn,"a");
			$nbp=mysqli_num_rows($rownb);
			
			
			if ($nbp!=0){
				$row=getStatTPresentMois($i,$year,$epn,"a");
				$tauxPresence=number_format($row,2);
				//graphique du taux de pr&eacute;sence annuel
					$dataSet->addPoint(new Point(getMonth($i), $tauxPresence));
					$chart->setDataSet($dataSet);
					$chart->setTitle("Taux de presence mensuel aux ateliers (".$year.")");
					$chart->getPlot()->getPalette()->setBarColor(array(new Color(152,1,1)));
					$chart->render("img/chart/".$year."/presenceAtelier.png");
					////
				echo '<tr>
						<td><a href="index.php?a=5&b=4&month='.$i.'&year='.$year.'">'.getMonth($i).'</a></b>&nbsp;&nbsp;</td>
						
						<td>'.$tauxPresence.'%</td>
						</tr>';
			}
		}// fin du for
		?>
</tbody></table>
</div></div>
			<?php
						
				$row=getStatPresents($month,$year,$epn,"a"); // retourne l'array de presents/inscrits/label 
				$nbp=mysqli_num_rows($row);
				if ($nbp!=0){
					echo '<div class="box"><div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">D&eacute;tail pour le mois de '.getMonth($month).'</h3></div>
							<div class="box-body"><table class="table table-condensed"> 
								<thead><tr><th>Date</th>
									<th>Nom de l\'atelier</th>
									<th>Nb. d\'inscrits</th>
									<th>Nb. de pr&eacute;sents</th>
									<th>Taux de pr&eacute;sence</th></tr></thead><tbody>
						';  
						
						for ($x=1 ; $x<=$nbp;++$x)
						{
							$presence=mysqli_fetch_array($row);
							
							echo '<tr>
									<td>'.$presence['date_AS'].'</td>
									<td>'.$presence['label_atelier'].'</td>
									<td>'.$presence['inscrits'].'</td>
									<td>'.$presence['presents'].'</td>
									<td>'.$presence['total'].'%</td></tr>';
												  
						}
						echo '</tbody></table></div></div>';
					
				}//fin du if
					

?>


<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Statistiques par cat&eacute;gorie</h3></div>
	<div class="box-body">
<?php
	//initialisation du graphique1 nombre atelier par categorie sur 1 an
		$chartC = new VerticalBarChart(449, 250);
		$dataSetC = new XYDataSet();
	
	//Donnes du graphique
	$nbcategories=CountCategories();
	
	if ($nbcategories>0){
		for ($n=1;$n<=$nbcategories;$n++){
			$categories=mysqli_fetch_array(statAtelierCategorie($year,$n,$epn));
			//debug($categories);
			$dataSetC->addPoint(new Point($categories['label_categorie'] , $categories['npCat']));
		}
		
		//creation du graphique
		
		$chartC->setDataSet($dataSetC);
		$chartC->setTitle("Nombre d'ateliers par categories sur l'annee");
		$chartC->getPlot()->getPalette()->setBarColor(array(new Color(1,105,201)));
		$chartC->render("img/chart/".$year."/categorieAtelier.png");
	}
	
	//GRAPHIQUE 2 : taux de presence par categorie sur l'ann&eacute;e
		$chartC2 = new VerticalBarChart(449, 250);
		$dataSetC2 = new XYDataSet();
	if ($nbcategories>0){
		for ($x=1;$x<=$nbcategories;$x++){
			$categorieT=mysqli_fetch_array(StatPresentsCat($year,$x,$epn));
			//debug($categorieT);
			$tauxP=number_format((($categorieT['NumP']/$categorieT['NumI'])*100),2)." %";
			$dataSetC2->addPoint(new Point($categorieT['label_categorie'] , $tauxP));
		}
		
		//creation du graphique
		
		$chartC2->setDataSet($dataSetC2);
		$chartC2->setTitle("Taux de presence par categories sur l'annee");
		$chartC2->getPlot()->getPalette()->setBarColor(array(new Color(1,105,201)));
		$chartC2->render("img/chart/".$year."/categorieB.png");
	}
	
?>
<img src="img/chart/<?php echo $year?>/categorieAtelier.png">
<img src="img/chart/<?php echo $year?>/categorieB.png">
</div></div>

<!-- Classement des ateliesr : les adh&eacute;rents par classe d'âge-->
<!-- Mettre en evidence la categorie d'âge la plus assidue -->
<div class="box box-primary">
      <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les participations par classe d'&acirc;ge</h3></div>
<div class="box-body">

</div>
</div>

</div><!-- /col-->
</div><!-- /row -->


<!-- nombre d'inscription moyen par usager // chiffre encadre-->
<!-- Age moyen des groups GD // chiffre encadre -->
