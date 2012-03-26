<?php include '../common/connect.php' ?>
<?php
session_start();
if(!isset($_SESSION['user']))
    header('location:fpl_login.php');
$res=mysql_query('select * from fpl where user="'.$_SESSION['user'].'"');
if(mysql_num_rows($res)==0)
    header('location:maketeam.php');
$old=mysql_fetch_array($res);
?>
<?php
if(isset($_REQUEST['update']) && isset($_REQUEST['captain']))
{   
    $cnt=0;
    $scnt=0;
    for($i=0;$i<15;$i++)
    {
        $pold[$i]=$old['p'.($i+1)];
        if(isset($_REQUEST['p'.$i]))
        {
            $playing[$cnt]=$_REQUEST['p'.$i];
            $cnt++;
        }
        else
        {
            $sub[$scnt]=$pold[$i];
            $scnt++;
        }
    }
    $in=0;
    $flag=0;
    for($i=0;$i<11;$i++)
    {
        if($playing[$i]==$_REQUEST['captain'])
        $flag=1;
        for($j=0;$j<15;$j++)
        {
            if($playing[$i]==$pold[$j])
            {
                $in++;
                break;
            }
        }
    }
    if($cnt==11 && $in==11 && $flag)
    {
        $query='update fpl set ';
        $query=$query.'p'.(1).'='.$playing[0].',';
        $query=$query.'p'.(2).'='.$sub[0].',';
        $query=$query.'p'.(3).'='.$playing[1].',';
        $query=$query.'p'.(4).'='.$playing[2].',';
        $query=$query.'p'.(5).'='.$playing[3].',';
        $query=$query.'p'.(6).'='.$playing[4].',';
        $query=$query.'p'.(7).'='.$sub[1].',';
        $query=$query.'p'.(8).'='.$playing[5].',';
        $query=$query.'p'.(9).'='.$playing[6].',';
        $query=$query.'p'.(10).'='.$playing[7].',';
        $query=$query.'p'.(11).'='.$playing[8].',';
        $query=$query.'p'.(12).'='.$sub[2].',';
        $query=$query.'p'.(13).'='.$playing[9].',';
        $query=$query.'p'.(14).'='.$playing[10].',';
        $query=$query.'p'.(15).'='.$sub[3].',';
        $query=$query.'captain='.$_REQUEST['captain'];
        $query=$query.' where user="'.$_SESSION['user'].'"';
        if(!mysql_query($query))
            die('Error '.mysql_error());
        header('location:fpl.php');
    }
    die('cheater rascal');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript">
var pid=new Array(15);
var pname=new Array(15);
var ppos=new Array(15);
var ppoints=new Array(15);
var pteam=new Array(15);
var pclass=new Array(15);
var passists=new Array(15);
var pgoals=new Array(15);
var prc=new Array(15);
var pyc=new Array(15);
<?php 
echo 'function onload_func2()
    {
        onload_func();
';
for($i=0;$i<15;$i++)
{
    $res=mysql_query('select * from ifl_player where id='.$old['p'.($i+1)]);
    $p[$i]=mysql_fetch_array($res);
    if($i<2)
            $id='gk'.$i;
    else if($i<7)
            $id='def'.($i-2);
    else if($i<12)
            $id='mid'.($i-7);
    else if($i<15)
            $id='for'.($i-12);
    if($i==1)
        $id='subg';
    else if($i==6)
        $id='subd';
    else if($i==11)
        $id='subm';
    else if($i==14)
        $id='subf';
    $pc[$i]=$id;
    echo '
    pid['.$i.']='.$p[$i]['id'].';
    pname['.$i.']=\''.$p[$i]['name'].'\';
    ppos['.$i.']=\''.$p[$i]['position'].'\';
    ppoints['.$i.']=\''.$p[$i]['points'].'\';
    pteam['.$i.']=\''.$p[$i]['team'].'\';
    pclass['.$i.']=\''.$pc[$i].'\';
    pgoals['.$i.']='.$p[$i]['last_goals'].';
    passists['.$i.']='.$p[$i]['last_assists'].';
    pyc['.$i.']='.$p[$i]['last_yellowcards'].';
    prc['.$i.']='.$p[$i]['last_redcards'].';';
}
echo 'captain='.$old['captain'].';';
?>
numgk=1;
numdef=6;
nummid=11;
numfor=14;
}
<?php include 'fpl.js'; ?>

</script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" type="text/css" href="fpl.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>Fantasy Premier League</title>
</head>
<body id="fpl_maketeam" onload="onload_func2()">
<?php include '../common/header.php' ?>
<div id="fpl_content">
<h4>Fantasy Premier League</h4><br>
<?php include 'links.php'?>
<br><br><br>
<ul class="left" style="margin-left:20px">
<li>Select the playing 11 and a captain.</li>
<li>Click on update after selection.</li>
<li>The number beneath a player represents his points.</li>
</ul>
<br>
<h4>Current Points: <p id="points"><?php echo $old['points']?></p><br></h4>
<p style="float:left;margin-left:100px">Playing 11</p>
<p style="float:left;margin-left:250px">Substitutes</p>
<br><br>

<div id="list">
<form action="fpl.php" method="post" id="form1" onsubmit="return check_submit()">
<img src="../images/field.png" id="field"/><br><br>
<?php
    for($i=0;($i<15);$i++)
    {
        if(($i==1)||($i==6)||($i==11)||($i==14))
            continue;
        $name=explode(" ",$p[$i]['name']);
        echo '<div id="'.$pc[$i].'">
        <img src="../images/shirts/'.$p[$i]['team'].'.gif"/><br>
        <p id="fieldname">'.$name[0].'</p>
        <img style="float:right;margin-top:5px" id="remove" src="../images/remove.png" onclick="remove_player('.$i.')"/>
        <p style="color:white;font-size:0.9em">'.$p[$i]['points'].'</p>
        <p id="matchstats">
        G: '.$p[$i]['last_goals'].'<br>
        A: '.$p[$i]['last_assists'].'<br>
        YC: '.$p[$i]['last_yellowcards'].'<br>
        RC: '.$p[$i]['last_redcards'].'<br> 
        </p>
        <input type="hidden" name="p'.$i.'" value="'.$p[$i]['id'].'" /></div>';
    }
?>
</div>
<div id="choice">
<table id="selection">
    <th id="tl">Team</th><th class="left">Player</th><th class="left">Position</th><th id="tr">Points</th>
<?
    $ar=array(1,6,11,14);
    for( $i=0; $i<4; $i++)
    {
        echo '<tr id="'.$pc[$ar[$i]].'" class="two"><td><img id="logo" src="../images/teams/'
            .$p[$ar[$i]]['team'].'.png"/></td><td class="left">'.$p[$ar[$i]]['name'].
            '</td><td class="left">' .$p[$ar[$i]]['position'].'</td><td>'.$p[$ar[$i]]['points'].'</td>
            </tr>';
        
    }
 ?>           
</table>
<br>
Choose Captain: 
<select name="captain" id="captain" onchange="change_captain()">
<?php
for($i=0;($i<15);$i++)
{
    if($pc[$i][0]!='s')
    {
        if($p[$i]['id']==$old['captain'])
            echo '<option id="p'.$i.'" selected="selected" value="'.$p[$i]['id'].'">'.$p[$i]['name'].'</option>';
        else
            echo '<option id="p'.$i.'" value="'.$p[$i]['id'].'">'.$p[$i]['name'].'</option>';
    }
}
?>
</select>
<br><br>
<input style="margin-left:100px;" type="submit" name="update" value="Update"/>
</form>
</div>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
</body>	
</html>
<?php mysql_close($con); ?>
