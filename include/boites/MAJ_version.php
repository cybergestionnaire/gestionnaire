<?php
    $version = getConfigVersion($_SESSION["idepn"]);
    $newversion = 1.3;
    if ($version <> $newversion) {
?>
    <!--DIV Mises &agrave; jour -->
    <div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header">
                <i class="fa fa-warning"></i><h3 class="box-title">Mise &agrave; jour de version</h3>
            </div>
            <div class="box-body">
                Une nouvelle version demande bien quelques efforts, cliquez sur le bouton ci-dessous pour faire la mise &agrave; jour imm&eacute;diatement !
            </div>
            <div class="box-footer">
                <a href="index.php?a=61"><input type="submit" name="mises &agrave; jour" value="Faire la mise &agrave; jour" class="btn btn-danger"></a>
            </div>
        </div>
    </div>
    <!-- / MAJ -->
<?php
    }
?>