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

 */


// Fonction user --------------------------------------
//
// passwd()
// crypt le mot de passe
function passwd($pass)
{
    return md5($pass);
}


//converti une date en jour de l'annee
function convertDateJour($jour)
{
    $jouran = strftime("%j", strtotime($jour));
    return ($jouran);
}


//
//
// Calendrier ------------------------------------------------------------------
//
//
//renvoi le nom du mois a partir du numero de mois
function getMonthName($monthNum)
{
    $monthList = array("", "Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "D&eacute;cembre");
    return $monthList[$monthNum];
}

// renvoi le premier jour du mois en chiffre
function getFirstDay($year, $month)
{
    return date("N", mktime(0, 0, 0, $month, 1, $year));
}

// renvoi le dernier jour du mois - 7
function getLastDay($year, $month)
{
    $nb_jour = date("t", mktime(0, 0, 0, $month, 1, $year));
    $tmp = date("N", mktime(0, 0, 0, $month, $nb_jour, $year));
    return 7 - $tmp;
}

// renvoi un calendrier du mois et de l'annee donnee
function getCalendar($year, $month, $day, $epn)
{
    $calendar = "";
    // tableau des index des jours
    $dayArray = array("L", "M", "M", "J", "V", "S", "D");
    //nombre de jour das le mois en cours
    $nb_jour = date("t", mktime(0, 0, 0, $month, 1, $year));
    //epn sélectionné
    /// GNIIIII ???? à quoi sert de donner l'argument $epn ????
    // $Pepn = $_SESSION["idepn"];
    // if ($epn == $Pepn) {
    // $epn = $epn;
    // } else {
    // $epn = $Pepn;
    // }
    //Bouton pour la resa a posteriori que animateurs ou admin
    if ($_SESSION["status"] == 3 or $_SESSION["status"] == 4) {
        $boutonresa = "<a href=\"index.php?a=19\"><i class=\"ion ion-log-in\"></i></a>";
    } else {
        $boutonresa = "";
    }
    //Affichage -------------------------------------
    //affichage du mois et de l'année
    $calendar = "<div align=\"center\" class=\"titreCal\">
                    <h4 >
                        <a href=\"?m=3&jour=" . $day . "&mois=" . ($month - 1) . "&annee=" . $year . "\"><i class=\"ion-arrow-left-b\"></i></a>
                        &nbsp;&nbsp;<b>" . getMonthName($month) . " " . $year . "</b>
                        <a href=\"?m=3&jour=" . $day . "&mois=" . ($month + 1) . "&annee=" . $year . "\">&nbsp;&nbsp;<i class=\"ion-arrow-right-b\"></i></a>
                        &nbsp;&nbsp;&nbsp;&nbsp;" . $boutonresa . "
                    </h4>
                </div> ";

    $calendar .= "<div class=\"calendar\">";

    // affichage du nom des jours
    for ($i = 0; $i < 7; $i++) {
        $calendar .= "<div class=\"labelDay\">" . $dayArray[$i] . "</div>";
    }
    $calendar .= "<br>";

    // affichage des cases vides de debut
    $firstDay = getFirstDay($year, $month);
    for ($k = 1; $k < $firstDay; $k++) {
        $calendar .= "<div class=\"labelNum\">&nbsp;</div>";
    }

    // affichage des jours
    for ($j = 1; $j <= $nb_jour; $j++) {
        switch (checkDayOpen2($j, $month, $year, $epn)) {
            case "ouvert":
                if ($j == $day) {
                    $calendar .= "<div class=\"labelNumCurrent\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">" . $j . "</span></a></div>";
                } elseif ($year == date('Y') and $month == date("m") and $j == date("d")) {
                    $calendar .= "<div class=\"labelNumToday\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">" . $j . "</span></a></div>";
                } else {
                    $calendar .= "<div class=\"labelNum\"><a href=\"index.php?m=3&mois=" . $month . "&annee=" . $year . "&jour=" . $j . "\"><span class=\"cal\">" . $j . "</span></a></div>";
                }
                break;
            case "ferme":
                if ($month == date("m") and $j == date("d")) {
                    $calendar .= "<div class=\"labelNumCurrent\">" . $j . "</div>";
                } else {
                    $calendar .= "<div class=\"labelNumClose\">" . $j . "</div>";
                }
                break;
            case "ferie":
                if ($month == date("m") and $j == date("d")) {
                    $calendar .= "<div class=\"labelNumCurrent\">" . $j . "</div>";
                } else {
                    $calendar .= "<div class=\"labelNumOff\">" . $j . "</div>";
                }
                break;
        }
    }

    // affichage des cases vides de fin
    $lastDay = getLastDay($year, $month);
    for ($l = 1; $l <= $lastDay; $l++) {
        $calendar .= "<div class=\"labelNum\">&nbsp;</div>";
    }
    $calendar .= "<div style=\"clear:both;font-size:10px;padding-top:3px;\"></div></div>";

    return $calendar;
}

//renvoi le numero du jour de la semaine de 0->lundi a 6->dimanche
function getDayNum($j, $m, $a)
{
    return date("z", mktime(0, 0, 0, $m, $j, $a));
}

// Fonction diverses -----------------------------------------------------------
//getDayfr() retourne le jour de la semaine
function getDayFR($date)
{//,$format='D j F'
    $date0 = date('Y-n-j-w', strtotime($date));
    $dateArr = explode("-", $date0);
    $jourfr = $dateArr[3];
    $jour = $dateArr[2];
    $mois = $dateArr[1];
    $annee = $dateArr[0];
    $dayArr = array("Dimanche", "lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

    return $dayArr[$jourfr] . " " . $jour . " " . getMonthName($mois) . " " . $annee;
}

function getDateFR($date)
{ //,$format='D j F à 10h'
    $date0 = date('Y-n-j-w', strtotime($date));
    $dateArr = explode("-", $date0);
    $jourfr = $dateArr[3];
    $jour = $dateArr[2];
    $mois = $dateArr[1];
    $annee = $dateArr[0];
    $date1 = date('H:i', strtotime($date));
    $heurearr = explode(":", $date1);
    $heure = $heurearr[0] . 'h' . $heurearr[1];
    $dayArr = array("Dimanche", "lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

    return $dayArr[$jourfr] . " " . $jour . " " . getMonthName($mois) . " " . $annee . " &agrave; " . $heure;
}

// getMonth()
// donne le mois en fonction du numero du mois
function getMonth($nb)
{
    switch ($nb) {
        case "1":
            $mois = "Janvier";
            break;
        case "2":
            $mois = "F&eacute;vrier";
            break;
        case "3":
            $mois = "Mars";
            break;
        case "4":
            $mois = "Avril";
            break;
        case "5":
            $mois = "Mai";
            break;
        case "6":
            $mois = "Juin";
            break;
        case "7":
            $mois = "Juillet";
            break;
        case "8":
            $mois = "Ao&ucirc;t";
            break;
        case "9":
            $mois = "Septembre";
            break;
        case "10":
            $mois = "Octobre";
            break;
        case "11":
            $mois = "Novembre";
            break;
        case "12":
            $mois = "D&eacute;cembre";
            break;
    }
    return $mois;
}

//
// renvoi une date au format FR
function dateFr($date, $format = 'd/m/Y')
{
    return date($format, strtotime($date));
}

//
// checkdateformat
function checkDateFormat($datefr)
{
    $exp = '^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}^';

    if (preg_match($exp, $datefr) == 1) {
        return true;
    } else {
        return false;
    }
}

// convertDate
function convertDate($datefr)
{
    $tmp = explode("/", $datefr);
    return $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
}

function numToDate($quant, $annee)
{
    $date = strtotime("+" . ($quant) . " day", mktime(12, 0, 0, 01, 01, $annee));
    return date("Y-m-d", $date);
}

//
// getPourcent($nb,$total)
// retourne un pourcentage a partir d'un nombre et d'un total
function getPourcent($nb, $total)
{
    if ($nb != "" and $nb != 0 and $total != "" and $total != 0) {
        $pourcent = round(($nb * 100) / $total, 1 );
        return $pourcent . "%";
    } else {
        return "0";
    }
}

// getError()
// Affiche un message d'erreur
function getError($nb)
{
    include("include/texte/error.php");
    $error = "mes_" . $nb;
    return "<div>" . $$error . "</div>";
}

//
//getTime($temps)
// retourne l'heure et les minutes a partir du temps en minutes
function getTime($temps)
{
    if ($temps < 60) {
        $heures = 0;
        $minutes = $temps;
    } else {
        $heures = floor(($temps) / 60);
        $minutes = $temps - ($heures * 60);
    }
    // creation de la variable time //
    if ($minutes == 0) {
        $time = $heures . "h";
    } else {
        if ($heures == 0) {
            $time = $minutes . "mn";
        } else {
            $time = $heures . "h" . $minutes . "mn";
        }
    }
    return $time;
}

//
// function de debug
function debug($var)
{
    echo '<pre>';
    echo '########## Debut du debug de $var=&nbsp;';
    echo var_export($var);
    echo '########## Fin du debug';
    echo '</pre>';
}

//-------------------------
// Fonctions additionnelles
function csv_to_array($filename, $delimiter)
{
    if (!file_exists($filename) || !is_readable($filename)) {
        return false;
    }

    $header = null;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false) {
        $lines = file($filename);
        foreach ($lines as $line) {
            $values = str_getcsv($line, $delimiter, $enclosure = '"', $escape = '\\');
            if (!$header) {
                $header = $values;
            } else {
                $data[] = array_combine($header, $values);
            }
        }

        fclose($handle);
    }

    return $data;
}

function gFilelog($texte, $titre)
{
    $pathfichier = "logs/" . $titre;
    $fp = fopen($pathfichier, "a+");
    fseek($fp, SEEK_END);
    fputs($fp, $texte);
    fclose($fp);
}

//affiche les boutons dans la config suivant la page desactivee
function configBut($page)
{
    $confbut = array(
        array(41, "fa fa-cloud", "VILLES"),
        array(43, "fa fa-home", "EPN"),
        array(44, "fa fa-square", "SALLES"),
        array(42, "fa fa-clock-o", "HORAIRES"),
        array(47, "fa fa-eur", "TARIFS"),
        array(2, "fa fa-desktop", "MATERIEL"),
        array(48, "fa fa-user-md", "USAGES"),
        array(46, "fa fa-caret-square-o-up", "USAGES POSTES"),
        array(23, "fa fa-users", "ADMIN/ANIM"),
        array(49, "fa fa-database", "BDD"),
        array(25, "fa fa-unlock-alt", "EPN-CONNECT"),
        array(53, "fa fa-user-plus", "INSCRIPTIONS"),
    );
    $htmlbut = '';
    for ($u = 0; $u < count($confbut); $u++) {
        if ($confbut[$u][0] == $page) {
            $disab = "disabled";
        } else {
            $disab = "";
        }
        //debug($confbut[$u][0]);
        $htmlbut .= '<a class="btn btn-app ' . $disab . '"  href="index.php?a=' . $confbut[$u][0] . '"><i class="' . $confbut[$u][1] . '"></i> ' . $confbut[$u][2] . '</a>';
    }
    return $htmlbut;
}


function getDay($nb)
{
    switch ($nb) {
        case "0":
            $day = "Dimanche";
            break;
        case "1":
            $day = "Lundi";
            break;
        case "2":
            $day = "Mardi";
            break;
        case "3":
            $day = "Mercredi";
            break;
        case "4":
            $day = "Jeudi";
            break;
        case "5":
            $day = "Vendredi";
            break;
        case "6":
            $day = "Samedi";
            break;
    }
    return $day;
}

/////FONCTIONS SUR LES SEMAINES////////
//
// fonction qui renvoi les dates de comparaisons pour la semaine
function get_lundi_dimanche_from_week($week)
{
    $week = $week - 1;
    $year = date('Y');

    for ($i = 1; $i < 8; $i++) {
        if (date('D', mktime(0, 0, 0, 1, $i, $year)) == 'Mon') {
            $lundi = mktime(0, 0, 0, 1, $i, $year);
        }
    }
    $dimanche = $lundi + (60 * 60 * 24 * 6);
    $lundi = $lundi + (60 * 60 * 24 * 7 * ($week - 1));
    $dimanche = $dimanche + (60 * 60 * 24 * 7 * ($week - 1));

    return (array($lundi, $dimanche));
}

function getDaySemaine($d, $weekday, $dayYear)
{
    if ($d > $weekday) {
        $day = $dayYear + ($d - $weekday); //jour suivants
    } elseif ($d == $weekday) {
        $day = $dayYear; //jour choisi même jour qu'aujourd'hui
    } else {
        $day = $dayYear - ($weekday - $d); // jour precedents
    }
    return $day;
}

function numero_semaine($jour, $mois, $annee)
{
    /*
     * Norme ISO-8601:
     * - La semaine 1 de toute année est celle qui contient le 4 janvier ou que la semaine 1 de toute année est celle qui contient le 1er jeudi de janvier.
     * - La majorité des années ont 52 semaines mais les années qui commence un jeudi et les années bissextiles commençant un mercredi en possède 53.
     * - Le 1er jour de la semaine est le Lundi
     */

    // Définition du Jeudi de la semaine
    if (date("w", mktime(12, 0, 0, $mois, $jour, $annee)) == 0) { // Dimanche
        $jeudiSemaine = mktime(12, 0, 0, $mois, $jour, $annee) - 3 * 24 * 60 * 60;
    } elseif (date("w", mktime(12, 0, 0, $mois, $jour, $annee)) < 4) { // du Lundi au Mercredi
        $jeudiSemaine = mktime(12, 0, 0, $mois, $jour, $annee) + (4 - date("w", mktime(12, 0, 0, $mois, $jour, $annee))) * 24 * 60 * 60;
    } elseif (date("w", mktime(12, 0, 0, $mois, $jour, $annee)) > 4) { // du Vendredi au Samedi
        $jeudiSemaine = mktime(12, 0, 0, $mois, $jour, $annee) - (date("w", mktime(12, 0, 0, $mois, $jour, $annee)) - 4) * 24 * 60 * 60;
    } else { // Jeudi
        $jeudiSemaine = mktime(12, 0, 0, $mois, $jour, $annee);
    }

    // Définition du premier Jeudi de l'année
    if (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) == 0) { // Dimanche
        $premierJeudiAnnee = mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine)) + 4 * 24 * 60 * 60;
    } elseif (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) < 4) { // du Lundi au Mercredi
        $premierJeudiAnnee = mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine)) + (4 - date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine)))) * 24 * 60 * 60;
    } elseif (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) > 4) { // du Vendredi au Samedi
        $premierJeudiAnnee = mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine)) + (7 - (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) - 4)) * 24 * 60 * 60;
    } else { // Jeudi
        $premierJeudiAnnee = mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine));
    }

    // Définition du numéro de semaine: nb de jours entre "premier Jeudi de l'année" et "Jeudi de la semaine";
    $numeroSemaine = (
            (
            date("z", mktime(12, 0, 0, date("m", $jeudiSemaine), date("d", $jeudiSemaine), date("Y", $jeudiSemaine))) -
            date("z", mktime(12, 0, 0, date("m", $premierJeudiAnnee), date("d", $premierJeudiAnnee), date("Y", $premierJeudiAnnee)))
            ) / 7
            ) + 1;

    // Cas particulier de la semaine 53
    if ($numeroSemaine == 53) {
        // Les années qui commence un Jeudi et les années bissextiles commençant un Mercredi en possède 53
        if (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) == 4 || (date("w", mktime(12, 0, 0, 1, 1, date("Y", $jeudiSemaine))) == 3 && date("z", mktime(12, 0, 0, 12, 31, date("Y", $jeudiSemaine))) == 365)) {
            $numeroSemaine = 53;
        } else {
            $numeroSemaine = 1;
        }
    }

    //echo $jour."-".$mois."-".$annee." (".date("d-m-Y",$premierJeudiAnnee)." - ".date("d-m-Y",$jeudiSemaine).") -> ".$numeroSemaine."<BR>";

    return sprintf("%02d", $numeroSemaine);
}
//****
/* * ***********************************************************************
  php easy :: pagination scripts set - Version Two
  ==========================================================================
  Author:      php easy code, www.phpeasycode.com
  Web Site:    http://www.phpeasycode.com
  Contact:     webmaster@phpeasycode.com
 * *********************************************************************** */
function paginate_two($reload, $page, $tpages, $adjacents)
{
    $firstlabel = "&laquo;&nbsp;";
    $prevlabel = "&lsaquo;&nbsp;";
    $nextlabel = "&nbsp;&rsaquo;";
    $lastlabel = "&nbsp;&raquo;";

    $out = "<ul class=\"pagination pagination-sm no-margin pull-right\">\n";

    // first
    if ($page > ($adjacents + 1)) {
        $out .= "<li><a href=\"" . $reload . "\">" . $firstlabel . "</a></li>\n";
    } else {
        $out .= "<li><span>" . $firstlabel . "</span></li>\n";
    }

    // previous
    if ($page == 1) {
        $out .= "<li><span>" . $prevlabel . "</span></li>\n";
    } elseif ($page == 2) {
        $out .= "<li><a href=\"" . $reload . "\">" . $prevlabel . "</a></li>\n";
    } else {
        $out .= "<li><a href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a></li>\n";
    }

    // 1 2 3 4 etc
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= "<li><span class=\"btn active\">" . $i . "</span></li>\n";
        } elseif ($i == 1) {
            $out .= "<li><a href=\"" . $reload . "\">" . $i . "</a></li>\n";
        } else {
            $out .= "<li><a href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a></li>\n";
        }
    }

    // next
    if ($page < $tpages) {
        $out .= "<li><a href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a></li>\n";
    } else {
        $out .= "<li><span>" . $nextlabel . "</span></li>\n";
    }

    // last
    if ($page < ($tpages - $adjacents)) {
        $out .= "<li><a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $lastlabel . "</a></li>\n";
    } else {
        $out .= "<li><span>" . $lastlabel . "</span></li>\n";
    }

    $out .= "</ul>";

    return $out;
}

//****///////////////END PAGINATION FUNCTION //****


/* return Operating System */

function operating_system_detection()
{
    if (isset($_SERVER)) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
    } else {
        global $HTTP_SERVER_VARS;
        if (isset($HTTP_SERVER_VARS)) {
            $agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
        } else {
            global $HTTP_USER_AGENT;
            $agent = $HTTP_USER_AGENT;
        }
    }
    $ros[] = array('Windows XP', 'Windows XP');
    $ros[] = array('Windows NT 5.1|Windows NT5.1', 'Windows XP');
    $ros[] = array('Windows 2000', 'Windows 2000');
    $ros[] = array('Windows NT 5.0', 'Windows 2000');
    $ros[] = array('Windows NT 4.0|WinNT4.0', 'Windows NT');
    $ros[] = array('Windows NT 5.2', 'Windows Server 2003');
    $ros[] = array('Windows NT 6.0', 'Windows Vista');
    $ros[] = array('Windows NT 7.0', 'Windows 7');
    $ros[] = array('Windows CE', 'Windows CE');
    $ros[] = array('(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center');
    $ros[] = array('(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows');
    $ros[] = array('(win)([0-9]{2})', 'Windows');
    $ros[] = array('(windows)([0-9x]{2})', 'Windows');
    // Doesn't seem like these are necessary...not totally sure though..
    //$ros[] = array('(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'Windows NT');
    //$ros[] = array('(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})', 'Windows NT'); // fix by bg
    $ros[] = array('Windows ME', 'Windows ME');
    $ros[] = array('Win 9x 4.90', 'Windows ME');
    $ros[] = array('Windows 98|Win98', 'Windows 98');
    $ros[] = array('Windows 95', 'Windows 95');
    $ros[] = array('(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows');
    $ros[] = array('win32', 'Windows');
    $ros[] = array('(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java');
    $ros[] = array('(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris');
    $ros[] = array('dos x86', 'DOS');
    $ros[] = array('unix', 'Unix');
    $ros[] = array('Mac OS X', 'Mac OS X');
    $ros[] = array('Mac_PowerPC', 'Macintosh PowerPC');
    $ros[] = array('(mac|Macintosh)', 'Mac OS');
    $ros[] = array('(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS');
    $ros[] = array('(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS');
    $ros[] = array('(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS');
    // $ros[] = array('os/2', 'OS/2');
    $ros[] = array('freebsd', 'FreeBSD');
    $ros[] = array('openbsd', 'OpenBSD');
    $ros[] = array('netbsd', 'NetBSD');
    $ros[] = array('irix', 'IRIX');
    $ros[] = array('plan9', 'Plan9');
    $ros[] = array('osf', 'OSF');
    $ros[] = array('aix', 'AIX');
    $ros[] = array('GNU Hurd', 'GNU Hurd');
    $ros[] = array('(fedora)', 'Linux - Fedora');
    $ros[] = array('(kubuntu)', 'Linux - Kubuntu');
    $ros[] = array('(ubuntu)', 'Linux - Ubuntu');
    $ros[] = array('(debian)', 'Linux - Debian');
    $ros[] = array('(CentOS)', 'Linux - CentOS');
    $ros[] = array('(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva');
    $ros[] = array('(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE');
    $ros[] = array('(Dropline)', 'Linux - Slackware (Dropline GNOME)');
    $ros[] = array('(ASPLinux)', 'Linux - ASPLinux');
    $ros[] = array('(Red Hat)', 'Linux - Red Hat');
    // Loads of Linux machines will be detected as unix.
    // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
    //$ros[] = array('X11', 'Unix');
    $ros[] = array('(linux)', 'Linux');
    $ros[] = array('(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS');
    $ros[] = array('amiga-aweb', 'AmigaOS');
    $ros[] = array('amiga', 'Amiga');
    $ros[] = array('AvantGo', 'PalmOS');
    // $ros[] = array('[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux');
    // $ros[] = array('(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV');
    $ros[] = array('Dreamcast', 'Dreamcast OS');
    $ros[] = array('GetRight', 'Windows');
    $ros[] = array('go!zilla', 'Windows');
    $ros[] = array('gozilla', 'Windows');
    $ros[] = array('gulliver', 'Windows');
    $ros[] = array('ia archiver', 'Windows');
    $ros[] = array('NetPositive', 'Windows');
    $ros[] = array('mass downloader', 'Windows');
    $ros[] = array('microsoft', 'Windows');
    $ros[] = array('offline explorer', 'Windows');
    $ros[] = array('teleport', 'Windows');
    $ros[] = array('web downloader', 'Windows');
    $ros[] = array('webcapture', 'Windows');
    $ros[] = array('webcollage', 'Windows');
    $ros[] = array('webcopier', 'Windows');
    $ros[] = array('webstripper', 'Windows');
    $ros[] = array('webzip', 'Windows');
    $ros[] = array('wget', 'Windows');
    $ros[] = array('Java', 'Unknown');
    $ros[] = array('flashget', 'Windows');
    // delete next line if the script show not the right OS
    //$ros[] = array('(PHP)/([0-9]{1,2}.[0-9]{1,2})', 'PHP');
    $ros[] = array('MS FrontPage', 'Windows');
    //$ros[] = array('(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows');
    $ros[] = array('(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows');
    $ros[] = array('libwww-perl', 'Unix');
    $ros[] = array('UP.Browser', 'Windows CE');
    $ros[] = array('NetAnts', 'Windows');
    $file = count($ros);
    $os = '';
    for ($n = 0; $n < $file; $n++) {
        //error_log("preg = " . $ros[$n][0]);
        if (preg_match('/' . $ros[$n][0] . '/i', $agent, $name)) {
            $os = @$ros[$n][1] . ' ' . @$name[2];
            break;
        }
    }
    return trim($os);
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}
