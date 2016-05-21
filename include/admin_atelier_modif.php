<?php
/*
  
 include/admin_form_atelier.php V0.1
 Creation des ateliers dans la base
*/

$mess=$_GET["mesno"];

if ($mess !="")
{
  echo geterror($mess);
}


$pSujet  = $_POST["sujet"];
$atelier=getAllSujet();
//recuperation des tarifs categorieTarif(5)=forfait atelier
$tarifs=getTarifsbyCat(5);

//debug($pSujet);
?>

<section class="col-lg-5 connectedSortable"> 
<!-- bouton nouvel espace-->
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">Sélectionnez un atelier</h3></div>
	<form method="post" action="index.php?a=17&b=1" role="form">
	<div class="box-body">
		<div class="form-group"><label>Sujet </label>
		    <select name="sujet" class="form-control">
		    <?php
			foreach ($atelier AS $key=>$value)
			{
			    if ($pSujet == $key)
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
	<div class="box-footer">	<input type="submit" value="Modifier" class="btn btn-primary"></div></form></div>
</div>

<?php
$plevel = getAllLevel(1) ;
$category = getAllCategorie(1) ;
//debug($plevel);
if ($pSujet>0)
{
	$result=getSujetById($pSujet);
	$rowsujet=mysqli_fetch_array($result);
	$niveau=$rowsujet['niveau_atelier'];
	$categorie=$rowsujet['categorie_atelier'];
	$tarif=$rowsujet['prix_atelier'];
	//debug($tarif);

?>
<form method="post" action="index.php?a=17&b=13&sujet=<?php echo $pSujet?>" role="form">
	<div class="box box-solid bg-red">
	<div class="box-header"><h3 class="box-title">Suppression du sujet d'atelier</h3></div>
		<div class="box-body">
			 <input type="submit" name="submit_atelier" value="supprimer l'atelier" class="btn btn-default"></div>
	</div></form>
	
</section>

<section class="col-lg-7 connectedSortable"> 		
<form method="post" action="index.php?a=17&b=11&sujet=<?php echo $pSujet; ?>" role="form">
	<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Modification d'un atelier</h3></div>
		<div class="box-body">
		<div class="form-group"><label>Sujet*</label>
			<input type="text" name="label_atelier" value="<?php echo stripslashes($rowsujet['label_atelier']);?>"  class="form-control">
			<!--<input type="hidden" name="sujet" value="<?php echo $pSujet; ?>">--></div>
			
		<div class="form-group"><label>Texte de Pr&eacute;sentation*</label>
			<textarea name="content"  class="form-control"><?php echo stripslashes($rowsujet["content_atelier"]);?></textarea></div>
			
		<div class="form-group"><label>Ressource</label>
		<textarea name="ressource" value="Tutoriel fourni"  class="form-control"><?php echo stripslashes($rowsujet["ressource_atelier"]);?></textarea></div>

			
		<div class="form-group"><label>Niveau</label>
			   <select name="niveau"  class="form-control">
			 <?php
			   foreach ($plevel AS $key=>$value)
					{
						if ($niveau == $key)
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
				   </div>
			
		<div class="form-group"><label>Cat&eacute;gorie</label>
				<select name="categorie"  class="form-control">
			<?php
				foreach ($category AS $key=>$value)
				{
					if ($categorie == $key)
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
			   </div>
		   
		 
	<div class="box-footer">
				<input type="submit" name="submit_atelier" value="Modifier l'atelier" class="btn btn-primary">
				</div>
			
			</div></div></form>
			
	</section>
<?php 
}
?>
