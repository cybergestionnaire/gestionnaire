<?php
//recuperation des get & post

$act = $_GET["act"];
$idinter = $_GET["idinter"];
$statut = $_POST["statut"];

switch ($act) {
    case mod:
        if (false == modInter($idinter, $statut)) {
            echo getError(0);
        }
        break;
    case del:
        if (false == supInter($idinter)) {
            echo getError(0);
        }
        break;
}

$result = getAllInter();
?>

<div class="row">   <div class="col-md-9">
        <?php
        $nb = mysqli_num_rows($result);
        if ($nb == 0) {
            echo getError(12);
        } else {
            ?>
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Interventions archiv&eacute;es</h3></div>
                <div class="box-body"> <table class="table">
                        <thead> 
                            <tr> <th>Date</th><th>Poste</th><th>Type d'intervention</th><th>Commentaire</th><th>Statut</th><th></th></tr></thead>
                        <tbody>
                            <?php
// affichage des interventions

                            for ($i = 1; $i <= $nb; $i++) {
                                $row = mysqli_fetch_array($result);
                                switch ($row["statut_inter"]) {
                                    case 0:

                                        $sel1 = "selected";
                                        $sel2 = "";
                                        break;
                                    case 1:

                                        $sel1 = "";
                                        $sel2 = "selected";
                                        break;
                                } ?>


                                <tr>
                                    <td><?php echo $row["date_inter"]; ?></td>

                                    <td>
                                        <?php
                                        $result2 = getInterComputer($row["id_inter"]);
                                if ($result2 != false) {
                                    $nb2 = mysqli_num_rows($result2);
                                    if ($nb2 > 0) {
                                        for ($j = 1; $j <= $nb2; $j++) {
                                            $row2 = mysqli_fetch_array($result2);
                                            if ($j > 1) {
                                                echo ",&nbsp;";
                                            }
                                            echo $row2["nom_computer"];
                                        }
                                    }
                                } ?>
                                    </td>
                                    <td><?php echo stripslashes($row["titre_inter"]); ?></td>
                                    <td><?php echo stripslashes($row["comment_inter"]); ?></td>

                            <form action="index.php?a=3&act=mod&idinter=<?php echo $row['id_inter']; ?>" method="post" role="form">
                                <td >

                                    <select name="statut" class="form-control" style="width:200px;">
                                        <option value="0" <?php echo $sel1; ?>>Intervention en cours</option>
                                        <option value="1" <?php echo $sel2; ?>>Intervention termin&eacute;e</option>
                                    </select><input type="submit" value="Modifier le statut" class="form-control">
                                </td></form>
                            <td><a href="index.php?a=3&act=del&idinter=<?php echo $row['id_inter']; ?>">
                                    <button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a></td>

                            <?php
                            } ?>


                        </tr></tbody></table></div></div>

            <?php
        }
        ?>	
    </div>

    <div class="col-md-3">
        <div class="small-box bg-light-blue">  <div class="inner"><h3>&nbsp;</h3><p>nouvelle intervention</p></div>
            <div class="icon"><i class="ion ion-wrench"></i></div>
            <a href="index.php?a=3&b=1" class="small-box-footer">Ajouter <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>


</div>