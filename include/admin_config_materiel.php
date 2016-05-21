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
// chargement des valeurs pour l'epn par d�faut
$epn=$_SESSION['idepn'];
//si changment d'epn
$epn_r=$_GET['epnr'];
if (isset($epn_r)){
	$epn=$epn_r;
}

$espaces=getAllEPN();
// Fichier de gestion du materiel  ...
$mesno= $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}
?>
<!-- DIV accès direct aux autres paramètres-->
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
<!-- section 1 - 3 -->
 <div class="row">
  <section class="col-lg-8 connectedSortable"> 
<div class="box box-solid box-warning"> <div class="box-header"><h3 class="box-title">Mat&eacute;riel accessible au public</h3></div>
 <div class="box-body no-padding"> <table class="table">
    <thead><tr>
        <th>Nom</th><th>OS</th><th>Salle</th><th>Commentaires</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>
<?php
//$result=getAllMateriel();
$result=getMaterielFromEpn($epn);
if (FALSE == $result)
{
	echo getError(0) ;
}
else
{
	$nb=mysqli_num_rows($result);
	if ($nb == 0)
	{
		echo getError(9);
	}
	else
	{
		$usage=1; // usage par defaut
		for ($i=0;$i<$nb;$i++)
		{
			$row = mysqli_fetch_array($result);
    		$salle=getNomsalleforAnim($row["id_salle"]);
    		if ($row["usage_computer"]==$usage)
            {
				?>
                <tr>
                <td><?php echo $row["nom_computer"]; ?></td>
                <td><?php echo $row["os_computer"]; ?></td>
                <td><?php echo $salle; ?></td>
                <td><?php echo stripslashes($row["comment_computer"]); ?></td>
                <td><a href="index.php?a=2&b=2&idmat=<?php echo $row["id_computer"];?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
               	<td><a href="index.php?a=2&b=3&act=3&idmat=<?php echo $row["id_computer"];?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td></tr>
            	<?php
          	}
		}
	}
}?>

</tbody></table></div></div>

<div class="box"> <div class="box-header"><h3 class="box-title">Mat&eacute;riel interne non accessible au public</h3></div>
<div class="box-body no-padding"> <table class="table">
    <thead><tr>
        <th>Nom</th><th>OS</th><th>Salle</th><th>Commentaires</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>
<?php
$result=getMaterielFromEpn($epn);
if (FALSE == $result)
{
	echo getError(0) ;
}
else
{
	$nb=mysqli_num_rows($result);
	if ($nb == 0)
	{
		echo getError(9);
	}
	else
	{
		$usage=2; // usage par defaut
		for ($i=0;$i<$nb;$i++)
		{
			$row = mysqli_fetch_array($result);
    		$salle=getNomsalleforAnim($row["id_salle"]);
    		 if ($row["usage_computer"]==$usage)
            {
				?>
                <tr>
                <td><?php echo $row["nom_computer"]; ?></td>
                <td><?php echo $row["os_computer"]; ?></td>
                <td><?php echo $salle; ?></td>
                <td><?php echo stripslashes($row["comment_computer"]); ?></td>
                <td align="center"><a href="index.php?a=2&b=2&idmat=<?php echo $row["id_computer"];?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
               	<td align="center"><a href="index.php?a=2&b=3&act=3&idmat=<?php echo $row["id_computer"];?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td></tr>
            	<?php
			}
    	}
	}
}

        ?>
</tbody></table></div></div>

<div class="box"> <div class="box-header"><h3 class="box-title">Autre mat&eacute;riel</h3></div>
<div class="box-body no-padding"> <table class="table">
    <thead><tr>
        <th>Nom</th><th>OS</th><th>Salle</th><th>Commentaires</th><th>&nbsp;</th><th>&nbsp;</th></tr></thead><tbody>
<?php
$result=getMaterielFromEpn($epn);

if (FALSE == $result)
{
	echo getError(0) ;
}
else
{
	$nb=mysqli_num_rows($result);
	if ($nb == 0)
	{
		echo getError(9);
	}
	else
	{
		$usage=3; // usage par defaut
		for ($i=0;$i<$nb;$i++)
		{
			$row = mysqli_fetch_array($result);
    		$salle=getNomsalleforAnim($row["id_salle"]);
    		 if ($row["usage_computer"]==$usage)
            {
				?>
                <tr>
                <td><?php echo $row["nom_computer"]; ?></td>
                <td><?php echo $row["os_computer"]; ?></td>
                <td><?php echo $salle; ?></td>
                <td><?php echo stripslashes($row["comment_computer"]); ?></td>
                <td align="center"><a href="index.php?a=2&b=2&idmat=<?php echo $row["id_computer"];?>"><button class="btn btn-success btn-sm"  type="submit" value="modifier">Modifier</button></a></td>
               	<td align="center"><a href="index.php?a=2&b=3&act=3&idmat=<?php echo $row["id_computer"];?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a></td></tr>
            	<?php
			}
    	}
	}
}

        ?>
</tbody></table></div></div>



</section>

<!-- Left col --><section class="col-lg-4 connectedSortable">
<form action="index.php?a=2" method="post" role="form">	
	<div class="box box-primary"><div class="box-header"><i class="ion ion-home"></i><h3 class="box-title">Changer l'espace</h3></div>
	<div class="box-body">
	<div class="input-group">
		<select name="epn_r"  class="form-control input-sm" >
			<?php
				foreach ($espaces AS $key=>$value)
				{
					if ($epn == $key)
					{
						echo "<option value=\"".$key."\" selected>".$value."</option>";
					}
					else
					{
						echo "<option value=\"".$key."\">".$value."</option>";
					}
				}
				
			?>
			</select><div class="input-group-btn">
		
		<button type="submit" name="submit" value="Valider" class="btn btn-primary"><i class="fa fa-repeat"></i></button></div></div>
		<!--<div class="box-footer">
			<input type="hidden" name="form" value="1">
			<button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</div>-->
	</div></form>
	</div>
	
 	<div class="small-box bg-light-blue">
                <div class="inner"><h3>&nbsp;</h3><p>Nouveau Materiel</p></div>
		<div class="icon"><i class="ion ion-laptop"></i></div>
		<a href="index.php?a=2&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</section>
</div>
