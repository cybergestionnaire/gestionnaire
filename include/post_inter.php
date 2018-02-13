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

// error_log('in post_inter.php -------------------------');
// error_log("---- POST ----");
// error_log(print_r($_POST, true));
// error_log("---- GET  ----");
// error_log(print_r($_GET, true));

// verification de l'envoi du formulaire
if (isset($_POST["submit"])) {

    // traitement des post et get des interventions

    $titreInter = (string)filter_input(INPUT_POST, "titre");
    $date = (string)filter_input(INPUT_POST, "date");
    $comment = (string)filter_input(INPUT_POST, "comment");
    $dispo = (string)filter_input(INPUT_POST, "dispo");

    
    // recuperation des postes concernés
    
    $materiels = Materiel::getMateriels();
    if ($materiels !== null && count($materiels) > 0) {
        $comp = array();
        foreach($materiels as $materiel) {
            if (isset($_POST[$materiel->getId()]))  {
                $comp[] = $materiel->getId();
            }
        }
    }

    if (!$titreInter || !$comment) { //verification des champs non vide
        header("Location:index.php?a=3&b=1&mesno=4");
    } else {
       $inter = Intervention::creerIntervention($titreInter, $comment, $dispo, $date);
        
        if ($inter === null) {
            header("Location:index.php?a=3&b=1&mesno=0");
        } else {
            foreach ($comp as $key => $value) {
                if (!$inter->addMateriel($value)) {
                    header("Location:index.php?a=3&b=1&mesno=4");
                }
            }
            header("Location:index.php?a=3&mesno=14");
        }
    }
}
