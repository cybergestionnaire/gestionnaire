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
 

 include/admin_inscription.php V0.1
*/


// admin --- Utilisateur
$mesno  = $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

// Les adhérents en pré-inscription
$result = getAllUserInsc();
$nb  = mysqli_num_rows($result);
    if ($nb > 0)
    {
    	?>
<div class="box box-primary"><div class="box-header"><h3 class="box-title">Pr&eacute;-inscriptions</h3></div>
      <div class="box-body"><table class="table">
	<thead><tr><td>Nom</td><td>Pr&eacute;nom</td><td>Nom d'utilisateur</td><td>Poste concern&eacute; ou EPN choisi par internet</td><td>&nbsp;</td></tr></thead>
            <?php
                    for ($i=1; $i<=$nb; $i++)
                    {
                        $row = mysqli_fetch_array($result) ;
						$rowcomputer = getComputerName($row["id_inscription_computer"]);
                        echo "<tr><td>".$row["nom_inscription_user"]."</td>
                             <td>".$row["prenom_inscription_user"]."</td>
                             <td>".$row["login_inscription_user"]."</td>
                             <td>".$rowcomputer."</td>
                             <td><a href=\"index.php?a=24&b=1&iduser=".$row["id_inscription_user"]."\"><img src=\"./img/mod.gif\" alt=\"Modifier\" title=\"Modifier\"></a></td></tr>";
                    }
            ?>
    </table></div></div>
	
    <?php
       
    }	else {
		echo getError(41);
	}

?>

