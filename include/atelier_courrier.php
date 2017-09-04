<?php
/*
  a = 33
  Afficher la liste des adh�rents qui vont aux ateliers
  impression des courriers de relance
  impression pour l'inscription
  d�tail des inscriptions pass�es, pr�sence oui/non
  colonne : Nom pr�nom | inscriptions pass�es | inscription en cours | validation inscription
  ajout : listing des adresses pour envoi
 */

$mesno = $_GET["mesno"];
$export = $_POST["export"];
$present = $_POST["present"];

if ($mesno != "") {
    echo getError($mesno);
}

if (isset($_POST['export'])) {
    for ($i = 0, $c = count($_POST['present_']); $i < $c; $i++) {
        $ids_presents = $ids_presents . $_POST['present_'][$i] . ";";
    }
    echo "<h4 class=\"alert_info\"><a href=\"a_export.php?ids=$ids_presents\" target=\"_blank\">export de la liste</a></h4>";
    //	echo $ids_presents;
}
?>

<?php
$y = date('Y');
$m = date('m');
if ($m == 12) {
    $y = $y + 1;
    $m = 1;
}

$result = getAllUserAtelierMois($y, $m);
if (false == $result) {
    echo getError(1);
} else {  // affichage du resultat
    $nb = mysqli_num_rows($result);

    if ($nb > 0) {
        ?>
        <article class="module width_full"><header><h3>Export de toutes les fiches d'inscription</h3></header>
            <div class="module_content"><p>Cliquez sur le bouton pour exporter toutes les fiches individuelles des inscrits ci-dessous au format PDF. Date des ateliers le mois en cours et les mois suivants</p></div>
            <footer><div class="submit_link">
                    <?php
                    $row = MakePDFUserAtelier($y, $m);
        if (mysqli_num_rows($row) > 0) {
            $resultpdf = mysql_fetch_array($row);
            //debug($resultpdf);
                        //envoi de la liste � terminer
                        //echo '<a href="pdf_atelier.php?user="'.$ipdf["id_user"].'" target="_blank\">  ';
        } ?>
                    <input type="submit" name="Creation PDF" value="export_pdf" class="alt_btn"></a></div></footer>
        </article>
        <article class="module width_full"><header><h3>Liste pour lettre de rappel</h3></header>
            <div class="tab_container">
                <form method="post" action="index.php?a=33">
                    <table class="tablesorter" cellspacing="0"><thead> 
                            <tr> <th>Nom Pr&eacute;nom</th>
                                <th>email</th>
                                <th>tel</th>
                                <th>fiche adh</th>
                                <th>inscription</th>
                                <th>Export des adresses</th></tr><tbody>
                            <?php
                            for ($i = 1; $i <= $nb; $i++) {
                                $row = mysqli_fetch_array($result);


                                echo "<tr>
							<td>" . $row["nom_user"] . "&nbsp;" . $row["prenom_user"] . "</td>
                             <td>" . $row["mail_user"] . "</td>
							 <td>" . $row["tel_user"] . "</td>
							 <td><a href=\"index.php?a=1&b=2&iduser=" . $row["id_user"] . "\"><img src=\"images/icn_edit.png\"></a></td>
							 <td><a href=\"pdf_atelier.php?user=" . $row["id_user"] . "\" target=\"_blank\"><img src=\"images/icn_pdf.png\"></a>
                             <td><input type=\"checkbox\" name=\"present_[]\" value=" . $row["id_user"] . "></td>
                             </tr>";
                            } ?>
                        </tbody></table></div>
            <footer><div class="submit_link"><input type="submit" name="export" value="export" class="alt_btn"></div></footer></form></article>
                    <?php
    }
    //fin des tableaux
}
            ?>

