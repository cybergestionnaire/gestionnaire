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

  2006 Namont Nicolas (Cybermin)
 */

// POST de Configuration du logiciel EPN Connect
require_once("include/class/Espace.class.php");
require_once("include/class/Config.class.php");
require_once("include/class/ConfigLogiciel.class.php");
//debug
// error_log("---- _POST ----");
// error_log(print_r($_POST, true));
// error_log("---- _GET ----");
// error_log(print_r($_GET, true));


$act = isset($_GET["act"]) ? $_GET["act"] : '';
$idEspace = isset($_GET["idepn"]) ? $_GET["idepn"] : '';

if (isset($_POST['submit'])) {
    switch ($_POST["form"]) {
        case 1:
            $epnr = $_POST["epn_r"];
            header("Location:index.php?a=25&epnr=" . $epnr);
            break;

        case 2:

            $id = isset($_POST['idconfig']) ? $_POST['idconfig'] : '';
            $shiftlog = isset($_POST['shiftlog']) ? $_POST['shiftlog'] : '';
            $insclog = isset($_POST['insclog']) ? $_POST['insclog'] : '';
            $renslog = isset($_POST['renslog']) ? $_POST['renslog'] : '';
            $conexlog = isset($_POST['conexlog']) ? $_POST['conexlog'] : '';
            $bloclog = isset($_POST['bloclog']) ? $_POST['bloclog'] : '';
            $tempslog = isset($_POST['tempslog']) ? $_POST['tempslog'] : '';
            $decouselog = isset($_POST['decouselog']) ? $_POST['decouselog'] : '';
            $fermersessionlog = isset($_POST['fermersessionlog']) ? $_POST['fermersessionlog'] : '';

            $activerforfait = isset($_POST['forfait']) ? $_POST['forfait'] : '';
            $inscription_auto = isset($_POST['inscrip_auto']) ? $_POST['inscrip_auto'] : '';
            $message_inscrip = isset($_POST['message_inscrip']) ? $_POST['message_inscrip'] : '';
            $epn = isset($_POST['epn']) ? $_POST['epn'] : '';

            $espace = Espace::getEspaceById($idEspace);
            $configLogiciel = $espace->getConfigLogiciel();
            $config = $espace->getConfig();

            if ($configLogiciel->setConfigLogiciel($shiftlog, $insclog, $renslog, $conexlog, $bloclog, $tempslog, $decouselog, $fermersessionlog) && $config->modifier(
                            $config->getActiverConsole(),
                $config->getName(),
                $config->getDefaultTimeUnit(),
                $config->getUnit(),
                $config->getMaxTime(),
                $config->getDefaultMaxTime(),
                $config->getIdEspace(),
                $inscription_auto,
                $message_inscrip,
                $config->getNomEspace(),
                $activerforfait,
                $config->getResaRapide(),
                $config->getDureeResaRapide()
                    )) {
                header("Location:index.php?a=25&mess=ok&epnr=" . $idEspace);
            } else {
                header("Location:index.php?a=25&mess=0&epnr=" . $idEspace);
            }

            break;

        case 4:
            $idEspace = isset($_POST["epn_r"]) ? $_POST["epn_r"] : '';
            $console = isset($_POST["console"]) ? $_POST["console"] : '';

            $espace = Espace::getEspaceById($idEspace);
            $config = $espace->getConfig();

            if ($config->modifier(
                            $console,
                $config->getName(),
                $config->getDefaultTimeUnit(),
                $config->getUnit(),
                $config->getMaxTime(),
                $config->getDefaultMaxTime(),
                $config->getIdEspace(),
                $config->getInscriptionUsagersAuto(),
                $config->getMessageInscription(),
                $config->getNomEspace(),
                $config->getActivationForfait(),
                $config->getResaRapide(),
                $config->getDureeResaRapide()
                    )) {
                header("Location:index.php?a=25&mess=ok&epnr=" . $idEspace);
            } else {
                header("Location:index.php?a=25&mess=0&epnr=" . $idEspace);
            }

            break;
    }
}
