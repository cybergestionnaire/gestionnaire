<?php
/*
 2006 Namont Nicolas
 2013 

 
*/
$b=$_GET['b'];
$id=$_GET['sujet'];


if ($_POST["submit_atelier"] !="")  
{
	$niveau    = $_POST["niveau"] ;
	$categorie = $_POST["categorie"] ;
	
	$sujet     = addslashes($_POST["label_atelier"]) ;
	$content   = addslashes($_POST["content"]) ;
	$ressource =addslashes( $_POST["ressource"] );



  if ($b==13){
			//debug($id);
			delSujetAtelier($id);// suppression du sujet d'atelier dans la base
			header ("Location:index.php?a=11&b=13&sujet=0&mesno=21");
				
	}
	
	if ($b==11){
		  if (!$sujet ||!$content)
		  {
			   $mess= getError(4) ;
		  }
		  else
		  {
			modifSujetAtelier($id,$sujet,$content,$ressource,$niveau,$categorie);
			header ("Location:index.php?a=17&sujet=".$id."&mesno=22");
			}
	}
		
	if ($b==12){
		if (!$sujet ||!$content)
		  {
			   $mess= getError(4) ;
		  }
		  else
		  {
			createAtelier($sujet,$content,$ressource,$niveau,$categorie);
		    header ("Location:index.php?a=11&mesno=20");
		   }
	}
  
}

?>
