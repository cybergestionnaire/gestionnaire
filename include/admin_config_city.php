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
    2016 Tariel Christophe
 

  include/admin_city.php V0.1
*/

// Gestion des villes
//

require_once("include/class/Ville.class.php");

// traitement des post
$act    = isset($_GET['act'])    ? $_GET['act']    : '';
$idcity = isset($_GET['idcity']) ? $_GET['idcity'] : '';

if (isset($_GET["act"])) {

    switch ($act) {
        case 1: // creation
            $nom      = $_POST["newcity"];
            $codepost = $_POST["newcodepost"];
            $pays     = $_POST["newpays"];
           
            if (!$nom || !$codepost || !$pays) {
               $mess = getError(4);
            } else {   
                $ville = Ville::creerVille($nom, $codepost, $pays);
                
                if ($ville == null) {
                    echo getError(0);
                } else {
                    //header("Location:index.php?a=41&mesno=14") ;
                    echo getError(14);
                }
            }
            break;
            
        case 2: // modification
            $nom      = $_POST["city"];
            $codepost = $_POST["codepost"];
            $pays     = $_POST["pays"];

            if (!$nom || !$codepost || !$pays) {
                $mess = getError(4);
            } else {
                $ville = Ville::getVilleById(intval($idcity));
                
                if ($ville != null) {
                        
                    if (FALSE == $ville->modifier($nom, $codepost, $pays)) {
                        echo getError(0);
                    } else {
                        // header("Location:index.php?a=41&mesno=14") ;
                        echo getError(14);
                    }
                }
            }
            break;
            
        case 3: // suppression
            $ville = Ville::getVilleById(intval($idcity));
            if ($ville != null) {
                $errno = $ville->supprimer();
                switch ($errno)
                {
                    case 0: // impossible de joindre la base
                        echo getError(0);
                        break;
                    case 1: // la liste des adherents n'est pas vide
                        echo getError(11);
                        break;
                } 
            } else {
                echo getError(0);
            }
            
            break; 
    }
}
include("include/boites/menu-parametres.php");
?>


<div class="box box-solid box-warning">
    <div class="box-header"><h3 class="box-title">Les villes de vos EPN</h3></div>
    <div class="box-body"> 
        <h4>Enregistrer une nouvelle ville</h4>
        <div class="table">
            <form class="tr" method="post" action="index.php?a=41&act=1">
                <span class="td"><input type="text" class="form-control" name="newcity" placeholder="Nom"></span>
                <span class="td"><input type="text" class="form-control" name="newcodepost" placeholder="Code Postal" maxlength="10"></span>
                <span class="td"><input type="text" name="newpays" class="form-control"  placeholder="Pays"></span>
                <span class="td"><button type="submit" class="btn btn-primary">Cr&eacute;er</button></span>
            </form>
        </div>
    </div>


<?php
$villes   = Ville::getVilles();
$nbVilles = count($villes);

if ($nbVilles > 0) { ?>

    <div class="box-body">
        <div class="table">
<?php
    foreach($villes as $ville) {
?>
            <form class="tr" action="index.php?a=41&act=2&idcity=<?php echo $ville->getId(); ?>" method="post">
                <span class="td"><input class="form-control" type="text" name="city" value="<?php echo htmlentities($ville->getNom()) ; ?>"></span>
                <span class="td"><input class="form-control" type="text" name="codepost" value="<?php echo htmlentities($ville->getCodePostal()); ?>" maxlength="10"></span>
                <span class="td"><input class="form-control" type="text" name="pays" value="<?php echo htmlentities($ville->getPays()) ; ?>"></span>
                <span class="td">
                    <button class="btn btn-success"  type="submit" value="modifier"><i class="fa fa-edit"></i></button>&nbsp;
                    <a href="index.php?a=41&act=3&idcity=<?php echo htmlentities($ville->getId()); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                </span>
                <span class="td"><a href="index.php?a=41&act=4&idcity=<?php echo htmlentities($ville->getId()); ?>#liste"><?php echo htmlentities($ville->nbAdherents()); ?> Adh.</a></span>
            </form>
<?php   
    }
?>    
        </div>
<?php
} else {
?>
        <div class="alert alert-info alert-dismissable">
            <i class="fa fa-info"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><b>Pas de villes encore !</b>
        </div>
<?php } ?>
    </div>

</div>

<?php
if ($act == 4) {
    $utilisateurs = Utilisateur::getUtilisateursByVille($idcity);

    if (FALSE == $utilisateurs)
    {
        echo getError(0);
    } else {
        $arraystatus = array(1=>"Actif", 2=>"Inactif");
        $arraycolor  = array(1=>"bg-light-blue", 2=>"bg-yellow");
?>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Liste des adh&eacute;rents inscrits dans la commune</h3>
        </div>
        <div class="box-body no-padding">
            <table class="table">
                <thead><tr><th>Nom</th><th>Pr&eacute;nom</th><th></th><th>Voir</th></tr></thead>
<?php        
        foreach ($utilisateurs as $utilisateur) {
?>
                <tr>
                    <td><?php echo htmlentities($utilisateur->getNom()) ; ?></td>
                    <td><?php echo htmlentities($utilisateur->getPrenom()) ; ?></td>
                    <td><span class="badge <?php echo htmlentities($arraycolor[$utilisateur->getStatut()]) ; ?>"><?php echo htmlentities($arraystatus[$utilisateur->getStatut()]) ; ?></span></td>
                    <td><a href="index.php?a=1&b=2&iduser=<?php echo htmlentities($utilisateur->getId()) ; ?>">Voir</a></td>
                </tr>
<?php   } ?>
            </table>
        </div>
    </div>
<?php
    }
}
?>


