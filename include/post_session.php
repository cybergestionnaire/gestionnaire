<?php
/*
   
 include/post_atelier.php V0.1

*/

$idsession =  $_GET["idsession"];
$m =  $_GET["m"];

//
/*
$statusarray=array(
	0=>"Atelier En cours",
	1=>"En programmation",
	2=>"Atelier Annulé / Annuler",
	3=>"Supprimer"
);*/


if ($_POST["submit_session"] !="")   // si le formulaire est posté
{
//recuperation et traitement des variables
   
	$nbplace =  $_POST["nbplace"];
	$nom =  $_POST["nom"]; 
	$nbre_dates =  $_POST["nbre_dates"];
	$anim=$_POST["anim"];
	$salle=$_POST["salle"];
	$tarif      = $_POST["tarif"] ;
	
	
	
	//////////////////classer par ordre num les dates
	function my_sort($f,$g)
		{
		if (strtotime($f)==strtotime($g)) return 0;
		   return (strtotime($f)<strtotime($g))?-1:1;
		}

	
	
	
	
if ($m !="" AND $m!=3)  // verifier si on envoie la creation ou la modification
	{	
	////Compulser les dates
	if($m==1){
		//verification du nombre de dates....
		$y=0;
		for($i=1;$i<=$nbre_dates;$i++){
			$d=$_POST["date".$i];
			if ($d<>''){
				$sessiondates[$y]=$d;
				}
			$y=$y+1;
			}
		$resultat=count($sessiondates); // variable pour comparaison nombre
		//trier le tableau dates
		$arraydate=$sessiondates;
		usort($arraydate,"my_sort"); //de 0 à nbredates !
		//debug($arraydate);
		$dates=$arraydate[$nbre_dates-1]; //donner la derniere date comme date reference
	
	}else{
		$nbre_origin=getSessionNbreDates($idsession); //nombre initial entrée lors de la premiere creation
		//recompiler les dates mais sans les ranger
		for($i=1;$i<=$nbre_dates;$i++){
			$d=$_POST["date".$i];
			if ($d<>''){
				$sessiondates[$i]=$d;
				}
			}
		$resultat=count($sessiondates); // variable pour comparaison nombre
		$dates=$sessiondates[1]; //variable la première pour la liste d'affichage
		$arraydate=[];
		//debug($sessiondates);
	}
		
	///entrer les données dans la base	   
	//1 s'il manque des dates
	if(TRUE==($resultat<$nbre_dates)){
		
		$_SESSION['sauvegarde']=$_POST;
		header('Location: ./index.php?a=31&m='.$m.'&idsession='.$idsession.'&mesno=45');
		exit; 	
	}else{
	 
		 if (!$nom | !$nbre_dates | !$nbplace)
		  {
			$mess= getError(4) ; //autres champs manquants
		  }
		  else
			{
		
		//Insertion des données
		switch($m)  
		{
		case 1:   // ajout planification d'une session
			
			$idsession=addSession($dates,$nom,$nbplace,$nbre_dates,$anim,$salle,$tarif);	
			 if (FALSE == $idsession )
			 {
			     header("Location: ./index.php?a=37&mesno=0");
			 }
			 else
			 {
				
				for($i=0;$i<$nbre_dates;$i++){
								
					insertDateSessions($idsession,$arraydate[$i],0);
				}
				 header("Location: ./index.php?a=37");
		       }
				
		    break;
		    
		case 2:   // modifie programmation session
			
			//recuperation des dates pour modification/suppression
			
			 if (FALSE == modifSession($idsession,$dates,$nom,$nbplace,$nbre_dates,$anim,$salle,$tarif))
			 {
			    header("Location: ./index.php?a=37&mesno=0");
			 }
			 else
			 {
				
				if($nbre_origin==$nbre_dates){  ///en cas de suppression de dates ou modif
					$o=0;
					//en cas de modification , modifier les nouvelles dates
					for($i=1;$i<=$nbre_dates;$i++){
						if(isset($_POST["statutdate".$i])){		
						
						//recuperer les status envoyés par les selects
							if($_POST["statutdate".$i]=="3") //suppression de la date
							{	
								$sup=deletedatesession($_POST["iddate".$i]);
								//cloturer la session si les dates precedentes sont déjà cloturées
								if($sup!=FALSE){ 
									$nbrrestant=$nbre_origin-1;
									$nbrvalides=getDatesValidesbysession($idsession);
									if($nbrrestant==$nbrvalides){ 
										updateSessionStatut($idsession);}
									}
								//tester s'il y a des inscrits, et supprimer la relation date en cas de resultat positif							
								if(FALSE!=testrelsessiondate($idsession)){
									deleteRelsessionUser($idsession,$_POST["iddate".$i]);
								}
								$o=$o+1;
							}else {//modification de date 
								modifDateSession($_POST["iddate".$i], $sessiondates[$i],$_POST["statutdate".$i]);
								//cloturer la session si les dates precedentes sont déjà cloturées en cas de modif ==2
								if($_POST["statutdate".$i]=="2") //Annulation de la date
								{
								//en cas d'aucune date en attente, valider la session et l'inscrire 
									$nbrrestant=$nbre_origin-1;
									$nbrvalides=getDatesValidesbysession($idsession);
									if($nbrrestant==$nbrvalides){ 
										updateSessionStatut($idsession);
									}
								//inserer les stats aussi !!
								$arrayresult=getInscritpersession($idsession,$_POST["iddate".$i]);
								InsertStatAS('s',$idsession, $sessiondates[$i],$arrayresult[0],0,0,$arrayresult[1],$nbplace,2,$anim,$_SESSION["idepn"]);
								}
								
								
							}
						}
					}
					
					//remettre le bon nombre pour $nbre_dates dans tab_session
					if(($nbre_dates-$o)<$nbre_dates){
						$nbre_dates=$nbre_dates-$o;
						updatenbredates($idsession,$nbre_dates);
					}
					$i=0;
					header("Location: ./index.php?a=37&mesno=14");
					
				//en cas d'ajout de date	
				}elseif ($nbre_origin<$nbre_dates){
					//changer le nombre de dates dans tab_session
					updatenbredates($idsession,$nbre_dates);
					
					//insérer les nouvelles dates avec le statut s'il ya a lieu
					for($i=$nbre_origin+1;$i<=$nbre_dates;$i++){
						$result=insertDateSessions($idsession,$sessiondates[$i],0);
						 //inserer la relation aussi
						 //retrouver la liste des inscrits
						$listeu=getSessionUser($idsession,0);
						$c=mysqli_num_rows($listeu);
						$list=mysqli_fetch_array($listeu);
						if((FALSE!=testrelsessiondate($idsession)) AND FALSE!=$result){
							for($y=0;$y<$c;$y++){
								addUserSession($idsession,$list["id_user"],0,$result);
							}
						}
						
					}
					$i=0;
					header("Location: ./index.php?a=37&mesno=14");
				}
			 }
			break;
				
		}
		   
		   
			}   
		}
	
	}
}

// Si le bouton supprimé est posté
if ($m==4) // supprime un poste
{


  if (FALSE == delSession($idsession))
  {
      header("Location: ./index.php?a=37&mesno=0");
  }
  else
  {
	//supprimer les relations user concernées
	$result = getSessionUser($idsession,0) ;
	$nb = mysqli_num_rows($result) ;
	if ($nb>0)
	{
		for ($i =0 ; $i<= $nb; $i++)
		{
             $row = mysqli_fetch_array($result) ;
		delUserSession($idsession,$row["id_user"]);
		}
	 }
	 //supprimer les relations user concernées en liste d'attente aussi !
	$result2 = getSessionUser($idsession,2) ;
	$nb2 = mysqli_num_rows($result2) ;
	if ($nb2>0)
	{
		for ($i =0 ; $i<= $nb2; $i++)
		{
             $row2 = mysqli_fetch_array($result2) ;
		delUserSession($idsession,$row2["id_user"]);
		}
	 }
	header("Location: ./index.php?a=37&mesno=46");
  }
}
?>
