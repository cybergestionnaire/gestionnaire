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
 

  include/admin_form_breve.php V0.1
*/

// formulaire de gestion des breves

if (FALSE == isset($id))
{  // creation
        $post_url = "index.php?a=4&b=1&act=1";
        $label_bouton = "Cr&eacute;er une br&egrave;ve" ;
				$datenews=date("Y-m-d H:i");
}
else
{ // modification
        $post_url = "index.php?a=4&b=2&act=2&idbreve=".$id;
        $label_bouton = "Modifier la br&egrave;ve" ;
        $row = getBreve($id);

        //Informations matos
        $titr     = stripslashes($row["titre_news"]);
        $comment   = stripslashes($row["comment_news"]);
        $visible   = $row["visible_news"];
		$type=$row["type_news"];
		$datenews=$row["date_news"];
		$datepublish=$row["date_publish"];
		$epn=$row["id_epn"];
		
		//debug($visible);
}
//Affichage -----
echo $mess ;
// retrouver les espaces
$espaces = getAllepn();

// array des types d'info
$arraytype=array(
	1=>News,
	2=>Reunion,
	3=>Animation,
	4=>Conference,
	5=>Evenement
	);
	
?>

<form method="post" action="<?php echo $post_url; ?>">
<div class="row"><div class="col-md-6">
       <div class="box box-primary"><div class="box-header"><h3 class="box-title"><?php echo $label_bouton;?></h3></div>
	<div class="box-body">
                <div class="form-group"><label>Titre*</label>
    			<input type="text" name="titr" value="<?php echo $titr;?>" class="form-control"></div>
	
	     <div class="form-group"><label>Contenu*</label>
		<textarea name="comment" class="form-control" rows="5" placeholder="Mettez votre texte au format html aussi !"><?php echo $comment;?></textarea></div>

    <div class="form-group"><label>Visibilit&eacute;</label>
    <?php
        switch ($visible)
        {
            case 0:
                 $sel1="checked=\"checked\"" ;
                 $sel2="";
            break;
            case 1:
                 $sel1="" ;
                 $sel2="checked=\"checked\"";
            break;
            default:
                 $sel1="checked=\"checked\"" ;
                 $sel2="";
            break;
        }
        ?>
     <div class="radio">
		 <label><input  type="radio" name="visible" value="0" <?php echo $sel1; ?>> Public (tout le monde peut la consulter)</label>
		 <label><input  type="radio" name="visible" value="1" <?php echo $sel2; ?>> Interne (Visible uniquement par les administrateurs et animateurs </label>
    </div></div>
	<div class="form-group"><label>Type</label>
	<select name="type" class="form-control" >
		<?php
			foreach ($arraytype AS $key=>$value)
			{
				if ($type == $key)
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
		
	<div class="form-group"><label>Date de l'&eacute;v&eacute;nement</label>
		<input type="text" class="form-control" name="datenews" value="<?php echo $datenews; ?>" placeholder="2014-15-15 15:30">
		<input type="hidden" name="datepublish" value="<?php echo date('Y-m-d H:i'); ?>" ></div>
		
	<div class="form-group"><label>Epn li&eacute;</label>
	<select name="idepn" class="form-control" >
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
		</select></div>
	
	<div class="box-footer"><input type="submit" name="nom" value="<?php echo $label_bouton ;?>" class="btn btn-primary"></div>
	
	</div></div>
	
</form>
</div>
</div>


