<?php
/*
 2006 Namont Nicolas
 

 include/post_atelier.php V0.1
*/
$pSujet=$_POST["sujet"];

if ($_POST["modifier"] !="")   // si le formulaire est posté
{
//date/heure/places/lieux/niv/cat/sujet/texte pres
//$m =  $_GET["m"];
  $nbplace   = $_POST["nbplace"] ;
  $niveau    = $_POST["niveau"] ;
  $categorie = $_POST["categorie"] ;
 // $prix      = $_POST["tarif"] ;
  $lieu      = $_POST["lieu"] ;
  $public    = $_POST["public"] ;
  $anim      = $_POST["anim"] ;
  $sujet     = $_POST["label_atelier"] ;
  $content   = $_POST["content"] ;
  $ressource = $_POST["ressource"] ;

  //debug($anim,$sujet,$content,$ressource,$nbplace,$niveau,$categorie,$lieu,$prix,$public);
  //debug($sujet);
  
  if (!$sujet ||!$content)
  {
       $mess= getError(4) ;
  }
  else
  {
        if (FALSE == modifAtelier($sujet,$content,$ressource,$niveau,$categorie,$public,$lieu,$nbplace))
		   {
			   $mess = getError(0);
		   }
		   else
		   {
			   header ("Location:index.php?a=35&p=ok");
		   }
	   
  }
}

?>