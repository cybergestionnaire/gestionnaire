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

  class_db.php V0.1
  CyberGestionnaire 2

 * classe de gestion de la base de donn�es
 *
 */

class db
{
    public $host;        # hote
    public $port;     # port
    public $database;   # nom de la base de donn�es
    public $login;    # login
    public $passwd;     # passwd
    public $table;    # liste des tables de la base
    public $column;    # liste des champs d'une table
    public $nbcolumn;    # Nombre de champs dans une table

    /*
     * Constructeur de classe
     * permet d'initialiser les variables de connexion
     */

    public function db()
    {
        include('./connect_db.php');
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->login = $userdb;
        $this->passwd = $passdb;
    }

    /* accesseur de column
     * rempli la var column en fonction de la table demand�e et la renvoi
     */

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    /* accesseur de column
     * rempli la var column en fonction de la table demand�e et renvoi
     * un tableau contenant les valeurs des entetes de la table
     */

    public function setColumn($list)
    {
        $this->column = $list;
    }

    public function getColumn()
    {
        return $this->column;
    }

    /* accesseur du nombre de column
     * dans une table
     */

    public function setNbColumn($number)
    {
        $this->nbcolumn = $number;
    }

    public function getNbColumn()
    {
        return $this->nbcolumn;
    }

    /*
     * Ouverture de la base de donn�es
     */

    public function opendb()
    {
        /* creation de la liaison avec la base de donnees */
        $db = mysql_connect($this->host . ':' . $this->port, $this->login, $this->passwd);
        /* en cas d'echec */
        if (false === $db) {
            return false;
        } else {
            /* selection de la base de donnees a utilise */
            mysql_select_db($this->database);
            return true;
        }
    }

    /* Recupere la liste des tables de la base de donn�es
     * @return FALSE
     * @set table
     */

    public function getTableList()
    {
        $sql = 'SHOW TABLES FROM ' . $this->database;
        if (false == $this->opendb()) { //ouvre la base
            return false;
        } else { // base ouverte
            $result = mysql_query($sql);
            if (false == $result) {
                return false;
            } else { // traitemnet de la requete
                $table = array();
                $i = 0;
                while ($row = mysql_fetch_assoc($result)) {
                    foreach ($row as $value) {
                        $table[$i] = $value;
                    }
                    $i++;
                }
                // initialise la variable table
                // contenant la liste des tables de la base
                $this->setTable($table);
            }
        }
    }

    /* Initialise la liste des champs d'une table
     * @return FALSE
     * @set column
     */

    public function getColumnList($table)
    {
        $sql = 'SHOW COLUMNS FROM ' . $table . ' FROM ' . $this->database;
        if (false == $this->opendb()) {
            return false;
        } else {
            $result = mysql_query($sql);
            if (false == $result) {
                return false;
            } else {
                // initialise la variable column
                // contenant la liste des colonnes d'une table
                $this->setColumn($result);
                // on initialise le nombre de colonnes
                // dans une table
                $this->setNbColumn(mysql_num_rows($result));
            }
        }
    }

    /* Renvoi le dump de la base CyberGestionnaire
     * @return FALSE
     * @return string
     */

    public function dumpdb()
    {
        $var = "-- SAUVEGARDE CYBERGESTIONNAIRE 0.8 \n";
        $var .= "-- le " . date('d-m-Y � h:i:s') . " \n\n";
        // on cr�e la liste des tables
        $this->getTableList();
        // pour chaque table de la base on extrait les champs
        foreach ($this->getTable() as $table) {
            $var .= "\n--\n-- " . $table . "\n--\n";

            $var .= "TRUNCATE TABLE `" . $table . "` ;\n\n";



            //on cr�e la liste des champs de la table courante
            $this->getColumnList($table);
            // onn initialise la variable temp. var a vide
            $tmp = '';
            $this->opendb();

            $sql = "SELECT * FROM `" . $table . "` ";
            $result = mysql_query($sql);
            if ($result != false) {
                while ($row = mysql_fetch_array($result)) {
                    //echo count($row) ;
                    //print_r($row) ;
                    //remplissage de la table
                    $var .= "INSERT INTO `" . $table . "` VALUES (";
                    for ($i = 1; $i <= (count($row) / 2); $i++) {
                        if ($i != (count($row) / 2)) {
                            $var .= "'" . addslashes($row[$i - 1]) . "',";
                        } else {
                            $var .= "'" . addslashes($row[$i - 1]) . "'";
                        }
                    }
                    $var .= ");\n";
                }
            }
        }
        // on renvoi le dump de la base
        return $var;
    }
}
