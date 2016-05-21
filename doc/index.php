<?php 
//fichier d'aide



?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CyberGestionnaire V.1 | Documentation</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../template/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../template/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
   <link href="style.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue fixed" data-spy="scroll" data-target="#scrollspy">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="../index.php" class="logo"><b>Retour</b></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
         
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" id="scrollspy">
			
          <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="nav sidebar-menu">
							
            <li class="header">TABLE DES MATIERES</li>
            <li><a href="#introduction"><i class='fa fa-circle-o'></i> Introduction</a></li>
        
        <li class='treeview' id='scrollspy-components'>
							<a href="#installation"><i class='fa fa-circle-o'></i> Installation</a>
             <ul>
                <li><a href='#component-prerequis'>Pré-requis</a></li>
                <li><a href='#component-basededonnee'>Paramétrage de la base de donnée</a></li>
                <li><a href='#component-gestionnaire'>Installer le CyberGestionnaire</a></li>                
               
              </ul>
            </li>
             
            
              <li class='treeview' id='scrollspy-components'>
								<a href="#configuration"><i class='fa fa-circle-o'></i> Configuration</a></li>
								<ul>
                <li><a href='#1villes'>Les villes</a></li>
                <li><a href='#2epn'>Les EPN</a></li>
                <li><a href='#3salles'>Les salles</a></li>
                <li><a href='#4time'>Les horaires</a></li>  
                <li><a href='#5tarifs'>Les tarifs</a></li>  
                <li><a href='#6postes'>Les postes</a></li>  
                <li><a href='#7usagesadh'>Les usages adhérents</a></li>  
                <li><a href='#8usagespostes'>Les usages postes</a></li>  
                <li><a href='#9anim'>Gestion des animateurs</a></li>  
                <li><a href='#10bdd'>La BDD</a></li>  
                <li><a href='#epnconnect'>EpnConnect</a></li>  
                <li><a href='#12inscriptions'>Pre-Inscriptions</a></li>  
              </ul>
              </li>
              
            <li><a href="#epnconnect"><i class='fa fa-circle-o'></i> EpnConnect</a></li>
             <li><a href="#planning"><i class='fa fa-circle-o'></i> Planning</a></li>
            <li><a href="#adherents"><i class='fa fa-circle-o'></i> Adhérents</a></li>
            <li><a href="#ateliers"><i class='fa fa-circle-o'></i> Ateliers</a></li>
            <li><a href="#sessions"><i class='fa fa-circle-o'></i> Sessions</a></li>
            <li><a href="#impressions"><i class='fa fa-circle-o'></i> Impressions</a></li>
           
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <h1>
            Documentation du CyberGestionnaire
            <small>Version actuelle 1.2</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="../index.php"><i class="fa fa-dashboard"></i> Accueil</a></li>
            <li class="active">Documentation</li>
          </ol>
        </div>

        <!-- Main content -->
        <div class="content body">
          <div class="callout callout-warning no-margin">
            <h4>Attention !</h4>
            Cette documentation n'en est qu'à son début. Elle contient pour l'instant l'essentiel pour l'utilisation courante.
          </div>
<section id='introduction'>
  <h2 class='page-header'><a href="#introduction">Introduction</a></h2>
  <p class="lead">
    <b>CyberGestionnaire</b> est un logiciel de gestion au quotidien de votre EPN. Grâce à une base de données MySQL, vos adhérents, leurs forfaits, la consultation internet, les ateliers et les sessions sont accessibles depuis un navigateur web le plus simplement possible.
  </p>
</section><!-- /#introduction -->

          <!-- ============================================================= -->

<section id='installation' data-spy="scroll" data-target="#scrollspy-component">
  <h2 class='page-header'><a href="#installation">Installation</a></h2>
  <p class="lead">
    CyberGestionnaire est téléchargeable librement, sous licence GNU GPL depuis le site de sourceforge : <a href="https://sourceforge.net/projects/cybergestionnaire" target="_self">sourceforge.net/projects/cybergestionnaire</a>
  </p>
 
 
 <h3 id='component-prerequis'>Pré-requis</h3>

<p>A télécharger au choix :</p>
<a href="http://www.wampserver.com/">WAMPP</a>   / <a href="https://www.apachefriends.org/fr/index.html">XAMPP</a>
<p class="text-justify">
NB : pour les ordinateurs windows > à 7, les deux fonctionnent parfaitement pour cybermin; pour les ordinateurs windows XP/vista/7, le serveur apache n'est pas stable et cybermin ne fonctionne pas avec Wampp, préférez donc Xampp.
</p><p class="text-justify">
Téléchargez la bonne version (64 bits ou autre !) et suivez la procédure d'installation. Si vous ne voulez pas surcharger l'odinateur, vous n'êtes pas obligé d'installer le serveur FTP ni le serveur de mail, seuls Apache et Mysql sont obligatoires.
</p><p class="text-justify">
Une fois installé et les serveurs démarrés à la fin de l'installation, veuillez à bien sécuriser le fonctionnement : donner un utilisateur/mot de passe pour le dossier XAMPP, puis un mot de passe pour votre premier utilisateur : root.
</p><p class="text-justify">
Voici des tutos : <a href="http://doc.ubuntu-fr.org/xampp">pour Linux.</a>
<br>
Pour windows, ouvrez une fenêtre de navigateur, entrez l'adresse :

<p class="text-blue"><b>localhost</b></p>
<p class="text-justify">
Le mot de passe pour le dossier Xampp que vous avez rentré à l'installation pourra vous être demandé la première fois. Une fois sur la page d'accueil, allez dans la rubrique Sécurité, puis changez/créez les mots de passe. En bas de la page vous avez un utilitaire qui vous guidera pas à pas. 
  </p>
  <img src="https://image.jimcdn.com/app/cms/image/transf/dimension=490x10000:format=png/path/sf5e5aab50816c139/image/ibaab39a9cbb29704/version/1415538947/image.png" >
  
  
    
 <h3 id='component-basededonnee'>Paramétrage de la base de donnée</h3>
 <p class="text-justify">
 <img src="https://image.jimcdn.com/app/cms/image/transf/dimension=206x1024:format=png/path/sf5e5aab50816c139/image/if401fddded5c6caf/version/1415539276/image.png" vspace="15px" hspace="15px" align="Left">
 
Votre serveur apache (web) fonctionne, et le serveur de gestion de base de donnée (mysql) aussi; pour commencer véritablement l'installation (enfin !) de cyberGestionnaire, il faut d'abord créer une base vide.
<br>
Entrez l'adresse : localhost/phpmyadmin
<br>Pour entrer, mettez l'utilisateur (root) et le mot de passe (créé l'étape d'avant !).</p>
<p class="text-justify"><img src="https://image.jimcdn.com/app/cms/image/transf/dimension=344x1024:format=png/path/sf5e5aab50816c139/image/i1abe69de6c4c0ade/version/1415539647/image.png" vspace="15px" hspace="15px" align="right">
Cliquez sur l'onglet bases de données, puis entrez un nom pour la future base, pas de consigne, sauf d'éviter les noms trop long, les espaces, etc...; Choisissez l'interclassement Latin1-general-ci, c'est pour la reconnaissance des caractères, si vous laissez par défaut, les voyelles accentuées vont être bizarres, et surtout bien vérifier le CI (casse insensitive), sinon vous aurez des erreurs partout à cause des MAJ/MIN.
<br>
Cliquez sur créer !
<br>
Fini !</p>

<h3 id='component-gestionnaire'>Installation de cyberGestionnaire sur le serveur</h3>
<p class="text-justify">  Dézippez maintenant le dossier de l'application dans le dossier suivant :
<br>
pour Xampp -> C:\Xampp\HTDOCS	
<br>
pour Wampp -> C:\Wampp\WWW
<br><br> Sous linux : /opt/lampp/htdocs<br>
Vérifiez les options d'écriture dans le dossier : sudo chmod -R 777 /opt/lampp/htdocs
<br><br>
Le serveur apache va chercher dans ces dossiers tous les sites que vous voulez construire, soit directement si vous codez le HTML, soit les CMS que vous voulez utiliser (wordpress,joomla, etc...). Si vous devez aussi héberger un site pour votre EPN, c'est le moment de tester Wordpress !
<br>
De retour dans votre navigateur, entrez maintenant l'adresse de votre dossier d'installation :
<br>
localhost/nom_du_dossier/install
<br>
Vous devriez tomber sur l'image ci-dessous.Comme on vous y invite, cliquez sur installer, puis laissez-vous guider. 
</p>
<p><img src="img/install_1.png"></p>
<p><img src="img/install_2.png"></p><p>Etape 1, vérification de la présence du dossier de sauvegarde de la base de données (voir plus loin !), et du fichier de configuration pour la connexion.</p>
<p>A l'étape 2, rentrez comme demandé les informations pour que le logiciel puisse accéder à la base de donnée (pour l'instant vide) que vous avez créée spécialement pour lui. </p>
<p><img src="img/install_3.png"></p>
<ul>

  <li>  Nom d'utilisateur, pour l'instant c'est root</li>
  <li>  Le mot de passe (pour root)</li>
   <li> La base</li></ul>
<p class="text-justify">L'étape 3 va créer les tables de données qui servent au stockage de toutes vos opérations. Pour l'instant la majorité sera vide, à vous de remplir, notamment la base adhérent (qui fera l'objet d'une prochaine page tuto..).
<br>
Votre application est maintenant fonctionnelle, pour la lancer, enlevez dans la barre d'adresse le dossier install pour laisser juste :
<br>
localhost/nom_du_dossier
<br>
Entrez les login/mot de passe : admin/admin pour entrer et commencer à paramétrer. 
</p>

          <!-- ============================================================= -->

<section id="configuration">
  <h2 class="page-header"><a href="#configuration">Configuration</a></h2>
  <p>Veuillez remplir les paramètres dans l'ordre suivant pour avoir accès aux réglages :</p>
  <p class="lead" id="1villes">1.Les Villes</p>
  <p>Menu Configuration / Villes</p>
  
  <p class="lead" id="2epn">2.Les EPN</p>
  <p class="text-justify">Menu Configuration / EPN<br>Les coordonnées rentrée servent à contruire les courriers qui sont générés automatiquement en PDF, les zones sont donc toutes importantes !<br>Pour trouver le logo de votre EPN dans la liste, les images doivent être placées dans le dossier img/logo. Taille 220px X 50 px<br>La couleur de l'interface que vous choissirez sera aussi un marqueur pour les événements futurs !</p>
  
   <p class="lead" id="3salles">3.Les Salles</p>
 <p class="text-justify">Menu Configuration / Salles<br>Les epn apparaitront dans la liste grâce à l'étape précédente ! Elles sont nécessaires pour la programmation des ateliers/session ainsi que pour la consultation internet.</p>
 
 <p class="lead" id="4time">4.Les Horaires</p>
 <p>Pour chaque Epn de votre réseau entrez :
	<ul><li>les horaires d'ouverture</li>
		<li>les jours de fermeture exceptionnels</li>
		<li>choisissez l'activation de la reservation rapide (facilité de réservation manuelle d'un poste pour la journée en cours), avec son unité par défaut : 1 h, 30 min par réservation etc..</li>
		<li>choisissez la plus petite <b>unité de temps réservable</b> sur le planning des réservations et la <b>durée maximum autorisée</b> pour 1 connexion.</li>
		</ul>
	</p>
	
	<p class="lead" id="5tarifs">5.Les Tarifs</p>
	<p>Vous avez le choix d'entrer les différents types de tarifs sur cet écran:</p>
		<ul><li>Les abonnements</li>
		<li>Les consommables --> dans "divers"</li>
		<li>Les impressions </li>
		<li>Les forfaits ateliers</li>
		<li>Les forfaits pour la consultation internet</li></ul>
		<p class="bg-danger">Ces deux derniers tarifs interagissent avec EPNConnect, qui ira vérifier leur état de validité et d'expiration.</p>
		
	<p class="lead" id="6postes">6.Les Postes</p>
	<p>Entrez les postes informatiques et les ressources que vous mettez à disposition dans vos epns. Les adresses IP et paramétrages avancés ne sont pas indispensables si vous n'utilisez pas EpnConnect !</p>
	
	<p class="lead" id="7usagesadh">7.Les Usages adhérents</p>
	<p>Cette page est dédiée à EpnConnect également, elle affiche à la connexion de l'adhérent une liste de choix pour sa connexion au poste.</p>
	
	<p class="lead" id="8usagespostes">8.Les usages postes</p>
	<p>Cette est maintenue malgré le fait qu'elle n'est pas encore utilisée, elle concerne les usages à la réservation des postes.</p>
	
	<p class="lead" id="9anim">9.La gestion des Animateurs et Administrateurs</p>
	<p>Entrez ici les animateurs de votre réseau en 2 étapes :
		<ol><li>La fiche adhérent de l'animateur avec un minimum d'info, n'oubliez pas le mot de passe !</li>
			<li>Les paramétrages pour chaque animateur :
					<ul><li>Son avatar</li><li>Son Epn de référence</li><li>Les salles sur lesquelles il exerce</li></ul>
				</li></ol>
			Attention ces informations sont capitales pour la programmation des ateliers !!
	</p>
	
	<p class="lead" id="10bdd">10. Les imports/exports de la base de donnée</p>
	<p>Il est temps maintenant que toutes les données préalables ont été rentrées d'importer vos données et de les conserver !</p>
	<div class="callout callout-danger " ><h4>Concernant les sauvegardes de la base : notez bien qu'une fois par mois, lorsqu'un administrateur se connecte il lui sera demander d'effectuer un export de la base. Elle sera automatiquement enregistrée sur le serveur dans le dossier /sql. Je vous conseille vivement d'en faire une copie sur support externe en cas de crash.</h4></div>
	<p></p>
	
	<p class="lead">11. Paramétrages d'EpnConnect</p><p>Voir la section plus bas</p>
	
	<p class="lead" id="12inscriptions">12. Les pré-inscriptions</p>
	<p class="text-justify">Il est possible d'effectuer des préinscriptions directement via internet (si votre Gestionnaire est hébergé) ou dans votre espace si vous n'utilisez pas EpnConnect. En activant cette option, vous devrez entrer le code Recaptcha fourni par google pour vérifier que c'est bien une personne physique qui s'est pré-inscrite. Rendez-vous sur <a href="https://www.google.com/recaptcha" target="_self">leur site</a>, inscrivez-vous avec un compte google, et accédez au service.</p>
	<p class="text-justify">La première étape est de déclarer votre site</p>
	<p><img src="img/create.png"></p><p>Entrez le nom de votre cyberGestionnaire, puis son URL</p>
	<p><img src="img/code.png"></p>
	<p class="text-justify">Pour la seconde partie, Google va vous fournir un code à mettre dans la base du Gestionnaire, le <code>data-sitekey</code>
	, c'est la partie que j'ai flouttée !<br>
	Faites un copier-coller de ce code, sans les guillemets dans le formulaire :</p>
	<p><img src="img/enregistrer.png"></p>
	<p class="text-justify">Le captcha devrait maintenant apparaitre dans la page des préinscriptions accessible depuis la page de login.</p>
	<p><img src="img/login.png"></p>
	<p>Les animateurs sont avertis des demandes de préinscription directement sur l'interface de gestion</p>
	<p><img src="img/notification_preins.png"></p>
</section>

          <!-- ============================================================= -->

<section id='epnconnect'>
  <h2 class='page-header'><a href="#epnconnect">EpnConnect</a></h2>
  <p class="lead">EpnConnect est un module client qui s'installe sur chaque PC de votre epn afin de sécuriser la connexion internet libre. Il permet d'enregistrer les entrées / sorties de chaque adhérent.</p>
  <h3 id='installation-epnconnect'>Installation d'epnConnect</h3>
<p>Créez un nouveau profil de type "administrateur" sur chacune de vos machine, vous pouvez sous AD créer un profil itinérant qui sera installé via le serveur sur chaque machine. </p>
<p>Dézippez EpnConnect directement dans C:, évitez le dossier programme qui est soumis selon votre OS a des droits différents.</p>
<p>Lancez epnconnect pour le paramétrer la première fois.</p>
 <h3 id='parametres-epnconnect'>Paramétrages d'epnConnect</h3>
<p>L'écran de paramétrage comporte les rubriques suivantes : IP du poste serveur sur lequel est situé le CyberGestionnaire, nom de la base/mot de passe. Ensuite vous sera demandé un profil animateur ou administrateur avec lequel vous pourrez manipuler le Pc.</p>
<p>Vérifiez sur la page Configuration/EpnConnect que toutes les cases sont cochées, notament celles qui servent à "désarmer" EpnConnect quand il est lancé (CTRL+MAJ+ALT+touche monter)</p>

</section>

          <!-- ============================================================= -->

<section id='planning'>
  <h2 class='page-header'><a href="#planning">Planning</a></h2>
  <p class="lead">Zone en construction</p>
  
</section>

          <!-- ============================================================= -->

<section id='adherents'>
  <h2 class='page-header'><a href="#adherents">Adherents</a></h2>
   <p class="lead">Zone en construction</p>
</section>
					
					<!-- ============================================================= -->

<section id='ateliers'>
  <h2 class='page-header'><a href="#ateliers">Ateliers</a></h2>
   <p class="lead">Zone en construction</p>
</section>
					
					<!-- ============================================================= -->

<section id='sessions'>
  <h2 class='page-header'><a href="#sessions">Sessions</a></h2>
   <p class="lead">Zone en construction</p>
</section>
          
          				<!-- ============================================================= -->

<section id='impressions'>
  <h2 class='page-header'><a href="#impressions">Impressions</a></h2>
   <p class="lead">Zone en construction</p>
</section>


        </div><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
       
      </footer>

    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../template/dist/js/app.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
    <script src="docs.js"></script>
  </body>
</html>
<?php
?>