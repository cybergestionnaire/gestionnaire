<?php

// Fichier de gestion des url / favoris d'un utilisateur

// on supprime un lien
if ($_GET["action"] == "del" )
{
    delBookmark(0,AddSlashes($_GET["idurl"]));
}

// On modifie un lien
if($_GET["action"]=="mod")
{
	$formSubmit = "Modifier" ;
	
	$row 	  = getOneUrl($_GET["idurl"]) ;
	$titreUrl = $row["titre_url"] ;
	$url   	  = $row["url_url"] ;
	$theme 	  = $row["label_url_rub"] ;
}
else{
	$formSubmit = "Ajouter" ;
}
// formulaire de creation / modification d'un lien

//affichage du message d'erreur 
if ($mess!="")
{
	echo $mess;
}
?>
<div class="col-md-6">
	<div class="box box-info"><div class="box-header"><h3 class="box-title"><?php echo $formSubmit ;?> un favori </h3></div>
<div class="box-body">

<form method="post" action="index.php?a=10">
    <div class="form-group"><label>Titre :</label><input type="text" name="titre" value="<?php echo $titreUrl ;?>" class="form-control"></div>
    <div class="form-group"><label>URL :</label><input type="text" name="url" value="<?php echo $url ;?>" class="form-control" placeholder="http://"></div>
<?php 
if ($_GET["action"]!="mod") // si ajout
{
?>
	<div class="form-group"><label>Theme :</label>
       
        	<?php echo getUrlSelect();?> </div>
<?php 
}else { // sinon modification
?>
	<div class="form-group"><label>Theme :</label>
       <input type="hidden" name="idurl" value="<?php echo $_GET['idurl'];?>">
       <?php echo $theme;?><?php echo getUrlSelect();?>
       </div>
<?php 
};
?>
    </div>
   <div class="box-footer">
         	<input type="submit" value="<?php echo $formSubmit ;?>" name="submit" class="btn btn-primary"></div>
</form>
</div>
</div>

<div class="col-md-6">
<!-- Affichage des liens-->
<div class="box box-info"><div class="box-header"><h3 class="box-title">S&eacute;lection de liens utiles visibles par d&eacute;faut pour les adh&eacute;rents</h3></div>
<div class="box-body no-padding"><table class="table">
	<thead><tr><th>Theme</th><th>Titre du lien</th><th>&nbsp;</th></tr></thead><tbody>
<?php
    $result= getBookmark(0); // 0 = valeur pour les liens du cyber
    if ($result!= FALSE)
    {
        $nb = mysqli_num_rows($result) ;
        for ($i=1;$i<=$nb ;$i++)
        {  
           $row = mysqli_fetch_array($result);                                                                               
           echo "<tr><td>".$row["Flabel"]."</td><td>&nbsp;<a href=\"".$row["Furl"]."\" target=\"_blank\">".$row["Ftitre"]."</a></td>
                     <td><a href=\"index.php?a=10&action=mod&idurl=".$row["Fid"]."\"><button type=\"button\" class=\"btn bg-green sm\"><i class=\"fa fa-edit\"></i></button></a>
                     <a href=\"index.php?a=10&action=del&idurl=".$row["Fid"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr></tbody>";
        }
    }
    ?>
</table></div></div>

</div>
