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
 
*/
 // On met a jour la duree de la resa
include ("post_reservation-rapide.php");
$term   = $_POST["term"];
$mesno  = $_GET["mesno"];

 if (TRUE == isset($_POST['modify_duration']))
 {
     updateDureeResa($_POST);
	
  }

 // Affichage des reservations par utilisateur
  if ( $_SESSION['status']==3 OR $_SESSION['status']==4 )
  {
        if (TRUE == is_numeric($_GET['del']))
        {
            delResa2($_GET['del']) ;
			
        }
   }



// re initialisation des variables debut et duree
if (TRUE==isset($_SESSION['debut']))
{
    unset($_SESSION['debut']);
    unset($_SESSION['duree']);
	
}
// Fichier de reservation d'un poste

//recuperation des get
$jour  = $_GET["jour"];
$mois  = $_GET["mois"];
$annee = $_GET["annee"];
$salle = $_GET["salle"];

$epn=$_SESSION["idepn"];
 //Affichage de la salle  
  if (TRUE == isset($_POST['modifsalle']))
 {
     $salle=$_POST['Psalle'];
	
  }

// on recupere le num du jour de la semaine ˆ, 1, 2, ...7 
$dayNum = date("N",mktime(0, 0, 0, $mois, $jour, $annee)) ;
$dayArr = array ("","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
//consignes
if ($_SESSION['status']==3){
}
// affichage du titre et des horaires d'ouverture en fonction de la salle choisie
if($_SESSION["status"]==3){
	$arraysalles=getSallesbyAnim($_SESSION["iduser"]);
	$listesalles=explode(";",$arraysalles["id_salle"]);
	$nbsalles=count($listesalles);
	$allsalles=array();
		for ($i=0;$i<$nbsalles;$i++){
			$allsalles[$listesalles[$i]]=getNomsalleforAnim($listesalles[$i]);
			}
			
	if(isset($salle)){
	$salle=$salle;
	}else{
	$salle=$listesalles[0];
	}
	
}
	
if ($_SESSION["status"]==4 OR $_SESSION["status"]==1){
$allsalles=getAllsalles();
$epn=$_SESSION["idepn"];
if(isset($salle)){
	$salle=$salle;
	}else{
	$salle=1;
	}
}


// Tableau des unit&eacute; d'affectation
    $tab_unite_temps_affectation = array(
           1=> 1, //minutes
           2=> 60 //heures
    );
	
	// Tableau des fr&eacute;quence d'affectation
    $tab_frequence_temps_affectation = array(
           1=> "par Jour",
           2=> "par Semaine",
           3=> "par Mois"
    );


$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
  
}

?>
<div class="row">
<!--calendrier resa future -->

<div class="col-md-3">
<div class="box">

<?php include ("include/calendrier.php"); ?>

</div>
</div>
<?php
//** page utilisateur Aide + acces archives
if($_SESSION["status"]==1){
?>
 <div class="col-md-4">
	<div class="box box-default box-solid">
                <div class="box-header with-border"><h3 class="box-title">Aide</h3></div>
	<div class="box-body">
		<p>Vous pouvez faire une r&eacute;servation pour le jour d'ouverture correspondant &agrave; la structure que vous d&eacute;sirez en cliquant sur un poste. La demande sera enregistr&eacute;e automatiquement.</p>
	</div></div>
	
</div>

<div class="col-md-4">

<div class="box"><div class="box-header"><h3 class="box-title">Actions</h3></div>
	<div class="box-body">
	 <a class="btn btn-app" href="index.php?m=8"><i class="fa fa-inbox"></i> Archives</a>
	 <a class="btn btn-app"><i class="fa fa-save"></i> Enregistrer</a>
	</div><!-- /.box-body -->
</div><!-- /.box -->
							
</div>

<?php 
}
?>


<?php
///pas de resa rapide dans le futur ou le passe
$dateget=$annee.'-'.$mois.'-'.$jour;

if ($dateget==date('Y-n-d')){
///Affichage ou non de la resa rapide selon la configuration
$resamode=getConfigConsole($epn,"resarapide");
if($resamode=='1' AND $_SESSION["status"]>2){
	
?>


<!-- Reservation rapide -->

 <div class="col-md-3">
<div class="box"><div class="box-header"><h3 class="box-title">R&eacute;servation rapide par adh&eacute;rent</h3></div>
			<div class="box-body"><form method="post"  role="form">
			<div class="input-group input-group-sm">
				 <input type="text" name="term" class="form-control pull-right"  placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
			</form></div>
			</div></div>
<?php if (isset($messErr)){echo $messErr;} ?>			
<?php
if (strlen($term)>=2)
{
    // Recherche d'un adherent
    $result = searchUserRapid($term);
	
    if (FALSE == $result OR mysqli_num_rows($result)==0)
    {
		echo "<div class=\"col-md-6\">";
		echo getError(6);
		echo "</div>";
    }
    else
    {
      $nb  = mysqli_num_rows($result);
      if ($nb > 0)
      {
	
      ?>
	<div class="col-md-6">
	<div class="box">
	<div class="box-body">
     <h4><?php echo "R&eacute;sultats de la recherche: ".$nb; ?></h4>
	  <form method="post"  role="form">
		<table class="table"><thead><tr><th>Nom</th><th>Pr&eacute;nom</th><th>Age</th><th>Temps disponible</th><th>Resa</th></thead> 
			<tbody> 
			<?php
                for ($i=1; $i<=$nb; $i++){
					$row = mysqli_fetch_array($result) ;
					$credit  = getTime($row["temps_user"]);
					$age = date('Y')-$row["annee_naissance_user"];
					
					if($row['status_user']==1){
						$class="" ;
					}else{
						$class="inactif" ;}
					//*****tarifs de consultation
					$tarifTemps= getForfaitConsult($row["id_user"]);
					//`nombre_temps_affectation`,`unite_temps_affectation`,`frequence_temps_affectation`
					//temps en minute autoris&eacute; selon le tarif
					$tarifreferencetemps= $tarifTemps["nombre_temps_affectation"]*$tab_unite_temps_affectation[$tarifTemps["unite_temps_affectation"]];
					//modifier le temps comptabilis&eacute; en fonction de la frequence_temps_affectation
					if($tarifTemps["frequence_temps_affectation"]==1){ 
							//par jour
							$date1=date('Y-m-d');
							$date2=$date1;
					}else if($tarifTemps["frequence_temps_affectation"]==2){ 
							//par semaine;
							$semaine=get_lundi_dimanche_from_week(date('W'));
							$date1=strftime("%Y-%m-%d",$semaine[0]);
							$date2=strftime("%Y-%m-%d",$semaine[1]);
					
					}else if($tarifTemps["frequence_temps_affectation"]==3){ 
							//par mois
							$date1=date('Y-m')."-01";
							$date2=date('Y-m')."-31";
					}
					
						$resautilise = getTempsCredit($row["id_user"],$date1,$date2);
						$restant=$tarifreferencetemps-$resautilise['util'];
						
						
						
					echo "<tr class=\"".$class."\">
						<td>".$row["nom_user"]."</td>
						<td>".$row["prenom_user"]."</td>
						<td>".$age." ans</td>
						<td>".getTime($restant)."</td>
						<td><input type=\"radio\" name=\"adh_submit\" value=".$row["id_user"].">
						</td>
						 
						 </tr>";
                }
            ?> </tbody></table>
						
		
			<?php 
			$minute=str_split(date('i'));
			$min=0;
			if ($minute[1]>=0 and $minute[1]<4){
			$min=substr_replace(date('i'),"0",1,1);
			}else if ($minute[1]>3 and $minute[1]<8){
			$min=substr_replace(date('i'),"5",1,1);
			}else if ($minute[1]>7){
			$minu=($minute[0]+1)."0";
			$min=substr_replace(date('i'),$minu,0,2);
			}
			
			$heure=date('G')*60+$min;
			$duree=getConfigConsole($epn,"duree_resarapide"); //duree de la resa determin&eacute;e par config_horaire
			
			if($restant<$duree){$duree=$restant;} //mettre le temps restant par defaut pour la resa.
			//retrouver la liste des postes
			//$salle=1;
			$poster = getAllComputerDispo($salle); 
			
			?>
			
			
			<div class="input-group">
				<label>Poste : </label>
					<select name="idcomp">
					<?php
						foreach ($poster AS $key=>$value)
						{
							if ($num == $key)
							{
								echo "<option value=\"".$key."\" selected>".$value."</option>";
							}
							else
							{
								echo "<option value=\"".$key."\">".$value."</option>";
							}
						}
					?>
		   </select></div>
								
				<div class="input-group"><label>Heure d'arriv&eacute;e :</label>
							&nbsp;maintenant (<?php echo date('G').":".$min ; ?>)&nbsp;<input value="<?php echo $heure; ?>" type="hidden" name="heure"></div>
				<div class="input-group"><label>Dur&eacute;e :</label>&nbsp;<?php echo $duree; ?> min 
				<input value="<?php echo $duree; ?>" type="hidden" name="duree">
				<input value="<?php echo $restant; ?>" type="hidden" name="restant">
				<input value="0" type="hidden" name="pastresa">
				<input value="<?php echo (date('Y')."-".date('m')."-".date('d')); ?>" type="hidden" name="date">
				</div>
			</div>
			<div class="box-footer"><input type="submit" name="resa_submit" value="valider la reservation"  class="btn btn-primary"></div>
			</form>
			</div>
</div><!-- /col-->
			
<?php
		}
	}
}

}
}


?>

</div>
<!-- /row-->

<div class="row">
 <div class="col-md-12">
<div class="box box-info"><div class="box-header"><h3 class="box-title">Planning par postes</h3>
		<div class="box-tools"><form method="post" role="form">
			<div class="input-group">
			
			 <select name="Psalle"  class="form-control pull-right" style="width: 200px;">
       	<?php
        foreach ($allsalles AS $key=>$value)
        {
            if ($salle == $key)
            {
                echo "<option  value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option  value=\"".$key."\">".$value."</option>";
            }
        }
		
    ?></select>
		<div class="input-group-btn"><button type="submit" value="Rafraichir"  name="modifsalle" class="btn btn-default" style="height: 34px;"><i class="fa fa-repeat"></i></button></div>
	</div></form>
	</div>
	</div>
<div class="box-body">

<?php
echo "<h4>".$dayArr[$dayNum]." ".$jour." ".getMonthName($mois)." ".$annee."    <small class=\"badge bg-blue\" data-toggle=\"tooltip\" title=\"S&eacute;lectionnez un poste pour y faire une r&eacute;servation\"><i class=\"fa fa-info\"></i></small></h4>" ;
echo "<p>Horaires d'ouverture : ".getHoraireTexte($dayNum,$epn)."</p>" ;


// Affichage des machines
// et affichage du planning

// on efface d'eventuel session restante
unset($_SESSION['resa']);

// on garde l'url d'origine pour la redirection a la fin de l'enregistrement de la resa
$_SESSION['resa']['url']  = $_SERVER['REQUEST_URI'] ;

// on stocke la date du jour de la resa
$_SESSION['resa']['date'] = $annee."-".$mois."-".$jour ;

$row = getHoraire($dayNum,$epn) ;
$table = getPlanning($_SESSION['resa']['date'] , $row["hor1_begin_horaire"] , $row["hor1_end_horaire"] , $row["hor2_begin_horaire"] , $row["hor2_end_horaire"] , $epn,$salle) ;
if ($table != FALSE)
{
  // affichage du planning
  echo $table ;
echo '</div></div>'; // fin du cadre avec les plages horaires et planning


  // Affichage des reservations par utilisateur pour les admins
  if ( $_SESSION['status']==3 OR $_SESSION['status']==4 )
  {
        $resultresa = getResa('All' , $_SESSION['resa']['date'],$salle) ;
				
	?>
       <div class="box box-info"><div class="box-header"><h3 class="box-title">R&eacute;servation par utilisateur</h3></div>
			<div class="box-body no-padding"><table class="table table-condensed">
				<thead><tr> 
				<th></th>
				
   				<th>Nom de l'adh&eacute;rent</th> 
             	<th>Machine</th>
                <th >D&eacute;but</th>
                <th >Fin</th>
                <th>Dur&eacute;e </th>
                <th >&nbsp;</th></tr></thead> 
			<tbody>
	<?php
        while($rowresa = mysqli_fetch_array($resultresa))
				
        {
				
			
			$statususer=getUser($rowresa['id_user_resa']);
			if($statususer['status_user']==2){
             $class="text-muted" ;
						$info="<span class=\"badge bg-primary\" title=\"Renouvellement de l'adh&eacute;sion depuis le ".dateFr($statususer['dateRen_user'])." au tarif : ".getNomTarif($statususer['tarif_user'])."\" data-toggle=\"tooltip\"><i class=\"fa fa-info\"></i></span>";
          }else{
             $class="" ;
						  $info="";
							}
			
        	
             echo '<tr><form method="post" role="form">
	<td><a href="index.php?a=1&b=2&iduser='.$rowresa['id_user_resa'].'"><button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="    Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a>
		<a href="index.php?a=21&b=1&iduser='.$rowresa['id_user_resa'].'"><button type="button" class="btn bg-navy sm" data-toggle="tooltip" title="Ajouter impressions"><i class="fa fa-print"></i></button></a></td>
                       
        <td><h5 id="p'.$rowresa['id_user_resa'].'" class='.$class.'>'.getUserName($rowresa['id_user_resa']).'&nbsp;&nbsp;'.$info.'</h5></td>
                      
	<td><h5>'.getComputerName($rowresa['id_computer_resa']).'</h5></td>
	
       <td><h5>'.getTime($rowresa['debut_resa']).'</h5></td>
       
       <td><h5>'.getTime($rowresa['debut_resa']+$rowresa['duree_resa']).'</h5></td>
       
	<td>'.getHorDureeSelect2($rowresa['duree_resa'],$rowresa['debut_resa'],$_SESSION['resa']['date'],$rowresa['id_computer_resa'],$epn).'
              <!--<input type="checkbox" name="free" '.$check.'/>-->
     <input type="submit" value="mod." name="modify_duration" class="btn btn-sm"/>
		<input type="hidden" name="idResa" value="'.$rowresa['id_resa'].'"/></td>
                       
	<td><a href="'.$_SERVER['REQUEST_URI'].'&del='.$rowresa['id_resa'].'"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a></td></form></tr>';
        }
        echo '</form></tbody></table></div></div>';
  }

}
else // si le jour n'est pas ouvr&eacute;
{
  echo geterror(19);
}


?>
</div>
 </div>