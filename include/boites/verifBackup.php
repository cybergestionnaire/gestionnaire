<?php //*** Backup base automatique 1 fois par mois pour les admins qui se connectent! ***///
    if (getLogBackup()) {
     
?>
    <div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header"> <i class="fa fa-warning"></i><h3 class="box-title">Sauvegarde de la base de donn&eacute;e</h3></div>
            <div class="box-body">
                Cela fait un mois que la base de donn&eacute;e n'a pas &eacute;t&eacute; sauvegard&eacute;e, cliquez sur le bouton pour la lancer !
            </div>
            <div class="box-footer">
                <a href="index.php?a=62&maj=0"><input type="submit" name="sauvegarde" value="Lancer la sauvegarde" class="btn btn-danger"></a>
            </div>
        </div>
    </div>
    
<?php 
    }
?>