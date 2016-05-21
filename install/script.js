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

* Javascript
* 
*/

function showTip(num)
{
    switch(num)
    {
        case 1:
            var mess = "Le nom de l'hote (exemple : localhost) ou l'adresse IP (exemple : 127.0.0.1)" ;
        break;
        case 2:
            var mess = "Le port par defaut de la base de donnees MySql est generalement 3306." ;
        break;
    }
    alert(mess) ;
}