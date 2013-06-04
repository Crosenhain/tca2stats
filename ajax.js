//https://github.com/Crosenhain/tca2stats
//Really basic ajax stuff
var xmlHttp;

function getGameList(str)
{
        xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null) {
            alert ("AJAX ERROR");
                return;
        }

        var url2="gameList.php";
        url2=url2+"?game="+escape(str);
        url2=url2+"&sid="+Math.random();
        xmlHttp.open("GET",url2,true);
        xmlHttp.send(null);
        xmlHttp.onreadystatechange= function () {
                if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {
                        document.getElementById("individualservers").innerHTML=xmlHttp.responseText;
                        document.getElementById("individualservers").scrollIntoView();
                }
        }
}


function clearGameList()
{
    document.getElementById("individualservers").innerHTML="";
    document.getElementById("container").scrollIntoView();
}

function GetXmlHttpObject()
{
        var xmlHttp=null;
        try
        {
                // Firefox, Opera 8.0+, Safari
                xmlHttp=new XMLHttpRequest();
        }
        catch (e)
        {
                //Internet Explorer
                try
                {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
        }
        return xmlHttp;
}
