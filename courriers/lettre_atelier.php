<?php
include("../connect_db.php");
include("../include/fonction.php");
require("../fpdf.php");

$epn=$_GET['epn'];
$user=$_GET['user'];

//***Recuperation des données de la base
$db = mysqli_connect($host,$userdb,$passdb,$database) ;

/*Recuperation des donnees  de l'epn emetteur*/ 
$emetteur = array("nom"=>"","adr"=>"","cp"=>'',"logo"=>'');
$sqlepn = "SELECT `nom_espace` , `adresse` , `nom_city` , `code_postale_city`, logo_espace
		FROM `tab_espace` , `tab_city` 
		WHERE `tab_city`.`id_city`=`tab_espace`.`id_city` 
		AND `tab_espace`.`id_espace` = '".$epn."' ";
	$rowepn = mysqli_query($db, $sqlepn);
	$pEpn=mysqli_fetch_array($rowepn);
  $emetteur['nom'] = $pEpn['nom_espace'];
  $emetteur['adr'] = $pEpn['adresse'];
  $emetteur['cp']=$pEpn['code_postale_city'].' '.$pEpn['nom_city'];
	$emetteur['logo']='../img/logo/'.$pEpn['logo_espace'];
	
/*Recuperation des donnees  de l'utilisateur*/ 
$destinataire = array("nom"=>"","adr"=>"","cp"=>'');
	$sqluser="SELECT nom_user, prenom_user, adresse_user, ville_user, nom_city, `code_postale_city` 
		FROM tab_user, tab_city
		WHERE id_user=".$user." 
		AND tab_city.id_city=tab_user.ville_user";
	$rowuser = mysqli_query($db, $sqluser);
	$resultuser=mysqli_fetch_array($rowuser);
	$destinataire['nom'] = $resultuser['prenom_user']." ".$resultuser['nom_user'];
	$destinataire['adr'] = $resultuser['adresse_user'];
	if($resultuser['ville_user']>16 AND $resultuser['ville_user']<20){
			$destinataire['cp']="";
	} else {
		$destinataire['cp']=$resultuser['code_postale_city']." ".$resultuser['nom_city'];
	}
	
	/* date et heure */
	$semaine = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
	$mois = array("","janvier","février","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","décembre");
	$dateLieu = "A ".$pEpn['nom_city'].", le ".$semaine[date("w")].' '.date("j")." ".$mois[date("n")]." ".date("Y");
	

 //texte d'intro donnees de la base
	$sqltextes="SELECT * FROM `tab_courriers` WHERE `courrier_name`=2";
	$rowtextes = mysqli_query($db, $sqltextes);
	$nb= mysqli_num_rows($rowtextes);

	if($nb!=0){
		for ($i=1;$i<=$nb;$i++)
		{
				$texteslettre=mysqli_fetch_array($rowtextes);
				$rowtextesl[$texteslettre["courrier_type"]] = $texteslettre["courrier_text"] ;
	
		}
        /*   $arraytype=array(		1=>"Introduction",		2=>"Sujet/object",		3=>"Corps du texte",		4=>"Signature"		);*/
	if($rowtextesl[1]<>'')	{
		$introduction=$rowtextesl[1];} else { $introduction='';}
	if($rowtextesl[3]<>'')	{
		$paragraphe=$rowtextesl[3];} else { $paragraphe='';}
	if($rowtextesl[4]<>'')	{
		$signature=$rowtextesl[4];} else { $signature='';}
		} else {
			$introduction=utf8_decode('Attention aucun texte d\'introduction, aucune signature n\'ont été rentré dans la base, veuillez allez dans la configuration des courriers pour en rentrer d\'urgence !');
			$paragraphe='';
			$signature='';
		}

//Recuperation de laliste des ateliers //
	$an=date('Y')."-".date('m')."-".date('d');
	$sqlatelier="SELECT atelier.id_atelier,`date_atelier`,`heure_atelier`,duree_atelier, suj.label_atelier,suj.content_atelier
			FROM `tab_atelier` AS atelier, `rel_atelier_user` AS rel, tab_atelier_sujet AS suj
			WHERE atelier.id_atelier = rel.id_atelier
			AND atelier.id_sujet=suj.id_sujet
			AND rel.id_user=".$user."
			AND date_atelier >= '".$an."'
			AND status_rel_atelier_user < 2
			ORDER BY `date_atelier` ASC";
	$rowatelier = mysqli_query($db, $sqlatelier);
	if($rowatelier==FALSE){$nba=0 ;} else {$nba=mysqli_num_rows($rowatelier);}
	

//recuperation de la liste des ateliers en attente
	$sqlatelierattente="SELECT atelier.id_atelier,`date_atelier`, `heure_atelier`,duree_atelier, suj.label_atelier,suj.content_atelier
		FROM `tab_atelier` AS atelier, `rel_atelier_user` AS rel, tab_atelier_sujet AS suj
		WHERE atelier.id_atelier = rel.id_atelier
		AND atelier.id_sujet=suj.id_sujet
		AND rel.id_user=".$user."
		AND date_atelier >= '".$an."'
		AND status_rel_atelier_user = 2
		ORDER BY `date_atelier` ASC";
	$rowatelierattente = mysqli_query($db, $sqlatelierattente);
	if($rowatelierattente==FALSE){$nbattente=0;} else {$nbattente=mysqli_num_rows($rowatelierattente);}	
	
	/*
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
	if($rowsession==FALSE){$nbsess=0;} else { $nbsess=mysqli_num_rows($rowsession);}	
	
//recuperation de la liste des ateliers en attente
	$sqlattente="SELECT rel_session_user.`id_session`,`session_titre`,`session_detail`
	FROM `rel_session_user`,tab_session,tab_session_sujet
	WHERE `id_user` ='".$user."'
	AND tab_session.`id_session`=rel_session_user.`id_session`
	AND `status_rel_session` =2
	AND `status_session` =0
	AND tab_session_sujet.`id_session_sujet`= tab_session.nom_session
	GROUP BY rel_session_user.`id_session` ";
	$rowsessionattente = mysqli_query($db, $sqlsessattente);
	if(mysqli_num_rows($rowsessionattente)==0){	$nbattentesess=0;	} else {$nbattentesess=mysqli_num_rows($rowsessionattente);}
*/

//fin des donnees extraites de la base
mysqli_close ($db) ;



///**********ECRITURE DU COURRIER*******************//

class PDF extends FPDF
{
function Header()
{
   global $emetteur;
    // Arial gras 15
    $this->SetFont('SourceSansPro-Regular','',10);
    // insertion logo
		$this->Image($emetteur['logo'],8,12,30);
    $this->SetXY(45,15);
    // Titre
    $this->Cell(0,5,$emetteur['nom'],0,2,'L',false);
		$this->SetX(45);
		$this->MultiCell(0,5,$emetteur['adr'],0,'L',false);
		$this->SetX(45);
		$this->MultiCell(0,5,$emetteur['cp'],0,'L',false);
			
    // Saut de ligne
    $this->Ln(15);
}

function Footer()
{
	global $signature;
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Arial italique 8
    $this->SetFont('Arial','I',8);
    // Couleur du texte en gris
    $this->SetTextColor(128);
    // Numéro de page
		$this->MultiCell(0,5,$signature,'T','C',false);
    $this->Cell(0,0,'Page '.$this->PageNo(),0,0,'R');
}

function lettredestinataire($destinataire,$dateLieu)
{
	$this->SetFont('SourceSansPro-Regular','',12);
	$this->SetX(120);
	$this->Cell(0,5,$destinataire['nom'],0,1,'L',false);
	$this->SetX(120);
	$this->Cell(0,5,$destinataire['adr'],0,1,'L',false);
	$this->SetX(120);
	$this->Cell(0,5,$destinataire['cp'],0,1,'L',false);
	$this->Ln(10);
	//ligne de la date et du lieu aligné à droite
	$this->Cell(0,5,$dateLieu,0,1,'R',false);
	$this->Ln(10);
}

function lettreobjet($introduction,$paragraphe)
{
    // Arial 13
    $this->SetFont('SourceSansPro-Regular','',13);
     // Titre
    $this->MultiCell(0,5,$introduction,0,'J',false);
    // Saut de ligne
    $this->Ln(4);
		
		// Arial 12
    $this->SetFont('SourceSansPro-Regular','',12);
     // Titre
    $this->MultiCell(0,5,$paragraphe,0,'J',false);
    // Saut de ligne
    $this->Ln(15);
}

function lettreatelier($rowatelier,$nba,$nbattente,$rowatelierattente)
{
 //********liste d'inscription aux ateliers 
   if($nba>0){
		$this->SetFont('SourceSansPro-Regular','',12);
		 while($atelier=mysqli_fetch_array($rowatelier))
	 {
		$contenuatelier1=getDayfr($atelier['date_atelier'])." ".utf8_decode("à")." ".$atelier['heure_atelier']." : ".$atelier['label_atelier']."\n";
		$contenuatelier2=utf8_decode("Détail")." : ".$atelier['content_atelier']."\n\n";
			$this->SetFont('SourceSansPro-Semibold','',12);
		$this->MultiCell(0,5,$contenuatelier1,0,'J',false);
			$this->SetFont('SourceSansPro-LightItalic','',12);
		$this->MultiCell(0,5,$contenuatelier2,0,'J',false);
	 }
	} else {
		$this->Write(5,utf8_decode("Vous n'êtes inscrit à aucun atelier actuellement."));
	}
	
	$this->Ln(15);
	
	//****Liste d'attente aux ateliers 
	if($nbattente>0){
	$this->MultiCell(0,5,utf8_decode('Vous êtes inscrit(e) en attente pour les ateliers suivants'),0,'J',false);
	while($row2=mysqli_fetch_array($rowatelierattente))
	 {
		$contenuatelier1=getDayfr($row2['date_atelier'])." ".utf8_decode("à")." ".$row2['heure_atelier']." : ".$row2['label_atelier']."\n";
		$contenuatelier2=utf8_decode("Détail")." : ".$row2['content_atelier']."\n\n";
			$this->SetFont('SourceSansPro-Semibold','',12);
		$this->MultiCell(0,5,$contenuatelier1,0,'J',false);
			$this->SetFont('SourceSansPro-LightItalic','',12);
		$this->MultiCell(0,5,$contenuatelier2,0,'J',false);
		 }
	
	$this->Ln(8);
		
	}

}
}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AddFont('SourceSansPro-Regular','','SourceSansPro-Regular.php');
$pdf->AddFont('SourceSansPro-Semibold','','SourceSansPro-Semibold.php');
$pdf->AddFont('SourceSansPro-LightItalic','','SourceSansPro-LightItalic.php');

$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->lettredestinataire($destinataire,$dateLieu);
$pdf->lettreobjet($introduction,$paragraphe);
$pdf->lettreatelier($rowatelier,$nba,$nbattente,$rowatelierattente);
$pdf->Output();





?>