<?php
// Désactivation du rapport d'erreur
error_reporting(0);
// Affichage des erreurs pour le debuggage
//error_reporting(E_ALL);


//demarrage de la session
session_start();
// Fichier inclus
include("include/fonction.php");
include("include/fonction2.php");
include("include/conf.php");


$iduser=$_GET["iduser"];
$row = getUser($iduser); 
$rowa = getAvatar($iduser);
$avatar=$rowa["anim_avatar"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta charset="UTF-8">
  
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo"><?php echo getconfigname(); ?>
      </div>
      <!-- User name -->
      <div class="lockscreen-name"><?php echo $row["prenom_user"]; ?>&nbsp;<?php echo $row["nom_user"]; ?></div>

      <!-- START LOCK SCREEN ITEM -->
      <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
          <img src="img/avatar/<?php echo $avatar; ?>"/>
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials"  method="post" action="post_login.php">
	
	  <?php
		  if ($error !="")
		  {
			  echo "Acces refus&eacute; veuillez vous identifier";
		  }
	  ?>
          <div class="input-group">
            <input type="password" class="form-control" placeholder="password" name="pass" required />
            <div class="input-group-btn">
              <button class="btn" ><i class="fa fa-arrow-right text-muted" type="submit" name="submit"></i></button>
            </div>
          </div>
        </form><!-- /.lockscreen credentials -->

      </div><!-- /.lockscreen-item -->
      <div class="help-block text-center">
        Entrez votre mot de passe pour r&eacute;activer votre session
      </div>
      <div class='text-center'>
        <a href="index.php?logout=yes">Ou connexion pour un nouvel utilisateur</a>
      </div>
      <!--<div class='lockscreen-footer text-center'>
        Copyright &copy; 2014-2015 <b><a href="http://almsaeedstudio.com" class='text-black'>Almsaeed Studio</a></b><br>
        All rights reserved
      </div>-->
    </div><!-- /.center -->

    <!-- jQuery 2.1.3 -->
    <script src="template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>