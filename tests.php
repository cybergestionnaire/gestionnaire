<?php

// fichier de test pour essayer des bouts de codes.

include("include/fonction.php");
include("include/fonction2.php");
include("include/fonction_maj.php");
$versionactuelle = getMajConfigVersion("1");


echo "Version actuelle : " . $versionactuelle . "<br />";
echo "type de la variable : " . gettype($versionactuelle) . "<br />";
echo "Version actuelle : " . floatval($versionactuelle) . "<br />";
echo "type de la variable : " . gettype(floatval($versionactuelle)) . "<br />";

if (floatval($versionactuelle) < 1.3) {
    echo "inférieur";
} else {
    echo "supérieur";
}
?>