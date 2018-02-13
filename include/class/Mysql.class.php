<?php

class Mysql
{
    public static function opendb()
    {
        include("./connect_db.php");

        if ($port == "" or false == is_numeric($port)) {
            $port = "3306";
        }

        /* creation de la liaison avec la base de donnees */
        $db = mysqli_connect($host, $userdb, $passdb, $database);
        /* en cas d'echec */
        if (mysqli_connect_errno()) {
            return false;
        } else {
            $db->set_charset("utf8");
            return $db;
        }
    }

    public static function closedb($mydb)
    {
        mysqli_close($mydb);
    }
}
