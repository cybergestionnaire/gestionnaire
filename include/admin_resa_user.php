<?php
/*
     This file is part of CyberGestionnaire.

    CyberGestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 2012 Florence DAUVERGNE

*/

// Formulaire de creation ou de modification d'un adherent

$id_user = $_GET["iduser"];
$datedebut=$_POST["datedebut"];
$datefin=$_POST["datefin"];
//debug($date1);
//debug($date2);

//retrouver les infos utilisateur
if(isset($id_user)){
$rowu = getUser($id_user);
 $nom      = stripslashes( $rowu["nom_user"]);
 $prenom   = stripslashes( $rowu["prenom_user"]);
   $statuss   =  $rowu["status_user"];
   $dateinscr     =  $rowu["date_insc_user"];
	$daterenouv=$rowu["dateRen_user"];
		
}
$week= date('W')+1;
$semaine=get_lundi_dimanche_from_week($week);
$date1=$semaine[0];
$date2=$semaine[1];

// Tableau des mois
$month = array(
       1=> "Janvier",
       2=> "F&eacute;vrier",
       3=> "Mars",
       4=> "Avril",
       5=> "Mai" ,
       6=> "Juin",
       7=> "Juillet",
       8=> "Aout",
       9=> "Septembre",
       10=> "Octobre",
       11=> "Novembre",
       12=> "D&eacute;cembre"
);

$annees=array(
	1=> "2010",
	2=> "2011",
	3=> "2012",
	4=> "2013",
	5=> "2014",
	6=> "2015"
	

);


?>

<div class="row">
<!-- left column --><div class="col-md-8">
<div class="box box-info"><div class="box-header"><h3 class="box-title">Réservations de <?php echo $prenom."  ".$nom ;?></h3>
	<div class="box-tools pull-right">
		<a href="index.php?a=1&b=2&iduser=<?php echo $id_user ; ?>"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="Fiche adhérent"><i class="fa fa-edit"></i></button></a>
		 <a href="index.php?a=6&iduser=<?php echo $id_user; ?>"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></button></a>
</div>
</div>
	 <div class="box-body">
	 

	<?php
	
	if (TRUE==checkResa($id_user))
	{	
	// affichage des reservation a venir
	$result = getResaById($id_user,1) ;
	if ($result!=FALSE)
	{
		echo " <p>R&eacute;servations à venir</p>" ;
		echo "<table class=\"table\">
				<thead><tr> 
				   <th>Date</th>
				   <th>Heure de debut</th>
				   <th>Heure de fin</th>
				   <th>Dur&eacute;e</th>
				   <th>Machine r&eacute;serv&eacute;e</th>
				   </tr></thead><tbody>";
				   
		while($row = mysqli_fetch_array($result))
		{
		echo "<tr><td>".dateFr($row['dateresa_resa'])."</td>
				  <td>".getTime($row['debut_resa'])."</td>
				  <td>".getTime(($row['debut_resa']+$row['duree_resa']))."</td>
				  <td>".getTime($row['duree_resa'])."</td>
				  <td>".$row['nom_computer']."</td>
				</tr>" ;
		}
		echo "</tbody></table>" ;
	}else{
		echo "Aucune réservation enregistrée pour les jours prochains</br>";
	}
	
// ARCHIVES DES RESERVATIONS

	//$result = getUserResaById($_SESSION['iduser'],date("m"),date("Y")) ;
	$result = getResaByMonth($id_user,date("m"),date("Y")) ;

	if($result!=FALSE)
	{
		// affichage
		
		echo "<p>R&eacute;servations archiv&eacute;s (mois en cours)</p>" ;
		echo "<table class=\"table\">
				<thead><tr> 
				   <th>Date</th>
				   <th>Heure de debut</th>
				   <th>Heure de fin</th>
				   <th>Dur&eacute;e</th>
				   <th>Machine r&eacute;serv&eacute;e</th></tr></thead><tbody>";
		
				   
		while($row = mysqli_fetch_array($result))
		{
		
		echo "<tr><td>".dateFr($row['dateresa_resa'])."</td>
				  <td>".getTime($row['debut_resa'])."</td>
				  <td>".getTime(($row['debut_resa']+$row['duree_resa']))."</td>
				  <td>".getTime($row['duree_resa'])."</td>
				  <td>".$row['nom_computer']."</td></tr>" ;
		}
		echo "</tbody></table>" ;
	}else{
		echo "Aucune réservation enregistrée pour le mois en cours </br>";
	}
	

}
else
{
	echo "Vous n'avez pas de r&eacute;servations enregistrée" ;
	
}

?>

</div>
</div>



<div class="box box-info"><div class="box-header"><h3 class="box-title">Chercher dans les réservations</h3></div>
  <div class="box-body"><form method="POST" role="form" >
  <div class="form-group">
                    <label>Choisissez la période</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
		     <input name="datedebut" class='input'  id="left" placeholder="de la date ....">
			 <input name="datefin" class='input'  id="right" placeholder="....à la date">
             <button class="btn btn-default" type="submit" name="submit" value="periode"><i class="fa fa-search"></i></button> </form>     </div><!-- /.input group -->
                  </div><!-- /.form group -->
	
		 <?php
		// debug($_POST["submit"]);
	if(isset($_POST["submit"])) {
	
	if( $datedebut!="" AND $datefin!=""){

	$result = getResaBy2dates($id_user,$datedebut,$datefin) ;
	if ($result!=FALSE)
	{
		echo " <p>R&eacute;servations entre ".getDateFr($datedebut)." et ".getDateFr($datefin)."</p>" ;
		echo "<table class=\"table\">
				<thead><tr> 
				   <th>Date</th>
				   <th>Heure de debut</th>
				   <th>Heure de fin</th>
				   <th>Dur&eacute;e</th>
				   <th>Machine r&eacute;serv&eacute;e</th>
				   </tr></thead><tbody>";
				   
		while($row = mysqli_fetch_array($result))
		{
		echo "<tr><td>".dateFr($row['dateresa_resa'])."</td>
				  <td>".getTime($row['debut_resa'])."</td>
				  <td>".getTime(($row['debut_resa']+$row['duree_resa']))."</td>
				  <td>".getTime($row['duree_resa'])."</td>
				  <td>".$row['nom_computer']."</td>
				</tr>" ;
		}
		echo "</tbody></table>" ;
	}else{
		echo "Aucune réservation enregistrée pour la période demandée</br>";
	}
}else{
	echo "Veuillez entrer 2 dates pour définir la période SVP!";
}
}
	?>
	</div>

	<div class="box-footer clearfix no-border"><a href="courriers/csv_resa-user.php?user=<?php echo $id_user?>&date1=<?php echo $datedebut ?>&date2=<?php echo $datefin ?>&epn=<?php echo $_SESSION["idepn"]; ?>" class="btn btn-success pull-right"><i class="fa fa-download"></i>&nbsp;&nbsp;Générer le CSV</a>
	</div>
</div>



</div>


<!-- right column --><div class="col-md-4">
<!--
<div class="box"><div class="box-header"><h3 class="box-title">Infos sur le compte</h3></div>
  <div class="box-body">
<?php

	if (TRUE==checkResaSemaine($id_user,strftime("%Y-%m-%d",$date1), strftime("%Y-%m-%d",$date2)))
	{
		$row=getTempsCredit($id_user, strftime("%Y-%m-%d",$date1), strftime("%Y-%m-%d",$date2));
		//debug(strftime("%Y-%m-%d",$date2));
		debug($row['temps_user']);
		if ($row['total']==999)
		{
			$total = 'illimit&eacute;' ;
			$reste = $total ;
		}
		else
		{
			$total = getTime($row['total']);
			$utilise=$row['util'];
			$reste = getTime($row['total']-$utilise) ;
			
			//$reste = getTime($row['total']-$row['util']) ;
		}
	}else{
		$total=getTime(300);
		$utilise=0;
		$reste=$total;
	}
		echo " 
				<h5>Semaine du ".strftime("%d/%m/%Y",$date1)." au ".strftime("%d/%m/%Y",$date2)." </h5>
				<p>Cr&eacute;dit temps total par semaine :<b> ".$total." </b></p>
				<p>Cr&eacute;dit temps utilis&eacute; cette semaine : ".getTime($utilise)."</p>
				<p>Cr&eacute;dit temps restant cette semaine : <b>".$reste." </b></p>
				
				
			  ";
	?>
	</div>
</div>	
-->
<?php
//Calcul statistique de la consultation par mois
if (TRUE==checkResa($id_user))
	//debug(checkResa($id_user));
{	
?>
<div class="box"><div class="box-header"><h3 class="box-title">Statistiques</h3></div>
  <div class="box-body">
                 
  <H5>Cumul par mois (année en cours)</h5>
		 <table class="table table-condensed">
				<thead><tr> 
				   <th>Mois</th>
				   <th>Durée</th>
				  
				   </tr></thead><tbody>
<?php
	// 
	 $month = date('n');
	for ($i=1 ; $i<= $month;++$i){	 
			$result = getUserResabyMonth($id_user,$i,date('Y'));
			while($row = mysqli_fetch_array($result))
			{
			echo "<tr><td>".getMonth($i)."</td>
					  <td>".getTime($row['duree'])."</td>
									  </tr>" ;
			}
			
		}
	
	echo "</tbody></table></div></div>" ;

}
?>

<!--
<div class="box"><div class="box-header"><h3 class="box-title">Statistiques</h3></div>
  <div class="box-body">
	<p>Moyenne de la consultation par semaine : (en h)</p>
	
	
	<p>Postes utilisés</p>
	
	</div>
</div>
-->

</div>


</div><!-- /row -->

 <!-- Daterange picker -->
<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/historique.js'></script>