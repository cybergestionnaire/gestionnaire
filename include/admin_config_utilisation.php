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
 

 include/admin_utilisation.php V0.1
*/

// gestion des fonctions des postes
                            
// traitement des post
$act = $_GET["act"];
$idutilisation = $_GET["idutilisation"];
//$typemenu = $_GET["typemenu"];

// type de menu d&eacute;fini
$menuarray = array (
         0 => "Menu Principal",   
         1 => "Sous Menu"
);

// type de visibilit&eacute; d&eacute;fini
$visiblearray = array (
         0 => "oui",   
         1 => "non"
);
switch ($act)
{
  case 1: // creation
       $utilisation = addslashes($_POST["newutilisation"]);
       $typemenu = $_POST["typemenu"];
       $visible = $_POST["visiblemenu"];
       if (FALSE == addUtilisation($utilisation,$typemenu, $visible))
       {
           echo getError(0);
       }     
  break;
  case 2: // modification
       $utilisation = addslashes($_POST["utilisation"]);
       $typemenu = $_POST["typemenu"];
       $visible = $_POST["visiblemenu"];
       if (FALSE == modUtilisation($idutilisation,$utilisation,$typemenu, $visible))
       {
           echo getError(0);
       }
  break;
  case 3: // suppression
       if (FALSE == supUtilisation($idutilisation))
       {
           echo getError(0);
       }
  break;
}

// affichage  -----------
$result = getAllUtilisation();
//$fonction = getAllUtilisation();
/*$typemenu="Non d&eacute;fini";
$visible="Non d&eacute;fini";*/
if (FALSE != $result)
{
	$nb = mysqli_num_rows($result);
      $fonction = array();
	  $menuarray2 = array();
	  $visiblearray2 = array();
      for ($i=1;$i<=$nb;$i++)
      {
          $row=mysqli_fetch_array($result);
          $fonction[$row["id_utilisation"]] = $row["nom_utilisation"] ;
		  $menuarray2[$row["id_utilisation"]] = $row["type_menu"] ;
		  $visiblearray2[$row["id_utilisation"]] = $row["visible"] ;
      }
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



<div class="box box-solid box-warning"><div class="box-header"><h3 class="box-title">Liste des utilisations</h3></div>
        <div class="box-body no-padding">
	<br><p>&nbsp; &nbsp;NB : Cette liste apparait sur le formulaire de pr&eacute;-inscription des usagers et sera utilis&eacute;e pour les statistiques.</p>
	<table class="table table-condensed">
		<thead><tr><th>Utilisation</th><th>Type</th><th>Visible</th><th>&nbsp;</th></tr></thead>
<?php

foreach ($fonction AS $key2 =>$value2)
{?> 
    <form action="index.php?a=48&act=2&idutilisation=<?php echo $key2; ?>" method="post" role="form">
         <tr>
           <td><input type="text" name="utilisation" value="<?php echo $value2; ?>" class="form-control"></td>
           <td><select name="typemenu" id="typemenu" class="form-control">
           <?php
			if(strcmp ($menuarray2[$key2],"")==0)
			{	
				echo "<option selected>Non d&eacute;fini</option>";
			}
           for($b=0; $b<2; $b++)
			{
				if ($menuarray[$b] == $menuarray2[$key2])
				{
					echo "<option value=\"".$menuarray[$b]."\" selected>".$menuarray[$b]."</option>";
				}
				else
				{
					echo "<option value=\"".$menuarray[$b]."\">".$menuarray[$b]."</option>";
				}
			}?>
       	   </select></td>
	<td><select name="visiblemenu" id="visiblemenu" class="form-control">
           <?php
			if(strcmp ($visiblearray2[$key2],"")==0)
			{	
				echo "<option selected>Non d&eacute;fini</option>";
			}
           for($b=0; $b<2; $b++)
			{
				if ($visiblearray[$b] == $visiblearray2[$key2])
				{
					echo "<option value=\"".$visiblearray[$b]."\" selected>".$visiblearray[$b]."</option>";
				}
				else
				{
					echo "<option value=\"".$visiblearray[$b]."\">".$visiblearray[$b]."</option>";
				}
			}?>
       	   </select></td>
           <td><input type="submit" value="modifier" class="btn bg-green sm">
            <a href="index.php?a=48&act=3&idutilisation=<?php echo $key2; ?>"><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a></td>
         </tr></form><?php
}
?>
</table>
</div>

 <div class="box-footer"><h4>Ajouter une autre utilisation</h4>
     <form method="post" action="index.php?a=48&act=1">  
     <div class="row">
				<div class="col-xs-5"><input type="text" name="newutilisation" class="form-control">  </div>
	<div class="col-xs-3"><select name="typemenu" class="form-control" >
	<?php foreach ($menuarray AS $key=>$value)
	{
            echo "<option value=\"".$value."\">".$value."</option>";
    }?>
    </select></div>
    
	<div class="col-xs-2"><select name="visiblemenu" class="form-control">
	<?php foreach ($visiblearray AS $key6=>$value6)
    {		
            echo "<option value=\"".$value6."\">".$value6."</option>";
    }?>
    </select></div>
    
    <div class="col-xs-2"><a type="submit" value="Ajouter"><button class="btn btn-primary">Ajouter</button></a></div>
</form>
</div>
</div>



