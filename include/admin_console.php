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
 2016 Tariel Christophe
 

 include/admin_console.php V0.1
*/
/*function trigger() {
        echo '<script type="text/javascript">window.alert("'.$chargé.'");</script>';
}*/

// console
	//}

?>
<form method="post" action="index.php?a=45">
    <table width="100%">
        <tr class="list_salle">
            <td align="right" colspan="4">
                Salle : <select name="numsalle">
<?php
    if (isset($_POST['numsalle'])) {
        $premiereSalleAnim = $_POST['numsalle'];
    } else {
        // recuperation de la premiere salle geree par l'animateur
        $premiereSalleAnim = 1 ; // valeur par defaut pour l'initialisation. Posera probleme s'il n'y a pas de salle avec l'id = 1
        $resultSallesAnim = getSallesbyAnim($_SESSION["iduser"]);
        
        $sallesAnim = explode(";", $resultSallesAnim[0]);
        if (count($sallesAnim) > 0) {
            $premiereSalleAnim = $sallesAnim[0];
        }
    }        
    // creation de la liste des salles
    $resultsalle = getAllSalle();
    $nbsalle     = mysqli_num_rows($resultsalle);
    for($i = 1; $i <= $nbsalle; $i++) {
        $rowsalle = mysqli_fetch_array($resultsalle);
        if ( $rowsalle["id_salle"] == $premiereSalleAnim ) {
            echo "<option value=\"".$rowsalle["id_salle"]."\" selected >".$rowsalle["nom_salle"]."</option>";
        } else {
            echo "<option value=\"".$rowsalle["id_salle"]."\">".$rowsalle["nom_salle"]."</option>";
        }
    }
?>
                </select>
                <input type="submit" value="Ok" onclick="request(readData);">
            </td>
        </tr>
    </table>
</form>
<input type="hidden" id="numconsole" value="<?php echo $premiereSalleAnim ?>">
<div id="consoleafficher" align="center"><img src="img/ajax-loader.gif"></div>
<div id="actionconsoleafficher" align="center"></div>