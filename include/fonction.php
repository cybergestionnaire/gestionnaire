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
function opendb() {
    include ("./connect_db.php");
    
    if ($port == "" OR !is_numeric($port)){
        $port = "3306" ;
    }
    
    /*creation de la liaison avec la base de donnees*/
    $db = mysqli_connect($host, $userdb, $passdb, $database) ;
    /*en cas d'echec*/
    if (mysqli_connect_errno()) {
       return false;
    } else {
        $db->set_charset("utf8");
        return $db ;
    }
}     

//
// closedb()
// fermeture de la connexion a la base de donnée
function closedb ($mydb) {
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

//converti une date en jour de l'annee
function convertDateJour($jour)
{
$jouran=strftime("%j",strtotime($jour)) ;
return ($jouran);
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


// ajout de la relation resa / computer / usage 1=resa, 2=atelier
// Laissé en commentaire le temps de définir si c'est utile ou non...

// function insertrelresa($idresa,$usage,$titreatelier)
// {
    // $sql="INSERT INTO `rel_resa_usage`(`id_relresa`, `id_usage`, `id_resa`,`id_titreatelier`) VALUES ('','".$usage."','".$idresa."','".$titreatelier."')";
     // $db=opendb();
   // $result = mysqli_query($db,$sql) ;
    
    // closedb($db);
    // if ($result ==TRUE)
    // {
        // return TRUE;
    // } else {
        // return FALSE;
    // }
    
// }

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
function getCalendar($year, $month, $day, $epn)
{
    $calendar = "" ;
    // tableau des index des jours
    $dayArray = array("L","M","M","J","V","S","D") ;
    //nombre de jour das le mois en cours
    $nb_jour  = date("t", mktime(0, 0, 0, $month, 1, $year));
    //epn sélectionné
    
    /// GNIIIII ???? à quoi sert de donner l'argument $epn ????
    // $Pepn = $_SESSION["idepn"];
    // if ($epn == $Pepn) {
        // $epn = $epn;
    // } else {
        // $epn = $Pepn;
    // }
    
    
    //Bouton pour la resa a posteriori que animateurs ou admin
    if ($_SESSION["status"] == 3 OR $_SESSION["status"] == 4){
        $boutonresa = "<a href=\"index.php?a=19\"><i class=\"ion ion-log-in\"></i></a>";
    } else {
        $boutonresa = "";
    }
    //Affichage -------------------------------------

    //affichage du mois et de l'année
    $calendar = "<div align=\"center\" class=\"titreCal\">
                    <h4 >
                        <a href=\"?m=3&jour=" . $day . "&mois=" . ($month - 1) . "&annee=" . $year . "\"><i class=\"ion-arrow-left-b\"></i></a>
                        &nbsp;&nbsp;<b>" . getMonthName($month) . " " . $year . "</b>
                        <a href=\"?m=3&jour=" . $day . "&mois=".($month+1)."&annee=".$year."\">&nbsp;&nbsp;<i class=\"ion-arrow-right-b\"></i></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;" . $boutonresa . "
                    </h4>
                </div> ";

    $calendar .= "<div class=\"calendar\">" ;

    // affichage du nom des jours
    for ($i = 0 ; $i < 7 ; $i++) {
        $calendar .= "<div class=\"labelDay\">" . $dayArray[$i] . "</div>" ;
    }
    $calendar .= "<br>" ;

    // affichage des cases vides de debut
    $firstDay = getFirstDay($year,$month) ;
    for ($k = 1 ; $k < $firstDay ; $k++) {
        $calendar .= "<div class=\"labelNum\">&nbsp;</div>" ;
    }

    // affichage des jours
    for ($j = 1 ; $j <= $nb_jour ; $j++) {
        switch (checkDayOpen2($j, $month, $year, $epn)) {
            case "ouvert":
                if ($j == $day)
                    $calendar .= "<div class=\"labelNumCurrent\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">".$j."</span></a></div>" ;
                else if ($year == date('Y') AND $month == date ("m") AND $j == date("d"))
                    $calendar .= "<div class=\"labelNumToday\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">".$j."</span></a></div>" ;
                else 
                    $calendar .= "<div class=\"labelNum\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">".$j."</span></a></div>" ;
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
    $lastDay = getLastDay($year ,$month) ;
    for ($l = 1 ; $l <= $lastDay ; $l++) {
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
    $db  = opendb();
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
    $db  = opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if ($result == TRUE) {
        return TRUE;
    } else {
        return FALSE;
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


// Fonction diverses -----------------------------------------------------------

//getDayfr() retourne le jour de la semaine
function getDayFR($date) {//,$format='D j F'

    $date0   = date('Y-n-j-w',strtotime($date));
    $dateArr = explode("-",$date0);
    $jourfr  = $dateArr[3];
    $jour    = $dateArr[2];
    $mois    = $dateArr[1];
    $annee   = $dateArr[0];
    $dayArr  = array ("Dimanche","lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");

    return $dayArr[$jourfr] . " " . $jour . " " . getMonthName($mois) . " " . $annee ;
}

function getDateFR($date) //,$format='D j F à 10h'
{
    $date0    = date('Y-n-j-w',strtotime($date));
    $dateArr  = explode("-", $date0);
    $jourfr   = $dateArr[3];
    $jour     = $dateArr[2];
    $mois     = $dateArr[1];
    $annee    = $dateArr[0];
    $date1    = date('H:i' ,strtotime($date));
    $heurearr = explode(":", $date1);
    $heure    = $heurearr[0] . 'h' . $heurearr[1];
    $dayArr   = array ("Dimanche", "lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

    return $dayArr[$jourfr] . " " . $jour . " " . getMonthName($mois) . " " . $annee . " &agrave; " . $heure;
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
            $mois = "F&eacute;vrier";
        break;
        case "3":
            $mois = "Mars";
        break;
        case "4":
            $mois = "Avril";
        break;
        case "5":
            $mois = "Mai";
        break;
        case "6":
            $mois = "Juin";
        break;
        case "7":
            $mois = "Juillet";
        break;
        case "8":
            $mois = "Ao&ucirc;t";
        break;
        case "9":
            $mois = "Septembre";
        break;
        case "10":
            $mois = "Octobre";
        break;
        case "11":
            $mois = "Novembre";
        break;
        case "12":
            $mois = "D&eacute;cembre";
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

    if(preg_match($exp , $datefr ) == 1)
        return TRUE;
    else
        return FALSE;
}
// convertDate
function convertDate($datefr)
{
    $tmp = explode("/",$datefr) ;
    return $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0] ;
}

//
// getPourcent($nb,$total)
// retourne un pourcentage a partir d'un nombre et d'un total
function getPourcent($nb, $total) {
    if ($nb != "" AND $nb != 0 AND $total != "" AND $total != 0) {
        $pourcent = round(($nb * 100) / $total) ;
        return $pourcent . "%";
    } else {
        return "0";
    }
}



// getError()
// Affiche un message d'erreur
function getError($nb) {
    include("include/texte/error.php");
    $error = "mes_".$nb;
    return "<div>".$$error."</div>";
}

//
//getTime($temps)
// retourne l'heure et les minutes a partir du temps en minutes
function getTime($temps) {
    if($temps < 60) {
      $heures  = 0;
      $minutes = $temps;
    } else {
      $heures  = floor(($temps)/60);
      $minutes = $temps-($heures*60) ;
    }
    // creation de la variable time //
    if ($minutes == 0) {
        $time = $heures."h" ;
    } else {
        if ($heures == 0) {
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
