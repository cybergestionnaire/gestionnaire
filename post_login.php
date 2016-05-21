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

 post_login.php V0.1
*/

// Process d'autentification utilisateur et admin.

$login  = $_POST["log"];
$passwd = $_POST["pass"];

include ("include/fonction.php");
$result = checkUser($login,$passwd);
if (FALSE == $result)
{
  header("Location: ./index.php?error=3");
}
else
{
  header("Location: ./index.php");
}

?>