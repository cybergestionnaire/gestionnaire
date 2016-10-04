<script type="text/javascript" src="./js/jquerry/external/jquery/jquery.js"></script>
<script type="text/javascript" src="./js/jquerry/jquery-ui.js"></script>

<link rel="stylesheet" href="./js/jquerry/jquery-ui.css">

<script type="text/javascript">

  $(function() 
  {
    $( "#datepicker" ).datepicker();
  });
  </script>
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
 
 2014 SAINT MARTIN Brice
 

  include/admin_form_forfait.php V0.1
*/

// Formulaire de creation ou de modification d'un forfait

    $id_forfait = $_GET["idforfait"];

    if (FALSE == isset($id_forfait))
    {   // Parametre du formulaire pour la CREATION
        $post_url = "index.php?a=23&b=1&act=1";
        $label_bouton = "Cr&eacute;er le forfait" ;
		
        $date_creat_forfait = date("d/m/Y");
		$type_forfait = 0;
		$nom_forfait="";
		$prix_forfait = 0;
		$critere_forfait="";
		$comment_forfait="";
		$date_debut_forfait = date("d/m/Y");
		$nombre_duree_forfait = 0;
		$unite_duree_forfait=0;
		$nombre_temps_affectation = 0;
		$unite_temps_affectation = 0;
		$frequence_temps_affectation=0;
		$nombre_atelier_forfait=0;
		$temps_affectation_occasionnel=0;
		//$status_forfait=0;
		$temps_forfait_illimite=0;
    }
    else
    {
        // Parametre du formulaire pour la MODIFICATION
        $post_url = "index.php?a=23&b=2&act=2&idforfait=".$id_forfait;
        $label_bouton = "Modifier le forfait" ;

        $row = getForfait($id_forfait);
		
		// Information Forfait
	    $dateusa = DateTime::createFromFormat('Y-m-d', $row["date_creation_forfait"]);
        $date_creat_forfait = $dateusa->format('d/m/Y');
		$type_forfait = $row["type_forfait"];
		$nom_forfait= $row["nom_forfait"];
		$prix_forfait = $row["prix_forfait"];
		$critere_forfait= $row["critere_forfait"];
		$comment_forfait= $row["commentaire_forfait"];
	    $dateusa = DateTime::createFromFormat('Y-m-d', $row["date_debut_forfait"]);
	    $date_debut_forfait = $dateusa->format('d/m/Y');
		$nombre_duree_forfait = $row["nombre_duree_forfait"];
		$unite_duree_forfait= $row["unite_duree_forfait"];
		$nombre_temps_affectation = $row["nombre_temps_affectation"];
		$unite_temps_affectation = $row["unite_temps_affectation"];
		$frequence_temps_affectation= $row["frequence_temps_affectation"];
		$nombre_atelier_forfait= $row["nombre_atelier_forfait"];
		$temps_affectation_occasionnel=$row["temps_affectation_occasionnel"];
		//$status_forfait=$row["status_forfait"];
		$temps_forfait_illimite=$row["temps_forfait_illimite"];
    }
	// Tableau des types de forfaits
    $tab_type_forfait = array(
           1=> "Forfait d'affectation",
           2=> "Forfait d'atelier",
           3=> "Forfait tout compris",
           4=> "Forfait d'affectation occasionnel"
    );
	
	// Tableau des unité de durée forfait
    $tab_unite_duree_forfait = array(
           1=> "Jour",
           2=> "Semaine",
           3=> "Mois"
    );
	
	// Tableau des unité d'affectation
    $tab_unite_temps_affectation = array(
           1=> "Minutes",
           2=> "Heures"
    );
	
	// Tableau des fréquence d'affectation
    $tab_frequence_temps_affectation = array(
           1=> "Jour",
           2=> "Semaine",
           3=> "Mois"
    );
		
//Affichage -----
echo $mess ;

?>
<table align="center">
<form name="formforfait" method="post" action="<?php echo $post_url; ?>">
<tr>
    <td colspan="2"><br><b>Informations G&eacute;n&eacute;rales : </b></td></tr>
<tr>
    <td class="label">Date de cr&eacute;ation :</td>
    <td class="field"><p><p><?php echo $date_creat_forfait ; ?></p></p></td></tr>
<tr>
    <td class="label">Type :</td>
    <td class="field"><select name="type_forfait" onchange="AfficheAffectation();" >
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
    	<option value="0">Choisissez un type de forfait ...</option>
        <?php
	}
	for($i=1; $i<=4; $i++)
	{
		if($i==$type_forfait)
		{
			?>
    		<option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_type_forfait[$i]; ?></option>
			<?php
        }
		else
		{
			?>
    		<option value="<?php echo $i; ?>"><?php echo $tab_type_forfait[$i]; ?></option>
			<?php
        }
	}
	?></select></td></tr>
<tr>
    <td class="label">Intitul&eacute; :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
    	<input disabled=true type="text" name="nom_forfait" value="<?php echo $nom_forfait; ?>" maxlength="50"/>
        <?php
	}
	else
	{
		?>
    	<input type="text" name="nom_forfait" value="<?php echo $nom_forfait; ?>" maxlength="50"/>
        <?php
	}
	?></td></tr>
<tr>
    <td class="label">Prix (&euro;) :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
        <input disabled=true type="number" step="1" min="0" value="<?php echo $prix_forfait; ?>" name="prix_forfait" style="width:60px;" /> 0 = Gratuit
        <?php
	}
	else
	{
		?>
    	<input type="number" step="1" min="0" value="<?php echo $prix_forfait; ?>" name="prix_forfait" style="width:60px;" /> 0 = Gratuit
        <?php
	}
	?></td></tr>
<tr>
    <td class="label">Crit&egrave;re :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
        <input disabled=true type="text" name="critere_forfait" value="<?php echo $critere_forfait; ?>" maxlength="50" />
        <?php
	}
	else
	{
		?>
		<input type="text" name="critere_forfait" value="<?php echo $critere_forfait; ?>" maxlength="50" />        
		<?php
	}
	?></td></tr>
<tr>
    <td class="label">Commentaire :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
        <textarea disabled=true name="comment_forfait"><?php echo $comment_forfait; ?></textarea>
        <?php
	}
	else
	{
		?>
    	<textarea name="comment_forfait"><?php echo $comment_forfait; ?></textarea>
        <?php
	}
	?></td></tr>
<tr>
    <td colspan="2"><br><b>Temps de validit&eacute; du forfait : </b></td></tr>
<tr>
    <td class="label">Date de d&eacute;but de validit&eacute; :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
        <input disabled=true type="text" name="imputDate" size="10" maxlength="10" value="<?php echo $date_debut_forfait;?>" id="datepicker" style="width: 100px;"> (jj/mm/aaaa)
        <?php
	}
	else
	{
		?>
    	<input type="text" name="imputDate" size="10" maxlength="10" value="<?php echo $date_debut_forfait;?>" id="datepicker" style="width: 100px;"> (jj/mm/aaaa)
        <?php
	}
	?></td></tr>
<tr>
    <td class="label">Nombre :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
        <input disabled=true type="number" step="1" min="0" value="<?php echo $nombre_duree_forfait; ?>" name="nombre_duree_forfait" style="width:60px;" />
       	<?php
	}
	else
	{
		if($temps_forfait_illimite==0)	//non illimité
		{
			?>
    		<input type="number" step="1" min="0" value="<?php echo $nombre_duree_forfait; ?>" name="nombre_duree_forfait" style="width:60px;" />
        	<?php
		}
		else if($temps_forfait_illimite==1) //illimité
		{
			?>
        	<input disabled=true type="number" step="1" min="0" value="<?php echo $nombre_duree_forfait; ?>" name="nombre_duree_forfait" style="width:60px;" />
       	 	<?php
		}
	}
	?></td></tr>
<tr>
    <td class="label">Unit&eacute; de temps :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		?>
    	<select disabled=true name="unite_duree_forfait">
        <?php
        for($i=1; $i<=3; $i++)
        {
            if($i==$unite_duree_forfait)
            {
                ?>
                <option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
                <?php
            }
            else
            {
                ?>
                <option value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
                <?php
            }
        }
		?>
        </select>
        <?php
	}
	else
	{
		if($temps_forfait_illimite==0)	//non illimité
		{
			?>
			<select name="unite_duree_forfait">
			<?php
			for($i=1; $i<=3; $i++)
			{
				if($i==$unite_duree_forfait)
				{
					?>
					<option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
					<?php
				}
				else
				{
					?>
					<option value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
					<?php
				}
			}
			?>
			</select>
			<?php
		}
		else if($temps_forfait_illimite==1) //illimité
		{
			?>
			<select disabled=true name="unite_duree_forfait">
			<?php
			for($i=1; $i<=3; $i++)
			{
				if($i==$unite_duree_forfait)
				{
					?>
					<option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
					<?php
				}
				else
				{
					?>
					<option value="<?php echo $i; ?>"><?php echo $tab_unite_duree_forfait[$i]; ?></option>
					<?php
				}
			}
			?>
			</select>
			<?php
		}
	}
	?></td></tr>
<tr>
    <td class="label">Illimit&eacute; :</td>
    <td class="field">
    <?php 
	if(($type_forfait==0)||($type_forfait>4))
	{
		if($temps_forfait_illimite==0)	//non illimité
		{
			?>
			<input onchange="AfficheDuree();" disabled=true type="checkbox" value="1" name="temps_forfait_illimite" style="width:20px;" />
			<?php
		}
		else if($temps_forfait_illimite==1) //illimité
		{
			?>
			<input onchange="AfficheDuree();" disabled=true type="checkbox" checked=true value="1" name="temps_forfait_illimite" style="width:20px;" />
			<?php
		}
	}
	else
	{
		if($temps_forfait_illimite==0)	//non illimité
		{
			?>
    		<input onchange="AfficheDuree();" type="checkbox" value="1" name="temps_forfait_illimite" style="width:20px;" />
        	<?php
		}
		else if($temps_forfait_illimite==1) //illimité
		{
			?>
			<input onchange="AfficheDuree();" type="checkbox" value="1" checked=true name="temps_forfait_illimite" style="width:20px;" />
			<?php
		}
	}
	?></td></tr>
<tr>
    <td colspan="2"><br><b>Limite d'affectation : </b></td></tr>
<tr>
    <td class="label">Nombre :</td>
    <td class="field">
    <?php 
	if(($type_forfait==1)||($type_forfait==3))
	{
		?>
    	<input type="number" step="1" min="0" value="<?php echo $nombre_temps_affectation; ?>" name="nombre_temps_affectation" style="width:60px;" />
        <?php
	}
	else
	{
		?>
        <input disabled=true type="number" step="1" min="0" value="<?php echo $nombre_temps_affectation; ?>" name="nombre_temps_affectation" style="width:60px;" />
        <?php
	}
	?></td></tr>
<tr>
    <td class="label">Unit&eacute; de temps :</td>
    <td class="field">
    <?php 
	if(($type_forfait==1)||($type_forfait==3))
	{
		?>
    	<select name="unite_temps_affectation">
        <?php
        for($i=1; $i<=2; $i++)
        {
            if($i==$unite_temps_affectation)
            {
                ?>
                <option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_unite_temps_affectation[$i]; ?></option>
                <?php
            }
            else
            {
                ?>
                <option value="<?php echo $i; ?>"><?php echo $tab_unite_temps_affectation[$i]; ?></option>
                <?php
            }
        }
		?>
        </select>
        <?php
	}
	else
	{
		?>
    	<select disabled=true name="unite_temps_affectation">
        <?php
        for($i=1; $i<=2; $i++)
        {
            if($i==$unite_temps_affectation)
            {
                ?>
                <option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_unite_temps_affectation[$i]; ?></option>
                <?php
            }
            else
            {
                ?>
                <option value="<?php echo $i; ?>"><?php echo $tab_unite_temps_affectation[$i]; ?></option>
                <?php
            }
        }
		?>
        </select>
        <?php
	}
	?></td></tr>
<tr>
    <td class="label">Fr&eacute;quence :</td>
    <td class="field">
    <?php 
	if(($type_forfait==1)||($type_forfait==3))
	{
		?>
    	<select name="frequence_temps_affectation">
        <?php
        for($i=1; $i<=3; $i++)
        {
            if($i==$frequence_temps_affectation)
            {
                ?>
                <option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_frequence_temps_affectation[$i]; ?></option>
                <?php
            }
            else
            {
                ?>
                <option value="<?php echo $i; ?>"><?php echo $tab_frequence_temps_affectation[$i]; ?></option>
                <?php
            }
        }
		?>
        </select>
        <?php
	}
	else
	{
		?>
    	<select disabled=true name="frequence_temps_affectation">
        <?php
        for($i=1; $i<=3; $i++)
        {
            if($i==$frequence_temps_affectation)
            {
                ?>
                <option selected="selected" value="<?php echo $i; ?>"><?php echo $tab_frequence_temps_affectation[$i]; ?></option>
                <?php
            }
            else
            {
                ?>
                <option value="<?php echo $i; ?>"><?php echo $tab_frequence_temps_affectation[$i]; ?></option>
                <?php
            }
        }
		?>
        </select>
        <?php
	}
	?></td></tr>
<tr>
    <td colspan="2"><br><b>Limite d'atelier : </b></td></tr>
<tr>
    <td class="label">Nombre d'atelier :</td>
    <td class="field">
    <?php 
	if(($type_forfait==2)||($type_forfait==3))
	{
		?>
    	<input type="number" step="1" min="0" value="<?php echo $nombre_atelier_forfait; ?>" name="nombre_atelier_forfait" style="width:60px;" />
        <?php
	}
	else
	{
		?>
        <input disabled=true type="number" step="1" min="0" value="<?php echo $nombre_atelier_forfait; ?>" name="nombre_atelier_forfait" style="width:60px;" />
        <?php
	}
	?></td></tr>
<tr>
    <td colspan="2"><br><b>Affectation occasionnel : </b></td></tr>
<tr>
    <td class="label">Nombre de minutes :</td>
    <td class="field">
    <?php 
	if($type_forfait==4)
	{
		?>
    	<input type="number" step="1" min="0" value="<?php echo $temps_affectation_occasionnel; ?>" name="temps_affectation_occasionnel" style="width:60px;" />
        <?php
	}
	else
	{
		?>
        <input disabled=true type="number" step="1" min="0" value="<?php echo $temps_affectation_occasionnel; ?>" name="temps_affectation_occasionnel" style="width:60px;" />
        <?php
	}
	?></td></tr>
    
    <!-------------------------------------------------------------------------------------------------------------------------------->

<tr>
    <td colspan="2"><br><b>Espaces li&eacute;s : </b></td></tr> 
<tr>
    <td class="label">Espaces o&ugrave; le forfait sera propos&eacute; : </td>
    <td class="field"> 
    <table>  
        <tr class="list_title">
        	<td width="200">Nom</td><td width="60">Ajouter</td></tr>
            <?php
			$nombreespacesansforfait=0;
            $resultespace=getAllEspaceConfigForf();
			if (FALSE != $resultespace)
			{
				$nbespace=mysqli_num_rows($resultespace);
				$resultespacelier=getAllRelForfaitEspace($id_forfait);
				$rowespacelier=mysqli_fetch_array($resultespacelier);
				if($nbespace==0)
				{
					echo "<tr class=\"list\">
							<td colspan=\"2\" align=\"center\">Aucun Espace disponible</td>
						</tr>";
				}
				else if($nbespace>0)
				{
					for ($i=1 ; $i<=$nbespace ; $i++)
					{
						$rowespace=mysqli_fetch_array($resultespace);
						if($rowespace["activation_forfait"]==1)
						{
							if(strcasecmp($rowespace["nom_espace"],$rowespacelier["nom_espace"])==0)
							{
								echo "<tr class=\"list\">
											<td width=\"200\">".$rowespace["nom_espace"]."</td>
											<td width=\"60\"><input type=\"checkbox\" style=\"width:20px;\" id=\"checkbox_espace\" name=\"espace".$rowespace["id_espace"]."\" value=\"1\" checked=\"checked\"></td>
											</tr>";
								$rowespacelier=mysqli_fetch_array($resultespacelier);
							}
							else
							{
								echo "<tr class=\"list\">
											<td width=\"200\">".$rowespace["nom_espace"]."</td>
											<td width=\"60\"><input type=\"checkbox\" style=\"width:20px;\" id=\"checkbox_espace\" name=\"espace".$rowespace["id_espace"]."\" value=\"1\"></td>
											</tr>";
							}
						}
						else
						{
							$nombreespacesansforfait++;
							if(strcasecmp($rowespace["nom_espace"],$rowespacelier["nom_espace"])==0)
							{
								$resultforfesp=delForfaitEspace($id_forfait, $rowespace["id_espace"]);
								$rowespacelier=mysqli_fetch_array($resultespacelier);
							}
						}
					}
				}
			}
			else
			{
				echo "<tr class=\"list\">
							<td colspan=\"2\" align=\"center\">Aucun Espace disponible</td>
						</tr>";
			}
			if($nombreespacesansforfait==$nbespace)
			{
				echo "<tr class=\"list\">
							<td colspan=\"2\" align=\"center\">Aucun Espace disponible</td>
						</tr>";
			}
			?></table>
    <!-------------------------------------------------------------------------------------------------------------------------------->
<tr>
   	<td colspan="2" align="center"><br>
    	
    <?php 
	if(($type_forfait==1)||($type_forfait==2)||($type_forfait==3)||($type_forfait==4))
	{
		?>
    	<input type="submit" value="<?php echo $label_bouton ;?>" name="submit">
        <?php
	}
	else
	{
		?>
        <input disabled=true type="submit" value="<?php echo $label_bouton ;?>" name="submit">
        <?php
	}
	?></td></tr> 
	</form>
 </table>