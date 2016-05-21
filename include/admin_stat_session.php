<?php
include("libchart/classes/libchart.php");
/*
Fichier de statistique sur les sessions
Classement par type de session
Mettre % des sesions/ateliers annul&eacute;s !!

Ajout 2014 Florence D
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
	
/// condition d'affichage : 1 session dans la base !
$rowstatsession=statSessionAn($year,$epn);
$nbsession=mysqli_num_rows($rowstatsession);


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
			echo '<a href="index.php?a=5&b=5&year='.$ans['Y'].'&month=12&day=365&jour=31" > <button class="btn bg-maroon">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=5&b=5&year='.date('Y').'&month='.date('m').'"> <button class="btn bg-maroon"> Ann&eacute;e en cours</button></a>';
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
			<a class="btn btn-app disabled" href="index.php?a=5&b=5"><i class="fa fa-ticket"></i>Sessions</a>
			<a class="btn btn-app" href="index.php?a=5&b=4"><i class="fa fa-keyboard-o"></i>Ateliers</a>
			
			
		</div><!-- /.box-body -->
</div><!-- /.box -->
 </div><!-- /.col -->
 </div><!--/row-->

<?php

if ($nbsession>0){


?>
 <div class="row"><div class="col-md-6">

<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Les sessions pour l'ann&eacute;e <?php echo $year; ?></h3></div>
	<div class="box-body">
<?php 
	$toutsession=mysqli_fetch_array($rowstatsession);
	
	//debug($toutatelier);
	?>
	Nombre de sessions programm&eacute;es : <b><?php echo $toutsession["nbsession"]; ?></b></br>
	Nombre des pr&eacute;sents : <b><?php echo $toutsession["presents"]; ?></b></br>
	Nombre des inscrits : <b><?php echo $toutsession["inscrits"]; ?></b></br>
	
	<div class="statBar">
		<div class="statBarContainPurple">
		<div style="width:<?php echo getPourcent($toutsession['presents'],$toutsession['inscrits']); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($toutsession['presents'],$toutsession['inscrits']); ?></div></div>
    </div><div class="clear"></div>
	
</div><!-- /.box-body-->
</div><!-- /.box -->



<!-- Mettre en evidence frequentation participation -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Fr&eacute;quentation des sessions (ann&eacute;e <?php echo $year; ?>)</h3></div>
	<div class="box-body">
		<table class="table"> 
				<thead><tr>
					<th>Titre</th> 
					<th>Inscrits</th>
					<th>Pr&eacute;sents</th>
					<th>%</th></tr></thead><tbody>
	<?php
	$nbS=countSession($year,$epn);
$listeSessions=listSession($year,$epn);
	
	for ($x=0 ; $x<$nbS;++$x)
		{
		
		$frequentation=mysqli_fetch_array(statSessionParticip($listeSessions[$x]));
		//debug($listeSessions[$x]);
		if($x==0){$session1=$listeSessions[$x];} //pour la selection
		$pourcentF=($frequentation['presents']/$frequentation['inscrits'])*100;
		
		echo '<tr><td><a href="index.php?a=5&b=5&year='.$year.'&sessionselec='.$listeSessions[$x].' ">'.$frequentation['session_titre'].'</a></td>
				<td>'.$frequentation['inscrits'].'</td>
				<td>'.$frequentation['presents'].'</td>
				<td>'.number_format($pourcentF,2).' %</td></tr>';
		}

	?>
	</tbody></table>
</div><!-- /.box-body-->
</div><!-- /.box -->



</div><!-- /col1 -->



<?php
//liste des cat&eacute;gories croiser avec l'ann&eacute;e $categorie, $year frequentation depuis ann&eacute;e 0
//Categories
$nbcategories=CountCategories();
//donnes du graphique
$chartCat = new VerticalBarChart(425, 300);
$dataSetCat = new XYDataSet();
$y=$year;
if ($nbcategories>0){
	for ($n=1;$n<=$nbcategories;$n++){
			$categories=mysqli_fetch_array(statSessionCategory($n,$y,$epn));
			//debug($categories);
			$particip=round((($categories['presents']/$categories['inscrits'])*100),2)." %";
			$dataSetCat->addPoint(new Point($categories['label_categorie'], $particip));
		}
		
		//creation du graphique
		
		$chartCat->setDataSet($dataSetCat);
		$chartCat->setTitle("Participation par categories en %");
		$chartCat->getPlot()->getPalette()->setBarColor(array(new Color(1,105,201)));
		$chartCat->render("img/chart/".$year."/epn".$epn."_categorieSession.png");
}	

?>

<div class="col-md-6"><!-- col 2 -->
<!-- nombre de sessions programm&eacute;es par cat&eacute;gorie, fr&eacute;quentation -->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Classement des sessions par cat&eacute;gories (<?php echo $year; ?>)</h3></div>
<div class="box-body">
<img src="img/chart/<?php echo $year ; ?>/epn<?php echo $epn; ?>_categorieSession.png">
</div><!-- /.box-body-->
</div><!-- /.box -->


<!-- detail d'une session participation -->
<?php
if(isset($_GET["sessionselec"])){
	$sessionselec=$_GET["sessionselec"];
	}else{
		$sessionselec=$session1;
	}
	$titresession=getsessionamebyid($sessionselec);
?>
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">D&eacute;tail d'une session <?php echo $titresession; ?></h3></div>
<div class="box-body">
<table class="table"> 
				<thead><tr>	<th>date</th> 
					<th>Inscrits</th>
					<th>Pr&eacute;sents</th>
					<th>Absents</th>
					<th>Nbre de places</th>
					<th>Statut</th>
					<th>F%</th></tr></thead><tbody>
<?php
$statutarray=array(
	1=>"Valid&eacute;",
	2=>"Annul&eacute;"
);

	$rowsessionselec=getSessionDetailStat($sessionselec,$epn);
	$nbdates=mysqli_num_rows($rowsessionselec);
	
	for($y=0;$y<$nbdates;$y++){
		$detailsession=mysqli_fetch_array($rowsessionselec);
		$pourcentS=($detailsession['presents']/$detailsession['inscrits'])*100;
	echo '<tr>
					<td>'.$detailsession["date_AS"].'</td>
					<td>'.$detailsession["inscrits"].'</td>
					<td>'.$detailsession["presents"].'</td>
					<td>'.$detailsession["absents"].'</td>
					<td>'.$detailsession["nbplace"].'</td>
					<td>'.$statutarray[$detailsession["statut_programmation"]].'</td>
					<td>'.number_format($pourcentS,2).' %</td>
					</tr>	
	';
	
	
	}
	
	
?>
</tbody></table>
</div></div>

</div></div><!-- col /row -->

<!-- Classement des session par classe d'âge-->

<?php
}
else {  echo geterror(37) ;

}
?>
