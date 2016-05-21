<?php
/*
   
 include/post_atelier.php V0.1
*/
$id =  $_GET["idatelier"];
if ($_POST["submit_atelier"] !="")   // si le formulaire est posté
{
	$m =  $_GET["m"];


  $date    = $_POST["date"] ;
  $heure     = $_POST["heure"] ;
  $nbplace   = $_POST["nbplace"] ;
  $sujet	= $_POST["sujet"];
  $public    = $_POST["public"] ;
  $anim      = $_POST["anim"] ;
	$duree = $_POST["duree"];
	$stateAtelier=$_POST["statut"];
	$salle=$_POST["salle"];
	$tarif      = $_POST["tarif"] ;
	
	
  //debug($m);
  
  //debug($sujet);
if ($m !="" AND $m!=3)  // verife si non vide
{
  
  if (!$date ||!$heure ||!$sujet ||!$nbplace)
  {
       $mess= getError(4) ;
  }
  else
  {
       switch($m)  
        {
            case 1:   // ajout planification d'un poste
            $idatelier=addAtelier($date,$heure,$duree,$anim,$sujet,$nbplace,$public,$stateAtelier,$salle,$tarif,0,0);
                 if (FALSE ==$idatelier )
                 {
                     header("Location: ./index.php?a=11&mesno=0");
                 }
                 else
                 {
                   /*
									 // insertion de la relation usage de poste (resa=1 ou atelier=2) EN MODIF
									 $minute=str_split($heure);
										$min=0;
										if ($minute[1]>=0 and $minute[1]<4){
										$min=substr_replace(date('i'),"0",1,1);
										}else if ($minute[1]>3 and $minute[1]<8){
										$min=substr_replace(date('i'),"5",1,1);
										}else if ($minute[1]>7){
										$minu=($minute[0]+1)."0";
										$min=substr_replace(date('i'),$minu,0,2);
										}
										
										$heurer=date('G')*60+$min;
									 
									 //retrouver les id des ordis dans la salle et inserer pour la resa
									 for($nbcomp=0;$nbcomp<$nbplace;$nbcomp++){
										 
										
										$idresarel=addResa($idcomp,$anim ,$date,$heurer,$duree);
									 }
										insertrelresa($idresarel,2);
										
										///
										*/
										//rajouter la relation des computers à libérer pour epnconnect
                    if(FALSE==connectAtelierComputer($salle,$idatelier)){
											 header("Location: ./index.php?a=11&mesno=0");
                    }else{
											header("Location: ./index.php?a=11&p=ok");
                    }
                 }
            break;
						
            case 2:   // modifie un poste
                 if (FALSE == modifAtelier($id,$date,$heure,$duree,$anim,$sujet,$nbplace,$public,$stateAtelier,$salle,$tarif))
                 {
                     header("Location: ./index.php?a=11mesno=0");
                 }
                 else
                 {
										// modifier la rel aussi !! duree/postes/heure/date
										
										if($stateAtelier==3){ //en cas d'annulation d'atelier, l'inscrire dans les stats
											$inscrits=countPlace($id);
											//adherent en attente
											$rattente = getAtelierUser($idatelier,2) ; 
											$attente=mysqli_num_rows($rattente);
											 InsertStatAS('a',$id,$date,$inscrits,0,0,$attente,$nbplace,$stateAtelier);
										}
                     header("Location: ./index.php?a=11&p=ok");
                 }
            break;
			
        }
	   
	   
	}   
	   
  }
}

// Si le bouton supprimé est posté
if ($m==4) // supprime un atelier
{
  if (FALSE == delAtelier($id))
  {
      header("Location: ./index.php?a=11&mesno=0");
  }
  else
  {
	//supprimer les adherents inscrits
	$result = getAtelierUser($id,0) ; 
	$nb = mysqli_num_rows($result) ;
		if ($nb>0)
		{
			for ($i = 0 ; $i< $nb; $i++)
			{
				$row = mysqli_fetch_array($result) ;
				delUserAtelier($id,$row["id_user"]);
			}
		}
		//supprimer ceux en liste d'attente aussi !
		$result2 = getAtelierUser($id,2) ; 
		$nb2 = mysqli_num_rows($result2) ;
		if ($nb2>0)
		{
			for ($i = 0 ; $i< $nb2; $i++)
			{
				$row2 = mysqli_fetch_array($result2) ;
				delUserAtelier($id,$row2["id_user"]);
			}
		}
		
		//on supprimer aussi la relation computer pour epnconnect
		$supprimRel=supprimComputerAtelier($id);
		if (FALSE == $supprimRel)
		{
      header("Location: ./index.php?a=11&mesno=0");
      }else{
	
      header("Location: ./index.php?a=11&b=1");
      }
  }
}
?>
