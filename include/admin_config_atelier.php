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

*/
    require_once("include/class/CSP.class.php");
    require_once("include/class/AtelierCategorie.class.php");
    require_once("include/class/AtelierNiveau.class.php");
    
    $mesno = isset($_GET["mesno"]) ? $_GET["mesno"] : '';
    if ($mesno != "") {
        echo getError($mesno);
    }
    
?>

<div class="row">
    <div class="col-lg-4">
        <!-- liste des categories existantantes pour modification-->
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Cat&eacute;gories d'atelier ou session</h3></div>
            <div class="box-body no-padding">
                <table class="table">
<?php

    $categories = AtelierCategorie::getAtelierCategories();
    
    if ($categories !== null ) {
        foreach ($categories as $categorie) {
?>
                    <form action="index.php?a=7&act=2&idcat=<?php echo $categorie->getId(); ?>" method="post" class="form">
                        <tr>
                            <td><input type="hidden" name="submitcat" ><input class="form-control" type="text" name="categorie" value="<?php echo htmlentities($categorie->getLabel());?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
                            <td>
                                <button class="btn btn-success"  type="submit"  name="submitcat" value="<?php echo $categorie->getId(); ?>"><i class="fa fa-refresh"></i></button>
                                &nbsp;<a href="index.php?a=7&act=3&idcat=<?php echo $categorie->getId(); ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a>
                            </td>
                        </tr>
                    </form>
<?php 
        }
    
    }
    else {
        echo geterror(0);
    }
?>
                </table>
            </div>
            <div class="box-footer">
                <h4 >Enregistrer une nouvelle cat&eacute;gorie</h4>
                <form method="post" action="index.php?a=7&act=1" class="form">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="newcat" placeholder="Nom de la categorie"><input type="hidden" name="submitcat" >
                        <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
                    </div>
                </form>
            </div>
        

        </div><!-- .box -->
    </div><!-- .col-lg-4 -->


    <!-- Niveau de comp&eacute;tence Atelier -->
    <div class="col-lg-4">
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Niveau d'atelier ou session</h3></div>
            <div class="box-body no-padding">
                <table class="table">
<?php

    $niveaux = AtelierNiveau::getAtelierNiveaux();
    
    if ($niveaux !== null ) {
        foreach ($niveaux as $niveau) {
            
?>
                    <form action="index.php?a=7&act=2&idniveau=<?php echo $niveau->getId(); ?>" method="post" class="form">
                        <tr>
                            <td><input type="hidden" name="submitniv" ><input class="form-control" type="text" name="niveau" value="<?php echo htmlentities($niveau->getNom());?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
                            <td><button class="btn btn-success"  type="submit"  value="<?php echo $niveau->getId(); ?>"><i class="fa fa-refresh"></i></button>&nbsp;<a href="index.php?a=7&act=3&idniveau=<?php echo $niveau->getId(); ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a></td>
                        </tr>
                    </form>
<?php 
        }
    
    }
    else {
        echo geterror(0);
    }
?>
                </table>
            </div>
            <div class="box-footer">
                <h4 >Enregistrer un nouveau niveau</h4>
                <form method="post" action="index.php?a=7&act=1" class="form">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="newniveau" placeholder="Nom du niveau"><input type="hidden" name="submitniv" >
                        <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
                    </div>
                </form>
            </div>
        </div><!-- .box -->
    </div><!-- .col-lg-4 -->


    <!-- Categories socio professsionnelles -->
    <div class="col-lg-4">
  
        <!-- liste des categories existantantes pour modification-->
        <div class="box box-success">
            <div class="box-header"><h3 class="box-title">Cat&eacute;gories Socio-Professionnelles</h3></div>
            <div class="box-body no-padding">
                <table class="table">
<?php
    $professions = CSP::getCSPs();
    if ($professions !== null) {
        foreach($professions as $profession) {
?>
                    <form action="index.php?a=7&act=2&idcsp=<?php echo $profession->getId(); ?>" method="post" class="form">
                        <tr>
                            <td><input type="hidden" name="submitcsp" ><input class="form-control" type="text" name="csp" value="<?php echo htmlentities($profession->getCsp());?>" onchange="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1).toLowerCase();"></td>
                            <td><button class="btn btn-success"  type="submit"  name="submitcsp" value="<?php echo $profession->getId(); ?>"><i class="fa fa-refresh"></i></button>&nbsp;<a href="index.php?a=7&act=3&idcsp=<?php echo $profession->getId(); ?>"><button type="button" class="btn btn-danger"><i class="fa fa-remove"></i></button></a></td>
                        </tr>
                    </form>
<?php 
        }

    }
    else {
        echo geterror(0);
    }
?>
                </table>

            </div>
            
            <div class="box-footer">
                <h4 >Enregistrer une nouvelle cat&eacute;gorie</h4>
                <form method="post" action="index.php?a=7&act=1" class="form">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="newcsp" placeholder="Nom de la CSP"><input type="hidden" name="submitcsp" >
                        <span class="input-group-btn"><button type="submit" class="btn btn-info btn-flat" type="button"><i class="fa fa-check"></i></button></span>
                    </div>
                </form>
            </div>
        </div><!-- .box -->
    </div><!-- .col-lg-4 -->
</div><!-- .row -->
