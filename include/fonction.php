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

*/

//fonctions

// opendb ()
// connexion a la base de données
function opendb()
{
    include ("./connect_db.php");
    
    if ($port=="" OR FALSE==is_numeric($port)){
        $port="3306" ;}
    
    /*creation de la liaison avec la base de donnees*/
    $db = mysqli_connect($host,$userdb,$passdb,$database) ;
    /*en cas d'echec*/
   if (mysqli_connect_errno()) 
       {
       return false;
       } else {
        $db->set_charset("utf8");
    	return $db ;
	}
}     

//
// closedb()
// fermeture de la connexion a la base de donnée
function closedb ($mydb)
{
  mysqli_close ($mydb) ;
}


// Fonction user --------------------------------------
//
// passwd()
// crypt le mot de passe 
function passwd($pass)
{
    return md5($pass) ;
}

//
// getUser()
// recupere un utilisateur
function getUser($id)
{
  $sql="SELECT *  FROM tab_user WHERE id_user=".$id;
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

//
// searchUser()
// recherche un ou des utilisateurs et renvoi le resultat de la recherche
function searchUser($exp)
{
    $sql="SELECT *
        FROM `tab_user`
        WHERE  `status_user`< 3 
		AND ( `nom_user` LIKE '%".$exp."%'
        OR `prenom_user` LIKE '%".$exp."%'
		OR `login_user` LIKE '%".$exp."%' )
		ORDER BY `status_user` ASC, `nom_user` ASC";
    $db=opendb();
    $result=mysqli_query($db,$sql);
    closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return $result ;
    }
}
function searchUserRapid($exp)
{
    $sql="SELECT `id_user` , `nom_user` , `prenom_user` ,  `login_user`,`temps_user`,`status_user`,`annee_naissance_user`
        FROM `tab_user`
        WHERE ( `nom_user` LIKE '%".$exp."%'
        OR `prenom_user` LIKE '%".$exp."%'
		OR `login_user` LIKE '%".$exp."%' )
        AND (`status_user`=1 OR `status_user`=2) 
        ORDER BY `status_user` ASC, `nom_user` ASC LIMIT 10";
    $db=opendb();
    $result=mysqli_query($db,$sql);
    closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    } else {
        return $result ;
    }
}

//
// countUser()
// compte le nombre d'utilisateur actif ,inactifs , total
function countUser($id)
{
  switch ($id)
  {
      case 1: // TOTAL ACTIFS + INACTIFS
           $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`!=3 AND `status_user`!=4  " ;
      break;
      case 2: // TOTAL ACTIFS
           $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=1";
      break;
      case 3: // TOTAL INACTIFS
           $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=2";
      break;
			case 4: // TOTAL ARCHIVES
           $sql = "SELECT `id_user` FROM `tab_user` WHERE `status_user`=6";
      break;

  }
  $db=opendb();
 $result = mysqli_query($db, $sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      $nb = mysqli_num_rows($result) ;
      return $nb ;
  }
}


//
// updatePassword()
// modifie le mot de passe d'un utilisateur
function updatePassword($id,$pass)
{
    $sql="UPDATE `tab_user`
    SET `pass_user` ='".passwd($pass)."'
     WHERE `id_user` =".$id." LIMIT 1 ;";
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


function moduserstatus($user,$i){

$sql="UPDATE tab_user SET status_user='".$i."' WHERE id_user=".$user;
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
// Fonction url ----------------------------------------------------------------
//
//
// checkBookmark()
// renvoi TRUE si le user a au moins un lien
function checkBookmark($id)
{
  $sql = "SELECT `id_url` FROM `tab_url` WHERE `iduser_url`=".$id ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      if (mysqli_num_rows($result) <=0)
      {
          return FALSE ;
      } else {
          return TRUE;
      }
  }
}

// getBookmark()
// renvoi TRUE si le user a au moins un lien
function getBookmark($id)
{
  if ($id != 0)
  {
      /*$sql = "SELECT `id_url`,`titre_url`,`url_url` ,
              (
                    SELECT rub.label_url_rub
                    FROM rel_url_rub AS rel
                    INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
                    WHERE rel.id_url = url.id_url
                ) AS Flabel
              FROM `tab_url` 
              WHERE `iduser_url`=".$id." 
              ORDER BY `titre_url` ASC" ;*/
      $sql = "SELECT  url.id_url AS Fid, url.titre_url AS Ftitre, url.url_url AS Furl, 
                (
                    SELECT rub.label_url_rub
                    FROM rel_url_rub AS rel
                    INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
                    WHERE rel.id_url = url.id_url
                ) AS Flabel
                FROM tab_url AS url
                WHERE `iduser_url`=".$id." 
                ORDER BY Flabel ASC, Ftitre ASC" ;
  } else {
      $sql = "SELECT  url.id_url AS Fid, url.titre_url AS Ftitre, url.url_url AS Furl, 
                (
                    SELECT rub.label_url_rub
                    FROM rel_url_rub AS rel
                    INNER JOIN tab_url_rub AS rub ON rel.id_rub = rub.id_url_rub
                    WHERE rel.id_url = url.id_url
                ) AS Flabel
                FROM tab_url AS url
                WHERE url.iduser_url=0
                ORDER BY Flabel ASC, Ftitre ASC" ;
      //$sql = "SELECT `id_url`,`titre_url`,`url_url` FROM `tab_url` WHERE `iduser_url`=0 ORDER BY `titre_url` ASC" ;
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

//
//
function getOneUrl($id)
{
    $sql = "SELECT U.titre_url, U.url_url, R.label_url_rub 
            FROM tab_url AS U
            INNER JOIN rel_url_rub AS RU ON RU.id_url = U.id_url
            INNER JOIN tab_url_rub AS R ON R.id_url_rub = RU.id_rub
            WHERE U.id_url = ".$id ;
    $db=opendb();
  $result = mysqli_query($db, $sql);
    return mysqli_fetch_array($result) ;
    closedb($db);
}

//
// getUrlSelect
// renvoi le select contenant les rubrique d'url
function getUrlSelect()
{
    $sql = "SELECT * FROM `tab_url_rub` ORDER BY label_url_rub";
    $db=opendb();
  $result = mysqli_query($db, $sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $var = '<select name="rubSel">';
        while($row = mysqli_fetch_array($result))
        {
            $var .='<option value="'.$row['id_url_rub'].'">'.$row['label_url_rub'].'</option>' ;    
        }
        $var .="</select>" ;
        
        return $var;
    }
}

//
// addBokmark()
// ajoute un favoris dans la liste d'un utilisateur
function addBookmark($id,$titre,$url,$rubId=NULL,$rubName=FALSE)
{
    $db=opendb();
    // Requete d'insertion du lien
    $sql = "INSERT INTO `tab_url` ( `id_url` , `iduser_url` , `titre_url` , `url_url` )
            VALUES ('', '".$id."', '".$titre."', '".$url."')";
  $result = @mysqli_query($db, $sql);
    $idUrl  = @mysql_insert_id($db);
    
    //debut de creation de la requete d'insertion de la relation
    $sql3 = "INSERT INTO `rel_url_rub` (`id_url_rub`,`id_url`,`id_rub`)" ;
    
    // Requete de creation de la rubrique et execution si elle existe pas
    if (FALSE!=isset($rubName) AND $rubName !="")
    {
        $sql2 = "INSERT INTO `tab_url_rub` (`id_url_rub`,`iduser_url_rub`,`label_url_rub`)
                 VALUES('','0','".$rubName."')" ;
        $result2 = @mysqli_query($db,$sql2);  
        $idRub   = @mysql_insert_id($db);
        $sql3 .="VALUES ('','".$idUrl."','".$idRub."')" ;
    } else {
        $sql3 .="VALUES ('','".$idUrl."','".$rubId."')" ;
    }
    $result3 = @mysqli_query($db,$sql3) ;
    
    closedb($db);
    
    if (FALSE == $result OR FALSE == $result2 OR FALSE == $result3)
    {
        return FALSE ;
    } else {
        return TRUE;
    }
   
}

// updateBookmark
// modifie certaines infos du bookmark
function updateBookmark($id,$name,$url)
{
    $sql = "UPDATE `tab_url` SET titre_url='".$name."' , url_url='".$url."' WHERE id_url ='".$id."' LIMIT 1" ;
   $db= opendb() ;
    $result = mysqli_query($db, $sql);
    closedb($db);
    
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        return TRUE;
    }
}

//
// delBokmark()
// supprime un favoris dans la liste d'un utilisateur
function delBookmark($iduser,$idurl)
{
    $sql = "SELECT `id_url` FROM `tab_url` WHERE `iduser_url`=".$iduser." AND `id_url`=".$idurl;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (mysqli_num_rows($result) != 1)
    {
        return FALSE ;
    } else {
        $sql = "DELETE FROM `tab_url` WHERE `id_url`=".$idurl ;
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
}




//
// Fonction Atelier / formation ------------------------------------------------
//
//

//tous les ateliers du réseau
function getFutAtelier($year)
{
	if ($year==date('Y')){
	$sql= "SELECT *
			FROM `tab_atelier` 
			WHERE YEAR(`date_atelier`)=".$year." 
			AND MONTH(`date_atelier`)>= MONTH(NOW())-1
			ORDER BY `date_atelier` ASC";
			
	}else if ($year>date('Y')){
		$sql= "SELECT *
			FROM `tab_atelier` 
			WHERE YEAR(`date_atelier`)=".$year." 
			AND `statut_atelier`<2
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

//tous les ateliers par animateur
function getFutAtelierbyanim($year,$anim)
{
	$y=date('Y');
	
	if ($year>$y){
	
	$sql="SELECT *
			FROM `tab_atelier` 
			WHERE YEAR(`date_atelier`)=".$year."
			AND anim_atelier=".$anim."
			
			ORDER BY `date_atelier` ASC";
	
	}	else if ($year==$y){
	
	$sql= "SELECT *
			FROM `tab_atelier` 
			WHERE YEAR(`date_atelier`)=".$year." 
			AND MONTH( `date_atelier` ) >= MONTH( NOW( ))-1
			AND anim_atelier=".$anim."
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

//tous les ateliers par epn
function getFutAtelierbyepn($year,$epn)
{
	$sql= "SELECT *
			FROM `tab_atelier`, `tab_salle` 
			WHERE  `salle_atelier`=`id_salle`
			AND tab_salle.`id_espace`=".$epn."
			AND YEAR(`date_atelier`)=".$year." 
			AND `statut_atelier`<2
			ORDER BY `date_atelier` ASC";
			
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


// retourne les ateliers anciennement programmés ///DEPRECATED
function getAncAtelier($year)
{
if ($year!=date('Y')) { $annee=$year."-12-31"; } else { $annee=date('Y-m-d');}

$sql= "SELECT  *
		FROM `tab_atelier` 
		WHERE `date_atelier` BETWEEN '".$year."-01-01' AND '".$annee."'
		AND statut_atelier>=2
        ORDER BY `date_atelier` DESC";
		
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

// renvoie les données sujets en fonction de l'id du sujet
//INNER JOIN tab_atelier_categories AS tab_atelier_sujet.categorie_atelier=tab_atelier_categories.id_atelier_categorie

function getSujetById($idsujet)
{
$sql="SELECT *
		FROM tab_atelier_sujet 
		WHERE id_sujet=".$idsujet."";
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


// nombre total d'ateliers programmé par date > aujourdhui
function getNombreTotAtelier()
{
$sql="SELECT COUNT(`id_atelier`) AS nombreT_atelier FROM tab_atelier
		WHERE `date_atelier`>= '".date('Y-m-d')."' 
		";
$db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
  $row= mysqli_fetch_array($result) ;
	return $row;
  }
}

//
// getAtelier ()
// renvoi les info sur atelier par son ID
function getAtelier($id)
{
  $sql = "SELECT *
          FROM `tab_atelier`
          WHERE `id_atelier`=".$id."
		  ";
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      $row = mysqli_fetch_array($result) ;
      return $row;
  }
}

//
// getAllLevel()
// recupere la liste de tous les niveau (debutant, confirmé...)
function getAllLevel($x)
{
  $sql = "SELECT * FROM `tab_level` ORDER BY `id_level` ASC" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
		if($x>0){
		$level = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $level[$row["id_level"]] =stripslashes( $row["nom_level"] );
        }
        return $level ;
		} else {
      return $result;
	  }
  }
}


//
// getAllCategorie()
// recupere la liste de tous les categorie(Bureautique, ...)
function getAllCategorie($x)
{
  $sql = "SELECT * FROM `tab_atelier_categorie` ORDER BY `id_atelier_categorie` ASC" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
	if($x>0){
		$level = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $level[$row["id_atelier_categorie"]] = stripslashes($row["label_categorie"]) ;
        }
        return $level ;
	} else {
	return $result;
	}
     
  }
}

//
// getAllsujet()
// recupere la liste de tous les sujets(Bureautique, ...)
function getAllSujet()
{
  $sql = "SELECT id_sujet, label_atelier FROM `tab_atelier_sujet` ORDER BY `label_atelier` ASC" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
     $sujet = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $sujet[$row["id_sujet"]] = $row["label_atelier"] ;
        }
        return $sujet ;
  }
}

//CheckUserAS($id)
//verifie si un adherent est inscrit a une session ou a un atelier
function chechUserAS($iduser)
{
$sql="SELECT 
(SELECT count( `id_rel_session` )FROM `rel_session_user`  WHERE `rel_session_user`.`id_user`=".$iduser.") + 
(SELECT count( `id_rel_atelier_user` )  FROM  `rel_atelier_user` WHERE  `rel_atelier_user`.`id_user`=".$iduser." ) 
AS nba";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
	$row=mysqli_fetch_array($result);
	if($row['nba']>0){
		return TRUE;
	} else {
		return FALSE;
	}
  }


}

//
//checkUserAtelier ()
// verifie si un user est deja inscrit a un atelier ou non
function checkUserAtelier($idatelier,$iduser)
{
  // verifie si le user n'est pas deja inscrit et si il reste des places disponibles.
  $sql = "SELECT * FROM `rel_atelier_user` WHERE `id_atelier` =".$idatelier." AND `id_user` =".$iduser ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (mysqli_num_rows($result) == 0) // verifie si user deja inscrit
  {
      $sql3 = "SELECT `nbplace_atelier` FROM `tab_atelier` WHERE `id_atelier`=".$idatelier ;
      $db=opendb();
      $result3 = mysqli_query($db,$sql3);
      $row = mysqli_fetch_array($result3);
      closedb($db);
      if (countPlace($idatelier) < $row["nbplace_atelier"])  // verifie le nombre de place restante
      {
          return TRUE ;
      } else {
          return FALSE ; //inserer la liste d'attente Attention normalement doit etre FALSE
      }
  } else {
      return FALSE ;
  }
}

//
// countPlace()
// compte le nombre d'adherent dans un atelier
function countPlace ($idatelier)
{
   $sql = "SELECT `id_rel_atelier_user` FROM `rel_atelier_user` 
	WHERE `id_atelier`=".$idatelier." 
	AND `status_rel_atelier_user`= 0 ";
   $db=opendb();
   $result = mysqli_query($db,$sql);
   closedb($db);
   if (FALSE != $result)
   {
      return mysqli_num_rows($result) ;
   }
}

// getNombrePresents
// retrouve le nombre de présents à l'atelier
function getNombrePresents($idatelier)
{
   $sql = "SELECT `nombre_presents` FROM `tab_atelier_stat` WHERE `id_atelier`=".$idatelier ;
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



//
// addUserAtelier() -- insertion de la relation forfait : statut 0==inscription
// inscrit un adherent a un atelier
function addUserAtelier($idatelier,$idstatut,$iduser,$idtarif)
{
  if (FALSE != checkUserAtelier($idatelier,$iduser))
  {
      $sql ="INSERT INTO `rel_atelier_user` ( `id_rel_atelier_user` , `id_atelier` , `id_user` , `status_rel_atelier_user` )
             VALUES ('', '".$idatelier."', '".$iduser."', '".$idstatut."');";
	$sql2="INSERT INTO `rel_user_forfait`(`id_forfait`, `id_user`, `id_tarif`, `id_atelier`,`id_session`, `statut_forfait`) 
		VALUES('','".$iduser."','".$idtarif."','".$idatelier."', '0','0')";
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
  } else {
      return FALSE ;
  }
}


function addUserAtelierAttente($idatelier,$idstatut,$iduser)
{
 $sql ="INSERT INTO `rel_atelier_user` ( `id_rel_atelier_user` , `id_atelier` , `id_user` , `status_rel_atelier_user` )
             VALUES ('', '".$idatelier."', '".$iduser."', '".$idstatut."');";
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
//
// delUserAtelier()
// Desinscription d'un adherent a un atelier
function delUserAtelier($idatelier,$iduser)
{
    $sql = "DELETE FROM `rel_atelier_user` WHERE `id_user`=".$iduser." AND `id_atelier`=".$idatelier ;
    $sql2="DELETE FROM `rel_user_forfait` WHERE `id_user`=".$iduser." AND `id_atelier`=".$idatelier ;
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
// delUserAtelier()
// Desinscription d'un adherent a un atelier
function ModifyUserAtelier($idatelier, $iduser, $statut)
{

  $sql = "UPDATE `rel_atelier_user` 
			SET status_rel_atelier_user=".$statut."
			WHERE `id_user`=".$iduser." AND `id_atelier`=".$idatelier ;
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

//
// getMyAtelier()
// renvoi les atelier auxquels est inscrit un adherent
function getMyAtelier($iduser,$t,$a)
{
if ($t==1){ //ateliers futurs
	  $sql = "SELECT atelier.id_atelier,`id_sujet`,`date_atelier`,`heure_atelier`,duree_atelier,salle_atelier 
			  FROM `tab_atelier` AS atelier, `rel_atelier_user` AS rel
			  WHERE atelier.id_atelier = rel.id_atelier
			  AND `date_atelier`>= '".date('Y-m-d')."'
			  AND rel.id_user=".$iduser."
			  AND rel.status_rel_atelier_user=".$a."
			  ORDER BY `date_atelier` ASC";
	  $db=opendb();
	  $result = mysqli_query($db,$sql);
	   closedb($db);
	  if (FALSE == $result)
	  {
		  return FALSE ;
	  } else {
		  return $result;
	  }
 
	} else { // ancien atelier
	$sql = "SELECT atelier.id_atelier,`id_sujet`,`date_atelier`,`heure_atelier`
			  FROM `tab_atelier` AS atelier, `rel_atelier_user` AS rel
			  WHERE atelier.id_atelier = rel.id_atelier
			  AND `date_atelier`< '".date('Y-m-d')."'
			  AND rel.id_user=".$iduser."
			  ORDER BY `date_atelier` ASC";
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
}

// getMysession()
// renvoi les sessions futures ou en cours auxquelless est inscrit un adherent
function getMySession($iduser)
{
 // en cours de session, session non finalisee
 $sql = "SELECT rel.`id_session` , rel.`id_datesession` , dat.date_session, `statut_datesession`, status_rel_session, id_salle 
FROM `rel_session_user` AS rel, tab_session_dates AS dat, tab_session AS session 
WHERE rel.`id_user` =".$iduser."
AND `status_rel_session` < 2 
AND rel.id_datesession= dat.`id_datesession` 
AND rel.`id_session` = dat.`id_session` 
AND rel.`id_session` = session.`id_session` 
AND session.status_session=0 ORDER BY dat.date_session ASC";
		  
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
//renvoie les sessions en attentes de l'adherent
function getMySessionAttente($iduser)
{
$sql = "SELECT rel.`id_session` , rel.`id_datesession` , dat.date_session, `statut_datesession`, status_rel_session, id_salle 
FROM `rel_session_user` AS rel, tab_session_dates AS dat, tab_session AS session 
WHERE rel.`id_user` =".$iduser."
AND `status_rel_session` = 2 
AND rel.id_datesession= dat.`id_datesession` 
AND rel.`id_session` = dat.`id_session` 
AND rel.`id_session` = session.`id_session` 
AND session.status_session=0 ORDER BY dat.date_session ASC";
		  
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

//converti une date en jour de l'annee
function convertDateJour($jour)
{
$jouran=strftime("%j",strtotime($jour)) ;
return ($jouran);
}

//
// getAtelierUser()
// renvoi les users inscrits a un atelier
function getAtelierUser($idatelier,$statut)
{
if($statut==2){
  $sql = "SELECT rel.id_user, `nom_user` , `prenom_user`, mail_user, `status_rel_atelier_user`, dateRen_user
          FROM `tab_user` AS user, `rel_atelier_user` AS rel
          WHERE rel.id_user = user.id_user
		  AND rel.status_rel_atelier_user='".$statut."'
          AND rel.id_atelier ='".$idatelier."'  ORDER BY `id_rel_atelier_user` ASC
		  ";
} else {
  $sql = "SELECT rel.id_user, `nom_user` , `prenom_user`,mail_user, `status_rel_atelier_user`, dateRen_user
          FROM `tab_user` AS user, `rel_atelier_user` AS rel
          WHERE rel.id_user = user.id_user
		  AND rel.status_rel_atelier_user='".$statut."'
          AND rel.id_atelier ='".$idatelier."'  ORDER BY `nom_user` ASC
		  ";
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

///renvoie les participants à un atelier validé absents ou présents
function getAtelierArchivUser($idatelier){
$sql="SELECT rel.id_user, `nom_user` , `prenom_user`, `status_rel_atelier_user`
          FROM `tab_user` AS user, `rel_atelier_user` AS rel
          WHERE rel.id_user = user.id_user
		  AND rel.status_rel_atelier_user<2
          AND rel.id_atelier ='".$idatelier."'  ORDER BY `nom_user` ASC ";
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


//
// addAtelier()
// cree un atelier de formation
function addAtelier($date,$heure,$duree,$anim,$sujet,$nbplace,$public,$stateAtelier,$salle,$tarif,$statusepnconnect,$clotureatelier)
{
  $sql = "INSERT INTO `tab_atelier`(`id_atelier`, `date_atelier`, `heure_atelier`, `duree_atelier`, `anim_atelier`, `id_sujet`, `nbplace_atelier`, `public_atelier`, `statut_atelier`, `salle_atelier`, `tarif_atelier`, `status_atelier`, `cloturer_atelier`)
  VALUES ('','".$date."','".$heure."','".$duree."','".$anim."','".$sujet."','".$nbplace."','".$public."', '".$stateAtelier."', '".$salle."','".$tarif."','0','0')" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  $id=mysqli_insert_id($db);
  
   closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      return $id;
  }
}

// ModifAtelier()
// cree un atelier de formation
function ModifAtelier($id,$date,$heure,$duree,$anim,$sujet,$nbplace,$public,$stateAtelier,$salle,$tarif)
{
  $sql = "UPDATE `tab_atelier`
			SET `date_atelier` ='".$date."',
			`heure_atelier` ='".$heure."',
			`duree_atelier` ='".$duree."',
			`anim_atelier` ='".$anim."',
			`id_sujet` ='".$sujet."',
			`nbplace_atelier` ='".$nbplace."',
			`public_atelier` ='".$public."',	
			`statut_atelier` ='".$stateAtelier."',
			`salle_atelier` ='".$salle."',
			`tarif_atelier`='".$tarif."'
			
     WHERE `id_atelier` =".$id." LIMIT 1 ";
  
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


//
// delAtelier()
// supprime un atelier
function delAtelier($id)
{
  $sql = "DELETE FROM `tab_atelier` WHERE `id_atelier`=".$id ;
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


//retoune le titre d'un atelier en fonction de la programmation
function getAtelierSujet($idatelier)
{
$sql="SELECT `label_atelier` , `categorie_atelier`
FROM `tab_atelier_sujet` , tab_atelier
WHERE `tab_atelier_sujet`.`id_sujet` = tab_atelier.`id_sujet`
AND `id_atelier` =".$idatelier." ";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
	$row=mysqli_fetch_array($result);
      return $row;
  }

}

//
// Fonction materiel -----------------------------------------------------------
//
//
// getAllMateriel()
// recupere la liste de tous les materiel présent dans la table
function getAllMateriel()
{
  $sql = "SELECT `id_computer`,`nom_computer`,`os_computer`,`comment_computer`,`usage_computer`, `id_salle`
         FROM `tab_computer` ORDER BY `usage_computer` , `nom_computer`";
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

//materiel par epn
function getMaterielFromEpn($id)
{
$sql="SELECT `id_computer`,`nom_computer`,`os_computer`,`comment_computer`,`usage_computer` , tab_computer.`id_salle` , id_espace
FROM `tab_computer` , tab_salle
WHERE tab_computer.id_salle = tab_salle.id_salle
AND id_espace = '".$id."'
ORDER BY `usage_computer` , `nom_computer`";
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

function getComputerName($id)
{
    $sql = "SELECT `nom_computer`
         FROM `tab_computer`
         WHERE `id_computer` =".$id;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $row = mysqli_fetch_array($result);
        return $row['nom_computer'];
    } 
}

// renvoi toutes les machines disponibles à la reservation
function getAllMaterielDispo($salle)
{
  $sql = "SELECT `id_computer`,`nom_computer`,`os_computer`,`comment_computer`,`usage_computer`
         FROM `tab_computer`
         WHERE `id_salle` ='".$salle."'
				AND `usage_computer`=1
         ORDER BY  `nom_computer`
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

// recupere les machines en fonction e certains usages
function getComputerByUsage($usage)
{
    $nb = COUNT($usage) ;
    $i = 1 ;
    
    $clause = implode(',',array_keys($usage)) ;
    
    $sql = "SELECT C.id_computer,
                   C.nom_computer,
                   COUNT(R.id_usage) AS NB
            FROM tab_computer AS C
            INNER JOIN rel_usage_computer AS R ON C.id_computer = R.id_computer
            WHERE R.id_usage IN ($clause)
            AND C.usage_computer = 1";
    $sql .= " GROUP BY C.id_computer 
              ORDER BY NB DESC, C.nom_computer  ASC " ;
    
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

function getAllComputerDispo($salle)
{
    $sql = "SELECT id_computer,`nom_computer` FROM `tab_computer` 
	 WHERE `id_salle` ='".$salle."'
		AND `usage_computer`=1
         ORDER BY  `nom_computer`" ;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $comp = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
	    //les postes en intervention n'apparaissent pas dans la liste
		 if (FALSE ==checkInter($row["id_computer"])){
		    $comp[$row["id_computer"]] = $row["nom_computer"] ;
	    }
        }
        return $comp ;
    }
}
// variante utilise dans les interventions
function getComputerId()
{
  $sql = "SELECT `id_computer`
         FROM `tab_computer`
         ORDER BY `usage_computer` , `nom_computer`";
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

//
// getMateriel($id)
// renvoi les données sur un poste a partir de son id
function getMateriel($id)
{
  $sql = "SELECT *
         FROM `tab_computer` WHERE id_computer=".$id.";";
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  $row = mysqli_fetch_array($result);
  if (FALSE == $result)
  {
      return FALSE ;
  } else {
      return $row;
  }
}

//
// reservation & planning ------------------------------------------------------
//
//

//renvoi les resrvations par machines pour un jour
function getResa($id_comp,$date_resa,$salle)
{
  if (TRUE == is_numeric($id_comp))  
  {  
    $sql = "SELECT * FROM `tab_resa`
            WHERE `id_computer_resa`='".$id_comp."'
            AND `dateresa_resa`='".$date_resa."'
            ORDER BY `debut_resa` ASC";
  } else {
    $sql = "SELECT `id_resa`,`id_computer_resa`,`id_user_resa`,`dateresa_resa`,`debut_resa`,`duree_resa`,`date_resa`,`status_resa` 
	FROM `tab_resa`,`tab_computer` 
	WHERE `dateresa_resa`='".$date_resa."' 
	AND tab_resa.id_computer_resa=tab_computer.id_computer 
	AND tab_computer.id_salle='".$salle."'
	ORDER BY `debut_resa` ASC, id_computer_resa ASC";
  }
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


// renvoi les reservations d'un utilisateur
function getResaById($id,$type)
{
    $sql="SELECT `id_resa`,`dateresa_resa`,`debut_resa`,`duree_resa`,nom_computer, id_computer FROM tab_resa 
          INNER JOIN tab_computer ON id_computer=id_computer_resa
          WHERE `id_user_resa`=".$id." " ;
    if ($type==1)
        $sql .="AND `dateresa_resa`>'".date("Y-m-d")."' ";
    else
        $sql .="AND `dateresa_resa`<='".date("Y-m-d")."' ";
    
    $sql .="ORDER BY `dateresa_resa` DESC , `debut_resa` DESC";
    
    $db=opendb();
    $result = mysqli_query($db,$sql) ;
    closedb($db);
    if(FALSE == mysqli_num_rows($result))
    {
        return FALSE ;
    } else {
        return $result ;
    }
}


// renvoi TRUE si un utilisateur possede des reservations
function checkResa($id)
{
    $sql = "SELECT `id_resa` FROM tab_resa WHERE `id_user_resa`=".$id;
    $db=opendb();
   $result = mysqli_query($db,$sql) ;
    closedb($db);
    if (mysqli_num_rows($result)>0){
        return TRUE ;
    } else {
        return FALSE;
	}
}
//renvoie TRU si utilisateur est venu dans la semaine
function checkResaSemaine($id, $date1, $date2)
{
 $sql = "SELECT `id_resa` FROM tab_resa 
	WHERE `id_user_resa`=".$id."
	AND dateresa_resa  BETWEEN '".$date1."' AND '".$date2."'
	";
    $db=opendb();
    $result = mysqli_query($db,$sql) ;
    closedb($db);
    if (mysqli_num_rows($result)>0){
        return TRUE ;
    } else {
        return FALSE;}
}

// delResa , supprime une reservation
function delResa($id_resa,$id_user)
{
    $sql="DELETE FROM `tab_resa` 
          WHERE `id_resa`=".$id_resa." 
          AND `id_user_resa`=".$id_user."
          AND dateresa_resa>='".date("Y-m-d")."'" ;
    $db=opendb();
    $result = mysqli_query($db,$sql) ;
    closedb($db);
}
// getCredit
// renvoi le credit temps ( util, total dispo)
function getCredit($iduser)
{
    $sql="SELECT SUM(duree_resa) AS util,temps_user AS total
        FROM tab_resa
        INNER JOIN tab_user ON id_user=id_user_resa
        WHERE id_user_resa=".$iduser."
		 AND status_resa !='1'
		GROUP BY id_user_resa";

    $db=opendb();
   $result = mysqli_query($db,$sql) ;
    closedb($db);
    if ($result==FALSE){
        return FALSE;
    } else {
        return  mysqli_fetch_array($result) ;
    }
}



function getTempsCredit($iduser,$date1,$date2)
{
$sql= "SELECT SUM(`duree_resa`) AS util, temps_user AS total
        FROM tab_resa
        INNER JOIN tab_user ON id_user=id_user_resa
        WHERE id_user_resa='".$iduser."'
		 AND status_resa ='1'
		 AND dateresa_resa  BETWEEN '".$date1."' AND '".$date2."' ";
      
	$db=opendb();
    $result = mysqli_query($db,$sql) ;
    closedb($db);
    if ($result==FALSE)
	{
        return FALSE;
    } else {
        return mysqli_fetch_array($result) ;
    }
}


// renvoi un select contenant les horaires de reservation
// @param1 : unitŽ
// @param2 : Heure d'ouverture matin
// @param3 : Heure de fermeture matin
// @param4 : Heure d'ouverture de l'apres midi
// @param5 : Heure de fermeture de l'apres midi
function getHorDebutSelect($unit , $h1begin , $h1end , $h2begin , $h2end , $idcomp , $dateResa , $hselected)
{
  $select    = "<select name=\"debut\" size=\"15\" >" ;
  //renvoi le tableau des valeurs deja reservŽes
  $arrayResa = getResaArray($idcomp,$dateResa,$unit) ;
  //on boucle pour afficher 
  //$heureX=strftime("%H",time());
    
  $hselected=convertHoraire(strftime("%H",time()))+30; //affichage de l'heure en cours
  //debug($hselected);
  for ($i=$h1begin ; $i < $h2end ; $i=$i+$unit)
  {/*
      if ($i<$h1end OR $i>=$h2begin)
      {*/
         if($i==$hselected)
         {
            $select .= "<option value=\"".$i."\" selected>".getTime($i)."</option>";
         }
         else if (TRUE==in_array( $i, $arrayResa) OR ($i >=$h1end AND $i<$h2begin))
         {
            $select .= "<option value=\"".$i."\" disabled style=\"background-color:#EEEEEE\">".getTime($i)."</option>";
         } else {
            $select .= "<option value=\"".$i."\">".getTime($i)."</option>";   
         }
     // }
  }
  $select .= "</select>";
  return $select;
}

//renvoi un tableau des heures de debut de resa pour un jour et une machine
function getResaArray($idcomp,$dateResa,$unit)
{
    $sql = "SELECT debut_resa,duree_resa
            FROM tab_resa
            WHERE id_computer_resa=".$idcomp."
            AND dateresa_resa='".$dateResa."'
            ORDER BY debut_resa" ;
        
    $db=opendb() ;
 $result = mysqli_query($db,$sql) ;
    closedb($db);
    if ($result != FALSE)
    {
        $array = array() ;
        while($row = mysqli_fetch_array($result))
        {
            $array[] = $row['debut_resa'] ;
            // on cree un tableau contenant selon l'unite la liste des horaires utilise par la reservation
            $tmpArray = array();
            $tmpNb    = ($row['duree_resa']/$unit);
            $debutValue=$row['debut_resa'];
            for ($i=1 ;$i <=($tmpNb-1) ;++$i )
            {
                $debutValue = $debutValue+$unit ;
                $tmpArray[] = $debutValue ;
            }
            $array = array_merge($array,$tmpArray) ;
        }
        
        return $array ;
    } else {
        return FALSE ;
    }
}
    

function getHorDureeSelect($unit,$h1begin,$h1end,$h2begin,$h2end, $idcomp , $dateResa , $hselected,$epn)
{
  //select
  $select  = "<select name=\"duree\" size=\"15\" multiple>";
  // maxtime = initialisation du temps maximum de reseravtion a partir de l'heure donnee pour la date et la machine demande
  //requete pour definir la duree maximum par rapport au reservation en base
  $sql = "SELECT debut_resa
          FROM tab_resa
          WHERE dateresa_resa='".$dateResa."'
          AND id_computer_resa=".$idcomp."
          AND debut_resa>".$hselected."
          ORDER BY debut_resa ASC
          LIMIT 1" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  // on verifie l'existence d'une reservation apres celle demandee
  if (mysqli_num_rows($result)>0)
  {// si oui on calcul l'ecart
    $row = mysqli_fetch_array($result) ;
    $maxtimedb = $row['debut_resa']-$hselected ;
  } else {
    $maxtimedb = 9999999;  
  }
  
  // duree maximum d'une reservation dans le fichier config
  $maxtime = getConfig("maxtime_config","maxtime_default_config",$epn) ;
  
  // on verifie si on se trouve dans l'interval du matin
  if ($hselected<$h1end)
  {
      $delta = $h1end-$hselected ; 
  } 
  else if($hselected>=$h2begin)
  {
      $delta = $h2end-$hselected ;
  }
  
  //temps maximum determine par la config
  if($maxtimedb<$maxtime)
    $maxtime = $maxtimedb ;
  if($delta<$maxtime)
    $maxtime = $delta ;
    
    // on boucle 
    for ($i=$unit ; $i <= $maxtime ; $i = $i+$unit)
    {
        if($i==$_SESSION["duree"])
        {
            $select .= "<option value=\"".$i."\" selected>".getTime($i)."</option>";
        } else {
            $select .= "<option value=\"".$i."\">".getTime($i)."</option>";
        }
    }
    //$select .= "<option value=\"".getConfig("maxtime_config","maxtime_default_config")."\">".getTime(getConfig("maxtime_config","maxtime_default_config"))."</option>";
    $select .= "</select> ";

  return $select;
}


///pour modifier la duree d'une résa en cours
function getHorDureeSelect2($duree,$hbegin,$dateResa,$idComp,$epn)
{
    
  // duree maximum d'une reservation dans le fichier config
  $maxtime = getConfig("maxtime_config","maxtime_default_config",$epn) ;
  $unit = getConfig("unit_config","unit_default_config",$epn) ;
  // on verifie si une resa existe apres
  $sql = "SELECT debut_resa
          FROM tab_resa
          WHERE dateresa_resa='".$dateResa."'
          AND id_computer_resa='".$idComp."'
          AND debut_resa>".$hbegin."
          ORDER BY debut_resa ASC
          LIMIT 1" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  // on verifie l'existence d'une reservation apres celle demandee
  if (mysqli_num_rows($result)>0)
  {// si oui on calcul l'ecart
    $row = mysqli_fetch_array($result) ;
    $maxtimedb = $row['debut_resa']-$hbegin ;
    if ($maxtime>$maxtimedb){
    $maxtime = $maxtimedb ;
    }
  }
  
  
  
  //select
  $select  = "<select name=\"duree\">";
  for ($i=$unit ; $i<=$maxtime ;  $i = $i+$unit)
  {
        if ($i == $duree){
            $select .='<option value="'.$i.'" selected="selected">'.getTime($i).'</option>' ;} else {
            $select .='<option value="'.$i.'">'.getTime($i).'</option>' ;}
  }
  $select .= "</select> ";

  return $select;
}

// met a jour la duree d'une resa
// ou le temps decompte
function updateDureeResa($arrayPost){
    $idResa   = $arrayPost['idResa'];
    $newValue = $arrayPost['duree'];
		// modification du statut pour les resas avec la console
    /*$status   = $arrayPost['free'] ;
		if ($status=='on')
        $status='1' ;
    else
        $status='0' ;
			*/
   $status='1';  
	 
    $sql = "UPDATE tab_resa SET duree_resa='".$newValue."', status_resa='".$status ."' WHERE id_resa='".$idResa."' LIMIT 1" ;

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

//renvoi l'affichage du form de reseravtion pour une machine
// @param1 : etape 1 ou 2 
// @param2 : id du computer 
// @param3 : date du jour de la reseravtion
// @param4 : select a afficher
// @return : renvoi l
function getResaComp($step,$idcomp,$date_resa,$select)
{
   switch($step)
   {
       case 1:// step 1
            $table  ="<table><tr><td>";
            $table .= "<form method=\"post\" action=\"".$_SERVER["REQUEST_URI"]."\">
			
				";
           // $table .= "<thead><th>D&eacute;but de la reservation</th></thead>";
            $table .= $select;
            $table .= "</td><td valign=\"top\"><input type=\"hidden\" name=\"step\" value=\"1\">
                       <input type=\"submit\" class=\"btn btn-success\" name=\"submit1\" value=\"valider l'&eacute;tape 1\">";
            $table .= "</form></td></tr></table>";
       break;
       case 2: //step 2
            $table  ="<table><tr><td>";
            $table .= "<form method=\"post\" action=\"".$_SERVER["REQUEST_URI"]."\">";
           // $table .= "<div>Durée de la reservation </div>";
            $table .= $select;
            $table .= "</td><td valign=\"top\"><input type=\"hidden\" name=\"step\" value=\"2\">
                               <input type=\"submit\" class=\"btn btn-default\" name=\"retour\" value=\"<<\">
                               <input type=\"submit\" name=\"submit2\" class=\"btn btn-success\" value=\"valider l'&eacute;tape 2 >>\">";
            $table .= "</form></td></tr></table>";
       break;
   }
   return $table;
}

// del resa - supprime une reservation
function delResa2($id)
{
    $sql = "DELETE FROM tab_resa WHERE id_resa = ".$id ;
    $db=opendb();
   $result = mysqli_query($db,$sql) ;
    closedb($db);
    if ($result ==TRUE)
    {
        return TRUE;
    } else {
        return FALSE;
    }
}

// ajout d'une reservtion dans a base
function addResa($idcomp,$iduser,$date,$debut,$duree)
{
    $sql = "INSERT INTO tab_resa VALUES('',".$idcomp.",".$iduser.",'".$date."',".$debut.",".$duree.",'".date('Y-m-d')."','1')";
    $db=opendb();
   $result = mysqli_query($db,$sql) ;
	 $id=mysqli_insert_id($db);
    closedb($db);
    if ($result ==TRUE)
    {
        return $id;
    } else {
        return FALSE;
    }
}

// ajout de la relation resa / computer / usage 1=resa, 2=atelier
function insertrelresa($idresa,$usage,$titreatelier)
{
	$sql="INSERT INTO `rel_resa_usage`(`id_relresa`, `id_usage`, `id_resa`,`id_titreatelier`) VALUES ('','".$usage."','".$idresa."','".$titreatelier."')";
	 $db=opendb();
   $result = mysqli_query($db,$sql) ;
	
    closedb($db);
    if ($result ==TRUE)
    {
        return TRUE;
    } else {
        return FALSE;
    }
	
}

// renvoi la largeur en % par unité de temps
// $nbtot = int en mn
// $unit  = int en mn
function getWidthPerUnit($nbTotM,$unit)
{
  return (10*$unit)/(6*$nbTotM) ;
}

//
function getWidth($duree,$nbtot,$unit)
{
  return $duree*(getWidthPerUnit($nbtot,$unit)) ;
}

// renvoi le decalage en % par rapport a la position en min
function getPosition($debutresa,$h1begin,$wu)
{
  return (($debutresa-$h1begin)*$wu) ;
}

// renvoi le nom et le prenom d'un user
function getUserName($id)
{
  $row = getUser($id)  ;
  return $row["prenom_user"]." ".$row["nom_user"] ;
}

// renvoi le nom et le prenom d'un user en abrege
function getUserNameAbrev($id)
{
  $row = getUser($id)  ;
  return substr($row["prenom_user"],0,1).".".$row["nom_user"] ;
}

// renvoi l'unite de temps
function getConfig($field,$default_field,$epn)
{
  $sql = "SELECT `".$field."`,`".$default_field."` FROM `tab_config` WHERE `id_espace`='".$epn."' LIMIT 1" ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  $row = mysqli_fetch_array($result) ;
  if ($row[$field]>0)
  {
      return $row[$field] ;
  } else {
      return $row[$default_field];
  }
}


function getConfigConsole($epn,$field){
$sql="SELECT `".$field."` FROM `tab_config` WHERE `id_espace`=".$epn;
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
 if($result==FALSE){
	return FALSE;
	} else {
	$row= mysqli_fetch_array($result);
	return $row[$field] ;
	}
  
}

// renvoi un graf de temps en fonction des horaires matin(h1) et apm(h2)
function getPlanning($dotd,$h1begin,$h1end,$h2begin,$h2end,$epn,$salle)
{
  if ($h1begin == 0 AND $h2begin>0) //si fermé le matin
  {
      $h1begin = $h2begin ;
  }
  if ($h2end == 0 AND $h1begin>0)   //si ferm&eacute; l'apres midi
  {
      $h2end = $h1end ;
  }
  if ($h1begin == 0 AND $h2end ==0)
  {
      return FALSE;
      exit;
  }
  // Initialisation des variables

  $graf          = "" ;
  $unit          = getConfig("unit_config","unit_default_config",$epn) ; // unitŽ dans la table config
  $unitLabel     = 30 ;                  // echelle de division du temps pour les labels des heures
  
  $h1begin = (floor($h1begin/60)*60); // on recupere l"heure de debut ex : 9h15 =>9h => 540mn
  if ($h2end != (floor($h2end/60)*60))
     $h2end = ((floor($h2end/60)*60)+60); // on recupere l"heure de fin ex : 19h15 =>20h

  $nbTotM        = (($h2end-$h1begin)) ; // nombre total de minute d'ouverture
  $widthPause    = getWidth(($h2begin-$h1end),$nbTotM,$unit)*(60/$unit) ;
  $positionPause = (getPosition($h1end,$h1begin,getWidthPerUnit($nbTotM,$unit)))*(60/$unit) ;
  
  // selection des machines par salle
   $result = getAllMaterielDispo($salle); 
  
  // affichage du resultat
  if (mysqli_num_rows($result)<1)
  {
      $graf = "Aucun ordinateur dans la salle s&eacute;lection&eacute;e, veuillez choisir une autre salle" ;
  } else {
      // Creation du tableau
      $graf .= "<table  class=\"table table-condensed\">" ;
    
      // ligne des horaires - echelle au dessus des reservations
      $graf .= "<tr><td></td><td >" ; 
      for ($i = 0 ; $i < ($nbTotM/$unitLabel) ; $i++)
      {
         if ($i == (($nbTotM/$unitLabel)-1)) // correction bug I.E ...
         {
            $largeur = (getWidth(60,$nbTotM,$unitLabel)-2);
           if (strlen(getTime($h1begin+($i*$unitLabel)))<=3)
           $graf.= "<div class=\"labelHor\" style=\"width:".$largeur."%;\">|".getTime($h1begin+($i*$unitLabel))."</div>" ;
           else
           $graf.= "<div class=\"labelHor1\" style=\"width:".$largeur."%;\">|30</div>" ;
         }
         else  // sinon normal
         {
           $time = getTime($h1begin+($i*$unitLabel));
           if (strlen(getTime($h1begin+($i*$unitLabel)))<=3)
           $graf.= "<div class=\"labelHor\" style=\"width:".getWidth(60,$nbTotM,$unitLabel)."%;\">|".$time."</div>" ;
           else
           $graf.= "<div class=\"labelHor1\" style=\"width:".getWidth(60,$nbTotM,$unitLabel)."%;\">|30</div>" ;
         }
      }
      $graf .= "</td></tr>" ;

      //affichage des machines + liste des reservations
      while ($row = mysqli_fetch_array($result))
      {
	//old function affichage par usage//
          if ($row['NB']=="")
            $nbCritere='';
          else
            $nbCritere=' ('.$row['NB'].')' ;
          ///
	  
          if (strtotime($dotd)<strtotime(date("Y-m-d"))) // pas de reservation sur les dates pass&eacute;es
          {
          $graf .= "<tr><td class=\"computer\" >".$row["nom_computer"]."</td>
                        <td class=\"horaire\">" ;
          } else {
              /*if(COUNT($usage)==$row['NB']) // si la recherche est exacte
              {
                $graf .= "<tr><td class=\"computer2\"><a href=\"index.php?m=7&idepn=".$epn."&idcomp=".$row["id_computer"]."&nomcomp=".$row["nom_computer"]."&date=".$dotd."\">".$row["nom_computer"]."".$nbCritere."</a></td>
                            <td class=\"horaire\">" ;
                $lineExist = TRUE ;         
                            
              }
              else // sinon on affiche les resultats avec les autres criteres de recherche
              {*/
	       if (FALSE ==checkInter($row["id_computer"])){ //si pas d'intervention
	      
                $graf .= "<tr><td class=\"computer\"><a href=\"index.php?m=7&idepn=".$epn."&idcomp=".$row["id_computer"]."&nomcomp=".$row["nom_computer"]."&date=".$dotd."\">".$row["nom_computer"]."".$nbCritere."</a></td>
                            <td class=\"horaire\">" ;
		} else {
		 $graf .= "<tr><td class=\"computer\"><span data-toggle=\"tooltip\" title=\"Une intervention est en cours sur ce poste, pas de r&eacute;servation possible !\" class=\"text-red\">".$row["nom_computer"]."</span></td>
                            <td class=\"horaire\">" ;
		
		
		}
            //  }
          }
          
          // affichage des horaires et des occupations
          $result2  = getResa($row["id_computer"],$dotd)   ;
          $width    = 0;
          $position = 0;
          $widthTmp = 0;
          $widthTmp2 = 0;
          $i=0;
          while ($row2 = mysqli_fetch_array($result2))
          {
            
            $i=0;
			
            // largeur en % du div representant la resa
            $width        = getWidth($row2["duree_resa"],$nbTotM,$unit)*(60/$unit) ;
            
            // recupere la position absolue dans le tableau
            $positionTmp  = getPosition($row2["debut_resa"],
                                         $h1begin,
                                         getWidthPerUnit($nbTotM,
                                                         $unit));
            // position en % du div en cours (represente l'ecart avec celui de devant)
            $position     = ($positionTmp-$widthTmp2)*(60/$unit)-(($unit/60)*$i) ;
            if($position<0)
            {
                $position=0;
            }
            // Affichage de la ligne d'une machine;
            $urlGraf = "#p".$row2["id_user_resa"]; //Ajout lien vers ancre dans la liste//$_SERVER['REQUEST_URI'] ; //."&idResa=".$row2['id_resa'];
            if ($_SESSION['status']==3 OR $_SESSION['status']==4)
            { // comment d'admin et d'anim
				
                $altGraf = "(".getUserName($row2["id_user_resa"])." - ".getTime($row2["debut_resa"])." &agrave; ".getTime(($row2["debut_resa"])+($row2["duree_resa"])).")" ;
				
            } else { // comment d'utilisateur
                $altGraf = "(".getTime($row2["debut_resa"])." &agrave; ".getTime(($row2["debut_resa"])+($row2["duree_resa"])).")" ;
            }
            $graf        .= "<div class=\"unitbusy\" style=\"width:".$width."%;left:".$position."%;\">
                                <a href=\"".$urlGraf."\" alt=\"".$altGraf."\" title=\"".$altGraf."\">".getUserNameAbrev($row2["id_user_resa"])."</a>
                            </div>" ;
            $widthTmp     = ($widthTmp+$width) ;
            $widthTmp2    = $widthTmp/(60/$unit)  ;
            ++$i ;
            //echo $position.'% = (PA:'.$positionTmp.'-W'.$widthTmp.')*(60/'.$unit.') -- width:'.$width.'% -- nbTotM:'.$nbTotM.'<br/>';
          }
          // fin de l'affichage des horaires et des occupations
          $graf .= "</td></tr>" ;
      }
      
      // ligne des horaires - echelle en dessous du tableau de reservation 2
      $graf .= "<tr><td></td><td >" ;
      for ($i = 0 ; $i < ($nbTotM/$unitLabel) ; $i++)
      {
         if ($i == (($nbTotM/$unitLabel)-1)) // correction bug I.E ...
         {
         if (strlen(getTime($h1begin+($i*$unitLabel)))<=3)
         $graf.= "<div class=\"labelHor\" style=\"width:".(getWidth(60,$nbTotM,$unitLabel)-2)."%;\">|".getTime($h1begin+($i*$unitLabel))."</div>" ;
         else
         $graf.= "<div class=\"labelHor1\" style=\"width:".(getWidth(60,$nbTotM,$unitLabel)-2)."%;\">|30</div>" ;
         }
         else  // sinon normal
         {
         if (strlen(getTime($h1begin+($i*$unitLabel)))<=3)
         $graf.= "<div class=\"labelHor\" style=\"width:".getWidth(60,$nbTotM,$unitLabel)."%;\">|".getTime($h1begin+($i*$unitLabel))."</div>" ;
         else
         $graf.= "<div class=\"labelHor1\" style=\"width:".getWidth(60,$nbTotM,$unitLabel)."%;\">|30</div>" ;
         }
      }
      $graf .= "</td></tr>" ;
    
      $graf .= "</table>" ;
  }
 

  return $graf ;
}

// renvoi les horaires d'ouverture en min.
function getHoraire($day,$epn)
{
  $sql = "SELECT `hor1_begin_horaire`,`hor1_end_horaire`,`hor2_begin_horaire`,`hor2_end_horaire`
          FROM `tab_horaire`
          WHERE `jour_horaire`='".$day."'
				AND `id_epn`='".$epn."'
	  " ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
      $row = mysqli_fetch_array($result);
      return $row  ;
  }
}

// renvoi les horaires d'ouverture sous forme d'une phrase.
function getHoraireTexte($day, $epn)
{
  $sql = "SELECT `hor1_begin_horaire`,`hor1_end_horaire`,`hor2_begin_horaire`,`hor2_end_horaire`
          FROM `tab_horaire`
          WHERE `jour_horaire`='".$day."'
		  AND id_epn='".$epn."'
		  " ;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
      $row = mysqli_fetch_array($result);
      if ($row["hor1_begin_horaire"]!=0 AND $row["hor1_end_horaire"]!=0)
         $horaire = getTime($row["hor1_begin_horaire"])." &agrave; ".getTime($row["hor1_end_horaire"]);
      else
         $horaire = "Ferm&eacute; le matin ";
      if ($row["hor2_begin_horaire"]!=0 AND $row["hor2_end_horaire"]!=0)
         $horaire .= ", ouvert de ".getTime($row["hor2_begin_horaire"])." &agrave; ".getTime($row["hor2_end_horaire"]) ;
      else
         $horaire .= ", Ferm&eacute; l'après midi" ;

      if ($row["hor1_begin_horaire"]!="" AND $row["hor1_end_horaire"]==0 AND $row["hor2_begin_horaire"]==0 AND $row["hor2_end_horaire"]!="")
         $horaire =  getTime($row["hor1_begin_horaire"])." &agrave; ".getTime($row["hor2_end_horaire"]) ;

      return $horaire  ;
  }
}

//
// breves ----------------------------------------------------------------------
//
//
// getAllbreve()
// recupere toutes les breves
function getAllBreve($nb)
{
  switch($nb)
  {
      
      case 0: // Toutes les breves , gestion dans l'admin
      $sql ="SELECT *
             FROM `tab_news` " ;
      break;
      case 1: // breve public, pour les adhérents
      $sql ="SELECT `id_news`,`titre_news`,`comment_news`,`visible_news`
             FROM `tab_news`
             WHERE `visible_news`= 0
             ORDER BY `id_news` ASC" ;
      break;
      case 2: // Breves interne, pour les admin et les animateurs
      $sql ="SELECT `id_news`,`titre_news`,`comment_news`,`visible_news`
             FROM `tab_news`
             WHERE `visible_news`= 1
             ORDER BY `id_news` DESC" ;
      break;
  }
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
      return $result  ;
  }
}

//
// getBreve()
// recupere une breve(news) a partir d'un id
function getBreve($id)
{
  $sql = "SELECT *
             FROM `tab_news`
             WHERE `id_news`=".$id;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
      $row = mysqli_fetch_array($result);
      return $row  ;
  }
}

//
// addBreve()
// ajoute une breve
function addBreve($titr,$comment,$visible,$type,$datepublish,$datenews,$epn)
{
  $sql = "INSERT INTO `tab_news`
         (`id_news`,`titre_news`,`comment_news`,`visible_news`,`type_news`,`date_publish`, `date_news`, `id_epn`)
         VALUES ('','".$titr."','".$comment."','".$visible."','".$type."','".$datepublish."','".$datenews."','".$epn."')" ;
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

//
// modBreve()
// ajoute une breve
function modBreve($id,$titr,$comment,$visible,$type,$datepublish,$datenews,$epn)
{
  
    $sql="UPDATE `tab_news`
    SET `titre_news` ='".$titr."',
        `comment_news` ='".$comment."',
        `visible_news` ='".$visible."',
		`type_news` ='".$type."',
		`date_publish`='".$datepublish."', 
		`date_news`='".$datenews."',  
		`id_epn`='".$epn."'
     WHERE `id_news` =".$id." LIMIT 1 ";
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

//
// supBreve()
// ajoute une breve
function supBreve($id)
{
  $sql = "DELETE FROM `tab_news` WHERE `id_news`=".$id ;
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


//
// Gestion des impressions ------------
//AND `print_date` BETWEEN '".$year1."-".$month1."-01' AND '".$year."-".$month."-31'
// renvoi les impressions d'un utilisateur  WHERE `id_user_print`=".$id." AND `type_print` NOT LIKE 'credit' " ;
function getPrintById($iduser)
{
   
	$sql="SELECT * FROM tab_print
		WHERE print_user='".$iduser."'
		AND (print_statut=0 OR print_statut=1)
		AND TO_DAYS(NOW()) - TO_DAYS(print_date) <= 360
		ORDER BY `print_date` DESC ";
    
    $db=opendb();
   $result = mysqli_query($db,$sql) ;
    closedb($db);
    if(FALSE == mysqli_num_rows($result))
    {
        return FALSE ;
    } else {
        return $result ;
    }
}



function getPrintdebitType($iduser,$type)
{
    $sql="SELECT SUM(`print_credit`)AS nbpage, print_statut, donnee_tarif, nom_tarif,id_tarif
		FROM tab_print,tab_tarifs
		WHERE print_user=".$iduser." 
		AND print_tarif=".$type."
		AND tab_print.print_tarif = tab_tarifs.id_tarif
				" ;

    $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
    $print= mysqli_fetch_array($result);
     return $print ;
  }
}

function selectIdPrintUser($iduser){
$sql="SELECT id_print
		FROM tab_print
		WHERE print_user='".$iduser."'
		AND print_statut<=1 ";
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

function getDebitUser($iduser)
{
$sql="SELECT SUM(print_debit*donnee_tarif) as debit
FROM tab_print,tab_tarifs
WHERE print_user=".$iduser." 
AND print_statut<=1
AND tab_print.print_tarif = tab_tarifs.id_tarif
";
 $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if (FALSE == $result)
  {
      return FALSE;
  } else {
    $print= mysqli_fetch_array($result);
     return $print['debit'] ;
  }

}

// renvoi TRUE si un utilisateur possede des impressions
function checkPrint($id)
{
    $sql = "SELECT `id_print` FROM tab_print WHERE `print_user`=".$id ;
    $db=opendb();
 $result = mysqli_query($db,$sql) ;
    closedb($db);
    if (mysqli_num_rows($result)>0){
        return TRUE ;
    } else {
	
        return FALSE;}
}

// addPrint()
// ajoute un nouveau credit/debit au compte d'impression
function addPrint($date_p,$id_user,$debit_p, $tarif_p,$statut_p,$credit_p,$nomuser_p,$epn,$caissier, $moyen_p)
{
	$sql="INSERT INTO `tab_print`(`id_print`, `print_date`, `print_user`, `print_debit`, `print_tarif`, `print_statut`, `print_credit`, `print_userexterne`, `print_epn`, `print_caissier`,`print_paiement`) 
  VALUES ('','".$date_p."','".$id_user."','".$debit_p."','".$tarif_p."','".$statut_p."','".$credit_p."','".$nomuser_p."','".$epn."','".$caissier."','".$moyen_p."')";
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


//
// modPrint()
// modifie un credit debit
function modPrint($id,$date_p,$debit_p,$tarif_p, $statut_p, $credit_p,$nomuser_p,$moyen_p)
{
   $sql="UPDATE `tab_print`
    SET `print_date` ='".$date_p."',
        `print_debit` ='".$debit_p."',
        `print_tarif` ='".$tarif_p."',
		`print_statut` ='".$statut_p."',
		`print_credit` ='".$credit_p."',
		print_userexterne='".$nomuser_p."',
		print_paiement='".$moyen_p."'
	WHERE `id_print` ='".$id."' 
	 ";
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


//
// getPrintid()
// recupere un credit a partir d'un id
function getPrintid($id_p)
{
  $sql = "SELECT * FROM `tab_print`
             WHERE `id_print`=".$id_p;
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
      $row = mysqli_fetch_array($result);
      return $row  ;
  }
}
//
// retrouve les credits d'impression positifs...quand l'usager a mis de l'argent en plus en ope unique
function getCreditPrintId($id)
{
$sql="SELECT * FROM `tab_print` WHERE `print_statut`=2 and `print_user`='".$id."' ";
$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result ==FALSE)
  {
      return FALSE ;
  } else {
     
      return $result  ;
  }
}

//
// supBreve()
// ajoute une breve
function supPrint($id_p)
{
  $sql ="DELETE FROM `tab_print` WHERE `id_print`=".$id_p." 
  ";
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

function getCreditUser($id)
{
$sql="SELECT SUM(`print_credit`) AS credit FROM `tab_print` WHERE `print_user`='".$id."' AND `print_statut`>=1 
";

$db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result == FALSE)
  {
      return FALSE ;
  } else {
       $row = mysqli_fetch_array($result);
      return $row["credit"]  ;
  }

}


//
// usages ----------------------------------------------------------------------
//


//renvoi le nom des usages d'une machine
function getUsageNameById($idcomp)
{
    $sql = 'SELECT TU.nom_usage
            FROM rel_usage_computer AS RUC 
            INNER JOIN tab_usage AS TU ON TU.id_usage = RUC.id_usage            
            WHERE id_computer='.$idcomp.'' ;

    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if ($result==FALSE)
    {
        return FALSE ;
    } else {
        return $result;    
    }
}

//
// Interventions ---------------------------------------------------------------
//
//
// getInter()
// recupere la liste des interventions
function getAllInter()
{
       $sql="SELECT `id_inter`,`titre_inter` , `comment_inter` , `statut_inter` , `date_inter`
            FROM `tab_inter` 
            ORDER BY `statut_inter` ASC ,`id_inter` DESC";
        $db=opendb(); 
      $result = mysqli_query($db,$sql);
        closedb($db);
        if ($result == FALSE )
        {
            return FALSE;
        } else {
            return $result;
        } 
}

// checkInter()
// verifie si une intervention est en cours sur une machine
// TRUE : une intervention est en cours sur la machine 
// FALSE : aucune intervention en cours sur la  machine
function checkInter($id_comp)
{
    $sql ="SELECT COUNT(TI.id_inter) AS nb
           FROM tab_inter AS TI
           INNER JOIN rel_inter_computer AS RIC ON RIC.id_inter = TI.id_inter
           WHERE RIC.id_computer = ".$id_comp."
           AND TI.statut_inter = 0" ;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if ($result == TRUE )
    {
        $row = mysqli_fetch_array($result) ;
        if ($row['nb']>0)
            return TRUE ;
        else
            return FALSE ;
    } else {
        return FALSE;
    }
}


//
// addInter()
// ajoute une intervention
function addInter($titr,$date,$comment,$dispo)
{
    $sql="INSERT INTO `tab_inter` (`id_inter`, `titre_inter`, `comment_inter`, `statut_inter`, `date_inter`)  VALUES ('','".$titr."', '".$comment."','".$dispo."','".$date."')";
        $db=opendb();
       $result = mysqli_query($db,$sql);
           $lastid = mysqli_insert_id($db) ;
        closedb($db);
        if ($result == FALSE )
        {
            return FALSE;
        } else {
            return $lastid;
        } 
}

//
// addInterComputer()
// ajoute une relation dans la table rel_inter_computer 
function addInterComputer($idinter,$idcomputer)
{
           $sql="INSERT INTO `rel_inter_computer` (`id_inter_computer`, `id_inter`, `id_computer`) VALUES ('','".$idinter."', '".$idcomputer."')";
           $db=opendb();
        $result = mysqli_query($db,$sql);
        closedb($db);
        if ($result == FALSE )
        {
            return FALSE;
        } else {
            return TRUE;
        } 
}

//
// getInterComputer($idinter)
// recupere le nom des machine concernŽ par une intervention
function getInterComputer($idinter)
{
    $sql = "SELECT `nom_computer` 
        FROM `rel_inter_computer` AS rel ,`tab_computer`AS comp
        WHERE rel.id_computer = comp.id_computer
        AND rel.id_inter='".$idinter."' ORDER BY `nom_computer` ASC";
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

//
// modInter()
// modifie le statut d'une intervention
function modInter($id,$statut)
{
    $sql="UPDATE `tab_inter` SET `statut_inter`='".$statut."' WHERE `id_inter`=".$id;
        $db=opendb();
     $result = mysqli_query($db,$sql);
        closedb($db);
        if ($result == FALSE )
        {
            return FALSE;
        } else {
            return TRUE;
        } 
}

//
// supInter()
// supprime une intervention
function supInter($id)
{
    $sql="DELETE FROM `tab_inter` WHERE `id_inter`=".$id;
        $db=opendb();
       $result = mysqli_query($db,$sql);
        closedb($db);
        if ($result == FALSE )
        {
            return FALSE;
        } else {
            $sql = "DELETE FROM `rel_inter_computer` WHERE `id_inter`=".$id;
            $db=opendb();
            $result = mysqli_query($db,$sql);
            closedb($db);
            if ($result == FALSE )
            {
                return FALSE;
            } else {
                  return TRUE;
            }
        } 
}

//
//
// Calendrier ------------------------------------------------------------------
//
//

//renvoi le nom du mois a partir du numero de mois
function getMonthName($monthNum)
{
  $monthList = array("","Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","D&eacute;cembre" ) ;
  return  $monthList[$monthNum] ;
}
// renvoi le premier jour du mois en chiffre
function getFirstDay($year,$month)
{
  return date("N",mktime(0, 0, 0, $month, 1, $year)) ;
}
// renvoi le dernier jour du mois - 7
function getLastDay($year,$month)
{
  $nb_jour  = date("t", mktime(0, 0, 0, $month, 1, $year));
  $tmp = date("N",mktime(0, 0, 0, $month, $nb_jour, $year)) ;
  return 7-$tmp ;
}


// renvoi un calendrier du mois et de l'annee donnee
function getCalendar($year,$month,$epn)
{
  $calendar = "" ;
  // tableau des index des jours
  $dayArray = array("L","M","M","J","V","S","D") ;
  //nombre de jour das le mois en cours
  $nb_jour  = date("t", mktime(0, 0, 0, $month, 1, $year));
  //epn sélectionné
	$Pepn=$_SESSION["idepn"];
	if ($epn==$Pepn){
	$epn=$epn;
	} else {
	$epn=$Pepn;
	}
	
  //Bouton pour la resa a posteriori que animateurs ou admin
	if($_SESSION["status"]==3 OR $_SESSION["status"]==4){
		$boutonresa="<a href=\"index.php?a=19\"><i class=\"ion ion-log-in\"></i></a>";
	} else {
		$boutonresa="";
	}
  //Affichage -------------------------------------

  //affichage du mois et de l'année
   $calendar = "<div align=\"center\" class=\"titreCal\"> <h4 ><a href=\"?m=3&month=".($month-1)."&year=".$year."\"><i class=\"ion-arrow-left-b\"></i></a>&nbsp;&nbsp;<b>".getMonthName($month)." ".$year."</b>
	 <a href=\"?m=3&month=".($month+1)."&year=".$year."\">&nbsp;&nbsp;<i class=\"ion-arrow-right-b\"></i></a>
	 &nbsp;&nbsp;&nbsp;&nbsp;".$boutonresa."</h4></div> ";

  $calendar .= "<div class=\"calendar\">" ;

  // affichage du nom des jours

  for ($i=0 ; $i<7 ;$i++)
  {
      $calendar .= "<div class=\"labelDay\">".$dayArray[$i]."</div>" ;
  }
  $calendar .= "<br>" ;

  // affichage des cases vides de debut
  $firstDay = getFirstDay($year,$month) ;
  for ($k = 1 ; $k < $firstDay ; $k++)
  {
      $calendar .= "<div class=\"labelNum\">&nbsp;</div>" ;
  }

  // affichage des jours
  for ($j = 1 ; $j <= $nb_jour ; $j++)
  {
        switch (checkDayOpen2($j,$month,$year,$epn))
        {
            case "ouvert":
               if ($month == date ("m") AND $j == date("d"))
               $calendar .= "<div class=\"labelNumCurrent\"><a href=\"index.php?m=3&month=".$month."&year=".$year."&jour=".$j."&mois=".$month."&annee=".$year."\"><span class=\"cal\">".$j."</span></a></div>" ;
               else
               $calendar .= "<div class=\"labelNum\"><a href=\"index.php?m=3&month=".$month."&year=".$year."&jour=".$j."&mois=".$month."&annee=".$year."\"><span class=\"cal\">".$j."</span></a></div>" ;
            break;
            case "ferme":
               if ($month == date ("m") AND $j == date("d"))
               $calendar .= "<div class=\"labelNumCurrent\">".$j."</div>" ;
               else
               $calendar .= "<div class=\"labelNumClose\">".$j."</div>" ;
            break;
            case "ferie":
               if ($month == date ("m") AND $j == date("d"))
               $calendar .= "<div class=\"labelNumCurrent\">".$j."</div>" ;
               else
               $calendar .= "<div class=\"labelNumOff\">".$j."</div>" ;
            break;
        }
  }

  // affichage des cases vides de fin
  $lastDay = getLastDay($year,$month) ;
  for ($l = 1 ; $l <= $lastDay ; $l++)
  {
      $calendar .= "<div class=\"labelNum\">&nbsp;</div>" ;
  }
  $calendar .= "<div style=\"clear:both;font-size:10px;padding-top:3px;\"></div></div>";

  return $calendar ;
}
//renvoi le numero du jour de la semaine de 0->lundi a 6->dimanche
function getDayNum($j,$m,$a)
{
  return date("z",mktime(0,0,0,$m,$j,$a)) ;
}

// renvoi le statut ouvert ou ferme en fonction des horaire de la journé
function checkHoraireDay($j,$m,$y,$epn)
{
  $row = getHoraire(date("w",mktime(0,0,0,$m,$j,$y)),$epn) ;
  if ($row["hor1_begin_horaire"]==0 AND $row["hor1_end_horaire"]==0 AND $row["hor2_begin_horaire"]==0 AND $row["hor2_end_horaire"]==0)
     return FALSE;
  else
     return TRUE;
}

// renvoi si le jour est ouvert ou fermé
function checkDayOpen($daynum,$year,$epn)
{
    $sql = "SELECT id_days_closed, state_days_closed FROM `tab_days_closed` WHERE `year_days_closed`='".$year."' AND `num_days_closed`='".$daynum."' AND `id_epn`='".$epn."'
		  ";
	  
$db=opendb();
$result = mysqli_query($db,$sql);
 closedb($db);
$nb=mysqli_num_rows($result);
    
  if ($nb==0)
  {
       return $nb;
  } else {
  $row=mysqli_fetch_array($result);
      return $row["id_days_closed"];
  }
}

// renvoi si le jour est ouvert ou fermé
function checkDayOpen2($j,$m,$year,$epn)
{
  $daynum = getDayNum($j,$m,$year);
  $sql = "SELECT `state_days_closed`
          FROM `tab_days_closed`
          WHERE `num_days_closed`='".$daynum."'
          AND `year_days_closed` = '".$year."'
		  AND `id_epn`='".$epn."'
		  ";
  $db=opendb();
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_array($result) ;
  closedb($db);
  if (mysqli_num_rows($result)==0)
  {
      if(FALSE==checkHoraireDay($j,$m,$year,$epn))
          return "ferme";
      else
          return "ouvert";
  }
  else if($row["state_days_closed"]=="F")
  {
      return "ferie";
  }
}
// met a jour un jour ferié
/*
function updateDay($daynum,$year,$epn)
{
  if (TRUE==checkDayOpen($daynum,$year,$epn))
  {
      //debug(checkDayOpen($daynum,$year));
	  $sql = "INSERT INTO `tab_days_closed` (`id_days_closed`, `year_days_closed`, `num_days_closed`, `state_days_closed`, `id_epn`) VALUES ('','".$year."','".$daynum."','F','".$epn."') ";
  }
  elseif(FALSE==checkDayOpen($daynum,$year,$epn))
  {
      $sql = "DELETE FROM `tab_days_closed` WHERE `num_days_closed`='".$daynum."' AND `year_days_closed`='".$year."'  WHERE id_epn='".$epn."' LIMIT 1" ;
  }
  $db=opendb();
  $result = mysqli_query($db,$sql);
  closedb($db);
  if ($result == TRUE)
     return TRUE;
  else
     return FALSE;
}
*/
function insertJourFerie($daynum,$year,$epn){
 $sql = "INSERT INTO `tab_days_closed` (`id_days_closed`, `year_days_closed`, `num_days_closed`, `state_days_closed`, `id_epn`) VALUES ('','".$year."','".$daynum."','F','".$epn."') ";
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
if ($result == TRUE){
     return TRUE;
  } else {
     return FALSE;
}
}

function deleteJourFerie($id){
   $sql = "DELETE FROM `tab_days_closed` WHERE `id_days_closed`='".$id."' " ;
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
if ($result == TRUE){
     return TRUE;
  } else {
     return FALSE;
}

}


// renvoi un calendrier du mois et de l'annee donnee pour determiner les jours feries
function getCalendarClose($year,$month,$epn)
{
  $calendar = "" ;
  // tableau des index des jours
  $dayArray = array("L","M","M","J","V","S","D") ;
  //nombre de jour dans le mois en cours
  $nb_jour  = date("t", mktime(0, 0, 0, $month, 1, $year));
$Pepn=$_SESSION["idepn"];
  //Affichage -------------------------------------

  //affichage du mois et de l'année
  $calendar = "<br><b><a name=".$month."></a>".getMonthName($month)." ".$year."</b>";

  $calendar .= "<div class=\"calendar2\">" ;

  // affichage du nom des jours

  for ($i=0 ; $i<7 ;$i++)
  {
      $calendar .= "<div class=\"labelDay\"><span class=\"cal\">".$dayArray[$i]."</span></div>" ;
  }
 
  // affichage des cases vides de debut
  $firstDay = getFirstDay($year,$month) ;
  for ($k = 1 ; $k < $firstDay ; $k++)
  {
      $calendar .= "<div class=\"labelNum\">&nbsp;</div>" ;
  }

  // affichage des jours
  for ($j = 1 ; $j <= $nb_jour ; $j++)
  {
     switch (checkDayOpen2($j,$month,$year,$epn))
     {
            case "ouvert":
               $calendar .= "<div class=\"labelNum\"><span class=\"cal\"><a href=\"".$_SERVER["REQUEST_URI"]."&idday=".getDayNum($j,$month,$year)."#".$month."\">".$j."</a></span></div>" ;
            break;
            case "ferme":
               $calendar .= "<div class=\"labelNumClose\"><span class=\"cal\"><a href=\"".$_SERVER["REQUEST_URI"]."&idday=".getDayNum($j,$month,$year)."#".$month."\">".$j."</a></span></div>" ;
            break;
            case "ferie":
               $calendar .= "<div class=\"labelNumOff\"><span class=\"cal\"><a href=\"".$_SERVER["REQUEST_URI"]."&idday=".getDayNum($j,$month,$year)."#".$month."\">".$j."</a></span></div>" ;
            break;
     }
  }

  // affichage des cases vides de fin
  $lastDay = getLastDay($year,$month) ;
  for ($l = 1 ; $l <= $lastDay ; $l++)
  {
      $calendar .= "<div class=\"labelNum\">&nbsp;</div>" ;
  }
  $calendar .= "</div>";

  return $calendar ;
}
//
// Configuration
//
//
// modifie le titre de l'espace
function convertHoraire($temps)
{
  $h = substr($temps,0,2) ;
  $m = substr($temps,3,2) ;
  $conv = (60*$h)+$m ;
  return $conv ;
}

//
function checkHoraire($h1begin,$h1end,$h2begin,$h2end)
{
    if ($h1begin!="" AND $h1end=="" AND $h2begin=="" AND $h2end!="") // pas de pause le midi
       return TRUE;
    elseif ($h1begin=="" and $h1end=="") // ferme le matin
       return TRUE;
    elseif ($h2begin=="" AND $h2end=="") // ferme l'apres smidi
       return TRUE;
    elseif ($h1end<$h1begin OR $h2end<$h2begin)  //l'heure de fin inferieur a l'heure de debut
       return FALSE ;
    elseif($h1begin=="" AND $h1end!="")     //
       return FALSE;
    elseif($h2end=="" AND $h2begin!="")     //
       return FALSE;
    elseif($h1end>$h2begin OR $h1end>$h2end OR $h1begin>$h2begin OR $h1begin>$h2end)
       return FALSE;
    else
       return TRUE ;
}


// modifie les champs de la config
function updateConfig($table,$array,$field,$idvalue,$epn)
{
    $sql ="";
    $sql .="UPDATE `".$table."` SET" ;
    $nb = count($array) ;
    $c = 1;
    foreach($array AS $key=>$value)
    {
        if ($c == $nb)
           $sql .="`".$key."` = '".$value."'" ;
        else
           $sql .="`".$key."` = '".$value."'," ;
        $c = $c+1;
    }
    $sql .=" WHERE `".$field."`='".$idvalue."'" ;
    $sql .=" AND id_espace='".$epn."' ";
    //echo $sql;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        return TRUE ;
    }
}

function updateresaconfig($epnr, $unitconfig, $maxtime_config,$resarapide, $duree_resarapide){
	$sql="UPDATE `tab_config` SET 
`unit_config`='".$unitconfig."',
`maxtime_config`='".$maxtime_config."',
`resarapide`='".$resarapide."',
`duree_resarapide`='". $duree_resarapide."' WHERE `id_espace`=".$epnr;
	$db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        return TRUE ;
    }
	
}


function getCyberName($epn)
{
    $sql = "SELECT `nom_espace` FROM `tab_espace` WHERE `id_espace`='".$epn."' " ;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $row = mysqli_fetch_array($result);
                
           return $row["nom_espace"] ;
    }
}
/////deprecated
/*
function getCyberLogo($epn)
{
$sql = "SELECT `logo_espace` FROM `tab_espace` WHERE `id_espace`='".$epn."' " ;
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $row = mysqli_fetch_array($result);
                
           return $row["logo_espace"] ;
    }
}
*/

function getCyberSpec($epn)
{
    $sql = "SELECT * FROM `tab_espace` WHERE `id_espace`='".$epn."' " ;
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


// Fonction diverses -----------------------------------------------------------

//getDayfr() retourne le jour de la semaine
function getDayfr($date) //,$format='D j F'
{
$date0=date('Y-n-j-w',strtotime($date));
$dateArr=explode("-",$date0);
$jourfr=$dateArr[3];
$jour=$dateArr[2];
$mois=$dateArr[1];
$annee=$dateArr[0];
$dayArr = array ("Dimanche","lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
 
 return $dayArr[$jourfr]." ".$jour." ".getMonthName($mois)." ".$annee ;
}

function getDatefr($date) //,$format='D j F à 10h'
{
$date0=date('Y-n-j-w',strtotime($date));
$dateArr=explode("-",$date0);
$jourfr=$dateArr[3];
$jour=$dateArr[2];
$mois=$dateArr[1];
$annee=$dateArr[0];
$date1=date('H:i',strtotime($date));
$heurearr=explode(":",$date1);
$heure=$heurearr[0].'h'.$heurearr[1];
$dayArr = array ("Dimanche","lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
 
 return $dayArr[$jourfr]." ".$jour." ".getMonthName($mois)." ".$annee." &agrave; ". $heure;
}

// getMonth()
// donne le mois en fonction du numero du mois
function getMonth($nb)
{
    switch ($nb)
    {
        case "1":
            $mois = "Janvier";
        break;
        case "2":
            $mois ="F&eacute;vrier";
        break;
        case "3":
            $mois ="Mars";
        break;
        case "4":
            $mois ="Avril";
        break;
        case "5":
            $mois ="Mai";
        break;
        case "6":
            $mois ="Juin";
        break;
        case "7":
            $mois ="Juillet";
        break;
        case "8":
            $mois ="Ao&ucirc;t";
        break;
        case "9":
            $mois ="Septembre";
        break;
        case "10":
            $mois ="Octobre";
        break;
        case "11":
            $mois ="Novembre";
        break;
        case "12":
            $mois ="D&eacute;cembre";
        break;
    }   
    return $mois;
}

//
// renvoi une date au format FR
function dateFr($date,$format='d/m/Y')
{
  return date($format,strtotime($date));
}

//
// checkdateformat
function checkDateFormat($datefr)
{
    $exp = '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}^' ;

    if(preg_match( $exp , $datefr )==1)
        return TRUE;
    else
        return FALSE;
}
// convertDate
function convertDate($datefr)
{
    $tmp = explode("/",$datefr) ;
    return $tmp[2].'-'.$tmp[1].'-'.$tmp[0] ;
}

//
// getPourcent($nb,$total)
// retourne un pourcentage a partir d'un nombre et d'un total
function getPourcent($nb,$total)
{
    if ($nb!="" AND $nb!=0 AND $total!="" AND $total!=0)
    {
    $pourcent = round(($nb*100)/$total) ;
    return $pourcent."%";
    } else {
    return "0";
    }
}



// getError()
// Affiche un message d'erreur
function getError($nb)
{
include("include/texte/error.php");

$error = "mes_".$nb;
return "<div>".$$error."</div>";
}

//
//getTime($temps)
// retourne l'heure et les minutes a partir du temps en minutes
function getTime($temps)
{
    if($temps < 60)
    {
      $heures = 0;
      $minutes= $temps;
    } else {
      $heures  = floor(($temps)/60);
      $minutes = $temps-($heures*60) ;
    }
    // creation de la variable time //
    if ($minutes == 0)
    {
        $time = $heures."h" ;
    } else {
        if ($heures == 0)
        {
            $time = $minutes."mn" ;
        } else {
            $time = $heures."h".$minutes."mn";
        }
    }
    return $time;
}

//
// function de debug
function debug($var)
{
    echo '<pre>';
    echo '########## Debut du debug de $var=&nbsp;' ;
    echo var_export($var) ;
    echo '########## Fin du debug' ;
    echo '</pre>';
}

?>
