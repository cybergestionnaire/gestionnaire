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
 

  include/admin_materiel.php V0.1
*/

// Configuration des cat&eacute;gories modifiables pour les statistiques

$mesno= $_GET["mesno"];
if ($mesno !="")
{
  echo getError($mesno);
}

// traitement des post
$act = $_GET["act"];

$idcat = $_GET["idcat"];
$idniveau=$_GET["idniveau"];
$idcsp=$_GET["idcsp"];

$testcat=$_POST["submitcat"];
$testniv=$_POST["submitniv"];
$testcsp=$_POST["submitcsp"];

switch ($act)
{
  case 1: // creation
       $nom = addslashes($_POST["newcat"]);
      $niveau=addslashes($_POST["newniveau"]);
			$newcsp=addslashes($_POST["newcsp"]);
      
    if (isset($testcat)){
      
	       if (FALSE == addCategorie($nom))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
		}
	}elseif(isset($testniv)){
			   if (FALSE == addniveau($niveau))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
		}

	}elseif(isset($testcsp)){
		 if (FALSE == addcsp($newcsp))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
				 }
	}
	
	
	
  break;

  case 2: // modification
      $nom = addslashes($_POST["categorie"]);
      $nivau = addslashes($_POST["niveau"]);
			$modcsp=addslashes($_POST["csp"]);
       
     if (isset($testcat)){
	       if (FALSE == modCategorie($idcat,$nom))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
		}
	}elseif(isset($testniv)){
		       if (FALSE == modniveau($idniveau,$nivau))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
		}
	}elseif(isset($testcsp)){
		       if (FALSE == modcsp($idcsp,$modcsp))
	       {
		   echo getError(0);
	       }else{
	       
		header("Location:index.php?a=7") ;
		}
	}
	
  break;
  case 3: // suppression
		$idcat=$_GET["idcat"];
		$idniveau=$_GET["idniveau"];
  if (isset($idcat)){		
       if (FALSE == supCategorie($idcat)){
	    echo getError(0);
       }else{
       
        header("Location:index.php?a=7") ;
        }
    }elseif(isset($idniveau)){
	  if (FALSE == supNiveau($idniveau)){
	    echo getError(0);
       }else{
       
        header("Location:index.php?a=7") ;
        }
	}elseif(isset($idcsp)){
	  if (FALSE == supcsp($idcsp)){
	    echo getError(0);
       }else{
       
        header("Location:index.php?a=7") ;
        }
	}
  break; 
}
 
?>

<div class="row">
<div class="col-lg-4">
<!-- liste des categories existantantes pour modification-->
 <div class="box box-success"><div class="box-header"><h3 class="box-title">Cat&eacute;gories d'atelier ou session</h3></div>
	 <div class="box-body no-padding"> <table class="table">
	<?php
	$categories = getAllCategorie(1) ;
	$nbcat=count($categories);
	//debug($nbcat);
if ($nbcat>0){
foreach($categories as $key => $value)
	
	{
	//debug($i);
	?>
		<form action="index.php?a=7&act=2&idcat=<?php echo $key; ?>" method="post" class="form">
		<tr>
          	 <td><input type="hidden" name="submitcat" ><input class="form-control" type="text" name="categorie" value="<?php echo $value;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
			<td><button class="btn btn-success"  type="submit"  name="submitcat" value="<?php echo $key; ?>"><i class="fa fa-refresh"></i></button>
			&nbsp;<a href="index.php?a=7&act=3&idcat=<?php echo $key; ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a></td>
		</tr>
		</form>
	<?php 
	}
	
}else{
	echo geterror(0);
}	?>
	</table>

</div><div class="box-footer">
		<h4 >Enregistrer une nouvelle cat&eacute;gorie</h4>
	<form method="post" action="index.php?a=7&act=1" class="form">
	<div class="input-group input-group-sm">
			<input type="text" class="form-control" name="newcat" placeholder="Nom de la categorie"><input type="hidden" name="submitcat" >
			 <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
	</div>
	</form>
	</div>
	

</div></div>


<!-- Niveau de comp&eacute;tence Atelier -->
<div class="col-lg-4">
 <div class="box box-success"><div class="box-header"><h3 class="box-title">Niveau d'atelier ou session</h3></div>
	 <div class="box-body no-padding"> <table class="table">
	 <?php
	$niveaux =getAllLevel(1);
	$nbniv=count($niveaux);
	
if ($nbniv>0){

	foreach($niveaux as $key => $value)
	{
	
	?>
		<form action="index.php?a=7&act=2&idniveau=<?php echo $key; ?>" method="post" class="form">
		<tr>
          	 <td><input type="hidden" name="submitniv" ><input class="form-control" type="text" name="niveau" value="<?php echo $value;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
						 <td><button class="btn btn-success"  type="submit"  value="<?php echo $key; ?>"><i class="fa fa-refresh"></i></button>&nbsp;<a href="index.php?a=7&act=3&idniveau=<?php echo $key; ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a></td>
		</tr>
		</form>
	<?php 
	}
	
}else{
	echo geterror(0);
}	?>
	 </table></div>
	 <div class="box-footer">
		<h4 >Enregistrer un nouveau niveau</h4>
	<form method="post" action="index.php?a=7&act=1" class="form">
	<div class="input-group input-group-sm">
			<input type="text" class="form-control" name="newniveau" placeholder="Nom du niveau"><input type="hidden" name="submitniv" >
			 <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
	</div>
	</form>
</div></div>
</div>


<!-- Categories socio professsionnelles -->
<div class="col-lg-4">
  
<!-- liste des categories existantantes pour modification-->
 <div class="box box-success"><div class="box-header"><h3 class="box-title">Cat&eacute;gories Socio-Professionnelles</h3></div>
	 <div class="box-body no-padding"> <table class="table">
	<?php
	$profession = getAllCsp();
	$nbcsp=count($profession);
	//debug($nbcat);
if ($nbcsp>0){
foreach($profession as $key => $value)
	
	{
	//debug($i);
	?>
		<form action="index.php?a=7&act=2&idcsp=<?php echo $key; ?>" method="post" class="form">
		<tr>
          	 <td><input type="hidden" name="submitcsp" ><input class="form-control" type="text" name="csp" value="<?php echo $value;?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
			<td><button class="btn btn-success"  type="submit"  name="submitcsp" value="<?php echo $key; ?>"><i class="fa fa-refresh"></i></button>&nbsp;<a href="index.php?a=7&act=3&idcsp=<?php echo $key; ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a></td>
		</tr>
		</form>
	<?php 
	}
	
}else{
	echo geterror(0);
}	?>
	</table>

</div><div class="box-footer">
		<h4 >Enregistrer une nouvelle cat&eacute;gorie</h4>
	<form method="post" action="index.php?a=7&act=1" class="form">
	<div class="input-group input-group-sm">
			<input type="text" class="form-control" name="newcsp" placeholder="Nom de la CSP"><input type="hidden" name="submitcsp" >
			 <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
	</div>
	</form>
	</div>
	

</div>
</div>







</div>
