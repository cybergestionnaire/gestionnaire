<?php
/*
  
 2006 Namont Nicolas
 
 include/user_atelier.php V0.1
*/
$b = $_GET["b"];
$idatelier = $_GET["idatelier"];
$idsession=$_GET["idsession"];
$user=getUser($_SESSION["iduser"]);
$epnuser=$user["epn_user"];
 //statut de l'atelier
$stateAtelier = array(
               1=> "Programm&eacute;",
               2=> "En programmation",
               3=> "Annul&eacute;" );

$espaces = getAllepn();

// si b =2 inscription a un atelier
if ($b==2)
{
  if (FALSE != addUserAtelier($idatelier,0,$_SESSION["iduser"]))
  {
      echo "<div class=\"row\"><div class=\"col-md-6\"> <div class=\"alert alert-info alert-dismissable\"> 
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                    <h4><i class=\"icon fa fa-info\"></i>Inscription valid&eacute;e</h4></div></div></div>" ;
  }
}
// si b=3 desinscription a un atelier
if ($b==3)
{
  delUserAtelier($idatelier,$_SESSION["iduser"]) ;
  echo "<div class=\"row\"><div class=\"col-md-6\"> <div class=\"alert alert-info alert-dismissable\"> 
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
				 <h4><i class=\"icon fa fa-info\"></i>D&eacute;sinscription effectu&eacute;e</h4></div></div></div>" ;
}

//si b==6 inscription a une session
if ($b==6)
{
   //verification d'inscription
    if (FALSE != checkUserSession($idsession,$_SESSION["iduser"]))
       {
				$nbrdates=$_GET["nbd"];
       $datesarray2=getDatesSession($idsession);
       //boucler pour ins&eacute;rer le nombre de dates par sessions
				for ($f=0; $f<$nbrdates ; $f++){
					$row2=mysqli_fetch_array($datesarray2);
					addUserSession($idsession,$_SESSION["iduser"],0,$row2["id_datesession"]);
				}
       
			 echo geterror(25);
		
	  
      }else{
      	echo geterror(21);
	
      }
}
// si b=7 desinscription a une session
if ($b==7)
{
  delUserSession($idsession,$_SESSION["iduser"]) ;
  echo "<div class=\"row\"><div class=\"col-md-6\"> <div class=\"alert alert-info alert-dismissable\"> 
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
				 <h4><i class=\"icon fa fa-info\"></i>D&eacute;sinscription effectu&eacute;e</h4></div></div></div>" ;
}

//*************** si b =1 affichage de l'atelier en detail

if ($b == 1 OR $b==5 )  
{
  switch ($b)
         {
        case 1:
			$row = getAtelier($idatelier,0);
			$idsujet=$row["id_sujet"];
			$result=getSujetById($idsujet);
			$rowsujet=mysqli_fetch_array($result);
			
			if ($row["prix_atelier"] == 0)
				{
					 $prix = "Gratuit";
				}else{
					 $prix = $row["prix_atelier"]."&euro;" ;
				}
			
				$nbplacerestantes = $row["nbplace_atelier"]-countPlace($idatelier);
				$nbplaceatelier=$row["nbplace_atelier"];
			if ($nbplace < 0) {
			$nbplace=0;}
			$titreatelier=$rowsujet["label_atelier"];
			$detailatelier=$rowsujet["content_atelier"];
			$dureeatelier=getTime($row["duree_atelier"]);
			$dateheure=getDayfr($row["date_atelier"])." &agrave; ".$row["heure_atelier"];
			$salle=mysqli_fetch_array(getSalle($row["salle_atelier"]));
			$nomsalle=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
			$anim= getUserName($row["anim_atelier"]);
			$posturl="index.php?m=6&b=2&idatelier=".$idatelier ;
			$bouton="s'inscrire &agrave; cet atelier";
			
			break;
	
			case 5:
			$rowsession = getSession($idsession);
			$titresession=getTitreSession($rowsession["nom_session"]);
			$titreatelier=stripslashes($titresession["session_titre"]);
			$detailatelier=stripslashes($titresession["session_detail"]);
			
			$anim=getUserName($rowsession["id_anim"]);
			$salle=mysqli_fetch_array(getSalle($rowsession["id_salle"]));
			$nomsalle=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
			$prix=getNomTarif($rowsession["id_tarif"]);
			$nbplaceatelier=$rowsession["nbplace_session"];
			$nbplacerestantes=$nbplaceatelier-countPlaceSession($idsession,0);
			//Afficher les dates de la session
			$nbrdates=$rowsession["nbre_dates_sessions"];
			$datesarray=getDatesSession($idsession);

			for ($f=0; $f<$nbrdates ; $f++){
			$rowd=mysqli_fetch_array($datesarray);
			
			$dateheure= $dateheure.getDatefr($rowd["date_session"])." <br>";
		//	$iddate=$rowd["id_datesession"]; //donner la derniere id
			}
			
			$posturl="index.php?m=6&b=6&idsession=".$idsession."&nbd=".$nbrdates ;
			$bouton="s'inscrire &agrave; cette session";
			$break;
	
				 }
    ?>
    
<div class="row">


 <section class="col-lg-7 connectedSortable">
 <div class="box box-success"><div class="box-header"> <h3 class="box-title"><?php echo $titreatelier;?></h3></div>
				<div class="box-body">
				<dl class="dl-horizontal">
				<dt>Date</dt><dd>Le <?php echo $dateheure;?></dd>
				<dt>Anim&eacute; par </dt><dd><?php echo $anim;?></dd>
         <dt>Dur&eacute;e </dt><dd><?php echo $dureeatelier;?></dd>
					<dt>Lieu </dt><dd> <?php echo $nomsalle;?></dd>
           <dt>Tarif</dt><dd><?php echo $prix ;?></dd>
          <dt>Places ouvertes</dt><dd><?php echo $nbplacerestantes ;?> (Au total : <?php echo $nbplaceatelier;?> places)</dd>
           <dt>D&eacute;tail</dt><dd><?php echo $detailatelier;?></dd>
			  </div>
			  
          <div class="box-footer">
			<a href="index.php?m=6"><input type="submit" name="" value="Retour" class="btn btn-primary  pull-right"></a>
			<form method="post" action="<?php echo $posturl; ?>">
			<input type="submit" name="submit" value="<?php echo $bouton; ?>" class="btn btn-success "></form>
			 </div>
	</div>
	</section>
	<section class="col-lg-5 connectedSortable">
	<div class="box box-solid box-primary"><div class="box-header"><h3 class="box-title">Aide</h3></div>
	<div class="box-body">
		<p class="lead">Si vous souhaitez vous inscrire, cliquez sur le bouton "s'inscrire", pour quitter la page cliquez sur "retour &agrave; la liste"</p>

	</div></div>
</section>

	</div><!-- /row-->
			   
    <?php
	
}
//***FIN DETAIL ATELIERS //****
else
{


//**** INSCRIPTIONS DE l'adh&eacute;rent ****//
/*
$result = getMyAtelier($_SESSION["iduser"],1,0)  ;
$nb     = mysqli_num_rows($result);

$ListeSessionEnCours=getMySession($_SESSION["iduser"]);
$numSession=mysqli_num_rows($ListeSessionEnCours);

*/

//************** Affichage de la liste des ateliers affichage par defaut ****///
//La liste des ateliers
	 $listeAtelier = getMyFutAtelier(date('Y'), date('n'), date('d'));
		$nba=mysqli_num_rows($listeAtelier);
// la liste des sessions 
	$futsessionrow = getFutsessions(0);
	$nbsessionsprog=mysqli_num_rows($futsessionrow);
	
	
		?> 
	<div class="row">
	<!-- criteres de choix -->
	<div class="col-md-3">
	<div class="box"><div class="box-header"><h3 class="box-title">Crit&egrave;res</h3> </div>
		<div class="box-body">
		<form role="form" method="POST" action="#">
			<div class="form-group"><label>Cat&eacute;gories</label>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Jeunesse</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Syst&egrave;me d'exploitation</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Web</div>
					 </div>
			<div class="form-group"><label>Niveau</label>
					<div class="checkbox"><input type="checkbox" name="cat1" value="cat1">D&eacute;butant</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Autonome</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Admin</div>
					</div>
			<div class="form-group"><label>Dates</label>
					<div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Mois en cours</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Mois suivant</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">6 mois</div>
					</div>
		</form>
		</div></div>
	</div>
	
	<div class="col-md-9">
	<div class="box box-primary">
	<div class="box-header"><h3 class="box-title">Liste des ateliers propos&eacute;s pour <?php echo date('Y'); ?></h3></div>
	<div class="box-body">
		<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_3" data-toggle="tab">Les ateliers (<?php echo $nba;?>)</a></li>
					<li><a href="#tab_4" data-toggle="tab">Les sessions (<?php echo $nbsessionsprog; ?>)</a></li>
					</ul>
				<div class="tab-content">
					
				<div class="tab-pane active" id="tab_3">
				<?php 
					if ($nba > 0)
				{
					
					?>
				<table class="table table-condensed">
					<tr><th>Date</th><th>Heure</th><th>Dur&eacute;e</th><th>Titre</th><th>Niveau</th><th>Lieu</th><th>Places restantes</th><th>Inscription</th>	</tr>				
				<?php
				
					 for ($x=1 ; $x<=$nba;++$x)
						{
							$rowateliers = mysqli_fetch_array($listeAtelier) ;
							$idsujet=$rowateliers["id_sujet"];
							$rowsujet=mysqli_fetch_array(getSujetById($idsujet));
							$nbplace = $rowateliers["nbplace_atelier"]-countPlace($rowateliers["id_atelier"]);
							
							$salle=mysqli_fetch_array(getSalle($rowateliers["salle_atelier"]));
							$lieuA=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
							
							if ($nbplace < 0) {
							$nbplace=0;}
							$idatelier=$rowateliers["id_atelier"];
							//Test inscription
							$testinscription=getTestInscript($_SESSION["iduser"],$idatelier,"a");
						if ($rowateliers["statut_atelier"]<3){	
							if($testinscription=="FALSE"){
								if ($nbplace>0){
								$boutoninscr="<a href=\"index.php?m=6&b=1&idatelier=".$idatelier."\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire</small></a>";
								}else{
								$boutoninscr="";
								}
							}else{
							$boutoninscr="<small class=\"badge bg-green\">d&eacute;j&agrave; inscrit</small>&nbsp; 
							<a href=\"index.php?m=6&b=3&idatelier=".$idatelier."\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
							
							}
						}else{
							$boutoninscr="<small class=\"badge bg-yellow\">Annul&eacute;</small>";
						
						}
						
							//niveau de l'atlier
							$plevel = getAllLevel(1) ;	
							 echo "<tr>
									<td>".datefr($rowateliers["date_atelier"])."</td>
									<td>".$rowateliers["heure_atelier"]."</td>
									<td>".getTime($rowateliers["duree_atelier"])."</td>
								<td>".$rowsujet["label_atelier"]."</td>
								<td>".$plevel[$rowsujet["niveau_atelier"]]."</td>
									<td>".$lieuA."</td>
											<td>"; 
											if ($nbplace==0){echo '<span class="badge bg-purple">COMPLET</span>'; }	else{echo $nbplace;}
											echo "</td>
										<td>".$boutoninscr."</td>
										</tr>";
						} 
						
						?>
						</table>
						<?php
				}else {
							echo '  <div class="alert alert-info alert-dismissable">
									<i class="fa fa-info"></i>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucun atelier programm&eacute; pour le moment</div>' ;
						}
						?>
				
				
				
				</div>
				
				
				<div class="tab-pane" id="tab_4">
      <?php if ($nbsessionsprog > 0){ ?>
			<table class="table table-condensed">
					 <tr><th>Dates</th><th>Titre</th><th>Lieu</th><th>Places restantes</th><th></th></tr>
					<?php 
				for ($j=1 ; $j <=$nbsessionsprog ; $j++)
					{
					$rowsession = mysqli_fetch_array($futsessionrow) ;
					//elements					
					$titresession=getTitreSession($rowsession["nom_session"]);
					//affichage de toutes les dates de la session
					$datesarray=getDatesSession($rowsession["id_session"]);
					$salle=mysqli_fetch_array(getSalle($rowsession["id_salle"]));
					$lieuS=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
					$nbrdates=$rowsession["nbre_dates_sessions"];
					$listedatess='';
					
						for ($f=0; $f<$nbrdates ; $f++){
							$rowdates=mysqli_fetch_array($datesarray);
							$dd=date_create($rowdates["date_session"]);
							$listedatess=$listedatess.date_format($dd,"Y/m/d H:i")."</br>";
						}
						//nombre de places pour la session
						$placesoccupee=countPlaceSession($rowsession["id_session"],0);
						$nbplace=$rowsession["nbplace_session"];
						$placesrestantes=$nbplace-$placesoccupee;
					// les boutons d'action
						$testinscription=getTestInscript($_SESSION["iduser"],$rowsession["id_session"],"s");
					
					
					if ($rowsession["status_session"]<2){	
							if($testinscription=="FALSE"){
								if ($placesrestantes>0){
								$boutoninscrsession="<a href=\"index.php?m=6&b=5&idsession=".$rowsession["id_session"]."\"><small class=\"badge bg-default\">voir le d&eacute;tail et s'inscrire</small></a>";
								}else{
									$boutoninscrsession="";
								}
							}else{
								
							$boutoninscrsession="<small class=\"badge bg-green\">d&eacute;j&agrave; inscrit</small>&nbsp; 
							<a href=\"index.php?m=6&b=5&idsession=".$rowsession["id_session"]."\"><button class=\"btn btn-xs btn-danger\" data-toggle=\"tooltip\" title=\"Se d&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a>";
							
							}
						}else{
							$boutoninscrsession="<small class=\"badge bg-yellow\">Annul&eacute;</small>";
						
						}
					
					//affichage
					echo '<tr> 
									<td><small>'.$listedatess.'</small></td>
									<td><span class="text-muted">'.$titresession["session_titre"].'</span></td>
									<td>'.$lieuS.'</td>						
									<td>';
									if ($placesrestantes==0){echo '<span class="badge bg-purple">COMPLET</span>'; }	else{echo $placesrestantes;}
									
									echo '</td>
									<td>'.$boutoninscrsession.'</td></tr>';
					}
					
			 ?>
						
						</table>
				
				<?php
				}else{
				echo '<div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune session programm&eacute;e</div>' ;
				
				}
				?>
       				
				</div><!--/ tab_pane -->
				
				</div><!--/ tab_content -->
		
		</div><!-- /nav-tab-->
		
		
</div><!-- / box body-->
</div><!-- / box-->
</div><!-- / col -->
</div><!-- /row -->
		
<?php



}
?>
