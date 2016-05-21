<?php
/*
2013 Modification
Fichier servant à modifier/créer la programmation d'un atelier

*/
if ($mess !="")
{
  echo $mess;
}

$id  = $_GET["idatelier"];
//recupérer les sujets d'atelier
$atelier=getAllSujet();
//recupérer les animateurs
$allanim=getAllAnim();
//récupérer les salles
$allsalles=getAllSalleAtelier();



if (FALSE == isset($id))
{  // creation
  $post_url = "index.php?a=12&m=1";
  $label_bouton = "Programmer" ;
		$public="Tous public";
		$anim=$_SESSION["iduser"];
		//statut de l'atelier
$stateAtelier = array(
	0=> "En cours",
	1=> "En programmation"
	//2=> "Cloturé",
	//3=> "Annulé"
	);
		
}
else
{ // modification
        $post_url = "index.php?a=14&m=2&idatelier=".$id;
        $label_bouton = "Modifier l'atelier" ;
		
        $row = getAtelier($id);
		
        //Informations matos
        $date = $row["date_atelier"];
        $heure = $row["heure_atelier"];
        $nbplace = $row["nbplace_atelier"];
		$duree= $row["duree_atelier"];
		$public =$row["public_atelier"];
		$anim =$row["anim_atelier"];
		$sujet =$row["id_sujet"];
		$statut=$row["statut_atelier"];
		$salle=$row["salle_atelier"];
		$tarif=$row["tarif_atelier"];
	//statut de l'atelier
$stateAtelier = array(
	0=> "En cours",
	1=> "En programmation",
	2=> "Clotur&eacute;",
	3=> "Annul&eacute;"
	);
}

$dureesa=array(
	30=>"30 min",
	60=>"1h",
	90=>"1h30",
	120=>"2h",
	150=>"2h30",
	180=>"3h");

//recuperation des tarifs categorieTarif(5)=forfait atelier
$tarifs=getTarifsbyCat(5);

//pas de programmation possible su aucun sujet d'atelier n'a été rentré
if(FALSE==$atelier){
?>
<div class="row"><div class="col-md-6">
	<div class="alert alert-warning alert-dismissable"><i class="fa fa-warning"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;Avant d'&eacute;tablir une programmation, vous devez cr&eacute;er au moins un sujet d'atelier.</div>
	</div>
	<div class="col-md-6">
	<div class="alert alert-info alert-dismissable"><i class="fa fa-warning"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;<a href="index.php?a=15">Cr&eacute;er un nouveau sujet</a></div>
	</div>

</div>

<?php	
}else{

?>

<div class="row">
 <!-- Left col -->  <div class="col-md-6">
<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Planification d'un atelier</h3></div>
	<form method="post" action="<?php echo $post_url; ?>" role="form">
	<div class="box-body">
	
	<div class="form-group"><label><span class="text-red">Sujet*</span></label>
   	 <select name="sujet" class="form-control">
	    <?php
		foreach ($atelier AS $key=>$value)
		{
		    if ($sujet == $key)
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
	
         
     
   
	<div class="row">
		<div class="col-lg-6"><label><span class="text-red">Places disponibles*</span></label>
		 <div class="input-group">
			<input type="text" name="nbplace" value="<?php echo $nbplace;?>" class="form-control">
		</div></div>
	
		<div class="col-lg-6"><label>Dur&eacute;e</label>
		<div class="input-group">
		<select name="duree" class="form-control">
			<?php 
			foreach ($dureesa AS $key=>$value)
		{
		    if ($duree == $key)
		    {
		        echo "<option value=\"".$key."\" selected>".$value."</option>";
		    }
		    else
		    {
		        echo "<option value=\"".$key."\">".$value."</option>";
		    }
		}
		?>
    		</select>
		</div></div>
	</div>
	  <br>
	<div class="row">
		<div class="col-lg-6"><label><span class="text-red">Date*</span></label>
                      <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-calendar"></i></span>
			<input name="date" id="dt0" placeholder="Prenez une date"  value="<?php echo $date; ?>" class="form-control">
		</div></div>
		
		
		<div class="col-lg-6"><label><span class="text-red">Heure*</span></label>
                      <div class="input-group"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
			<input  id="dt1" name="heure" value="<?php echo $heure;?>" class="form-control" placeholder="10h">
		</div><!-- /.input group -->
		</div>
	</div>

	</div><!-- /box-body -->
</div><!-- /box -->	
</div><!-- /col -->

  <div class="col-md-6">	
  <div class="box box-success"><div class="box-header"><h3 class="box-title"></h3></div>
	<div class="box-body">
	
   
	<div class="form-group"><label>Public concern&eacute;</label>
        <input type="text" name="public" value="<?php echo $public;?>" class="form-control"></div>
        
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
    
    
<div class="form-group"><label>Statut </label>
    <select name="statut" class="form-control">
    <?php
        foreach ($stateAtelier AS $key=>$value)
        {
            if ($statut == $key)
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

    <div class="box-footer"> <input type="submit" name="submit_atelier" value="<?php echo $label_bouton; ?>"  class="btn btn-primary"></div>
</form>
</div><!-- /box -->	
</div><!-- /col -->
</div><!-- /row -->
	

<script src='rome-master/dist/rome.js'></script>
<script src='rome-master/example/atelier.js'></script>


<?php } ?>