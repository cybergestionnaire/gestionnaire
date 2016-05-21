<?php
///FICHER DE FONCTIONS POUR LES MISES A JOURS

///Backup integral de la base
function backupbdd()
{
 include ("./connect_db.php");
 
new BackupMySQL(array(
	'username' => $userdb,
	'passwd' => $passdb,
	'dbname' => $database
	));

$sql="INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`) 
	VALUES ('','bac' ,NOW(), '1.2','1', 'Backup integral de la base effectue') ";
	
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
 
 if(FALSE==$result){ 
	return FALSE;
	}else{
		return TRUE;
	}
}

//*****************************FONCTIONS  PERENNES

//retrouver la dernière sauvegarde de la base avant de lancer les maj
function getLastBackup(){
$sql="SELECT `id_log`, `log_type`, `log_date` FROM `tab_logs` WHERE `log_type`='bac' AND MONTH(`log_date`)=MONTH(NOW()) AND YEAR(`log_date`)=YEAR(NOW())" ;
$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
	if(FALSE==$result){
		return FALSE;
	}else{
		if(mysqli_num_rows($result)==0){
		return FALSE;
		}else{
		return TRUE;
		}
	}
}



///Inserer dans les logs
function InsertLogMAJ($type,$version,$date,$comment)
{
$sql="INSERT INTO `tab_logs`(`id_log`, `log_type`, `log_date`, `log_MAJ`, `log_valid`, `log_comment`) 
VALUES ('', '".$type."','".$date."','".$version."','1','". $comment."') ";

$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
	if(FALSE==$result){
		return FALSE;
	}else{
		return TRUE;
	}

}

function modifNumMAJ($value)
{
$sql="UPDATE `tab_config` SET `name_config`=".$value;

$db=opendb();
$result = mysqli_query($db,$sql);
closedb($db);
	if(FALSE==$result){
		return FALSE;
	}else{
		return TRUE;
	}

}


/**
 * Output span with progress.
 *
 * @param $current integer Current progress out of total
 * @param $total   integer Total steps required to complete
 */
function outputProgress($current, $total,$table) {
    echo "<span style='position: absolute;z-index:$current;background:#FFF;'>".$table." : " . round($current / $total * 100) . "% </span>";
    myFlush();
    sleep();
}

/**
 * Flush output buffer
 */
function myFlush() {
    echo(str_repeat(' ', 256));
    if (@ob_get_contents()) {
        @ob_end_flush();
    }
    flush();
}

function getMajConfigVersion($idepn)
{
$sql="SELECT `name_config` FROM `tab_config` WHERE `id_espace`=".$idepn;
$db=opendb();
$result = mysqli_query($db, $sql);
closedb($db);
    if ($result == FALSE )
    {
        return FALSE ;
    }
    else
    {
	$row=mysqli_fetch_array($result);
	return $row["name_config"] ;
    }


}


//******** Ajout des tables supplémentaires


function createtabinscriptMAJ()
{
$sql="CREATE TABLE IF NOT EXISTS `tab_captcha` (
  `id_captcha` int(11) NOT NULL AUTO_INCREMENT,
  `capt_activation` ENUM('N', 'Y') NOT NULL,
  `capt_code` varchar(500) NOT NULL,
   PRIMARY KEY (`id_captcha`)
	) ENGINE=MyISAM ;";
	
	$db=opendb();
	$result = mysqli_query($db,$sql);
	closedb($db);
	if(FALSE==$result){	$row="echec"; }else{ $row="OK"; }
return $row;
}

function Tab_ins1()
{
	$sql="ALTER TABLE `tab_inscription_user` CHANGE `equipement_inscription_user` `equipement_inscription_user` VARCHAR( 50 ) NOT NULL ;";
	$db=opendb();
	$result = mysqli_query($db,$sql);
	closedb($db);
	if(FALSE==$result){	$row="echec"; }else{ $row="OK"; }
return $row;
}

function Tab_ins2()
{
	$sql="ALTER TABLE `tab_inscription_user` CHANGE `connaissance_inscription_user` `connaissance_inscription_user` INT(11) NOT NULL ;";
	$db=opendb();
	$result = mysqli_query($db,$sql);
	closedb($db);
	if(FALSE==$result){	$row="echec"; }else{ $row="OK"; }
return $row;
}

function alterMessageMAJ()
{
		$sql="ALTER TABLE `tab_messages` CHANGE `mes_date` `mes_date` DATETIME NULL DEFAULT NULL ;";
	$db=opendb();
	$result = mysqli_query($db,$sql);
	closedb($db);
	if(FALSE==$result){	$row="echec"; }else{ $row="OK"; }
return $row;
}



function insertCapt()
{
	$sql="INSERT INTO `tab_captcha`(`id_captcha`, `capt_activation`, `capt_code`) VALUES (1,'N','') ;";
		$db=opendb();
	$result = mysqli_query($db,$sql);
	closedb($db);
	if(FALSE==$result){	$row="echec"; }else{ $row="OK"; }
return $row;
}





?>