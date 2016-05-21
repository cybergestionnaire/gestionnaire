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
 

 include/calendrier.php V0.1
*/

// on verifie si le mois et l'annee existe et sont de type numerique
if (TRUE == isset($_GET["month"]) AND TRUE == isset($_GET["year"]) AND TRUE == is_numeric($_GET["month"]) AND TRUE == is_numeric($_GET["year"]))
{
        $month = $_GET["month"] ;
        $year  = $_GET["year"] ;
        if($month == 13) //annee suivante
        {
            $month = 1;
            $year++ ;
        }
        if ($month == 0) //annee precedente
        {
            $month = 12;
            $year-- ;
        }
}
else  // sinon on prend le mois et l'annee en cours
{
   $month = date("n") ;
   $year  = date("Y") ;
}
$epn=$_SESSION["idepn"];

echo getCalendar($year,$month,$epn) ;


?>

