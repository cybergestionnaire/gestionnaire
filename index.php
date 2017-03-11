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

    Original work : Cybermin / 2006-2008 Namont Nicolas
*/

// Desactivation du rapport d'erreur
//error_reporting(0);
// Affichage des erreurs pour le debuggage
error_reporting(E_ALL);


//demarrage de la session
session_start();

//Recuperation des variable du menu
$m = isset($_GET['m']) ? $_GET['m'] : '';
$a = isset($_GET['a']) ? $_GET['a'] : '';
$b = isset($_GET['b']) ? $_GET['b'] : '';

// Recuperation des variable de fonctionnement
$error  = isset($_GET['error']) ? $_GET['error'] : '';
$logout = isset($_GET['logout']) ? $_GET['logout'] : '';

// Fichier inclus
include("include/fonction.php");
include("include/fonction2.php");
include("include/conf.php");

// deconnexion
if ($logout != '') {
    //log des donnees de navigation pour la deconnexion
    enterConnexionstatus($_SESSION['iduser'],date('Y-m-d H:i:s'),2,0,0,0);
    unset($_SESSION["login"]);
    unset($_SESSION["status"]);
    unset($_SESSION["iduser"]);
    unset($_SESSION["idepn"]);
}



 //Autentification
if (FALSE == isset($_SESSION["login"])) {
    include ("login.php") ;
} else {
    // Acces autorise
    require_once("include/class/Espace.class.php");
    require_once("include/class/Config.class.php");
    require_once("include/class/Utilisateur.class.php");

    //Variables de l'epn
    $espace      = Espace::getEspacebyId(intval($_SESSION["idepn"]));
    $utilisateur = Utilisateur::getUtilisateurById(intval($_SESSION["iduser"]));
    $config      = Config::getConfig(intval($_SESSION["idepn"]));

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title><?php echo $titre; ?> -- <?php echo $espace->getNom(); ?></title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
    <!-- FontAwesome 4.3.0 -->
    <link  href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link href="template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="template/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="template/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link href="template/plugins/select2/select2.min.css" type="text/css" rel="stylesheet" >
    <!-- iCheck -->
    <link href="template/plugins/iCheck/square/blue.css" type="text/css" rel="stylesheet" >
        
    <!-- calendar style -->
    <link href="template/style_calendar.css" rel="stylesheet" type="text/css" />
    <!--FONT SPECIFIC -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='rome-master/dist/rome.css' rel='stylesheet' type='text/css' />

    <!-- ajout console -->
    <script type="text/javascript" src="js/fonction.js"></script>
    <script src="js/ckeditor/ckeditor.js"></script>
    <style>
        DIV.table {
            display:table;
        }
        FORM.tr, DIV.tr {
            display:table-row;
        }
        SPAN.td {
            display:table-cell;
            padding: 5px;
            vertical-align: middle;
        }
    </style>
</head>


<body class="hold-transition sidebar-mini skin-<?php echo $espace->getCouleur(); ?>">
    <div class="wrapper">
        <header class="main-header">
<?php
    if($_SESSION["status"] == "3" OR $_SESSION["status"] == "4") {
?>
            <!-- section pour les administrateurs -->
            <a href="index.php?m=1" class="logo"><img src="img/logo/<?php echo $espace->getLogo(); ?>" class="logo" alt="Logo"></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Navigation</span>
                </a>
                <span class="navbar-brand"><?php echo $espace->getNom() ; ?></span>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Notifications, preinsciptions en attente -->
                    
<?php
        //retrouve le nombre de preinscriptions en attente
        $newinscritsar = getAllUserInsc();
        $nbinscrits    = mysqli_num_rows( $newinscritsar);
        if ($nbinscrits > 0){
?>
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning"><?php echo $nbinscrits; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><?php echo $nbinscrits; ?> pr&eacute;insciption(s) en attente !</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
<?php
            for ($i = 0 ; $i < $nbinscrits ; $i++) {
                $newinscrits = mysqli_fetch_array($newinscritsar);
?>
                                        <li>
                                            <a href="index.php?a=24&b=1&iduser='<?php echo $newinscrits["id_inscription_user"]; ?>'">
                                                <i class="fa fa-users text-aqua"></i>
                                                <?php echo $newinscrits["nom_inscription_user"]; ?>&nbsp;<?php echo $newinscrits["prenom_inscription_user"] ?>&nbsp;(<?php echo $newinscrits["date_inscription_user"] ?>)
                                            </a>
                                        </li>
<?php                     
            }
?>
                                    </ul>
                                </li>
                                <li class="footer"><a href="index.php?a=24">Toutes les inscriptions en attente</a></li>
                            </ul>
                        </li>
<?php
        }
?>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ion ion-person"></i>
                                <span><?php echo $utilisateur->getPrenom(); ?>&nbsp;<?php echo $utilisateur->getNom(); ?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="img/avatar/<?php echo $utilisateur->getAvatar(); ?>" class="img-circle" alt="" />
                                    <p>
                                        <?php echo $utilisateur->getPrenom(); ?>&nbsp;<?php echo $utilisateur->getNom(); ?>
                                        <small>inscrit depuis <?php echo $utilisateur->getDateInscription(); ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="index.php?a=51&b=2&iduser=<?php echo $utilisateur->getId(); ?>" class="btn btn-default btn-flat">Profil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="lockscreen.php?iduser=<?php echo $utilisateur->getId(); ?>" class="btn btn-default btn-flat">Veille</a>
                                        <a href="index.php?logout=yes" class="btn btn-default btn-flat">D&eacute;connexion</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
<?php
    } else {
    
    ///*** section pour les utilisateurs *** ////
?>
    
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Navigation</span>
                </a>
                <!-- Sidebar toggle button-->
                <span class="navbar-brand"><?php echo getnomreseau(); ?></span>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ion ion-person"></i>
                                <span><?php echo $utilisateur->getPrenom(); ?>&nbsp;<?php echo $utilisateur->getNom(); ?><i class="caret"></i></span>
                            </a>
                           
                            <ul class="dropdown-menu">
                                <li class="user-header bg-light-blue">
                                    <p><?php echo $utilisateur->getPrenom(); ?>&nbsp;<?php echo $utilisateur->getNom(); ?><small>inscrit depuis <?php echo $utilisateur->getDateInscription(); ?></small></p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                      <div class="pull-left"><a href="#" class="btn btn-default btn-flat">Profil</a>  </div>
                                      <div class="pull-right"><a href="index.php?logout=yes" class="btn btn-default btn-flat">D&eacute;connexion</a></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- end of section utilisateur -->
<?php
    } /// Fin section utilisateur ///
?>
        </header>

        <!-- navigation par le menu a gauche -->

        <div class="main-sidebar">            
<?php
    // menu de l'utilisateur , de l'admin et de l'animateur
    include("include/menu.php");
?>  
        </div><!-- end of sidebar -->
            
        <!-- Debut du pave central des includes --> 
        <div class="content-wrapper">
         <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><?php echo $titre ; ?></h1>
                <ol class="breadcrumb">
                    <li><a href="index.php?m=1"><i class="fa fa-dashboard"></i> Accueil</a></li>
                    <li class="active"><?php echo $titre ; ?></li>
                </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
<?php
    include("include/".$inc);
?>
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
              <b>CyberGestionnaire </b>V.<?php echo $config->getname(); ?>
            </div>
            <strong><a href="index.php?a=60">Cr&eacute;dits </a></strong>
        </footer>
    </div>
    <!-- jQuery 2.1.3 -->
    <script src="template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.2 -->
    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>-->
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!--   <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>-->
    <script src="template/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="template/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
   
     
    <script src="template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
  
    <!-- datepicker -->
    <script src="template/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    
        <script src="template/plugins/select2/select2.full.min.js"></script>
    <!-- Slimscroll -->
    <script src="template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/app.min.js" type="text/javascript"></script>
         <!-- iCheck -->
         
<?php 
    if ($a == 1 OR $a == 43) { 
        "nothing";
    } else {
?>
    <script src="template/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
<?php } ?>

</body>

</html>
<?php } ?>