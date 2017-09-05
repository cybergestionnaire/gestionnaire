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
/* function trigger() {
  echo '<script type="text/javascript">window.alert("'.$chargé.'");</script>';
  } */

// console
//}
require_once("include/class/Salle.class.php");
$animateur = Utilisateur::getUtilisateurById($_SESSION["iduser"]);
if ($animateur->getStatut() == 3) {
    $salles = $animateur->getSallesAnim();
}
if ($animateur->getStatut() == 4) {
    $salles = Salle::getSalles();
}

// error_log("salles = " . print_r($salles , true));
?>
<form method="post" action="index.php?a=45">
    <table width="100%">
        <tr class="list_salle">
            <td align="right" colspan="4">
                Salle : <select name="numsalle">
                    <?php
                    if (isset($_POST['numsalle'])) {
                        $idSalleSelect = $_POST['numsalle'];
                    } else {
                        // recuperation de la premiere salle geree par l'animateur
                        if (count($salles) > 0) {
                            $idSalleSelect = $salles[0]->getId(); // on prend la première par défaut
                        } else {
                            $idSalleSelect = 0;
                        }
                    }


                    foreach ($salles as $salle) {
                        if ($salle->getId() == $idSalleSelect) {
                            echo "<option value=\"" . $salle->getId() . "\" selected >" . htmlentities($salle->getNom()) . "</option>";
                        } else {
                            echo "<option value=\"" . $salle->getId() . "\">" . htmlentities($salle->getNom()) . "</option>";
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="Ok" onclick="request(readData);">
            </td>
        </tr>
    </table>
</form>
<input type="hidden" id="numconsole" value="<?php echo $idSalleSelect ?>">
<div id="consoleafficher" align="center"><img src="img/ajax-loader.gif"></div>
<div id="actionconsoleafficher" align="center"></div>