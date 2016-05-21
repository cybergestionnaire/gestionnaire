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

 * STEP 2
 *
 * Configuration de la base de donn&eacute;es
 **/
    session_start() ;
    
    $db_host = 'localhost';
    $db_port = '3306';
    $db_name='';
$db_pass ='';
    $db_user ='';
    
    if ($_SERVER['REQUEST_METHOD']=='POST')
    {
        if (TRUE == isset($_POST['step3'])){
            $db_host = $_POST['db_host'];
            $db_name = $_POST['db_name'];
            $db_pass = $_POST['db_pass'];
            $db_port = $_POST['db_port'];
            $db_user = $_POST['db_user'];
            
            if (FALSE == testDb($_POST))
            {
                $mess = ' Les param&ecirc;tres fournis sont incorrects' ;    
            }
            else{
                $_SESSION['db'] = $_POST ;
                header('Location:step3.php')  ;
                exit;
            }
        }
        else if (TRUE == isset($_POST['step1'])){
            header('Location:step1.php') ;
            exit;
        }
    }
    
    
    /**
     * Test de la connexion a la base de donnÈes
          * @return bool TRUE ou FALSE
     **/
function testDb($array){
   	
$sql = new mysqli($array['db_host'], $array['db_user'],$array['db_pass'], $array['db_name']);
	if ($sql->connect_error) {
	    die('Erreur de connexion (' . $sql->connect_errno . ') '. $sql->connect_error);
		return FALSE ;
	}else{
		return TRUE;
	}
	$sql->close();
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" class="lockscreen">
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
<body class="register-page">
    <div class="register-box">
      <div class="register-logo">Installation Etape 2 / 3</div>
     
     <div class="register-box-body"> 
    <form method="post" action="step2.php">
       <p class="login-box-msg">
            <h4>Param&egrave;tres de connexion &agrave; la base de donn&eacute;es</h4>
            <p >Cette &eacute;tape vous permet de d&eacute;finir vos param&ecirc;tres de connexions &agrave; la base de donn&eacute;es .<br/>
            <br/><strong>Attention !!</strong> Ceci va &eacute;craser la base si celle ci contient d&eacute;j&agrave; des tables ou des donn&eacute;es, les donn&eacute;es
            seront perdues. Si vous utilisez une version pr&eacute;c&eacute;dente de Cybermin (V1.0) vous devez cr&eacute;er une autre base de donn&eacute;es
            ou sauvegarder et importer vos donn&eacute;es ult&eacute;rieurement. Attention &agrave; la casse (Maj ou Min) !</p>
            <?php
            if (TRUE == isset($mess)){
                echo '<span class="error">'.$mess.'</span>' ;
            }
            ?>
	 <div class="form-group has-feedback"><label>Nom ou adresse IP de l'hote <a href="#" onclick="showTip(1)">?</a></label>
			<input type="text" name="db_host" class="form-control" value="<?php echo $db_host;?>" required>  </div>
            
    <div class="form-group has-feedback"><label>Port de la base de donn&eacute;es  <a href="#" onclick="showTip(2)">?</a></label>
			<input type="text" name="db_port" class="form-control" value="<?php echo $db_port;?>"/> </div>
		
   <div class="form-group has-feedback"><input type="text" name="db_user" class="form-control" value="<?php echo $db_user;?>" placeholder="Utilisateur" required></div>
            
    <div class="form-group has-feedback"><input type="text" class="form-control" name="db_pass" value="<?php echo $db_pass;?>" placeholder="mon mot de passe" required></div>
    
     <div class="form-group has-feedback"><input type="text" class="form-control" name="db_name" value="<?php echo $db_name;?>" placeholder="nom de la base sql" required></div>
     
    <div class="row">
     <div class="col-xs-6">	<button  type="submit" class="btn bg-blue btn-block" name="step1" value="Etape 1 "/><i class="fa fa-arrow-circle-left "></i> &nbsp;Etape 1</button></div>
     
			 <div class="col-xs-6">	<button  type="submit" class="btn bg-blue btn-block"  name="step3" value="Etape 3"/> Etape 3&nbsp; <i class="fa fa-arrow-circle-right"></i></button></div>
		</div>
        
    </form>
    </div></div>
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