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


 * STEP 1
 **/
session_start() ;

if (TRUE == is_file('../connect_db.php') && TRUE == is_writable('../connect_db.php')){
    $class_connect = 'writable' ;    
}
else{
    $class_connect = 'error' ;
    $error = 'yes';
}

if (TRUE == is_dir('../sql') && TRUE == is_writable('../sql')){
    $class_save = 'writable' ;    
}
else{
    $class_save = 'error' ;
    $error = 'yes' ;
}

if ($_SERVER['REQUEST_METHOD']=='POST')
{
    if (TRUE == isset($_POST['step2']) && FALSE == isset($error) )
    {
        header('Location:step2.php') ;
    }
    else if (TRUE == isset($_POST['step2']) && TRUE == isset($error) )
    {
        $mess = 'Cette &eacute;tape ne peut &ecirc;tre valid&eacute;e car des erreurs subsistent' ;
    }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"  class="lockscreen">
<head>
    <title>Installation de Cyber-Gestionnaire</title>
   <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <script type="text/javascript" src="script.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
         <!-- Bootstrap 3.3.2 -->
    <link href="../template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="../template/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
    
</head>
 <body class="register-page"><form method="post" action="step1.php">
    <div class="register-box">
      <div class="register-logo">Installation Etape 1 / 3</div>
     
     <div class="register-box-body">   
        
      <p class="login-box-msg">
            <h4>V&eacute;rification des droits d'&eacute;criture </h4>
           Cette &eacute;tape vous permet de v&eacute;rifier que les droits sont correctement positionn&eacute;s sur les fichiers et les dossiers
            dont l'application va avoir besoin. Si le dossier ou le fichier est indiqu&eacute; avec une erreur vous devez changer
            ses propri&eacute;t&eacute;s et lui donner les droits suffisants en ecriture</p>
            <?php
            if (TRUE == isset($mess)){
                echo '<span class="error">'.$mess.'</span>' ;
            }
            ?>
            <ul>
                <li class="<?php echo $class_connect;?>">Fichier de configuration <strong>/include/connect_db.php'</strong></li>
                <li class="<?php echo $class_save;?>">Dossier de sauvegarde de la base de donn&eacute;es <strong>/sql</strong></li>
            </ul>
			 
             
	  
	   
    </div>
    <div class="box-footer"><button  type="submit" class="btn btn-flat pull-right" name="step2" value="Etape 2 "/>Etape 2&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-circle-right "></i></button>
        
       </div></form>
    </div>
    
 <!-- jQuery 2.1.3 -->
    <script src="../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="../template/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
 <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
</body>
</html>
