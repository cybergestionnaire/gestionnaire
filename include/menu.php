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
 template Copyright &copy; 2011 Website Admin Theme by <a href="http://www.medialoot.com">MediaLoot</a>

*/

// menu niveau 1
?>
   
<section class="sidebar"><!-- Sidebar user panel -->
   
     
<?php
    //photo de profil
    if ($_SESSION["status"] == 3 OR  $_SESSION["status"] == 4) {
        $avatar = "img/avatar/" . $utilisateur->getAvatar();
    } else {
        $nomSE    = str_replace(CHR(32), "", $utilisateur->getNom());
        $prenomSE = str_replace(CHR(32), "", $utilisateur->getPrenom());
        $filenamephoto = "img/photos_profil/" . trim($nomSE) . "_" . trim($prenomSE) . ".jpg" ;
        if (file_exists($filenamephoto)) {
            $avatar = $filenamephoto;
        } else {
            if ($utilisateur->getSexe() == 'H') {
                $avatar = "img/avatar/male.png";
            } else {
                $avatar = "img/avatar/female.png";
            }
        }
    }
?>
    <div class="user-panel">
        <div class="pull-left image"> <img src="<?php echo $avatar; ?>" class="img-circle" alt="" />  </div>            
        <div class="pull-left info">
            <p>Salut, <?php echo $utilisateur->getPrenom(); ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Connect&eacute;</a>
        </div>
    </div>
 
    <!-- module de recherche rapide -->
<?php if ($_SESSION['status'] == 3 OR $_SESSION['status'] == 4) { ?>
    <form  method="post" action="index.php?a=1" class="sidebar-form">
        <div class="input-group">
            <input type="text" name="term" value="Recherche Adh&eacute;rent" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;" class="form-control">
            <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </form>
        
        
<?php
    }
    
    //include ("include/calendrier.php");
    $m = isset($_GET["m"]) ? $_GET["m"] : '';
    
    if ($_SESSION['status'] == 1) {
        // menu utilisateur standard
?>
    <ul class="sidebar-menu">
        <li class="<?php if ($m == "") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php"><i class="fa fa-home"></i> <span>Tableau de bord</span></a></li>
        <li class="<?php if ($m == "2") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php?m=2"><i class="fa fa-edit" ></i><span>Mon compte</span></a></li>
        <!--<li><a href="index.php?m=5"><i class="fa fa-bookmark-o" ></i><span>Mes liens favoris</span></a></li>-->
        <li class="<?php if ($m == "20") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php?m=20"><i class="fa fa-print" ></i><span>Mes impressions</span></a></li>
        <li class="<?php if ($m == "6") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php?m=6"><i class="fa fa-graduation-cap" ></i><span>Mes formations</span></a></li>
        <li class="<?php if ($m == "3") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php?m=3"><i class="fa fa-calendar"></i><span>Mes r&eacute;servations</span></a></li>
        <!--<li><a href="index.php?m=20"><span>Mes impressions</span></a></li>-->
    </ul>
<?php
    }
    

    if ($_SESSION['status'] == 3 || $_SESSION['status'] == 4) {
        // menu animateur/administrateur
?>
    <ul class="sidebar-menu">
        <li class="<?php if ($a == "") { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php"><i class="fa fa-home"></i><span>Accueil</span></a></li>
        <li class="treeview"><a href="index.php?m=3&month=<?php echo date('n'); ?>&year=<?php echo date('Y'); ?>&jour=<?php echo date('d') ; ?>&mois=<?php echo date('n'); ?>&annee=<?php echo date('Y'); ?>"><i class="fa fa-calendar"></i><span>Réservations</span></a></li>
<?php 
        $consolemode = getConfigConsole($_SESSION["idepn"], "activer_console");
        if ($consolemode == 1) {
            if ($a == 45) { $class = "active"; } else { $class = "treeview" ;}
            echo '<li class="' . $class . '"><a href="index.php?a=45"><i class="fa fa-dashboard"></i><span>Console</span></a></li>';
        }
?>
<?php   if ($_SESSION['status'] == 4) { ?>
        <li class="<?php if ($a == 41) { echo "active"; } else { echo "treeview" ;} ?>"><a href="index.php?a=41"><i class="fa fa-gears"></i> <span>Configuration</span> </a></li>
<?php   } ?>        
        <li class="<?php if ($a == 1 OR $a == 24 OR $a == 23) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-group"></i> <span>Adh&eacute;rents</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="<?php if ( $a == 1 && $b != 1) { echo "active"; } ?>"><a href="index.php?a=1"><i class="fa fa-angle-double-right"></i>Liste des Adh&eacute;rents</a></li>
                <li class="<?php if ( $a == 1 && $b == 1) { echo "active"; } ?>"><a href="index.php?a=1&b=1"><i class="fa fa-angle-double-right"></i>Cr&eacute;er Adh&eacute;rent</a></li>
                <li class="<?php if ( $a == 24) { echo "active"; } ?>"><a href="index.php?a=24"><i class="fa fa-angle-double-right"></i>Pr&eacute;inscriptions</a></li>
<?php   if ($_SESSION['status'] == 4) { ?>
                <li class="<?php if ( $a == 23) { echo "active"; } ?>"><a href="index.php?a=23"><i class="fa fa-angle-double-right"></i>Gestion des Admin/Anim</a></li>
<?php   } ?>        
            </ul>
        </li>
        
        <li class="<?php if ($a == 21 OR $a == 8) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-money"></i><span>Transactions</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="<?php if ( $a == 21) { echo "active"; } ?>"><a href="index.php?a=21"><i class="fa fa-angle-double-right"></i>Impressions</a></li>
                <li class="<?php if ( $a == 8) { echo "active"; } ?>"><a href="index.php?a=8"><i class="fa fa-angle-double-right"></i>Forfaits Ateliers</a></li>
            </ul>
        </li>
        
        <li class="<?php if ($a == 11 OR $a == 12  OR $a == 18 OR $a == 7 OR $a == 10 or $a == 15 OR $a == 17) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-keyboard-o"></i> <span>Ateliers</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="<?php if ($a == 7 OR $a == 10 or $a == 15 OR $a == 17) { echo "active"; } else { echo "treeview" ;} ?>">
                    <a href="#"><i class="fa fa-angle-double-right"></i> Param&egrave;tres <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="<?php if ( $a == 7) { echo "active"; } ?>"><a href="index.php?a=7"><i class="fa fa-angle-double-right"></i> Cat&eacute;gories / Niveaux</a></li>
                        <li class="<?php if ( $a == 17) { echo "active"; } ?>"><a href="index.php?a=17"><i class="fa fa-angle-double-right"></i> Sujets</a></li>
                 
                    </ul>
                </li>
                <li class="<?php if ( $a == 11) { echo "active"; } ?>"><a href="index.php?a=11"><i class="fa fa-angle-double-right"></i>Programmation en cours</a></li>
                <li class="<?php if ( $a == 12) { echo "active"; } ?>"><a href="index.php?a=12"><i class="fa fa-angle-double-right"></i>Planifier un atelier</a></li>
                <li class="<?php if ( $a == 18) { echo "active"; } ?>"><a href="index.php?a=18"><i class="fa fa-angle-double-right"></i>Archives</a></li>
                <!-- <li class="icn_mail"><a href="index.php?a=33"> Gestion des courriers</a></li>-->
            </ul>
        </li>
        
        <li class="<?php if ($a == 31 OR $a == 37 OR $a == 29 or $a == 34 OR $a == 35 OR $a == 36) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-ticket"></i> <span>Sessions</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="<?php if ( $a == 29 OR $a == 34 OR $a == 35) { echo "active treeview"; } else { echo "treeview" ;} ?>">
                    <a href="#"><i class="fa fa-angle-double-right"></i>Param&egrave;tres <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="<?php if ( $a == 29 OR $a == 34 OR $a == 35) { echo "active"; } ?>"><a href="index.php?a=29"><i class="fa fa-angle-double-right"></i>sujets</a></li>
<!--                        <li><a href="index.php?a=35"><i class="fa fa-angle-double-right"></i> Modifier un sujet</a></li>-->
                    </ul>
                </li>
                <li class="<?php if ( $a == 31) { echo "active"; } ?>"><a href="index.php?a=31&m=1"><i class="fa fa-angle-double-right"></i>Planifier une session</a></li>
                <li class="<?php if ( $a == 37) { echo "active"; } ?>"><a href="index.php?a=37"><i class="fa fa-angle-double-right"></i>Sessions en cours</a></li>
                <li class="<?php if ( $a == 36) { echo "active"; } ?>"><a href="index.php?a=36"><i class="fa fa-angle-double-right"></i>Archives</a></li>
            </ul>
        </li>
        
        <li class="<?php if ($a == 5) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-bar-chart-o"></i> <span>Statistiques</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="<?php if ( $a == 5 && $b == 1) { echo "active"; } ?>"><a href="index.php?a=5&b=1"><i class="fa fa-angle-double-right"></i>Adh&eacute;rents</a></li>
                <li class="<?php if ( $a == 5 && $b == 2) { echo "active"; } ?>"><a href="index.php?a=5&b=2"><i class="fa fa-angle-double-right"></i>R&eacute;servations</a></li>
                <li class="<?php if ( $a == 5 && $b == 3) { echo "active"; } ?>"><a href="index.php?a=5&b=3"><i class="fa fa-angle-double-right"></i>Impressions</a></li>
                <li class="<?php if ( $a == 5 && $b == 5) { echo "active"; } ?>"><a href="index.php?a=5&b=5"><i class="fa fa-angle-double-right"></i>Sessions</a></li>
                <li class="<?php if ( $a == 5 && $b == 4) { echo "active"; } ?>"><a href="index.php?a=5&b=4"><i class="fa fa-angle-double-right"></i>Ateliers</a></li>
            </ul>
        </li>
        
        <!-- Gestion -->    
        <li class="<?php if ($a == 3 OR $a == 4) { echo "active"; } else { echo "treeview" ;} ?>">
            <a href="#"><i class="fa fa-gear"></i> <span>Gestion de l'espace</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">

                <li ><a href="index.php?a=3"><i class="fa fa-angle-double-right"></i>Interventions</a></li>
                <li ><a href="index.php?a=4"><i class="fa fa-angle-double-right"></i>Br&egrave;ves</a></li>
                <li ><a href="index.php?a=52"><i class="fa fa-angle-double-right"></i>Courriers</a></li>
                <li ><a href="index.php?a=10"><i class="fa fa-angle-double-right"></i>Liens</a></li>
            </ul>
        <!-- Fin gestion--> 
        </li>
        <li class=""><a href="doc/index.php"><i class="fa fa-book"></i> <span>Aide</span> </a></li>
    </ul>
            
<?php
    }
?>
</section>

      
