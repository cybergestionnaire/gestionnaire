<?php
// verifier les abonnements des adherent et mettre a jour le statut actif
$majadh = getLogUser('adh');
$logadh = false;
if (mysqli_num_rows($majadh) == 0) {
    $listAdhinactifs = getAdhInactif(date('Y-m-d'));
    $updateA = updateUserStatut(); // les usagers dont la date de renouvellement est du jour.
    //ajout d'un log
    if ($updateA <> false) { //maj type 1 == update tab_user
        $logadh = addLog(date('Y-m-d H:i'), "adh", '1', 'Mise &agrave; jour des adhesions adherents du jour');
    }
}

if ($logadh == true) {
    ?>
    <div class="col-md-4">
        <div class="alert alert-success alert-dismissable">
            <i class="fa fa-check-square"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Mise &agrave; jour des adh&eacute;rents effectu&eacute;e. <?php echo $updateA; ?> adh&eacute;rents inactifs !
        </div>
    </div>
    <?php
}
?>