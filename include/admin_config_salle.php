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
 

  include/admin_materiel.php V0.1
*/

// Fichier de gestion des salles ...
$mesno= $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}
?>
<!-- DIV acc&egrave;s direct aux autres param&egrave;tres-->
 <div class="box">
		<div class="box-header">
			<h3 class="box-title">Param&eacute;trages</h3>
		</div>
		<div class="box-body">
			
			<?php 
			//debug($_GET["a"]);
			echo configBut($_GET["a"]) ;
		
			?>
			
		</div><!-- /.box-body -->
</div><!-- /.box -->

<div class="row">
<section class="col-lg-9 connectedSortable">

<!-- liste des salles existants-->
<div class="box box-solid box-warning"><div class="box-header"><h3 class="box-title">Liste des Salles</h3>
<div class="box-tools pull-right">
				<a href="index.php?a=44&b=1"><button class="btn bg-gray btn-sm"  data-toggle="tooltip" title="Ajouter"><i class="fa fa-plus"></i></button></a>
			</div></div>

	<div class="box-body no-padding"> <table class="table">
			<thead> <tr> 
			<th>Nom</th>
			<th>EPN li&eacute;</th>
			<th>Nombre de postes</th>
			<th>Commentaires</th>
			<th>&nbsp;</th>
				<th>&nbsp;</th></tr></thead><tbody>
    <?php
    $result=getAllSalle();
    if (FALSE == $result)
    {
        echo getError(31) ;
    }
    else
    {
			
			$nb=mysqli_num_rows($result);
			if ($nb == 0)
			{
				echo getError(31);
			}
			else
			{
			  	for ($i=0;$i<$nb;$i++)
			  	{
					$row = mysqli_fetch_array($result);
    				$resultespace=getEspace($row["id_espace"]);
					//debug($resultespace);
    				if (FALSE == $resultespace)
    				{
        				$salleesp="Non d&eacute;fini";
    				}
    				else
    				{
						$resultpost = getConsole($row["id_salle"]);
    					if (FALSE == $resultpost)
    					{
        					$nbpost="Non d&eacute;fini";
    					}
    					else
    					{
							$nbpost=mysqli_num_rows($resultpost);
						}
						$rowespace = mysqli_fetch_array($resultespace);
    					if ($row["id_espace"]==0)
    					{
        					$salleesp="Non d&eacute;fini";
    					}
    					else
    					{
        					$salleesp=$rowespace["nom_espace"];
						}
					}
            		?>
                <tr>
                    <td><?php echo $row["nom_salle"]; ?></td>
                    <td><?php echo $salleesp; ?></td>
                    <td><?php echo $nbpost; ?></td>
                    <td><?php echo $row["comment_salle"]; ?></td>
                    <td><a href="index.php?a=44&b=2&idsalle=<?php echo $row["id_salle"];?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
                <td><a href="index.php?a=44&b=3&act=3&idsalle=<?php echo $row["id_salle"];?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td></tr>
            	<?php
          	}
        }
    }

        ?>
</tbody></table></div>
</div>
</section>

<!-- bouton nouvelle salle-->
<section class="col-lg-3 connectedSortable"> 
<div class="small-box bg-light-blue">
                <div class="inner"><h3>&nbsp;</h3><p>Nouvelle salle</p></div>
		<div class="icon"><i class="ion ion-stop"></i></div>
<a href="index.php?a=44&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
</div>

<!-- AIDE -->
<div class="box">
     <div class="box-header"><h3 class="box-title">Conseil</h3></div>
	<div class="box-body"><p>Pour le mat&eacute;riel hors salle de consultation et salle d'atelier et accessible au public, pensez &agrave; cr&eacute;er une salle "mat&eacute;riel public" ou "salle consommables"...</p></div>
	
</div>
</section>
</div>

