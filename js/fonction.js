// JavaScript Document

//fonction de comptage de checkbox coché page admin_form_atelier.php
function countChecked()
{
   var total = 0;
   for ( var i = 0; i < document.atelier.elements.length; i++ )
   {
      if ( document.atelier.elements[ i ].type == 'checkbox' )
      {
		  	if( document.atelier.elements[ i ].id == 'checkbox_ress' )
			{
         		if ( document.atelier.elements[ i ].checked == true )
         		{
            		total ++;
         		}
			}
      }
   }
   if(total<=1)
   {
   			//ici le champ text pour afficher le nombre de cases cochées inférieur ou égale à 1
   			document.getElementById("nbrressources").innerHTML="<strong>"+total + " ressource</strong>";
   }
   else
   {
   			//ici le champ text pour afficher le nombre de cases cochées supérieur à 1
   			document.getElementById("nbrressources").innerHTML="<strong>"+total + " ressources</strong>";
   }
} 

//fonction d'afichage d'un type texte apres selection "autre" combobox ville 
//page user_form_compte.php 
//page admin_form_user.php
//page admin_form_inscription.php
function AfficheCommuneAutre()
{
   var commune = 0;
   for ( var i = 0; i < document.formcompte.elements.length; i++ )
   {
      if ( document.formcompte.elements[ i ].name == 'ville' )
      {
         if ( document.formcompte.elements[ i ].options[document.formcompte.elements[ i ].options.selectedIndex].text == "Autres" )
         {
            commune=1;
         }
      }
   }
   if(commune==1)
   {
   		for ( var i = 0; i < document.formcompte.elements.length; i++ )
   		{
      		if ( document.formcompte.elements[ i ].name == 'commune_autre' )
      		{
        		 document.formcompte.elements[ i ].type = "text";
      		}
      		else if ( document.formcompte.elements[ i ].name == 'code_postale' )
      		{
        		 document.formcompte.elements[ i ].type = "text";
      		}
      		else if ( document.formcompte.elements[ i ].name == 'pays' )
      		{
        		 document.formcompte.elements[ i ].type = "text";
      		}
		}
   }
   else
   {
   		for ( var i = 0; i < document.formcompte.elements.length; i++ )
   		{
      		if ( document.formcompte.elements[ i ].name == 'commune_autre' )
      		{
        		 document.formcompte.elements[ i ].type = "hidden";
      		}
      		else if ( document.formcompte.elements[ i ].name == 'code_postale' )
      		{
        		 document.formcompte.elements[ i ].type = "hidden";
      		}
      		else if ( document.formcompte.elements[ i ].name == 'pays' )
      		{
        		 document.formcompte.elements[ i ].type = "hidden";
      		}
		}
   }
} 

//fonction d'affichage de la console page admin_console.php
function request(callback) {
	var xhr;
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xhr=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xhr=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	
	xhr.onreadystatechange = function() 
	{
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
		{
			callback(xhr.responseText);
		}
	};
	var numsalle = encodeURIComponent(document.getElementById('numconsole').value);

	if(numsalle!=0)
	{
		xhr.open("POST","include/console.php",true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("id_salle="+numsalle);
	}
}

function readData(sData) 
{
	document.getElementById("consoleafficher").innerHTML=sData;
}

var timer=setInterval("request(readData)", 5000); // répète toutes les 5s

window.onload = function(e) {
    request(readData);
};

//action (affectation, libération, etc...) de la console page admin_console.php
function ActionConsole() 
{
	var xhr;
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xhr=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xhr=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	
	xhr.onreadystatechange = function() 
	{
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) 
		{
			//alert(xhr.responseText);
			document.getElementById("actionconsoleafficher").innerHTML=xhr.responseText;
		}
	};
   for ( var i = 0; i < document.formactionconsole.elements.length; i++ )
   {
     	if ( document.formactionconsole.elements[ i ].name == 'option_console' )
      	{
			if(document.formactionconsole.elements[i].options[document.formactionconsole.elements[i].options.selectedIndex].value!=0)
			{
				var action = document.formactionconsole.elements[i].options[document.formactionconsole.elements[i].options.selectedIndex].value;
			}
      	}
   }
   //alert(action);
	xhr.open("GET","include/actionconsole.php?" + action,true);
	xhr.send(null);
}

function ActionConsole2(callback, action) {
    //alert("callback = " + callback + ", action = " + action);
    
    var xhr;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xhr=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xhr=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            //alert(xhr.responseText);
            callback(xhr.responseText);
        }
    };

    //alert(action);
    xhr.open("GET","include/actionconsole.php?" + action,true);
    xhr.send(null);
}
function affichageAction(sData) {
    //alert("sData = " + sData);
    document.getElementById("actionconsoleafficher").innerHTML=sData;
    request(readData);
}