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

    Original work : CyberGestionnaire / 2006 Namont Nicolas
    revamped by : CyberGestionnaire-martigues / 2014 SAINT MARTIN Brice
    
*/
    header("Content-Type: text/html; charset=UTF-8");
    //header("Content-Type: text/plain");

    date_default_timezone_set('Europe/Paris');
    include ("../connect_db.php");
    
    /*if ($port=="" OR FALSE==is_numeric($port))
    {
        $port="3306" ;
    }*/
    
    
    /*creation de la liaison avec la base de donnees*/
    $db = mysqli_connect($host,$userdb,$passdb,$database ) ;
    /* Vérification de la connexion */
    if (mysqli_connect_errno()) 
    {
        return false;
    } else {
        $db->set_charset("utf8");
        $salle = $_POST['id_salle'];
                    
        //$resultPostes = getConsole($salle);
        //récupération de la liste d'ordinateur dans la salle demandé
        $sql = "SELECT `nom_computer`, `id_computer`, `adresse_ip_computer`,`date_lastetat_computer`, `lastetat_computer`, `usage_computer` FROM tab_computer WHERE id_salle=".$salle." ORDER BY nom_computer;";
        $resultPostes = mysqli_query($db, $sql);
        //$resultPostes = $db->query($sql);
        
        //récupération des informations d'occupation de poste dans la salle demandé         
        $sql = "SELECT `nom_computer`, `id_computer`, `nom_user`, `prenom_user`, `status_user`, `date_resa`, `debut_resa` FROM `tab_user`, `tab_computer`, `tab_resa` WHERE `tab_resa`.`id_user_resa`=`tab_user`.`id_user` AND `tab_resa`.`id_computer_resa`=`tab_computer`.`id_computer` AND `tab_computer`.`id_salle`='".$salle."' AND `tab_resa`.`status_resa`='0' ORDER BY `nom_computer`;";
        $resultInfos = mysqli_query($db, $sql);
        //$resultInfos = $db->query($sql); 
        
        //récupération des informations de la salle         
        $sql = "SELECT `id_salle`, `nom_salle`, `id_espace`, `comment_salle` FROM tab_salle WHERE id_salle=".$salle.";";
        $resultSalles = mysqli_query($db, $sql);
        //$resultSalles = $db->query($sql);
                    
        mysqli_close ($db) ;
                    
        if (FALSE == $resultInfos || FALSE == $resultPostes || FALSE == $resultSalles)
        {
                        //echo getError(1);
            echo "<div class=\"error\">Impossible de r&eacute;cup&eacute;rer les informations sur l'occupation des postes</div>";
        }
        else  // affichage du resultat
        {
            $rowSalles = mysqli_fetch_array($resultSalles) ;
            $nbPostes  = mysqli_num_rows($resultPostes);
            //$nbPostes  = $resultPostes->num_rows;
?>
<div class="box box-solid box-warning">
    <div class="box-header"><h3 class="box-title"><?php echo $rowSalles['nom_salle'] ?></h3></div>
    <div class="box-body no-padding">
    <form name="formactionconsole">
    <table class="table">
        <tr class="list_title"><th>Nom Poste</th><th>&Eacute;tat</th><th>Affectation</th><th>Options</th></tr>
<?php
            if ($nbPostes > 0) {// il y a des postes dans la salle demandee
                $rowInfos = mysqli_fetch_array($resultInfos) ;
                for ($i = 1 ; $i <= $nbPostes ; $i++)  {
                    $rowPostes = mysqli_fetch_array($resultPostes) ;
                    if($rowPostes["id_computer"] == $rowInfos["id_computer"]) { //si un poste a des infos de reservation, alors il est occupe
                        //poste occupé
                        
                        $heureresa = $rowInfos["debut_resa"];
                        $heure     = floor($heureresa / 60);
                        $minute    = $heureresa - $heure * 60;
                        if ($minute < 10) {
                            $temp = $rowInfos["date_resa"] . " " . $heure . ":0" . $minute;
                        } else {
                            $temp = $rowInfos["date_resa"] . " " . $heure . ":" . $minute;
                        }                                
                        $dateresa      = date_create_from_format("Y-m-d H:i",$temp);
                        $diff          = time() - date_timestamp_get($dateresa); // difference en secondes
                        $now           = new DateTime();
                        $interval      = date_diff($datelastetat, $now);
                        $time          = $interval->format("%d j %hh%im");                       
                        if ($diff < 60) {
                            $time = "<1mm" ;
                        }
?>
        <tr class="list_console_occup">
            <td><?php echo $rowInfos["nom_computer"] ?></td>
            <td>Occup&eacute;</td>
            <td><?php echo $rowInfos["nom_user"] ?> <?php echo $rowInfos["prenom_user"] ?> 
<?php
                        if ($rowInfos["status_user"] == 1) {
                            echo "(".$time.")"; 
                        }
                        else if($rowInfos["status_user"] == 3) {
                            echo "(Animateur)";
                        }
                        else if($rowInfos["status_user"] == 4)
                        {
                            echo "(Administrateur)";                                  
                        }
?>
            </td>
            <td>
            <?php if($rowInfos["status_user"] == 1) { ?>
                <a class="btn btn-danger" href="#" onClick="ActionConsole2(affichageAction,'action=2&id_poste=<?php echo $rowPostes["id_computer"] ?>')">Lib&eacute;ration</a>
            <?php } ?>
            </td>
        </tr>
<?php                  
                        $rowInfos = mysqli_fetch_array($resultInfos) ;    
                    } else {   
                        //poste libre
                        $heurelastetat = $rowPostes["lastetat_computer"]; // en secondes depuis 0:00:00
                        $heure         = floor($heurelastetat / 3600);
                        $minute        = floor(($heurelastetat - $heure * 3600) / 60 );
                        $seconde       = floor($heurelastetat - $heure * 3600 - $minute*60);
                        
                        $temp          = $rowPostes["date_lastetat_computer"]." ".str_pad($heure, 2, "0",STR_PAD_LEFT).":".str_pad($minute, 2, "0",STR_PAD_LEFT).":".str_pad($seconde, 2, "0",STR_PAD_LEFT);
                        $datelastetat  = date_create_from_format("Y-m-d H:i:s",$temp);
                        $diff          = time() - date_timestamp_get($datelastetat); // difference en secondes
                        $now           = new DateTime();
                        $interval      = date_diff($datelastetat, $now);
                        $time          = $interval->format("%d j %hh%Im");

?>
        <tr class="list">
            <td><?php echo $rowPostes["nom_computer"] ?></td>
            <td><?php if ($diff < 15) { ?>Libre<?php } else { ?>&Eacute;teint (depuis  <?php echo $time ?> ) <?php } ?></td>
            <td>-</td>
            <td>
<?php 
                if (($rowPostes["usage_computer"]==1) && ($diff < 15)) { 

                    $id_poste = $rowPostes["id_computer"];
                    $db = mysqli_connect($host,$userdb,$passdb,$database ) ;
                    $sql = "SELECT tab_computer.nom_computer, tab_salle.id_espace FROM tab_computer, tab_salle WHERE tab_salle.id_salle = tab_computer.id_salle and tab_computer.id_computer = $id_poste";
                    
                    $result = mysqli_query($db, $sql);
                    $row = mysqli_fetch_array($result) ;

                    $epn = $row["id_espace"] ;
                    $nomcomp = $row["nom_computer"];
                    $dateresa = date("Y-m-d");
                    $debutresa = date("G")*60 + intval(date("i"));    
                    mysqli_close ($db) ;
?>
                <a class="btn btn-success" href="index.php?m=7&idepn=<?php echo $epn ?>&idcomp=<?php echo $id_poste ?>&nomcomp=<?php echo $nomcomp ?>&date=<?php echo $dateresa ?>&debut=<?php echo $debutresa ?>">Affectation</a>
            <?php } ?>
            </td>
        </tr>
<?php
                    }
                }
?>
    </table>
    </form>
</div>
<?php
            } else {
?>
<table width="100%"><tr class="list" align="center"><td>Aucun poste n'est pr&eacute;sent dans cette salle</td> </tr></table>
<?php
           }
        }
    }
?>