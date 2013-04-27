function onlight(){
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET', 'onlight.php', true);
    xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4  && xmlhttp.status == 200)
            {

            }
        }
    xmlhttp.send(null);
}

function upsencnt(){
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open('GET', 'upsencnt.php', true);
    xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4  && xmlhttp.status == 200)
            {

            }
        }
    xmlhttp.send(null);
}

function getnowtemp()
{   
	var hd="";
	var t0="";
	var t1="";
	var t2="";
	var elhd = document.getElementById('hd');
	var elt0 = document.getElementById('t0');
	var elt1 = document.getElementById('t1');
    	var elt2 = document.getElementById('t2');

	elhd.innerHTML = ""; 
    	elt0.innerHTML = "";
    	elt1.innerHTML = "";
    	elt2.innerHTML = "";
                 
	xmlhttp = new XMLHttpRequest();
    	xmlhttp.open('GET', 'nowtemp.php', true);
    	xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4  && xmlhttp.status == 200)
            {
                var json = eval( xmlhttp.responseText );                 
    			 elhd.innerHTML = json[0].hd; 
                 elt0.innerHTML = json[0].t0;
                 elt1.innerHTML = json[0].t1;
                 elt2.innerHTML = json[0].t2;
                 
            }
        }
    	xmlhttp.send(null);
}
