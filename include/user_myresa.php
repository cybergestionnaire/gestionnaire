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
 

 include/admin_breve.php V0.1
*/

// fichier de gestion des reservations
//declaration du temps semaine pour le credit de temps 



if(FALSE!=isset($_GET['del']) AND FALSE!=is_numeric($_GET['del']))
{
	delResa($_GET['del'],$_SESSION['iduser']); //suppression de la resa
	
}
?>
 <div class="row">  
 <!--
  <div class="col-md-4">
  <div class="box box-primary"><div class="box-header"> <h3 class="box-title">Aide</h3></div>
		 <div class="box-body">
		  <p>Cliquez sur le calendrier &agrave; gauche pour r&eacute;server un poste &agrave; la date qui vous convient et suivez la proc&eacute;dure.
		  </p>
		  <p><b>N.B : Vous ne pourrez pas annuler une r&eacute;servation du jour ou une r&eacute;servation pass&eacute;e, en cas de probleme adressez vous &agrave; l'accueil.</b></p>
		  </div></div>
</div>-->

 <div class="col-md-8">
<?php

	
if (TRUE==checkResa($_SESSION['iduser']))
{	
	// affichage des reservation a venir
	$result = getResaById($_SESSION['iduser'],1) ;
	if ($result!=FALSE)
	{
	?>
		
		<div class="box box-success"><div class="box-header"> <h3 class="box-title">R&eacute;servations à venir</h3></div>
		<div class="box-body"><table class="table">
				<tr> 
				   <th>Date</th>
				   <th>Heure de debut</th>
				   <th>Heure de fin</th>
				   <th>Dur&eacute;e</th>
				   <th>Machine r&eacute;serv&eacute;e</th>
				   <th>&nbsp;</th></tr>
		<?php		   
		while($row = mysqli_fetch_array($result))
		{
		echo "<tr><td>".dateFr($row['dateresa_resa'])."</td>
				  <td>".getTime($row['debut_resa'])."</td>
				  <td>".getTime(($row['debut_resa']+$row['duree_resa']))."</td>
				  <td>".getTime($row['duree_resa'])."</td>
				  <td>".$row['nom_computer']."</td>
				  <td><a href=\"index.php?m=8&del=".$row['id_resa']."&min=".$row['duree_resa']."&date=".$row['dateresa_resa']."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\" title=\" supprimer\"></i></button></td></tr>" ;
		}
		?>
		</table></div></div>
		<?php
	}
	
// ARCHIVES DES RESERVATIONS

	//$result = getUserResaById($_SESSION['iduser'],date("m"),date("Y")) ;
	$result = getResaById($_SESSION['iduser'],2) ;
	if($result!=FALSE)
	{
		// affichage
	?>	
		
		<div class="box box-warning"><div class="box-header"> <h3 class="box-title">R&eacute;servations archiv&eacute;es</h3></div>
		<div class="box-body"><table class="table">
		
				<tr> 
				   <th>Date</th>
				   <th>Heure de debut</th>
				   <th>Heure de fin</th>
				   <th>Dur&eacute;e</th>
				   <th>Machine r&eacute;serv&eacute;e</th></tr>
		
		<?php		   
		while($row = mysqli_fetch_array($result))
		{
		
		echo "<tr><td>".dateFr($row['dateresa_resa'])."</td>
				  <td>".getTime($row['debut_resa'])."</td>
				  <td>".getTime(($row['debut_resa']+$row['duree_resa']))."</td>
				  <td>".getTime($row['duree_resa'])."</td>
				  <td>".$row['nom_computer']."</td></tr>" ;
		}
		?>
		</table></div>
		<div class="box-footer">
		 <a href="index.php?m=3"><button type="submit" class="btn btn-default">Retour aux r&eacute;servations</button></a>
     <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Enregistrer</button>
		
		</div>
		
		</div>
			
			<?php
	}
	

}
else
{
	echo "<div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;&nbsp;&nbsp;Vous n'avez pas de r&eacute;servations enregistrée</div>" ;
	
}

?>
</div>
</div>

