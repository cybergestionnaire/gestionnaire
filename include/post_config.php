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
 

  include/post_config.php V0.1
*/

// POST de Configuration de l'espace

//debug

if ($_POST["submit"] !="" )
{
  switch ($_POST["form"])
  {
      case 1: //nom de l'espace
           $update = array ("name_config" => $_POST["name"] );
           updateConfig("tab_config",$update,"id_config",1)  ;
      break;

      case 2: //horaire
           for ($i=1 ; $i<8 ; $i++)
           {
                   $update = array();
                   $update["hor1_begin_horaire"] = $_POST[$i."-h1begin"] ;
                   $update["hor1_end_horaire"]   = $_POST[$i."-h1end"] ;
                   $update["hor2_begin_horaire"] = $_POST[$i."-h2begin"] ;
                   $update["hor2_end_horaire"]   = $_POST[$i."-h2end"] ;
                   if (TRUE == checkHoraire($_POST[$i."-h1begin"],$_POST[$i."-h1end"],$_POST[$i."-h2begin"],$_POST[$i."-h2end"]))
                   {
                       updateConfig("tab_horaire",$update,"jour_horaire",$i) ;
                   }
                   else
                   {
                       header("Location:index.php?a=9&mess=Hwrong&dayline=".$i) ;
                       exit;
                   }
           }
      break;
      case 3: //reservation minimum
           $update = array() ;
           $update["unit_config"]    = $_POST["unit"] ;
           $update["maxtime_config"] = $_POST["maxtime"] ;
                   
           updateConfig("tab_config",$update,"id_config",1)  ;
      break;
    
  }

  header("Location:index.php?a=9&mess=ok") ;

}
?>