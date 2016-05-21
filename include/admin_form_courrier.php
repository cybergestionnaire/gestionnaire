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
 

  include/admin_form_courrier.php V1.1
*/

// formulaire de gestion des courriers

$id=$_GET["idcourrier"];
$b=$_GET["b"];

if ($b==1)
{  // creation
        $post_url = "index.php?a=52&b=1&act=1";
        $label_bouton = "Ajouter un texte &agrave; un courrier" ;
        $label_titre ="Cr&eacute;er un nouveau texte de courrier";
}
else
{ // modification
        $post_url = "index.php?a=52&b=2&act=2&idcourrier=".$id;
        $label_bouton = "Modifier le texte du courrier" ;
        $label_titre="Modifier le texte d'un courrier";
        $row = getCourrier($id);

		//Informations matos
		$titrecourrier = stripslashes($row["courrier_titre"]);
		$texte = stripslashes($row["courrier_text"]);
		$name = $row["courrier_name"];
		$type=$row["courrier_type"];
	
}
//Affichage -----
echo geterror($_GET["mesno"]) ;

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
	
?>

<div class="row">
<form method="post" action="<?php echo $post_url; ?>">
<div class="col-md-6">
       <div class="box box-primary"><div class="box-header"><h3 class="box-title"><?php echo $label_titre;?></h3></div>
	<div class="box-body">
                <div class="form-group"><label>Nom* (pour la base)</label>
    			<input type="text" name="titre" value="<?php echo $titrecourrier;?>" class="form-control"></div>
	
	     <div class="form-group"><label>Contenu*</label>
		<textarea name="texte" class="form-control" rows="5" placeholder="Mettez votre texte au format html aussi !"><?php echo $texte;?></textarea></div>

	<div class="form-group"><label>Courrier rattach&eacute;</label>
	<select name="courrier_name" class="form-control" >
		<?php
			foreach ($arrayname AS $key=>$value)
			{
				if ($name == $key)
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
		
	<div class="form-group"><label>Type de contenu</label>
	<select name="courrier_type" class="form-control" >
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
		
		
	<div class="box-footer"><input type="submit" name="submit" value="<?php echo $label_bouton ;?>" class="btn btn-primary"></div>
	
	</div></div>
	
</form>
</div>

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


