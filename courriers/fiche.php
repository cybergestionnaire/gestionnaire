<?php
include("../connect_db.php");
include("../include/fonction.php");
require("../fpdf.php");

/* variables contenu texte */
	
	$destinataire = array("nom"=>"","adr"=>"","cp"=>'');
	//$objet = 'Liste de vos inscriptions aux ateliers';
	$epn=$_GET['epn'];
	$user=$_GET['user'];
	
	/*Recuperation des donnees  de l'epn emetteur*/ 
	$db = mysqli_connect($host,$userdb,$passdb,$database) ;
	$sql = "SELECT `nom_espace` , `adresse` , `nom_city` , `code_postale_city`, logo_espace
		FROM `tab_espace` , `tab_city` 
		WHERE `tab_city`.`id_city`=`tab_espace`.`id_city` 
		AND `tab_espace`.`id_espace` = '".$epn."' ";
	$row = mysqli_query($db, $sql);
	mysqli_close ($db) ;
	$pEpn=mysqli_fetch_array($row);
    
	$emetteur['nom'] = $pEpn['nom_espace'];
    $emetteur['adr'] = $pEpn['adresse'];
    $emetteur['cp']=$pEpn['code_postale_city'];
    $ville=$pEpn['nom_city'];
     $logo='../img/logo/'.$pEpn['logo_espace'];
     
/*Recuperation des donnees  de l'utilisateur*/ 
	$db = mysqli_connect($host,$userdb,$passdb,$database) ;
	$sql="SELECT `date_insc_user`,`nom_user`,`prenom_user`,`jour_naissance_user`,`mois_naissance_user`,`annee_naissance_user`,`adresse_user`,`tel_user`,`mail_user`,`login_user`,`nom_tarif`,`newsletter_user`,`donnee_tarif`,`nom_espace`,`nom_city`,`code_postale_city`, csp
		FROM tab_user, tab_city, tab_tarifs, tab_espace,tab_csp
		WHERE id_user=".$user."
		AND tab_city.id_city=tab_user.`ville_user`
        AND tab_user.`tarif_user`=tab_tarifs.`id_tarif`
	AND tab_user.csp_user=tab_csp.id_csp
        AND tab_user.`epn_user`=tab_espace.`id_espace`";
	$row = mysqli_query($db, $sql);
	mysqli_close ($db) ;
    $resultuser=mysqli_fetch_array($row);
 
	//transcription des données
	if ($resultuser['newsletter_user']==0){$news="Non";}else{$news="Oui";}
	
	$tarif=$resultuser['nom_tarif']." (".$resultuser['donnee_tarif'].")";
	//photo de profil
	$nomSE=str_replace(CHR(32),"",$resultuser['nom_user']);
	$prenomSE=str_replace(CHR(32),"",$resultuser['prenom_user']);
	$filenamephoto = "../img/photos_profil/".trim($nomSE)."_".trim($prenomSE).".jpg" ;
 
//**************************************//
   //texte d'intro fichier externe
	$fichierparag='txt_lettre/fiche.txt';
	$f=fopen($fichierparag,'r');
	$paragraphe =fread($f,filesize($fichierparag));
	fclose($f);
   
   //Texte du rÃ¨glement en page 2
   /*
	$fichierreglement='txt_lettre/reglement.txt';
	//Lecture du fichier texte
	$f=fopen($fichierreglement,'r');
	$reglement=fread($f,filesize($fichierreglement));
	fclose($f);
	*/
	
//**********************//
	

////**********ECRITURE DU COURRIER
	/* initialisation/configuration de la classe*/
	$courrier = new FPDF();

	$courrier->open();
	$courrier->SetAutoPageBreak(1, 2);
	//font
	$courrier->AddFont('SourceSansPro-Regular','','SourceSansPro-Regular.php');
	$courrier->SetFont('SourceSansPro-Regular','',18);
	
	$courrier->AddPage();
	
	
	
	/* BLOC RENSEIGNEMENTS UTILISATEUR */
	
	$courrier->Cell(0,5,"FICHE D'INSCRIPTION",0,1,'C',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(20);
	
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->Cell(0,5,"Coordonnées de l'adhérent",0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(15);
	
	
	if (file_exists($filenamephoto)) {
		$courrier->Image($filenamephoto,150,50,40);
	}
	
	$courrier->SetY(50);
	$courrier->Cell(40,8,"NOM : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Cell(40,8,"PRENOM : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Cell(40,8, "ADRESSE : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	
	$courrier->SetXY(50,50);
	$courrier->Cell(200,8,$resultuser['nom_user'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['prenom_user'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['adresse_user'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['code_postale_city']." ".$resultuser['nom_city'],0,1,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond; `nom_city` , `code_postale_city`,
	
	$courrier->Ln(10);
	$courrier->SetY(85);
	$courrier->Cell(40,8,"Date de Naissance : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Cell(40,8,"Téléphone(s) : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Cell(40,8,"Courriel : ",0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond; Catégorie Socio-professionnelle
	$courrier->Cell(40,8,"Date d'inscription : ",0,1,'R',false);
	$courrier->Cell(40,8,"EPN d'inscription : ",0,1,'R',false);
	$courrier->Cell(40,8,"Catégorie S-P : ",0,1,'R',false);
	$courrier->Ln(10);
	$courrier->Cell(40,8,"Tarif de l'adhésion : ",0,1,'R',false);
	$courrier->Cell(40,8,"Nom d'utilisateur : ",0,1,'R',false);
	$courrier->Cell(40,8,"Adh Newsletter : ",0,1,'R',false);
	
	$courrier->SetXY(50,85);
	$courrier->Cell(200,8,$resultuser['jour_naissance_user']."/".$resultuser['mois_naissance_user']."/".$resultuser['annee_naissance_user'],0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['tel_user'],0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['mail_user'],0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['date_insc_user'],0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['nom_espace'],0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['csp'],0,1,'L',false);
	
	$courrier->Ln(10);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$tarif,0,1,'L',false);
	$courrier->SetX(50);
	$courrier->Cell(200,8,$resultuser['login_user'],0,1,'L',false);
	$courrier->SetX(50);
	
	$courrier->Cell(200,8,$news,0,1,'L',false);
	
	$courrier->Ln(10);
	
	



/* paragraphe  */
	$courrier->SetFont('SourceSansPro-Regular','',12);
	$courrier->MultiCell(0,5,$paragraphe,0,'J',false);
	$courrier->Ln(15);
	
/* date et heure */
	$semaine = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
	$mois = array("","janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
	$dateLieu = "A ".$ville.", le ".$semaine[date("w")].' '.date("j")." ".$mois[date("n")]." ".date("Y");
	$courrier->Cell(0,5,$dateLieu,0,1,'R',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(10);


	/* Reglement  au verso page 2*/
/*
	$courrier->AddPage();
	$courrier->SetFont('SourceSansPro-Regular','',6);
	$courrier->MultiCell(0,4,utf8_decode($reglement),0,'L',false);
	
*/	
	/* bloc emetteur  en signature*/
	
	$courrier->SetFont('helvetica','',10);
	$courrier->Image($logo,10,260,25);
		
	$courrier->SetXY(35,260);
	$courrier->Cell(0,5,$emetteur['nom'],0,2,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->MultiCell(0,5,$emetteur['adr'],0,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->SetX(35);
	$courrier->MultiCell(0,5,$emetteur['cp']." ".$ville,0,'L',false);//largeur,hauteur,,texte,bodure,suite,alignement,couleur de fond;
	$courrier->Ln(10);
	
	
	
	
	$courrier->Output();
?>
