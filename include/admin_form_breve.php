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

$idBreve = (string) filter_input(INPUT_GET, "idbreve");

// formulaire de gestion des breves

if ($idBreve == "") {
//if (false == isset($id)) {
    // creation
    $post_url = "index.php?a=4&b=1&act=1";
    $label_bouton = "Cr&eacute;er une br&egrave;ve";
    $datenews = date("Y-m-d H:i");

    $titr =  "";
    $comment = "";
    $visible = "";
    $type = "";
    $datenews = date('Y-m-d H:i');
    $datepublish = "";
    $idEspace = "";
    
} else { // modification
    $post_url = "index.php?a=4&b=2&act=2&idbreve=" . $idBreve;
    $label_bouton = "Modifier la br&egrave;ve";
    //$row = getBreve($idBreve);
    $breve = Breve::getBreveById($idBreve);

    //Informations matos
    $titr =  htmlentities($breve->getTitre());
    $comment =  htmlentities($breve->getCommentaire());
    $visible = $breve->getVisible();
    $type = $breve->getType();
    $datenews = htmlentities($breve->getDateBreve());
    $datepublish = htmlentities($breve->getDatePublication());
    $idEspace = $breve->getIdEspace();


    //debug($visible);
}
//Affichage -----
echo isset($mess) ? $mess : '';
// retrouver les espaces
// $espaces = getAllepn();

$espaces = Espace::getEspaces();

// array des types d'info
$arraytype = array(
    1 => "News",
    2 => "Reunion",
    3 => "Animation",
    4 => "Conference",
    5 => "Evenement"
);
?>

<form method="post" action="<?php echo $post_url; ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header"><h3 class="box-title"><?php echo $label_bouton; ?></h3></div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Titre*</label>
                        <input type="text" name="titr" value="<?php echo $titr ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Contenu*</label>
                        <textarea name="comment" class="form-control" rows="5" placeholder="Mettez votre texte au format html aussi !"><?php echo $comment ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Visibilit&eacute;</label>
                        <?php
                        switch ($visible) {
                            case 0:
                                $sel1 = "checked=\"checked\"";
                                $sel2 = "";
                                break;
                            case 1:
                                $sel1 = "";
                                $sel2 = "checked=\"checked\"";
                                break;
                            default:
                                $sel1 = "checked=\"checked\"";
                                $sel2 = "";
                                break;
                        }
                        ?>
                        <div class="radio">
                            <label><input  type="radio" name="visible" value="0" <?php echo $sel1; ?>> Public (tout le monde peut la consulter)</label>
                            <label><input  type="radio" name="visible" value="1" <?php echo $sel2; ?>> Interne (Visible uniquement par les administrateurs et animateurs </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" >
                            <?php
                            foreach ($arraytype as $key => $value) {
                                if ($type == $key) {
                                    echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                                } else {
                                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date de l'&eacute;v&eacute;nement</label>
                        <input type="text" class="form-control" name="datenews" value="<?php echo $datenews ?>" placeholder="2014-15-15 15:30">
                        <input type="hidden" name="datepublish" value="<?php echo date('Y-m-d H:i'); ?>" ></div>

                    <div class="form-group">
                        <label>Epn li&eacute;</label>
                        <select name="idepn" class="form-control" >
                            <?php
                            foreach ($espaces as $espace) {
                                if ($idEspace == $espace->getId()) {
                                    echo "<option value=\"" . $espace->getId() . "\" selected>" . htmlentities($espace->getNom()) . "</option>";
                                } else {
                                    echo "<option value=\"" . $espace->getId() . "\">" . htmlentities($espace->getNom()) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="box-footer"><input type="submit" name="nom" value="<?php echo $label_bouton; ?>" class="btn btn-primary"></div>

                </div>
            </div>
        </div>
    </div>
</form>


