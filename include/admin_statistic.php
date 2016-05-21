
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
 

  include/admin_stat.php V0.1
  2013 Ajout de la librairy libchart (gnugpl)
  Modification du fichier fonction, ajout include fonction_stat.php
  
*/

// affichage des statistiques
if ($mess !="")
{
  echo $mess;
}

?>
<article class="module width_half"><header><h3>Statistiques par utilisateurs : R&eacute;partition Homme / Femme</h3></header>
	<div class="module_content">
		<p>Nombre total de membres (adh&eacute;rents): <?php echo $nbTotal ;?> actifs, nombre total d'inactifs : <?php echo countUser(3) ;?></p>
		<div class="statBar">
			<div class="statText">Hommes</div>
			<div class="statBarContainPurple">
				<div style="width:<?php echo getPourcent($nbH,$nbTotal); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbH,$nbTotal); ?></div>
			</div></div>
			<div class="clear"></div>
		<div class="statBar">
			<div class="statText">Femmes</div>
			<div class="statBarContainPurple">
				<div style="width:<?php echo getPourcent($nbF,$nbTotal); ?>;" class="statBarPourcentYellow">&nbsp;<?php echo getPourcent($nbF,$nbTotal); ?></div>
		</div></div>
		
</div></article>
