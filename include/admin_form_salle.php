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
 

  include/admin_form_salle.php V0.1
*/

// formulaire de creation / modification de salle
                            
    $id_salle = $_GET["idsalle"];
    if (FALSE == isset($id_salle))
    {   // Parametre du formulaire pour la CREATION
        $post_url = "index.php?a=44&b=1&act=1";
        $label_bouton = "Cr&eacute;er la salle" ;
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=44&b=2&act=2&idsalle=".$id_salle;
        $label_bouton = "Modifier la salle" ;
        $resultsalle = getSalle($id_salle);
  		$row = mysqli_fetch_array($resultsalle);

        //Informations matos
        $nom     = $row["nom_salle"];
        $espace  = $row["id_espace"];
        $comment =stripslashes( $row["comment_salle"]);
    }

	$espaces = getAllepn();

//Affichage -----
echo $mess ;
?>
<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Cr&eacute;er et modifier une salle</h3></div>
		<div class="box-body">
<form method="post" action="<?php echo $post_url; ?>" role="form">
<div class="form-group">
	<label >Nom de la salle *: </label>
    	<input type="text" name="nom" value="<?php echo $nom;?>"  class="form-control"></div>
<div class="form-group">
	<label >Espace *:</label>
	<select name="espace"  class="form-control">
	<?php
			foreach ($espaces AS $key=>$value)
			{
				if ($espace == $key)
				{
					echo "<option value=\"".$key."\" selected>".$value."</option>";
				}
				else
				{
					echo "<option value=\"".$key."\">".$value."</option>";
				}
			}
		?>
		</select></div>
    
<div class="form-group">
	<label >Commentaires : </label>
    	<textarea name="comment"  class="form-control" rows="3"><?php echo $comment;?></textarea></div>
<div class="box-footer"><button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button></div>
</form>
</div></div>
