<?php
/*
   
Formulaire de validation de présence aux ateliers
renvoie le nombre d'inscrits, le nombre de présents et l'id des présents (pour les stats personnelles)
 include/admin_atelier.php V0.1
*/


$idatelier  = $_GET["idatelier"];
$iduser     = $_GET["iduser"];
$act=$_GET["act"];

$row = getAtelier($idatelier);
 // affichage d'un atelier classique---------------------------------------

$result=getSujetById($row["id_sujet"]);
$rowsujet=mysqli_fetch_array($result);
$id_categorie=$rowsujet["categorie_atelier"];
$date_atelier=$row["date_atelier"];
$nom_atelier=$rowsujet["label_atelier"];
$anim= getUserName($row["anim_atelier"]);

 //adherent en attente
$rattente = getAtelierUser($idatelier,2) ; 
$enattente=mysqli_num_rows($rattente);  
$datea=$row['date_atelier']." ".str_replace("h", ":", $row['heure_atelier']);
	
?> 

<div class="row">
<!-- DETAIL DE L'ATELIER-->
<div class=" col-xs-6">
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Validation des pr&eacute;sences &agrave; l'Atelier</h3></div>
		<div class="box-body">
		<dl class="dl-horizontal">
		<dt>Titre</dt><dd> <?php echo $rowsujet["label_atelier"];?></dd>
               <dt>Date</dt><dd> <?php echo getDayfr($row["date_atelier"]);?> &agrave; <?php echo $row["heure_atelier"];?> </dd>
               <dt>Anim&eacute; par</dt><dd> <?php echo $anim;?></dd>
                <dt>Places restantes</dt><dd> <?php echo $nbplace ;?> (Total : <?php echo $row["nbplace_atelier"];?> places ouvertes)</dd>
		<dt>Adh&eacute;rents en attente </dt><dd><?php echo $enattente ;?></dd>
               <dt>Description</dt><dd><?php echo $rowsujet["content_atelier"];?></dd>
               </dl>
    </div>
    <div class="box-footer">
	<?php if($act==0){ ?>
	<a href="index.php?a=13&b=1&idatelier=<?php echo $idatelier; ?>"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour &agrave; l'atelier</button></a></div>
	<?php }else{ ?>
	<a href="index.php?a=18"><button class="btn btn-default"> <i class="fa fa-arrow-circle-left"></i> Retour aux archives</button></a></div>
	<?php } ?>

   </div>
</div>
<div class="col-xs-6">
	<!-- Fin DETAIL DE L'ATELIER-->
    <?php
	// liste des user inscrit a un atelier
if($act==0){
    $result = getAtelierUser($idatelier,0) ; 
    $action="index.php?a=13&b=5&idatelier=".$idatelier." ";
    $bouton="Valider les pr&eacute;sences";
    
}else if ($act==1){ //venue depuis les archives pour modification
	$result=getAtelierArchivUser($idatelier);
	   $action="index.php?a=16&b=4&act=1&idatelier=".$idatelier." ";
	   $bouton="Modifier les pr&eacute;sences";
}
    $nb = mysqli_num_rows($result) ;
	
	if ($nb>0)
    {                  
    ?>
	<form method="post" action="<?php echo $action; ?>">
<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Liste des participants &agrave; cet atelier</h3></div>
		<div class="box-body">
		<table class="table"> 
			<thead><tr> 
   			<th>Nom, prenom</th>
			<th>Pr&eacute;sence</th>
			<th></th></tr><tbody>
			
	<?php
	for ($i = 1 ; $i<= $nb; $i++)
        {
        $row2 = mysqli_fetch_array($result) ;
	if($row2["status_rel_atelier_user"]==0){
			$check="";
			}else{
			$check="checked";
			}
	
        ?>
        <tr><td><?php echo $row2["nom_user"]." ".$row2["prenom_user"] ;?></td>
	 
		<td><input type="checkbox" name="present_[]" value="<?php echo $row2['id_user']; ?>"  <?php echo $check; ?> >
			
		<td><input type="hidden" value="<?php echo $id_categorie; ?>" name="id_categorie">
				<input type="hidden" value="<?php echo $idatelier; ?>" name="idatelier">
				<input type="hidden" value="<?php echo $datea ; ?>" name="date_atelier">
				<input type="hidden" value="<?php echo $enattente; ?>" name="attente">
				<input type="hidden" value="<?php echo $row["anim_atelier"]; ?>" name="anim">
				<input type="hidden" value="<?php echo $row["nbplace_atelier"]; ?>" name="nbplace">
				<input type="hidden" value="<?php echo $nom_atelier; ?>" name="nom_atelier">
				<input type="hidden" value="<?php echo $nb; ?>" name="nbrinscrits"></td></tr>
		 
		<?php
        }
	?>
		
		</tbody></table></div>
			  <div class="box-footer"><input type="submit" name="valider_presence" value="<?php echo $bouton; ?>" class="btn bg-olive"></div>
			</div></form>
<?php
    }

?>
</div>
</div>