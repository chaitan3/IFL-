var i;
function change_header()
{
    i++;
    if(i>3)
        i=1;
    document.getElementById('pic').src="/~sports/ifl/images/stadiums/"+i+".jpg";
    
}
function onload_func()
{
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent))
    {
        var ieversion=new Number(RegExp.$1);
        if(ieversion<8)
        {
            document.getElementById('fpl_maketeam').innerHTML="";
            alert("Please upgrade to the latest version of Internet Explorer, 8 or use a different browser like Firefox");
            window.location="http://www.microsoft.com/Windows/internet-explorer/";
        }
    }
    i=document.getElementById('pic').src.match(/\d/g);
    i=i.join("")*1;
    setInterval("change_header()",5000);
}

