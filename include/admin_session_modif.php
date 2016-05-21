<?php
/*
  
 include/admin_form_atelier.php V0.1
 Creation des ateliers dans la base
*/

$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}


$pSujet  = $_POST["sujetsession"];

$session=getAllSujetSession();
$s =  $_GET["s"];

?>

<div class="row">

<section class="col-lg-5 connectedSortable"> 
<!-- bouton nouvel espace-->
<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title">S&eacute;lectionnez une session</h3></div>
	<form method="post" action="index.php?a=35&s=0&sujetsession=<?php echo $pSujet ?>">
	<div class="box-body">
		<div class="form-group"><label>Sujet </label>
    <select name="sujetsession" class="form-control" id="sujetsession">
    <?php
        foreach ($session AS $key=>$value)
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
	<div class="box-footer">
	<input type="submit" value="Modifier" class="btn btn-primary">
    </div>
    </form></div>
</div>
</section>
<?php
$plevel = getAllLevel(1) ;
$category = getAllCategorie(1) ;
//debug($plevel);
if ($pSujet>0)
{
	$result=getSujetSessionById($pSujet);
	$rowsujet=mysqli_fetch_array($result);
	$niveau=$rowsujet['session_niveau'];
	$categorie=$rowsujet['session_categorie'];

?>


<section class="col-lg-7 connectedSortable"> 		
<form method="post" action="index.php?a=35&s=3&sujetsession=<?php echo $pSujet; ?>">
	<div class="box box-success">
	<div class="box-header"><h3 class="box-title">Modification d'une session</h3></div>
		<div class="box-body">
		<div class="form-group"><label>Sujet*</label>
				<input type="text" name="label_session" value="<?php echo stripslashes($rowsujet['session_titre']);?>" class="form-control"></div>
			
		<div class="form-group"><label>Texte de Pr&eacute;sentation*</label>
				<textarea name="content" class="form-control"><?php echo stripslashes($rowsujet["session_detail"]);?></textarea></div>
			
		<div class="form-group"><label>Niveau</label>
			   <select name="niveau" class="form-control">
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
			</select> </div>
			
		<div class="form-group"><label>Cat&eacute;gorie</label>
				<select name="categorie" class="form-control">
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
			<a href="index.php?a=35&s=3&sujetsession=<?php echo $pSujet; ?>"><input type="submit" name="submit_session" value="Modifier" class="btn btn-primary"></a></form>
			<form method="post" action="index.php?a=35&s=del&sujetsession=<?php echo $pSujet; ?>"><input type="submit" name="submit_session" value="supprimer" class="btn btn-warning pull right"></form>
		</div>
	</div></div><!--dox body -->
			
	</section>

<?php 
}
?>
	</div>