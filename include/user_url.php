<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
2012 Florence DAUVERGNE, changement de la feuille de style !
  include/user_url.php V0.1
*/

// Fichier de gestion des url / favoris d'un utilisateur

if ($_POST["submit"]!="")
{                       
   $titre = $_POST["titre"];
   $url   = $_POST["url"];
   if (!$titre || !$url)
   {
       echo getError(13);
   }
   else
   {
       addBookmark($_SESSION["iduser"],$titre,$url,$_POST['rubSel']);
   }
}

if ($_GET["act"] == "del" )
{
    delBookmark($_SESSION["iduser"],AddSlashes($_GET["idurl"]));
}

?>

  <div class="row"><!-- left column --><div class="col-md-6">
  
 <div class="box box-info"> <div class="box-header"><h3 class="box-title">Aide</h3></div>
		 <div class="box-body">
<p>Cette page est &agrave; votre disposition pour y stocker vos favoris personnels</p>
</div></div>

<form method="post" action="index.php?m=5">
 <div class="box box-success">
                                <div class="box-header"><h3 class="box-title">Ajouter un favori</h3></div>
	<div class="box-body">
  <div class="form-group"><label>Titre</label><input type="text" name="titre" class="form-control"></div>
     <div class="form-group"><label>URL</label><input type="text" name="url" class="form-control" placeholder="http://www"></div>
     <div class="form-group"><label>Theme</label><?php echo getUrlSelect();?></div>
 </div>
 
 <div class="box-footer"><input type="submit" value="ajouter" name="submit" class="btn btn-primary"></div>
</div> 
</form>




<?php
if (FALSE != checkBookmark($_SESSION["iduser"]))
{
?>
<div class="box box-success"> <div class="box-header"><h3 class="box-title">Mes liens favoris</h3></div>
<div class="box-body">
<table class="table"> <thead><tr> <th>Theme</th><th>Titre du lien</th><th>Sup.</th></tr></thead><tbody>
<?php
    $result= getBookmark($_SESSION["iduser"]);
    if ($result!= FALSE)
    {
        $nb = mysqli_num_rows($result) ;
        for ($i=1;$i<=$nb ;$i++)
        {  
           $row = mysqli_fetch_array($result);                                                                               
           echo "<tr>
           			<td>".$row["Flabel"]."</td>
           			<td>&nbsp;<a href=\"".$row["Furl"]."\" target=\"_blank\">".$row["Ftitre"]."</a></td>
                    <td width=\"15\"><a href=\"index.php?m=5&act=del&idurl=".$row["Fid"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr>";
        }
    }
    ?>
</tbody></table></div></div>

<?php
}
?>
</div><div class="col-md-6">

<div class="box box-success"> <div class="box-header"><h3 class="box-title">Selection de liens utiles</h3></header>
<div class="box-body">
<table class="table"> 
			<thead><tr> <th>Theme</th><th>Titre du lien</th></tr></thead><tbody>
<?php
    $result= getBookmark(0); // 0 = valeur pour les liens du cyber
    if ($result!= FALSE)
    {
        $nb = mysqli_num_rows($result) ;
        for ($i=1;$i<=$nb ;$i++)
        {  
           $row = mysqli_fetch_array($result);                                                                               
           echo "<tr><td>".$row["Flabel"]."</td><td>&nbsp;<a href=\"".$row["Furl"]."\" target=\"_blank\">".$row["Ftitre"]."</a></td></tr>";
        }
    }
    ?>
</tbody></table></div></div>

</div><!-- /col -->
</div><!-- /row -->
