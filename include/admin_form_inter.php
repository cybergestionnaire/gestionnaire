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
 

  include/admin_form_inter.php V0.1
*/

// Formulaire de creation d'une intervention


switch($_GET["error"])
{
    case 1:
        echo getError(4);
    break;
    case 2:
        echo getError(0);
    break;
}

?>
<div class="row">
<section class="col-lg-7 connectedSortable">

<form method="post" action="index.php?a=3" role="form">
<div class="box box-success"><div class="box-header"><h3 class="box-title">D&eacute;tail de l'intervention</h3></div>
	<div class="box-body">
	
	<div class="form-group"><label>Titre</label>
        <input type="text" name="titr" class="form-control"></div>
    
    <div class="form-group"><label>Date*</label>
        <input type="text" name="date" style="width:100px;" maxlength="10" class="form-control" value="<?php echo date('Y-m-d'); ?>"></div>
    
    <div class="form-group"><label>Commentaire*</label>
        <textarea name="comment" class="form-control"></textarea></div>

   <div class="form-group"><label>R&eacute;servation*</label>
        <input type="radio" name="dispo" value="0" checked>Possible (Le poste reste disponible)<br>
        <input type="radio" name="dispo" value="1">Impossible (Le poste devient indisponible)</div>
    
    <div class="form-group"><label>Poste concern&eacute;*</label>
        <input type="checkbox" name="all" value="all"> Tous<br><br>
            <?php
                $result = getAllMateriel();
                $nb = mysqli_num_rows($result);
                if ($nb>0)
                {
                    for ($i=1;$i<=$nb;$i++)
                    {
                        $row = mysqli_fetch_array($result);
                        echo "<input type=\"checkbox\" name=\"".$row["id_computer"]."\">".$row["nom_computer"]." (".$row["os_computer"].")<br>";
                    }
                }
            ?>
        </div>
        
	<div class="box-footer">
       <input type="submit" name="submit" value="Cr&eacute;er une intervention"  class="btn btn-primary"></div>
	</div>
</div>
</form>
</section></div>