<?php
/*
     This file is part of Cybermin.

    Cybermin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Cybermin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Cybermin; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 2006-2008 Namont Nicolas
 
 index.php V0.1  
 cybermin 2

*/
// Desactivation du rapport d'erreur
error_reporting(0);
// Affichage des erreurs pour le debuggage
//error_reporting(E_ALL);


//demarrage de la session
session_start();

//Recuperation des variable du menu
$m=$_GET["m"];
$a=$_GET["a"];
$b=$_GET["b"];
// Recuperation des variable de fonctionnement
$error=$_GET["error"];
$logout=$_GET["logout"];

// Fichier inclus
include("include/fonction.php");
include("include/fonction2.php");
include("include/conf.php");
// deconnexion
if (FALSE !=isset($logout))
{
  
//log des donnees de navigation pour la deconnexion
	enterConnexionstatus($_SESSION['iduser'],date('Y-m-d H:i:s'),2,0,0,0);
  unset($_SESSION["login"]);
  unset($_SESSION["status"]);
  unset($_SESSION["iduser"]);
  unset($_SESSION["idepn"]);
   
}



 //Autentification
if (FALSE == isset($_SESSION["login"]))
{
  include ("login.php") ;
}

// Acces autorise
else

{


$error=$_GET["error"];

//Variables de l'epn
$epnspec=getCyberSpec($_SESSION["idepn"]);

//tableau des couleurs
$couleurArray=array(
	1=> "green",
	2=> "blue",
	3=> "yellow",
	4=> "red",
	//5=> "olive",
	6=> "purple",
	//7=> "orange",
	//8=> "maroon",
	9=> "black"
	);
$couleur=$couleurArray[$epnspec["couleur_espace"]];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title><?php echo $titre; ?> -- <?php echo $epnspec["nom_espace"] ; ?></title>
	<!--<title>Cyber-Gestionnaire V0.8</title>-->
	
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
    <!-- Morris chart -->
    <link href="template/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="template/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="template/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
	<!-- Select2 -->
    <link href="template/plugins/select2/select2.min.css" type="text/css" rel="stylesheet" >
		 <!-- iCheck -->
    <link href="template/plugins/iCheck/square/blue.css" type="text/css" rel="stylesheet" >
		
	
	<!-- calendar style -->
  <link href="template/style_calendar.css" rel="stylesheet" type="text/css" />
	<!--FONT SPECIFIC -->
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<!-- ajout console -->
	<script type="text/javascript" src="js/fonction.js"></script>
	
	<script src="js/ckeditor/ckeditor.js"></script>
	
 <script src="template/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
  <script src="template/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
 
	<link href='rome-master/dist/rome.css' rel='stylesheet' type='text/css' />
	
	
</head>


<body class="hold-transition  sidebar-mini skin-<?php echo $couleur; ?>">
<div class="wrapper">

	 <header class="main-header">
		
		
		<!-- section pour les adminsitrateurs -->
	<?php if($_SESSION["status"]=="3" OR $_SESSION["status"]=="4"){
	
			?>
			<a href="index.php?m=1" class="logo"><img src="img/logo/<?php echo $epnspec["logo_espace"]; ?>" class="logo"></a>
		<!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Navigation</span>
                  
                </a>
		 <span class="navbar-brand"><?php echo $epnspec["nom_espace"] ; ?></span>
			 <div class="navbar-custom-menu">
			
       <ul class="nav navbar-nav">
						<!-- Notifications, preinsciptions en attente -->
						
					 <?php
              //retrouve le nombre de preinscriptions en attente
              $newinscritsar=getAllUserInsc();
              $nbinscrits=mysqli_num_rows( $newinscritsar);
              if($nbinscrits>0){
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
                    for($i=0;$i<$nbinscrits;$i++){
											$newinscrits=mysqli_fetch_array($newinscritsar);
											echo '
                      <li><a href="index.php?a=24&b=1&iduser='.$newinscrits["id_inscription_user"].'"><i class="fa fa-users text-aqua"></i>
                      '.$newinscrits["nom_inscription_user"].'&nbsp;'.$newinscrits["prenom_inscription_user"].'&nbsp;('.$newinscrits["date_inscription_user"].')</a></li>';
                     
                     }
                     ?>
                      
                    </ul>
                  </li>
                  <li class="footer"><a href="index.php?a=24">Toutes les inscriptions en attente</a></li>
                </ul>
              </li>
              <?php } ?>
              
							

				<li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ion ion-person"></i>
                                <span><?php $row = getUser($_SESSION["iduser"]); ?>
								<?php echo $row["prenom_user"]; ?>&nbsp;<?php echo $row["nom_user"]; ?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
				<?php
				
				$rowa = getAvatar($_SESSION["iduser"]);
				$avatar=$rowa["anim_avatar"];
				
				?>
                                    <img src="img/avatar/<?php echo $avatar; ?>" class="img-circle" alt="" />
				 
                                    <p><?php echo $row["prenom_user"]; ?>&nbsp;<?php echo $row["nom_user"]; ?>
                                        <small>inscrit depuis <?php echo $row["date_insc_user"]; ?></small>
                                    </p>
                                </li>
								<!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="index.php?a=51&b=2&iduser=<?php echo $_SESSION["iduser"]; ?>" class="btn btn-default btn-flat">Profil</a>
                                    </div>
                                    <div class="pull-right">
					<a href="lockscreen.php?iduser=<?php echo $_SESSION["iduser"]; ?>" class="btn btn-default btn-flat">Veille</a>
                                        <a href="index.php?logout=yes" class="btn btn-default btn-flat">D&eacute;connexion</a>
                                    </div>
                                </li>
                            </ul>
						</li>
						</ul>
					</div>
				</nav>
		</header>
		
	<?php
	}else{
	
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
			<ul class="nav navbar-nav"><li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="ion ion-person"></i>
           <span><?php $row = getUser($_SESSION["iduser"]); ?>
								<?php echo $row["prenom_user"]; ?>&nbsp;<?php echo $row["nom_user"]; ?><i class="caret"></i></span> </a>
                           
         <ul class="dropdown-menu"><li class="user-header bg-light-blue"><p><?php echo $row["prenom_user"]; ?>&nbsp;<?php echo $row["nom_user"]; ?><small>inscrit depuis <?php echo $row["date_insc_user"]; ?></small></p> </li>
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
	</header>
	<!-- end of section utilisateur -->

	<?php
	} /// Fin section utilisateur ///
	?>

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
          <b>CyberGestionnaire </b>V.<?php echo getVersion($_SESSION["idepn"]); ?>
        </div>
        <strong><a href="index.php?a=60">Cr&eacute;dits </strong>
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
   
	 
    <script src="template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
  
    <!-- datepicker -->
    <script src="template/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="template/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    
		<script src="template/plugins/select2/select2.full.min.js"></script>
    <!-- Slimscroll -->
    <script src="template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="template/dist/js/app.min.js" type="text/javascript"></script>
		 <!-- iCheck -->
		 
		<?php if($a==1 OR $a==43){ 
			nothing;
		}else{
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
	<?php	} ?>

</body>

</html>
	<?php
	}
	?>