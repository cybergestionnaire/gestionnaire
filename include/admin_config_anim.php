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

 2006 Namont Nicolas
 

 include/admin_user.php V0.1
*/

// admin --- Utilisateur
$mess=$_GET["mesno"];
if(isset($mess)){
	echo geterror($mess);
}

//animateur et admin
$state = array(
           3=> "Animateur",
               4=> "Administrateur",
	         5=> "Animateur Inactif"
        );
?>

<!-- DIV acces direct aux autres parametres-->
 <div class="box">
		<div class="box-header">
			<h3 class="box-title">Param&eacute;trages</h3>
		</div>
		<div class="box-body">
		<?php if($_SESSION['status']==4){
			
		
			echo configBut($_GET["a"]) ;
		
			
		 } ?>	
		</div><!-- /.box-body -->
</div><!-- /.box -->

 <div class="box">
<div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
		<?php if($_SESSION['status']==4){
               echo ' <li ><a href="#tab1"  data-toggle="tab">Administrateur</a></li>';
			  }else{
			   echo ' <li><a href="">Administrateur</a></li>';
			   }
			   ?>
				<li class="active"><a href="#tab2" data-toggle="tab">Animateur</a></li>
		
		 <li class="pull-left header">Gestion des animateurs et administrateurs</li></ul>
		
		
<?php

    // Les administrateurs ......
    $result = getAllUser(4);
 if (FALSE == $result)
    {
      echo getError(1);
    }
    else
    {
	//seuls les admins ont le droit de modifier un profil admin
	if($_SESSION['status']==4){
	 $nb  = mysqli_num_rows($result);
      if ($nb > 0)
      {
      //echo "<div class=soustitre>Administrateurs: ".$nb."</div>";
      ?>
       <div class="tab-content">
		<div class="tab-pane" id="tab1"><table class="table">
     		<thead><tr>
            <th>Nom</th><th>Pr&eacute;nom</th>
			<th>Fiches</th></tr></thead><tbody>
            <?php
				
                    for ($i=1; $i<=$nb; $i++)
                    {
                        $row = mysqli_fetch_array($result) ;
                        $testAnim=Ptestanim($row["id_user"]);
                        echo "<tr><td>".$row["nom_user"]."</td>
                             <td>".$row["prenom_user"]."</td>
							 
                             <td><a href=\"index.php?a=51&b=2&type=admin&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Fiche inscription\"><i class=\"fa fa-edit\"></i></button></a>&nbsp;";
							 
							 if ($testAnim==TRUE){ 
						echo	 "<a href=\"index.php?a=50&b=2&idanim=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Param&egrave;tres\"><i class=\"fa fa-gear\"></i></button>";
							} else {
						echo	 "<a href=\"index.php?a=50&b=1&idanim=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Param&egrave;tres\"><i class=\"fa fa-gear\"></i></button>";
							}
							echo "</td> </tr>";
                    }
            ?>
     </tbody> </table></div><!-- end of #tab1 -->
    <?php
       }
	}
 }
   
   
 // Les animateurs ......
    $result = getAllUser(3);
	
 if (FALSE == $result) 
 {
      echo getError(1);
    }
    else
    {
    $nb  = mysqli_num_rows($result);
	    if ($nb > 0)
	    {
    //echo "<div class=soustitre>Animateurs: ".$nb."</div>";
    ?>
 
<div class="tab-pane active" id="tab2"><table class="table">
			<thead>
            <th>Nom</th><th>Pr&eacute;nom</th><th>Statut</th>
			<!--<th>salles attribu&eacute;es</th>-->
			<th>Fiche</th><th>Param&egrave;tres</th></thead><tbody>
            <?php
		
				$sallesarray = getAllsalles();
				
                    for ($i=1; $i<=$nb; $i++)
                    {
                        $row = mysqli_fetch_array($result) ;
                        $testAnim=Ptestanim($row["id_user"]);
						$row2 = getAnimateur($row["id_user"]);
						$statut=$state[$row["status_user"]];
						// Information Utilisateur
						$salles=explode(";",$row2["id_salle"]);
						//$nomsalles=$sallesarray[$salle[1]];
						if($row["status_user"]==5){ 
							$class="text-muted";
							}else{ 
							$class=""; }
						
                        echo "<tr class=".$class."><td>".$row["nom_user"]."</td>
                             <td>".$row["prenom_user"]."</td>
								<td>".$statut."</td>
							
                             <td><a href=\"index.php?a=51&b=2&type=anim&iduser=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Fiche inscription\"><i class=\"fa fa-user\"></i></button></a></td>";
							 
							if ($testAnim==TRUE){ 
						echo	 "<td><a href=\"index.php?a=50&b=2&idanim=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Param&egrave;tres\"><i class=\"fa fa-gear\"></i></button></td>";
							} else {
						echo	 "<td><a href=\"index.php?a=50&b=1&idanim=".$row["id_user"]."\"><button type=\"button\" class=\"btn btn-primary sm\" data-toggle=\"tooltip\" title=\"Param&egrave;tres\"><i class=\"fa fa-gear\"></i></button></td>";
							}
						echo	"</tr>";
                    }
            ?>
   </tbody> </table>
</div><!-- /.tab-pane -->

    <?php
      } 
 }  
?>
</div><!-- /.tab-content -->
 </div><!-- nav-tabs-custom -->
 <div class="box-footer clearfix"><a href="index.php?a=51&b=1&type=anim"><input value="Cr&eacute;er un nouvel animateur/administrateur" type="submit" class="btn btn-primary"></a></div>
</div>



