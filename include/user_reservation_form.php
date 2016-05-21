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
 

  include/user_reservation_form.php V0.1
*/
// fonctions additionnelles

$semaine=get_lundi_dimanche_from_week(date('W'));
$date1=strftime("%Y-%m-%d",$semaine[0]);
$date2=strftime("%Y-%m-%d",$semaine[1]);
$epn=$_GET["idepn"];
 

// affichage de form de reservation
  if (FALSE!=is_numeric($_GET["idcomp"]))
  {
      // initialisation
      $step1 = 'step';
      $step2 = 'step';
      $step3 = 'step';
      if (isset($_GET["debut"]) and !isset($step)) { // cas de l'affectation depuis la console
          $_SESSION['resa']['idcomp']    = $_GET['idcomp']; 
          $_SESSION['resa']['nomcomp']   = $_GET['nomcomp'] ;
          $_SESSION['resa']['materiel']  = getMateriel($_GET['idcomp']);
          $_SESSION['resa']['date']      = $_GET["date"];
          $_SESSION['debut']             = $_GET["debut"];
          $step = 2;
      }
      
      
      //  affichage des etapes
      $row    = getHoraire( date("N",strtotime($_SESSION['resa']['date'])),$epn ) ;
      
      
      
      switch($step)
      {
          
      default: // etape 1: choix de l'heure de debut
          $step1 = 'currentStep';
          $submit = 'Etape suivante' ;
          //recuperation des GET
          $_SESSION['resa']['idcomp']    = $_GET['idcomp']; 
          $_SESSION['resa']['nomcomp']   = $_GET['nomcomp'] ;
          $_SESSION['resa']['materiel']  = getMateriel($_GET['idcomp']);
		    
          
          $titre = 'Choix de l\'heure de d&eacute;but de la r&eacute;servation'  ;
		  
          $step  = getResaComp(1,
                               $_SESSION['resa']['idcomp'],
                               $_SESSION['resa']['date'] ,
                               getHorDebutSelect( getConfig("unit_config",
                                                            "unit_default_config",$epn),
                                                 $row["hor1_begin_horaire"],
                                                 $row["hor1_end_horaire"],
                                                 $row["hor2_begin_horaire"],
                                                 $row["hor2_end_horaire"],
                                                 $_SESSION['resa']['idcomp'],
                                                 $_SESSION['resa']['date'],
												$_SESSION['debut']
                                                 ));      
      break;
    
      case 2: // etape 2 durŽe 
          $step2 = 'currentStep' ;
          $titre = 'Choix de la dur&eacute;e de la r&eacute;servation' ;
		  
      	  $step  = getResaComp(2,
                               $_SESSION['resa']['idcomp'],
                               $_SESSION['resa']['date'],
                               getHorDureeSelect( getConfig("unit_config",
                                                            "unit_default_config",$epn),
                                                 $row["hor1_begin_horaire"],
                                                 $row["hor1_end_horaire"],
                                                 $row["hor2_begin_horaire"],
                                                 $row["hor2_end_horaire"],
                                                 $_SESSION['resa']['idcomp'],
                                                 $_SESSION['resa']['date'],
                                                 $_SESSION['debut'],$epn
                                                 ));      
      break;
    
      case 3: // etape 3
          $step3 = 'currentStep' ;
          $titre = 'Confirmation de votre r&eacute;servation';
          /*
          $res = getUsageNameById($_SESSION['resa']['idcomp']) ;
          if ($res != FALSE)
          {
            $usage  = '';
            while($row = mysql_fetch_array($res))
            {
              $usage .= '<br />'.$row['nom_usage'];  
            }
          }*/
          
          // affichage
          if (TRUE == isset($_SESSION['other_user']))
          {
              $reserve = '<dt>R&eacute;servation pour : </dt><dd> '.getUserName($_SESSION['other_user']).'</dd>' ;
          }
          else
          {
              $reserve = '<dt>R&eacute;servation par : </dt><dd> '.getUserName($_SESSION['iduser']).'<dd>' ;
          }
          $step  =  '<dl class="dl-horizontal">'.$reserve.'
                    <dt>prevue le : </dt><dd> '.dateFr($_SESSION['resa']['date'] ).'<dd>
                    <dt>De </dt><dd>'.getTime($_SESSION["debut"]).' &agrave; '.getTime($_SESSION["debut"]+$_SESSION["duree"]).'
                    (Dur&eacute;e : '.getTime($_SESSION["duree"]).')</dd>                    
                     <dt>Ordinateur s&eacute;lectionn&eacute; : </dt><dd>'.$_SESSION['resa']['nomcomp'].'</dd>
		     </dl>
                     
			<form method="post" action="'.$_SERVER["REQUEST_URI"].'" role="form">';
                    
                    //choix de l'utilisateur si on est autorise
              if ($_SESSION['status']==4 OR $_SESSION['status']==3 )
              {
                    $searchuser = $_POST['adh'] ;
                    $step .= '
					<p class="lead">Entrez un adh&eacute;rent (nom ou num&eacute;ro de carte):</p> 
					<div class="input-group input-group-sm">  <input type="text" name="adh" class="form-control">
					<span class="input-group-btn"><button type="submit" value="Rechercher" name="adh_submit" class="btn btn-default btn-flat"><i class="fa fa-search"></i></button></span>
					</div>
                  ';
                    //affichage du resultat de la recherche
                    if ($searchuser !="" and strlen($searchuser)>2)
                    {
                        // Recherche d'un adherent
                        $result = searchUser($searchuser);
                        if (FALSE == $result OR mysqli_num_rows($result)==0)
                        {
                          echo getError(6);
                        }
                        else
                        {
                          $nb  = mysqli_num_rows($result);
                          if ($nb > 0)
                          {
                          $test ="<b>R&eacute;sultats de la recherche: ".$nb."</b>";
                          $test .='<table class="table"><thead>
						<tr><th>&nbsp;</th><th>Nom, Pr&eacute;nom</th><th>Login</th><th>Temps restant</th><th>Infos</th></tr></thead><tbody>';
                            
                            for ($i=0; $i<$nb; $i++)
                            {
                                $row = mysqli_fetch_array($result) ;
                               //donnees utlisateur
								//$age = date('Y')-$row["annee_naissance_user"];
                                $temps=getTempsCredit($row["id_user"],$date1,$date2);
								$dateadhesion=strtotime($row["dateRen_user"]);
								$aujourdhui=strtotime(date('Y-m-d'));
								
							if($row['status_user']==2){
								$class="text-muted" ;
									if ($dateadhesion<$aujourdhui){	
										$info='<small class="badge bg-blue" data-toggle="tooltip" title="adh&eacute;sion &agrave; renouveller"><i class="fa fa-info"></i></small> ';
									} else{ 
										$info='<small class="badge bg-blue" data-toggle="tooltip" title="compte inactif"><i class="fa fa-info"></i></small>';
									}
								}else{
								$class="";
								$info="";
								}
									
                                $test.= "<form method=\"post\" role=\"form\" >
                                <input type=\"hidden\" name=\"step\" value=\"3\">
                                <tr>
								<td><input type=\"hidden\" value=\"".$row["id_user"]."\" name=\"choose\"/>
									<button type=\"submit\" class=\"btn btn-success sm\" value=\"S&eacute;lectionner\" name=\"choose_adh\"/> <i class=\"fa fa-check\"></i></button></td>
                              
								<td><a href=\"index.php?a=1&b=2&iduser=".$row["id_user"]."\" data-toggle=\"tooltip\" title=\"Fiche adh&eacute;rent\"><span class=".$class.">".$row["nom_user"]." ".$row["prenom_user"]."</span></a></td>
                                <td><span class=".$class.">".$row["login_user"]."</span></td>
								<td>".getTime($temps['total']-$temps['util'])."</td>
								<td>".$info."</td>
                                </tr></form>";
                            }
                          $test .= '</tbody></table>';
                         }
                       }
                    }
              }
             $step .= '<br>'.$test.'
				<input type="hidden" name="step" value="3"><input type="hidden" name="salle" value="'.$salle.'">
				<input type="submit" name="retour" class="btn btn-default btn-flat" value=" <<">
				
				<input type="submit" class="btn btn-success btn-flat" name="valider" value="Valider la r&eacute;servation">
					 </form>
					';
          
      break;
      }
  }
  //affichage

if (TRUE ==checkInter($_SESSION['resa']['idcomp']))
{?>
  <div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> <b>ATTENTION</b> </h4>Une intervention est en cours sur cette machine, veuillez vous adresser &agrave;
votre animateur afin qu'il vous confirme la possibilit&eacute; de r&eacute;server cette machine</div>
<?php } ?>

<div class="row"><section class="col-lg-7 connectedSortable"> 

<div class="box"><div class="box-header"><h3 class="box-title">R&eacute;servation</h3></div>
<div class="box-body">

<a class="<?php echo $step1 ;?>"><button class="btn btn-default">Etape 1 / 3</button></a>
<a class="<?php echo $step2 ;?>"><button class="btn btn-default">Etape 2 / 3</button></a>
<a class="<?php echo $step3 ;?>"><button class="btn btn-default">Etape 3 / 3</button></a>
	
</div></div>


<div class="box"><div class="box-header"><h3 class="box-title"><?php echo $titre ;?></h3></div>
<div class="box-body">

    <?php
    if (TRUE == isset($messErr))
    {
        echo '<div class="callout callout-danger"><h4>'.$messErr.'</h4></div>';
        }
    ?>
    
        <?php echo $step ;?>
  

</div>

 <div class="box-footer">
        <a href="<?php echo $_SESSION['resa']['url'];?>"><input type="submit" name="annuler" value="Annuler la r&eacute;servation"  class="btn btn-warning"></a></div>

</div>


</section></div>

