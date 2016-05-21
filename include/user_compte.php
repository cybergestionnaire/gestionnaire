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
 

 include/user_compte.php V0.1
*/

// Page de gestion du compte d'un utilisateur
$row = getUser($_SESSION["iduser"]);
//recuperation des tarifs categorieTarif(2)=adhesion
$tarifs=getTarifsbyCat(2);
//recuperation des tarifs pour la consultation internet
$tariftemps=getTarifsTemps();
$lasteresa=getLastResaUser($row["id_user"]);
if($lasteresa==FALSE){$lasteresa="NC";}		
//TARIF CONSULTATION
	$tarifTemps= getForfaitConsult($row["id_user"]);
	$min=$tab_unite_temps_affectation[$tarifTemps["unite_temps_affectation"]];
	$tarifreferencetemps= $tarifTemps["nombre_temps_affectation"]*$min;
if(TRUE==$tarifTemps){
						
	//modifier le temps comptabilise en fonction de la frequence_temps_affectation
	if($tarifTemps["frequence_temps_affectation"]==1){ 
			//par jour
			$date1=date('Y-m-d');
			$date2=$date1;
	}else if($tarifTemps["frequence_temps_affectation"]==2){ 
			//par semaine;
			$semaine=get_lundi_dimanche_from_week(date('W'));
			$date1=strftime("%Y-%m-%d",$semaine[0]);
			$date2=strftime("%Y-%m-%d",$semaine[1]);
	
	}else if($tarifTemps["frequence_temps_affectation"]==3){ 
			//par mois
			$date1=date('Y-m')."-01";
			$date2=date('Y-m')."-31";
	}
		
	//debug($tarifreferencetemps);
		$resautilise = getTempsCredit($row["id_user"],$date1,$date2);
		$restant=$tarifreferencetemps-$resautilise['util'];
		$rapport=round(($restant/$tarifreferencetemps)*100);
	}

if (FALSE != isset($row["id_user"])){
				if ($row["sexe_user"] =="F"){
				$imgprofile='img/avatar/female.png' ;
				} else {
				$imgprofile="img/avatar/male.png" ;
				}
			}


//Affichage d'une erreur si erreur il y a
echo $mess ;

?>
<div class="row">
  <div class="col-md-3">
<div class="box box-primary">
  <div class="box-body box-profile">
    <img class="profile-user-img img-responsive img-circle" src="<?php echo $imgprofile; ?>" alt="User profile picture">
		<h3 class="profile-username text-center"><?php echo $row["nom_user"] ; ?>&nbsp;<?php echo $row["prenom_user"] ; ?></h3>
			<p class="text-muted text-center">Inscrit(e) depuis le <?php echo dateFr($row["date_insc_user"]) ; ?></p>
		
		<hr>
		<strong><i class="fa fa-map-marker margin-r-5"></i> Adresse</strong>
            <p class="text-muted"><?php echo $row["adresse_user"] ; ?> <br><?php echo getCity($row["ville_user"]) ; ?></p>
		<hr>
		<strong><i class="fa fa-pencil margin-r-5"></i> Donn&eacute;es personnelles</strong>
            <p class="text-muted">n&eacute;(e) le <?php echo $row["jour_naissance_user"]." ".getMonth($row["mois_naissance_user"])." ".$row["annee_naissance_user"] ; ?></p>
						<p class="text-muted"><i class="fa fa-phone margin-r-5"></i> <?php echo $row["tel_user"] ; ?></p>
						<p class="text-muted"><i class="fa fa-envelope margin-r-5"></i><?php echo $row["mail_user"] ; ?></p>
		
			
	
</div></div>

</div>

<div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#activity" data-toggle="tab">Activit&eacute;</a></li>
                  <li><a href="#settings" data-toggle="tab">Param&egrave;tres</a></li>
                </ul>
								
                <div class="tab-content">
	
	
	 <div class="active tab-pane" id="activity">
		 <dl class="dl-horizontal">
         <dt>Derni&egrave;re consultation </dt><dd>le <?php echo getDayfr($lasteresa); ?></dd>
		<dt>Tarif / Temps restant<dt><dd><?php echo	'<span class="badge bg-blue">'.$tarifTemps["nom_forfait"].'</span>&nbsp;&nbsp; '.getTime($restant); ?></dd>
		<dt>Adh&eacute;sion </dt><dd>A renouveller le <?php echo getDayfr($row["dateRen_user"]); ?></dd>
		<dt>Au tarif de </dt><dd><?php echo $tarifs[$row["tarif_user"]]; ?></dd>
		<dt>Newsletter </dt><dd><?php if($row["newsletter_user"]==1) {
		echo "Je suis abonn&eacute;" ;
		} else{
		echo "Je ne suis pas abonn&eacute; ";
		} ?></dd>
		</dl>
	 
   </div>
	
	<div class="tab-pane" id="settings">
                  
	<form method="post" action="index.php?m=2" class="form-horizontal">
		<div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Num&eacute;ro de carte </label><div class="col-sm-10"><input type="email" class="form-control" id="inputName" value="<?php echo $row["login_user"] ; ?>" disabled/></div>
    </div>
		
		<div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Mot de passe </label><div class="col-sm-10"><input type="email" class="form-control" id="inputName" value="********" disabled/></div>
    </div>
	 
	  <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Modifiez votre mot de passe </label><div class="col-sm-10"><input type="password" class="form-control"  name="pass1"></div>
    </div>
	  
	<div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Confirmation </label><div class="col-sm-10"><input type="password" class="form-control"  name="pass2"></div>
    </div>
	
	<div class="form-group">
        <label  class="col-sm-2 control-label">Abonnement newsletter </label>
		 <div class="col-sm-10"><div class="radio icheck">
		<label>
		<?php 
		
		if($row["newsletter_user"]==1){ 
			echo '<input type="radio" name="newsletter" value="0"  /> non
			<input type="radio" name="newsletter" value="1"  checked /> oui ';
		}else{
			echo '<input type="radio" name="newsletter" value="0"  checked /> non
			<input type="radio" name="newsletter" value="1"  /> oui ';
		} ?>
		
		
		
		</div></div>
    </div>
	
  <div class="box-footer"><input type="submit" value="modifier" name="submit" class="btn btn-primary"></div></form>
	
	 </div><!-- /.tab-pane -->
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->
</div><!-- /.col -->

</div>