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

 include/admin_breve.php V0.1
*/
//Fichier de gestion des archives.
 //statut de l'atelier
 /*
$stateAtelier = array(
	0=> "En cours",
	1=> "En programmation",
	2=> "Cloturé",
	3=> "Annulé"
				);
	
*/	
$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
  
}
// classement par annee-
if (TRUE == isset($_GET['year']))
	{
	$year =$_GET['year'];
	}
	else
	{
	   $year=date('Y');
	}
//affichage admin
if($_SESSION["status"]==4){
	$result2=getArchivAtelier($year,0);
}
if($_SESSION["status"]==3){
	$anim=$_SESSION["iduser"];
	$result2 = getArchivAtelier($year, $anim);
}

$nb2 = mysqli_num_rows($result2) ;	

?>
	<div class="box box-success"><div class="box-header"><h3 class="box-title">Liste des ateliers archiv&eacute;s pour <?php echo $year; ?></h3>
		<div class="box-tools pull-right">
            <div class="btn-group">
			<?php 
		
		$rowanneesstat=getYearStatAtelierSessions();
		while($ans=mysqli_fetch_array($rowanneesstat)){
		
			echo '<a href="index.php?a=18&year='.$ans['Y'].'&month=12&day=365&jour=31" > <button class="btn bg-yellow btn-sm">'.$ans['Y'].' </button></a>'; 
		 }
		//annee en cours
		echo '<a href="index.php?a=18&year='.date('Y').'"> <button class="btn bg-yellow btn-sm"> Ann&eacute;e en cours</button></a>';
		?>
		</div></div>
		</div>
		<div class="box-body">
<?php
	if ($nb2 > 0)
		{
			 ?>
		<table class="table"> 
			<thead><tr><th>Date</th><th>Titre</th><th>Inscrits</th><th>Pr&eacute;sents</th><th>Absents</th><th>En Attente</th></tr></thead><tbody>
			  <?php
				for ($j=0; $j <$nb2 ; $j++)
				{
					$row2 = mysqli_fetch_array($result2) ;
					$sujet=getAtelierSujet($row2['id_AS']);
										
					  echo "<tr>
						 <td>".getDatefr($row2["date_AS"])."</td>
						  <td><a href=\"index.php?a=16&b=4&act=1&idatelier=".$row2['id_AS']." \">".$sujet['label_atelier']."</a></td>
						  <td>".$row2["inscrits"]."</td>
						  <td>".$row2["presents"]."</td>
						  <td>".$row2["absents"]."</td>
						  <td>".$row2["attente"]."</td>
						  </tr>";
				}
			echo "</tbody></table></div>" ;
		
		}else{
		
			echo '<div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune formation archiv&eacute;e cette ann&eacute;e</div>' ;
		}  
?>
</div>
