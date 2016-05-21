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
 

 include/admin_usage.php V0.1
*/

// gestion des fonctions des postes
                            
// traitement des post
$act = $_GET["act"];
$idusage = $_GET["idusage"];

switch ($act)
{
  case 1: // creation
       $usage = $_POST["newusage"];
       if (FALSE == addUsage($usage))
       {
           echo getError(0);
       }     
  break;
  case 2: // modification
       $usage = $_POST["usage"];
       if (FALSE == modUsage($idusage,$usage))
       {
           echo getError(0);
       }
  break;
  case 3: // suppression
       if (FALSE == supUsage($idusage))
       {
           echo getError(0);
       }
  break;
}

// affichage  -----------
$fonction = getAllUsage();
?>
<!-- DIV acc&egrave;s direct aux autres param&egrave;tres-->
<div class="row"><div class="col-md-12">
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

</div>


<div class="col-md-8">
<div class="box box-solid box-warning"><div class="box-header"><h3 class="box-title">Gestion des fonctions pour les postes</h3></div>
<div class="box-body no-padding"><table class="table">
			<thead> 
				<tr><th>Fonctions</th><th></th><tr></thead><tbody>
<?php
foreach ($fonction AS $key =>$value)
{
    echo "<form action=index.php?a=46&act=2&idusage=".$key." method=\"post\">
         <tr>
           <td><input type=\"text\" name=\"usage\" value=\"".$value."\"  class=\"form-control\"></td>
           <td><input type=\"submit\" value=\"modifier\" class=\"btn bg-green sm\">
           <a href=\"index.php?a=46&act=3&idusage=".$key."\"><button type=\"button\" class=\"btn bg-red sm\"><i class=\"fa fa-trash-o\"></i></button></a></td>
         </tr></form>";
}
?>
</table></div>
<div class="box-footer"><h4>Nouvelle fonction</h4>
<form method="post" action="index.php?a=46&act=1">
	 <div class="input-group input-group-sm"><label></label>
		<input type="text" name="newusage" class="form-control" placeholder="Nom">
		<span class="input-group-btn" ><button class="btn btn-primary btn-flat" type="submit" >Cr&eacute;er</button> </span>
		<!--<input type="submit" value="Cr&eacute;er"  class="alt_btn">--></div>
</form>
</div></div>

</div>
