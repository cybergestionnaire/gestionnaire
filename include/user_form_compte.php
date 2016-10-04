<?php
/*
     This file is part of CyberGestionnaire.

    CyberGestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

  include/admin_form_user.php V0.1
*/

// Formulaire de creation ou de modification d'un adherent

    $id_user = $_SESSION["iduser"];

        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?m=2&b=1&iduser=".$id_user;
        $label_bouton = "Modifier mes informations" ;

        $row = getUser($id_user);
// Information Utilisateur
        $date     =  $row["date_insc_user"];
        $nom      =  $row["nom_user"];
        $prenom   =  $row["prenom_user"];
        $sexe     =  $row["sexe_user"];
        $jour     =  $row["jour_naissance_user"];
        $mois     =  $row["mois_naissance_user"];
        $annee    =  $row["annee_naissance_user"];
        $adresse  =  $row["adresse_user"];
        $code_postale    =  $row["code_postal_user"];
        $commune_autre    =  $row["commune_autre_user"];
        $ville    =  $row["ville_user"];
        $pays    =  $row["pays_user"];
        $tel      =  $row["tel_user"];
		$telport  =  $row["tel_port_user"];
        $mail     =  $row["mail_user"];
        $temps     =  $row["temps_user"];
        $csp     =  $row["csp_user"];
        $equipement     =  $row["equipement_user"];
        $utilisation     =  $row["utilisation_user"];
        $connaissance     =  $row["connaissance_user"];
        $info     =  $row["info_user"];
        $loginn    =  $row["login_user"];
    
// Tableau des mois
        $month = array(
               1=> "Janvier",
               2=> "F&eacute;vrier",
               3=> "Mars",
               4=> "Avril",
               5=> "Mai" ,
               6=> "Juin",
               7=> "Juillet",
               8=> "Aout",
               9=> "Septembre",
               10=> "Octobre",
               11=> "Novembre",
               12=> "D&eacute;cembre",
        );
		
		// type de csp défini
$csparray = array (
         0 => "Élève",   
         1 => "Collégien",
         2 => "Lycéen",
         3 => "Étudiant",
         4 => "Demandeur d'emploi",   
         5 => "Agriculteur exploitant",
         6 => "Artisan-commerçant-chef d'entreprise",
         7 => "Cadres et professions libérales",
         8 => "Profession intermédiaires",   
         9 => "Employé",
         10 => "Ouvrier",
         11 => "Retraité"
);
		
		// type d'équipement défini
$equipementarray = array (
         0 => "Aucun équipement",   
         1 => "Ordinateur seul",
         2 => "Ordinateur et Internet"
);
		
		// type d'utilisation défini
$utilisationarray = array (
         0 => "Aucun Lieu",
         1 => "A la maison",   
         2 => "Au bureau ou à l'école",
         3 => "A la maison et au bureau ou à l'école"
);
		
		// type de connaissance défini
$connaissancearray = array (
         0 => "Débutant",   
         1 => "Intermédiaire",
         2 => "Confirmé"
);

// recupere les villes
$resultville = getAllCity();
$nbville=mysqli_num_rows($resultville);
for($i=0; $i<$nbville; $i++)
{
	$rowville = mysqli_fetch_array($resultville);
	$townarray[$i] = $rowville["id_city"];
	$townnomarray[$i] = $rowville["nom_city"];
}
//$row=mysql_fetch_array($result);

//Affichage -----
echo $mess ;



?>
<table align="center">
<form name="formcompte" method="post" action="<?php echo $post_url; ?>">
<tr>
    <td colspan="2"><br><b>Informations personnelles : </b></td></tr>
<tr>
    <td class="label">Date d'inscription :</td>
    <td class="field"><p><p><?php echo $row["date_insc_user"] ; ?></p></p></td></tr>
<tr>
    <td class="label">Civilité :</td>
    <td class="field2">
    <?php
    if (FALSE != isset($id_user))
    {
        if ($sexe =="F")
        {?>
        <input type="radio" name="sexe" value="H">Monsieur<br>
        <input type="radio" name="sexe" value="F" checked>Madame
        <?php }
        else
        {?>
        <input type="radio" name="sexe" value="H" checked>Monsieur<br>
        <input type="radio" name="sexe" value="F">Madame
        <?php
        }
    }
    else
    {?>
    <input type="radio" name="sexe" value="H" checked>Monsieur<br>
    <input type="radio" name="sexe" value="F">Madame
    <?php
    }
    ?>
    </td></tr>
<tr>
    <td class="label">Nom :</td>
    <td class="field"><input type="text" name="nom" value="<?php echo $nom;?>" onChange="javascript:this.value=this.value.toUpperCase();">*</td></tr>
<tr>
    <td class="label">Pr&eacute;nom :</td>
    <td class="field"><input type="text" name="prenom" value="<?php echo $prenom;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();">*</td></tr>
<tr>
    <td class="label">Date de naissance :</td>
    <td class="field"><select name="jour">
        <?php
        for ($i=1 ; $i<32 ; $i++)
        {
            if ($i == $jour)
            {
                echo "<option value=\"".$i."\" selected>".$i."</option>";
            }
            else
            {
                echo "<option value=\"".$i."\">".$i."</option>";
            }
        }
        ?>
    </select>
    <select name="mois">
    <?php
        foreach ($month AS $key=>$value)
        {
            if ($mois == $key)
            {
                echo "<option value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option value=\"".$key."\">".$value."</option>";
            }
        }
    ?>


    </select>
    <input type="text" name="annee" maxlength="4" value="<?php echo $annee;?>" style="width:50px;">*</td></tr>
<tr>
    <td class="label">Adresse :</td>
    <td class="field"><textarea name="adresse"><?php echo $adresse;?></textarea>*</td></tr>
<tr>
    <td class="label">Ville :</td>
    <td class="field">
    <select name="ville" onchange="AfficheCommuneAutre();">
    <?php
	if(empty($ville))
	{
					$communediff=1;
	}
		for($i=0; $i<$nbville; $i++)
        {
            if ($ville == $townarray[$i])
            {
                //echo "<option onclick=\"AfficheCommuneAutre();\" value=\"".$townarray[$i]."\" selected>".$townnomarray[$i]."</option>";
                echo "<option value=\"".$townarray[$i]."\" selected>".$townnomarray[$i]."</option>";
    			if(strcmp($townnomarray[$i], "Autres")==0)
				{
					$communediff=1;
				}
            }
            else
            {
                //echo "<option onclick=\"AfficheCommuneAutre();\" value=\"".$townarray[$i]."\">".$townnomarray[$i]."</option>";
				echo "<option value=\"".$townarray[$i]."\">".$townnomarray[$i]."</option>";
            }
        }
    ?>
    </select>
    <?php
    if($communediff==1)
	{	
		?>
		<p><input type="text" maxlength="20" placeholder="Code postale" style="width: 100px;" name="code_postale" value="<?php echo $code_postale;?>"/><p><input type="text" maxlength="50" placeholder="Autre Commune" style="width: 150px;" name="commune_autre" value="<?php echo $commune_autre;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"/><p><input type="text" maxlength="50" placeholder="Pays" style="width: 150px;" name="pays" value="<?php echo $pays;?>" onchange="this.value = this.value.toUpperCase();"/></td></tr>
        <?php
	}
	else
	{
		?>
		<p><input type="hidden" maxlength="20" placeholder="Code postale" style="width: 100px;" name="code_postale" value="<?php echo $code_postale;?>"/><p><input type="hidden" maxlength="50" placeholder="Autre Commune" style="width: 150px;" name="commune_autre" value="<?php echo $commune_autre;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"/><p><input type="hidden" placeholder="Pays" style="width: 150px;" maxlength="50" name="pays" value="<?php echo $pays;?>" onchange="this.value = this.value.toUpperCase();"/></td></tr>
        <?php
	}
	?>
<tr>
    <td class="label">T&eacute;l&eacute;phone Fixe :</td>
    <td class="field"><input type="text" name="tel" value="<?php echo $tel;?>"></td></tr>
<tr>
    <td class="label">T&eacute;l&eacute;phone Portable :</td>
    <td class="field"><input type="text" name="telport" value="<?php echo $telport;?>"></td></tr>
<tr>
    <td class="label">E-Mail :</td>
    <td class="field"><input type="text" name="mail" value="<?php echo $mail;?>"></td></tr>
<tr>
    <td colspan="2"><br><b>Information de typologie : </b></td></tr>
<tr>
    <td class="label">Catégorie Socio-professionnelle :</td>
    <td class="field2">
    <?php
        foreach ($csparray AS $keycsp=>$valuecsp)
        {
            if (strcmp ($csp,$keycsp)==0)
            {
       			echo "<input type=\"radio\" name=\"csp\" value=".$keycsp." checked>".$valuecsp."<br>";
            }
            else
            {
       			echo "<input type=\"radio\" name=\"csp\" value=".$keycsp.">".$valuecsp."<br>";
            }
        }
    ?>
    </td></tr>
<tr>
    <td class="label"><p><p>&Eacute;quipement personnel:</p></p> </td>
    <td class="field2">
    <?php
        foreach ($equipementarray AS $keyequip=>$valueequip)
        {
            if (strcmp ($equipement,$keyequip)==0)
            {				
       			echo "<input type=\"radio\" name=\"equipement\" value=".$keyequip." checked>".$valueequip."";
            }
            else
            {
       			echo "<input type=\"radio\" name=\"equipement\" value=".$keyequip.">".$valueequip."";
            }
        }
    ?>
    </td></tr>
<tr>
    <td class="label">Lieu d'utilisation d'internet :</td>
    <td class="field2">
    <?php
        foreach ($utilisationarray AS $keyutil=>$valueutil)
        {
            if (strcmp ($utilisation,$keyutil)==0)
            {
       			echo "<input type=\"radio\" name=\"utilisation\" value=".$keyutil." checked>".$valueutil."  ";
            }
            else
            {
       			echo "<input type=\"radio\" name=\"utilisation\" value=".$keyutil.">".$valueutil."  ";
            }
        }
    ?>
    </td></tr>
<tr>
    <td class="label">Quel est votre niveau en informatiques ?</td>
    <td class="field2">
    <?php
        foreach ($connaissancearray AS $key=>$valuecon)
        {
            if (strcmp ($connaissance,$valuecon)==0)
            {
       			echo "<input type=\"radio\" name=\"connaissance\" value=".$valuecon." checked>".$valuecon."";
            }
            else
            {
       			echo "<input type=\"radio\" name=\"connaissance\" value=".$valuecon.">".$valuecon."";
            }
        }
    ?>
    </td></tr>
<tr>
    <td class="label">Information complémentaire :</td>
    <td class="field"><textarea name="info"><?php echo $info;?></textarea></td></tr>
<tr>
    <td colspan="2"><br><b>Param&egrave;tres de connexion : </b></td></tr>
<tr>
    <td class="label">Login :</td>
    <td class="field"><input type="text" name="login" value="<?php echo $loginn;?>">*</td></tr>
<tr>
    <td class="label">Mot de passe :</td>
    <td class="field"><input type="text" name="passw" value="">*</td></tr>
<tr>
    <td colspan="2" align="center"><br>
    <input type="submit" value="<?php echo $label_bouton ;?>" name="submit"></td></tr>
 </table>   
</form>