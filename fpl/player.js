var points=1000;
var offset=0;
var num=0;
var pid=new Array(15);
var pname=new Array(15);
var ppos=new Array(15);
var pvalue=new Array(15);
var pteam=new Array(15);
var pclass=new Array(15);
 
function player_query(off)
{
    if(off)
        offset=0;
    query=new XMLHttpRequest();
    team=document.getElementById('team').value;
    value=document.getElementById('range').value;
    pos=document.getElementById('pos').value;
    url="player.php?team="+team+"&range="+value+"&pos="+pos+"&offset="+offset;
    query.open("GET",url,false);
    query.send(null);
    document.getElementById('selection').innerHTML=query.responseText;
}
function player_query_prev()
{
    offset--;
    player_query(0);
}
function player_query_next()
{
    offset++;
    player_query(0);
}
function insert_player(index)
{
    sname=pname[index].split(" ");
    
    str='<div id="'+pclass[index]+'">';
    str+='<img src="../images/shirts/'+pteam[index]+'.gif"/><br>';
    str+='<p id="fieldname">'+sname[0]+'</p>';
    str+='<img style="float:right;margin-top:5px" id="remove" src="../images/remove.png" onclick="remove_player('+index+')"/>';
    str+='<p style="color:white;font-size:0.9em">'+pvalue[index]+'</p>';
    str+='<input type="hidden" name="p'+index+'" value="'+pid[index]+'" /></div>';
    document.getElementById('form1').innerHTML+=str;
}

//points condition
//15 players condition
//player can not be added once he has already been added
//you cannot have more than three players from one team
//you cannot have more than 5 defenders, 5 midfielders, 2 gks, 3forwards

function add_player(id,name,pos,value,team)
{
    
    if(points-value>=0) 
    {
        if(num<15)
        {
            flag=1;
            for(i=0;i<15;i++)
            {
                if(pid[i]==id)
                    flag=0;
            }
            if(flag)
            {
                numteam=0;
                for(i=0;i<15;i++)
                {
                    if(pteam[i]==team)
                        numteam++;
                }
                if(numteam<3)
                {
                    flag=0;
                    if(pos=="Goalkeeper")
                    {
                        for(i=0;i<2;i++)
                        {
                            if(pid[i]==0)
                            {
                                
                                index=i;
                                pclass[index]='gk'+i;
                                flag=1;
                                break;
                            }
                        }
                    }
                    else if(pos=="Defender")
                    {
                        for(i=2;i<7;i++)
                        {
                            if(pid[i]==0)
                            {
                                index=i;
                                pclass[index]='def'+(i-2);
                                flag=1;
                                break;
                            }
                        }
                    }
                    else if(pos=="Midfielder")
                    {
                        for(i=7;i<12;i++)
                        {
                            if(pid[i]==0)
                            {
                                index=i;
                                pclass[index]='mid'+(i-7);
                                flag=1;
                                break;
                            }
                        }
                    }
                    else if(pos=="Striker")
                    {
                        
                        for(i=12;i<15;i++)
                        {
                            
                            if(pid[i]==0)
                            {
                                index=i;
                                pclass[index]='for'+(i-12);
                                flag=1;
                                break;
                            }
                        }
                    }
                    if(flag)
                    {
                        pid[index]=id;
                        pname[index]=name;
                        ppos[index]=pos;
                        pvalue[index]=value;
                        pteam[index]=team;
                        
                        points-=value;
                        document.getElementById('points').innerHTML=points;
                        insert_player(index);
                        num++;
                    }
                    else
                        alert('You cannot have more than 5 defenders, 5 midfielders, 2 gks, 3forwards');
                }
                else
                    alert('You can only select 3 players from the same team');
                
            }
            else
                alert('Player already added');
        }
        else
            alert('You have already selected 15 players');
    }
    else
        alert('Not Enough Points');
}
function remove_player(index)
{
    points+=pvalue[index];
    document.getElementById('points').innerHTML=points;
    form1=document.getElementById('form1');
    player=document.getElementById(pclass[index]);
    form1.removeChild(player);
    pid[index]=0;
    pname[index]="";
    ppos[index]="";
    pvalue[index]=0;
    pteam[index]="";
    num--;
}
function check15_submit()
{
    if(num==15)
        return true;
    else
    {
        alert('Select 15 players.');
        return false;
    }
}
