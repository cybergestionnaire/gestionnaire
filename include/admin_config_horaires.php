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
 

  include/admin_config.php V0.1
*/

// Configuration de l'espace

if ($_GET["mess"] == "ok")
{
  echo '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Mise &agrave; jour effectu&eacute;e</div>';
  
}
// chargement des valeurs pour l'epn par d&eacute;faut
$epn=$_SESSION['idepn'];
//si changment d'epn
$epn_r=$_GET['epnr'];
if (isset($epn_r)){
	$epn=$epn_r;
}

// Choix de l'epn   -------------------------------------
$espaces=getAllEPN();
//debug($epn);
$dureearray=array("30"=>"30 min", "60"=>"1 heure", "90"=>"1h30", "120"=>"2 heures");


$mesno= $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

?>

<!-- DIV acc&egrave;s direct aux autres param&egrave;tres-->
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

<div class="row">
	<!-- Colonne de gauche -->
	<div class="col-md-6">
	
<!-- NOM ESPACE -->	
<form action="index.php?a=42" method="post" role="form">	
 <div class="box box-warning"><div class="box-header"><h3 class="box-title">Les horaires de l'espace choisi</h3></div>
	<div class="box-body">
	<div class="form-group"><label>Choisissez l'espace :</label>
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
		<div class="box-footer">
			<input type="hidden" name="form" value="1">
			<button type="submit" name="submit" value="Valider" class="btn btn-primary">Changer</div>
		
	</div>
</form></div>





<!-- MODULE HORAIRES-->

 <div class="box box-warning">
	<div class="box-header">
		<h3 class="box-title">Horaires d'ouverture</h3></div>
	<div class="box-body no-padding">

<?php

  $dayArray = array("","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche") ;
  
  if ($_GET["mess"] == "Hwrong")
   {
     echo getError(15) ;
   }
  $table =" <table class=\"table\">
  <form method=\"post\" action=\"index.php?a=42\">\r\n
          <tr>\r\n
              <th style=\"width: 10px\">&nbsp;</th>
			  <th>TRANCHE 1 (Matin)</th>
			  <th>TRANCHE 2 (Apres-midi)</th></tr>\r\n" ;

  // affichage
  for ($i = 1 ; $i < 8 ; $i++)
  {
    if ($i!=$_GET["dayline"])
      $color = "#EEEEEE";
    else
      $color = "#CC9999";
    $row = getHoraire($i,$epn) ;
    $table .= "<tr><td>".$dayArray[$i]."</td>";
    $table .= "<td>de " ;
    // H1 begin
    // tableau des heures
       $H = "" ;
       $H .= "<option value=\"\"></option>\r\n" ;
     // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
       for ($a = 6 ; $a <= 23 ; $a++)
       {
           if (strlen($a)<2)
           $a = "0".$a ;
           $M = "";
           for ($b = 0 ; $b <= 59 ;$b=$b+15)
           {
               if ($row["hor1_begin_horaire"] == convertHoraire($a."h".$b))
                  $select="selected" ;
               else
                  $select="";

               if ($b == 0 )
                  $b  ="00" ;
               $H .= "<option value=\"".convertHoraire($a."h".$b)."\" ".$select.">".$a."h".$b."</option>\r\n" ;
           }
       }

    $table .= "<select  name=\"".$i."-h1begin\">".$H."</select> <b>&agrave;</b> " ;
    // H1 end
    // tableau des heures
       $H = "" ;
       $H .= "<option value=\"\"></option>\r\n" ;
      // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
       for ($a = 6 ; $a <= 23 ; $a++)
       {
           if (strlen($a)<2)
           $a = "0".$a ;
           $M = "";
           for ($b = 0 ; $b <= 59 ;$b=$b+15)
           {
               if ($row["hor1_end_horaire"] == convertHoraire($a."h".$b))
                  $select="selected" ;
               else
                  $select="";

               if ($b == 0 )
                  $b  ="00" ;
               $H .= "<option value=\"".convertHoraire($a."h".$b)."\" ".$select.">".$a."h".$b."</option>\r\n" ;
           }
       }

    $table .= "<select name=\"".$i."-h1end\">".$H."</select>" ;
    $table .= "</td><td class=\"selH\"><b>de</b> " ;
    // H2 begin
    // tableau des heures
       $H = "" ;
       $H .= "<option value=\"\"></option>\r\n" ;
      // $H .= "<option value=\"0\">Ferm&eacute;</option>\r\n" ;
       for ($a = 6 ; $a <= 23 ; $a++)
       {
           if (strlen($a)<2)
           $a = "0".$a ;
           $M = "";
           for ($b = 0 ; $b <= 59 ;$b=$b+15)
           {
               if ($row["hor2_begin_horaire"] == convertHoraire($a."h".$b))
                  $select="selected" ;
               else
                  $select="";

               if ($b == 0 )
                  $b  ="00" ;
               $H .= "<option value=\"".convertHoraire($a."h".$b)."\" ".$select.">".$a."h".$b."</option>\r\n" ;
           }
       }

    $table .= "<select name=\"".$i."-h2begin\">".$H."</select> <b>&agrave;</b> " ;
    // H2 end
    // tableau des heures
       $H = "" ;
       $H .= "<option value=\"\"></option>\r\n" ;
//            $H .= "<option value=\"\">Ferm&eacute;</option>\r\n" ;
       for ($a = 6 ; $a <= 23 ; $a++)
       {
           if (strlen($a)<2)
           $a = "0".$a ;
           $M = "";
           for ($b = 0 ; $b <= 59 ;$b=$b+15)
           {
               if ($row["hor2_end_horaire"] == convertHoraire($a."h".$b))
                  $select="selected" ;
               else
                  $select="";

               if ($b == 0 )
                  $b  ="00" ;
               $H .= "<option value=\"".convertHoraire($a."h".$b)."\" ".$select.">".$a."h".$b."</option>\r\n" ;
           }
       }

    $table .= "<select name=\"".$i."-h2end\">".$H."</select>" ;
    $table .= "</td></tr>" ;
  }
  $table .= "<tr><td colspan=\"3\"><span style=\"font-size:10px;\">* La modification des horaires peut entrainer des probl&egrave;mes au niveau des reservations de machines et des statistiques d'occupation.</span>  <div class=\"box-footer\">
				<input type=\"hidden\" name=\"form\" value=\"2\">
				<input type=\"hidden\" name=\"epn_r\" value=\"".$epn."\">
				<button type=\"submit\" value=\"Valider * \" name=\"submit\" class=\"btn btn-primary\">Valider *
  </div></form></td></tr>" ;
  $table .= "</table></div>" ;

  echo $table ;
  ?>
</div>
<?php
// tranche de reservation
?>

</div><!--/.col (left) -->
                       
<!-- right column -->
<div class="col-md-6">	
	 
	<div class="box box-warning">
		<div class="box-header"><h3 class="box-title">Param&egrave;trage des r&eacute;servations</h3></div>
	<div class="box-body">
		<form method="post" action="index.php?a=42">
		<!-- Param&eacute;trages du planning des r&eacute;servations -->
		<div class="form-group">
			<label>Unit&eacute; de temps (min): <small class="badge bg-blue" data-toggle="tooltip" title="Pour le planning des r&eacute;servations, la plus petite portion de temps &agrave; accorder par tranche de 5, 10, x minutes..."><i class="fa fa-info"></i></small></label>
				<input type="text" value="<?php echo getConfig("unit_config","unit_default_config",$epn) ;?>" name="unit" class="form-control" placeholder="Min">
			<label>Dur&eacute;e maximum (min): <small class="badge bg-blue" data-toggle="tooltip" title="Dur&eacute;e maximum de la r&eacute;servation d'un poste par personne "><i class="fa fa-info"></i></small></label>
				<input type="text" value="<?php echo getConfig("maxtime_config","maxtime_default_config",$epn) ;?>" name="maxtime" class="form-control" placeholder="Min">
			</div>
			
		<!-- Param&eacute;trages de la r&eacute;servation rapide -->
			<div class="form-group">
			<label>Activer la r&eacute;servation rapide ?</label>
				<?php
				$resamode=getConfigConsole($epn,"resarapide");
				$dureerr=getConfig("duree_resarapide","unit_default_config",$epn);
			
				switch ($resamode)
								{
										case '0':
												 $sel1="checked=\"checked\"" ;
												 $sel2="";
								
										break;
										case '1':       
												 $sel1="" ;
												 $sel2="checked=\"checked\"";
								 
										break;
						}
					?>
				<input type="radio"  value="0" name="resarapide" <?php echo $sel1; ?>> Non &nbsp;
				<input type="radio"  value="1" name="resarapide" <?php echo $sel2; ?>> Oui
				</div>
		
			<div class="form-group">
			<label>S&eacute;lectionnez la dur&eacute;e par d&eacute;faut pour la r&eacute;servation rapide</label>
				<select class="form-control" name="duree_resarapide">
				<?php
				foreach ($dureearray AS $key=>$value)
				{
					if ($dureerr == $key)
					{
						echo "<option value=\"".$key."\" selected>".$value."</option>";
					}
					else
					{
						echo "<option value=\"".$key."\">".$value."</option>";
					}
				}
				
			?></select>
				
			</div>
		</div>
		<div class="box-footer">
				<input type="hidden" name="form" value="3">
				<input type="hidden" name="epn_r" value="<?php echo $epn; ?>">
				<button type="submit" value="Valider" name="submit" class="btn btn-primary">Valider</div>
		</form>
	
	</div>
	

	
<!-- FERMETURES ANNUELLLES-->

 <div class="box box-warning">
<div class="box-header">
	<h3 class="box-title">Selection des jours ou l'espace sera ferm&eacute; en <?php echo date("Y");?> :</h3></div>
<div class="box-body">

<form method="get" action="index.php?a=42" role="form">
<input type="hidden" name="a" value="42">
<input type="hidden" name="epnr" value="<?php echo $epn; ?>">
     <div class="form-group"><label>S&eacute;lectionnez la p&eacute;riode &agrave; afficher:</label>
     <select name="display" class="form-control">                               
         <option value="">Mois en cours</option>
         <option value="all">Vue compl&egrave;te sur l'ann&eacute;e <?php echo date("Y");?></option>
         <option value="1">Janvier <?php echo date("Y");?></option>
         <option value="2">F&eacute;vrier <?php echo date("Y");?></option>
         <option value="3">Mars <?php echo date("Y");?></option>
         <option value="4">Avril <?php echo date("Y");?></option>
         <option value="5">Mai <?php echo date("Y");?></option>
         <option value="6">Juin <?php echo date("Y");?></option>
         <option value ="7">Juillet <?php echo date("Y");?></option>
         <option value="8">Aout <?php echo date("Y");?></option>
         <option value="9">Septembre <?php echo date("Y");?></option>
         <option value="10">Octobre <?php echo date("Y");?></option>
         <option value="11">Novembre <?php echo date("Y");?></option>
         <option value="12">D&eacute;cembre <?php echo date("Y");?></option>
     </select>&nbsp;<input type="submit" name="submit" value="ok" class="alt_btn">
	<br><div style="font-size:10px;">Cliquez sur un jour pour le rendre feri&eacute;(inaccessible au public) et vice versa pour le rendre ouvr&eacute;.</div>
    </div>
	
</form>


<?php
if(FALSE!=isset($_GET["idday"]))         // mise a jour d'un jour
{
$check=checkDayOpen(intval($_GET["idday"]),intval(date("Y")),$epn);
  // debug(is_int(intval($_GET["idday"])));
 //debug(updateDay(intval($_GET["idday"]),intval(date("Y")),$epn)) ;
 

 if ($check==0){
	insertJourFerie(intval($_GET["idday"]),intval(date("Y")),$epn);
 }else{
	deleteJourFerie($check);
 }

}

switch ($_GET["display"])
{
  case "all": // affichage par an
       for ($i=1 ; $i<13 ;$i++)
       {
         echo "<span style=\"float:left;width:32%;height:300px;\">".getCalendarClose(date("Y"),$i,$epn)."&nbsp;&nbsp;&nbsp;</span>" ;
       }
  break;
  default: //affichage du mois
      if ($_GET["display"]!="")
         $month = $_GET["display"] ;
      else
         $month = date("n");
         echo getCalendarClose(date("Y"),$month,$epn) ;
  break;
}
?>

</div>
</div>
