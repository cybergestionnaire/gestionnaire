<?php
/*
  
 Afficher la liste des comptes d'impression
 sélection d'un compte b=1

 include/admin_user.php V0.1
*/

// admin --- Utilisateur
$term   = $_POST["term"];
// affichage  -----------

$categorieTarif=array(
	1=>"impression",
	2=>"adhesion",
	3=>"consommables",
	4=>"divers"
	);

$statutPrint=array(
	0 =>"pas pay&eacute;",
	1 =>"pay&eacute;",
	);

?>
<div class="row">
<section class="col-lg-4 connectedSortable">
 
<div class="box box-primary box-solid">  <div class="box-header with-border"><h3 class="box-title">Outils</h3></div>
	<div class="box-body">
	<p>Etat de caisse journalier&nbsp;&nbsp;<a href="courriers/csv_caisse-jour.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('Y-m-d')?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a></p>
	<p>Etat de caisse hebdomadaire&nbsp;&nbsp;<a href="courriers/csv_caisse-hebdo.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('Y-m-d') ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a></p>
	<p>Etat de caisse mensuel<br>
		<a href="courriers/csv_caisse-mensuel.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo date('n') ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a>&nbsp;&nbsp;&nbsp;&nbsp;mois en cours</p>
		<p><a href="courriers/csv_caisse-mensuel.php?epn=<?php echo $_SESSION['idepn']; ?>&date=<?php echo (date('n')-1) ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;CSV</i></a>&nbsp;&nbsp;&nbsp;&nbsp;mois pr&eacute;c&eacute;dent</p>
		
	</div>
</div>
 
<!-- SOLDE CREDITEUR -->
 <?php
    //revoir le code sql !!!
	 $result = getPrintingUserswithcredit();
	 $nbpc  = mysqli_num_rows($result);
	
	 if ($nbpc > 0)
   		{
	?>

<div class="box box-success box-solid">  <div class="box-header with-border"><h3 class="box-title">Adh&eacute;rent &agrave; solde cr&eacute;diteur :</h3></div>
			<div class="box-body"><table class="table"> 
			<thead><tr><th>&nbsp;</th><th>Nom</th><th>Pr&eacute;nom</th><th>solde</th></tr>
			</thead>
			<tbody> 
        <?php
		   
				for ($i=0; $i<$nbpc; $i++)
                    {
           $arrayp = mysqli_fetch_array($result) ;
					$nomarray=getuser($arrayp['print_user']);
					$credituser=$arrayp['credit']-$arrayp['donnee'];	
			
					echo "<tr><td><a href=\"index.php?a=21&b=1&iduser=".$arrayp['print_user']."\"><button type=\"button\" class=\"btn bg-navy sm\"><i class=\"fa fa-print\"></i></button></a></a></td>
							<td>".$nomarray["nom_user"]."</td>
                            <td>".$nomarray["prenom_user"]."</td>
							<td>".$credituser."</td>
                             </tr>";
					
                    }
				?>
				
			</tbody>
			</table>
			</div>
		</div>
			
<?php } 

///adherents a sole debiteur
	$resultd=getPrintingUserswithdebt();
	$nbd=mysqli_num_rows($resultd);
	if($nbd>0){
	

?>

<!-- SOLDE DEBITEUR -->

<div class="box box-danger box-solid"> <div class="box-header with-border"><h3 class="box-title">Adh&eacute;rent &agrave; solde d&eacute;biteur :</h3></div>
			<div class="box-body"><table class="table"> 
			<thead><tr><th></th><th>Date</th><th>Nom</th><th>Pr&eacute;nom</th><th>solde</th></tr>
			</thead>
			<tbody> 
			<?php for ($i=0; $i<$nbd; $i++)
                    {
           $arrayd = mysqli_fetch_array($resultd) ;
		   $nomd=getuser($arrayd['print_user']);
		   
		   echo '<tr><td><a href="index.php?a=21&b=1&iduser='.$arrayd['print_user'].'"><button type="button" class="btn bg-navy sm"><i class="fa fa-print"></i></button></a></td>
					<td>'.$arrayd["print_date"].'</td>
					<td>'.$nomd["nom_user"].'</td>
                     <td>'.$nomd["prenom_user"].'</td>
					<td>'.$arrayd["debit"].'</td>
                             </tr>';
		   
		   }
		   
		   ?>
			</tbody></table>
			</div>
</div>

<?php } ?>

</section><!-- /col -->
 
 <!-- RESULTATS DE LA RECHERCHE -->
<section class="col-lg-8 connectedSortable">
<?php

if (strlen($term)>=2)
{
    // Recherche d'un adherent
    $result = searchUser($term);
    if (FALSE == $result OR mysqli_num_rows($result)==0)
    {
      ?>
			 <div class="box box-success"><div class="box-header"><h3 class="box-title">R&eacute;sultats de la recherche: 0
			 </h3>
		<div class="box-tools">
			<div class="input-group">
			
			<form method="post" action="index.php?a=21">
				 <div class="input-group input-group-sm"><a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div></form>
				 
				 </div><!-- /input-group -->
	</div>
		</div>
			<div class="box-body">
			<?php 
			
			echo  geterror(6);
			echo '</div></div>';
    }
    else
    {
      $nb  = mysqli_num_rows($result);
      if ($nb > 0)
      {
       
      echo '<div class="box box-success"><div class="box-header"><h3 class="box-title">R&eacute;sultats de la recherche: '.$nb;
      ?>
	  </h3>
		<div class="box-tools">
			<div class="input-group">
			
			<form method="post" action="index.php?a=21">
				 <div class="input-group input-group-sm"><a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div></form>
				 
				 </div><!-- /input-group -->
	</div>
		</div>
			<div class="box-body">
			<table class="table"> 
			<thead> 	<tr> <th></th><th></th>							
					</thead> 
			
            <?php 
	    
		for ($i=1; $i<=$nb; $i++)
		{
                        $row = mysqli_fetch_array($result) ;
			echo '<tr><td><a href="index.php?a=21&b=1&iduser='.$row['id_user'].' "><button type="button" class="btn bg-navy sm"><i class="fa fa-print"></i></button></a></td>
						<td>'.$row['prenom_user'].'&nbsp;'.$row['nom_user'].'</td>
						</tr>
						
						';
	
}
	
   
    }
    ?>
     </table>
</section>
</div><!-- /row -->

 <div class="row"><div class="col-lg-3 col-xs-7">

 <?php
}
}
else // si pas de recherche alors affichage classique
{
  		
	?>
		<div class="box box-primary"><div class="box-header"><h3 class="box-title">Les impressions du jour </h3>
	<div class="box-tools">
			<div class="input-group">
			
			<form method="post" action="index.php?a=21">
				 <div class="input-group input-group-sm"><a href="index.php?a=21&b=2&act=&caisse=0&iduser=ext" class="btn bg-yellow btn-sm">Impression sur compte externe</a>&nbsp;&nbsp;ou &nbsp;&nbsp;
				 <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
				<span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                        </div></form>
				 
				 </div><!-- /input-group -->
	</div>	<div class="box-body">
	<?php 
	// les adherents qui impriment récemment
	$result = getAllUserPrint();
	
    if (FALSE == $result)
    {
			?>
			<br>
      <div class="col-xs-6"><div class="alert alert-warning alert-dismissable"><i class="fa fa-warning"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Aucune transaction enregistr&eacute;e pour l'instant</div></div>
					<!-- cloture box-->
					</div></div>
					
   <?php }
    else  // affichage du resultat
    {
    $nb  = mysqli_num_rows($result);
    
	if ($nb > 0)
    {
       ?>
	
		
			<table class="table"> 
			<thead> 
				<tr> 
   					<th>&nbsp;</th>
					<th>Nom</th>
					<th>Pr&eacute;nom</th> 
					<th>Date</th>
					<th>debit(&euro;)</th><th></th>
				</tr>	
			</thead> 
			
                <?php
             
	       for ($i=1; $i<=$nb; $i++)
                    {
                        $row = mysqli_fetch_array($result) ;
			
			$tarif=mysqli_fetch_array(getPrixFromTarif($row['print_tarif']));
			$prix=round(($row['print_debit'] * $tarif['donnee_tarif']),2);
			$statut=$statutPrint[$row['print_statut']];
			$totalprintday=$totalprintday+$prix;
					
                        echo "<tr><td><a href=\"index.php?a=21&b=1&iduser=".$row["print_user"]."\"><button type=\"button\" class=\"btn bg-navy sm\" title=\"compte d'impression\"><i class=\"fa fa-print\"></i></button></a></td>
							<td>".$row["nom_user"]."</td>
                             <td>".$row["prenom_user"]."</td>
							 <td>".$row["print_date"]."</td>
							 <td>".$prix." </td>";
						if($row['print_statut']==0){  
							echo "<td><p class=\"text-red\">".$statut."</p></td> ";
						}else{
							echo "<td><p class=\"text-light-blue\">".$statut."</p></td> ";
						}
						echo "</tr>";
						
                    }
						echo "<tr><td></td><td></td><td></td><td></td><td>".$totalprintday." € (total jour)</td><td></td></tr>";
			?>
				
			
			</table>
			</div><!-- end of .tab_container -->
		</div><!-- end of  article -->

	<?php
	
	//fin des tableaux
    }
  }
}
?>
</div><!-- /col -->
</div><!-- /row -->
