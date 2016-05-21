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
 

 include/post_inter.php V0.1
*/

// traitement des post et get des interventions

$titr    = addslashes($_POST["titr"]);
$date    = $_POST["date"];
$comment = addslashes($_POST["comment"]);
$dispo   = $_POST["dispo"];


// verification de l'envoi du formulaire
if (FALSE != isset($_POST["submit"]))
{
    // recuperation des postes concernés
    $result  = getComputerId();
    if (FALSE != $result)
    {
        $nb = mysqli_num_rows($result);
        if ($nb >0)
        {
            $comp = array();
            for ($i=1;$i<=$nb;$i++)
            {
                $row = mysqli_fetch_array($result);
                if ($_POST[$row["id_computer"]] == "on" )
                {
                        $comp[$i]=$row["id_computer"] ;
                }
            }
        }
    }

     
    if (!$titr || !$comment) //verification des champs non vide
    {
        header ("Location:index.php?a=3&b=1&error=1");
    }
    else
    {
       $result = addInter($titr,$date,$comment,$dispo);    
        if (FALSE == $result)
        {
            header ("Location:index.php?a=3&b=1&error=2");
        }
        else
        {
           $idinter = $result;  
               foreach ($comp AS $key=>$value)
               {    
                   if (FALSE == addInterComputer($idinter,$value)){
                    header ("Location:index.php?a=3&b=1&error=1");
                 }
            }
            header ("Location:index.php?a=3");
        }
    }
}

?>