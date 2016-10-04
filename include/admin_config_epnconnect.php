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
 

  include/admin_configuration_epnconnect.php V0.1
*/

// Configuration des options du logiciel

if ($_GET["mess"] == "ok")
{
  echo getError(14) ;
}
else if ($_GET["mess"] != "")
{
  echo getError($_GET["mess"]) ;
}

//si changment d'epn
$epn_r=$_GET['epnr'];
if (isset($epn_r)){
	$epn=$epn_r;
}
$act=$_GET["act"];

// configuration des options du logiciel --------------------------------------------
//par défaut l'epn dont la session est ouverte


if($act==0){
//modification d'une config existante
	$epn=$_SESSION["idepn"];
	$posturl="index.php?a=25&act=0&idepn=".$epn;
	$bouton="valider les modifications";
		
		
}else{
// creation d'une nouvelle config
	$epn=$epn_r;
	//tester si pas déjà entré puis charger la config !!
	//debug(testconfigepn($epn));
	if(FALSE==testconfigepn($epn)){	
		$posturl="index.php?a=25&act=1&idepn=".$epn;
		$bouton="cr&eacute;er la configuration";
	}else{
		$posturl="index.php?a=25&act=0&idepn=".$epn;
		$bouton="valider les modifications";
	}
}

$row=getConfig_logiciel($epn);
$rowforfait=getActivationForfaitEpn($epn);

// Choix de l'epn   -------------------------------------
$espaces=getAllEPN();


?>


<!-- DIV accès direct aux autres paramètres-->

 <div class="box"><div class="box-header">
			<h3 class="box-title">Param&egrave;trages</h3>
		</div>
		<div class="box-body">
			
			<?php 
			//debug($_GET["a"]);
			echo configBut($_GET["a"]) ;
		
			?>
			
		</div><!-- /.box-body -->
</div><!-- /.box -->

<div class="row">
	<!-- Colonne de gauche -->
	<div class="col-md-4">
 <div class="box"><div class="box-header"><h3 class="box-title">Installer sur les postes clients</h3></div>
 <div class="box-body">
T&eacute;l&eacute;charger le logiciel EPN-Connect en cliquant sur le lien ci-dessous :<br />
<a href="./epnconnect/EPN-Connectv1.0.zip">T&eacute;l&eacute;charger EPN-Connect</a>
</div></div>

	<!-- NOM ESPACE -->	
 <div class="box box-warning"><div class="box-header"><h3 class="box-title">Choisissez l'espace</h3></div>
	<div class="box-body"><form action="index.php?a=25" method="post" role="form">
	<div class="form-group">
		<select name="epn_r" class="form-control" >
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
		<div class="box-footer"><input type="hidden" name="form" value="1">
			<button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</div>
		
	
</form></div></div>



<!-- activation mode console ou non -->
	<div class="box box-warning">
		<div class="box-header"><h3 class="box-title">Activation du mode console</h3></div>
	<div class="box-body">
	<p class="text-light-blue">L'activation du mode console permet l'autoaffection des postes avec l'epnconnect. Si vous ne pouvez pas utiliser cet outil (si votre serveur n'est pas local par exemple), vous n'aurez pas besoin de la console, elle disparait du menu.
	Cet outil est activable &agrave; tout moment dans cette page, cela n'affectera pas les statistiques !</p>
	<form method="post" action="index.php?a=25">
<?php
$consolemode=$rowforfait["activer_console"];
switch ($consolemode)
        {
            case 0:
                 $sel1="checked=\"checked\"" ;
                 $sel2="";
				
            break;
            case 1:       
                 $sel1="" ;
                 $sel2="checked=\"checked\"";
				 
            break;
		}
	?>
<input type="radio"  value="0" name="console" <?php echo $sel1; ?>> Non &nbsp;
<input type="radio"  value="1" name="console" <?php echo $sel2; ?>> Oui

	</div>
	<div class="box-footer">
				<input type="hidden" name="form" value="4">
				<input type="hidden" name="epn_r" value="<?php echo $epn; ?>">
				<button type="submit" value="Valider" name="submit" class="btn btn-primary">Modifier</div>
		</form>
	
	</div>
	
</div>


<!-- Colonne de droite -->
	<div class="col-md-8">
	
<div class="box"><div class="box-header"><h3 class="box-title">Options du logiciel EPN-Connect </h3></div>
<div class="box-body">
<form action="<?php echo $posturl; ?>" method="post" role="form">
	
<div class="form-group"><label> Validez les options ci-dessous pour activer les fonctionnalit&eacute;s d'EPN-Connect</label></div>
	<input type="hidden" name="idconfig" value="<?php echo $row['id_config_logiciel']; ?>">
	<input type="hidden" name="epn" value="<?php echo $epn ;?>">
	<div class="form-group"><?php
		if($row["page_inscription_logiciel"]==1)
		{
			?>
        	<input type="checkbox" name="insclog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="insclog" value="1">
            <?php
		}
        ?>Affichage de la page d'inscription
        </div>
	<div class="form-group">
       <?php
		if($row["page_renseignement_logiciel"]==1)
		{
			?>
        	<input type="checkbox" name="renslog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="renslog" value="1">
            <?php
		}
        ?>Affichage de la page de renseignements
        </div>
    <div class="form-group">
       
        <?php
		if($row["bloquage_touche_logiciel"]==1)
		{
			?>
        	<input type="checkbox" name="bloclog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="bloclog" value="1">
            <?php
		}
        ?>Arret du logiciel par combinaison de touches
      </div>
   <div class="form-group">
       
        <?php
		if($row["affichage_temps_logiciel"]==1)
		{
			?>
        	<input type="checkbox" name="tempslog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="tempslog" value="1">
            <?php
		}
        ?>Affichage temps restant
        </div>
     <div class="form-group">
       <?php
		if($row["deconnexion_auto_logiciel"]==1)
		{
			?>
        	<input type="checkbox" name="decouselog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="decouselog" value="1">
            <?php
		}
        ?>
		D&eacute;connexion automatique
       </div>
	   <div class="form-group">
       <?php
		if($row["fermeture_session_auto"]==1)
		{
			?>
        	<input type="checkbox" name="fermersessionlog" value="1" checked="checked">
            <?php
		}
		else
		{
			?>
        	<input type="checkbox" name="fermersessionlog" value="1">
            <?php
		}
        ?>
		Fermeture automatique de la session Windows
       </div>
			<div class="form-group"><label>Activer les forfaits consultation dans l'epn ? </label>
		 <?php 
				 switch ($rowforfait["activation_forfait"])
        {
            case 0:
                 $self1="checked=\"checked\"" ;
                 $self2="";
				
            break;
            case 1:       
                 $self1="" ;
                 $self2="checked=\"checked\"";
				 
            break;
					}
				?>
				 
				 
	
		 <div class="radio"> <label><input type="radio" name="forfait" value="1"   <?php echo $self2; ?>>oui<label>
												<label><input type="radio" name="forfait" value="0"  <?php echo $self1; ?>>non<label></div></div> 
			 
			 
    
		<h4>Autres Options</h4>
		<?php 
		switch ($rowforfait["inscription_usagers_auto"])
		{
            case 0:
                 $seli1="checked=\"checked\"" ;
                 $seli2="";
            break;
            case 1:       
                 $seli1="" ;
                 $seli2="checked=\"checked\"";
				 
            break;
					}
		?>
		<div class="form-group"><label>Activer l'inscription automatique par les adh&eacute;rents ? </label>
			 <div class="radio"> <label><input type="radio" name="inscrip_auto" value="1"   <?php echo $seli2; ?>>oui<label>
														<label><input type="radio" name="inscrip_auto" value="0"  <?php echo $seli1; ?>>non<label></div></div>
			
		<div class="form-group"><label>Si non, &eacute;crivez le message :</label><textarea class="form-control" rows="3" name="message_inscrip"><?php echo $rowforfait["message_inscription"]; ?></textarea>
			</div>
			 
		</div>
	<div class="box-footer"><input type="hidden" name="form" value="2">
	<input type="submit" name="submit" value="Valider" class="btn btn-success"></div>

</form>

</div><!-- /box -->

</div></div><!-- /col /row -->


