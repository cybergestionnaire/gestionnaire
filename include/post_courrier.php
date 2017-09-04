<?php

// Page de traitement du formulaire de breve

$act = $_GET["act"];
$id = $_GET["idcourrier"];

$titrecourrier = addslashes($_POST["titre"]);
$texte = addslashes($_POST["texte"]);

$name = $_POST["courrier_name"];
$type = $_POST["courrier_type"];


if ($act != "" and $act != 3) {  // verife si non vide
    // Traitement des champs a ins�rer
    if (!$titrecourrier || !$name) {
        $mess = getError(4);
    } else {
        switch ($act) {
            case 1:   // ajout d'un courrier
                if (false == createCourrier($titrecourrier, $texte, $name, $type)) {
                    header("Location: ./index.php?a=52&mesno=0");
                } else {
                    header("Location: ./index.php?a=52");
                }
                break;
            case 2:   // modifie un courrier
                if (false == modCourrier($id, $titrecourrier, $texte, $name, $type)) {
                    header("Location: ./index.php?a=52&mesno=0");
                } else {
                    header("Location: ./index.php?a=52");
                }
                break;
        }
    }
}

if ($act == 3) { // supprime un courrier
    if (false == supCourrier($id)) {
        header("Location: ./index.php?a=52&mesno=0");
    } else {
        header("Location: ./index.php?a=52");
    }
}
