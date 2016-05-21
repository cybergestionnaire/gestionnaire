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
 

  include/admin_form_materiel.php V0.1
*/

// formulaire de creation / modification de materiel
                            
    $idmat = $_GET["idmat"];
    $epn=$_GET["epnr"];
    if (FALSE == isset($idmat))
    {   // Parametre du formulaire pour la CREATION
        $post_url = "index.php?a=2&b=1&act=1";
        $label_bouton = "Cr&eacute;er le poste" ;
		
		
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=2&act=2&idmat=".$idmat;
        $label_bouton = "Modifier le poste" ;
        $row = getMateriel($idmat);

        //Informations matos
        $nom     = stripslashes($row["nom_computer"]);
        $os      = $row["os_computer"];
        $usage   = $row["usage_computer"];
        $comment = stripslashes($row["comment_computer"]);
        $salle = $row["id_salle"];
		
        $adresseIP=$row["adresse_ip_computer"];
        $adresseMAC=$row["adresse_mac_computer"];
        $nomhote=$row["nom_hote_computer"];
		
		$fonctionarray=$row["fonction_computer"];
		$fonctions=explode(";",$fonctionarray);
		
        
    }
	
$fonction=getAllUsage();
$nbfonction=count($fonction);


// type d'os d&eacute;fini
$osarray = array (
         0 => "Windows 8",   
         1 => "Windows 7",
         2 => "Windows Vista",
         3 => "Windows XP",
         4 => "Mac OSX",
         5 => "Ubuntu",
         6 => "Linux",
         7 => "Windows server"
);

	$allsalles=getAllSalleAtelier();
	

//Affichage -----
echo $mess ;
?>

<div class="row"><div class="col-md-7">


<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title"><?php echo $label_bouton; ?></h3></div>
	<div class="box-body">
<form method="post" action="<?php echo $post_url; ?>" role="form">
<div class="form-group">
	<label >Nom du poste *: </label>
    	<input type="text" name="nom" value="<?php echo $nom;?>" class="form-control"></div>
<div class="form-group">
	<label >Syst&egrave;me :</label>
       <select name="os" class="form-control">
    <?php
    foreach ($osarray AS $key=>$value)
    {
        if ($os == $value)
        {
            echo "<option value=\"".$value."\" selected>".$value."</option>";
        }
        else
        {
            echo "<option value=\"".$value."\">".$value."</option>";
        }
    }
    ?>
    </select></div>
    
<div class="form-group">
	<label >Salle *:</label>
   
    <select name="salle" class="form-control">
       	<?php
        foreach ($allsalles AS $key=>$value)
        {
            if ($salle == $key)
            {
                echo "<option value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option value=\"".$key."\">".$value."</option>";
            }
        }
		
    ?></select></div>
    
<div class="form-group">
	<label >Commentaires : </label>
    	<textarea name="comment" class="form-control"><?php echo $comment;?></textarea></div>
<div class="form-group">
	<label >Accessible &agrave; la r&eacute;servation : </label>
   
        <?php
        switch ($usage)
        {
            case 1:
                 $sel1="checked=\"checked\"" ;
                 $sel2="";
				 $sel3="";
            break;
            case 2:       
                 $sel1="" ;
                 $sel2="checked=\"checked\"";
				  $sel3="";
            break;
			case 3:       
                 $sel1="" ;
                 $sel2="";
				 $sel3="checked=\"checked\"";
            break;
            default:
                 $sel1="checked=\"checked\"" ;
                 $sel2="";
				 $sel3="";
            break;
        }
        ?>
		<div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="usage" value="1" <?php echo $sel1; ?>><label>Oui (usage public)</label></div>
        <div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="usage" value="2" <?php echo $sel2; ?>><label>Non (usage interne)</label></div>
		<div class="radio">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="usage" value="3" <?php echo $sel3; ?>><label>Non (usage public sans r&eacute;servation)</label></div>
		</div>
<div class="form-group">
	<label >Fonctions</label>
    <?php
    if ($idmat !="" )
    {   
	
		for ($x=1;$x<=$nbfonction;$x++){
	
		if (in_array($x,$fonctions))  { 
		$check = "checked"; 
		} else {
		$check = ''; 
		}
		echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fonction[]\" value=".$x."  ".$check.">&nbsp;&nbsp;".$fonction[$x]."</div>";
		}
		
    }
    else
    {   // creation d'un poste
	
        $fonction = getAllUsage() ;
        foreach ($fonction AS $key => $value)
        {
            echo "<div class=\"checkbox\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fonction[]\" value=".$key.">&nbsp;&nbsp;".$value."</div>" ;
        }
    }
    ?>
    </div>
    
    <!-- IP mask -->
    <div class="form-group">
        <label>Adresse IP</label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
            </div>
            <input type="text" name="adresseIP" class="form-control" value="<?php echo $adresseIP;?>" placeholder="192.168.0.1" data-inputmask="'alias': 'ip'" data-mask/>
        </div><!-- /.input group -->
    </div><!-- /.form group -->

     <div class="form-group">
        <label>Adresse MAC</label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
            </div>
            <input type="text" name="adresseMAC" class="form-control" value="<?php echo $adresseMAC;?>" placeholder="AA-00-B2-12...."/>
        </div><!-- /.input group -->
    </div><!-- /.form group -->
    
    
     <div class="form-group">
        <label>Nom Hôte</label>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-laptop"></i>
            </div>
            <input type="text" name="nomhotecomputer" class="form-control" value="<?php echo $nomhote;?>" placeholder="\\Poste..." />
        </div><!-- /.input group -->
    </div><!-- /.form group -->
    
 <div class="box-footer"><button type="submit" class="btn btn-primary" value="<?php echo $label_bouton;?>" name="submit"><?php echo $label_bouton;?></button></div>
</form>
</div>
</div>

</div></div>
