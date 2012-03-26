<?php include '../common/connect.php' ?>
<?php

session_start();
if(!isset($_SESSION['user']))
    header('location:fpl_login.php');
$res=mysql_query('select user from fpl where user="'.$_SESSION['user'].'"');
if(mysql_num_rows($res)==0)
    die('cheater, rascal');
$res=mysql_query('select * from fpl where user="'.$_SESSION['user'].'"');
$old=mysql_fetch_array($res);
?>
<?php
if(isset($_REQUEST['submit']))
{
    for($i=0;$i<15;$i++)
    {
        $res=mysql_query('select * from ifl_player where id='.$_REQUEST['p'.$i]);
        if(mysql_num_rows($res)>0)
        {
            $row[$i]=mysql_fetch_array($res);
        }
        else
            die('cheater');
    }
    
    $sum=0;
    $def=0;
    $mid=0;
    $gk=0;
    $for=0;
    for($i=0;$i<15;$i++)
    {
        $sum += $row[$i]['bidding_value'];
        if($row[$i]['position']=="Defender")
            $def++;
        else if($row[$i]['position']=="Midfielder")
            $mid++;
        else if($row[$i]['position']=="Goalkeeper")
            $gk++;
        else if($row[$i]['position']=="Striker")
            $for++;
    }
    $flag=1;
    for($i=0;$i<2;$i++)
    {
        if($row[$i]['position']!="Goalkeeper")
            $flag=0;
    }
    for($i=2;$i<7;$i++)
    {
        if($row[$i]['position']!="Defender")
            $flag=0;
    }
    for($i=7;$i<12;$i++)
    {
        if($row[$i]['position']!="Midfielder")
            $flag=0;
    }
    for($i=12;$i<15;$i++)
    {
        if($row[$i]['position']!="Striker")
            $flag=0;
    }
    if(($sum<=1000) && $flag)
    {
            $flag=0;
            for($i=0;$i<15;$i++)
            {
                $chk=$row[$i]['id'];
                for($j=0; (($j<15) && ($i!=$j)) ;$j++)
                {
                    if($chk==$row[$j]['id'])
                    {
                        $flag=1;
                        break;
                    }
                }
                if($flag==1)
                    break;
            }
            if($flag==0)
            {
                if(($def==5)&&($mid==5)&&($gk==2)&&($for==3))
                {
                    for($i=0;$i<8;$i++)
                        $team[$i]=0;
                    for($i=0;$i<15;$i++)
                    {
                        if($row[$i]['team']=="Arsenal")
                                $team[0]++;
                        if($row[$i]['team']=="Barcelona")
                                $team[1]++;
                        if($row[$i]['team']=="Chelsea")
                                $team[2]++;
                        if($row[$i]['team']=="AC Milan")
                                $team[3]++;
                        if($row[$i]['team']=="Real Madrid")
                                $team[4]++;
                        if($row[$i]['team']=="Inter Milan")
                                $team[5]++;        
                        if($row[$i]['team']=="Manchester United")
                                $team[6]++;
                        if($row[$i]['team']=="Liverpool")
                                $team[7]++;
                    }
                    $flag=0;
                    for($i=0;$i<8;$i++)
                    {
                        if($team[$i]>3)
                        $flag=0;
                    }   
                    if($flag==0)
                    {
                        for($i=0;$i<15;$i++)
                            $pold[$i]=$old['p'.($i+1)];
                        for($i=0;$i<15;$i++)
                            $pnew[$i]=$row[$i]['id'];
                        $diff=array_diff($pold,$pnew);
                        $n=count($diff);
                        $points=0;
                        if($n>0)
                        {
                            if($old['transfers']==0)
                                $points=$n*4-4;
                            else    
                                $points=$n*4;
                        }
                        $points=$r['points']-$points;
                        
                        $query='update fpl set ';
                        for($i=0;$i<15;$i++)
                            $query=$query.'p'.($i+1).'='.$row[$i]['id'].',';
                        $query=$query.'money='.(1000-$sum).',';
                        $query=$query.'captain='.$row[0]['id'].',';
                        $query=$query.'points='.($old['points']+$points).',transfers='.($old['transfers']+$n).' where user="'.$_SESSION['user'].'"';
                        
                        if(!mysql_query($query))
                            die('Error '.mysql_error());
                        header('location:fpl.php');
                    }
                }
            }
    }
                                    
    
    die('cheater, select 15 properly');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript">
<?php 
include 'player.js';
echo 'function onload_func2()
    {
        onload_func();
';
echo 'points='.$old['money'].';';
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
    $pc[$i]=$id;
    echo '
    pid['.$i.']='.$p[$i]['id'].';
    pname['.$i.']=\''.$p[$i]['name'].'\';
    ppos['.$i.']=\''.$p[$i]['position'].'\';
    pvalue['.$i.']='.$p[$i]['bidding_value'].';
    pteam['.$i.']=\''.$p[$i]['team'].'\';
    pclass['.$i.']=\''.$pc[$i].'\';';
}
?>
num=15;
}
</script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" type="text/css" href="player.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>Fantasy Premier League</title>
</head>
<body id="fpl_maketeam" onload="onload_func2()">
<?php include '../common/header.php' ?>
<div id="fpl_content">
<h4>Fantasy Premier League: Transfers</h4>
<br>
<?php include 'links.php'?>
<br><br><br>
<ul class="left" style="margin-left:20px">
<li>Points will be deducted for making transfers.</li>
<li>You are allowed one free transfer, additional transfers will reduce your <b>POINTS</b> by 4 for every transfer.s</li>
<li>The number beneath a player is the value of the player.</li>
<li>Select your captain again after you do a transfer.</li>
</ul>
<br>
<h4>Current Value: <p id="points"><?php echo $old['money']?></p><br></h4>
<div id="list">
<form action="transfers.php" method="post" id="form1" onsubmit="return check15_submit()">
<img src="../images/field.png" id="field"/><br><br>
<?php
    for($i=0;$i<15;$i++)
    {
        
        $name=explode(" ",$p[$i]['name']);
        echo '<div id="'.$pc[$i].'">
        <img src="../images/shirts/'.$p[$i]['team'].'.gif"/><br>
        <p id="fieldname">'.$name[0].'</p>
        <img style="float:right;margin-top:5px" id="remove" src="../images/remove.png" onclick="remove_player('.$i.')"/>
        <p style="color:white;font-size:0.9em">'.$p[$i]['bidding_value'].'</p>
        <input type="hidden" name="p'.$i.'" value="'.$p[$i]['id'].'" /></div>';
    }
?>
<input type="submit" name="submit" value="Submit"/>
</form>
</div>
<div id="choice">
<select id="team" onchange="player_query(1)">
    <option value="any">Team: Any</option>
    <?php
        $res=mysql_query('select team_name from ifl_team');
        while($row=mysql_fetch_array($res)){
            echo '<option value="'.$row['team_name'].'">'.$row['team_name'].'</option>';
        }
    ?>
</select>&nbsp;&nbsp;
<select id="range"  onchange="player_query(1)">
    <option value="0">Value Range: Any</option>
    <option value="1">0-25</option>
    <option value="2">25-50</option>
    <option value="3">50-100</option>
    <option value="4">100-150</option>
    <option value="5">150-250</option>
    <option value="6">250+</option>
</select>&nbsp;&nbsp;
<select id="pos" onchange="player_query(1)">
    <option value="any">Position: Any</option>
    <?php
        $res=mysql_query('select * from positions');
        while($row=mysql_fetch_array($res)){
            $s='';
            if($_REQUEST['pos']==$row['position'])
                $s='selected="selected"';
            echo '<option '.$s.' value="'.$row['position'].'" '.$s.'>'.$row['position'].'</option>';
        }
    ?>
    </select><br><br>
<table id="selection">
<?php include 'player.php' ?>
</table>
</div>
<br>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
</body>	
</html>
<?php mysql_close($con); ?>


