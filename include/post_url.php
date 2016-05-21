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
 

  include/admin_url.php V0.1
*/
// TRAITEMENT DU FORMULAIRE POSTE

// on enregistre le lien
if ($_POST["submit"]=="Ajouter")
{                       // si les champs sont incomplets

   $tit = $_POST["titre"];
   $ur  = $_POST["url"];
   if (!$tit || !$ur)
   {
       $mess =  getError(13);
   }
   else
   { // si les champs ne sont pas vides on ajoute
   		if($_POST['rubInput']!="")
   		{
   			$rubName = $_POST['rubInput'];			
   		}
   		else
   		{
   			$rubId = $_POST['rubSel'] ;
   		}
       addBookmark(0,$tit,$ur,$rubId,$rubName);
   }
}
// on enregistre le lien modifiŽ
if ($_POST["submit"]=="Modifier")
{                       // si les champs sont incomplets
   $tit = $_POST["titre"];
   $ur  = $_POST["url"];
   $idu = $_POST["idurl"] ;
   if (!$tit || !$ur)
   {
       $mess = getError(13);
   }
   else
   { // si les champs ne sont pas vides on update
   		updateBookmark($idu,$tit,$ur);
   		
   }
}
// FIN DU TRAITEMENT DU FORMULAIRE
?>