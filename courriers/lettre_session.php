<?php
include("../connect_db.php");
include("../include/fonction.php");
require("../fpdf.php");



	/* variables contenu texte */
	$emetteur = array("nom"=>"","adr"=>"","cp"=>'');
	$destinataire = array("nom"=>"","adr"=>"","cp"=>'');
	$objet = 'Liste de vos inscriptions aux sessions';
	$epn=$_GET['epn'];
	$user=$_GET['user'];
	
	/*Recuperation des donnees  de l'epn emetteur*/ 
	$db = mysqli_connect($host,$userdb,$passdb,$database) ;

	//donnees EPN
	$sqlepn = "SELECT `nom_espace` , `adresse` , `nom_city` , `code_postale_city`, logo_espace
		FROM `tab_espace` , `tab_city` 
		WHERE `tab_city`.`id_city`=`tab_espace`.`id_city` 
		AND `tab_espace`.`id_espace` = '".$epn."' ";
	$rowepn = mysqli_query($db, $sqlepn);
	$pEpn=mysqli_fetch_array($rowepn);
  $emetteur['nom'] = $pEpn['nom_espace'];
	$emetteur['adr'] = $pEpn['adresse'];
	$emetteur['cp']=$pEpn['code_postale_city'];
	$ville=$pEpn['nom_city'];
	$logo='../img/logo/'.$pEpn['logo_espace'];
		
/*Recuperation des donnees  de l'utilisateur*/ 
	$sql="SELECT nom_user, prenom_user, adresse_user, ville_user, nom_city, `code_postale_city` , ville_user
		FROM tab_user, tab_city
		WHERE id_user=".$user."
		AND tab_city.id_city=tab_user.ville_user";
	$row = mysqli_query($db, $sql);
	$resultuser=mysqli_fetch_array($row);
	$destinataire['nom'] = $resultuser['prenom_user']." ".$resultuser['nom_user'];
		$destinataire['adr'] = $resultuser['adresse_user'];
	if($resultuser['ville_user']>16 AND $resultuser['ville_user']<20){
			$destinataire['cp']="";
	} else {
		$destinataire['cp']=$resultuser['code_postale_city']." ".$resultuser['nom_city'];
	}
	
///texte d'intro donnees de la base
	$sqltextes="SELECT * FROM `tab_courriers` WHERE `courrier_name`=3";
	$rowtextes = mysqli_query($db, $sqltextes);
	$nb= mysqli_num_rows($rowtextes);

	if($nb!=0){
		for ($i=1;$i<=$nb;$i++)
		{
				$texteslettre=mysqli_fetch_array($rowtextes);
				$rowtextesl[$texteslettre["courrier_type"]] = $texteslettre["courrier_text"] ;
	
		}
        /*   $arraytype=array(		1=>"Introduction",		2=>"Sujet/object",		3=>"Corps du texte",		4=>"Signature"		);*/
	if(isset($rowtextesl[1]))	{
		$introduction=$rowtextesl[1];} else { $introduction='';}
	if(isset($rowtextesl[3]))	{
		$paragraphe=$rowtextesl[3];} else { $paragraphe='';}
	if(isset($rowtextesl[4]))	{
		$signature=$rowtextesl[4];} else { $signature='';}
		} else {
			$introduction='Attention aucun texte d\'introduction, aucune signature n\'ont été rentré dans la base, veuillez allez dans la configuration des courriers pour en rentrer d\'urgence !';
			$paragraphe='';
			$signature='';
		}


//Recuperation de laliste des sessions //
	$an=date('Y')."-".date('m')."-".date('d');
	$sqlsession="SELECT rel_session_user.`id_session`,`session_titre`,`session_detail`
	FROM `rel_session_user`,tab_session,tab_session_sujet
	WHERE `id_user` ='".$user."'
	AND tab_session.`id_session`=rel_session_user.`id_session`
	AND `status_rel_session` =0
	AND `status_session` =0
	AND tab_session_sujet.`id_session_sujet`= tab_session.nom_session
	GROUP BY rel_session_user.`id_session` 
	";
	$rowsession = mysqli_query($db, $sqlsession);

if($rowsession==FALSE){$nbses=0;} else { $nbses=mysqli_num_rows($rowsession);}	
	
	
//recuperation de la liste des ateliers en attente
	$sqlattente="SELECT rel_session_user.`id_session`,`session_titre`,`session_detail`
	FROM `rel_session_user`,tab_session,tab_session_sujet
	WHERE `id_user` ='".$user."'
	AND tab_session.`id_session`=rel_session_user.`id_session`
	AND `status_rel_session` =2
	AND `status_session` =0
	AND tab_session_sujet.`id_session_sujet`= tab_session.nom_session
	GROUP BY rel_session_user.`id_session` ";
	$rowsessionattente = mysqli_query($db, $sqlattente);
	if(mysqli_num_rows($rowsessionattente)==0){
		$attente=0;
		} else {
		$nba=mysqli_num_rows($rowsessionattente);
		$attente=1;
	}
	
	//fin de la collecte des donnees
	mysqli_close ($db) ;
//***************************************//

///**********ECRITURE DU COURRIER
	/* initialisation/configuration de la classe*/
	$courrier = new FPDF();
	//font declaration des polices !
	$courrier->AddFont('SourceSansPro-Regular','','SourceSansPro-Regular.php');
	$courrier->AddFont('SourceSansPro-Semibold','','SourceSansPro-Semibold.php');
	$courrier->AddFont('SourceSansPro-LightItalic','','SourceSansPro-LightItalic.php');
	
	$courrier->open();
	$courrier->SetAutoPageBreak(1,15);
	$courrier->AddPage();
	
	/* CrÃ©ation bloc emetteur */
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->Image($logo,5,10,45,27);
	$courrier->Ln(20);
	$courrier->SetXY(55,15);
	$courrier->Cell(0,5,$emetteur['nom'],0,2,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(55);
	$courrier->MultiCell(0,5,$emetteur['adr'],0,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(55);
	$courrier->MultiCell(0,5,$emetteur['cp']." ".$ville,0,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(20);
	
	/* CrÃ©ation bloc destinataire */
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->SetX(120);
	$courrier->Cell(0,5,$destinataire['nom'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(120);
	$courrier->MultiCell(0,5,$destinataire['adr'],0,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(120);
	$courrier->Cell(0,5,$destinataire['cp'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(10);
	
	
	/* date et heure */
	$semaine = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
	$mois = array("","janvier","février","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","décembre");
	$dateLieu = "A ".$ville.", le ".$semaine[date("w")].' '.date("j")." ".$mois[date("n")]." ".date("Y");
	$courrier->Cell(0,5,$dateLieu,0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(10);
	
	/* paragraphe d'intro */
	$courrier->SetFont('SourceSansPro-Regular','',13);
	$courrier->MultiCell(0,5,$introduction,0,'J',false);
	$courrier->Ln(2);
	
	/* corps du message */
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->MultiCell(0,5,$paragraphe,0,'J',false);
	$courrier->Ln(15);
	
//********liste des sessions enregistrées
	
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->Ln();
	//Données
	$dates='';
	if($nbses>0){
	while($rowS=mysqli_fetch_array($rowsession))
		{
	///recuperer la liste des dates
	$ids=$rowS["id_session"];
	$db = mysqli_connect($host,$userdb,$passdb,$database) ;
		$sqldates="SELECT `date_session` FROM `tab_session_dates` WHERE `id_session` ='".$ids."'";
		$rowsdates = mysqli_query($db, $sqldates);
		mysqli_close ($db) ;
	$nbrdates=mysqli_num_rows($rowsdates);
	
	for ($f=0; $f<$nbrdates ; $f++){
		$rowd=mysqli_fetch_array($rowsdates);
		$dates=$dates.getDatefr($rowd["date_session"])."\n";
	
	}
	
	
	
		$courrier->SetFont('SourceSansPro-Semibold','',12);
		$titre=$rowS['session_titre']." : ";
		$courrier->Write(7,$titre);
		$courrier->Ln();
		$courrier->SetFont('SourceSansPro-LightItalic','',12);
		$courrier->Write(5,$rowS['session_detail']);
		$courrier->Ln(7);
		
		$courrier->SetFont('SourceSansPro-Regular','',12);
		$courrier->SetX(30);
		$courrier->Multicell(0,5,$dates,0,'L',false);
		
		$courrier->Ln();
		
		$dates="";
		} 
	 } else {
		 $courrier->Write(5,"Vous n'êtes inscrit à aucune session actuellement.");
	 }
	$courrier->Ln(15);
	
//****Liste d'attente aux ateliers 
	if($attente>0){
	$courrier->MultiCell(0,5,'Vous êtes également inscrit en attente pour les sessions suivantes',0,'J',false);

	
		if($nba>0){
		while($rowa=mysqli_fetch_array($rowsessionattente))
			{
		///recuperer la liste des dates
		$idsa=$rowa["id_session"];
		$db = mysqli_connect($host,$userdb,$passdb,$database) ;
			$sqldates="SELECT `date_session` FROM `tab_session_dates` WHERE `id_session` ='".$idsa."'";
			$rowsdates = mysqli_query($db, $sqldates);
			mysqli_close ($db) ;
		$nbrdates=mysqli_num_rows($rowsdates);
		
		for ($f=0; $f<$nbrdates ; $f++){
			$rowd=mysqli_fetch_array($rowsdates);
			$dates=$dates.getDatefr($rowd["date_session"])."\n";
		
		}
			$courrier->SetFont('SourceSansPro-Semibold','',12);
			$titre=$rowa['session_titre']." : ";
			$courrier->Write(7,$titre);
			$courrier->Ln();
			$courrier->SetFont('SourceSansPro-LightItalic','',12);
			$courrier->Write(5,$rowa['session_detail']);
			$courrier->Ln(7);
		
			$courrier->SetFont('SourceSansPro-Regular','',12);
			$courrier->Multicell(0,7,$dates,0,'L',false);
			
			$courrier->Ln();
			
			$dates="";
			} 
			
	}
	} 
	$courrier->Ln(15);
	
	/* politesse */
	
	//$courrier->Image('../img/logos_courrier.jpg',10,275,35);
	$courrier->SetFont('SourceSansPro-Regular','',8);
	$courrier->SetXY(10,270);
	$courrier->MultiCell(0,5,$signature,'T','C',false);
	
	$courrier->Output();
?>
