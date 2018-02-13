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
  2012 Dauvergne Florence

 */
//require_once("include/class/Utilisateur.class.php");
//require_once("include/class/Tarif.class.php");
//require_once("include/class/Forfait.class.php");


// admin --- Utilisateur
$term = isset($_POST["term"]) ? $_POST["term"] : '';
$mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
$adh = isset($_GET['adh']) ? $_GET['adh'] : '';

if ($mesno != "") {
    echo getError($mesno);
}


// Tableau des unité d'affectation
$tab_unite_temps_affectation = array(
    1 => 1, //minutes
    2 => 60 //heures
);

// Tableau des fréquence d'affectation
$tab_frequence_temps_affectation = array(
    1 => "par Jour",
    2 => "par Semaine",
    3 => "par Mois"
);

//tableau des statuts
$statutarray = array(
    1 => "Actif",
    2 => "Inactif",
    6 => "Archiv&eacute;"
);

//** adherents mis en archive ***///
if (isset($_POST["archivage"])) {
    $arrayusers = isset($_POST["archiv_"]) ? $_POST["archiv_"] : '';
    $nbusersarchiv = count($arrayusers);

    if ($nbusersarchiv > 0) {
        for ($i = 0; $i < $nbusersarchiv; $i++) {
            // $utilisateur = Utilisateur::getUtilisateurById($arrayusers[$i]);
            // $utilisateur->archiver();
            Utilisateur::archiver($arrayusers[$i]);
        }

        echo '<div class="row"><div class="col-md-4">';
        echo geterror(47);
        echo '</div></div>';
    }
    //vidage des variables
    $arrayusers = [];
    $nbusersarchiv = 0;
}
$classadh = '';
?>


<div class="row"> 


    <div class="col-xs-12">
        <?php
        if (strlen($term) >= 2) {
            // Recherche d'un adherent
            $utilisateursRecherche = Utilisateur::searchUtilisateurs($term);
            $nbUtilisateursRecherche = count($utilisateursRecherche);
            if ($utilisateursRecherche == null or $nbUtilisateursRecherche == 0) {
                ?>
                <div class="col-xs-6">
                    <?php echo getError(6); ?>
                </div>
                <div class="col-xs-6"><div class="alert alert-info alert-dismissable"><i class="fa fa-info"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>&nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1" >Cr&eacute;er un nouvel utilisateur ?</a>
                    </div>
                    <?php
            } else {
                // affichage des résultats de recherche
                    ?>
                    <!-- Resultats de la recherche -->
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo "R&eacute;sultats de la recherche: " . $nbUtilisateursRecherche . ""; ?>&nbsp;&nbsp;<small class="badge bg-blue" data-toggle="tooltip" title="Les adh&eacute;rents inactifs sont gris&eacute;s"><i class="fa fa-info"></i></small></h3>
                            <!-- div recherche -->
                            <div class="box-tools">
                                <div class="input-group">
                                    <form method="post" action="index.php?a=1">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                            <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table">
                                <thead>
                                    <tr><th></th><th>Nom</th><th>Pr&eacute;nom</th><th>login</th><th>Age</th><th>Visite r&eacute;cente(r&eacute;sa)</th><th>Statut</th><th>Adh&eacute;sion</th><th>Temps Utilis&eacute;</th></tr>
                                </thead>
                                <tbody> 
                                    <?php
                                    //error_log(print_r($utilisateursRecherche));
                                    $i = 0;
                foreach ($utilisateursRecherche as $utilisateurRecherche) {
                    $i++;
                    //print_r($utilisateurRecherche);
//            for ($i = 1; $i <= $nb; $i++) {
//                 $row = mysqli_fetch_array($result) ;

                    $age = $utilisateurRecherche->getAge();
                    $tarifAdhesion = Tarif::getTarifById($utilisateurRecherche->getIdTarifAdhesion());
                    if ($tarifAdhesion != null) {
                        $adhesion = $tarifAdhesion->getNom();
                    } else {
                        $adhesion = '';
                    }
                    $aujourdhui = date_create(date('Y-m-d'));
                    $daterenouvellement = date_create($utilisateurRecherche->getDateRenouvellement());

                    $interval = date_diff($aujourdhui, $daterenouvellement);
                    //debug($interval->format('%R%a'));
                    if ($utilisateurRecherche->getStatut() == 1) {
                        if ($daterenouvellement <= $aujourdhui) {
                            $classadh = 'label label-warning';
                        } elseif ($daterenouvellement > $aujourdhui) {
                            $classadh = 'label label-success';
                        }
                    } elseif ($utilisateurRecherche->getStatut() == 2) {
                        $classadh = 'label label-danger';
                    }

                    //TARIF CONSULTATION
                    $forfaitConsultation = $utilisateurRecherche->getForfaitConsultation();

                    if ($forfaitConsultation != null) {
                        $min = $tab_unite_temps_affectation[$forfaitConsultation->getUniteConsultation()];
                        $tarifreferencetemps = $forfaitConsultation->getDureeConsultation() * $min;

                        $restant = $utilisateurRecherche->getTempsRestant();
                        $rapport = round(($restant / $tarifreferencetemps) * 100);
                    }

                    if ($utilisateurRecherche->getStatut() == 2 or $utilisateurRecherche->getStatut() == 6) {
                        $class = "text-muted";
                    } else {
                        $class = "";
                    }

                    //dernière reservation
                    $lasteresa = $utilisateurRecherche->getLastResa();
                    if ($lasteresa == null) {
                        $lasteresa = "NC";
                    } ?>
                                        <tr class="<?php echo $class ?>">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo htmlentities($utilisateurRecherche->getNom()) ?></td>
                                            <td><?php echo htmlentities($utilisateurRecherche->getPrenom()) ?></td>

                                            <td><?php echo htmlentities($utilisateurRecherche->getLogin()) ?></td>
                                            <td><?php echo $age ?> ans</td>
                                            <td><?php echo $lasteresa == "NC" ? "Inconnu" : getDayfr($lasteresa->getDateResa()); ?></td>
                                            <td><?php echo $statutarray[$utilisateurRecherche->getStatut()] ?></td>
                                            <td><span class="<?php echo $classadh ?>"><?php echo $adhesion ?></span></td>
                                            <td>
                                                <?php
                                                //statut actif
                                                if ($utilisateurRecherche->getStatut() == 1) {
                                                    if ($forfaitConsultation != null) {
                                                        ?>
                                                        <span class="badge bg-blue"><?php echo $forfaitConsultation->getNom() ?></span>&nbsp;<b><?php echo getTime($restant) ?></b>
                                                        <div class="progress">
                                                            <div class="progress progress-sm active">
                                                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:" . $rapport . "%"; ?>"></div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    echo '<span class="badge bg-red">NC</span> 0h';
                                                } ?>
                                            </td>
                                            <td>
                                                <a href="index.php?a=1&b=2&iduser=<?php echo $utilisateurRecherche->getId() ?>"><button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" title="fiche adh&eacute;rent"><i class="fa fa-edit"></i></button></a>
                                                &nbsp;<a href="index.php?a=6&iduser=<?php echo $utilisateurRecherche->getId() ?>"><button type="button" class="btn bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></button></a>
                                                <?php
                                                if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                                                    ?>
                                                    &nbsp;<a href="index.php?a=5&b=6&iduser=<?php echo $utilisateurRecherche->getId() ?>"><button type="button" class="btn bg-primary btn-sm" data-toggle="tooltip" title="Autres inscriptions"><i class="fa fa-keyboard-o"></i></button></a>
                                                    <?php
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                } ?>
                                </tbody>
                            </table>
                        </div><!-- .box-body -->
                    </div><!-- .box -->
                    <?php
            }
        } else {
            // si pas de recherche alors affichage classique
                switch ($adh) {// on recupere le type de membre a afficher
                    default:
                    case 1:
                        $titleAdh = "Adh&eacute;rents actifs";
                        $typeAdh = 1;
                        $other = 'inactifs';
                        $numOther = 2;
                        $othera = 'archiv&eacute;s';
                        $numOthera = 6;
                        // $_SESSION['page']=1;
                        break;

                    case 2:
                        $titleAdh = "Adh&eacute;rents inactifs";
                        $typeAdh = 2;
                        $other = 'actifs';
                        $numOther = 1;
                        $othera = 'archiv&eacute;s';
                        $numOthera = 6;
                        // $_SESSION['page']=1;
                        break;
                    case 6:
                        $titleAdh = "Adh&eacute;rents archiv&eacute;s";
                        $typeAdh = 6;
                        $other = 'actifs';
                        $numOther = 1;
                        $othera = 'inactifs';
                        $numOthera = 2;
                        // $_SESSION['page']=1;
                        break;
                }

            //utilisation des utilisateurs par type actifs/inactifs
            $utilisateurs = Utilisateur::getUtilisateursByStatut($typeAdh);
            $nbUtilisateurs = count($utilisateurs);

            if ($utilisateurs == null or $nbUtilisateurs == 0) {
                ?>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">";
                            <?php echo getError(1); ?>

                        </div>
                        <div class="col-xs-6">
                            <div class="alert alert-info alert-dismissable">
                                <i class="fa fa-info"></i>
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                &nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1" >Cr&eacute;er un nouvel utilisateur ?</a>
                            </div>
                        </div>
                    </div>
                    <?php
            } else { // affichage du resultat
                //$nb  = mysqli_num_rows($result);
                // count total number of appropriate listings:
                // $tcount = mysqli_num_rows($result);
                $tcount = $nbUtilisateurs;

                $rpp = 25; // results per page
                // count number of pages:
                $tpages = ($tcount) ? ceil($tcount / $rpp) : 20;
                //debug($tpages);
                /// AJOUT PAGINATION
                $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
                $adjacents = isset($_GET['adjacents']) ? intval($_GET['adjacents']) : 0;
                if ($page <= 0) {
                    $page = 1;
                }
                if ($adjacents <= 0) {
                    $adjacents = 4;
                }
                $reload = $_SERVER['PHP_SELF'] . "?a=1&adh=" . $typeAdh . "&tpages=" . $tpages . "&amp;adjacents=" . $adjacents;
                /// Fin pagination

                if ($tcount > 0) {
                    ?>
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title"><?php echo $titleAdh ?> : <?php echo count(Utilisateur::getUtilisateursByStatut($typeAdh)) ?>/<?php echo count(Utilisateur::getUtilisateurs()) ?>
                                    <?php
                                    if ( count(Utilisateur::getUtilisateursByStatut(2)) > 0) {
                                        echo "&nbsp;(<a href=\"index.php?a=1&adh=" . $numOther . "\">afficher les " . $other . " </a>)";
                                    } else {
                                        echo "";
                                    }
                                    //ajout des archivés
                                    if (count(Utilisateur::getUtilisateursByStatut(6)) > 0) {
                                        echo "&nbsp;(<a href=\"index.php?a=1&adh=" . $numOthera . "\">afficher les " . $othera . " </a>)";
                                    } else {
                                        echo "";
                                    } 
                                    ?>
                                </h3>

                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  href="index.php?a=1&b=1"  class="btn btn-default"  data-toggle="tooltip" title="Ajouter un adh&eacute;rent"><i class="fa fa-plus"></i></a>
                                &nbsp;&nbsp; <a href="index.php?a=1&b=3" class="btn btn-default"  data-toggle="tooltip" title="Voir les derniers inscrits"><i class="fa fa-users"></i></a>

                                <div class="box-tools">
                                    <div class="input-group">
                                        <form method="post" action="index.php?a=1">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="term" class="form-control pull-right" style="width: 200px;" placeholder="Entrez un nom ou un pr&eacute;nom">
                                                <span class="input-group-btn"><button type="submit" value="Rechercher"  class="btn btn-flat"><i class="fa fa-search"></i></button></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!--   <div class="box-tools pull-right"><?php echo paginate_two($reload, $page, $tpages, $adjacents); ?> </div>-->
                            </div><!-- .box-header -->



                            <div class="box-body table-responsive">
                                <?php
                                if ($adh == 2) {
                                    echo "<form role=\"form\" method=\"POST\">";
                                } ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Nom</th>
                                            <th>Pr&eacute;nom</th>
                                            <th>Nom d'utilisateur</th>
                                            <th>Age</th>
                                            <th>Derniere visite (r&eacute;sa)</th>
                                            <th>Adh&eacute;sion  <span class="badge bg-primary"  data-toggle="tooltip" title="Vert = en cours, Jaune = adh&eacute;sion &agrave; renouveller dans la semaine"><i class="fa fa-info"></i></th>
                                            <th>
                                                <?php
                                                if ($adh == 1 or $adh == '') {
                                                    echo 'Forfait temps';
                                                } elseif ($adh == 2) {
                                                    ?>
                                                    <button type="submit" name="archivage" class="btn bg-red btn-sm" data-toggle="tooltip" title="Archiver pour statistique" OnClick="return confirm(\'Veuillez confirmer le changement de statut de ces adh&eacute;rents !\');"><i class="fa fa-archive"></i></button>
                                                    <?php
                                                } else {
                                                    echo '';   // ????
                                                } ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $count = 0;
                    $i = ($page - 1) * $rpp;
                    while (($count < $rpp) && ($i < $tcount)) {
                        $utilisateur = $utilisateurs[$i];
                        $age = $utilisateur->getAge();
                        //ADHESION
                        $tarif = Tarif::getTarifById($utilisateur->getIdTarifAdhesion());
                        $adhesion = $tarif != null ? $tarif->getNom() : '';
                        $aujourdhui = date_create(date('Y-m-d'));
                        $daterenouvellement = date_create($utilisateur->getDateRenouvellement());
                        //$interval = date_diff($aujourdhui,$daterenouvellement);
                        //debug($interval->format('%R%a'));
                        if ($utilisateur->getStatut() == 1) {
                            if ($daterenouvellement <= $aujourdhui) {
                                $classadh = 'label label-warning';
                            } elseif ($daterenouvellement > $aujourdhui) {
                                $classadh = 'label label-success';
                            }
                        } elseif ($utilisateur->getStatut() == 2) {
                            $classadh = 'label label-danger';
                        }

                        //TARIF CONSULTATION
                        $forfaitConsultation = $utilisateur->getForfaitConsultation();

                        if ($forfaitConsultation != null) {
                            $min = $tab_unite_temps_affectation[$forfaitConsultation->getUniteConsultation()];
                            $tarifreferencetemps = $forfaitConsultation->getDureeConsultation() * $min;

                            $restant = $utilisateur->getTempsRestant();
                            $rapport = round(($restant / $tarifreferencetemps) * 100);
                        }
                        //dernière reservation
                        $lasteresa = $utilisateur->getLastResa();

                        if ($lasteresa == null) {
                            $lasteresa = "NC";
                        }
                        //debug($lasteresa); ?>
                                        <tr>
                                            <td>
                                                <a href="index.php?a=1&b=2&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-purple btn-sm" data-toggle="tooltip" title="Fiche adh&eacute;rent"><i class="fa fa-edit"></i></a>
                                                <a href="index.php?a=6&iduser=<?php echo $utilisateur->getId(); ?>" class="btn  bg-yellow btn-sm"  data-toggle="tooltip" title="Abonnements"><i class="ion ion-bag"></i></a>                    
                                                <a href="index.php?a=9&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-maroon btn-sm"  data-toggle="tooltip" title="Consultation internet"><i class="fa fa-globe"></i></a>
                                                <a href="index.php?a=21&b=1&iduser=<?php echo $utilisateur->getId(); ?>" class="btn bg-navy btn-sm"  data-toggle="tooltip" title="Compte d'impression"><i class="fa fa-print"></i></a>
                                                <?php
                                                if (($utilisateur->getNBAteliersEtSessionsInscrit() + $utilisateur->getNBAteliersEtSessionsPresent()) > 0) {
                                                    ?>
                                                    <a href="index.php?a=5&b=6&iduser=<?php echo $utilisateur->getId() ?>" class="btn bg-primary btn-sm" data-toggle="tooltip" title="Inscriptions Ateliers"><i class="fa fa-keyboard-o"></i></a>
                                                    <?php
                                                } ?>

                                            </td>
                                            <td><?php echo htmlentities($utilisateur->getNom()) ?></td>
                                            <td><?php echo htmlentities($utilisateur->getPrenom()) ?></td>
                                            <td><?php echo htmlentities($utilisateur->getLogin()) ?></td>
                                            <td><?php echo $age ?> ans</td>
                                            <td><?php echo $lasteresa == "NC" ? "Inconnu" : getDayfr($lasteresa->getDateResa()); ?></td>
                                            <td><span class="<?php echo $classadh ?>"><?php echo $adhesion ?></span></td>
                                            <td>
                                                <?php
                                                //statut actif
                                                if ($utilisateur->getStatut() == 1) {
                                                    if ($forfaitConsultation != null) {
                                                        ?>
                                                        <span class="badge bg-blue"><?php echo htmlentities($forfaitConsultation->getNom()) ?></span> <?php echo getTime($restant) ?>
                                                        <div class="progress">
                                                            <div class="progress progress-sm active">
                                                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $rapport ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo "width:" . $rapport . "%"; ?>"></div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        echo '<span class="badge bg-red">NC</span> 0h';
                                                    }
                                                } elseif ($utilisateur->getStatut() == 2) { //passer du statut inactif au statut archivé
                                                    echo '<input type="checkbox" name="archiv_[]" class="minimal" value=' . $utilisateur->getId() . '>';
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                        $count++;
                    } ?>
                                </table>
                                <?php
                                if ($adh == 2) {
                                    echo "</form>";
                                } ?> 
                                <br>
                                <?php
                                //if ($_SESSION['nbpager']!=0)

                                echo '<div class="box-footer clearfix">';
                    echo paginate_two($reload, $page, $tpages, $adjacents);
                    echo '</div>';
                }
            }
        }
                    ?>
                </div><!-- .box-body -->
            </div><!-- .box -->
        </div><!-- .col-xs-12 -->
    </div><!-- .row -->
