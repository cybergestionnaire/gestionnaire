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
function getAllCityname($ordreAlpha = false)
{
    if ($ordreAlpha) {
      $sql = "SELECT `id_city`,`nom_city` FROM `tab_city` ORDER BY `nom_city`" ;
    } else {
      $sql = "SELECT `id_city`,`nom_city` FROM `tab_city` ORDER BY `id_city`" ;
    }
    $db=opendb();
    $result = mysqli_query($db,$sql);
    closedb($db);
    if (FALSE == $result)
    {
        return FALSE ;
    } else {
        $city = array();
        $nb= mysqli_num_rows($result);
        for ($i=1;$i<=$nb;$i++)
        {
            $row = mysqli_fetch_array($result);
            $city[$row["id_city"]] = $row["nom_city"] ;
        }
        return $city ;
    }
}

    require_once("include/class/Ville.class.php");
    require_once("include/class/Forfait.class.php");
    require_once("include/class/Tarif.class.php");
    // Gestion de la base de donn&eacute;es

    $total_usagers = isset($_POST["total_usagers"]) ? $_POST["total_usagers"] : '';
    $total_echec   = isset($_POST["total_echec"])   ? $_POST["total_echec"] : '';
    $total_actif   = isset($_POST["total_actif"])   ? $_POST["total_actif"] : '';
    $total_inactif = isset($_POST["total_inactif"]) ? $_POST["total_inactif"] : '';

    if (isset($mess) && $mess == 1) {
        echo "<div>le repertoire de stockage 'SQL' &agrave; la racine de l'application CyberGestionnaire n'est pas accessible en &eacute;criture.<br /> Vous devez autoriser l'acc&egrave;s pour pouvoir sauvegarder le fichier</div>";
    }

    include("include/boites/menu-parametres.php");
?>
<!-- DIV accès direct aux autres paramètres-->
<div class="row">

    <!-- Left col -->
    <div class="col-lg-6">
  
        <!-- liste des espaces existants-->
        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Sauvegarde des donn&eacute;es</h3></div>
            <div class="box-body">
                <form method="post" action="index.php?a=49&act=save" role="form">
                    <div class="form-group">
                        <input type="radio" name="action" value="2" checked >
                        <label> Transmettre dans un fichier :</label>
                        <input type="text" name="fichier" value="<?php echo date('ymd');?>_gestionnaire.cyb">
                    </div>
                    <div class="form-group">
                        <input type="radio" name="action" value="1">
                        <label> Afficher le contenu de la sauvegarde</label>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Sauvegarder les donn&eacute;es"  class="btn btn-default">
                    </div>
           
<?php
    if (isset($save) && $save != "") {
        echo "<div class=\"form-group\"><textarea style=\"font-size:9pt;width:100%\"  rows=\"4\" class=\"form-control\">" . $save . "</textarea></div>" ;
    }
?>
          
                </form>
<?php
// Restauration, cette fonction est desactiv&eacute; pour des raison de s&eacute;curit&eacute;
/*
       <tr>
           <td>&nbsp;</td></tr>
       <tr class="list_title">
           <td>Restauration de la base de donn&eacute;es</td></tr>
       <form method="post" action="index.php?a=8&act=restore" enctype="multipart/form-data">
       <tr class="list">
           <td>Fichier de sauvegarde : <input type="file" name="restore_file">&nbsp;&nbsp;<input type="submit" value="Restaurer les donn&eacute;es"></td></tr>
       </form>

*/
?>
            </div>
            <div class="box-footer">
                <p>Pour restaurer les donn&eacute;es merci de passer par phpmyadmin pour r&eacute;importer un fichier de sauvegarde.<br></p>
            </div>
        </div><!-- .box -->

        <!-- aide restauration-->
        <div class="box box-info">
            <div class="box-header"><h3 class="box-title">Aide &agrave; l'import de la base des adh&eacute;rents depuis un CSV Cybanim</h3></div>
            <div class="box-body">
                <p class="text-info">Restauration de la base de donn&eacute;es usagers provenant de Cybanim seulement</p>
                <p class="text-red">Avant de commencer l'importation assurez-vous que :</p>
                <p>* Le fichier ait &eacute;t&eacute; export&eacute; en Windows-Latin1 ou UTF-8<br>* Le s&eacute;parateur de colonne choisi est bien le <b>";"</b>.
                <br>Pour &ecirc;tre sur, ouvrez le fichier avec votre tableur (excel ou calc), quand il vous demande la conversion, essayez ';' ou tabulation, et pour l'encodage des carat&egrave;res choisissez Windows-Latin1 ou UTF-8, 
                si les caract&egrave;res ne s'affichent pas correctement, essayez Latin-1 ou celui qui convient. 
                Puis suivez la proc&eacute;dure suivante :</p>
                <ol><li>Enregistrez le fichier au format excel ou calc</li>
                <li>V&eacute;rifiez que tous les champs correspondent aux en-t&ecirc;tes de colonnes (que la date d'inscription ne soit pas remplie avec du texte par exemple)</li>
                <li><span class="text-red">Remplacez les ent&ecirc;tes de colonne suivantes : <b>"date d'inscription" par "inscription"</b> (sans les "), et <b>"Espace d'origine" par "Espace"</b>  (sans les "); Assurez-vous ne pas avoir de double quote ('') &agrave; la place des simple (') pour les apostrophes.</span> Car il semblerait que le traitement de l'apostrophe n'est pas le m&ecirc;me suivant les diff&eacute;rents exports que j'ai pu traiter depuis cybanim (!!).</li>
                <li>Enregistrez sous le fichier au format CSV, dans la boite de dialogue suivante : </li>
                <li>Choisissez bien Windows-Latin1 ou UTF-8 pour l'encodage des caract&egrave;res puis le ";" pour la s&eacute;paration des colonnes, <span class="text-red">important ! veuillez d&eacute;cocher toutes les autres options propos&eacute;es, ne pas choisir la tabulation, car elle fausse les donn&eacute;es !</span></li>
                <li>Enregistrez ce nouveau fichier.</li>
                <li>Vous pourrez maintenant lancer l'importation dans CyberGestionnaire, ci-dessous.</li></ol>

                <p class="text-red">N'oubliez pas de choisir une option pour g&eacute;n&eacute;rer un nouveau mot de passe, puis la ville par d&eacute;faut au cas o&ugrave; aucune n'aurait &eacute;t&eacute; renseign&eacute;e, ainsi que le tarif par d&eacute;faut que vous souhaitez appliquer &agrave; vos adh&eacute;rents.</p>
                <p>La date de renouvellement du compte usager est par d&eacute;faut import&eacute;e au m&ecirc;me mois et m&ecirc;me jour que la date d'inscription ajust&eacute;e &agrave; l'ann&eacute;e en cours, sauf si l'adh&eacute;rent est inactif.</p>

            </div>
        </div><!-- .box -->
    </div><!-- .col-lg-6 -->

    <!-- Restauration, cette fonction permet de restaur&eacute; la bdd usagers cybanim -->
    <div class="col-lg-6">
  
        <!-- liste des espaces existants-->
        <div class="box box-warning">
            <div class="box-header"><h3 class="box-title">Restauration Cybanim</h3></div>
            <form method="post" action="index.php?a=49&act=restore" enctype="multipart/form-data" role="form">
                <div class="box-body">
<?php
    if ($total_usagers == '') {   
?>
        
                    <div class="form-group">
                        <label> Fichier de sauvegarde au format CSV</label>
                        <input type="file" name="restore_file" >
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Tarif consultation accord&eacute; aux usagers automatiquement</label>
                        <select name="temps_user" class="form-control" >
<?php
        $forfaits      = Forfait::getForfaits();
        foreach ($forfaits AS $forfait) {
            echo "<option value=\"" . $forfait->getId() . "\">" . htmlentities($forfait->getNom()) . "</option>";
        }
?>
                        </select>
                    </div>
        
                    <!--<input type="text" name="temps_user" class="form-control"/></div>-->
                    <div class="form-group">
                        <label>Choisir la ville par d&eacute;faut (quand aucune n'a &eacute;t&eacute; renseign&eacute;e)  <small class="badge bg-blue" data-toggle="tooltip" title="Pensez a cr&eacute;er une ville 'Non renseign&eacute;e' "><i class="fa fa-info"></i></small></label>
                        <select name="ville" class="form-control" >
<?php
        // recupere les villes
        $villes = Ville::getVilles();
                
        foreach ($villes AS $ville) {
            echo "<option value=\"" . $ville->getId() . "\">" . htmlentities($ville->getNom()) . "</option>";
        }
?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tarif par d&eacute;faut qui sera appliqu&eacute; &agrave; l'ensemble des usagers</label>
                        <select name="tarif" class="form-control" >
<?php
        //recuperer les tarifs
        $tarifs = Tarif::getTarifsbyCategorie(2);
        foreach ($tarifs AS $tarif) {
            echo "<option value=\"" . $tarif->getId() . "\">" . htmlentities($tarif->getNom()) . "</option>";
        }
?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>G&eacute;n&eacute;ration du nouveau mot de passe *</label>
                        <div class="radio">
                            <label><input type="radio" name="mdp" value="1" checked>&nbsp; nom.prenom</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="mdp" value="2" >&nbsp; AAAAnom (&agrave; partir de la date de naissance)</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="mdp" value="3" >&nbsp; AAAAMMDD  (&agrave; partir de la date de naissance)</label>
                        </div>
                    </div>
<?php
    }
?>
    
                </div><!-- .box-body -->
                <div class="box-footer">
                    <?php if($total_usagers == ''){ echo '<div class="input-group"><input align="absbottom" type="submit" value="Restaurer les donn&eacute;es" class="btn bg-red"></div> ';     } ?>
                </div>
            </form>
        </div><!-- .box -->

<?php
    if ($total_usagers != '') {
?>
        <h4 class="box-title">R&eacute;sultat de l'importation</h4>
        <p><?php echo $total_echec; ?>  adh&eacute;rents non-inscrit(s) suite &agrave; un &eacute;chec &agrave; l'importation dans la bdd, sur <?php echo $total_usagers; ?> usagers.<br>
        <?php echo $total_actif; ?> usagers actif sur <?php echo $total_usagers; ?> usagers.<br>
        <?php echo $total_inactif; ?> usagers inactif sur <?php echo $total_usagers; ?> usagers.</p>
        <p>Vous pouvez consulter le log d'importation pour voir les erreurs dans le fichier situ&eacute; dans le dossier /log, et r&eacute;cup&eacute;rer les usagers qui n'ont pu &ecirc;tre import&eacute;s.</p>
        <?php
    }
?>
    </div>
    </div><!-- /box-->

</div><!-- /col-->
