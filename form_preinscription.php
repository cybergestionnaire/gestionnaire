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
 

  include/admin_form_user.php V0.1
*/

include("include/fonction.php");
include("include/fonction2.php");

include("post_inscrip.php");
    
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
		

// recupere les villes
$town0 = getAllCityname();
$town=array_put_to_position($town0, 'Autre commune', 0,'0' );



// retrouver les espaces
$espaces = getAllepn();

//recupere la csp -- Ajout
$profession = getAllCsp();

// type d'equipement defini
$equipementarray = array (
         0 => "Aucun &eacute;quipement",   
         1 => "Ordinateur",
         2 => "Tablette",
		 3 => "Smartphone",
		 4 => "T&eacute;l&eacute;vision connect&eacute;e",
		5 => " Internet &agrave; la maison (ADSL, satellite ou fibre)",
		6 => " Internet mobile (3G, 4G,...)",
		7 => "Pas de connexion Internet"
		);


		// type d'utilisation defini
$utilisationarray = array (
         0 => "Aucun Lieu",
         1 => "A la maison",   
         2 => "Au bureau ou &agrave; l'&eacute;cole",
         3 => "A la maison et au bureau ou &agrave; l'&eacute;cole"
);
		
		// type de connaissance defini
$connaissancearray = array (
         0 => "D&eacute;butant",   
         1 => "Interm&eacute;diaire",
         2 => "Confirm&eacute;"
);


//verifiations en cas d'envoi partiel avec fautes
if(isset($_SESSION['sauvegarde']))
{
    $_POST = $_SESSION["sauvegarde"] ;
    $sexe     =  $_POST["sexe"];
    $nom      =  trim($_POST["nom"]);
    $prenom   =  trim($_POST["prenom"]);
    $jour     =  $_POST["jour"];
    $mois     =  $_POST["mois"];
    $annee    =  $_POST["annee"];
    $adresse  =  stripslashes($_POST["adresse"]);
     
    $ville    =  $_POST["ville"];
    
    $tel      =  $_POST["tel"];
    $telport=$_POST["telport"];
    $mail     =  trim($_POST["mail"]);
    $epn=$_POST["epn"];
    $equipement=  $_POST["equipement"];
    $commune     =  stripslashes($_POST["commune"]);
    $pays=stripslashes($_POST["pays"]);
    $utilisation     =  $_POST["utilisation"];
    $connaissance     =  $_POST["connaissance"];
    $info     =  stripslashes($_POST["info"]);
    if($codepostal=="vide"){$codepostal="";}else{$codepostal=$_POST["codepostal"];}
    
	 unset($_SESSION['sauvegarde']);
}else{
	 $sexe     =  "H";
    $nom      = "";
    $prenom   = "";
    $jour     = "";
    $mois     = "";
    $annee    =  "";
    $adresse  = "";
    $csp="14";//non renseignee
    $ville    = 0;
    $tel      = "";
    $telport="";
    $mail     = "";
    $epn="";
    $equipement=[];
    $commune     ="";
    $codepostal="";
    $utilisation     = "";
    $connaissance     = "";
    $info     = "";
    $pays="";
}




//messages d'erreur du formulaire
if(isset($mess)){
echo $mess ;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CyberGestionnaire | Pr&eacute;inscription</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="template/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="template/dist/css/AdminLTE.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="template/plugins/iCheck/square/blue.css">

    
		
		<script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
	
 <body class="hold-transition register-page">
    <div class="register-box">
			<div class="register-logo"><?php echo getconfigname(); ?></div>
      <div class="register-logo">Pr&eacute;inscription</div>
		<p class="login-box-msg">Veuillez remplir toutes les cases marqu&eacute;es d'une &eacute;toile* et envoyer le formulaire.</p>
<!-- infos utilisateur -->
<form name="forminscription" method="post" action="" role="form">
<div class="row"> <div class="col-lg-6">
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Informations personnelles</h3></div>
	 <div class="box-body">


<input type="hidden" name="date_inscription" value="<?php echo date('Y-m-d');?>">

<div class="form-group"><label>Civilit&eacute; *:</label>
    
   
    	<div class="radio icheck"><input type="radio" name="sexe" value="H">&nbsp;Monsieur
    	<input type="radio" name="sexe" value="F">&nbsp;Madame</div>
  
    </div>
<div class="form-group"><label>Nom *:</label>
    <input type="text" name="nom" onChange="javascript:this.value=this.value.toUpperCase();" class="form-control" value="<?php echo $nom; ?>"></div>
<div class="form-group"><label>Pr&eacute;nom *:</label>
    <input type="text" name="prenom"  onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();" class="form-control" value="<?php echo $prenom; ?>"></div>

<div class="form-group"><label>Date de naissance *:</label>
	<select name="jour">
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
		<select name="annee">
        <?php
				$annee=date('Y');
        for ($a=1910 ; $a<date('Y') ; $a++)
        {
            if ($a == $annee)
            {
                echo "<option value=\"".$a."\" selected>".$a."</option>";
            }
            else
            {
                echo "<option value=\"".$a."\">".$a."</option>";
            }
        }
        ?>
    </select>
   </div>

<div class="form-group"><label>Adresse *:</label>
    <textarea name="adresse" class="form-control" value="<?php echo $adresse; ?>"></textarea></div>
		
<div class="form-group"><label>Ville *:</label>
		<select name="ville" class="form-control" >
		<?php
			foreach ($town AS $key=>$value)
			{
				if ($ville == $key)
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
   
   <div class="form-group"><label>Si autre commune, indiquez la ville :</label>
   <div class="row">	<div class="col-xs-5"><input type="text" name="codepostal"  class="form-control" placeholder="75000" value="<?php echo $codepostal; ?>"></div>
		<div class="col-xs-5"><input type="text" name="commune"  class="form-control" placeholder="Paris" value="<?php echo $commune; ?>"></div>
   </div>   </div>  
   <div class="form-group"><input type="text" name="pays"  class="form-control" placeholder="Pays" value="<?php echo $pays; ?>"></div>
		
		<div class="row">	
 <div class="col-xs-5"><label>T&eacute;l&eacute;phone Fixe :</label><input type="text" name="tel"  class="form-control" placeholder="012345678" value="<?php echo $tel; ?>"></div>
 <div class="col-xs-5"><label>T&eacute;l. Portable :</label><input type="text" name="telport"  class="form-control" placeholder="012345678" value="<?php echo $telport; ?>"></div>
 </div>


<div class="form-group"><label>E-Mail *:</label><input type="text" name="mail"  class="form-control" placeholder="identifiant@mail.com" value="<?php echo $mail; ?>"></div>
<div class="form-group"><label>Votre epn *:</label>
		<select name="epn" class="form-control" >
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

<div class="g-recaptcha" data-sitekey="6LcBzxUTAAAAAG1S5-Bl4wPns5aIRHEpB2yC9_vC"></div>

</div>
</div></div>
 
 <div class="col-lg-6">

<div class="box box-primary"><div class="box-header"><h3 class="box-title">Information diverses (optionnel) : </h3></div>
	<div class="box-body">
<div class="form-group">
		<label>De quoi &ecirc;tes-vous &eacute;quip&eacute; ?</label>
	 <?php
      for ($x=0;$x<8;$x++){
	
		if (in_array($x,$equipement))  { 
		$check = "checked"; 
		} else {
		$check = ''; 
		}
	?>	
		<div class="checkbox"><input type="checkbox" name="equipement[]" value="<?php echo $x; ?>"  <?php echo $check; ?> >&nbsp; <?php echo $equipementarray[$x]; ?></div>
<?php	}
    ?>
	</div>
	
	 <div class="form-group">
		<label>O&ugrave; utilisez-vous internet ?</label>
			 <?php
			foreach ($utilisationarray AS $keyutil=>$valueutil)
			{
			    if (strcmp ($utilisation,$keyutil)==0)
			    {
		       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=".$keyutil."  class=\"minimal\" checked>&nbsp;".$valueutil."  </label></div>";
			    }
			    else
			    {
		       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"utilisation\" value=".$keyutil." class=\"minimal\">&nbsp;".$valueutil." </label></div> ";
			    }
			}
		    ?>
	</div>
	
    	<div class="form-group"><label>Quelle est votre profession ?</label>
    	 <select name="csp" class="form-control">
	    <?php
		foreach ($profession AS $key=>$value)
		{
		    if ($csp == $key)
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
<div class="form-group"><label>Quel est votre niveau en informatique ?</label>

    <?php
         foreach ($connaissancearray AS $key=>$valuecon)
        {
            if (strcmp ($connaissance,$key)==0)
            {
       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=".$key." checked>&nbsp;".$valuecon."</label></div>";
            }
            else
            {
       			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"connaissance\" value=".$key.">&nbsp;".$valuecon."</label></div>";
            }
        }
    ?>
    </div>
<div class="form-group"><label>Information compl&eacute;mentaire ou message &agrave; l'animateur:</label><textarea name="info" class="form-control" rows="5" value="<?php echo $info; ?>"></textarea></div>



</div><!-- //box body-->
<div class="box-footer"><input type="submit" name="submit" class="btn btn-success" value="Envoyer le formulaire"></div>
 </div>
</form></div>


</div></div>
 <!-- jQuery 2.1.4 -->
    <script src="template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="template/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="template/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
</body>
</html>