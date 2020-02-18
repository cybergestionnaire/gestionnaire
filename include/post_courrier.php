<?php

// Page de traitement du formulaire de courrier
//Mise à jour 2020

$act = (string)filter_input(INPUT_GET, "act");
$id = (string)filter_input(INPUT_GET, "idcourrier");


$titrec =(string)filter_input(INPUT_POST, "courrier_titre");
$texte = (string)filter_input(INPUT_POST, "courrier_texte");
$name = (string)filter_input(INPUT_POST, "courrier_name");
$type = (string)filter_input(INPUT_POST, "courrier_type");


if ($act != "" and $act != 3) {  // verife si non vide
    // Traitement des champs a ins�rer
    if (!$titrec || !$name) {
        $mess = getError(4);
    } else {
        switch ($act) {
            case 1:   // ajout d'un courrier
				$courrier= Courrier::creerCourrier($titrec, $texte, $name, $type);
				
                if ($courrier===null) {
                    header("Location: ./index.php?a=52&mesno=0");
                } else {
                    header("Location: ./index.php?a=52");
                }
                
                break;
            case 2:   // modifie un courrier
				//$courrier= Courrier::getCourrierByID($id);
				$courrier= Courrier::modifier($id, $titrec, $texte, $name, $type);
                if ($courrier===null) {
                    header("Location: ./index.php?a=52&mesno=0");
                } else {
                    header("Location: ./index.php?a=52");
                }
                break;
        }
    }
}

if ($act == 3) { // supprime un courrier
	$courrier = Courrier::getCourrierById($id);
    if ($courrier->supprimer()) {
        header("Location: ./index.php?a=52");
    } else {
        header("Location: ./index.php?a=52&mesno=0");
    }
}
