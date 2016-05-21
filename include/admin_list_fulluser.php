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

    //tableau des statuts
    $statutarray=array(
    1=> "Actif",
    2=> "Inactif",
    6=> "Archiv&eacute;"
    );

//** adherents mis en archive ***///
if(isset($_POST["archivage"])){

	$arrayusers=$_POST["archiv_"];
	$nbusersarchiv=count($arrayusers);

	if($nbusersarchiv>0){ 
		for($i=0;$i<$nbusersarchiv;$i++){
			moduserstatus($arrayusers[$i],6); //6=archivé statistique
		}
		
		echo '<div class="row"><div class="col-md-4">';
		echo geterror(47);
		echo '</div></div>';
	}
	//vidage des variables
	$arrayusers=[];
	$nbusersarchiv=0;
}

?>


<div class="row"> 

<!-- Resultats de la recherche -->
<div class="col-xs-12">
<?php
if (strlen($term)>=2)
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
     <div class="box box-info"><div class="box-header"><h3 class="box-title"><?php echo "R&eacute;sultats de la recherche: ".$nb."";?>&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Les adh&eacute;rents inactifs sont gris&eacute;s"><i class="fa fa-info"></i></small></h3>
     <!-- div recherche -->
      <div class="box-tools"><div class="input-group"><form method="post" action="index.php?a=1">
				 <div class="input-group input-group-sm">
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div>
                 </form></div>
           
           
           </div>
     
     
     
	
		</div>
	<div class="box-body no-padding"><table class="table">
	<thead><th></th><th>Nom</th><th>Pr&eacute;nom</th><th>login</th><th>Age</th><th>Visite r&eacute;cente(r&eacute;sa)</th><th>Statut</th><th>Adh&eacute;sion</th><th>Temps Utilis&eacute;</th></thead><tbody> 
			<?php
    for ($i=1; $i<=$nb; $i++)
      {
				$row = mysqli_fetch_array($result) ;
				
					$age = date('Y')-$row["annee_naissance_user"];
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
				
					//calcul du % de temps restant
						$resautilise = getTempsCredit($row["id_user"],$date1,$date2);
						$restant=$tarifreferencetemps-$resautilise['util'];
						$rapport=round(($restant/$tarifreferencetemps)*100);
						
						 if($row['status_user']==2 or $row['status_user']==6 ){
                            $class="text-muted" ;
                        }else{
                            $class="" ;}
							
					//dernière reservation
					$lasteresa=getLastResaUser($row["id_user"]);
					if($lasteresa==FALSE){$lasteresa="NC";}
					?>
						
			<?php		
				echo '<tr class='.$class.'>
				<td>'.$i.'</td>
				<td>'.$row["nom_user"].'</td>
				<td>'.$row["prenom_user"].'</td>
				
				<td>'.$row["login_user"].'</td>
				<td>'.$age.' ans</td>
				<td>'.$lasteresa.'</td>
				<td>'.$statutarray[$row['status_user']].'</td>
				<td><span class="'.$classadh.'">'.$adhesion.'</span></td>
			<td>';
			//statut actif			
			if($row['status_user']==1){	
				if(TRUE==$tarifTemps){
					echo	'<span class="badge bg-blue">'.$tarifTemps["nom_forfait"].'</span>&nbsp;<b>'.getTime($restant).'</b>
						<div class="progress">';
						?>
							<div class="progress progress-sm active"> <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:".$rapport."%"; ?>"></div>
						</div>
						
						<?php
						}
			}else{
				echo	'<span class="badge bg-red">NC</span> 0h';
			}
				echo '</div></td>';
			//boutons 
			echo "<td><a href=\"index.php?a=1&b=2&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary btn-sm\" data-toggle=\"tooltip\" title=\"fiche adh&eacute;rent\"><i class=\"fa fa-edit\"></i></button></a>
							 &nbsp;<a href=\"index.php?a=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-yellow btn-sm\"  data-toggle=\"tooltip\" title=\"Abonnements\"><i class=\"ion ion-bag\"></i></button></a>";
							
			if(chechUserAS($row["id_user"])==TRUE){
							echo	" &nbsp;<a href=\"index.php?a=5&b=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-primary btn-sm\" data-toggle=\"tooltip\" title=\"Autres inscriptions\"><i class=\"fa fa-keyboard-o\"></i></button></a>";
				}
							
			echo	"</td></tr>";
						
						?>			
									
									
									
		<?php		 }      ?>
      </tbody> </table>
			</div></div>
			
<?php
		}
	}
}
else // si pas de recherche alors affichage classique
{

 switch($_GET['adh']) // on recupere le type de membre a afficher
    {
      default:
      case 1:
           $titleAdh = "Adh&eacute;rents actifs" ;
           $typeAdh = 1;
           $num = 2 ;
           $other = 'inactifs';
           $numOther=2;
					 $othera = 'archiv&eacute;s';
           $numOthera=6;
          // $_SESSION['page']=1;
      break;
      case 2:                                     
           $titleAdh = "Adh&eacute;rents inactifs" ;
           $typeAdh = 2;
           $num = 3;   
           $other = 'actifs';
           $numOther=1;
						$othera = 'archiv&eacute;s';
           $numOthera=6;					 
          // $_SESSION['page']=1;
      break;
			case 6:                                     
           $titleAdh = "Adh&eacute;rents archiv&eacute;s" ;
           $typeAdh = 6;
           $num = 4;   
           $other = 'actifs';
           $numOther=1; 
					$othera = 'inactifs';
           $numOthera=2;
          // $_SESSION['page']=1;
      break;
    }
	
	//utilisation des utilisateurs par type actifs/inactifs
	$result=getAllUser($typeAdh);
	
	
    if (FALSE == $result OR mysqli_num_rows($result)==0)
    {
      echo "<br><div class=\"row\"><div class=\"col-xs-6\">";
			echo getError(1);
		
			echo "</div><div class=\"col-xs-6\"><div class=\"alert alert-info alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;&nbsp;&nbsp;<a  href=\"index.php?a=1&b=1\" >Cr&eacute;er un nouvel utilisateur ?</a></div></div></div>";
			
			
    }
    else  // affichage du resultat
    {
    //$nb  = mysqli_num_rows($result);
	// count total number of appropriate listings:
	$tcount = mysqli_num_rows($result);
	$rpp = 50; // results per page
	
	// count number of pages:
	$tpages = ($tcount) ? ceil($tcount/$rpp) : 20;
	//debug($tpages);
	/// AJOUT PAGINATION
	$page   = intval($_GET['page']);
	$adjacents  = intval($_GET['adjacents']);
	if($page<=0)  $page  = 1;
	if($adjacents<=0) $adjacents = 4;
	$reload = $_SERVER['PHP_SELF'] . "?a=1&adh=".$typeAdh."&tpages=" . $tpages . "&amp;adjacents=" . $adjacents;
	/// Fin pagination
	
    if ($tcount > 0)
    {
    echo " <div class=\"box box-info\"><div class=\"box-header\"><h3 class=\"box-title\">".$titleAdh." : ".countUser($num)."/".countUser(1)."  ";
				if (countUser(3)>0){
					echo "&nbsp;(<a href=\"index.php?a=1&adh=".$numOther."\">afficher les ".$other." </a>)";
				}else{
					echo ""; }
					//ajout des archivés
				if (countUser(4)>0){
					echo "&nbsp;(<a href=\"index.php?a=1&adh=".$numOthera."\">afficher les ".$othera." </a>)";
				}else{
					echo ""; }
		
    ?>
	</h3>
	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1"  class="btn btn-default"  data-toggle="tooltip" title="Ajouter un adh&eacute;rent"><i class="fa fa-plus"></i></a>
			&nbsp;&nbsp; <a href="index.php?a=1&b=3" class="btn btn-default"  data-toggle="tooltip" title="Voir les derniers inscrits"><i class="fa fa-users"></i></a>
		
	  <div class="box-tools">
	<div class="input-group"><form method="post" action="index.php?a=1">
				 <div class="input-group input-group-sm">
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
   </div></form></div></div>
		
     <!-- 	<div class="box-tools pull-right"><?php  echo paginate_two($reload, $page, $tpages, $adjacents); ?>	</div>-->
			</div><!--/head -->

	
	
	<div class="box-body table-responsive"><table class="table table-bordered table-striped">
        <thead>
            <tr><th>&nbsp;</th><th>Nom</th><th>Pr&eacute;nom</th><th>Nom d'utilisateur</th><th>Age</th><th>Derniere visite (r&eacute;sa)</th><th>Adh&eacute;sion  <span class="badge bg-primary"  data-toggle="tooltip" title="Vert = en cours, Jaune = adh&eacute;sion &agrave; renouveller dans la semaine"><i class="fa fa-info"></i></th>
							<th>
						<?php	
						if($_GET['adh']==1 OR $_GET['adh']==''){	
							echo 'Forfait temps';
						}elseif ($_GET['adh']==2){ 
						echo '<form role="form" method="POST"><button type="submit" name="archivage" class="btn bg-red btn-sm" data-toggle="tooltip" title="Archiver pour statistique" OnClick="return confirm(\'Veuillez confirmer le changement de statut de ces adh&eacute;rents !\');"><i class="fa fa-archive"></i></button>';
						}else{
						echo '';
						} ?>
							</th></tr></thead>
            <?php
                $count = 0;
				$i = ($page-1)*$rpp;
				while(($count<$rpp) && ($i<$tcount)) {
					mysqli_data_seek($result,$i);
					
					
          $row = mysqli_fetch_array($result) ;
					/* dernière connexion au portail
						if ($row["lastvisit_user"] != "0000-00-00"){
							$dateusa = DateTime::createFromFormat('Y-m-d', $row["lastvisit_user"]);
							$datefr = $dateusa->format('d/m/Y');
						}else{
							$datefr="NC";
						}
						*/
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
					//dernière reservation
					$lasteresa=getLastResaUser($row["id_user"]);
					if($lasteresa==FALSE){$lasteresa="NC";}
					//debug($lasteresa);
					
												
            echo "<tr><td><a href=\"index.php?a=1&b=2&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-purple btn-sm\" data-toggle=\"tooltip\" title=\"Fiche adh&eacute;rent\"><i class=\"fa fa-edit\"></i></button></a>
													<a href=\"index.php?a=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-yellow btn-sm\" data-toggle=\"tooltip\" title=\"transactions\"><i class=\"ion ion-bag\"></i></button></a> ";
						if(chechUserAS($row["id_user"])==TRUE){
						echo	" &nbsp;<a href=\"index.php?a=5&b=6&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-primary btn-sm\" data-toggle=\"tooltip\" title=\"Inscriptions Ateliers\"><i class=\"fa fa-keyboard-o\"></i></button></a>";
							}			
						
						echo '</td>
									<td>'.$row["nom_user"].'</td>
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
										echo '<span class="badge bg-red">NC</span> 0h';
									}
								}	elseif($row['status_user']==2){	//passer du statut inactif au statut archivé
					
								echo '
								
								<input type="checkbox" name="archiv_[]" class="minimal" value='.$row['id_user'].'>
									';
								}
									
					echo '</td></tr>';
				
								 
					$i++;
					$count++;
								 
                    }
					
            ?>
    </table></form><br>
    <?php
        //if ($_SESSION['nbpager']!=0)
		
           echo '<div class="box-footer clearfix">' ;
           echo paginate_two($reload, $page, $tpages, $adjacents);
		   echo '</div>';
       
    }
   }

}
?>

	
</div></div>

