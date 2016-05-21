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
               1=> "Programmé",
               2=> "En programmation",
               3=> "Annulé" );

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
       //boucler pour insérer le nombre de dates par sessions
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

//**** INSCRIPTIONS DE l'adhérent ****//
$result = getMyAtelier($_SESSION["iduser"],1,0)  ;
$nb     = mysqli_num_rows($result);

$ListeSessionEnCours=getMySession($_SESSION["iduser"]);
$numSession=mysqli_num_rows($ListeSessionEnCours);


	
	?>
	<div class="row">
	
	<div class="col-md-12">
	<div class="box box-warning"><div class="box-header"><h3 class="box-title">Vos inscriptions actuelles</h3></div>
		<!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab">Mes ateliers</a></li>
                  <li><a href="#tab_2" data-toggle="tab">Mes sessions</a></li>
                 
                </ul>
                <div class="tab-content">
								
					<!-- Les ateliers ou l'adherent est inscrit -->
					
					<div class="tab-pane active" id="tab_1">
					<?php if ($nb > 0)
					{								 
								?>
                    <table class="table"><thead>
								<tr>
										<th>Date et heure</th><th>Dur&eacute;e</th><th>Nom de l'atelier</th><th>Lieu</th><th>Désincription</th></tr></thead><tbody>
								<?php
									for ($i=1 ; $i<=$nb ; $i++)
									{
											$row = mysqli_fetch_array($result) ;
											$result2=getSujetById($row["id_sujet"]);
											$salle=mysqli_fetch_array(getSalle($row["salle_atelier"]));
											$lieuatelier=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
											$rowsujet=mysqli_fetch_array($result2);
											echo "<tr>
													<td>".getDayfr($row["date_atelier"])." (".$row["heure_atelier"].")</td>
														<td>".getTime($row["duree_atelier"])."</td>
													<td>".$rowsujet["label_atelier"]."</td>
													<td>".$lieuatelier."</td>
													<td><a href=\"index.php?m=6&b=3&idatelier=".$row["id_atelier"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" title=\" se désinscrire\"></i></button></a></td></tr>";
									}
									echo 	'</tbody></table>';
								// ateliers en attente 
	
									$resultA = getMyAtelier($_SESSION["iduser"],1,2)  ;
									$nbattente = mysqli_num_rows($resultA);
									
									if ($nbattente > 0){ ?>
										<hr><p class="text-warning">Vous &ecirc;tes inscrit(e) sur liste d'attente aux ateliers suivants :</p>
												<table class="table">
												<thead>
												<tr class="text-warning">
												<th>Date et heure</th><th>Dur&eacute;e</th><th>Nom de l'atelier</th><th>Lieu</th><th>Désincription</th></tr></thead><tbody>
									<?php			
										for ($i=1 ; $i<=$nbattente ; $i++)
										{
												$rowA = mysqli_fetch_array($resultA) ;
												$result2=getSujetById($rowA["id_sujet"]);
												$rowsujetA=mysqli_fetch_array($result2);
												$salle=mysqli_fetch_array(getSalle($row["salle_atelier"]));
												$lieuatelierA=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
												echo "<tr>
														<td >".dateFr($rowA["date_atelier"])." (".$rowA["heure_atelier"].")</td>
															<td>".getTime($rowA["duree_atelier"])."</td>
														<td>".$rowsujetA["label_atelier"]."</td>
														<td>".$lieuatelierA."</td>
														<td><a href=\"index.php?m=6&b=3&idatelier=".$row["id_atelier"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" title=\" se désinscrire\"></i></button></a></td></td></tr>";
										}
										?>
									</table>
									<?php
										}
									
					} else {
					echo  '<div class="alert alert-info alert-dismissable">
							<i class="fa fa-info"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Vous ne participez &agrave; aucun atelier</div>' ;
					}
									
									?>
								</div><!-- /.tab-pane atelier-->
								
								<!-- tab pane les sessions -->
<div class="tab-pane" id="tab_2">
<?php	if ($numSession>0){ ?>

<table class="table"><thead><tr><th>Date et heure</th><th>Nom de la session</th><th>Statut</th><th>Lieu</th><th>Désinscription</th></tr></thead><tbody>

<?php
	//numeros des sessions deja validés
		for ($j=1 ; $j<=$numSession ; $j++)
		{
			$rowLVS = mysqli_fetch_array($ListeSessionEnCours) ;
			$arraysessionLVS=getSession($rowLVS["id_session"]);
			$sujetLVS=mysqli_fetch_array(getSujetSessionById($arraysessionLVS["nom_session"]));
			$salle=mysqli_fetch_array(getSalle($rowLVS["id_salle"]));
			$lieuSession=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
			if($rowLVS["statut_datesession"]==1){
				$class= "text-muted";
				if($rowLVS["status_rel_session"]==1){ $presence="Présent"; }else{ $presence="Absent";}
			}else{
			$class="";
			$presence="inscrit(e)";
			}
				echo "<tr class=".$class.">
						<td>".getDatefr($rowLVS["date_session"])."</td>
				<td>".$sujetLVS["session_titre"]." (".$j.") </td>
						<td>".$presence."</td>
						<td>".$lieuSession."</td>
						<td><a href=\"index.php?m=6&b=7&idsession=".$rowLVS["id_session"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" title=\" se d&eacute;sinscrire\"></i></button></a></td></tr>";
			
		}
	echo 	'</tbody></table>';
	
	$resultsessionattente=getMySessionAttente($_SESSION["iduser"]);
	$nbrsessionattente=mysqli_num_rows($resultsessionattente);
	
	if($nbrsessionattente>0){
	?>
	<hr><p class="text-warning">Vous &ecirc;tes inscrit(e) sur liste d'attente aux sessions suivantes :</p>
			<table class="table"><thead><tr class="text-warning"><th>Date et heure</th><th>Dur&eacute;e</th><th>Nom de l'atelier</th><th>Lieu</th><th>Désincription</th></tr></thead><tbody>
												
	<?php
		for ($k=1 ; $k<=$nbrsessionattente ; $k++)
		{
			$rowLVSA = mysqli_fetch_array($resultsessionattente) ;
			$arraysessionLVSA=getSession($rowLVSA["id_session"]);
			$sujetLVSA=mysqli_fetch_array(getSujetSessionById($arraysessionLVSA["nom_session"]));
			$salle=mysqli_fetch_array(getSalle($rowLVSA["id_salle"]));
			$lieuSession=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
			if($rowLVSA["statut_datesession"]==1){
				$class= "text-muted";
				if($rowLVSA["status_rel_session"]==1){ $presence="Présent"; }else{ $presence="Absent";}
			}else{
			$class="";
			$presence="inscrit(e)";
			}
				echo "<tr class=".$class.">
						<td>".getDatefr($rowLVSA["date_session"])."</td>
				<td>".$sujetLVSA["session_titre"]." (".$k.") </td>
						<td>".$presence."</td>
						<td>".$lieuSession."</td>
						<td><a href=\"index.php?m=6&b=7&idsession=".$rowLVSA["id_session"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" title=\" se d&eacute;sinscrire\"></i></button></a></td></tr>";
			
		}
		?>
		</tbody></table>
	<?php
	}

}else{
	
echo  '<div class="alert alert-info alert-dismissable">
		<i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Vous ne participez &agrave; aucune session</div>' ;
}

?> 
</div><!-- /.tab-pane -->
								
                 
                </div><!-- /.tab-content -->
              </div><!-- nav-tabs-custom -->
	
	
	
   <div class="box-footer">
            <a href="courriers/lettre_atelier.php?user=<?php echo $_SESSION['iduser'];?>&epn=<?php echo $epnuser; ?>" target="_blank"><button type="submit" name="submit"  class="btn btn-primary"  data-toggle="tooltip" title="Imprimer les ateliers"><i class="fa fa-print"></i>&nbsp;&nbsp;Imprimer les ateliers</button></a>
			<a href="courriers/lettre_session.php?user=<?php echo  $_SESSION['iduser'] ; ?>&epn=<?php echo $epnuser; ?>" target="_blank"><button type="button" class="btn bg-navy pull-right"  data-toggle="tooltip" title="Imprimer les sessions"><i class="fa fa-print"></i>&nbsp;&nbsp;Imprimer les sessions</button></a>
			</div>
   
	 </div></div>
	</div>
  
  <?php  
	


//************** Affichage de la liste des ateliers affichage par defaut ****///
//La liste des atliers
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
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Système d'exploitation</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Web</div>
					 </div>
			<div class="form-group"><label>Niveau</label>
					<div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Débutant</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Système d'exploitation</div>
					 <div class="checkbox"><input type="checkbox" name="cat1" value="cat1">Web</div>
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
	<div class="box-header"><h3 class="box-title">Liste des ateliers propos&eacute;s pour <?php echo date('Y'); ?></h3> 
		<small class="badge bg-blue" data-toggle="tooltip" title="Cliquez sur un intitul&eacute; pour voir le d&eacute;tail d'un atelier et vous inscrire"><i class="fa fa-info"></i></small>
		</div>
	
	
		<div class="box-body">
		<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_3" data-toggle="tab">Les ateliers (<?php echo $nba;?>)</a></li>
					<li><a href="#tab_4" data-toggle="tab">Les sessions (<?php echo $nbsessionsprog; ?>)</a></li>
					</ul>
				
					<?php 
					if ($nba > 0)
				{
					
					?>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_3">
					
					
					<table class="table"><thead>
						  <th>Date</th><th>Heure</th><th>Dur&eacute;e</th><th>Nom de l'atelier</th><th>Niveau</th><th>Lieu</th><th>Places restantes</th>						  
						  </tr></thead><tbody>
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
							//niveau de l'atlier
							$plevel = getAllLevel(1) ;	
							 echo "<tr>
									<td>".getDayfr($rowateliers["date_atelier"])."</td>
									<td>".$rowateliers["heure_atelier"]."</td>
									<td>".getTime($rowateliers["duree_atelier"])."</td>";
								if ($nbplace>0){
									if ($rowateliers["statut_atelier"]<3){
										echo " <td><a href=\"index.php?m=6&b=1&idatelier=".$rowateliers["id_atelier"]."\" title=\"Cliquez pour voir le détail et vous inscrire\">".$rowsujet["label_atelier"]."</a></td>";
									}else{
										echo "<td><a href=\"#\" title=\"Cet atelier a été annulé par l'animateur, veuillez le consulter pour plus de détail\">".$rowsujet["label_atelier"]."</a></td>";
											}
									}else{
										echo "<td>".$rowsujet["label_atelier"]." &nbsp;&nbsp;&nbsp;COMPLET</td>";
									}
							 echo "
									<td>".$plevel[$rowsujet["niveau_atelier"]]."</td>
									<td>".$lieuA."</td>
											<td>".$nbplace."</td>
										</tr>";
						} 
						?>
						</table></tbody>
						<?php
				}else {
							echo '  <div class="alert alert-info alert-dismissable">
									<i class="fa fa-info"></i>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucun atelier programm&eacute; pour le moment</div>' ;
						}
						?>
				
				
				
				</div>
				
				
				<div class="tab-pane" id="tab_4">
                   	<?php 
			
				if ($nbsessionsprog > 0)
				{
					?>
				<table class="table">
					 <thead><th>Dates</th><th>Titre</th><th>Lieu</th><th>Places restantes</th></thead><tbody>
					<?php 
				for ($j=1 ; $j <=$nbsessionsprog ; $j++)
					{
					$row = mysqli_fetch_array($futsessionrow) ;
					//elements					
					$titresession=getTitreSession($row["nom_session"]);
					//affichage de toutes les dates de la session
					$datesarray=getDatesSession($row["id_session"]);
					$salle=mysqli_fetch_array(getSalle($row["id_salle"]));
					$lieuS=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
					$nbrdates=$row["nbre_dates_sessions"];
					$listedatess='';
						for ($f=0; $f<$nbrdates ; $f++){
							$rowdates=mysqli_fetch_array($datesarray);
							$dd=date_create($rowdates["date_session"]);
							$listedatess=$listedatess.date_format($dd,"Y/m/d H:i")."</br>";
						}
						
					//nombre de places pour la session
						$placesoccupee=countPlaceSession($row["id_session"],0);
						$nbplace=$row["nbplace_session"];
						$placesrestantes=$nbplace-$placesoccupee;
						$urlinscrip="index.php?m=6&b=5&idsession=".$row["id_session"];
						
					//affichage
					echo '<tr> 
									<td><small>'.$listedatess.'</small></td>';
						if($placesrestantes==0){
							echo '<td><span class="text-muted">'.$titresession["session_titre"].'&nbsp;&nbsp;&nbsp;COMPLET</span></td>';
							}else{		
							echo'<td><a href="'.$urlinscrip.'">'.$titresession["session_titre"].'</a></td>';
							}		
							echo'<td>'.$lieuS.'</td>						
									<td>'.$placesrestantes.'</td></tr>';
					}
			 ?>
						
						</tbody></table>
				
				<?php
				}else{
				echo '<div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune session programmée</div>' ;
				
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
