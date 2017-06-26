<?php
///--- Fichier de fonctions statistiques---///

//****************Stat Page d'accueil *********************************//

// retoune le nombre de nouveaux membre
function getNewMemberNum()
{
	$sql="SELECT COUNT(`id_user`) AS num FROM `tab_user` WHERE MONTH(`date_insc_user`)=MONTH(NOW()) AND YEAR(`date_insc_user`)=YEAR(NOW())";
	
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    $row= mysqli_fetch_array($result);
		return $row["num"];
    }
	
}

function getSessionbyMonth($y,$m)
{
	$sql="SELECT count(`id_session`) as num FROM `tab_session_dates` WHERE YEAR(`date_session`)=".$y."  AND MONTH(`date_session`)=".$m." ";
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    $row= mysqli_fetch_array($result);
		return $row["num"];
    }
	
	
}


//******************STATISTIQUES RESERVATION***********************************************************************
//retourne les années contenues dans les réservations
function getYearStatResa()
{
$sql="SELECT DISTINCT (YEAR( `dateresa_resa` )) AS Y FROM `tab_resa` WHERE YEAR( `dateresa_resa` )<YEAR(NOW())";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    return $result;
    }

}


function getStatResaByDay($date,$epn)
{
    $sql = "SELECT sum(duree_resa) AS duree, count(id_resa) AS nb
            FROM tab_resa, tab_computer,tab_salle
            WHERE dateresa_resa='".$date."'
						AND tab_computer.`id_salle` = tab_salle.id_salle
						AND tab_resa.`id_computer_resa` = tab_computer.id_computer
						AND `id_espace` ='".$epn."'
						" ;
   $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
       $array['duree']  = $row['duree'];
        $array['resa']  = $row['nb'];
        return $row ;
    }
}
/**
 * getStatResa
 * renvoi le nombre de reservations par mois
 **/
function getStatResa($monthNum,$year,$epn)
{
    $sql = "SELECT count(id_resa) AS nb, SUM(duree_resa) AS duree
            FROM tab_resa, tab_computer,tab_salle
            WHERE dateresa_resa BETWEEN '".$year."-".$monthNum."-01' AND '".$year."-".$monthNum."-31'
            AND tab_computer.`id_salle` = tab_salle.id_salle
						AND tab_resa.`id_computer_resa` = tab_computer.id_computer
						AND `id_espace` ='".$epn."'
           " ;
    $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result)  ;
        return $row ;
    }
}

//
//getComputerFromEpn
//retourne la liste des postes par epn_user
function getComputerStatFromEpn($epn)
{
$sql="SELECT id_computer, `id_espace`
FROM `tab_computer` , tab_salle
WHERE tab_computer.`id_salle` = tab_salle.id_salle
AND `id_espace` ='".$epn."' ";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result)  ;
        return $row ;
    }


}
//
/**
 * getStatResaComputer()
 * renvoi l'id de la machine la plus reservée
 **/
function getStatResaComputer($monthNum,$year,$epn)
{
    $sql = "SELECT count(R.id_computer_resa) AS nb ,
                   R.id_computer_resa,
                   C.nom_computer,
                   SUM(R.duree_resa) AS duree
        FROM tab_resa AS R
        INNER JOIN tab_computer AS C ON C.id_computer=R.id_computer_resa
        WHERE dateresa_resa BETWEEN '".$year."-".$monthNum."-01' AND '".$year."-".$monthNum."-31'
				AND id_user_resa !=1
				
        GROUP BY id_computer_resa
        ORDER BY duree DESC" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        return $result ;
    }
}

// Stats frequence des visites par mois
function getStatFrequence($y,$epn)
{

$sql="SELECT COUNT( `id_user_resa` ) AS frequence, `id_user_resa` 
FROM `tab_resa` , `tab_computer` , tab_salle
WHERE YEAR( dateresa_resa ) ='".$y."'
AND tab_computer.`id_salle` = tab_salle.id_salle
AND tab_resa.`id_computer_resa` = tab_computer.id_computer
AND `id_espace` ='".$epn."'
GROUP BY `id_user_resa` ";
		
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	
	if($result == FALSE)
    {
        return FALSE;
    } else {
    $nbA=0;
	$nbB=0;
	$nbC=0;
		if ($y==date('Y')){
			$nombreSemaines=date('W');
		} else {
			$nombreSemaines=52;
		}
		
	$nbf=mysqli_num_rows($result);
	for ( $i=1; $i<=$nbf ; ++$i)
		{
			$row=mysqli_fetch_array($result);
			$f=$row['frequence'];
				if ($f>0 and $f<5)
				{
					$nbA=$nbA+1;
				}elseif ($f>=5 and $f<9){
					$nbB=$nbB+1;
				}elseif ($f>=9){
					$nbC=$nbC+1;
				}
		}
	$array['f1']=round(($nbA/$nombreSemaines),2);
	$array['f2']=round(($nbB/$nombreSemaines),2);
	$array['f3']=round(($nbC/$nombreSemaines),2);
	return $array;
	   
    }
}
// retoune le nombre de poste unique occupé
function pOccupe($nb1,$nb2,$date)
{
  $sql = "SELECT id_resa
        FROM tab_resa
        WHERE dateresa_resa= '".$date."'
		AND debut_resa BETWEEN '".$nb1."' AND '".$nb2."'
		GROUP BY id_computer_resa 
		";
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if(mysqli_num_rows($result)>0){
	return $result ;
	} else {
	return FALSE;}
	
}

//
//recupère le nombre de postes occupés par tranche horaire 
// debut de tranche, nombre de resa dans la tranche
function statTrancheHour($nb1,$nb2,$nomjour,$year,$epn)
{
	$sql= "SELECT count( `id_resa` ) AS NB
	FROM `tab_resa`,`tab_computer` , tab_salle
	WHERE DAYNAME( `dateresa_resa` ) = '".$nomjour."'
	AND YEAR( `dateresa_resa` ) ='".$year."'
	AND `debut_resa` BETWEEN '".$nb1."' AND '".$nb2."' 
	AND tab_computer.`id_salle` = tab_salle.id_salle
	AND tab_resa.`id_computer_resa` = tab_computer.id_computer
	AND `id_espace` ='".$epn."'
	";
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if (mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		return $row['NB'];
		
	} else {
		return FALSE;
	}
}

///recupere le nombre de computer par epn
function getnbcomputperepn($epn)
{
	$sql="SELECT COUNT(`id_computer`) as NB FROM `tab_computer`, tab_salle WHERE `tab_computer`.`id_salle`=tab_salle.`id_salle` 
	AND `id_espace`='".$epn."' AND `usage_computer`=1 "	;
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if (mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		return $row['NB'];
		
	} else {
		return FALSE;
	}
	
	
}


function getJourEng($nb)
{
    switch ($nb)
    {
        case "0":
            $day = "Sunday";
        break;
        case "1":
            $day ="Monday";
        break;
        case "2":
            $day ="Tuesday";
        break;
        case "3":
            $day ="Wednesday";
        break;
        case "4":
            $day ="Thursday";
        break;
        case "5":
            $day ="Friday";
        break;
        case "6":
            $day ="Saturday";
        break;
        
    }   
    return $day;
}
/*
//milieu de tranche, nombre de resa accumulee avec leur duree, commençant et finissant dans la tranche
// pour avoir le nombre de personnes dans l'epn par tranche horaire.
function statTrancheHour2($nb1,$nb2,$date)
{
  $sql = "SELECT  count(id_resa) AS nb
        FROM tab_resa
        WHERE dateresa_resa= '".$date."'
		AND debut_resa+duree_resa BETWEEN ".$nb1." AND ".$nb2."
		";
	opendb();
    $result = mysql_query($sql);
	closedb;
	$row = mysql_fetch_array($result);
    $nb = $row['nb'];
    return $nb ;
    
}
*/
//Frequence par types d'abonnes
function getStatFrequenceTypeAbo($mois,$nb1,$nb2, $year)
{
 $anneeRef = $year;   // 2006
 $anneeHaute = $anneeRef-$nb1; // ex : entre 7 et 13 ans anneeHaute = 1999 et annebasse = 1993
 $anneeBasse = $anneeRef-$nb2; 

    $sql = "SELECT count(id_resa) AS nb
        FROM tab_resa
		INNER JOIN tab_user ON id_user=id_user_resa
        WHERE dateresa_resa BETWEEN '".$anneeRef."-".$mois."-01' AND '".$anneeRef."-".$mois."-31'
		AND annee_naissance_user <= ".$anneeHaute." AND annee_naissance_user >= ".$anneeBasse." 
		";
	
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    $row = mysqli_fetch_array($result);
	return $row['nb'] ;
}
// stat duree moyenne des consultations
function getStatFrequenceDuree($m,$y,$d1,$d2)
{
$sql="SELECT COUNT(id_resa) AS duree
		FROM tab_resa
		WHERE dateresa_resa BETWEEN '".$y."-".$m."-01' AND '".$y."-".$m."-31'
		AND duree_resa >'".$d1."' and duree_resa <'".$d2."'
		";
		
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	
	if($result == FALSE)
    {
        return FALSE;
    } else {
     $row=mysqli_fetch_array($result);
	 return $row['duree'];
	   
    }
}
function getStatDureeConsult($m,$y)
{
$sql="SELECT COUNT(id_resa) AS nd
		FROM tab_resa
		WHERE dateresa_resa BETWEEN '".$y."-".$m."-01' AND '".$y."-".$m."-31'
		";
		
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	
	if($result == FALSE)
    {
        return FALSE;
    } else {
     $row=mysqli_fetch_array($result);
	 return $row['nd'];
	   
    }
}


//nombre heure par types d'abonnes
function getStatHeureTypeAbo($y,$mois)
{
    $sql = "SELECT sum(duree_resa) as duree
        FROM tab_resa
		INNER JOIN tab_user ON id_user=id_user_resa
        WHERE adresse_user LIKE '%AFTAM%'
		AND dateresa_resa BETWEEN '".$y."-".$mois."-01' AND '".$y."-".$mois."-31'
		";
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    $row = mysqli_fetch_array($result);
	return $row['duree'] ;
}

//****
//*************                STATISTIQUES ADHERENTS                      ************************************************
//***

// recupere le nombre total d'adherents
function getadherenttotal($epn)
{
$sql=" SELECT count(id_user) as nbadh FROM tab_user
		WHERE status_user=1
		AND epn_user='".$epn."'
		";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
if ($result==FALSE)
	{
      return FALSE;
	} else {
		$row=mysqli_fetch_array($result);
		$nbadh=$row['nbadh'];
      return  $nbadh ;
	}
	
}
//
// statSexe()
// recupere la répartition homme femme

function statSexe($sex,$epn)
{
    $sql ="SELECT `sexe_user`
        FROM `tab_user`
        WHERE `sexe_user` = '".$sex."'
		AND status_user = 1
		AND epn_user='".$epn."'
		";
    $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $nb = mysqli_num_rows($result);
        return $nb ;
    }
} 

//
// statTranche()
// recupere le nombre de personnes dans une tranche d'age
function statTranche($nb1,$nb2,$nbtotal,$epn)
{
    $anneeRef = date("Y");   // 2006
    $anneeHaute = $anneeRef-$nb1; // ex : entre 7 et 13 ans anneeHaute = 1999 et annebasse = 1993
    $anneeBasse = $anneeRef-$nb2; 
    $sql = "SELECT `id_user`
        FROM `tab_user`
        WHERE `annee_naissance_user` <= ".$anneeHaute." AND `annee_naissance_user` >= ".$anneeBasse." 
		AND status_user = 1 
		AND epn_user='".$epn."' ";
	
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    $nb = mysqli_num_rows($result);
	 return $nb ;
}

//
// statInscription()
// retourne nombre d'inscrit par mois et par an
function statInscription($mois,$nb,$epn)
{
   $annee=date('Y');
  
   $sql="SELECT count(`id_user`) AS nb 
		FROM `tab_user` 
		WHERE MONTH(`date_insc_user`)= '".$mois."'
		AND YEAR(`date_insc_user`)= '".$annee."'
		AND status_user='".$nb."'
		AND epn_user='".$epn."'
		";
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if ($result == FALSE)
    {
        return FALSE;
    } else { 
    //         
    $row = mysqli_fetch_array($result)  ;
    return $row['nb'];
    }
}




//
// statCsp()
// retourne la répartition des adherents par Csp
function statCsp($csp,$epn)
{                              
  $csp =addslashes($csp) ;
  $sql = "SELECT count(`id_user`) AS nb FROM `tab_user`  
          WHERE `csp_user` = '".$csp."'
          AND `status_user`=1
		  AND epn_user='".$epn."'
		  " ;
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if($result == FALSE)
  {
      return FALSE;
  } else {
      $row = mysqli_fetch_array($result)  ;
      return $row['nb'] ;
  }
}


// statCity()
// retourne la répartition des adherents par ville
function statCity($ville,$statut)
{                              
 // $ville =addslashes($ville) ;
  $sql = "SELECT count(`id_user`) AS nb FROM `tab_user`  
          WHERE `ville_user` = '".$ville."' 
		  AND `status_user`='".$statut."'
		 
		  
		  ";
    $db=opendb();
  	$result = mysqli_query($db, $sql);
    closedb($db);
  if($result == FALSE)
  {
      return FALSE;
  } else {
      $row = mysqli_fetch_array($result)  ;
      return $row['nb'] ;
  }
}



//**************                 STATISTIQUES ATELIERS           ********************************************************

//retourne les années contenues dans les ateliers et sessions
function getYearStatAtelierSessions()
{
$sql="SELECT DISTINCT (YEAR( `date_AS` )) AS Y FROM `tab_as_stat` WHERE YEAR( `date_AS` )<YEAR(NOW())";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    return $result;
    }

}




//statistiques nombre d'heures d'ateliers par mois
function getStatAtelierByMonth($year,$monthNum,$nbtotal,$unit)
{
    $sql = "SELECT SUM(duree_atelier) AS duree, count(id_atelier) AS nb
            FROM tab_atelier
            WHERE date_atelier BETWEEN '".$year."-".$monthNum."-01' AND '".$year."-".$monthNum."-31'
			" ;
			
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
		
        $array['height'] = $row['duree']*$unit;
       $array['duree_atelier']  = $row['duree'];
        $array['atelier']   = $row['nb'];
       return $array ;
    }
}

//Statistique nombre d'atelier par an
function getStatAtelier($year)
{
    $sql = "SELECT count(id_atelier) AS nb, SUM(duree_atelier) AS duree
            FROM tab_atelier
            WHERE date_atelier BETWEEN '".$year."-01-01' AND '".$year."-12-31'
            " ;
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result)  ;
        return $row ;
    }
}

//Statistique nombre d'atelier par mois par catégorie
function getStatAtelierCategorie($year,$monthNum,$id_categorie,$unitV2)
{
    $sql = "SELECT count(id_atelier) AS nb_atelier, id_categorie
			FROM tab_atelier_stat
			WHERE date_atelier BETWEEN '".$year."-".$monthNum."-01' AND '".$year."-".$monthNum."-31'
			AND id_categorie='".$id_categorie."'
			"; 
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
       $row = mysqli_fetch_array($result);
		$array['height'] = $row['nb_atelier']*$unitV2;
       $array['id_categorie']  = $row['id_categorie'];
        $array['nb_atelier']   = $row['nb_atelier'];
       return $array ;
    }
}

///stat de présence d'un adhérent à une liste d'ateliers
function listAteliersPresent($atelier)
{
$sql="SELECT  `id_atelier`,`ids_presents` FROM `tab_atelier_stat` WHERE `id_atelier`='".$atelier."' ";
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
       $row = mysqli_fetch_array($result);
	return $row ;
    }

}



// stat nombre de présent par atelier par mois

function getStatPresents($m,$y,$epn,$type)
{
	$sql="SELECT round((`presents`/`inscrits`)*100) as total,`presents`, `inscrits` ,`absents`, `date_AS`, label_atelier
	FROM `tab_as_stat`, tab_atelier_sujet, tab_atelier
	WHERE `date_AS` BETWEEN '".$y."-".$m."-01' AND '".$y."-".$m."-31'
	AND `id_epn`='".$epn."' 
	AND `statut_programmation`=1 AND `type_AS`='".$type."'
	AND tab_as_stat.id_as=tab_atelier.id_atelier
	AND tab_atelier.`id_sujet`=tab_atelier_sujet.`id_sujet`
	GROUP BY `date_AS` ASC
			";
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}	


function getStatTPresentMois($m,$y,$epn,$type)
{
$sql="SELECT  SUM( presents ) AS P, SUM(inscrits ) AS I
			FROM `tab_as_stat`
			WHERE date_AS BETWEEN '".$y."-".$m."-01' AND '".$y."-".$m."-31'
			AND `id_epn`='".$epn."' AND `statut_programmation`=1 AND `type_AS`='".$type."'
			";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        $p=mysqli_fetch_array($result);
		$var=($p["P"]/$p["I"])*100;
		return $var;
    }

}


// stat nombre d'atelier par catégories + nombre de présents par catégories.
function CountCategories()
{
$sql="SELECT COUNT(id_atelier_categorie) as nbc FROM tab_atelier_categorie";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
         $row = mysqli_fetch_array($result)  ;
      return $row['nbc'] ;
    }
}

function statAtelierCategorie($y,$c,$epn)
{
$sql=" SELECT COUNT(`id_stat`) AS npCat, label_categorie
	FROM tab_as_stat,tab_atelier_categorie
	WHERE YEAR(`date_AS`)='".$y."'
	AND id_categorie = id_atelier_categorie
	AND id_categorie='".$c."'
	AND id_epn='".$epn."'
	";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}
function StatPresentsCat($y,$c,$epn)
{
	$sql="SELECT SUM(presents) AS NumP, SUM(inscrits) AS NumI ,label_categorie
			FROM tab_as_stat,tab_atelier_categorie
			WHERE YEAR(`date_AS`)='".$y."'
			AND id_categorie = id_atelier_categorie
			AND id_categorie=".$c."
			AND id_epn='".$epn."'
			
			";
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}	

//stat sur le nombre total des adherents inscrits/presents
function getStatInscrits($type,$year,$epn,$statut)
{
$sql=" SELECT SUM(`inscrits`) AS inscrits, SUM(`presents`) AS presents, SUM(`absents`) AS absents, SUM(`attente`) as attente, SUM(`nbplace`) as total, COUNT(id_stat) as nbateliers FROM `tab_as_stat` 
WHERE `type_AS`='".$type."'
AND YEAR(`date_AS`)='".$year."'
AND `statut_programmation`='".$statut."'
AND `id_epn`='".$epn."'

";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}

// inscription des jeunes
function getStatJInscrits($year,$epn)
{
$sql=" SELECT SUM(nombre_inscrits) as inscrits, SUM(nombre_presents) as presents
FROM tab_atelier_stat
WHERE date_atelier BETWEEN '".$year."-01-01' AND '".$year."-12-31'
AND id_categorie=8
";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}
///nombre des ateliers sur l'année catégorie adultes/enfant
/*
function statAtelierAn($year, $cat)
{
	if ($cat==1){
		$sql=" SELECT COUNT(id_programmation) as nbateliers
		FROM tab_atelier_stat
		WHERE date_atelier BETWEEN '".$year."-01-01' AND '".$year."-12-31'
		AND id_categorie=8
		";
	} else if ($cat==0) {
		$sql=" SELECT COUNT(id_programmation) as nbateliers
		FROM tab_atelier_stat
		WHERE date_atelier BETWEEN '".$year."-01-01' AND '".$year."-12-31'
		
		";
	}
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
		$row=mysqli_fetch_array($result);
		return $row["nbateliers"];
    }

}

*/
///retrouver les ateliers d'un adhérents par les forfaits
function UserStatInscription($iduser,$statut)
{
$sql="SELECT `id_atelier`,`id_session` FROM `rel_user_forfait` WHERE `id_user`='".$iduser."' AND `statut_forfait`='".$statut."' ";
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
if($result == FALSE)
    {
        return FALSE;
    } else {
        
	return $result;
    }

}

///determiner la présences aux ateliers ou aux sessions pour l'année en cours uniqument
// RAPPEL statut 0=inscrit, 1=présent, 2= en attente  /// type : 1->atelier, 2->session
function getUserStatutAS($iduser,$statut,$type,$statutatelier)
{

if($type==1){
	$sql="SELECT rel.`id_atelier`, atelier.statut_atelier 
	FROM `rel_atelier_user` as rel,tab_atelier as atelier 
	WHERE `id_user`=".$iduser." 
	AND `status_rel_atelier_user`=".$statut." 
	AND rel.id_atelier=atelier.id_atelier
	AND statut_atelier=".$statutatelier."
	
	ORDER BY date_atelier DESC
	";
	
	
}else if ($type==2){
	$sql="SELECT rel.`id_session` , rel.`id_datesession` , dat.date_session, ses.`status_session`
	FROM  `rel_session_user` AS rel, tab_session_dates AS dat, tab_session AS ses 
	WHERE  `id_user` =".$iduser."
	AND  `status_rel_session` =".$statut."
	AND rel.id_datesession = dat.`id_datesession` 
	AND rel.`id_session` = dat.`id_session` 
	AND rel.`id_session` = ses.`id_session` 
	AND ses.status_session =".$statutatelier."
	AND dat.statut_datesession<2
	
	ORDER BY rel.`id_session` , dat.`date_session` ASC 
	";
}

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }

}

function getUserPresence($idatelier)
{
$sql="SELECT `ids_presents` FROM `tab_atelier_stat` WHERE `id_atelier`='".$idatelier."' ";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }
}

///********************* Statistiques sur les sessions **********************
//nombre de sessions dans l'année, total inscrits, total présents
function statSessionAn($year,$idepn)
{
$sql=" SELECT count(DISTINCT `id_AS`) AS nbsession, SUM(`presents`) AS presents, SUM(`absents`) as presents, SUM(inscrits) as inscrits FROM `tab_as_stat` 
WHERE `type_AS`='s' 
AND `id_epn`= '".$idepn."'
AND YEAR(`date_AS`)='".$year."'
";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        
		return $result;
    }

}

/// compter le nombre de sessions
function countSession($year,$idepn)
{
$sql=" SELECT COUNT(DISTINCT `id_as`) as nb
FROM `tab_as_stat`
WHERE  `type_AS`='s' 
AND `id_epn`= '".$idepn."'
AND YEAR(`date_AS`)='".$year."'
";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        $row=mysqli_fetch_array($result);
		return $row['nb'];
    }

}
///Liste des sessions actives///
// retourne array des n° de sessions actives
function listSession($year,$idepn)
{
$sql=" SELECT DISTINCT(`id_AS`) FROM `tab_as_stat` 
WHERE `type_AS`='s'
AND `id_epn`='".$idepn."'
AND YEAR(`date_AS`)='".$year."'
ORDER BY `date_AS` DESC
";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
       while($row=mysqli_fetch_array($result)){
	   $r[]=$row[0];
	   }
	   return $r;
    }

}
function countPresentsSession($id,$numero)
{
$sql="SELECT count( `id_rel_session` ) AS present
FROM `rel_session_user`
WHERE `id_session` =".$id."
AND `status_rel_session` =1
AND `numero_date` =".$numero."
";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
      $row=mysqli_fetch_array($result);
	  return $row["present"];
    }
}

function getPresentsSession($id)
{
$sql="SELECT `nombre_presents`,`nombre_inscrits`,`id_date_session` 
FROM `tab_session_stat` WHERE `id_session`=".id." ";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
	return $result;
	}

}



//
///Statistique fréquence participation par sessions/dates
//
function statSessionParticip($x)
{
$sql=" SELECT sum(presents) as presents, sum(inscrits) as inscrits, `session_titre`
FROM tab_as_stat,tab_session_sujet, tab_session
WHERE `id_AS`= '".$x."'
AND `type_AS`='s'
AND `id_AS`=`id_session`
AND `nom_session`=`id_session_sujet`

";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        return $result;
    }
}

function statSessionCategory($categorie,$year,$idepn)
{
$sql="SELECT SUM(presents) as presents, SUM(inscrits) as inscrits, label_categorie
	FROM tab_as_stat, tab_atelier_categorie
	WHERE id_categorie='".$categorie."'
	AND `type_AS`='s' 
	AND `id_epn`= '".$idepn."'
	AND YEAR(`date_AS`)='".$year."'
	AND id_categorie = id_atelier_categorie
";
	
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        return $result;
    }

}

//stats detail d'une session
function getSessionDetailStat($ids,$epn)
{
	$sql="SELECT `date_AS`,`inscrits`, `presents`, `absents`, `attente`, `nbplace`, `statut_programmation` 
	FROM `tab_as_stat` 
	WHERE `id_AS`='".$ids."'
	AND id_epn='".$epn."'
	AND`type_AS`='s' 
	ORDER BY `date_AS` ASC ";
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        return $result;
    }
	
	
}

///retrouver le titre seul
function getsessionamebyid($id)
{
	$sql="SELECT `session_titre` FROM `tab_session_sujet`,tab_session WHERE `id_session_sujet`=nom_session AND id_session=".$id;
	$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        $row=mysqli_fetch_array($result);
        return $row["session_titre"];
    }
	
}

// ***************************   STATISTIQUES IMPRESSION         ***********************************************
//
//getStatPrint
//renvoi le nombre d'impressions par mois
//
///fonctions sur l'impression
/////recuperer la liste de tous les adh qui impriment et pas les autres !
// recupere le nombre d'adherents qui impriment

//retourne les années contenues dans les impressions
function getYearStatPrint()
{
$sql="SELECT DISTINCT (YEAR( `print_date` )) AS Y FROM `tab_print` WHERE YEAR( `print_date` )<YEAR(NOW())";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    return $result;
    }

}

function selectPrintTarif($type)
{
if ($type==1){ //couleur
		$sql="SELECT `id_tarif`
		FROM `tab_tarifs`
		WHERE `nom_tarif` LIKE '%coul%'
		OR `comment_tarif` LIKE '%coul%'
		AND `categorie_tarif` =1";
	} else {//noir et blanc
		$sql="SELECT `id_tarif`
		FROM `tab_tarifs`
		WHERE `nom_tarif` LIKE '%noir%'
		OR `comment_tarif` LIKE '%noir%'
		AND `categorie_tarif` =1";

	}
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
   
    return $result;
    }
}


function getstatimprim($epn)
{
$sql="SELECT DISTINCT `print_user`
FROM tab_print, tab_user
WHERE `print_user` = id_user
AND epn_user =".$epn."
		";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
if ($result==FALSE)
	{
      return FALSE;
	} else {
	$nb = mysqli_num_rows($result) ;
      return $nb ;
	}	
}
/*
function getStatPrintcredit($monthNum,$year)
{
    $sql = "SELECT SUM(print_credit) AS nombrepages
		FROM tab_print
		WHERE print_date BETWEEN '".$year."-".$monthNum."-01' AND '".$year."-".$monthNum."-31'
			" ;
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	$row = mysqli_fetch_array($result);
    $nb = $row['nombrepages'];
    
    if ($nb==FALSE) 
        return FALSE;
    else
    {
    	return $nb ;				
		
	}
}

*/

//Retourne le nombre total de page par tarif
function getStatNC($tarif)
{

$sql = "SELECT SUM( `print_debit` ) AS nb
FROM  tab_print
WHERE `print_tarif` =".$tarif."
AND print_statut=1
		";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
       $row=mysqli_fetch_array($result);
        return $row['nb'] ;
    }
}

// retourne le nombre total de page par mois et par tarif
function getStatNCbyM($mois,$year,$tarif,$epn)
{

$sql = "SELECT SUM( `print_debit` ) AS nb
FROM  tab_print
WHERE `print_tarif` ='".$tarif."'
AND MONTH(print_date)='".$mois."'
AND YEAR(print_date)='".$year."'
AND print_statut=1
AND print_epn='".$epn."'
		";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
       $row=mysqli_fetch_array($result);
        return $row['nb'] ;
    }
}
/*
function getNBC($i)
{
$sql="SELECT id_tarif FROM  `tab_tarifs` 
 WHERE `comment_tarif` LIKE '%".$i."%'
 AND `categorie_tarif` =1";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
	return $row["id_tarif"];
    }
}
*/

function getStatPages($monthNum,$year,$epn)
{
$sql = "SELECT SUM(`print_debit`) AS pages, SUM(print_credit) as montant
		FROM tab_print
		WHERE MONTH( print_date ) ='".$monthNum."'
		AND YEAR( `print_date` ) ='".$year."'
		AND `print_epn`='".$epn."'
		AND print_statut=1
		";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
        return $row;
    }

}
///
//***** stat impression repartition jour de la semaine ****////

function statImprimJS($nomjour,$year)
{
$sql=" SELECT SUM(print_credit) as debit
	FROM `tab_print`
	WHERE DAYNAME( `print_date` ) = '".$nomjour."'
	AND `print_date` BETWEEN '".$year."-01-01' AND '".$year."-12-31'
	";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
	if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
        return $row['debit'] ;
    }

}
function statImprimAn($year)
{
$sql="SELECT SUM(print_credit) as total
		FROM `tab_print`
		WHERE `print_date` BETWEEN '".$year."-01-01' AND '".$year."-12-31'";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);

	if($result == FALSE)
    {
        return FALSE;
    } else {
        $row = mysqli_fetch_array($result);
        return $row['total'] ;
    }
}

?>