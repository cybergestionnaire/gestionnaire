<?php

//chargement parametres
$idsession  = $_GET["idsession"];
$m=$_GET["m"];
$sessionsujet = getAllSujetSession() ;
//recupérer les animateurs
$allanim=getAllAnim();
//récupérer les salles
$allsalles=getAllSalleAtelier();
//recuperation des tarifs categorieTarif(5)=forfait atelier
$tarifs=getTarifsbyCat(5);
//statuts des dates
$statusarray=array(
	0=>"Atelier En cours",
	2=>"Atelier Annul&eacute; / Annuler",
	3=>"Supprimer"
);

//verifiations en cas d'envoi partiel avec fautes
if(isset($_SESSION['sauvegarde']))
{
    $_POST = $_SESSION["sauvegarde"] ;
    $nom=$_POST["nom"];
   $nbr_date =$_POST['nbre_dates'];
    $nbplace=$_POST['nbplace'];
    $salle=$_POST['salle'];
    $anim=$_POST['anim'];
    $tarif=$_POST['tarif'];
    $post_url = "index.php?a=31&m=1";
		$label_bouton = "Planifier" ;
  //debug($_POST);
  //dates à récuperer
	$date1=$_POST["date1"];
	$date2=$_POST["date2"];
	$date3=$_POST["date3"];
	$date4=$_POST["date4"];
	$date5=$_POST["date5"];
	$date6=$_POST["date6"];
	$date7=$_POST["date7"];
	$date8=$_POST["date8"];
	$date9=$_POST["date9"];
	$date10=$_POST["date10"];
	$date11=$_POST["date11"];
	$date12=$_POST["date12"];
	$date13=$_POST["date13"];
	$date14=$_POST["date14"];
	$date15=$_POST["date15"];
	$date16=$_POST["date16"];
	$date17=$_POST["date17"];
	$date18=$_POST["date18"];
	$date19=$_POST["date19"];
	$date20=$_POST["date20"];
  
    unset($_SESSION['sauvegarde']);
   
	
}else{
	if ( $m==1)
	{  // creation
		$post_url = "index.php?a=31&m=1";
		$label_bouton = "Planifier" ;
		$anim=$_SESSION["iduser"];
		$dates=date('Y-m-d');
		//recuperation du nombre de dates à planifier
		if (isset($_POST["nbre_dates_sessions"])){
		$nbr_date=$_POST["nbre_dates_sessions"];
		}else{
		$nbr_date=2;
		}
		for ($f=1; $f<=$nbr_date ; $f++){
		${'statutdate'.$f}=0;
			
		}
	
	}
	else if ($m==2)
	{ // modification
		$post_url = "index.php?a=31&m=2&idsession=".$idsession;
		$label_bouton = "Modifier" ;
			
		$row = getSession($idsession);
			$nom=$row["nom_session"];
			$nbplace=$row["nbplace_session"];
			$nbr_date = $row["nbre_dates_sessions"];
			$anim=$row["id_anim"];
			$salle=$row["id_salle"];
			$tarif=$row["id_tarif"];
		//retrouver toutes les dates actives
		$datesarray=getDatesSession($idsession);
		for ($f=1; $f<=$nbr_date ; $f++){
			$row=mysqli_fetch_array($datesarray);
			${'date'.$f}=$row["date_session"];
			${'statutdate'.$f}=$row["statut_datesession"];
			
		}
		//recuperation du nombre de dates à planifier
		if (isset($_POST["nbre_dates_sessions"])){
		$nbr_date=$_POST["nbre_dates_sessions"];
		}else{
		$nbr_date=$nbr_date;
		}
	
		
	}
	

	
}

$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

//pas de programmation possible su aucun sujet d'atelier n'a été rentré
if(FALSE==$sessionsujet){
?>
<div class="row"><div class="col-md-6">
	<div class="alert alert-warning alert-dismissable"><i class="fa fa-warning"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;Avant d'&eacute;tablir une programmation, vous devez cr&eacute;er au moins un sujet de session.</div>
	</div>
	<div class="col-md-6">
	<div class="alert alert-info alert-dismissable"><i class="fa fa-warning"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;<a href="index.php?a=34">Cr&eacute;er un nouveau sujet</a></div>
	</div>

</div>

<?php	
}else{


?>


<div class="row">
 <!-- Left col --><section class="col-lg-6 connectedSortable">
 <div class="box box-success">
<div class="box-header"><h3 class="box-title">Dates de la session</h3></div>
<div class="box-body">
<p class="text-blue">Commencez par choisir le nombre de dates de votre session, puis &agrave; saisir les dates. Elles seront automatiquement remise en ordre &agrave; la validation.
	En modification, choisir "Supprimer" pour que la date soit retir&eacute;e de la liste, ne modifiez pas le nombre, il sera automatiquement rafraichit. </p>
<p class="text-blue">NB : L'annulation n'est pas une suppression, l'atelier restera dans les statistiques.</p>
<form method="post" action="#">
 <div class="form-group has-error">
 <div class="input-group"><label>Nombre de dates *</label>
  <select name="nbre_dates_sessions" class="form-control input-sm pull-right" style="width: 150px;">
  <?php
					for ($i=2 ; $i<=20 ; $i++)
					{
						if ($i == $nbr_date)
						{
							echo "<option value=\"".$i."\" selected>".$i."</option>";
						}
						else
						{
							echo "<option value=\"".$i."\">".$i."</option>";
						}
					}
					?>
	</select>
	<div class="input-group-btn"><button  class="btn btn-default"><i class="fa fa-repeat"></i></button></div>
	</div></div>
	
	</form>
	<div class="form-group"><label>Dates &agrave; planifier *  </label><!--<p class="help-block">Cochez pour supprimer une date</p>-->
	<form method="post" action="<?php echo $post_url; ?>"><input type="hidden" name="nbre_dates" value="<?php echo $nbr_date; ?>" ></div>

<div class="form-group"><label>1.</label><?php if($statutdate1==1){ echo ' <span class="text-muted">'.$date1.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date1" class="hidden"  id="dt1" value="'.$date1.'">'; }else { ?><input name="date1" class='input'  id="dt1" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date1; ?>" >
	<select name="statutdate1" >
    <?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate1 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select><?php } ?></div>
	
<?php if ($nbr_date>=2){ ?><div class="form-group"><label>2.</label><?php if($statutdate2==1){ echo ' <span class="text-muted">'.$date2.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date2" class="hidden"  id="dt2" value="'.$date2.'">'; }else { ?><input name="date2" class='input'  id="dt2" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date2; ?>" >
		<select name="statutdate2" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate2 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select><?php } ?></div><?php } else { echo '' ; } ?>
<?php if ($nbr_date>=3){ ?><div class="form-group"><label>3.</label><?php if($statutdate3==1){ echo ' <span class="text-muted">'.$date3.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date3" class="hidden"  id="dt3" value="'.$date3.'">'; }else { ?><input  name="date3"  class='input' id="dt3" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date3; ?>">
<select name="statutdate3" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate3 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>

<?php if ($nbr_date>=4){ ?><div class="form-group"><label>4.</label><?php if($statutdate4==1){ echo ' <span class="text-muted">'.$date4.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date4" class="hidden"  id="dt4" value="'.$date4.'">'; }else { ?><input name="date4"  class='input' id="dt4" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date4; ?>">
<select name="statutdate4" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate4 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>

<?php if ($nbr_date>=5){ ?><div class="form-group"><label>5.</label><?php if($statutdate5==1){ echo ' <span class="text-muted">'.$date5.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date5" class="hidden"  id="dt5" value="'.$date5.'">'; }else { ?><input  name="date5"  class='input' id="dt5" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date5; ?>">
	<select name="statutdate5" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate5 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>

<?php if ($nbr_date>=6){ ?><div class="form-group"><label>6.</label><?php if($statutdate6==1){ echo ' <span class="text-muted">'.$date6.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date6" class="hidden"  id="dt6" value="'.$date6.'">'; }else { ?><input  name="date6"  class='input' id="dt6" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date6; ?>">
<select name="statutdate6" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate6 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>

<?php if ($nbr_date>=7){ ?><div class="form-group"><label>7.</label><?php if($statutdate7==1){ echo ' <span class="text-muted">'.$date7.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date7" class="hidden"  id="dt7" value="'.$date7.'">'; }else { ?><input  name="date7"  class='input' id="dt7" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date7; ?>">
<select name="statutdate7" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate7 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=8){ ?><div class="form-group"><label>8.</label><?php if($statutdate8==1){ echo ' <span class="text-muted">'.$date8.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date8" class="hidden"  id="dt8" value="'.$date8.'">'; }else { ?><input  name="date8"  class='input' id="dt8" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date8; ?>">
	<select name="statutdate8" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate8 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>	
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=9){ ?><div class="form-group"><label>9.</label><?php if($statutdate9==1){ echo ' <span class="text-muted">'.$date9.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date9" class="hidden"  id="dt9" value="'.$date9.'">'; }else { ?><input  name="date9"  class='input' id="dt9" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date9; ?>">
	<select name="statutdate9" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate9 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=10){ ?><div class="form-group"><label>10.</label><?php if($statutdate10==1){ echo ' <span class="text-muted">'.$date10.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date10" class="hidden"  id="dt10" value="'.$date10.'">'; }else { ?><input name="date10"   class='input' id="dt10" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date10; ?>">
	<select name="statutdate10" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate10 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=11){ ?><div class="form-group"><label>11.</label><?php if($statutdate11==1){ echo ' <span class="text-muted">'.$date11.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date11" class="hidden"  id="dt11" value="'.$date11.'">'; }else { ?><input name="date11"   class='input' id="dt11" placeholder="Cliquez pour prendre une date" style="width: 230px;"  value="<?php echo $date11; ?>">
	<select name="statutdate11" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate11 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=12){ ?><div class="form-group"><label>12.</label><?php if($statutdate12==1){ echo ' <span class="text-muted">'.$date12.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date12" class="hidden"  id="dt12" value="'.$date12.'">'; }else { ?><input name="date12"   class='input' id="dt12" placeholder="Cliquez pour prendre une date" style="width: 230px;"  value="<?php echo $date12; ?>">
	<select name="statutdate12" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate12 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=13){ ?><div class="form-group"><label>13.</label><?php if($statutdate13==1){ echo ' <span class="text-muted">'.$date13.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date13" class="hidden"  id="dt13" value="'.$date13.'">'; }else { ?><input  name="date13"   class='input' id="dt13" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date13; ?>">
	<select name="statutdate13" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate13 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=14){ ?><div class="form-group"><label>14.</label><?php if($statutdate14==1){ echo ' <span class="text-muted">'.$date14.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date14" class="hidden"  id="dt14" value="'.$date14.'">'; }else { ?><input name="date14"   class='input' id="dt14" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date14; ?>">
	<select name="statutdate14" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate14 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=15){ ?><div class="form-group"><label>15.</label><?php if($statutdate15==1){ echo ' <span class="text-muted">'.$date15.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date15" class="hidden"  id="dt15" value="'.$date15.'">'; }else { ?><input  name="date15"  class='input' id="dt15" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date15; ?>">
	<select name="statutdate15" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate15 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=16){ ?><div class="form-group"><label>16.</label><?php if($statutdate16==1){ echo ' <span class="text-muted">'.$date16.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date16" class="hidden"  id="dt16" value="'.$date16.'">'; }else { ?><input  name="date16"  class='input' id="dt16" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date16; ?>">
	<select name="statutdate16" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate16 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=17){ ?><div class="form-group"><label>17.</label><?php if($statutdate17==1){ echo ' <span class="text-muted">'.$date17.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date17" class="hidden"  id="dt17" value="'.$date17.'">'; }else { ?><input  name="date17"   class='input' id="dt17" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date17; ?>">
	<select name="statutdate17" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate17 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=18){ ?><div class="form-group"><label>18.</label><?php if($statutdate18==1){ echo ' <span class="text-muted">'.$date18.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date18" class="hidden"  id="dt18" value="'.$date18.'">'; }else { ?><input  name="date18"   class='input' id="dt18" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date18; ?>">
	<select name="statutdate18" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate18 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?></div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=19){ ?><div class="form-group"><label>19.</label><?php if($statutdate19==1){ echo ' <span class="text-muted">'.$date19.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date19" class="hidden"  id="dt19" value="'.$date19.'">'; }else { ?><input  name="date19"   class='input' id="dt19" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date19; ?>">
	<select name="statutdate19" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate19 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>
	
<?php if ($nbr_date>=20){ ?><div class="form-group"><label>20.</label><?php if($statutdate20==1){ echo ' <span class="text-muted">'.$date20.'  &nbsp;&nbsp;&nbsp;Atelier clotur&eacute; </span><input name="date20" class="hidden"  id="dt20" value="'.$date20.'">'; }else { ?><input name="date10"   class='input' id="dt20" placeholder="Cliquez pour prendre une date" style="width: 230px;" value="<?php echo $date20; ?>">
	<select name="statutdate20" >
		<?php foreach ($statusarray AS $key=>$value){  
		if ($statutdate20 == $key){
                echo "<option value=\"".$key."\" selected>".$value."</option>";}
            else {
                echo "<option value=\"".$key."\">".$value."</option>"; } } ?></select>
	<?php } ?> </div><?php } else { echo '' ; } ?>


<table>
<?php 

//envoyer aussi  son id
$datesarray2=getDatesSession($idsession);
for($y=1;$y<=$nbr_date;$y++){
	$row2=mysqli_fetch_array($datesarray2);
	
echo "<input type=\"hidden\" name=\"iddate".$y."\" value=".$row2['id_datesession'].">	";

}
//<input type=\"hidden\" name=\"statutdate".$y."\" value=".$row2['statut_datesession']." >
?>
</table>
</div></div>

</section>


<section class="col-lg-6 connectedSortable">

<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Programmation d'une session</h3></div>
	<div class="box-body">
	
	<div class="form-group has-error"><label>Titre *</label>
		<select name="nom" class="form-control" >
    <?php
        foreach ($sessionsujet AS $key=>$value)
        {
            if ($nom == $key)
            {
                echo "<option value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option value=\"".$key."\">".$value."</option>";
            }
        }
		
    ?></select></div>
	
	<div class="form-group has-error"><label>Nombre des membres*</label>
        <input type="text" name="nbplace" value="<?php echo $nbplace;?>" class="form-control"></div>
   
 <div class="form-group"><label>Salle</label>
        	<select name="salle" class="form-control">
       	<?php
        foreach ($allsalles AS $key=>$value)
        {
            if ($salle == $key)
            {
                echo "<option value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option value=\"".$key."\">".$value."</option>";
            }
        }
		
    ?></select></div>
		
	<div class="form-group"><label>Anim&eacute; par </label>
		 <select name="anim" class="form-control">
	    <?php
		foreach ($allanim AS $key=>$value)
		{
		    if ($anim == $key)
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
	
    <div class="form-group"><label>Tarif</label>
	 <!-- tools box -->
	    <div class="pull-right box-tools">
		<button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Si un atelier fait partie d'une tarification sp&eacute;ciale, choisissez-l&agrave; ici, sinon laissez le 'sans tarif' par d&eacute;faut, le d&eacute;compte des ateliers se fera en fonction de ce qui a &eacute;t&eacute; pay&eacute; par l'adh&eacute;rent."><i class="fa fa-info-circle"></i></button>
	    </div><!-- /. tools -->
	
  		<select name="tarif" class="form-control" >
		<?php
			foreach ($tarifs AS $key=>$value)
			{
				if ($tarif == $key)
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
    
	</div>
	
     <div class="box-footer"><input type="submit" name="submit_session" value="<?php echo $label_bouton; ?>" class="btn btn-primary"></div>
	
	</div>
	
</form></div>
<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/session.js'></script>

<?php } ?>