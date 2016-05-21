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
 

 include/telecharger.php V0.1
*/

// fichier de telechargement de la sauvegarde
// attention le dossier $chemin.$fichier doit posseder les droits en ecriture !!
$fichier  = $_GET["fichier"];
$chemin   = $_GET["chemin"];

header("Content-disposition: attachment; filename=$fichier");
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: text/plain\n"); // Surtout ne pas enlever le \n
//header("Content-Length: ".filesize($chemin.$fichier));
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
header("Expires: 0");
readfile($chemin . $fichier);

?>