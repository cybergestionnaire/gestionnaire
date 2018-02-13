<?php

$separator = ";";

// Premi�re ligne Excel
$csv_output = "Structure" . $separator . "Date d'inscription" . $separator . "Places maximum autoris�es" . $separator . "Places r�serv�es" . $separator . "Montant H.T." . $separator . "Montant T.V.A." . $separator . "Montant T.T.C." . $separator . "Transport H.T." . $separator . "Transport T.V.A." . $separator . "Transport T.T.C.";
$csv_output .= "\n";

//Boucle sur les resultats
foreach ($inscriptions as $key => $inscription) {
    $inscription["montant_transport"] = $inscription["montant_transport_arrivee"] + $inscription["montant_transport_depart"];
    //$inscription["montant_transport"] = 120;

    $csv_output .= $inscription["je_nom"] . $separator;
    $csv_output .= $inscription["liste_date"] . $separator;
    $csv_output .= $inscription["liste_places_max"] . $separator;
    $csv_output .= $inscription["places_reservees"] . $separator;
    $csv_output .= number_format($inscription["liste_montant"], 2, ".", " ") . $separator;
    $csv_output .= number_format(get_tva($inscription["liste_montant"]), 2, ".", " ") . $separator;
    $csv_output .= number_format(get_taxed_price($inscription["liste_montant"]), 2, ".", " ") . $separator;
    $csv_output .= number_format($inscription["montant_transport"], 2, ".", " ") . $separator;
    $csv_output .= number_format(get_tva($inscription["montant_transport"]), 2, ".", " ") . $separator;
    $csv_output .= number_format(get_taxed_price($inscription["montant_transport"]), 2, ".", " ");
    $csv_output .= "\n";
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . date("Y-m-d") . "-inscriptions-" . rewrite_value($inscription["event_nom"]) . ".xls");
print utf8_decode($csv_output);
exit();
