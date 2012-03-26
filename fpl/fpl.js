function insert_player(index)
{
    sname=pname[index].split(" ");
    str='<div id="'+pclass[index]+'">';
    str+='<img src="../images/shirts/'+pteam[index]+'.gif"/><br>';
    str+='<p id="fieldname">'+sname[0]+'</p>';
    str+='<img style="float:right;margin-top:5px" id="remove" src="../images/remove.png" onclick="remove_player('+index+')"/>';
    str+='<p style="color:white;font-size:0.9em">'+ppoints[index]+'</p>';
    str+='<p id="matchstats">G: '+pgoals[index]+'<br>A: '+passists[index]+'<br>YC: '
        +pyc[index]+'<br>RC: '+prc[index]+'<br></p>';
    str+='<input type="hidden" name="p'+index+'" value="'+pid[index]+'" /></div>';
    document.getElementById('form1').innerHTML+=str;
    str='<option id="p'+index+'" value="'+pid[index]+'">'+pname[index]+'</option>';
    document.getElementById('captain').innerHTML+=str;
}

function remove_player(index)
{
    if(pid[index]==captain)
    {
        alert('Cant Remove Captain from playing 11. First change the captain and then remove.');
        return;
    }
    player=document.getElementById(pclass[index]);
    player.parentNode.removeChild(player);
    player=document.getElementById('p'+index);
    player.parentNode.removeChild(player);
    if(ppos[index]=='Goalkeeper')
    {
        pclass[numgk]=pclass[index];
        tr=document.getElementById('subg');
        tr.parentNode.removeChild(tr);
        insert_player(numgk);
        pclass[index]='subg';
        numgk=index;
    }
    else if(ppos[index]=='Defender')
    {
        pclass[numdef]=pclass[index];
        tr=document.getElementById('subd');
        tr.parentNode.removeChild(tr);
        insert_player(numdef);
        pclass[index]='subd';
        numdef=index;
    }
    else if(ppos[index]=='Midfielder')
    {
        pclass[nummid]=pclass[index];
        tr=document.getElementById('subm');
        tr.parentNode.removeChild(tr);
        insert_player(nummid);
        pclass[index]='subm';
        nummid=index;
    }
    else if(ppos[index]=='Striker')
    {
        pclass[numfor]=pclass[index];
        tr=document.getElementById('subf');
        tr.parentNode.removeChild(tr);
        insert_player(numfor);
        pclass[index]='subf';
        numfor=index;
    }
    
    str='<tr id="'+pclass[index]+'" class="two"><td><img id="logo" src="../images/teams/'
            +pteam[index]+'.png"/></td><td class="left">'+pname[index]+
            '</td><td class="left">'+ppos[index]+'</td><td>'+ppoints[index]+
            '</td></tr>';
    document.getElementById('selection').innerHTML+=str;
    
}
function check_submit()
{
    return true;
}
function change_captain()
{
    captain=document.getElementById('captain').value;
}
