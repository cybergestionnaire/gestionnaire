<?php
/*
     This file is part of CyberGestionnaire.

    CyberGestionnaire is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

  include/post_config.php V0.1
*/

// POST de Configuration du logiciel EPN Connect

//debug

$act=$_GET["act"];
$epn=$_GET["idepn"];

if(isset($_POST['submit'])){

 switch ($_POST["form"])
  {
	case 1:
	$epnr=$_POST["epn_r"];
	header("Location:index.php?a=25&act=1&epnr=".$epnr);
	break;
	
	case 2:
	
	  $id=$_POST['idconfig'];
		$shiftlog=$_POST['shiftlog'];
		$insclog=$_POST['insclog'];
		$renslog = $_POST['renslog'];
		$conexlog=$_POST['conexlog'];
		$bloclog=$_POST['bloclog'];
		$tempslog=$_POST['tempslog'];
		$decouselog=$_POST['decouselog'];
		$fermersessionlog=$_POST['fermersessionlog'];
		
		$activerforfait=$_POST['forfait'];
		$inscription_auto=$_POST['inscrip_auto'];
		$message_inscrip=$_POST['message_inscrip'];
		$epn=$_POST['epn'];
	

	//1er enregistrement des paramètres dans tab_config_logiciel
	if($act==1){
	
		$result=addConfiglogiciel($epn,$shiftlog,$insclog,$renslog,$conexlog,$bloclog,$tempslog,$decouselog,$fermersessionlog);
			if(FALSE==$result)
			{
				header("Location:index.php?a=25&mess=0&epnr=".$epn);
			}
			else
			{
				header("Location:index.php?a=25&mess=ok&epnr=".$epn) ;
			}
		}
		
		
		//Modification des paramètres dans tab_config_logiciel
		if($act==0){
		
		$result=updateConfiglogiciel($id,$epn,$shiftlog,$insclog,$renslog,$conexlog,$bloclog,$tempslog,$decouselog,$fermersessionlog);
			if(FALSE==$result)
			{
				header("Location:index.php?a=25&mess=0&epnr=".$epn);
			}
			else
			{
				header("Location:index.php?a=25&mess=ok&epnr=".$epn) ;
			}
		}
		
		//dans tous les cas remodifier les paramètres config supplementaires dans tab_config
		$update=updateConfigForfait($epn,$activerforfait,$inscription_auto,$message_inscrip);
		if(FALSE==$update){
			header("Location:index.php?a=25&mess=0&epnr=".$epn);
			}
			else
			{
				header("Location:index.php?a=25&mess=ok&epnr=".$epn) ;
			}
	
	
	
	break;
	
	 case 4:
		  $epnr=$_POST["epn_r"];
		  $console = $_POST["console"] ;
		  $result=updateconsolemode($epnr, $console)  ;
			if(FALSE==$result)
			{
				header("Location:index.php?a=25&mess=0&epnr=".$epnr);
			}
			else
			{
				header("Location:index.php?a=25&mess=ok&epnr=".$epnr) ;
			}
			
	  break;
}	
}	

?>