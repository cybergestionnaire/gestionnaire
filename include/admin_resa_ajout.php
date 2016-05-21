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
 2012 Dauvergne Florence

 include/admin_user.php V0.1
*/


// admin --- Utilisateur
$term   = $_POST["term"];
$mesno  = $_GET["mesno"];
$salle= $_POST["salle"];

//debug($_POST);

if ($mesno !="")
{
  echo getError($mesno);
}

$allsalles=getAllsalles();
if ($salle==''){$salle=1;}

$epn=$_SESSION["idepn"];
//date par default : jour
$date=date('Y-m-d');
//duree par default : 1 heure
$duree=60;
// heure par default
$heure="12:00";

?>

<div class="row">

<div class="col-md-4">
<div class="box"><div class="box-header"><h3 class="box-title">R&eacute;servation par adh&eacute;rent</h3></div>
			<div class="box-body"><form method="post"  role="form">
			<div class="input-group"><label>Indiquez la salle :</label>
			 <select name="salle"  class="form-control pull-right" style="width: 200px;">
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
					
				?></select></div>
			<br>
			<div class="input-group"><label>Trouvez un adh&eacute;rent par son nom ou pr&eacute;nom: </label>
			<input type="text" name="term"></div>
			</div>
			<div class="box-footer"> <input type="submit" value="Rechercher" class="btn btn-sm bg-yellow"></div>
			</form>
			
			</div></div>
<?php if (isset($messErr)){echo $messErr;} ?>			
			
			
<?php
if (strlen($term)>=2)
{
    // Recherche d'un adherent
    $result = searchUserRapid($term);
	
    if (FALSE == $result OR mysqli_num_rows($result)==0)
    {
		echo "<div class=\"col-md-4\">";
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
					//temps en minute autorisé selon le tarif
					$tarifreferencetemps= $tarifTemps["nombre_temps_affectation"]*$tab_unite_temps_affectation[$tarifTemps["unite_temps_affectation"]];
					//modifier le temps comptabilisé en fonction de la frequence_temps_affectation
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
				
			<div class="input-group"><label>Dur&eacute;e (en min):</label>
					<input value="<?php echo $duree; ?>" name="duree" class="form-control">
					
					<input value="1" type="hidden" name="pastresa">
				</div>
				
				<div class="input-group">
					<div class="row">
					<div class="col-lg-6"><label>Date</label>
						<div class="input-group"> <span class="input-group-addon"> <i class="fa fa-calendar"></i></span>
						<input name="date" id="dt0" placeholder="Prenez une date"  value="<?php echo $date; ?>" class="form-control">
					</div></div>
					
					
					<div class="col-lg-6"><label>Heure</label>
						<div class="input-group"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
						<input  id="dt1" name="heure" value="<?php echo $heure;?>" class="form-control">
					</div><!-- /.input group -->
					</div>
					</div>
				</div>
				
			</div><!-- /box body-->
			<div class="box-footer"><input type="submit" name="resa_submit" value="valider la reservation"  class="btn btn-primary"></div>
			</form>
			
			</div><!-- /box-->
</div><!-- /col-->
			
<?php
		}
	}
}
?>
</div>
<!-- /row-->



<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/atelier.js'></script>