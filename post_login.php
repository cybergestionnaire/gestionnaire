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

    
    Original work : Cybermin / 2006-2008 Namont Nicolas

*/

// Process d'autentification utilisateur et admin.

$login  = $_POST["log"];
$passwd = $_POST["pass"];

//include ("include/fonction.php");
require_once("include/class/Utilisateur.class.php");

$utilisateur = Utilisateur::getUtilisateurByLoginPassword($login,$passwd);

if ($utilisateur == null) {
    header("Location: ./index.php?error=3");
} else {
    session_start();
    $_SESSION["login"]  = $utilisateur->getLogin();
    $_SESSION["status"] = $utilisateur->getStatut();
    $_SESSION["iduser"] = $utilisateur->getId();
    $_SESSION["idepn"]  = $utilisateur->getIdEspace();
    $utilisateur->MAJVisite();
    header("Location: ./index.php");
}

?>