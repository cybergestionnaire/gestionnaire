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

 
*/

/*
   
Formulaire de validation de présence aux ateliers
renvoie le nombre d'inscrits, le nombre de présents et l'id des présents (pour les stats personnelles)

*/
    // error_log("GET : " . print_r($_GET, true));
    // error_log("POST : " . print_r($_POST, true));
    
    require_once("include/class/Session.class.php");
    require_once("include/class/SessionDate.class.php");
    include_once("include/class/StatAtelierSession.class.php");

    $idsession  = isset($_GET["idsession"]) ? $_GET["idsession"] : '';
    if ($idsession == '') {
        $idsession  = isset($_POST["idsession"]) ? $_POST["idsession"] : '';
    }
    $act        = isset($_GET["act"]) ? $_GET["act"] : '';
    $iddate     = isset($_GET["dateid"]) ? $_GET["dateid"] : '';
    if ($iddate == '') {
        $iddate  = isset($_POST["dateid"]) ? $_POST["dateid"] : '';
    }
    $present    = isset($_POST["present"]) ? $_POST["present"] : '';

    $session       = Session::getSessionById($idsession);
    $sujet         = $session->getSessionSujet();
    $dateSession   = SessionDate::getSessionDateById($iddate);
    
    // // recupération des données de la session
    // $row = getSession($idsession);
    // //TITRE DE LA SESSION + DETAIL
    // $nomsession = getNomSession($row["nom_session"]);
    // //Nombre de places
    // $nombre_inscrit = countPlaceSession($idsession,0);
    // //date de la session
    // $date_session = date('Y-m-d', strtotime(getDatebyNumero($iddate)));
    // //animateur
    // $anim = $row["id_anim"];

    // modification des présences depuis les archives
    if ($act == 1) {
        $statutdatesession = 1;
        $action = "index.php?a=32&act=1";
    }
    else {
        $statutdatesession = 0;
        $action = "index.php?a=32&act=0";
    }
    
    ?> 
    <!-- DETAIL DE La session-->
<div class="row">
    <div class="col-lg-5">
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title"><?php echo htmlentities($sujet->getTitre()); ?></h3></div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Date</dt><dd> <?php echo getDatefr($dateSession->getDate());?> </dd>
                    <dt>Nombre d'inscrits</dt><dd><?php echo $session->getNbUtilisateursInscritsOuPresents() ;?> (sur <?php echo $session->getNbPlaces();?> places)</dd>
                    <dt>Description</dt><dd> <?php echo $sujet->getDetail(); ?></dd>
                </dl>
            </div>
    
            <div class="box-footer">
                <a href="index.php?a=30&b=1&idsession=<?php echo $idsession ; ?>"><button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-left"></i> Retour aux inscriptions</button></a>
            </div>
        </div><!-- .box -->
    </div><!-- .col-lg-5 -->
    <!-- Fin DETAIL DE L'ATELIER-->

    <div class="col-lg-7">
<?php
    
    // liste des user inscrit a un atelier
    if ($act == 0) {
        $utilisateursInscrits = $dateSession->getUtilisateursInscrits();
        $utilisateursPresents = $dateSession->getUtilisateursPresents();
        // $result2 = getSessionUser($idsession,$statutdatesession); 
    }
    elseif ($act == 1) {
        //$result2 = getSessionValidpresences($idsession,$iddate);
    }
    // $nb = mysqli_num_rows($result2) ;
    $nb = count ($utilisateursInscrits) + count ($utilisateursPresents);
    
    if ($nb>0)
    {       

?>

        <form method="post" action="<?php echo $action; ?>">
            <div class="box box-success">
                <div class="box-header"><h3 class="box-title">Liste des participants &agrave; cet session</h3></div>
                <div class="box-body">
                    <table class="table"> 
                        <thead>
                            <tr> 
                                <th>Nom, prenom</th>
                                <th>Pr&eacute;sence</th>
                                <th>
                                    <input type="hidden" value="<?php echo $idsession; ?>" name="idsession">
                                    <input type="hidden" value="<?php echo $iddate; ?>" name="dateid">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
<?php
         
        // for ($i = 1 ; $i <= $nb; $i++) {
            // $array = mysqli_fetch_array($result2) ;
        foreach ($utilisateursInscrits as $utilisateur) {
?>
                            <tr>
                                <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                                <td><input type="checkbox" name="present_[]" value="<?php echo $utilisateur->getId(); ?>" ></td>
                                <td></td>
                            </tr>
<?php
        }
?>
<?php
        foreach ($utilisateursPresents as $utilisateur) {
?>
                            <tr>
                                <td><?php echo htmlentities($utilisateur->getNom() . " " . $utilisateur->getPrenom()) ;?></td>
                                <td><input type="checkbox" name="present_[]" value="<?php echo $utilisateur->getId(); ?>" checked></td>
                                <td></td>
                            </tr>
<?php
        }
?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer"><input type="submit" class="btn bg-olive"></div>
            </div>
        </form>
<?php
    }
 
?>

    </div><!-- .col-lg-7 -->
</div><!-- .row -->