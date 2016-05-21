<?php
/*
 2006 Namont Nicolas
 2013 Florence DAUVERGNE
 
 A rajouter : les tranches horaires les plus fr&eacute;quent&eacute;es
*/

include("libchart/classes/libchart.php");

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
$namepn=getCyberName($epn);

$salles=getAllSallesbyepn($epn);
//debug($salles);
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
	<div class="input-group"><label>Changer d'espace</label><select name="pepn"  class="form-control pull-right" style="width: 210px;">
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
		$rowanneesstat=getYearStatResa();
		while($ans=mysqli_fetch_array($rowanneesstat)){
			echo '<a href="index.php?a=5&b=2&year='.$ans['Y'].'&month=12&day=365&jour=31" > <button class="btn bg-maroon">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=5&b=2&year='.date('Y').'&month='.date('m').'"> <button class="btn bg-maroon"> Ann&eacute;e en cours</button></a>';
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
			<a class="btn btn-app" href="index.php?a=5&b=1"><i class="fa fa-users"></i>Adh&eacute;rents</a>
			<a class="btn btn-app disabled" href="index.php?a=5&b=2"><i class="fa fa-clock-o"></i>R&eacute;servations</a>
			<a class="btn btn-app" href="index.php?a=5&b=3"><i class="fa fa-print"></i>Impressions</a>
			<a class="btn btn-app" href="index.php?a=5&b=4"><i class="fa fa-ticket"></i>Sessions</a>
			<a class="btn btn-app" href="index.php?a=5&b=5"><i class="fa fa-keyboard-o"></i>Ateliers</a>
			
			
		</div><!-- /.box-body -->
</div><!-- /.box -->
 
 </div><!-- /.col -->
 </div><!--/row-->

 <div class="row"><div class="col-md-6">
 
 <!-- Graphiques resas sur l'ann&eacute;e -->

<div class="box box-primary"><div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Statistiques de r&eacute;servations, ann&eacute;e <?php echo $year; ?></h3></div>
<?php

	$chart = new VerticalBarChart(900, 300);
	$chart2= new LineChart(900,250);
	$dataSet = new XYDataSet();
	$dataSet2 = new XYDataSet();
	for ($i=1 ; $i<= abs($month);++$i)
	{
		$row = getStatResa($i,$year,$epn);
	 	$dataSet->addPoint(new Point(getMonth($i), getTime($row['duree'])));
		$dataSet2->addPoint(new Point(getMonth($i), $row['nb']));
	}
	$chart->setDataSet($dataSet);
	$chart2->setDataSet($dataSet2);	
	?>
	
	<div class="box-body">
	<?php
		$chart->setTitle("Nombre d'heure de reservation par mois");
		$chart->getPlot()->getPalette()->setBarColor(array(new Color(74,192,242)));
		$chart->render("img/chart/".$year."/heures_resa_an_".$epn.".png");
		
		$chart2->setTitle("Fr&eacute;quence des reservations par mois");
		$chart2->getPlot()->getPalette()->setLineColor(array(new Color(2,119,158),new Color(190,128,255),new Color(255,84,143)));
		$chart2->render("img/chart/".$year."/nombre_resa_an_".$epn.".png");
	?>
	<img src="img/chart/<?php echo $year?>/heures_resa_an_<?php echo $epn; ?>.png" width="500px"><br>
	<img src="img/chart/<?php echo $year?>/nombre_resa_an_<?php echo $epn; ?>.png"  width="500px">
	</div>
</div>

 <!-- TABLEAU RESERVATIONS PAR MOIS/ANNEE -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Nombre de r&eacute;servations par mois, ann&eacute;e <?php echo $year; ?></h3></div>
<?php
	
for ($i=1 ; $i<12;++$i)
{
    $row = getStatResa($i,$year,$epn);
    $_SESSION['stat_hour'][$i] = $row['nb'] ;
    $_SESSION['stat_resa'][$i] = $row['duree'] ;
}

?>
          
<div class="box-body">
	<table class="table" > 
		<thead><tr><th></th><th>Nb. d'heures</th><th>Nb. </th><th>Cumul.</th></tr></thead><tbody>
	<?php

	for ($i=1 ; $i<= abs($month);++$i)
	{
		$row = getStatResa($i,$year,$epn);
		$tot1 = $tot1+$row['duree'];
		$tot2 = $tot2+$row['nb'] ;
		echo '<tr><td align="right"><a href="index.php?a=5&b=2&month='.$i.'&year='.$year.'">'.getMonth($i).'</a></td>
				  <td>'.getTime($row['duree']).'</td>
				  <td>'.$row['nb'].'</td>
				  <td>'.getTime($tot1).'('.$tot2.')</td></tr>';
	}
	?>
	</tbody></table>
	
	
</div></div>


</div><!-- /col-->

<!-- colonne de droite -->
<div class="col-md-6">

<!-- Classement des r&eacute;servations : Fr&eacute;quence des visites -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Fr&eacute;quences des visites d'abonn&eacute;s, ann&eacute;e <?php echo $year; ?></h3></div>
<div class="box-body">
<?php
//Initialisation graphique
$chartF = new PieChart(400, 280);
$dataSetF = new XYDataSet();
$row = getStatFrequence($year,$epn);

//debug($row);

$dataSetF->addPoint(new Point("1 &agrave; 2 fois par semaine", $row['f1']));
$dataSetF->addPoint(new Point("2 &agrave; 3 fois par semaine", $row['f2']));
$dataSetF->addPoint(new Point("+3 fois par semaine", $row['f3']));
$chartF->setDataSet($dataSetF);
$chartF->getPlot()->getPalette()->setPieColor(array(new Color(255,133,4),new Color(234,42,83),new Color(44,173,135)));

$chartF->setTitle("Frequence des visites (".$year.") ");
$chartF->render("img/chart/".$year."/resa_frequenceSemaine_".$epn.".png");

?>
<img src="img/chart/<?php echo $year; ?>/resa_frequenceSemaine_<?php echo $epn; ?>.png" >
        </div><!-- /.box-body-->
    </div><!-- /.box -->

<!-- Classement des r&eacute;servations : les adh&eacute;rents par classe d'&acirc;ge-->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les r&eacute;servations par classe d'&acirc;ge, ann&eacute;e <?php echo $year; ?></h3></div>
          
<div class="box-body">
			<table class="table"> 
			<thead><tr><th>Mois</th>
						<th>Enfants - de 14 ans</th>
						<th>Adolescents(15-25)</th>
						<th>Adultes</th>
						
						<th>% par mois</th></tr></thead><tbody> 
<?php

//initialisation graphique//
$chartCA= new LineChart(600,250);
$dataSetCA = new XYSeriesDataSet();
$serie1 = new XYDataSet();
$serie2 = new XYDataSet();
$serie3 = new XYDataSet();

	for ($i=1 ; $i<= $month;++$i)
{
    $nbTr1 =  getStatFrequenceTypeAbo($i,1,14,$year);
	$nbTr2 =  getStatFrequenceTypeAbo($i,15,25,$year);
	$nbTr3 =  getStatFrequenceTypeAbo($i,20,99,$year);
	//donnees du graphique
	$serie1->addPoint(new Point(getMonth($i), $nbTr1));
	$serie2->addPoint(new Point(getMonth($i), $nbTr2));
	$serie3->addPoint(new Point(getMonth($i), $nbTr3));
	
	$row2= getStatHeureTypeAbo($year,$i);
	$hparm=ceil(($row2*100)/56160);
	
	echo '<tr>
			<td>'.getMonth($i).'</td>
            <td>'.$nbTr1.'</td>
            <td>'.$nbTr2.'</td>
            <td>'.$nbTr3.'</td>
			
			<td>'.$hparm.' %</td></tr>';
	
}
	///Creation du graphique
	
	$dataSetCA->addSerie("Enfants", $serie1);
	$dataSetCA->addSerie("Adolescents", $serie2);
	$dataSetCA->addSerie("Adultes", $serie3);
	
	$chartCA->setDataSet($dataSetCA);
	$chartCA->setTitle("Frequence des reservations par age (".$year.")");
	$chartCA->getPlot()->setGraphCaptionRatio(0.62);
	$chartCA->getPlot()->getPalette()->setLineColor(array(new Color(2,119,158),new Color(190,128,255),new Color(255,84,143)));
	$chartCA->render("img/chart/".$year."/frequenceParAge.png");
?>




</tbody></table>
</div><div class="box-body">
<img src="img/chart/<?php echo $year; ?>/frequenceParAge.png" width="420px" >
        </div><!-- /.box-body-->
    </div><!-- /.box -->



</div></div>




 <div class="row"><div class="col-md-12"><!-- row 2 -->

<!--// Affichage du tableau de resultat par mois et stat journalieres-->

<?php

  $nb_jour  = date("t", mktime(0, 0, 0, $month, 1, $year));
  //$nb_jour=date('j');
	
   //insertion du graph libchart
   $chart3 = new VerticalBarChart(900, 300);
   $dataSet3 = new XYDataSet();
    ?>
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Nombre d'heures de r&eacute;servation par jour pour le mois de <?php echo getMonth($month); ?>  </h3></div>
<div class="box-body">
           <?php
            for ($i=1 ; $i<=$nb_jour;++$i)
            {
                $result = getStatResaByDay($year.'-'.$month.'-'.$i,$epn) ;
				//debug($result['duree']);
                $dataSet3->addPoint(new Point($i, $result['duree']));
				$maxBound[$i]=$result['duree'];
			 }
         $maxValue=ceil(max($maxBound)/60);
		 $chart3->setDataSet($dataSet3);
        $chart3->setTitle("Detail des heures pour ".getMonth($month)." ".$year." ");
		$chart3->getPlot()->getPalette()->setBarColor(array(new Color(128,195,28)));
		$chart3->getPlot()->setLabelGenerator(new TimeLabelGenerator());
		// arrondir la valeur maximale sup&eacute;rieure
				
		$chart3->getBound()->setUpperBound($maxValue * 60);
		//  $chart3->getTics()->quantizeTics(12);
		$chart3->render("img/chart/".$year."/".$epn."_resa-Mensuelle-".$month.".png");
            ?>
   <!-- rendu du graphique sous forme d'image-->      
	<img src="img/chart/<?php echo $year; ?>/<?php echo $namepn; ?>_resa-Mensuelle-<?php echo $month; ?>.png">
 </div></div>



<!-- Frequentation par tranche horaires  et par semaine-->
 <h2 class="page-header">R&eacute;partition par tranche horaire journali&egrave;res, ann&eacute;e <?php echo $year; ?></h2>
<div class="col-md-6">
		<div class="nav-tabs-custom">
	
<?php
///comptabiliser les connexions pour la journ&eacute;e choisie	
for ($d=2; $d<7;++$d){

$nomjour=getJourEng($d);
$nbPoste=getnbcomputperepn($epn);
//initialisation graphiques
$chartMardi = new HorizontalBarChart(440,400);
$dataSetMardi = new XYSeriesDataSet();
for ($i=0 ; $i<2;++$i)
{
	$y=$year-$i;
	$serieMardi="serieMardi$y";// series par ann&eacute;es
	$serieMardi = new XYDataSet(); 
	//debug($serieMardi);
	if ($y<$year)
	{
		$nombreSemaines=52;
	}
	else
	{
		$nombreSemaines=date('W');
	}

	if (isset($nomjour)){
		$n0TH1 = statTrancheHour(600,660,$nomjour,$y,$epn); //de 10h &agrave; 11h : h 600-659
		$n0TH2 = statTrancheHour(661,720,$nomjour,$y,$epn); //de 11h &agrave; 12h : h 660-720
		$n0TH3 = statTrancheHour(721,839,$nomjour,$y,$epn); // rajout tranche du midi 12h &agrave; 14h
		$n0TH4 = statTrancheHour(840,900,$nomjour,$y,$epn); //de 14h &agrave; 15h : h 840-899
		$n0TH5 = statTrancheHour(901,960,$nomjour,$y,$epn); //de 15h &agrave; 16h : h 900-959
		$n0TH6 = statTrancheHour(961,1020,$nomjour,$y,$epn); //de 16h &agrave; 17h : h 960-1019
		$n0TH7 = statTrancheHour(1021,1080,$nomjour,$y,$epn); //de 17h &agrave; 18h : h 1020-1079
		$n0TH8 = statTrancheHour(1081,1170,$nomjour,$y,$epn); //de 18h &agrave; 19h30 : h 1080-1170
		
	// pourcentage en fonction du nombre de semaines depuis la date choisie;
		$nTH1=round((($n0TH1/$nombreSemaines)/$nbPoste)*100);
		$nTH2=round((($n0TH2/$nombreSemaines)/$nbPoste)*100);
		$nTH3=round((($n0TH3/$nombreSemaines)/$nbPoste)*100);
		$nTH4=round((($n0TH4/$nombreSemaines)/$nbPoste)*100);
		$nTH5=round((($n0TH5/$nombreSemaines)/$nbPoste)*100);
		$nTH6=round((($n0TH6/$nombreSemaines)/$nbPoste)*100);
		$nTH7=round((($n0TH7/$nombreSemaines)/$nbPoste)*100);
		$nTH8=round((($n0TH8/$nombreSemaines)/$nbPoste)*100);
		
	}
	if ($d==3 or $d==6 ){
	$serieMardi->addPoint(new Point("10h-11h", $nTH1));
	$serieMardi->addPoint(new Point("11h-12h", $nTH2));
	$serieMardi->addPoint(new Point("12h-14h", $nTH3));
	}
	$serieMardi->addPoint(new Point("14h-15h", $nTH4));
	$serieMardi->addPoint(new Point("15h-16h", $nTH5));
	$serieMardi->addPoint(new Point("16h-17h", $nTH6));
	$serieMardi->addPoint(new Point("17h-18h",  $nTH7));
	if ($d==2){
	$serieMardi->addPoint(new Point("18-19h30", $nTH8));
	}
	$dataSetMardi->addSerie($y, $serieMardi);
	$chartMardi->setDataSet($dataSetMardi);
	//debug($nTH4);


	}	
	
	$chartMardi->getPlot()->getPalette()->setBarColor(array(new Color(144,213,236),new Color(229,87,91),new Color(167,119,229)));
	$chartMardi->setTitle("R&eacute;partition de la fr&eacute;quentation sur le ".getDay($d)." en %");
	$chartMardi->render("img/chart/".$year."/resa-horaire-".getDay($d).".png");
}

	//affichage des charts
	
	 echo ' <ul class="nav nav-tabs">';
	
	for ($d=2; $d<7;++$d){
		if($d==2){ $class="active"; }else{ $class=""; }
		
		echo '<li class="'.$class.'"><a href="#tab_'.$d.'" data-toggle="tab">'.getDay($d).'</a></li>';
	}; ?>
                  
    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                </ul>
                <div class="tab-content">
								
			<?php 			
			 for ($d=2; $d<7;++$d){     
				if($d==2){ $class="active"; }else{ $class=""; }
			 echo '
						<div class="tab-pane '.$class.'" id="tab_'.$d.'">
							<img src="img/chart/'.$year.'/resa-horaire-'.getDay($d).'.png" >
						</div>
					 ';
				}

	
?>
</div></div> 
</div>


 <div class="row"><div class="col-md-6"><!-- row 2 -->
<!-- Classement des r&eacute;servations : les adh&eacute;rents par CSP -->

<!-- Classement des r&eacute;servations : par poste -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les r&eacute;servations par machine (toutes salles), ann&eacute;e <?php echo $year; ?></h3></div>
<div class="box-body">

<?php

//stat par machine
        $result = getStatResaComputer($month,$year,$epn);
			
        $j=0;
        if (mysqli_num_rows($result)!=0)
        {
            echo '<div>
                        <table class="table">
                        <tr><td width="10%">&nbsp;</td>
                        <td>Nom</td>
                        <td >Nb. d\'heures</td>
                        <td>Nb. de r&eacute;servations</td></tr>';
            while ($row = mysqli_fetch_array($result))
            {
                echo '<tr><td>'.$j.'</td>
										 <td>'.$row['nom_computer'].'&nbsp;&nbsp;</td>
										 <td >'.getTime($row['duree']).'</td>
										 <td >'.$row['nb'].'</td></tr>';
										 ++$j ;
            }
            echo '</table></div>';
        }

?>

</div></div>

</div>

</div>
 