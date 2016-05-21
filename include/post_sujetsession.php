<?php
/*
 2006 Namont Nicolas
2012 Florence DAUVERGNE 
*/
$s=$_GET["s"];
$id=$_GET["sujetsession"];

if ($s=="del") {//suppression du sujet de la session
	 delSujetSession($id);
	 header ("Location:./index.php?a=30&mesno=24");
	 }
	 
if (isset($_POST["submit_session"])) 
 
{

	$niveau    = $_POST["niveau"] ;
	$categorie = $_POST["categorie"] ;
	$sujet     = addslashes($_POST["label_session"] );
	$content   = addslashes($_POST["content"]);
		

	if ($s==2) {//creation du sujet de la session
	
	 if (!$sujet ||!$categorie)
	  {
		   $mess= getError(4) ;
	  }
	  else
	  {
			if (FALSE == createSession($sujet,$content,$niveau,$categorie))
			   {
				   $mess = getError(0);
			   }
			   else
			   {
				   
				   header ("Location:./index.php?a=37&mesno=23");
			   }
		   
	  }
	 }
	 
	 if ($s==3){ //modification du sujet de la session
		if (!$sujet ||!$content)
		  {
			   $mess= getError(4) ;
		  }
		  else
		  {
		ModifSujetsession($id,$sujet,$content,$niveau,$categorie);
		    header ("Location:./index.php?a=37&mesno=22");
		   }
	}
	
}

?>
