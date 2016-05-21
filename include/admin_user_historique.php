
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
 

  include/admin_stat.php V0.1
  2013 Ajout de la librairy libchart (gnugpl)
  Modification du fichier fonction, ajout include fonction_stat.php
  
*/

// affichage des statistiques
if ($mess !="")
{
  echo $mess;
}

//inclusion des graphiques
include("libchart/classes/libchart.php");

// recuperation de l'adherent
$id_user=$_GET["iduser"];
$act=$_GET["act"];


$row = getUser($id_user);
$nom=$row["prenom_user"]." ".$row["nom_user"];
$equip     =  $row["equipement_user"];
$equipement=explode(";",$equip);
$utilisation     =  $row["utilisation_user"];
$connaissance     =  $row["connaissance_user"];
$info     =  stripslashes($row["info_user"]);
$sexe     =  $row["sexe_user"];



// type d'&eacute;quipement défini
$equipementarray = array (
         0 => "Aucun &eacute;quipement",   
         1 => "Ordinateur",
         2 => "Tablette",
	 3 => "Smartphone",
	 4 => "T&eacute;l&eacute;vision connect&eacute;e",
	5 => " Internet &agrave; la maison (ADSL, satellite ou fibre)",
	6 => " Internet mobile (3G, 4G+)",
	7 => "Pas de connexion Internet"
		);
foreach ($equipement AS $key=>$value)
{
$equipements=$equipements.$equipementarray[$value]." / ";
}

		
		// type d'utilisation défini
$utilisationarray = array (
         0 => "Aucun Lieu",
         1 => "A la maison",   
         2 => "Au bureau ou &agrave; l'&eacute;cole",
         3 => "A la maison et au bureau ou &agrave; l'&eacute;cole"
);
		
		// type de connaissance défini
$connaissancearray = array (
         0 => "D&eacute;butant",   
         1 => "Interm&eacute;diaire",
         2 => "Confirm&eacute;"
);

// si b=3 desinscription a un atelier
if ($act==1)
{
  delUserAtelier($_GET["idatelier"],$id_user) ;
  echo geterror() ;
}
//participation actuelle et future atelier-sessions
$userateliers=getMyAtelier($id_user,1,0)  ;
$nb = mysqli_num_rows($userateliers);
	//en attente
$resultA = getMyAtelier($id_user,1,2)  ;

//liste de tous les ateliers et session où l'dherent est inscrit et presence valid&eacute;e clotur&eacute; ==2 pour les ateliers, cloturé=1 pour les sessions !
//rappel getUserStatutAS($iduser,$statut,$type,$statutatelier) where $statut== présence, et $type==atelier ou session
$ListeAtelierPresent=getUserStatutAS($id_user,1,1,2); //ateliers passés
$ListeSessionPresent=getUserStatutAS($id_user,1,2,1);
$nbpresentatelier=mysqli_num_rows($ListeAtelierPresent);
$nbpresentsession=mysqli_num_rows($ListeSessionPresent);
$nbtotalpresent=$nbpresentatelier+$nbpresentsession;
	

//liste de tous les ateliers et session où l'dherent est inscrit et non validée $statut==0 --> absent !
$ListeAtelierAbsent=getUserStatutAS($id_user,0,1,2); 
$ListeSessionAbsent=getUserStatutAS($id_user,0,2,1);
$nbabsentatelier=mysqli_num_rows($ListeAtelierAbsent);
$nbabsentsession=mysqli_num_rows($ListeSessionAbsent);
$nbtotalabsent=$nbabsentatelier+$nbabsentsession;

//participation actuelle et future aux sessions en attente
$resultsessionattente=getUserStatutAS($id_user,2,2,0);
$nbrsessionattente=mysqli_num_rows($resultsessionattente);

//toutes sesssions en cours
$ListeInscriptionSession=getUserStatutAS($id_user,0,2,0);
$numinscriptionsession=mysqli_num_rows($ListeInscriptionSession);

//inscriptions pour les sessions en cours, date validée mais pas la session complete pour affichage ggrisé !

$ListeSessionEnCours=getMySession($id_user);
$numLVS=mysqli_num_rows($ListeSessionEnCours);
//debug(mysqli_fetch_array($ListeSessionEnCours));

$today=date('Y-m-d');

//verification que le dossier images des stats existe.
$dossierimg = "img/chart/".$year ;
if(!is_dir($dossierimg)){
   mkdir($dossierimg);
}
?>

<div class="row"> <section class="col-lg-6 connectedSortable"> 
<div class="box box-info"><div class="box-header"><h3 class="box-title">Historique de l'adh&eacute;rent</h3>
	<div class="box-tools pull-right">
		<a href="index.php?a=1&b=2&iduser=<?php echo $id_user ; ?>"><button class="btn bg-blue btn-sm"  data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a>
		 <a href="index.php?a=6&iduser=<?php echo $id_user; ?>"><button class="btn bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></button></a>
</div></div>
		<div class="box-body">
		<table class="table">
		<tr><td>
			<?php 
			if (FALSE != isset($id_user)){
				
				if ($sexe =="F"){
				echo '<img src="img/avatar/female.png">' ;
				} else {
				echo '<img src="img/avatar/male.png">' ;
				}
					
			}else{
				echo '<img src="img/avatar/default.png" width="60%">' ;
				}
			?>
			</td>
		<td><h4><?php echo $nom; ?></h4>
		<p><b>Rappel des conditions informatiques : </b></p>
		<p><?php echo $equipements; ?> <br> <?php echo $utilisationarray[$utilisation];?> / <?php echo $connaissancearray[$connaissance];?></p>
		<p><b>Notes particuli&egrave;res : </b></p>
		<p><?php  if ($infos!="") { echo $infos; } else { echo "pas d'infos ! ";} ?></p>
		</td></tr></table>
		
		</div>
</div>
<div class="box box-info"><div class="box-header"><h3 class="box-title">Taux de pr&eacute;sence</h3></div>
		<div class="box-body">
		<?php
		///total des inscriptions --> % présence / %absences sur total inscriptions pour les sessions et les ateliers
	//ateliers
	$chartA = new PieChart(400, 280);
	$dataSetA = new XYDataSet();
	$dataSetA->addPoint(new Point("Présences", $nbpresentatelier));
	$dataSetA->addPoint(new Point("Absences", $nbabsentatelier));
	$chartA->setDataSet($dataSetA);
$chartA->getPlot()->getPalette()->setPieColor(array(new Color(44,173,135),new Color(234,42,83)));
$chartA->setTitle("Taux de présence aux ateliers (".$year.") ");
$chartA->render("img/chart/".$year."/txpresencea_".$id_user.".png");
//sessions
$chartS = new PieChart(400, 280);
	$dataSetS = new XYDataSet();
	$dataSetS->addPoint(new Point("Présences", $nbpresentsession));
	$dataSetS->addPoint(new Point("Absences", $nbabsentsession));
	$chartS->setDataSet($dataSetS);
$chartS->getPlot()->getPalette()->setPieColor(array(new Color(44,173,135),new Color(234,42,83)));
$chartS->setTitle("Taux de présence aux session (".$year.") ");
$chartS->render("img/chart/".$year."/txpresences_".$id_user.".png");


?>
<img src="img/chart/<?php echo $year; ?>/txpresencea_<?php echo $id_user; ?>.png" >
<img src="img/chart/<?php echo $year; ?>/txpresences_<?php echo $id_user; ?>.png" >
		
		</div>
</div>
<div class="box box-info"><div class="box-header"><h3 class="box-title">Inscriptions actuelles aux ateliers</h3>
	<div class="box-tools pull-right">
		<a href="courriers/lettre_atelier.php?user=<?php echo $id_user ; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-print"></i></button></a>
	</div>
</div>
	<div class="box-body">
	<?php if($nb>0){  ?>	
		<table class="table"><thead><tr><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead><tbody>
		<?php
		for ($i=1 ; $i<=$nb ; $i++)
		{
		    $row = mysqli_fetch_array($userateliers) ;
				$result2=getSujetById($row["id_sujet"]);
				$rowsujet=mysqli_fetch_array($result2);
		    echo "<tr>
			      <td>".getDayfr($row["date_atelier"])." (".$row["heure_atelier"].")</td>
				<td>".$rowsujet["label_atelier"]."</td>
			      <td><a href=\"index.php?a=5&b=6&act=1&iduser=".$id_user."&idatelier=".$row["id_atelier"]."\"><button type=\"button\" class=\"btn bg-red sm\"  data-toggle=\"tooltip\" title=\" D&eacute;sinscrire\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr>";
		}
		?>
	   </tbody></table>
	   
	  <?php 
	  }else {
	  echo "<p>Pas d'inscription enregistr&eacute;e pour le moment</p>";

		} ?>
	   
	   
	   <?php
	   //en attente
	   $nbattente = mysqli_num_rows($resultA);
	if ($nbattente > 0){
		echo '<div class="box-body"><H4>Inscrit en liste d\'attente  :</h4><table class="table"><thead><tr><th>Date et heure</th><th>Nom de l\'atelier</th><th></th></tr></thead><tbody>';
			for ($i=1 ; $i<=$nbattente ; $i++)
			{
			    $rowA = mysqli_fetch_array($resultA) ;
					$result2=getSujetById($rowA["id_sujet"]);
					$rowsujetA=mysqli_fetch_array($result2);
			    echo "<tr>
				      <td >".getDayfr($rowA["date_atelier"])." (".$rowA["heure_atelier"].")</td>
				<td>".$rowsujetA["label_atelier"]."</td>
				<td><a href=\"index.php?a=5&b=6&act=1&iduser=".$id_user."&idatelier=".$row["id_atelier"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" data-toggle=\"tooltip\" title=\" D&eacute;sinscrire\"></i></button></a></td></tr>";
			}
			echo '</tbody></table></div>';
		} else {
		echo "<p class=\"text-info\">Pas d'inscription en liste d'attente pour le moment</p>";
		}
	   ?>

</div></div>

<div class="box box-info"><div class="box-header"><h3 class="box-title">Inscriptions actuelles aux sessions</h3>
<div class="box-tools pull-right">
		<a href="courriers/lettre_session.php?user=<?php echo $id_user ; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-print"></i></button></a>
	</div>
</div>
	<div class="box-body">
	<?php 

	
	if($numinscriptionsession>0){  ?>	
		<table class="table"><thead><tr><th>Date et heure</th><th>Nom de la session</th><th></th></tr></thead><tbody>
		<?php
		
		if ($numLVS>0){
			//numeros des sessions deja validés
			for ($j=1 ; $j<=$numLVS ; $j++)
			{
			    $rowLVS = mysqli_fetch_array($ListeSessionEnCours) ;
				$arraysessionLVS=getSession($rowLVS["id_session"]);
				$sujetLVS=mysqli_fetch_array(getSujetSessionById($arraysessionLVS["nom_session"]));
				if($rowLVS["statut_datesession"]==1){
				$class= "text-muted";
					if($rowLVS["status_rel_session"]==1){ $presence="Pr&eacute;sent"; }else{ $presence="Absent";}
				}else{
				$class="";
				$presence="";
				}
				
				
				
			    echo "<tr class=".$class.">
				      <td>".getDatefr($rowLVS["date_session"])."</td>
					<td>".$sujetLVS["session_titre"]." (".$j.") </td>
				      <td>".$presence."</td></tr>";
				
			}
		}
		
		?>
	   </tbody></table>
	   
	  <?php 
	  }else {
	  echo "<p>Pas d'inscription enregistr&eacute;e pour le moment</p>";

		} ?>
	   
	   
	   <?php
	   //en attente
	   
	if ($nbrsessionattente > 0){
		echo '<div class="box-body"><H4>Inscrit en liste d\'attente  :</h4><table class="table"><thead><tr><th>Date et heure</th><th>Nom de la session</th><th></th></tr></thead><tbody>';
			for ($i=1 ; $i<=$nbrsessionattente ; $i++)
			{
			    $rowAs= mysqli_fetch_array($resultsessionattente) ;
				$sessionattente=getSession($rowAs["id_session"]);
			
				$attentesujet=mysqli_fetch_array(getSujetSessionById($sessionattente["nom_session"]));
					
			    echo "<tr>
				      <td >".getDatefr($rowAs["date_session"])."</td>
				<td>".$attentesujet["session_titre"]."  (".($rowAs["id_datesession"]).") </td>
				<td><a href=\"index.php?a=5&b=6&act=1&iduser=".$id_user."&idsession=".$rowAs["id_session"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" data-toggle=\"tooltip\" title=\" D&eacute;sinscrire\"></i></button></a></td></tr>";
			
			}
			echo '</tbody></table></div>';
		} else {
		echo "<p class=\"text-info\">Pas d'inscription en liste d'attente pour le moment</p>";
		}
	   ?>
	
</div></div>
</section>

<section class="col-lg-6 connectedSortable"> 
<div class="box box-info"><div class="box-header"><h3 class="box-title">Participations pass&eacute;es (ann&eacute;e en cours)</h3>
	<div class="box-tools pull-right">
		<a href="courriers/csv_historique.php?user=<?php echo $id_user ; ?>&epn=<?php echo $_SESSION['idepn']; ?>" target="_blank"><button type="button" class="btn bg-green btn-sm"  data-toggle="tooltip" title="T&eacute;l&eacute;charger le XLS"><i class="fa fa-download"></i></button></a>
	</div>
</div>
		<div class="box-body">
		<p class="text-info">Attention les ateliers et la totalit&eacute; de dates d'une session doivent &ecirc;tre valid&eacute;es pour que la participation pass&eacute;e s'affiche !</p>
<?php if($nbtotalabsent>0){ ?>
		<h4>Inscrit mais absent (<?php echo $nbtotalabsent; ?>)</h4>
		<table class="table"><thead><tr><th>Type</th><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead><tbody>
		<?php
		
		for($i=1;$i<=$nbabsentatelier;$i++)
			{
				
			//atelier	
				$rowatelier=mysqli_fetch_array($ListeAtelierAbsent);
				$arrayatelier=getAtelier($rowatelier["id_atelier"]);
				$titrearray=getSujetById($arrayatelier["id_sujet"]);
				$rowsujetA=mysqli_fetch_array($titrearray);
				echo "<tr><td>Atelier</td><td>".getDayFR($arrayatelier["date_atelier"])." &agrave; ".$arrayatelier["heure_atelier"]."</td>
						<td>".$rowsujetA["label_atelier"]."</td>
						<td></td>
						</tr>";
				
			}
			//session
						
			for($i=1;$i<=$nbabsentsession;$i++)
			{
				$sessionabsent=mysqli_fetch_array($ListeSessionAbsent);
				$arrayses=getSession($sessionabsent["id_session"]);
				$titresession=mysqli_fetch_array(getSujetSessionById($arrayses["nom_session"]));
				echo "<tr><td>Session</td><td>".getDatefr($sessionabsent["date_session"])."</td>
						<td>".$titresession["session_titre"]."  </td>
						<td></td>
						</tr>";
									
			}
				
				
		
		
		?>
		</tbody></table>
<?php }else{ echo "<p>Pas d'absence pass&eacute;e enregistr&eacute;e</p>" ; } ?>
	
		<h4>Pr&eacute;sent (<?php echo $nbtotalpresent; ?>)</h4>
	<?php if($nbtotalpresent>0){ ?>
		<table class="table"><thead><tr><th>Type</th><th>Date et heure</th><th>Nom de l'atelier</th><th></th></tr></thead><tbody>
		<?php
		for($p=1;$p<=$nbpresentatelier;$p++)
			{
				$rowp=mysqli_fetch_array($ListeAtelierPresent);
				
			 //atelier	
				$arrayatelier=getAtelier($rowp["id_atelier"]);
				$titrearray=getSujetById($arrayatelier["id_sujet"]);
				$rowsujetA=mysqli_fetch_array($titrearray);
				
				echo "<tr><td>Atelier</td><td>".getDayFR($arrayatelier["date_atelier"])." &agrave; ".$arrayatelier["heure_atelier"]."</td>
				<td>".$rowsujetA["label_atelier"]."</td>
				<td></td>
				</tr>";
			}
			//session
		
			for($j=1;$j<=$nbpresentsession;$j++)
			{
				$rows=mysqli_fetch_array($ListeSessionPresent);
				$arraysession=getSession($rows["id_session"]);
				$titresession=mysqli_fetch_array(getSujetSessionById($arraysession["nom_session"]));
					echo "<tr><td>Session</td><td>".getDateFR($rows["date_session"])."</td>
						<td>".$titresession["session_titre"]."  </td>
						<td></td>
						</tr>";
					
			
				
				
			} ?></tbody></table>
		
	<?php } else { echo '<p>Aucune pr&eacute;sence valid&eacute;e n\'a &eacute;t&eacute; enregistr&eacute;e.</p>'; } ?>
	
		</div>
</div>
</section><!-- ./col -->
</div><!-- ./row -->























