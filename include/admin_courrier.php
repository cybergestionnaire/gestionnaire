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
  Mise à jour 2020

 */

// fichier de gestion des courriers destin&eacute;s aux utilisateurs

//Affichage -----
$mesno = (string)filter_input(INPUT_GET, "mesno");
if ($mesno != "") {
    echo getError($mesno);
}

// array des types d'info
$arrayname = array(
    1 => "Mail",
    2 => "Courrier atelier",
    3 => "Courrier session"
);

$arraytype = array(
    1 => "Introduction",
    2 => "Sujet/object",
    3 => "Corps du texte",
    4 => "Signature"
);

//chargement des courriers
$courrier = Courrier::getAllCourrier();
?>

<div class="row">
<?php
if ($courrier === null) {
    echo getError(0);
} else {
    
    if (count($courrier) == 0) {
        ?>   
        <div class="col-md-8"><?php echo getError(48); ?></div>
            <div class="col-lg-3 col-xs-6"><a href="index.php?a=52&b=1"><button class="btn btn-primary">Ajouter un nouveau courrier <i class="fa fa-plus-circle"></i></button></a></div>
        
        <?php
    } else {
        ?> 
       
            <div class="col-md-8">
                <div class="box box-primary">
					<div class="box-header"><h3 class="box-title">Liste des Courriers</h3></div>
                    <div class="box-body"><table class="table">
                            <thead><tr>
                                    <th>Nom</th><th>Texte</th><th>Courrier rattach&eacute;</th><th>Type de contenu</th><th></th></tr></thead><tbody>
                                <?php
								foreach($courrier as $courrier)
                                {
									//debug($courrier->getId());
									$idcourrierurl1="index.php?a=52&b=2&idcourrier=".$courrier->getId();
									$idcourrierurl2="index.php?a=52&act=3&idcourrier=".$courrier->getId();
									?>
                                   <tr><td><?php echo $courrier->getTitre()?></td>
										<td><?php echo $courrier->getTexte()?></td>
										<td><?php echo $courrier->getName()?></td>
										<td><?php echo $courrier->getType()?></td>
                    <td><a href="<?php echo $idcourrierurl1;?>"><button type="button" class="btn bg-green sm"><i class="fa fa-edit"></i></button></a>
                    <a href="<?php echo $idcourrierurl2; ?> "><button type="button" class="btn bg-red sm"><i class="fa fa-trash-o"></i></button></a></td></tr>
								<?php } ?>
                            </tbody></table></div>
                    <div class="box-footer clearfix"><a href="index.php?a=52&b=1">
                            <button class="pull-right btn btn-default" name="create_courrier">Cr&eacute;er un courrier &nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></button></a></div>
                </div>

                <div class="box box-success"><div class="box-header"><h3 class="box-title">Gestion de la Newsletter</h3></div>
                    <div class="box-body">	
                        <?php
                        $nbnews = Courrier::getNewsletterUsers();
						
						if ($nbnews){
							$nbnewsletter=$nbnews;
							}else{
							$nbnewsletter="Vous n'avez aucun inscrit !";	
							}
						?>
                        <p>Nombre d'adh&eacute;rents abonn&eacute;s &agrave; la newsletter : <?php echo $nbnewsletter; ?> </p>
                    </div>
                    <div class="box-footer clearfix">
                        <a href="courriers/csv_exportnewsletter.php"><button class="btn btn-success"><i class="fa fa-table"></i> Exporter la liste</button></a>
                    </div>
                </div>

            </div><!-- /col -->



            <div class="col-md-4">  <div class="box box-info"><div class="box-header"><h3 class="box-title">Aide</h3></div>
                    <div class="box-body"> 
                        <p>Sur cette page vous pouvez modifier les textes qui apparaitront dans vos courriers et mails en direction des usagers. </p>
                        <p>Mettez un nom commun pour les diff&eacute;rentes parties d'un mail type par exemple "le mail de relance ou rappel". </p>
                        <p>Pour l'instant il ne sera possible de diff&eacute;rencier qu'un seul type de courrier : 1 mail, 1 courrier issu des ateliers (en pr&eacute;paration !) et 1 pour les sessions (en pr&eacute;paration!)</p>
                        <p>NB: Pour le mail de rappel (cet exemple), les donn&eacute;es de l'atelier (date-heure-lieu-animateur-sujet-d&eacute;tail) s'ins&egrave;rent entre le texte que vous mettez en "corps de texte", et la signature.</p>
                    </div>
                </div>
            </div>
			 <div class="col-md-4">  <div class="box box-success"><div class="box-header"><h3 class="box-title">Export des ateliers programmés</h3></div>
                    <div class="box-body"> 
                        <p>Exportation de la programmation en cours en tant que fiche de projet </p>
                       
                    </div>
                </div>
            </div>

        
        <?php
    }
}
?>
</div>
