<?php

// Page de traitement du formulaire de breve

$act      =  $_GET["act"];
$id       =  $_GET["idbreve"];

$titr      = htmlentities($_POST["titr"], ENT_QUOTES) ;
$comment   =htmlentities($_POST["comment"], ENT_QUOTES) ;
$datenews=$_POST["datenews"];
$datepublish=$_POST["datepublish"];
$epn=$_POST["idepn"];
$type=$_POST["type"];
$visible   = $_POST["visible"] ;

if ($act !="" AND $act!=3)  // verife si non vide
{
  // Traitement des champs a insérer
    if (!$titr || !$comment )
    {
       $mess = getError(4);
    }
	
    else
    {
        switch($act)  
        {
            case 1:   // ajout d'un poste
                 if (FALSE == addBreve($titr,$comment,$visible,$type,$datepublish,$datenews,$epn))
                 {
                     
					 header("Location: ./index.php?a=4&mesno=0");
                 }
                 else
                 {
                    header("Location: ./index.php?a=4");
                 }
            break;
            case 2:   // modifie un poste
                 if (FALSE == modBreve($id,$titr,$comment,$visible,$type,$datepublish,$datenews,$epn))
                 {
					
                     header("Location: ./index.php?a=4mesno=0");
                 }
                 else
                 {
                     header("Location: ./index.php?a=4");
                 }
            break;
        }
    }
}
if ($act==3) // supprime un poste
{
  if (FALSE == supBreve($id))
  {
      header("Location: ./index.php?a=4mesno=0");
  }
  else
  {
      header("Location: ./index.php?a=4");
  }
}
?>