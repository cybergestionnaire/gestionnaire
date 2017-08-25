<?php

//-------------------------
// Fonctions additionnelles
function csv_to_array($filename, $delimiter)
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        $lines = file($filename);
  foreach($lines as $line) {
        $values = str_getcsv($line, $delimiter, $enclosure='"', $escape='\\');
        if(!$header) $header = $values;
        else $data[] = array_combine($header, $values);
      }

        fclose($handle);
    }
        
    return $data;
}

function gFilelog($texte,$titre)
{
$pathfichier="logs/".$titre;
 $fp = fopen($pathfichier, "a+");
fseek($fp,SEEK_END);
fputs ($fp, $texte);
fclose ($fp);

}

//affiche les boutons dans la config suivant la page desactivee
function configBut($page)
{
    $confbut=array(
        array(41,"fa fa-cloud","VILLES"),
        array(43,"fa fa-home","EPN"),
        array(44,"fa fa-square","SALLES"),
        array(42,"fa fa-clock-o","HORAIRES"),
        array(47,"fa fa-eur","TARIFS"),
        array(2,"fa fa-desktop","MATERIEL"),
        array(48,"fa fa-user-md","USAGES"),
        array(46,"fa fa-caret-square-o-up","USAGES POSTES"),
        array(23,"fa fa-users","ADMIN/ANIM"),
        array(49,"fa fa-database","BDD"),
        array(25,"fa fa-unlock-alt","EPN-CONNECT"),
        array(53,"fa fa-user-plus","INSCRIPTIONS"),
    );
    $htmlbut = '';
    for($u=0;$u<count($confbut);$u++){
        if($confbut[$u][0]==$page){ $disab="disabled";} else {$disab="";}
        //debug($confbut[$u][0]);
        $htmlbut.='<a class="btn btn-app '.$disab.'"  href="index.php?a='.$confbut[$u][0].'"><i class="'.$confbut[$u][1].'"></i> '.$confbut[$u][2].'</a>';
        
    }
    return $htmlbut;
}


///******RESERVATIONS ***///
//Donne la dernière réservation d'un adhérent (page liste user)
function getLastResaUser($id){
    $sql="SELECT  MAX(`dateresa_resa`) as last_resa FROM `tab_resa` WHERE `id_user_resa`=".$id;
     $db=opendb();
    $result = mysqli_query($db,$sql);
     closedb($db);
    if(NULL == $result)
    {
        return FALSE ;
    } else {
        $row=mysqli_fetch_array($result) ;
                return $row['last_resa'];
    }
        
}

function numToDate($quant, $annee)
{
     $date = strtotime("+".($quant)." day", mktime(12, 0, 0, 01, 01, $annee));
    return date("Y-m-d", $date);
}

// chercher tous les adhérents inscrits aux ateliers.
function getAllUserAtelier($epn)
{

$sql="SELECT DISTINCT tab_user.id_user, `nom_user` ,  `prenom_user`  
FROM  `rel_atelier_user` , tab_user, tab_atelier
WHERE tab_user.id_user =  `rel_atelier_user`.`id_user`
AND `epn_user`= ".$epn."
AND YEAR(date_atelier)=YEAR(NOW())
UNION 
SELECT DISTINCT tab_user.id_user, `nom_user` ,  `prenom_user` 
FROM  `rel_session_user` , tab_user,tab_session
WHERE tab_user.id_user =  `rel_session_user`.`id_user` 
AND `epn_user`=".$epn."
AND YEAR(date_session)=YEAR(NOW())
ORDER BY nom_user ASC";
  
  $db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}


function getAllUserAtelierMois($y,$m)
{
$an=$y."-".$m."-01";
$anf=$y."-".($m+2)."-31";

$sql="SELECT DISTINCT nom_user,prenom_user, mail_user,tel_user, rel.id_user
    FROM `rel_atelier_user` AS rel, tab_atelier AS atelier, tab_user AS coor
    WHERE rel.`id_atelier`=atelier.`id_atelier`
    AND atelier.date_atelier BETWEEN '".$an."' AND '".$anf."'
    AND rel.id_user=coor.id_user
    ORDER BY nom_user ASC";
  
  $db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}

///Function dans le courrier /// A SUPPRIMER
function MakePDFUserAtelier($y,$m)
{
$an=$y."-".$m."-01";
$anf=$y."-".($m+2)."-31";

$sql="SELECT DISTINCT id_user
    FROM rel_atelier_user AS r
    LEFT JOIN tab_atelier AS a ON r.id_atelier = a.id_atelier
    WHERE a.date_atelier BETWEEN '".$an."' AND '".$anf."'
    ";
  
  $db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      //$row=mysqli_fetch_array($result);
      return $result;
  }
}

//pages utilisateur les ateliers programmes
function getMyFutAtelier($y, $m, $d)
{
if ($y==date('Y')){
    
    $sql= "SELECT *
            FROM `tab_atelier` 
            WHERE `date_atelier` BETWEEN '".$y."-".$m."-".$d."' AND '".$y."-12-31'
            ORDER BY `date_atelier` ASC";
            
    }
    else if ($year>date('Y')){
    
    $sql="SELECT *
            FROM `tab_atelier` 
            WHERE `date_atelier` BETWEEN '".$y."-01-01' AND '".$y."-12-31'
            ORDER BY `date_atelier` ASC";
    
    }       
        $db=opendb();
      $result = mysqli_query($db,$sql);
      closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
        return $result;
      }


}

///////////***********Transaction sur les ateliers, gestion des forfaits ***************///
///

//gorfait utilise par l'adherent
function getForfaitUtilise($iduser,$tarif)
{
$sql="SELECT COUNT(`id_forfait`) as totalf FROM `rel_user_forfait` WHERE `id_user`='".$iduser."' AND`id_tarif`=".$tarif;
$db=opendb();
 $result = mysqli_query($db,$sql);
      closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
        $row=  mysqli_fetch_array($result);
        return $row['totalf'];
      }

}

//////*****


//Forfait à modifier
function getForfait($id)
{
$sql="SELECT * from `tab_transactions` WHERE   id_transac='".$id."'  LIMIT 1";
$db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
        return mysqli_fetch_array($result);
      }


}

//gettransactemps(id)
// retourne la transaction sur forfait temps en cours + id rel pour epnconnect*******************************//////////////////////////

function getTransactemps($id_user)
{
    $type="temps";
    $sql="SELECT `id_transac` , `id_rel_forfait_user`,`date_transac`,`status_transac`,`id_tarif`, nbr_forfait
FROM `tab_transactions` , `rel_forfait_user`
WHERE `type_transac`='".$type."'
AND `tab_transactions`.`id_user`='".$id_user."'
AND `tab_transactions`.`id_tarif`=`rel_forfait_user`.`id_forfait`";
    
    $db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
            return mysqli_fetch_array($result);
      }
    
}


// Inutile au niveau de CyberGestionnaire, mais casse potentiellement EPN-Connect

function addrelconsultationuser($type,$tarif_forfait,$id_user)
{
if ($type==1){
    $sql="INSERT INTO `rel_forfait_user`(`id_rel_forfait_user`, `id_forfait`, `id_user`) VALUES ('','".$tarif_forfait."','".$id_user."') ";
}else if ($type==2) {
    $sql="UPDATE `rel_forfait_user` SET `id_forfait`='".$tarif_forfait."' WHERE `id_user`='".$id_user."' ";
}
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
if (FALSE == $result)
{
  return FALSE ;
} else {
return TRUE;
}
}

function addRelforfaitUser($id_user,$idtransac,$nbatelier,$depense,$statutp)
{
    
$sql="INSERT INTO `rel_user_forfait`(`id_forfait`, `id_user`, `id_transac`, `total_atelier`, `depense`, `statut_forfait`) 
VALUES ('','".$id_user."','".$idtransac."','".$nbatelier."','".$depense."','".$statutp."') ";
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
if (FALSE == $result)
{
  return FALSE ;
} else {
return TRUE;
}
}

//modification d'un forfait sur le compte
function modifForfaitUser($id,$tarif_forfait,$date,$nbreforfait,$statutp,$nbatelier)
{
$sql="UPDATE `tab_transactions` SET `id_tarif`='".$tarif_forfait."',`nbr_forfait`='".$nbreforfait."',`date_transac`='".$date."',`status_transac`='".$statutp."' WHERE `id_transac`=".$id;

$sql2="UPDATE `rel_user_forfait` SET `total_atelier`='".$nbatelier."',`statut_forfait`='".$statutp."' WHERE `id_transac`=".$id;

$db=opendb();
      $result = mysqli_query($db,$sql);
       $result2 = mysqli_query($db,$sql2);
      closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
        return TRUE;
      }


}

//retourner le nombre d'atelier dépensés sur le forfait
function getForfaitdepense($iduser,$tarif,$status)
{
$sql="SELECT COUNT( `id_forfait` ) AS total
FROM `rel_user_forfait`
WHERE `id_user` ='".$iduser."'
AND `id_tarif` ='".$tarif."'
AND `statut_forfait` =".$status;

$db=opendb();
      $result = mysqli_query($db,$sql);
      closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
        $row=mysqli_fetch_array($result);
        return $row['total'];
      }

}

//
//////////////********GESTION DES SESSIONS*********///////////////////


// retourne toutes les dates d'une session
function getDatesSession($id)
{
$sql="SELECT * FROM `tab_session_dates` WHERE `id_session`=".$id." ORDER BY `date_session` ASC ";
$db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
    
      return FALSE ;
  } else {
      return $result;
  } 

}

//////////////*****************FIN SESSIONS*************//////////////

function getDay($nb)
{
    switch ($nb)
    {
        case "0":
            $day = "Dimanche";
        break;
        case "1":
            $day ="Lundi";
        break;
        case "2":
            $day ="Mardi";
        break;
        case "3":
            $day ="Mercredi";
        break;
        case "4":
            $day ="Jeudi";
        break;
        case "5":
            $day ="Vendredi";
        break;
        case "6":
            $day ="Samedi";
        break;
        
    }   
    return $day;
}




/////FONCTIONS SUR LES SEMAINES////////
//
// fonction qui renvoi les dates de comparaisons pour la semaine
function get_lundi_dimanche_from_week($week)
{
$week= $week-1;
$year= date('Y');

for ($i = 1 ; $i < 8; $i++)
    {
    if (date ('D' ,mktime(0, 0, 0, 1, $i, $year)) == 'Mon')
    $lundi = mktime(0, 0, 0, 1, $i, $year);
    }
$dimanche = $lundi + (60 * 60 * 24 * 6);
$lundi = $lundi + (60 * 60 * 24 * 7 * ($week - 1));
$dimanche = $dimanche + (60 * 60 * 24 * 7 * ($week - 1));

return (Array($lundi, $dimanche));
}



function getDaySemaine($d,$weekday,$dayYear)
{
    if ($d>$weekday){
        $day=$dayYear+($d-$weekday); //jour suivants
    }elseif ($d == $weekday){
        $day=$dayYear ; //jour choisi même jour qu'aujourd'hui
    } else {
        $day=$dayYear-($weekday-$d); // jour precedents
    }
return $day ;
}
 
function numero_semaine($jour,$mois,$annee)
{
    /*
     * Norme ISO-8601:
     * - La semaine 1 de toute année est celle qui contient le 4 janvier ou que la semaine 1 de toute année est celle qui contient le 1er jeudi de janvier.
     * - La majorité des années ont 52 semaines mais les années qui commence un jeudi et les années bissextiles commençant un mercredi en possède 53.
     * - Le 1er jour de la semaine est le Lundi
     */ 
    
    // Définition du Jeudi de la semaine
    if (date("w",mktime(12,0,0,$mois,$jour,$annee))==0) // Dimanche
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-3*24*60*60;
    else if (date("w",mktime(12,0,0,$mois,$jour,$annee))<4) // du Lundi au Mercredi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)+(4-date("w",mktime(12,0,0,$mois,$jour,$annee)))*24*60*60;
    else if (date("w",mktime(12,0,0,$mois,$jour,$annee))>4) // du Vendredi au Samedi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-(date("w",mktime(12,0,0,$mois,$jour,$annee))-4)*24*60*60;
    else // Jeudi
        $jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee);
    
    // Définition du premier Jeudi de l'année
    if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==0) // Dimanche
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+4*24*60*60;
    }
    else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))<4) // du Lundi au Mercredi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(4-date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine))))*24*60*60;
    }
    else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))>4) // du Vendredi au Samedi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(7-(date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))-4))*24*60*60;
    }
    else // Jeudi
    {
        $premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine));
    }
        
    // Définition du numéro de semaine: nb de jours entre "premier Jeudi de l'année" et "Jeudi de la semaine";
    $numeroSemaine =     ( 
                    ( 
                        date("z",mktime(12,0,0,date("m",$jeudiSemaine),date("d",$jeudiSemaine),date("Y",$jeudiSemaine))) 
                        -
                        date("z",mktime(12,0,0,date("m",$premierJeudiAnnee),date("d",$premierJeudiAnnee),date("Y",$premierJeudiAnnee))) 
                    ) / 7 
                ) + 1;
    
    // Cas particulier de la semaine 53
    if ($numeroSemaine==53)
    {
        // Les années qui commence un Jeudi et les années bissextiles commençant un Mercredi en possède 53
        if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==4 || (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==3 && date("z",mktime(12,0,0,12,31,date("Y",$jeudiSemaine)))==365))
        {
            $numeroSemaine = 53;
        } else {
            $numeroSemaine = 1;
        }
    }
        
    //echo $jour."-".$mois."-".$annee." (".date("d-m-Y",$premierJeudiAnnee)." - ".date("d-m-Y",$jeudiSemaine).") -> ".$numeroSemaine."<BR>";
            
    return sprintf("%02d",$numeroSemaine);
} 

///********************FIN SEMAINES**************///


/// ***********FONCTIONS SUR LES IMPRESSIONS ****************///

///// retrouver la transaction
function getPrintFromID($id)
{
$sql="SELECT * FROM tab_print WHERE id_print='".$id."' 
";
$db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
    
     return $result ;
  }

}

///********FIN IMPRESSIONS ***** //////////////////////////////////////////////

///*************FONCTION SUR LE MESSAGE BOARD **** ********************

function addMessage($date, $id_user, $message,$tags,$destinataire)
{
$sql = "INSERT INTO `tab_messages` (`id_messages`, `mes_date`, `mes_auteur`, `mes_txt`, `mes_tag`, `mes_destinataire`)
        VALUES ('','".$date."','".$id_user."','".$message."','".$tags."','".$destinataire."')" ;
  $db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
    
      return FALSE ;
  } else {
      return TRUE;
  }

}

function readMessage()
{
$jour=date('Y-m')."-01".date('H:i:s');

$sql="SELECT *
    from tab_messages
    WHERE mes_date BETWEEN '".$jour."' AND NOW()
    ORDER BY mes_date DESC ;
    ";
    
$db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
    
      return FALSE ;
  } else {
      return $result;
  }
}

//retourne la liste des usages ayant poste pour la reponse de lanimateur
function getListReponse($anim)
{
$sql="SELECT `mes_auteur`, nom_user, prenom_user,id_user FROM `tab_messages`, tab_user 
WHERE `mes_destinataire`='".$anim."' 
AND mes_auteur=id_user
ORDER BY `mes_auteur`";
$db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
    
      return FALSE ;
  } else {
     $auteur = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $auteur[$row["id_user"]] = $row["nom_user"]." ".$row["prenom_user"] ;
        }
        return $auteur ;
     
  }

}

// pour la admin envoi a tous les anim + adherents ayant poste
function getListRepAdmin()
{
$sql="SELECT `mes_auteur` , `nom_user` , `prenom_user` , `status_user`
FROM `tab_messages` , tab_user
WHERE `mes_auteur` = id_user
UNION
SELECT `id_user` , `nom_user` , `prenom_user` , `status_user`
FROM tab_user
WHERE `status_user` =3
OR `status_user` =4
ORDER BY `status_user` ASC ";
$db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
    
      return FALSE ;
  } else {
     $auteur = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $auteur[$row["mes_auteur"]] = $row["nom_user"]." ".$row["prenom_user"] ;
        }
        return $auteur ;
     
  }

}

///***********************************************************


//
// renvoi si il y a atelier et le nom de l'atelier

function checkDayAtelier($j,$m,$year)
{
   
  $sql = "SELECT `date_atelier`,label_atelier, id_atelier
          FROM `tab_atelier`,tab_atelier_sujet
          WHERE tab_atelier.id_sujet=tab_atelier_sujet.id_sujet
          AND date_atelier='".$year."-".$m."-".$j."' ";
  
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

//fonction qui affiche les dates des sessions.  ---> a faire

//renvoi la liste des mois où les ateliers sont programmés pour le calendrier

function ListMoisAtelier()
{
$sql="SELECT `date_atelier`
FROM `tab_atelier`
ORDER BY `date_atelier` ASC
";
$db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  
     if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return $result ;
    }
}

function getSessionValid($id)
{
$sql="SELECT `id_programmation` FROM `tab_session_stat` WHERE `id_session`='".$id."' ";
    
    $db=opendb();
    $result = mysqli_query($db,$sql);
     closedb($db);
  
    if (FALSE == $result)
  {
      return FALSE ;
  } else {
      $nb = mysqli_fetch_array($result) ;
      return $nb ;
  }

}

//modifier les nombres statistiuques, après modif par les archives
function ModifStatAS($inscrit,$present,$absents,$idatelier,$type)
{
$sql="UPDATE `tab_as_stat` SET `inscrits`=".$inscrit.",`presents`=".$present.",`absents`=".$absents." WHERE `type_AS`='".$type."' AND`id_AS`=".$idatelier;
    $db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      return True;
  }


}

//////////
/// Gestion des tarifs //////

function getTarifs($cat)
{
$sql="SELECT * FROM `tab_tarifs` WHERE `categorie_tarif`='".$cat."' AND `id_tarif`>1 ORDER BY `id_tarif` ASC";
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
      if (FALSE == $result)
      {
          return FALSE ;
      } else {
       //  $row = mysqli_fetch_array($result);
        return $result ;
      }
}

function getNomTarif($id)
{
$sql="SELECT `nom_tarif` FROM `tab_tarifs` WHERE `id_tarif`=".$id;
$db=opendb();
$result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
    $row=mysqli_fetch_array($result);
    return $row["nom_tarif"];
    }

}

////***** FONCTIONS SUR LA GESTION MULTIESPACE ***/////

///RESEAU ***///
// retourne le nom du reseau
function getnomreseau()
{
    $sql="SELECT `res_nom` FROM `tab_reseau` LIMIT 1";
    $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
    if($result==FALSE){
        return FALSE;
    } else {
        $row= mysqli_fetch_array($result);
        return $row['res_nom'] ;
    }
    
    
}

//retourne les parametres du reseau
function getReseau()
{
    $sql="SELECT * FROM `tab_reseau`";
    $db=opendb();
     $result = mysqli_query($db,$sql);
  closedb($db);
    if(mysqli_num_rows($result)==0){
        return FALSE;
    } else {
        return mysqli_fetch_array($result) ;
    }
    
}

function modreseau($nom,$adresse,$ville,$tel,$mail,$logo,$courrier,$activation)
{
    
    $sql="UPDATE `tab_reseau` SET 
    `res_nom`='".$nom."',
    `res_adresse`='".$adresse."',
    `res_ville`='".$ville."',
    `res_tel`='".$tel."',
    `res_mail`='".$mail."',
    `res_logo`='".$logo."',
    `res_courrier`='".$courrier."',
    `res_activation`='".$activation."'
    ";
    $db=opendb();
     $result = mysqli_query($db,$sql);
  closedb($db);
     if (FALSE == $result)
  {
      return FALSE;
  } else {
      return TRUE;
  }
    
}

//
// getAllSalle()
// recupere les salless
function getAllSalle()
{
    $sql="SELECT * FROM tab_salle;";
    
  $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}

//
// getSalle()
// recupere les salless
function getSalle($numsalle)
{
    $sql="SELECT * FROM tab_salle WHERE id_salle=".$numsalle.";";
    
  $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}

//
// getEspace()
// recupere les espaces
function getEspace($numespace)
{
    $sql="SELECT * FROM tab_espace WHERE id_espace=".$numespace." ";
    
  $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}

//
//recuperer l'activation des forfaits pour l'epn
//
function getActivationForfaitEpn($id)
{
    $sql="SELECT `activer_console`,`inscription_usagers_auto`, `message_inscription`, `activation_forfait` FROM `tab_config` WHERE id_espace='".$id."' ";
    
  $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      $row=mysqli_fetch_array($result);
            return $row;
  }
    
}

function getAllEPN()
{
 $sql = "SELECT `id_espace`, `nom_espace` FROM `tab_espace`  ORDER BY `nom_espace` asc" ;
    $db=opendb();
   $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $epn = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $epn[$row["id_espace"]] = $row["nom_espace"] ;
        }
        return $epn ;
    }


}

//retourne la liste des salles par epn
function getAllSallesbyepn($epn)
{
    $sql = "SELECT `id_salle`, `nom_salle` FROM `tab_salle`  
                    WHERE id_espace='".$epn."'
        
    ORDER BY `id_salle`" ;
    $db=opendb();
   $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $epn = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $epn[$row["id_salle"]] = $row["nom_salle"] ;
        }
        return $epn ;
    }
    
    
    
}


///***** GESTION DES COURRIERS ***** ///
function createCourrier($titre,$texte,$name,$type){
    $sql="INSERT INTO `tab_courriers`(`id_courrier`, `courrier_titre`, `courrier_text`, `courrier_name`, `courrier_type`) 
    VALUES ('','".$titre."','".$texte."','".$name."','".$type."')
    ";
    
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
      return TRUE ;
  }
}

function  getAllCourrier(){
    $sql="SELECT * FROM `tab_courriers`";
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
      return $result ;
  }
    
    
}

function getcourrier($id){
    $sql="SELECT * FROM `tab_courriers` WHERE id_courrier=".$id;
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
      return mysqli_fetch_array($result) ;
  }
}



function  modCourrier($id,$titre,$texte,$name,$type)
{
    $sql="UPDATE `tab_courriers` SET    `courrier_titre`='".$titre."',
    `courrier_text`='".$texte."',
    `courrier_name`=".$name.",
    `courrier_type`=".$type." 
    WHERE `id_courrier`=".$id;
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
      return TRUE ;
  }
    
    
}

function supCourrier($id)
{
    $sql="DELETE FROM `tab_courriers` WHERE `id_courrier`=".$id;
     $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result == FALSE)
  {
      return FALSE ;
  } else {
      return TRUE  ;
  }
}

//sur la page atelier, recuperer les infos du mail de rappel
function getMailRappel(){
    $sql="SELECT `courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1";
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
       $txt = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
                        $txt[$row["courrier_type"]] = $row["courrier_text"] ;
        
        }
        return $txt ;
  }
}
//sur la page utilisateur, recuperer les infos du mail d'inscription
function getMailInscript(){
    $sql="SELECT `courrier_text`,`courrier_type` FROM `tab_courriers` WHERE `courrier_name`=1 AND `courrier_titre` LIKE '%inscription%' ";
     $db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
       $txt = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
                        $txt[$row["courrier_type"]] = $row["courrier_text"] ;
        
        }
        return $txt ;
  }
}
//gestin de la newsletter
function getNewsletterUsers()
{
$sql="SELECT `nom_user`, `prenom_user`, `mail_user`, `epn_user` FROM `tab_user` WHERE `newsletter_user`=1";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result == FALSE)
  {
      return FALSE ;
  } else {
      
      return $result  ;
  }

}


///*******************************************************************///

///***** GESTION DES PROFILS ANIMATEURS ***** ///


function getAvatar($id){
$sql="SELECT `anim_avatar` FROM `rel_user_anim` WHERE `id_animateur`='".$id."' ";
$db=opendb();
 $result = mysqli_query($db,$sql);
   closedb($db);
  if($result == FALSE)
  {
      return FALSE ;
  } else {
     $row=mysqli_fetch_array($result) ;
      return $row;
  }
}

function getSallesbyAnim($id)
{
$sql="SELECT `id_salle` FROM `rel_user_anim` WHERE `id_animateur`='".$id."' ";
$db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
if($result == FALSE)
  {
      return FALSE ;
  } else {
     $row=mysqli_fetch_array($result) ;
      return $row;
  }

}

function getNomsalleforAnim($id)
{
$sql="SELECT `nom_salle` FROM `tab_salle` WHERE `id_salle`='".$id."' ";
$db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
if($result == FALSE)
  {
      return FALSE ;
  } else {
     $row=mysqli_fetch_array($result) ;
      return $row["nom_salle"];
  }
}

 function updateNewsletter($iduser,$type)
 {
 $sql="UPDATE `tab_user` SET `newsletter_user`=".$type." WHERE `id_user`=".$iduser;
 $db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
 if($result == FALSE)
  {
      return FALSE ;
  } else {
     return TRUE;
  }
 
 }
 
 function readMyMessage($iduser)
 {
 $sql="SELECT * FROM `tab_messages` WHERE `mes_auteur`='".$iduser."' OR `mes_destinataire`='".$iduser."'
ORDER BY `mes_date` DESC ";
 $db=opendb();
 $result = mysqli_query($db,$sql);
 closedb($db);
 if($result == FALSE)
  {
      return FALSE ;
  } else {
     return $result;
  }
 
 }
 
 //*************************************************************///
 
 //********Gestion des préinscriptions *********************/////
 
 //****preinscriptions automatiques par internet
 
 //retourne l'activation du module ou pas...
 function getPreinsmode()
 {
    $sql="SELECT * FROM tab_captcha";
     $db=opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return mysqli_fetch_array($result) ;
    }
    
 }
 

//
// getAllUserInsc()
// recupere les utilisateurs
function getAllUserInsc()
{
 $sql="SELECT `id_inscription_user`, `date_inscription_user`, `nom_inscription_user`, `prenom_inscription_user`, `login_inscription_user`, `id_inscription_computer`
        FROM tab_inscription_user  ORDER BY `nom_inscription_user`";
  
    $db=opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return $result;
  }
}

//
// getUserInsc()
// recupere un utilisateur
function getUserInsc($id)
{
  $sql="SELECT *
        FROM tab_inscription_user WHERE id_inscription_user=".$id;
    $db=opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      $row=mysqli_fetch_array($result);
      return $row;
  }
}

 
 //
// deluser
// Supprime un utilisateur
function delUserInsc($id)
{
  $sql = "DELETE FROM `tab_inscription_user` WHERE `id_inscription_user`=".$id." LIMIT 1 " ;
    $db=opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return TRUE;
  }
}


function addUserinscript($date,$nom,$prenom,$sexe,$jour,$mois,$annee,$adresse,$pays,$codepostal,$commune,$ville,$tel,$telport,$mail,$temps,$loginn,$passs,$status,$csp,$equipement,$utilisation,$connaissance, $info,$epn)
{
 $db=opendb();
 $nom = mysqli_real_escape_string($db,$nom);
  $prenom = mysqli_real_escape_string($db,$prenom);
  $adresse = mysqli_real_escape_string($db,$adresse);
  $pays = mysqli_real_escape_string($db,$pays);
  $codepostal = mysqli_real_escape_string($db,$codepostal);
  $commune = mysqli_real_escape_string($db,$commune);
  $ville = mysqli_real_escape_string($db,$ville);
  $tel = mysqli_real_escape_string($db,$tel);
  $telport = mysqli_real_escape_string($db,$telport);
  $mail = mysqli_real_escape_string($db,$mail);
  $info = mysqli_real_escape_string($db,$info);
  $loginn = mysqli_real_escape_string($db,$loginn);
  $passs = mysqli_real_escape_string($db,$passs);
  
$sql="INSERT INTO `tab_inscription_user`(`id_inscription_user`, `date_inscription_user`, `nom_inscription_user`, `prenom_inscription_user`, `sexe_inscription_user`, `jour_naissance_inscription_user`, `mois_naissance_inscription_user`, `annee_naissance_inscription_user`, `adresse_inscription_user`, `quartier_inscription_user`, `code_postal_inscription`, `commune_inscription_autres`, `ville_inscription_user`, `tel_inscription_user`, `tel_port_inscription_user`, `mail_inscription_user`, `temps_inscription_user`, `login_inscription_user`, `pass_inscription_user`, `status_inscription_user`, `lastvisit_inscription_user`, `csp_inscription_user`, `equipement_inscription_user`, `utilisation_inscription_user`, `connaissance_inscription_user`, `info_inscription_user`, `id_inscription_computer`) 
VALUES ('','".$date."','".$nom."','".$prenom."','".$sexe."','".$jour."','".$mois."','".$annee."','".$adresse."','".$pays."','".$codepostal."','".$commune."','".$ville."','".$tel."','".$telport."','".$mail."','".$temps."','".$loginn."','".$passs."','".$status."','".date('Y-m-d')."','".$csp."','".$equipement."','".$utilisation."','".$connaissance."','".$info."','".$epn."')";
 
    $result = mysqli_query($db, $sql);
    closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
      return TRUE;
  }

}


///Ajouter la relation atelier-commputer pour quEPN cpnnect libère la salle !
function connectAtelierComputer($salle,$idatelier)
{
    $sql="INSERT INTO `rel_atelier_computer` ( `id_atelier_computer` , `id_atelier_rel` , `id_computer_rel` )
SELECT '', '".$idatelier."', `id_computer`
FROM `tab_computer`
WHERE `id_salle` ='".$salle."'
AND `usage_computer` =1 ";
    $db=opendb();
    $resultrow = mysqli_query($db, $sql);
    closedb($db);
    if(FALSE==$resultrow){
        return FALSE;
    } else {
    
    return TRUE;
    }
}


///************Fonctions de la page d'accueil ********************///
//

// retourne l'id d'un log du jour pour la mose à jour du statut des adherents
function getLogUser($type)
{
$sql="SELECT `id_log`,`log_date` FROM `tab_logs` WHERE date(`log_date`)=date(NOW()) AND `log_type`='".$type."'  ";
$db=opendb();
$result = mysqli_query($db, $sql);
closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return $result ;
    }

}

// update des adherents actifs ---> inactifs
function updateUserStatut() {
    $sql    = "UPDATE `tab_user` SET `status_user`=2 WHERE `status_user`=1 AND DATE(`dateRen_user`)<=DATE(NOW())";
    $db     = opendb();
    $result = mysqli_query($db, $sql);
    $nb     = mysqli_affected_rows($db);

    closedb($db);
    if ($result == FALSE ) {
        return FALSE ;
    } else {
        return $nb ;
    }
}

//retourne id + nom + prenom des adherents inactifs du jour
function getAdhInactif($jour)
{
    $sql="SELECT `id_user`,`nom_user`,`prenom_user`  FROM `tab_user` WHERE  DATE(`dateRen_user`)=DATE(NOW())";
    
    $db=opendb();
$result = mysqli_query($db, $sql);
closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return mysqli_fetch_array($result) ;
    }

    
}

// test si la base a été sauvegardée
function getLogBackup() {
    $sql = "SELECT `id_log` FROM `tab_logs` WHERE YEAR(`log_date`) = YEAR(NOW()) AND MONTH(`log_date`)=MONTH(NOW())  AND `log_type`='bac' AND DATE(`log_date`)>DATE(`log_date`)-15 ";
    $db  = opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if (mysqli_num_rows($result) <= 0 ) {
        return TRUE ;
    } else {
        return FALSE ;
    }
}



//insert un log dans la table des logs
function addLog($date,$type,$valid,$comment)
{
$sql="INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`)
VALUES ('','".$type."','".$date."','','".$valid."','".$comment."')";
$db=opendb();
$result = mysqli_query($db, $sql);
closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return TRUE ;
    }



} 



//****
/*************************************************************************
php easy :: pagination scripts set - Version Two
==========================================================================
Author:      php easy code, www.phpeasycode.com
Web Site:    http://www.phpeasycode.com
Contact:     webmaster@phpeasycode.com
*************************************************************************/
function paginate_two($reload, $page, $tpages, $adjacents) {
    
    $firstlabel = "&laquo;&nbsp;";
    $prevlabel  = "&lsaquo;&nbsp;";
    $nextlabel  = "&nbsp;&rsaquo;";
    $lastlabel  = "&nbsp;&raquo;";
    
    $out = "<ul class=\"pagination pagination-sm no-margin pull-right\">\n";
    
    // first
    if($page>($adjacents+1)) {
        $out.= "<li><a href=\"" . $reload . "\">" . $firstlabel . "</a></li>\n";
    } else {
        $out.= "<li><span>" . $firstlabel . "</span></li>\n";
    }
    
    // previous
    if($page==1) {
        $out.= "<li><span>" . $prevlabel . "</span></li>\n";
    }
    elseif($page==2) {
        $out.= "<li><a href=\"" . $reload . "\">" . $prevlabel . "</a></li>\n";
    } else {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" . ($page-1) . "\">" . $prevlabel . "</a></li>\n";
    }
    
    // 1 2 3 4 etc
    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
    for($i=$pmin; $i<=$pmax; $i++) {
        if($i==$page) {
            $out.= "<li><span class=\"btn active\">" . $i . "</span></li>\n";
        }
        elseif($i==1) {
            $out.= "<li><a href=\"" . $reload . "\">" . $i . "</a></li>\n";
        } else {
            $out.= "<li><a href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a></li>\n";
        }
    }
    
    // next
    if($page<$tpages) {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" .($page+1) . "\">" . $nextlabel . "</a></li>\n";
    } else {
        $out.= "<li><span>" . $nextlabel . "</span></li>\n";
    }
    
    // last
    if($page<($tpages-$adjacents)) {
        $out.= "<li><a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $lastlabel . "</a></li>\n";
    } else {
        $out.= "<li><span>" . $lastlabel . "</span></li>\n";
    }
    
    $out.= "</ul>";
    
    return $out;
}
//****///////////////END PAGINATION FUNCTION //****


//****** Fonction pour la tab_connexion ***************//

//entre le log de connexion dans la base
function enterConnexionstatus($iduser,$date,$type,$macadress,$navig,$exploitation)
{
    $sql="INSERT INTO `tab_connexion`(`id_connexion`, `id_user`, `date_cx`, `type_cx`, `macasdress_cx`, `navigateur_cx`, `system_cx`) 
    VALUES ('','".$iduser."','".$date."','".$type."','".$macadress."','".$navig."','".$exploitation."')";
    $db=opendb();
    $result = mysqli_query($db, $sql);
    closedb($db);
    if($result==TRUE){
        return TRUE;
    } else {
        return FALSE;
    }
    
}


/* return Operating System */
function operating_system_detection(){

        
    if ( isset( $_SERVER ) ) {
    $agent = $_SERVER['HTTP_USER_AGENT'] ;
    } else {
            global $HTTP_SERVER_VARS ;
            if ( isset( $HTTP_SERVER_VARS ) ) {
            $agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ;
            } else {
            global $HTTP_USER_AGENT ;
            $agent = $HTTP_USER_AGENT ;
            }
    }
    $ros[] = array('Windows XP', 'Windows XP');
    $ros[] = array('Windows NT 5.1|Windows NT5.1', 'Windows XP');
    $ros[] = array('Windows 2000', 'Windows 2000');
    $ros[] = array('Windows NT 5.0', 'Windows 2000');
    $ros[] = array('Windows NT 4.0|WinNT4.0', 'Windows NT');
    $ros[] = array('Windows NT 5.2', 'Windows Server 2003');
    $ros[] = array('Windows NT 6.0', 'Windows Vista');
    $ros[] = array('Windows NT 7.0', 'Windows 7');
    $ros[] = array('Windows CE', 'Windows CE');
    $ros[] = array('(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center');
    $ros[] = array('(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows');
    $ros[] = array('(win)([0-9]{2})', 'Windows');
    $ros[] = array('(windows)([0-9x]{2})', 'Windows');
    // Doesn't seem like these are necessary...not totally sure though..
    //$ros[] = array('(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'Windows NT');
    //$ros[] = array('(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})', 'Windows NT'); // fix by bg
    $ros[] = array('Windows ME', 'Windows ME');
    $ros[] = array('Win 9x 4.90', 'Windows ME');
    $ros[] = array('Windows 98|Win98', 'Windows 98');
    $ros[] = array('Windows 95', 'Windows 95');
    $ros[] = array('(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows');
    $ros[] = array('win32', 'Windows');
    $ros[] = array('(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java');
    $ros[] = array('(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris');
    $ros[] = array('dos x86', 'DOS');
    $ros[] = array('unix', 'Unix');
    $ros[] = array('Mac OS X', 'Mac OS X');
    $ros[] = array('Mac_PowerPC', 'Macintosh PowerPC');
    $ros[] = array('(mac|Macintosh)', 'Mac OS');
    $ros[] = array('(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS');
    $ros[] = array('(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS');
    $ros[] = array('(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS');
    // $ros[] = array('os/2', 'OS/2');
    $ros[] = array('freebsd', 'FreeBSD');
    $ros[] = array('openbsd', 'OpenBSD');
    $ros[] = array('netbsd', 'NetBSD');
    $ros[] = array('irix', 'IRIX');
    $ros[] = array('plan9', 'Plan9');
    $ros[] = array('osf', 'OSF');
    $ros[] = array('aix', 'AIX');
    $ros[] = array('GNU Hurd', 'GNU Hurd');
    $ros[] = array('(fedora)', 'Linux - Fedora');
    $ros[] = array('(kubuntu)', 'Linux - Kubuntu');
    $ros[] = array('(ubuntu)', 'Linux - Ubuntu');
    $ros[] = array('(debian)', 'Linux - Debian');
    $ros[] = array('(CentOS)', 'Linux - CentOS');
    $ros[] = array('(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva');
    $ros[] = array('(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE');
    $ros[] = array('(Dropline)', 'Linux - Slackware (Dropline GNOME)');
    $ros[] = array('(ASPLinux)', 'Linux - ASPLinux');
    $ros[] = array('(Red Hat)', 'Linux - Red Hat');
    // Loads of Linux machines will be detected as unix.
    // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
    //$ros[] = array('X11', 'Unix');
    $ros[] = array('(linux)', 'Linux');
    $ros[] = array('(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS');
    $ros[] = array('amiga-aweb', 'AmigaOS');
    $ros[] = array('amiga', 'Amiga');
    $ros[] = array('AvantGo', 'PalmOS');
    // $ros[] = array('[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux');
    // $ros[] = array('(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV');
    $ros[] = array('Dreamcast', 'Dreamcast OS');
    $ros[] = array('GetRight', 'Windows');
    $ros[] = array('go!zilla', 'Windows');
    $ros[] = array('gozilla', 'Windows');
    $ros[] = array('gulliver', 'Windows');
    $ros[] = array('ia archiver', 'Windows');
    $ros[] = array('NetPositive', 'Windows');
    $ros[] = array('mass downloader', 'Windows');
    $ros[] = array('microsoft', 'Windows');
    $ros[] = array('offline explorer', 'Windows');
    $ros[] = array('teleport', 'Windows');
    $ros[] = array('web downloader', 'Windows');
    $ros[] = array('webcapture', 'Windows');
    $ros[] = array('webcollage', 'Windows');
    $ros[] = array('webcopier', 'Windows');
    $ros[] = array('webstripper', 'Windows');
    $ros[] = array('webzip', 'Windows');
    $ros[] = array('wget', 'Windows');
    $ros[] = array('Java', 'Unknown');
    $ros[] = array('flashget', 'Windows');
    // delete next line if the script show not the right OS
    //$ros[] = array('(PHP)/([0-9]{1,2}.[0-9]{1,2})', 'PHP');
    $ros[] = array('MS FrontPage', 'Windows');
    //$ros[] = array('(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows');
    $ros[] = array('(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows');
    $ros[] = array('libwww-perl', 'Unix');
    $ros[] = array('UP.Browser', 'Windows CE');
    $ros[] = array('NetAnts', 'Windows');
    $file = count ( $ros );
    $os = '';
    for ( $n=0 ; $n<$file ; $n++ ){
        //error_log("preg = " . $ros[$n][0]);
            if ( preg_match('/'.$ros[$n][0].'/i' , $agent, $name)){
            $os = @$ros[$n][1].' '.@$name[2];
            break;
            }
    }
    return trim ( $os );
}
 
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        } else {
            $version= $matches['version'][1];
        }
    } else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 
    
    
///fonctions pour le flux rss
function getAtelierDuMois(){
    $sql="SELECT *
FROM `tab_atelier`,tab_atelier_sujet
WHERE MONTH( `date_atelier` ) = MONTH( NOW( ) )
AND YEAR( `date_atelier` ) = YEAR( NOW( ) ) 
AND `tab_atelier`.id_sujet=tab_atelier_sujet.`id_sujet`";
        $db=opendb();
 $result = mysqli_query($db,$sql);
  closedb($db);
  
  if (mysqli_num_rows($result)==0)
  {
      return FALSE ;
  } else {
      return $result;
  }
    
}

?>


