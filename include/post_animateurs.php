<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006 Namont Nicolas
 

 include/post_animateur.php V0.1
*/
  $b      =  $_GET["b"];
  $idanim  =  $_GET["idanim"];
  
    //recuperation et traitement des variables
	$avatar_r    = $_POST["avatar_r"];
    $epn_r      =  $_POST["epn_r"];
	$salles_r=array();
    $salles_r=  $_POST["salle_r"];
	$salles=implode(";",$salles_r );

    
if ($b==1) // creation
 {
   // les salles et l'epn de rattachement
		  
		  if (!$epn_r || !$salles )
		  {
			 $mess = getError(50);
		  }
		  else
		  {
		 
			if (FALSE == paramAnim($idanim,$epn_r,$salles,$avatar_r))
					  {
						header("Location:index.php?a=50&idanim=".$idanim."&mesno=50");
					  }
					  else
					  {
						  header("Location:index.php?a=50&b=2&mess=ok&idanim=".$idanim);
					  }
				  
				 
			}
				
        
} else { // Modification
	
	if (!$epn_r || !$salles )
		  {
			 $mess = getError(50);
		  }
		  else
		  {
			if (FALSE == modifparamAnim($idanim,$epn_r,$salles,$avatar_r))
					  {
						header("Location:index.php?a=50&idanim=".$idanim."&mesno=50");
					  }
					  else
					  {
						  header("Location:index.php?a=50&b=2&mess=ok&idanim=".$idanim);
					  }
				   
				 
			}


}
?>
