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

 2006 Namont Nicolas (CyberGestionnaire)
 
*/

    // admin --- Utilisateur
    //animateur et admin
    $state = array(
            3=> "Animateur",
            4=> "Administrateur",
            5=> "Animateur Inactif"
        );
        
    include("include/boites/menu-parametres.php");
?>
<div class="box box-solid box-warning">
    <div class="box-header"><h3 class="box-title">Gestion des animateurs et administrateurs</h3></div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
<?php
    if ($_SESSION['status'] == 4) {
        echo ' <li ><a href="#tab1" data-toggle="tab">Administrateur</a></li>';
    }
    else {
        echo ' <li><a href="">Administrateur</a></li>';
    }
?>
            <li class="active"><a href="#tab2" data-toggle="tab">Animateur</a></li>
            <li class="pull-left header"></li>
        </ul>
<?php

    // Les administrateurs ......
    $administrateurs = Utilisateur::getAdministrateurs();
    if ($administrateurs == null) {
        echo getError(1);
    }
    else {
        //seuls les admins ont le droit de modifier un profil admin
                //echo "<div class=soustitre>Administrateurs: ".$nb."</div>";
?>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th><th>Pr&eacute;nom</th><th>Statut</th><th>Fiches</th><th>Param&egrave;tres</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
        foreach ($administrateurs as $administrateur) {
            $testAnim = Ptestanim($administrateur->getId());
            // je refais le test pour correspondre au code initial, mais pour moi, "b" ne sert à rien dans ce cas là (pas de "b" avec "a" = 50, cf conf_admin.php)
            if ($testAnim == TRUE) {
                $btest = 2;
            }
            else {
                $btest = 1;
            }  
            $statut   = $state[$administrateur->getStatut()];
?>                    
                        <tr>
                        <tr class="<?php echo $class ?>">
                            <td><?php echo htmlentities($administrateur->getNom()) ?></td>
                            <td><?php echo htmlentities($administrateur->getPrenom()) ?></td>
                            <td><?php echo $statut ?></td>
                            <td>
<?php
            if ($_SESSION['status'] == 4) {
?>
                                <a href="index.php?a=51&b=2&type=admin&iduser=<?php echo $administrateur->getId() ?>"><button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="Fiche inscription"><i class="fa fa-user"></i></button></a>
<?php
            }
?>
                            </td>

                            <td>
<?php
            if ($_SESSION['status'] == 4) {
?>
                            <a href="index.php?a=50&b=<?php echo $btest; ?>&idanim=<?php echo $administrateur->getId(); ?>"><button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="Param&egrave;tres"><i class="fa fa-gear"></i></button></a>
<?php
            }
?>
                            </td>
                        </tr>
<?php
        }
?>
                    </tbody>
                </table>
            </div><!-- end of #tab1 -->
<?php
    }
   
   
    // Les animateurs ......
    $animateurs = Utilisateur::getAnimateurs();
    
    if ($animateurs == null) {
        echo getError(1);
    }
    else {
        $nb  = count($animateurs);
            //echo "<div class=soustitre>Animateurs: ".$nb."</div>";
?>
 
            <div class="tab-pane active" id="tab2">
                <table class="table">
                    <thead>
                        <th>Nom</th><th>Pr&eacute;nom</th><th>Statut</th><!--<th>salles attribu&eacute;es</th>--><th>Fiche</th><th>Param&egrave;tres</th>
                    </thead>
                    <tbody>
<?php
        
        foreach ($animateurs as $animateur) {
            $testAnim = Ptestanim($animateur->getId()); // ??? qu'est ce que c'est ??
            // je refais le test pour correspondre au code initial, mais pour moi, "b" ne sert à rien dans ce cas là (pas de "b" avec "a" = 50, cf conf_admin.php)
            if ($testAnim == TRUE) {
                $btest = 2;
            }
            else {
                $btest = 1;
            }            
            
            $statut   = $state[$animateur->getStatut()];

            if ($animateur->getStatut() == 5) { 
                $class = "text-muted";
            }
            else { 
                $class = "";
            }
?>
                        <tr class="<?php echo $class ?>">
                            <td><?php echo htmlentities($animateur->getNom()) ?></td>
                            <td><?php echo htmlentities($animateur->getPrenom()) ?></td>
                            <td><?php echo $statut ?></td>
                            <td>
                                <a href="index.php?a=51&b=2&type=anim&iduser=<?php echo $animateur->getId() ?>">
                                    <button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="Fiche inscription"><i class="fa fa-user"></i></button>
                                </a>
                            </td>
<?php

?>
                            <td><a href="index.php?a=50&b=<?php echo $btest; ?>&idanim=<?php echo $animateur->getId(); ?>"><button type="button" class="btn btn-primary sm" data-toggle="tooltip" title="Param&egrave;tres"><i class="fa fa-gear"></i></button></a></td>
                        </tr>
<?php
        }
?>
                    </tbody>
                </table>
            </div><!-- /.tab-pane -->

<?php
    }
?>
        </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->
    <div class="box-footer clearfix"><a href="index.php?a=51&b=1&type=anim"><input value="Cr&eacute;er un nouvel animateur/administrateur" type="submit" class="btn btn-primary"></a></div>
</div><!-- .box -->



