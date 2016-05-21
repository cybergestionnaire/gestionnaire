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
 2012 Dauvergne florence pour les modifications

*/
//Fichier de gestion des archives.
$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}
//parametres
$statusarray=array(
	0=>"Atelier En cours",
	1=>"(Valid&eacute;)",
	2=>"(Annul&eacute;)"	
);	
// classement par annee-
if (TRUE == isset($_GET['year']))
	{
	$year =$_GET['year'];
	}
	else
	{
	   $year=date('Y');
	}
	
	
  $result = getAncSession($_SESSION['idepn'],$year);
  $nb = mysqli_num_rows($result) ;
	
	?>
	<div class="box box-success"> <div class="box-header"><h3 class="box-title">Liste des sessions archiv&eacute;s </h3>
		<div class="box-tools pull-right">
            <div class="btn-group">
			<?php 
		$rowanneesstat=getYearStatAtelierSessions();
		while($ans=mysqli_fetch_array($rowanneesstat)){
		
			echo '<a href="index.php?a=36&year='.$ans['Y'].'" > <button class="btn bg-yellow btn-sm">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=36&year='.date('Y').'"> <button class="btn bg-yellow btn-sm"> Ann&eacute;e en cours</button></a>';
		?>
		</div></div>
		</div>
	<div class="box-body no-padding">
	<?php

  if ($nb > 0)
  {
	
  ?>
	<table class="table table-condensed">
	   <thead><tr><th>Date</th><th>Intitul&eacute;</th><th>Animateur</th><th>Salle</th><th>Inscrits</th><th>Horaires&nbsp;&nbsp;<small class="badge bg-blue"  data-toggle="tooltip" title="Cliquez sur une date pour modifier les pr&eacute;sences"><i class="fa fa-info"></i></small></th><th></th></tr></thead><tbody>
  <?php

	  for ($j=1 ; $j <=$nb ; $j++)
	  {
		  $row = mysqli_fetch_array($result) ;
		
		// chargement des données
		$anim=getUserName($row["id_anim"]);
		$salle=mysqli_fetch_array(getSalle($row["id_salle"]));
		$nomsalle=$salle["nom_salle"];
		$idsession=$row["idsession"];
		$tarif=getNomTarif($row["id_tarif"]);
		$nbre_dates=$row["nbre_dates_sessions"];
		  // liste des dates de la session
		  $datesarray=getDatesSession($row["idsession"]);
		
		
		$placesoccupee=countPlaceSession($row["idsession"],0);
		  echo "<tr>
			  <td>".getDayfr($row["datep"])."</td>
			  <td>".$row["session_titre"]."</td>
			 <td>".$anim."</td>
			  <td>".$nomsalle."</td>
			   <td>".$placesoccupee."</td>
			 		
			 <td>";
		for ($s=0; $s<$nbre_dates; $s++){
			$row2=mysqli_fetch_array($datesarray);
			  $statut=$row2["statut_datesession"];
			  if($statut==2){
				echo getDatefr($row2["date_session"])."&nbsp;&nbsp;".$statusarray[$statut]." </br>";
			  }else{
				echo "<a href=\"index.php?a=32&act=1&idsession=".$idsession."&dateid=".$row2["id_datesession"]."&numerod=".$s."\">".getDatefr($row2["date_session"])."</a>&nbsp;&nbsp;".$statusarray[$statut]." </br> ";
			}
			}
	
	echo "</td>
			  </tr>";
	  }
	  ?>
	 </tbody></table>
	 </div>
	<div class="box-footer">
	<a href="index.php?a=37"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des sessions en cours</button></a></div>
	
	</div>	
	  
<?php
}else{
echo "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucune session archiv&eacute;e pour l'ann&eacute;e en cours</div>";

}
?>
		  