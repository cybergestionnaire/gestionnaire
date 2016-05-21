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

if ($mesno !="")
{
  echo getError($mesno);
}

// Tableau des unité d'affectation
    $tab_unite_temps_affectation = array(
           1=> 1, //minutes
           2=> 60 //heures
    );
	
	// Tableau des fréquence d'affectation
    $tab_frequence_temps_affectation = array(
           1=> "par Jour",
           2=> "par Semaine",
           3=> "par Mois"
    );


?>

<div class="row"> 

<!-- Resultats de la recherche -->
<div class="col-xs-12">
<?php
if (strlen($term)>=3)
{
    // Recherche d'un adherent
    $result = searchUser($term);
    if (FALSE == $result OR mysqli_num_rows($result)==0)
    {
     
		echo "<div class=\"col-xs-6\">";
		 echo getError(6);
		
			echo "</div><div class=\"col-xs-6\"><div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;&nbsp;&nbsp;<a  href=\"index.php?a=1&b=1\" >Cr&eacute;er un nouvel utilisateur ?</a></div></div>";
			
    }
    else
    {
      $nb  = mysqli_num_rows($result);
      if ($nb > 0)
      {
      ?>
    <div class="box box-info"><div class="box-header"><h3 class="box-title"><?php echo "R&eacute;sultats de la recherche: ".$nb." ";?>&nbsp;&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Les adh&eacute;rents inactifs sont gris&eacute;s"><i class="fa fa-info"></i></small></h3>
					<div class="box-tools">
			<div class="input-group"><form method="post" action="index.php?a=1">
				 <div class="input-group input-group-sm">
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
                 </form></div><!-- /input-group -->
	  		
			</div>
		
		</div>
	<div class="box-body no-padding"><table class="table">
	<thead><tr><th></th><th>Nom</a></th>
		<th>Pr&eacute;nom</a></th>
		
		<th>login</a></th>
		<th>Age</a></th>
		<th>Temps Utilis&eacute;</a></th>
		<th></th></thead><tbody> 
					
             <?php
    
			for ($i=1; $i<=$nb; $i++)
			{
					$row = mysqli_fetch_array($result) ;
					$credit  = getTime($row["temps_user"]);
$age = date('Y')-$row["annee_naissance_user"];
$utilise = getTempsCredit($row["id_user"],$date1,$date2);
					if ($row["temps_user"]==999)
					{
							$credit = "Infini";
					}
				if($row['status_user']==2 or $row['status_user']==6 ){
							$class="text-muted" ;
					}else{
							$class="" ;}
                            
      echo "<tr class=".$class.">
				<td>".$i."</td>
				<td>".$row["nom_user"]."</td>
				<td>".$row["prenom_user"]."</td>
				
				<td>".$row["login_user"]."</td>
				<td>".$age." ans</td>
				<td>".getTime($utilise['util'])."</td> ";
			
          echo "<td><a href=\"index.php?a=1&b=2&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary btn-sm\" data-toggle=\"tooltip\" title=\"fiche adh&eacute;rent\"><i class=\"fa fa-edit\"></i></button></a>
							 &nbsp;<a href=\"index.php?a=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-yellow btn-sm\"  data-toggle=\"tooltip\" title=\"Abonnements\"><i class=\"ion ion-bag\"></i></button></a>";
					
					if(chechUserAS($row["id_user"])==TRUE){
							echo	" &nbsp;<a href=\"index.php?a=5&b=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-primary btn-sm\" data-toggle=\"tooltip\" title=\"Autres inscriptions\"><i class=\"fa fa-keyboard-o\"></i></button></a>";
					}
							
						echo	"</td>
							 </tr>";
			 }
            ?>
      </tbody> </table>
			</div></div>
			
<?php
		}
	}
}
else // si pas de recherche alors affichage classique
{
   
   // Les adhérents // MODIF 2012 : liste des 25 derniers inscrits......
  	$result= getLastUser(25);
 
    if (FALSE == $result)
    {
      echo getError(1);
    
   
    }
    else  // affichage du resultat
    {
    $nb  = mysqli_num_rows($result);
    //debug($nb);
	
	if ($nb > 0)
    {
	 ?>
   	
	<div class="box box-info">
		<div class="box-header"><h3 class="box-title">Liste des 25 derniers adh&eacute;rents inscrits</h3>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1"  class="btn btn-default"  data-toggle="tooltip" title="Ajouter un adh&eacute;rent"><i class="fa fa-plus"></i></a>
			&nbsp;&nbsp; <a href="index.php?a=1" class="btn btn-default"  data-toggle="tooltip" title="Voir tous les adh&eacute;rents"><i class="fa fa-users"></i></a>
			<div class="box-tools">
			
			<div class="input-group"><form method="post" action="index.php?a=1">
				 <div class="input-group input-group-sm">
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
                 </form></div><!-- /input-group -->
	  		
			</div>
			
		</div>
	
	<div class="box-body no-padding"><table class="table">
		<thead><th></th>
				 <th>Nom</th>
				 <th>Pr&eacute;nom</th>
				 <th>Nom d'utilisateur</th>
				 <th>Age</th>
				 <th>Derni&egrave;re visite (r&eacute;sa)</th>
				 <th>Adh&eacute;sion</th>
				 <th>Temps Utilis&eacute;</th></thead> 
			<tbody> 
            <?php
    
		for ($i=1; $i<=$nb; $i++)
		{
     $row = mysqli_fetch_array($result) ;
			$age = date('Y')-$row["annee_naissance_user"];
			
    //ADHESION
	$adhesion=getNomTarif($row["tarif_user"]);
	$aujourdhui=date_create(date('Y-m-d'));
	$daterenouvellement=date_create($row["dateRen_user"]);
	//$interval = date_diff($aujourdhui,$daterenouvellement);
		//debug($interval->format('%R%a'));
	if ($row["status_user"]==1){
		if($daterenouvellement<=$aujourdhui){
			$classadh='label label-warning';
		}elseif($daterenouvellement>$aujourdhui){
			$classadh='label label-success';
			}
	}elseif($row["status_user"]==2){
		$classadh='label label-danger';
	}
						
	//TARIF CONSULTATION
	$tarifTemps= getForfaitConsult($row["id_user"]);
	$min=$tab_unite_temps_affectation[$tarifTemps["unite_temps_affectation"]];
	$tarifreferencetemps= $tarifTemps["nombre_temps_affectation"]*$min;
	if(TRUE==$tarifTemps){
						
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
		
	//debug($tarifreferencetemps);
		$resautilise = getTempsCredit($row["id_user"],$date1,$date2);
		$restant=$tarifreferencetemps-$resautilise['util'];
		$rapport=round(($restant/$tarifreferencetemps)*100);
	}		
			 if($row['status_user']==2){
						$class="text-muted" ;
				}else  if($row['status_user']==3){
						$class="text-aqua" ;
				}else{
					$class="";
				}
			    
	//dernière reservation
					$lasteresa=getLastResaUser($row["id_user"]);
					if($lasteresa==FALSE){$lasteresa="NC";}		
					
		echo "<tr class=".$class." >
       
				<td><a href=\"index.php?a=1&b=2&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-purple btn-sm\" data-toggle=\"tooltip\" title=\"Fiche adh&eacute;rent\"><i class=\"fa fa-edit\"></i></button></a>";
				
				if($row["status_user"]<3){   
					echo  "&nbsp;<a href=\"index.php?a=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn   bg-yellow btn-sm\" data-toggle=\"tooltip\" title=\"Abonnements\"><i class=\"ion ion-bag\"></i></button></a>";
					if(chechUserAS($row["id_user"])==TRUE){
							echo	" &nbsp;<a href=\"index.php?a=5&b=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-primary btn-sm\" data-toggle=\"tooltip\" title=\"Inscriptions Ateliers\"><i class=\"fa fa-keyboard-o\"></i></button></a>";
					}	
				echo "</td>";
				
				}
		echo '<td>'.$row["nom_user"].'</td>
			<td>'.$row["prenom_user"].'</td>
			<td>'.$row["login_user"].'</td>
			<td>'.$age.' ans</td>
		<td>'.$lasteresa.'</td>
			<td><span class="'.$classadh.'">'.$adhesion.'</span></td>
			<td>';
                  //statut actif			
						if($row['status_user']==1){	
							if(TRUE==$tarifTemps){
								echo	'<span class="badge bg-blue">'.$tarifTemps["nom_forfait"].'</span> '.getTime($restant).'
									<div class="progress">';
									?>
                    <div class="progress progress-sm active"> <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:".$rapport."%"; ?>"></div>
                  </div>
									
									<?php
									}else{
										echo	'<span class="badge bg-red">NC</span> 0h';
									}
								}	elseif($row['status_user']==2){	//statut inactif
					
								echo '<input type="checkbox" name="archiv" '.$check.' class="minimal"/><button type="button" class="btn bg-red btn-sm" data-toggle="tooltip" title="Archiver pour statistique"><i class="fa fa-archive"></i></button>';
									
								}
				  
				 echo '</td></tr>';
				}
?>
		</tbody> 
		</table></div></div>
			
			
    <?php
		
		}else{
		//si aucun nouvel inscrit depuis le début de l'année en cours
		echo "<div class=\"alert alert-warnint alert-dismissable\"><i class=\"fa fa-warning\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Aucun adh&eacute;rent n'est nouvellement inscrit dans votre espace</div>";
      echo '<a href="index.php?a=1" class="btn btn-default"  data-toggle="tooltip" title="Voir tous les adh&eacute;rents"><i class="fa fa-users"></i>&nbsp;&nbsp;Voir tous les autres adh&eacute;rents</a>';
		
		
		}
		
    }
	
}
?>

	
</div></div>

