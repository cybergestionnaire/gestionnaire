<?php
/*
 DETAIL D'UN ATELIER MODIFICATION 2013

*/

$b          = $_GET["b"];
$idatelier  = $_GET["idatelier"];
$iduser     = $_GET["iduser"];
$searchuser = $_POST["searchuser"];
$idstatut =$_GET["idstatut"];
$espaces = getAllepn();

if ($b != "")   // affichage d'un atelier ---------------------------------------
{//debut if0
//recuperation des variables
$row = getAtelier($idatelier,0);
	$idsujet=$row["id_sujet"];
	$result=getSujetById($idsujet);
	$rowsujet=mysqli_fetch_array($result);
	$anim= getUserName($row["anim_atelier"]);
	$salle=mysqli_fetch_array(getSalle($row["salle_atelier"]));
	$nomsalle=$salle["nom_salle"]." (".$espaces[$salle["id_espace"]].")";
	$tarif=getNomTarif($row["tarif_atelier"]);
	$idtarif=$row["tarif_atelier"];
	$statut=$row["statut_atelier"];
	//variable pour les courriers
	$idepn=$salle['id_espace'];
	$statusepnconnect=$row["status_atelier"];
	
//actions du formulaire pour inscriptions
	if ($b == 2)
	  {
		  if (FALSE != addUserAtelier($idatelier,$idstatut,$iduser,$idtarif))
		  {
			  echo geterror(25);
		  }
	  }
	  if ($b == 3)
	  {
		  if (FALSE != delUserAtelier($idatelier,$iduser))
		  {
			  echo geterror(27);
		  }
	  }
	 if ($b == 4)
	  {
		  //test s'il reste une place ou non, si oui enlever de la liste d'attente
		  if (countPlace($idatelier)<$row["nbplace_atelier"]){
		  
		  if (FALSE != ModifyUserAtelier($idatelier,$iduser,0))
		  {
			  echo geterror(26);
		  }
		  }else{
		  echo "<div class=\"alert alert-warning alert-dismissable\"><i class=\"fa fa-exclamation\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>L'atelier est d&eacute;j&agrave; complet, veuillez attendre qu'une place se lib&egrave;re !</div>" ;
			}
		}
	  if ($b == 5) // arrivée depuis le formulaire des présences
	  {
		    echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Pr&eacute;sence valid&eacute;e</div>" ;
		}
		if ($b == 6) // deplacer de l'inscription à la liste d'attente
	  {
		    if (FALSE != ModifyUserAtelier($idatelier,$iduser,2))
		  {
			  
			 echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription en liste d'attente valid&eacute;e</div>" ;
			}else{
				echo geterror(26);
		  
			}
		}
	
	  if ($b == 10) // en liste d'attente
	  {
		  if (FALSE != addUserAtelierAttente($idatelier,$idstatut,$iduser))
		  {
			  echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>Inscription en liste d'attente valid&eacute;e</div>" ;
		  }
	  }
	
		//libération pour epnconnect
		if ($b == 11) 
	  {
		  if (FALSE != ModifStatusAtelier($idatelier,1))
		  {
			  echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;EpnConnect lib&egrave;re les postes pour l'atelier.</div>" ;
		  }
	  }
		
		//Cloture de l'atelier pour epnconnect
		if ($b == 12) 
	  {
		  if (FALSE != ModifStatusAtelier($idatelier,2))
		  {
			  echo "<div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-info\"></i>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>&nbsp;Atelier cl&ocirc;tur&eacute;, EpnConnect reprends le contr&ocirc;le !</div>" ;
		  }
	  }
	
			
		if (countPlace($idatelier)>$row["nbplace_atelier"]){
			$nbplace=0;
		}else {
			$nbplace = $row["nbplace_atelier"]-countPlace($idatelier);
		}
		//adherent en attente
		$rattente = getAtelierUser($idatelier,2) ; 
		$enattente=mysqli_num_rows($rattente);
		
$testTarifAtelier= TestTarifs();		
//
////Envoi du mail de rappel
//retrouver les mails de l'epn, les donnees texte subject/body
//coordonnees de l'espace
$arraymail=getMailRappel();

if (FALSE==$arraymail){
	
	$mailok=0;

}else{
$espacearray=mysqli_fetch_array(getEspace($idepn));
$mail_epn=$espacearray["mail_espace"];
$adresse_epn=$espacearray["adresse"];
$nom_epn=$espacearray["nom_espace"];
$tel_epn==$espacearray["tel_espace"];

$arraymailtype=array(
1=>"Introduction",
2=>"Sujet/object",
3=>"Corps du texte",
4=>"Signature"
);

$mail_subject=$arraymail[2];
$mail_body1=$arraymail[3];
$mail_signature=$arraymail[4];

$mail_body=$mail_body1."\r\n en date du ".getDayfr($row["date_atelier"])." &agrave; ".$row["heure_atelier"]." pour l'atelier ".$rowsujet["label_atelier"]."  anim&eacute; par ".$anim."  &agrave; ".$nomsalle.".\n\r D&eacute;tail de l'atelier : \r\n".stripslashes($rowsujet["content_atelier"]).". ".$mail_signature." \r\n\r\n".$nom_epn." \r\n".$adresse_epn." \r\n".$tel_epn.".";

$mailok=1;
}
 
$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
  
}
		
    ?> 
	
<!-- DETAIL DE L'ATELIER-->
<div class="row"><!-- Left col --><section class="col-lg-7 connectedSortable">
 
<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Atelier <?php echo $rowsujet["label_atelier"];?></h3></div>
	<div class="box-body">
		<dl class="dl-horizontal">
		       <dt>Date</dt><dd>Le <?php echo getDayfr($row["date_atelier"]);?> &agrave; <?php echo $row["heure_atelier"];?> </dd>
		       <dt>Anim&eacute; par</dt><dd> <?php echo $anim;?></dd>
		       <dt>O&ugrave;</dt><dd> <?php echo $nomsalle ;?> </dd>
		       <dt>Tarif</dt><dd> <?php echo $tarif ;?></dd>
		       <dt>Places restantes</dt><dd> <?php echo $nbplace ;?> (Total : <?php echo $row["nbplace_atelier"];?> places ouvertes)</dd>
					<dt>Adh&eacute;rents en attente </dt><dd><?php echo $enattente ;?></dd>
		       <dt>Description</dt><dd><?php echo stripslashes($rowsujet["content_atelier"]);?></dd>
		</dl>
	</div>
	<?php 
	//test activation epnconnect pour les ateliers + si date du jour OK 
	if (date('Y-m-d')>=$row["date_atelier"]){
	if ($statusepnconnect>0){
		$class="disabled";
		$action="#";
		}else{
			$class="";
			$action="index.php?a=13&b=11&idatelier=".$idatelier;
		}
	if ($statusepnconnect==2){$class2="disabled"; }else{			$class2="";}
	?>
	<div class="box-footer">

		<a href="<?php echo $action; ?>"><button class="btn bg-red" type="submit"  <?php echo $class; ?>> <i class="fa fa-unlock"></i>&nbsp;&nbsp;D&eacute;sactiver EpnConnect</button></a>
		&nbsp;<a href="index.php?a=13&b=12&idatelier=<?php echo $idatelier;?>"><button class="btn bg-green" type="submit" <?php echo $class2; ?>> <i class="fa fa-lock"></i>&nbsp;&nbsp;R&eacute;activer EpnConnect</button></a></div>
	<?php }?>
	<div class="box-footer"><a href="index.php?a=11"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; la liste des ateliers</button></a></div>
</div>

	
<!-- Fin DETAIL DE L'ATELIER-->
<!-- **********************liste des user inscrit a un atelier-->
	<?php

		$result = getAtelierUser($idatelier,0) ; 
		$nb = mysqli_num_rows($result) ;
		
if ($nb>0)
{      
	//tester la présence de tarifs ateliers
	
	if($testTarifAtelier>1){ 
		$tooltipinfo="Inscriptions en cours / total d&eacute;pensé  sur total achet&eacute;";
		}else{
		$tooltipinfo="Inscriptions en cours";
		}
     	?>
	
	

     	<div class="box box-success"><div class="box-header"><h3 class="box-title">Abonn&eacute;s inscrits</h3>
				<div class="box-tools pull-right" ><div class="btn-group" ><small class="badge bg-blue" data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>"><i class="fa fa-info"></i></small></div></div></div>
		<div class="box-body">
			 <table class="table">
				<thead><th>Fiche</th><th>Nom, pr&eacute;nom</th><th><span data-toggle="tooltip" title="<?php echo $tooltipinfo; ?>">Inscriptions/Forfait</span>
				
				</th><th></th></thead>
		<?php

		 for ($i = 1 ; $i<= $nb; $i++)
		{
			$row2 = mysqli_fetch_array($result) ;
			// 0= inscription en cours non validée
			$nbASencours=getnbASUserEncours($row2['id_user'],0) ; 
			
			// construction des BCCmail
			if($row2["mail_user"]<>''){
					$bccusers=$bccusers.trim($row2["mail_user"]).";";
			}
			
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
    <tr><td><a href="index.php?a=1&b=2&iduser=<?php echo $row2['id_user'] ;?>"  class="btn btn-default btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></a>
			</td>
			<td><span class="badge bg-yellow" data-toggle="tooltip" title="Date renouvellement adh&eacute;sion : <?php echo $row2["dateRen_user"]; ?>">A</span>&nbsp;&nbsp;<?php echo $row2["nom_user"]." ".$row2["prenom_user"] ;?> </td>
			<td><?php echo $affichage ?></td>
			<td>
			 <a href="index.php?a=6&iduser=<?php echo $row2['id_user'] ;?>"  class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>
			 <a href="index.php?a=5&b=6&iduser=<?php echo $row2['id_user'] ;?>"  class="btn bg-blue btn-sm" data-toggle="tooltip" title="Autres inscriptions"><i class="fa fa-keyboard-o"></i></a>
			 <a href="courriers/lettre_atelier.php?user=<?php echo $row2['id_user'];?>&epn=<?php echo $idepn; ?>" target="_blank" class="btn bg-navy btn-sm" data-toggle="tooltip" title="imprimer les inscriptions"><i class="fa fa-envelope"></i></a>
			 
			<?php
				if ($statut==0 ){ ?>
			<a href="index.php?a=13&b=3&iduser=<?php echo $row2['id_user'];?>&idatelier=<?php echo $idatelier;?>"  class="btn bg-red btn-sm"  data-toggle="tooltip" title="D&eacute;sinscrire" ><i class="fa fa-trash-o"></i></a>
			<?php } ?>
			 <a href="index.php?a=13&b=6&idatelier=<?php echo $idatelier;?>&iduser=<?php echo $row2['id_user'] ;?>"  class="btn bg-purple btn-sm"  data-toggle="tooltip" title="Mettre en liste d'attente"><i class="fa fa-repeat"></i></a>
			
			</td></tr>
            
	    <?php
		}
	
		 ?>
		</table></div>
	<div class="box-footer">
	 
	 <?php if ($statut<2 ){
	 //validation interdite si déjà faite !
		echo "<a href=\"index.php?a=16&b=4&act=0&idatelier=".$idatelier."\"><input type=\"submit\" name=\"valider_presence\" value=\"Valider les Presences\" class=\"btn btn-success\"></a>";
		 } else if($statut==2){
		
		echo "<p class=\"text-red\">Cet atelier est pass&eacute; et clotur&eacute;, vous ne pouvez plus inscrire d'adh&eacute;rent</p>";
		 } 
		 
		 //Bouton d'envoi de mail de rappel
		 if($mailok==1){
		?>
		<a href="mailto:<?php echo $mail_epn; ?>?BCC=<?php echo $bccusers; ?>&SUBJECT=<?php echo $mail_subject; ?>&BODY=<?php echo $mail_body; ?>">
		<button class="btn bg-navy  pull-right"> <i class="fa fa-paper-plane"></i> Envoyer un rappel </button></a>
		<?php } ?>
		
	</div>		
</div><!-- box -->

			
	<?php
 } ?>
	
	
</section>
<!--**********************inscrire un adherent à l'atelier-->
<section class="col-lg-5 connectedSortable"> 

  <div class="box box-success">
	<div class="box-header"><h3 class="box-title">Inscription</h3>
		<div class="box-tools"><form method="POST" action="index.php?a=13&b=1&idatelier=<?php echo $idatelier ;?> " role="form">
     		<div class="input-group">
			<input type="text" name="searchuser" class="form-control input-sm pull-right" style="width: 200px;" placeholder="Nom ou num&eacute;ro de carte"/>
    		<div class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button> </div></div></form>
           </div>
         </div><!-- /.box-header -->
   <div class="box-body table-responsive">
    	
  <?php
   //resultat de la recherche si -------------------------------------
    if ($searchuser !="" and strlen($searchuser)>2)
	{ // debut ifsearch
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
			<thead><tr><th>Nom, Pr&eacute;nom</th><th>Inscriptions/Forfait</th><th></th></tr></thead><tbody>
                <?php
		for ($i=1; $i<=$nb; $i++){
      $row = mysqli_fetch_array($result) ;
		  $statutuser=$row["status_user"];
		//mise en place tarification
			if($testTarifAtelier>1){ 
				$nbASencours=getnbASUserEncours($row['id_user'],0) ; 
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
				
			//en grisé si adhérent inactif
			if( $statutuser==2){$classstatut="text-muted";}else{$classstatut="";}
					
		if ($nbplace>0){
		echo "<tr><td class=".$classstatut.">".$row["nom_user"]." ".$row["prenom_user"]."</td>
		 <td>".$affichage."</td>
		<td><a href=\"index.php?a=13&b=2&idstatut=0&idatelier=".$idatelier."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-success sm\"  data-toggle=\"tooltip\" title=\"Inscrire\"><i class=\"fa fa-check\"></i></button></a>
		<a href=\"index.php?a=13&b=10&idstatut=2&idatelier=".$idatelier."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn bg-purple sm\"   data-toggle=\"tooltip\" title=\"Mettre en liste d'attente\"><i class=\"fa fa-repeat\"></i></button></a></td>
			
                         </tr>";
			}else{
		echo "<tr><td>".$row["nom_user"]." ".$row["prenom_user"]."</td>
		  <td>".$affichage."</td>
			<td><a href=\"index.php?a=13&b=10&idstatut=2&idatelier=".$idatelier."&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-success sm\"><i class=\"fa fa-pause\" title=\"Mettre en liste d'attente\"></i></button></a></td>
			
                         </tr>";
			}
					
                    }
                ?>
          </tbody></table>
    <?php          }
    
	}
	
    } ?>
    </div></div>
    
<?php	



	//******************* liste des user en liste d'attente
	$result = getAtelierUser($idatelier,2) ; 
    $nb = mysqli_num_rows($result) ;
    if ($nb>0)
    {                  
     	?> <div class="box box-warning">
                <div class="box-header"><h3 class="box-title">Abonn&eacute;s sur la liste d'attente   <small class="badge bg-blue" data-toggle="tooltip" title="Classement par ordre d'arriv&eacute;e, du plus ancien au plus r&eacute;cent"><i class="fa fa-info"></i></small></h3></div>
		<div class="box-body"><table class="table">
		<thead><tr> 
			<th>&nbsp;</th>
			<th>Nom, prenom</th>
			<th>PDF</th>
		</tr></thead><tbody>
	<?php	
		 for ($i = 1 ; $i<= $nb; $i++)
         {
             $row2 = mysqli_fetch_array($result) ;
            ?>
             <tr><td><a href="index.php?a=13&b=4&iduser=<?php echo $row2['id_user'];?>&idatelier=<?php echo $idatelier;?>"><button type="button" class="btn bg-green btn-sm"  data-toggle="tooltip"  title="Inscrire"><i class="fa fa-arrow-up"></i></button></a>
				<a href="index.php?a=13&b=3&iduser=<?php echo $row2['id_user'];?>&idatelier=<?php echo $idatelier;?>"><button type="button" class="btn btn-warning btn-sm"  data-toggle="tooltip" title="D&eacute;sinscrire"><i class="fa fa-trash-o"></i></button></a></td>
                 <td><?php echo $row2["nom_user"]." ".$row2["prenom_user"] ;?></td>
		<td><a href="lettre_atelier.php?user=<?php echo $row2['id_user'];?>&epn=<?php echo $idepn; ?>" target="_blank"><button type="button" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Imprimer les inscriptions"><i class="fa fa-envelope"></i></button></a></td></tr>
             <?php
			 
         }
		 
         echo "</tbody></table></div></div>" ;
    }//FIN en Attente
	?>
</section>
<!-- retour de la validation-->
<?php if($statut==2){ ?>
	

<section class="col-lg-5 connectedSortable"> 

  <div class="box box-success"><div class="box-header"><h3 class="box-title">Atelier clotur&eacute;</h3></div>
	<div class="box-body">
		<p>Les pr&eacute;sences &agrave; cet atelier viennent d'&ecirc;tre valid&eacute;e. <br><p class="text-warning">Cliquez sur "r&eacute;activer EPNConnect" pour cloturer l'atelier.</p> 
		Les archives vous permettront de modifier une pr&eacute;sence en cas d'erreur !</p>
	</div>
	</div>
</section>	
<?php } ?>

</div><!-- /row -->


<?php
}
?>
