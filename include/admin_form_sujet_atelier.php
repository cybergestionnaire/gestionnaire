<?php
/*
  
 include/admin_form_atelier.php V0.1
 Creation des ateliers dans la base



*/
//Affichage -----
$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
  
}

$public="Tous Publics";
$ressource="Tutoriel fourni";


?>
 <!-- right column --><div class="col-md-8">
 <div class="box box-success"><div class="box-header"><h3 class="box-title">Formulaire d'enregistrement du sujet</h3></div>
 
<form method="post" action="index.php?a=15&b=12" role="form">
<div class="box-body">
	
     <div class="form-group"><label>Sujet*</label>
        <input type="text" name="label_atelier" value="<?php echo stripslashes($label_atelier);?>" class="form-control"></div>
	
	<div class="form-group"><label>Texte de Pr&eacute;sentation*</label>
        <textarea name="content" class="form-control" ><?php echo stripslashes($content);?></textarea></div>
    
	<div class="form-group"><label>Ressource</label>
        <textarea name="ressource" value="Tutoriel fourni" class="form-control" ><?php echo stripslashes($ressource);?></textarea></div>
 
	<div class="form-group"><label>Niveau</label>
       <?php
            echo "<select name=\"niveau\" class=\"form-control\" >" ;
            $result = getAllLevel(0) ;
            $nb = mysqli_num_rows($result) ;
            for ($i = 1 ; $i<=$nb ; $i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value=\"".$row["id_level"]."\">".$row["nom_level"]."</option>" ;
            }
            echo "</select>";
        ?></div>
    
	<div class="form-group"><label>Cat&eacute;gorie</label>
        <?php
        echo "<select name=\"categorie\" class=\"form-control\" >" ;
            $result = getAllCategorie(0) ;
            $nb = mysqli_num_rows($result) ;
            for ($i = 1 ; $i<=$nb ; $i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value=\"".$row["id_atelier_categorie"]."\">".$row["label_categorie"]."</option>" ;
            }
            echo "</select>";
        ?>
       </div>
   
  
     
   </div><!-- box content -->
    <div class="box-footer">
        <input type="submit" name="submit_atelier" value="Cr&eacute;er l'atelier" class="btn btn-primary"></div></footer>
	
</div><!-- box -->
	
</form>
</div>
