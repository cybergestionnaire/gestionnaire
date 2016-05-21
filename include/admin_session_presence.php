<?php
/*
   
Formulaire de validation de présence aux ateliers
renvoie le nombre d'inscrits, le nombre de présents et l'id des présents (pour les stats personnelles)
 include/admin_atelier.php V0.1
*/

$idsession  = $_GET["idsession"];
$act=$_GET["act"];
$present = $_POST["present"];
$iddate=$_GET["dateid"];
$numerodate=$_GET["numerod"];
// recupération des données de la session
$row = getSession($idsession);
//TITRE DE LA SESSION + DETAIL
$nomsession=getNomSession($row["nom_session"]);
//Nombre de places
$nombre_inscrit=countPlaceSession($idsession,0);
//date de la session
$date_session= date('Y-m-d',strtotime(getDatebyNumero($iddate)));
//animateur
$anim=$row["id_anim"];

 //adherent en attente
$rattente = getSessionUser($idsession,2) ; 
$enattente=mysqli_num_rows($rattente);  

// modification des présences depuis les archives
if($act==1){
$statutdatesession=1;
$action="index.php?a=32&act=1";
}else{
$statutdatesession=0;
$action="index.php?a=32&act=0";
}
	
	?> 
	<!-- DETAIL DE La session-->
<div class="row">
<div class="col-lg-5">
<div class="box box-success"><div class="box-header"><h3 class="box-title"><?php echo $nomsession["session_titre"];?></h3></div>
	<div class="box-body">
		<dl class="dl-horizontal">
			<dt>Date</dt><dd> <?php echo getDayfr($date_session);?> </dd>
			<dt>Nombre d'inscrits</dt><dd><?php echo $nombre_inscrit ;?> (sur <?php echo $row["nbplace_session"];?> places)</dd>
			<dt>Description</dt><dd> <?php echo $nomsession["session_detail"];?></dd></dl>	  
	</div>
	
	   <div class="box-footer">
	<a href="index.php?a=30&b=1&idsession=<?php echo $idsession ; ?>"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour aux inscriptions</button></a></div>
	</div></div>
	<!-- Fin DETAIL DE L'ATELIER-->

<div class="col-lg-7">
    <?php
	
	// liste des user inscrit a un atelier
	if($act==0){
    $result2 = getSessionUser($idsession,$statutdatesession); 
	}elseif ($act==1){
	$result2=getSessionValidpresences($idsession,$iddate);
	}
	$nb = mysqli_num_rows($result2) ;
	
    if ($nb>0)
    {       

       ?>

<form method="post" action="<?php echo $action; ?>">
<div class="box box-success"><div class="box-header"><h3 class="box-title">Liste des participants &agrave; cet session</h3></div>
	<div class="box-body">
		<table class="table"> 
			<thead><tr> 
			<th>Nom, prenom</th>
				<th>Pr&eacute;sence</th>
				<th><input type="hidden" class="form-control" value="<?php echo $nomsession['session_categorie']; ?>" name="id_categorie" >
				<input type="hidden" value="<?php echo $idsession; ?>" name="idsession">
				<input type="hidden" value="<?php echo $iddate; ?>" name="dateid">
				<input type="hidden" value="<?php echo $anim; ?>" name="anim">
				<input type="hidden" value="<?php echo $nb; ?>" name="inscrits">
				<input type="hidden" value="<?php echo getDatebyNumero($iddate); ?>" name="date_session">
				<input type="hidden" value="<?php echo $row["nbre_dates_sessions"]; ?>" name="nbre_dates">
				<input type="hidden" value="<?php echo $numerodate; ?>" name="num">
				<input type="hidden" value="<?php echo $enattente; ?>" name="attente">
				<input type="hidden" value="<?php echo $nomsession["session_categorie"]; ?>" name="categorie">
				<input type="hidden" value="<?php echo $row["nbplace_session"]; ?>" name="nbplace">
				<input type="hidden" value="<?php echo $nomsession["session_titre"]; ?>" name="nom_session"></th></tr></thead><tbody>
				
		 <?php
		 
		 for ($i = 1 ; $i<= $nb; $i++)
         {
			$array= mysqli_fetch_array($result2) ;
			if($array["status_rel_session"]==0){
			$check="";
			}else{
			$check="checked";
			}
             ?>
             <tr><td><?php echo $array["nom_user"]." ".$array["prenom_user"] ;?></td>
				 <td><input type="checkbox" name="present_[]" value="<?php echo $array["id_user"]; ?>" <?php echo $check; ?>>
					
				 </td>
				 <td></td></tr>
				 
             <?php
         } ?>
		 </tbody></table></div>
				<div class="box-footer"><input type="submit" class="btn bg-olive"></div>
				</div>
        </form>
		 <?php
    }
 
?>

</div>
</div>