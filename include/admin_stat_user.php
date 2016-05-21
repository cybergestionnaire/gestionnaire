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
  include/admin_stat.php V0.1
*/
if ($mess !="")
{
  echo $mess;
}

// Fichier de statistiques de l'application

// chargement des valeurs pour l'epn par défaut
$epn=$_SESSION['idepn'];
//si changment d'epn
 if (TRUE == isset($_POST['modifepn']))
 {
     $epn=$_POST['Pepn'];
	
  }

// Choix de l'epn   -------------------------------------
$espaces=getAllEPN();
	

// repartition homme/femme ------------
$nbH = statSexe("H",$epn);
$nbF = statSexe("F",$epn); 

// Total d'adherents
$nbTotal = $nbF+$nbH ;

// repartition par tranche d'age -------
$nbTr1 =  statTranche(0,6,$nbTotal,$epn);
$nbTr2 =  statTranche(7,13,$nbTotal,$epn);
$nbTr3 =  statTranche(14,17,$nbTotal,$epn);
$nbTr4 =  statTranche(18,25,$nbTotal,$epn);
$nbTr5 =  statTranche(26,45,$nbTotal,$epn);
$nbTr6 =  statTranche(46,65,$nbTotal,$epn);
$nbTr7 =  statTranche(66,75,$nbTotal,$epn);
$nbTr8 =  statTranche(76,110,$nbTotal,$epn);

$month = date('m') ;
//verification que le dossier images des stats existe.
$dossierimg = "img/chart/".$year ;
if(!is_dir($dossierimg)){
   mkdir($dossierimg);
}

// Affichage
?>

<!-- NOM ESPACE -->	


 <div class="row"><div class="col-md-6">
<!-- DIV accès direct aux autres paramètres-->
 <div class="box">
		<div class="box-header">
			<h3 class="box-title">Statistiques</h3>
			<div class="box-tools"><form method="post" role="form">
			<div class="input-group">
			
			 <select name="Pepn"  class="form-control pull-right" style="width: 200px;">
       	<?php
        foreach ($espaces AS $key=>$value)
        {
            if ($epn == $key)
            {
                echo "<option  value=\"".$key."\" selected>".$value."</option>";
            }
            else
            {
                echo "<option  value=\"".$key."\">".$value."</option>";
            }
        }
		
    ?></select>
		<div class="input-group-btn"><button type="submit" value="Rafraichir"  name="modifepn" class="btn btn-default" style="height: 34px;"><i class="fa fa-repeat"></i></button></div>
	</div></form>
	</div>
		</div>
		<div class="box-body">
			<a class="btn btn-app disabled" href="index.php?a=5&b=1"><i class="fa fa-users"></i>Adhérents<a>
			<a class="btn btn-app" href="index.php?a=5&b=2" /><i class="fa fa-clock-o"></i>Réservations</a>
			<a class="btn btn-app" href="index.php?a=5&b=3"><i class="fa fa-print"></i>Impressions</a>
			<a class="btn btn-app" href="index.php?a=5&b=5" /><i class="fa fa-ticket" ></i>Sessions</a>
			<a class="btn btn-app" href="index.php?a=5&b=4" /><i class="fa fa-keyboard-o" ></i>Ateliers</a>
			
			
		</div><!-- /.box-body -->
</div><!-- /.box -->   

<?php
if (getadherenttotal($epn)){ // condition pour affichage si pas d'adherents, message !
?>
   
 <div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">R&eacute;partition Homme / Femme (<?php echo getCyberName($epn);?>)</h3></div>
	<div class="box-body"> <dl class="dl-horizontal">
		
		<dt>Actifs</dt><dd><span class="text-red"><?php echo countUser(2) ;?></span></dd>
		<dt>Inactifs</dt><dd><?php echo countUser(3) ;?></dd>
		<dt>Archivés</dt><dd><?php echo countUser(4) ;?></dd></dl>
				
				
				
		<div class="statBar">
			<div class="statText">Hommes</div>
			<div class="statBarContainPurple">
				<div style="width:<?php echo getPourcent($nbH,$nbTotal); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbH,$nbTotal); ?></div>
			</div></div>
			<div class="clear"></div>
		<div class="statBar">
			<div class="statText">Femmes</div>
			<div class="statBarContainPurple">
				<div style="width:<?php echo getPourcent($nbF,$nbTotal); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbF,$nbTotal); ?></div>
		</div></div><div class="clear"></div>
		<div class="spacer"></div>	
</div></div>



<!-- REPARTITION PAR TRANCHE D AGE-->
                  
 <div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">R&eacute;partition par tranche d'&acirc;ge (<?php echo getCyberName($epn)?>)</h3></div>
	<div class="box-body">
	
		<div class="statBar">
			<div class="statText">0  &agrave;   6 :</div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr1,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr1,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">7  &agrave;  11 : </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr2,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr2,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">12  &agrave;  17 :  </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr3,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr3,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">18  &agrave;  25 : </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr4,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr4,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">25  &agrave;  45 :  </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr5,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr5,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">46  &agrave;  65 : </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr6,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr6,$nbTotal); ?></div></div>
		</div>	<div class="clear"></div>
		<div class="statBar">
			<div class="statText">66  &agrave; 75 :  </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr7,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr7,$nbTotal); ?></div></div>
		</div><div class="clear"></div>
		<div class="statBar">
			<div class="statText">75  et + :  </div><div class="statBarContainDBlue">
			<div style="width:<?php echo getPourcent($nbTr7,$nbTotal); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbTr8,$nbTotal); ?></div></div>
		</div><div class="clear"></div>
</div></div>

<!-- REPARTITION PAR VILLE-->
<div class="nav-tabs-custom">
   <ul class="nav nav-tabs pull-right">
			<li class="active"><a href="#tab_1-1" data-toggle="tab">Actifs / ville</a></li>
			<li><a href="#tab_2-2" data-toggle="tab">Total adh / ville</a></li>
			<li class="pull-left header">R&eacute;partition par ville</li>
     </ul>
  
  <div class="tab-content">
		<div class="tab-pane active" id="tab_1-1">
<?php
$town = getAllCityname();
$nbactifs=countUser(2);
$nbinactifs=countUser(3);
$nbtotalai=countUser(1);
//debug($nbtotalai);
foreach ($town AS $key => $value)
{
  $nbAdhCityactifs = statCity($key,1) ; // actifs
	$nbAdhCityinactifs= statCity($key,2) ; //inactifs
	$nbAdhCity=$nbAdhCityactifs+$nbAdhCityinactifs;

	//debug($nbAdhCity);
  if ($nbAdhCity >0)
  {
  ?>
  
  <div class="statBar">
			<div class="statTextVille"><?php echo $value ."  <small>(".$nbAdhCityactifs." adh)</small>";?> </div>
			<div class="statBarContainBlue">
					<div style="width:<?php echo getPourcent($nbAdhCityactifs,$nbtotalai); ?>" class="statBarPourcentGreen">&nbsp;<?php echo getPourcent($nbAdhCityactifs,$nbtotalai); ?></div>
				
			</div>
  	</div><div class="clear"></div>
<?php
    }
}



?>

</div>
<div class="tab-pane active" id="tab_2-2">
<?php
$town = getAllCityname();
$nbactifs=countUser(2);
$nbinactifs=countUser(3);
$nbtotalai=countUser(1);
//debug($nbtotalai);
foreach ($town AS $key => $value)
{
  $nbAdhCityactifs = statCity($key,1) ; // actifs
	$nbAdhCityinactifs= statCity($key,2) ; //inactifs
	$nbAdhCity=$nbAdhCityactifs+$nbAdhCityinactifs;

	//debug($nbAdhCity);
  if ($nbAdhCity >0)
  {
  ?>
  
  <div class="statBar">
			<div class="statTextVille"><?php echo $value ."  <small>(".$nbAdhCity." adh)</small>";?> </div>
			<div class="statBarContainBlue">
					<div style="width:<?php echo getPourcent($nbAdhCity,$nbtotalai); ?>" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbAdhCity,$nbtotalai); ?></div>
				
			</div>
  	</div><div class="clear"></div>
<?php
    }
}



?>

</div>




</div><!-- content -->


</div><!--/panel -->
</div><!-- /col 1 -->



<div class="col-md-6">

<!-- REPARTITION PAR CSP-->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">R&eacute;partition par CSP (<?php echo getCyberName($epn)?>)</h3></div>
<div class="box-body">
<?php
$csp = getAllCsp();
foreach ($csp AS $key => $value)
{
  $nbAdhCsp = statCsp($key,$epn) ;
  if ($nbAdhCsp >0)
  {
  ?>
 
  <div class="statBar">
			<div class="statTextVille"><?php echo $value;?></div>
			<div class="statBarContainPurple">
  <div style="width:<?php echo getPourcent($nbAdhCsp,$nbTotal); ?>" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbAdhCsp,$nbTotal); ?></div></div>
    </div><div class="clear"></div>
	
<?php
    }
}
?>
</div></div>


<!--repartition des inscrits par mois et par annee --->
<div class="box box-primary">
          <div class="box-header"><i class="fa fa-bar-chart-o"></i><h3 class="box-title">Nouveaux inscrits par mois (<?php echo getCyberName($epn)?>)</h3></div>
<div class="box-body"><table class="table"> 
				<thead><tr>
					<th></th>
					<th>Nouveaux inscrits</th>
					<th>Dont actifs</th></td></tr></thead></tbody>
<?php

for ($i=1 ; $i<= $month;++$i)
{
    $nbNewAdhActifs= statInscription($i,1,$epn);
	$nbNewAdhInactifs = statInscription($i,2,$epn);
	//debug($nbNewAdhInactifs);
	$totalNewadh=$nbNewAdhActifs+$nbNewAdhInactifs;
	//debug($nbNewAdh);
	echo '<tr><td >'.getMonth($i).'</td>
              <td >'.$totalNewadh.'</td>
              <td >'.$nbNewAdhActifs.' ('.getPourcent($nbNewAdhActifs,$nbTotal).')</td></tr>';
}
?>
</TBODY></table>
</div></div>


<?php 
	}else{
	 echo geterror(36);
	}
?>


</div></div><!-- /col /row -->
