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
 

 include/admin_breve.php V0.1
*/

// fichier de gestion des breves

$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

?>


<?php 
//chargement des breves
$result = getAllBreve(0);
if ($result == FALSE)
{
  echo getError(0);
}
else
{
  $nb = mysqli_num_rows($result);
  if ($nb==0)
  {
      echo '<div class="row">  <div class="col-md-6">';
      echo getError(10).'</div>';  
      //ajout creation de breve
      echo '
		<div class="col-md-4">
		<div class="small-box bg-light-blue">
                <div class="inner"><h3>&nbsp;</h3><p>Nouvelle Br&egrave;ve</p></div>
		<div class="icon"><i class="ion ion-clipboard"></i></div>
	<a href="index.php?a=4&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
</div></div>
</div>';
   
  }
  else
  {
     
     ?> 
  
     
    <div class="row"> 
     <div class="col-md-6">
	<div class="box box-info"><div class="box-header"><h3 class="box-title">Liste des br&egrave;ves</h3></div>
		 <div class="box-body no-padding"><table class="table">
            <thead><tr>
               <th>Titre</th>
               <th>Date de publication</th><th></th></tr></thead><tbody>
     <?php
      for ($i=1;$i<=$nb;$i++)
      {
          $row = mysqli_fetch_array($result);
          
          echo "<tr><td>".$row["titre_news"]."</td>
				<td>".$row["date_publish"]."</td>
                    <td><a href=\"index.php?a=4&b=2&idbreve=".$row["id_news"]."\"><button type=\"button\" class=\"btn bg-green sm\"><i class=\"fa fa-edit\"></i></button></a>
                    <a href=\"index.php?a=4&b=3&act=3&idbreve=".$row["id_news"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr>";
      }
      echo "</tbody></table></div>";


?>
<div class="box-footer clearfix"><a href="index.php?a=4&b=1">
         <button class="pull-right btn btn-default" name="create_breve">Cr&eacute;er une br&egrave;ve &nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></button></a></div>
         </div></div>

</div>
<?php
  }
}
?>
