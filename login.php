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

  login.php V0.1
*/

$rowprinscription=getPreinsmode();

?>
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
    <!-- iCheck -->
    <link href="template/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

<body class="login-page">
	<div class="login-box">
	<div class="login-logo"><?php 
	//debug(mysqli_num_rows(getReseau()));
	if (mysqli_num_rows(getReseau())==0){
		echo "Connexion au CyberGestionnaire";
	}else{
		$rowreseau=mysqli_fetch_array(getReseau());
		$logoreseau="img/logo/".$rowsreseau['res_logo'];
		echo "<img src=".$logoreseau.">";
		
			echo $rowreseau['res_nom']; 
			
			}?></div><!-- /.login-logo -->
	
	<div class="login-box-body">
		

	
 <form method="post" action="post_login.php">
	  <?php
		
		  if ($error !="")
		  {
			  echo '<p class="login-box-msg text-red">Acces refus&eacute; mot de passe ou identifiant invalide, veuillez recommencer !</p>';
		  }else{
				echo ' <p class="login-box-msg">Identifiez-vous pour commencer une session</p>';
			}
	  ?>
	    <div class="form-group has-feedback"><input type="text" tabindex="1" placeholder="nom utilisateur" required  name="log" class="form-control">
			</div>
		
		 <div class="form-group has-feedback"><input type="password" tabindex="2"   name="pass" placeholder="Password" class="form-control"  required>
			  <span class="fa fa-lock form-control-feedback"></span>
		</div>
		 
		 <div class="row"> <div class="col-xs-8">&nbsp;</div>                                                            
             <div class="col-xs-4"><button type="submit"  class="btn btn-primary btn-block btn-flat" name="submit" value="Entrer">Entr&eacute;e</button>  
            </div></div>
			
		<?php 
		if($rowprinscription["capt_activation"]=='Y'){ ?>
			 <div class="text-center">
				<p>&nbsp;</p>
			  <p>- Pas encore inscrit ? -</p>
				<p><a href="form_preinscription.php" class="text-center">Demandez une pr&eacute;inscription !</a></p>
				</div>
			<?php } ?>
		  </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
		</form>
		
	 <!-- jQuery 2.1.3 -->
    <script src="template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
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
				

</body>
</html>

 
 
 