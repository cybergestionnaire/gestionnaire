<?php
/*
  
 include/admin_form_atelier.php V0.1
 Creation des ateliers dans la base
*/

$b= $_GET["b"];
$pSujet  = $_POST["sujet"];

$atelier = getAllSujet();
//debug($atelier);
///condition pour affichage ateliers
if ($atelier!=FALSE){

?>

<article class="module width_half">
	<header><h3>S&eacute;lectionnez un atelier</h3></header>
	<form method="post" action="index.php?a=35&b=1&sujet=<?php echo $sujet ?>">
	<div class="module_content">
		  <fieldset><label>Sujet </label>
		 
    <select name="sujet">
    <?php 
	debug($atelier);
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
    </select></fieldset>
	
	<input type="submit" value="Modifier" class="alt_btn">
    </div></form>
</article>

<?php
if (strlen($pSujet)>=0)
{
	$result=getSujetById($pSujet);
	$rowsujet=mysqli_fetch_array($result);
?>
<form method="post" action="index.php?a=35&b=1">
<article class="module width_half">
	<header><h3>Modification d'un atelier</h3></header>
	 <div class="module_content">
    <fieldset><label>*Sujet</label>
        <input type="text" name="label_atelier" value="<?php echo $rowsujet["label_atelier"];?>" class="email"></fieldset>
	
	<fieldset><label>*Texte de Pr&eacute;sentation</label>
        <textarea name="content"><?php echo $rowsujet["content_atelier"];?></textarea></fieldset>
    
	<fieldset><label>Ressource</label>
        <textarea name="ressource" value="Tutoriel fourni"><?php echo $rowsujet["ressource_atelier"];?></textarea></fieldset>

	
	<fieldset><label>Niveau</label>
       <?php
            echo "<select name=\"niveau\">" ;
            $result = getAllLevel() ;
            $nb = mysqli_num_rows($result) ;
            for ($i = 1 ; $i<=$nb ; $i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value=\"".$row["id_level"]."\">".$row["nom_level"]."</option>" ;
            }
            echo "</select>";
        ?></fieldset>
    
	<fieldset><label>Cat&eacute;gorie</label>
        <?php
        echo "<select name=\"categorie\">" ;
            $result = getAllCategorie() ;
            $nb = mysqli_num_rows($result) ;
            for ($i = 1 ; $i<=$nb ; $i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value=\"".$row["id_atelier_categorie"]."\">".$row["label_categorie"]."</option>" ;
            }
            echo "</select>";
        ?>
       </fieldset>
   
   <fieldset><label>Prix</label>
        <input type="text" maxlength="3" style="width: 90px;" name="prix" value="<?php echo $rowsujet["prix_atelier"];?>">Eur. (0 = gratuit)</fieldset>
    
	<fieldset><label>Lieu</label>
        <input type="text" name="lieu" value="<?php echo $rowsujet["lieu_atelier"];?>"></fieldset>
   
	   
   </div>
    <footer><div class="submit_link">
        <input type="submit" name="submit_atelier" value="Modifier l'atelier" class="alt_btn"></div></footer>
	
	</div></article>
	
</form>

<?php 
}

}
else{
	echo "<h4 class=\"alert_warning\">Aucun atelier &agrave; modifier</h4>";
}
?>