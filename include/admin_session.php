<?php
/*
    
 include/admin_atelier.php V0.1
*/
$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

$b          = $_GET["b"];
$iduser     = $_GET["iduser"];
$searchuser = $_POST["searchuser"];
$idstatut =$_GET["idstatut"];
$idsession=$_GET["idsession"];
$espaces = getAllepn();
// affichage d'une session---------------------------------------
$row = getSession($idsession);
	$titresession=getTitreSession($row["nom_session"]);
	$anim=getUserName($row["id_anim"]);
	$salle=mysqli_fetch_array(getSalle($row["id_salle"]));
	$nomsalle=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
	$tarif=getNomTarif($row["id_tarif"]);
	$idtarif=$row["id_tarif"];
	$epn=$salle['id_espace'];
	$nbplace=$row["nbplace_session"];
	$sessionstatut=$row["status_session"];
	//Afficher les dates de la session
	$nbrdates=$row["nbre_dates_sessions"];
	$datesarray=getDatesSession($idsession);

for ($f=0; $f<$nbrdates ; $f++){
	$rowd=mysqli_fetch_array($datesarray);
	//debug($row);
	$datessession= $datessession.getDatefr($rowd["date_session"])." <br>";
	$iddate=$rowd["id_datesession"]; //donner la derniere id
	}
$placesoccupee=countPlaceSession($idsession,0);
$enattente=countPlaceSession($idsession,2);
$placesrestantes=$nbplace-$placesoccupee;

//tester la présence de tarifs ateliers
$testTarifAtelier=TestTarifs();

  if ($b == 2)
  {
      //verification d'inscription
       if (FALSE != checkUserSession($idsession,$iduser))
       {
       $datesarray2=getDatesSession($idsession);
       //boucler pour insérer le nombre de dates par sessions
		for ($f=0; $f<$nbrdates ; $f++){
			$row2=mysqli_fetch_array($datesarray2);
			addUserSession($idsession,$iduser,$idstatut,$row2["id_datesession"]);
		}
          echo geterror(25);
		header("Location:index.php?a=30&b=1&idsession=".$idsession) ;
	  
      }else{
      	echo geterror(21);
		header("Location:index.php?a=30&b=1&idsession=".$idsession) ;
      }
  }
  if ($b == 3)
  {
      if (FALSE != delUserSession($idsession,$iduser))
      {
          echo geterror(27);
		 header("Location:index.php?a=30&b=1&idsession=".$idsession) ;
      }
  }
 if ($b == 4)
  {
	
	if (FALSE !=ModifyUserSession($idsession,$iduser,0 ))
      {
		 echo geterror(26);
		 header("Location:index.php?a=30&b=1&idsession=".$idsession) ;
      }
  }
 

?> 
    
    
<!-- DETAIL DE LA SESSION-->
<div class="row"><section class="col-lg-7 connectedSortable">
 
<div class="box box-success">
	<div class="box-header"><h3 class="box-title"><?php echo stripslashes($titresession["session_titre"]);?></h3></div>
   <div class="box-body">
		<dl class="dl-horizontal">
           <dt>Dates programm&eacute;es</dt><dd> <?php echo $datessession;?></dd>
           <dt>Salle</dt><dd><?php echo $nomsalle; ?></dd>
            <dt>Places restantes</dt><dd> <?php echo $placesrestantes ;?> (Total : <?php echo $nbplace;?>)</dd>
	<dt>Adh&eacute;rents en attente</dt><dd> <?php echo $enattente ;?></dd>
             <dt>Description</dt> <dd><?php echo stripslashes($titresession["session_detail"]);?></dd>
	     <dt>Tarif</dt> <dd><?php echo $tarif; ?></dd>
             <dt>Anim&eacute; par</dt> <dd><?php echo $anim; ?></dd>
             
             </dl>
             </div>
	     <div class="box-footer">
	<a href="index.php?a=37"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des sessions</button></a></div>
	     </div>
<!-- DETAIL DE LA SESSION-->


	<?php		 
if ($sessionstatut==0){
///si la session est encore valide, affichage des adhérents	
   
	// liste des user inscrit a une session
    $result = getSessionUser($idsession,0) ;
	$nb = mysqli_num_rows($result) ;
	//$datesListe=explode(",",$row["session_dates"]);
	$validpresence=getSessionValid($idsession);

if ($nb>0)
{     
	if($testTarifAtelier>1){ 
		$tooltipinfo="Inscriptions en cours / total d&eacute;pens&eacute;  sur total achet&eacute;";
		}else{
		$tooltipinfo="Inscriptions en cours ";
		}

     	?>
	
	<div class="box box-success"><div class="box-header"><h3 class="box-title">Liste des participants</h3></div>
	<div class="box-body">
			 <table class="table">
			  <thead><th></th> <th>Nom, prenom</th>
					<th>Inscriptions/Forfait <small class="badge bg-blue" data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>"><i class="fa fa-info"></i></small></th>
					<th></th>
					</thead><tbody>
			
		<?php
		 for ($i = 1 ; $i<= $nb; $i++)
         {
             $row2 = mysqli_fetch_array($result) ;
			 // 0= inscription en cours non validée
			$nbASencours=getnbASUserEncours($row2['id_user'],0) ; 
			
			//mise en place tarification
			if($testTarifAtelier>1){ 
				
				// 1= présence validée & depensé sur forfait en cours
				$forfaitencours=getForfaitUserEncours($row2['id_user']);
				$depenseactuel=$forfaitencours["depense"]; //restant apres dépense
				$nbactuelsurforfait=$forfaitencours["total_atelier"];
				//nombre d'inscriptions validées hors forfait
				$nbtotalforfait=getForfaitAchete($row2['id_user']); //nbr total acheté !
				$nbASpresent=getnbASUservalidees($row2['id_user']); //nbr total validé
				$nbhorsforfait=$nbtotalforfait-$nbASpresent; //restant hors forfait
			
			//debug($nbrestant);
			
				if(FALSE==$forfaitencours){ //gestion hors forfait
					if($nbhorsforfait==0){
						$depense="0";
					}elseif ($nbhorsforfait<0){
						$depense="<span class=\"text-red\">".abs($nbhorsforfait)." Hors forfait</span>";
					}
					$affichage=$nbASencours."/ ".$depense;
				}else{
				//affichage avec forfait en cours
				
				$affichage=$nbASencours."/ ".$depenseactuel." sur ".$nbactuelsurforfait;
				
				}
			
			
			}else{ // sans le forfait, affichage des autres inscriptions
				$affichage=$nbASencours;
			}
            ?>
             <tr>
                 <td><a href="index.php?a=1&b=2&iduser=<?php echo $row2['id_user'] ;?>"><button type="button" class="btn btn-default  btn-sm"  data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a></td>
                 <td><?php echo $row2["nom_user"]." ".$row2["prenom_user"] ;?></td>
		  <td><?php echo $affichage ; ?></td>
		<td>
			<a href="index.php?a=5&b=6&iduser=<?php echo $row2['id_user'] ;?>"  class="btn bg-blue  btn-sm" data-toggle="tooltip" title="Autres inscriptions"><i class="fa fa-keyboard-o"></i></a>
		<a href="courriers/lettre_session.php?user=<?php echo $row2['id_user'];?>&epn=<?php echo $epn; ?>" target="_blank" class="btn bg-navy btn-sm" data-toggle="tooltip" title="envoyer un courrier"><i class="fa fa-envelope"></i></a>
		<a href="index.php?a=30&b=3&iduser=<?php echo $row2['id_user'];?>&idsession=<?php echo $idsession;?>" class="btn bg-red  btn-sm" data-toggle="tooltip" title="d&eacute;sinscrire"><i class="fa fa-trash-o"></i></a></td>
				 </tr>
             <?php
			 
         } ?>
		 
        </tbody></table></div>
		<!-- VALIDATION DES PRESENTS INSCRITS -->
		<div class="box-footer">
		<?php
		$datesarray=getDatesSession($idsession);
		for ($z=1;$z<=$nbrdates;$z++)
		{
			$rowd=mysqli_fetch_array($datesarray);
			if($rowd["statut_datesession"]==0){
			echo '<p></p><a href="index.php?a=32&act=0&idsession='.$idsession.'&dateid='.$rowd["id_datesession"].'&numerod='.($z-1).' "><input type="submit" value="Valider les pr&eacute;sences pour le '.getDayFr($rowd["date_session"]).'" class="btn btn-block bg-olive"></a>';
			}else if($rowd["statut_datesession"]==1){
			echo '<p></p><input type="submit" value="Atelier du '.getDayFr($rowd["date_session"]).' clotur&eacute;" class="btn btn-block bg-maroon" disabled/>';
			}else if($rowd["statut_datesession"]==2){
			echo '<p></p><input type="submit" value="Atelier du '.getDayFr($rowd["date_session"]).' Annul&eacute;" class="btn btn-block bg-orange" disabled/>';
			}
		}
		?>
		</div></div>
	<?php
    }
	
	
	echo '</section>';
?>
<!--AIDE COLONNE 2-->

 <section class="col-lg-5 connectedSortable">
<div class="box box-default collapsed-box box-solid"><div class="box-header with-border"><i class="fa fa-info-circle"></i><h3 class="box-title">Aide</h3>
	<div class="box-tools pull-right">
			    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			  </div><!-- /.box-tools -->
	</div><!-- /.box-header -->
	<div class="box-body">
	 
			<h4>Inscriptions</h4>
			<p>Attention, les inscriptions ne sont plus modifiables une fois toutes les dates de la session valid&eacute;es et clotur&eacute;es. </p>
		
	   
	</div><!-- /.box-body -->
</div><!-- /.box -->

<!--AIDE-->

<!--RECHERCHE ADHERENT-->


  <div class="box box-success">
  <div class="box-header"><h3 class="box-title">Inscrire un adh&eacute;rent</h3>
	<div class="box-tools"><form method="POST" action="index.php?a=30&b=1&idsession=<?php echo $idsession; ?>">
		<div class="input-group"><input type="text" name="searchuser" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Nom ou num&eacute;ro de carte"/>
    	<div class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button> </div></div></form>
    </div> 
	</div><!-- /.box-header -->
	<div class="box-body">
   
<?php

   //resultat de la recherche si -------------------------------------
    if ($searchuser !="" and strlen($searchuser)>2)
    {
        // Recherche d'un adherent
        $result = searchUser($searchuser);
        if (FALSE == $result OR mysqli_num_rows($result)==0)
        {
          echo getError(6);
        }
        else
        {
          $nb  = mysqli_num_rows($result);
          if ($nb > 0)
          {
          echo "<p>R&eacute;sultats de la recherche: ".$nb."</p>";
          ?>
          <table class="table table-hover">
			<thead><tr><th></th><th>Nom Pr&eacute;nom</th><th><span data-toggle="tooltip" title="Inscriptions en cours / total d&eacute;pens&eacute; sur total achet&eacute;">Inscriptions/Forfait</span></th></tr></thead><tbody>
                <?php
        
			for ($i=1; $i<=$nb; $i++)
			{
			$row = mysqli_fetch_array($result) ;
			
			if ($placesrestantes>0){
			
				 // 0= inscription en cours non validée
			$nbASencours=getnbASUserEncours($row['id_user'],0) ; 
			
			//mise en place tarification
			if($testTarifAtelier>1){ 
				
				// 1= présence validée & depensé sur forfait en cours
				$forfaitencours=getForfaitUserEncours($row['id_user']);
				$depenseactuel=$forfaitencours["depense"]; //restant apres dépense
				$nbactuelsurforfait=$forfaitencours["total_atelier"];
				//nombre d'inscriptions validées hors forfait
				$nbtotalforfait=getForfaitAchete($row['id_user']); //nbr total acheté !
				$nbASpresent=getnbASUservalidees($row['id_user']); //nbr total validé
				$nbhorsforfait=$nbtotalforfait-$nbASpresent; //restant hors forfait
			
			//debug($nbrestant);
			
				if(FALSE==$forfaitencours){ //gestion hors forfait
					if($nbhorsforfait==0){
						$depense="0";
					}elseif ($nbhorsforfait<0){
						$depense="<span class=\"text-red\">".abs($nbhorsforfait)." Hors forfait</span>";
					}
					$affichage=$nbASencours."/ ".$depense;
				}else{
				//affichage avec forfait en cours
				
				$affichage=$nbASencours."/ ".$depenseactuel." sur ".$nbactuelsurforfait;
				
				}
			
			
			}else{ // sans le forfait, affichage des autres inscriptions
				$affichage=$nbASencours;
			}
			
			  echo "<tr>
				<td><a href=\"index.php?a=30&b=2&idstatut=0&idsession=".$idsession."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-success sm\" title=\"Inscrire\"><i class=\"fa fa-check\"></i></button></a>
				<a href=\"index.php?a=30&b=2&idstatut=2&idsession=".$idsession."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-purple sm\"  title=\"Mettre en liste d'attente\"><i class=\"fa fa-repeat\"></i></button></a></td>
				
				<td>".$row["nom_user"]." ".$row["prenom_user"]."</td>
				<td>".$affichage."</td>
                                 </tr>";
						}else{
						echo "<tr>
								<td></td>
								<td><a href=\"index.php?a=30&b=2&idstatut=2&idsession=".$idsession."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-purple sm\"  title=\"Mettre en liste d'attente\"><i class=\"fa fa-repeat\"></i></button></a></td>
								<td>".$row["nom_user"]." ".$row["prenom_user"]."</td>
								<td>".$affichage."</td>
                                 </tr>";
								
								}
                        }
                ?>
          </tbody></table>
		  <?php } 
        
       }
    }
		?>
		</div>
	</div>
		
	<?php
	// liste des user en liste d'attente
    $result = getSessionUser($idsession,2) ; 
    $nb = mysqli_num_rows($result) ;
    if ($nb>0)
    {    
    ?>              
     	
		<div class="box box-success"><div class="box-header"><h3 class="box-title">Liste des participants en liste d'attente   <small class="badge bg-blue" data-toggle="tooltip" title="Classement par ordre d'arriv&eacute;e, du plus ancien au plus r&eacute;cent"><i class="fa fa-info"></i></small></h3></div>
		<div class="box-body"><table class="table">
				<thead> 
					<th></th> 
					
					<th>Nom, prenom</th>
					<!--<th>autres inscriptions (pdf)</th>-->
				</thead><tbody>
		<?php		
		
		 for ($i = 1 ; $i<= $nb; $i++)
         {
             $row2 = mysqli_fetch_array($result) ;
            ?>
             <tr>
                 <td><a href="index.php?a=30&b=4&iduser=<?php echo $row2["id_user"];?>&idsession=<?php echo $idsession;?>"><button type="button" class="btn btn-success sm"><i class="fa fa-check"></i></button></a>
				 <a href="index.php?a=30&b=3&iduser=<?php echo $row2["id_user"];?>&idsession=<?php echo $idsession;?>"><button type="button" class="btn btn-warning sm"><i class="fa fa-trash-o"></i></button></a></td>
                 <td><?php echo $row2["nom_user"]." ".$row2["prenom_user"] ;?></td>
				 <!--<td><a href="pdf_atelier.php?user=<?php echo $row2["id_user"];?>" target="_blank"><button type="button" class="btn btn-info sm"><i class="fa fa-envelope"></i></button></a></td>-->
				 </tr>
             <?php
			 
         }
		 ?>
        </tbody></table></div></div>
		
		<?php
    }
		
	?>
	
</section>	
	
<?php 	
	
}else{
//la session a ete cloturée
?>
	<div class="box box-success"><div class="box-header"><h3 class="box-title">Session clotur&eacute;e</h3></div>
		<div class="box-body">
		<p>Toutes les dates de cette session ont &eacute;t&eacute; clotur&eacute;es, vous ne pouvez plus modifier les inscriptions, pour modifier les pr&eacute;sences, rendez-vous aux archives !</p>
		</div>
		 <div class="box-footer">
	<a href="index.php?a=36"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Acc&egrave;s aux archives</button></a></div>
		</div>

<?php 
}
  	
?>


	
</div><!-- /row -->




