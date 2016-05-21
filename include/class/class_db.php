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
 
 class_db.php V0.1  
 cybermin 2
 
 * classe de gestion de la base de données
 *
*/
class db
{
	 var $host;        # hote 
	 var $port;		   # port	
	 var $database ;   # nom de la base de données	
	 var $login ;	   # login
	 var $passwd ;     # passwd	
	 var $table	;	   # liste des tables de la base	
	 var $column ;	   # liste des champs d'une table
	 var $nbcolumn;	   # Nombre de champs dans une table
      
     /*
      * Constructeur de classe
      * permet d'initialiser les variables de connexion
     */
     function db()
     {
      		include('./connect_db.php');
      		$this->host     = $host ;
      		$this->port     = $port ; 
      		$this->database = $database ;
      		$this->login    = $userdb ;
      		$this->passwd   = $passdb ;      		
     }
     
     /* accesseur de column
      * rempli la var column en fonction de la table demandée et la renvoi
     */
     function setTable($table)
     {
     	$this->table=$table ;
     }
     function getTable()
     {
     	return $this->table ;
     }
     
     /* accesseur de column
      * rempli la var column en fonction de la table demandée et renvoi
      * un tableau contenant les valeurs des entetes de la table
     */
     function setColumn($list)
     {
     	$this->column=$list ;
     }
     function getColumn()
     {
     	return $this->column ;
     }
     
     /* accesseur du nombre de column
      * dans une table
     */
     function setNbColumn($number)
     {
     	$this->nbcolumn=$number ;
     }
     function getNbColumn()
     {
     	return $this->nbcolumn ;
     }
     
     /*
      * Ouverture de la base de données
     */
     function opendb()
     {
     	/*creation de la liaison avec la base de donnees*/
    	$db = mysql_connect($this->host.':'.$this->port , $this->login , $this->passwd) ;
    	/*en cas d'echec*/
    	if (FALSE === $db)
       	{
       		return FALSE;
       	}
       	else
       	{
    		/*selection de la base de donnees a utilise */
    		mysql_select_db ($this->database) ;
    		return TRUE ;
    	}
     }
      
      /* Recupere la liste des tables de la base de données
       * @return FALSE
       * @set table
      */
      function getTableList()
      {
        $sql = 'SHOW TABLES FROM '.$this->database ;
        if(FALSE==$this->opendb()) //ouvre la base
        {
        	return FALSE ; 
        }
        else // base ouverte
        { 
        	$result = mysql_query($sql) ;
        	if (FALSE == $result)
			{
				return FALSE ;
			}
			else // traitemnet de la requete
			{
				$table = array();
				$i=0 ;
				while ($row = mysql_fetch_assoc($result))
				{                                              
					 foreach($row AS $value) 
					 {
						 $table[$i]= $value ; 
					 }
					 $i++;
				}
				// initialise la variable table 
				// contenant la liste des tables de la base
				$this->setTable($table) ;
			} 
        }
    }
    
    /* Initialise la liste des champs d'une table
     * @return FALSE
     * @set column
    */
    function getColumnList($table)
    {
    	$sql = 'SHOW COLUMNS FROM '.$table.' FROM '.$this->database;
    	if(FALSE==$this->opendb())
        {
        	return FALSE ;
        }
        else
        { 
        	$result = mysql_query($sql) ;
        	if (FALSE == $result)
			{
				return FALSE ;
			}
			else
			{
				// initialise la variable column 
				// contenant la liste des colonnes d'une table
				$this->setColumn($result);
				// on initialise le nombre de colonnes 
				// dans une table
				$this->setNbColumn(mysql_num_rows($result)) ;
			}
		}
    }
    
    /* Renvoi le dump de la base cybermin
     * @return FALSE
     * @return string
    */
    function dumpdb()
    {
    	$var  ="-- SAUVEGARDE CYBERGESTIONNAIRE 0.8 \n";
    	$var .="-- le ".date('d-m-Y à h:i:s')." \n\n";
    	// on crée la liste des tables
    	$this->getTableList() ;
    	// pour chaque table de la base on extrait les champs 
    	foreach($this->getTable() AS $table)
    	{
    		
		$var.="\n--\n-- ".$table."\n--\n" ;
		
    		$var.="TRUNCATE TABLE `".$table."` ;\n\n" ;
		
		
				
    		//on crée la liste des champs de la table courante
    		$this->getColumnList($table);
    		// onn initialise la variable temp. var a vide
    		$tmp='';
       		$this->opendb();
				
    		$sql = "SELECT * FROM `".$table."` " ;
    		$result = mysql_query($sql) ;
    		if($result!=FALSE)
    		{
    			while($row = mysql_fetch_array($result))
    			{
    				//echo count($row) ;
    				//print_r($row) ;
				
    				//remplissage de la table
   					$var .= "INSERT INTO `".$table."` VALUES (";
    				for($i=1; $i<=(count($row)/2) ;$i++)
    				{ 
    					if ($i!=(count($row)/2))
    						$var .= "'".addslashes($row[$i-1])."'," ;
    					else
    						$var .= "'".addslashes($row[$i-1])."'" ;
    				}
    				$var .=");\n" ;
    			}
    		}
    	}
		// on renvoi le dump de la base
    	return $var ;
    }
    
    
    
    
    
    
}


?>