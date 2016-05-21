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

// fichier de gestion des courriers destin&eacute;s aux utilisateurs

$mesno=$_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

// array des types d'info
$arrayname=array(
	1=>"Mail",
	2=>"Courrier atelier",
	3=>"Courrier session"
	);
	
$arraytype=array(
1=>"Introduction",
2=>"Sujet/object",
3=>"Corps du texte",
4=>"Signature"
);

//chargement des courriers
$result = getAllCourrier();
if ($result == FALSE)
{
  echo getError(0);
}
else
{
  $nb = mysqli_num_rows($result);
  if ($nb==0)
  {
   ?>   
		<div class="row"> <div class="col-md-6"><?php echo getError(48); ?></div>
     <div class="col-lg-3 col-xs-6"><a href="index.php?a=52&b=1"><button class="btn btn-primary">Ajouter un nouveau courrier <i class="fa fa-plus-circle"></i></button></a></div>
		 </div>
 <?php  
  }
  else
  {
     
     ?> 
  
     
    <div class="row"> 
     <div class="col-md-8">
	<div class="box box-primary"><div class="box-header"><h3 class="box-title">Liste des Courriers</h3></div>
		 <div class="box-body"><table class="table">
            <thead><tr>
               <th>Nom</th><th>Texte</th><th>Courrier rattach&eacute;</th><th>Type de contenu</th><th></th></tr></thead><tbody>
     <?php
      for ($i=1;$i<=$nb;$i++)
      {
          $row = mysqli_fetch_array($result);
         
          echo "<tr><td>".stripslashes($row["courrier_titre"])."</td>
										<td>".stripslashes($row["courrier_text"])."</td>
										<td>".$arrayname[$row["courrier_name"]]."</td>
										<td>".$arraytype[$row["courrier_type"]]."</td>
                    <td><a href=\"index.php?a=52&b=2&idcourrier=".$row["id_courrier"]."\"><button type=\"button\" class=\"btn bg-green sm\"><i class=\"fa fa-edit\"></i></button></a>
                    <a href=\"index.php?a=52&act=3&idcourrier=".$row["id_courrier"]."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\"></i></button></a></td></tr>";
      }
     
?>
</tbody></table></div>
<div class="box-footer clearfix"><a href="index.php?a=52&b=1">
         <button class="pull-right btn btn-default" name="create_courrier">Cr&eacute;er un courrier &nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></button></a></div>
         </div>

<div class="box box-success"><div class="box-header"><h3 class="box-title">Gestion de la Newsletter</h3></div>
		 <div class="box-body">	
		 <?php 
		 $rownewsletter=getNewsletterUsers();
		 $nbnews=mysqli_num_rows($rownewsletter);
		 
		 ?>
			<p>Nombre d'adh&eacute;rents abonn&eacute;s &agrave; la newsletter : <?php echo  $nbnews; ?> </p>
		 </div>
<div class="box-footer clearfix">
	<a href="courriers/csv_exportnewsletter.php"><button class="btn btn-success"><i class="fa fa-table"></i> Exporter la liste</button></a>
		 </div>
         </div>
		 
		 </div><!-- /col -->
         
         
         
<div class="col-md-4">  <div class="box box-info"><div class="box-header"><h3 class="box-title">Aide</h3></div>
		 <div class="box-body"> 
		 <p>Sur cette page vous pouvez modifier les textes qui apparaitront dans vos courriers et mails en direction des usagers. </p>
		 <p>Mettez un nom commun pour les diff&eacute;rentes parties d'un mail type par exemple "le mail de relance ou rappel". </p>
		 <p>Pour l'instant il ne sera possible de diff&eacute;rencier qu'un seul type de courrier : 1 mail, 1 courrier issu des ateliers (en pr&eacute;paration !) et 1 pour les sessions (en pr&eacute;paration!)</p>
		 <p>NB: Pour le mail de rappel (cet exemple), les donn&eacute;es de l'atelier (date-heure-lieu-animateur-sujet-d&eacute;tail) s'ins&egrave;rent entre le texte que vous mettez en "corps de texte", et la signature.
     </div>
     </div>
     </div>

</div>
<?php
  }
}
?>
