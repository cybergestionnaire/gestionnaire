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

// Fichier de post de mon compte / modification du mot de passe

if (isset($_POST["submit"])) {
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];

    if ($pass1 != "" and $pass2 != "") {
        if ($pass1 != $pass2) {
            $mess = getError(7);
        } else {
            $utilisateur = Utilisateur::getUtilisateurById($_SESSION["iduser"]);
            $result = $utilisateur->updatePassword($pass1);
            if ($result == false) {
                $mess = getError(0);
            } else {
                $mess = getError(8);
            }
        }
    }

    ///inscription a la newsletter


    if (false == updateNewsletter($_SESSION["iduser"], $_POST["newsletter"])) {
        $mess = getError(0);
    } else {
        $mess = getError(8);
    }
}
