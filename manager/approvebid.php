<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
<?php



if(isset($_REQUEST['approve']))
{
    $res=mysql_query('select * from bids_'.$_REQUEST['player'].' where id='.$_REQUEST['bid']);
    $bid=mysql_fetch_array($res);
    
    $res=mysql_query('select * from ifl_player where id='.$_REQUEST['player']);
    $p2=mysql_fetch_array($res);
    if($p2['team']!=$_SESSION['manager_team'])
        die('rascal');
    
    $res=mysql_query('select * from ifl_player where id='.$bid['player']);
    $p1=mysql_fetch_array($res);
    
    $res=mysql_query('select value,asset,id from ifl_team where team_name="'.$p1['team'].'"');
    $team1=mysql_fetch_array($res);
    $res=mysql_query('select value,asset from ifl_team where team_name="'.$p2['team'].'"');
    $team2=mysql_fetch_array($res);
    
    $team1['value']=$team1['value']-$bid['value'];
    $team2['value']=$team2['value']+$bid['value'];
    $team1['asset']=$team1['asset']-$p1['bidding_value']+$p2['bidding_value'];
    $team2['asset']=$team2['asset']-$p2['bidding_value']+$p1['bidding_value'];
    
    $query='update ifl_player set for_sale=0,team="'.$p1['team'].'" where id='.$p2['id'];
    if(!mysql_query($query))
        die('Error: '.mysql_error());
    $query='update ifl_player set for_sale=0,team="'.$p2['team'].'" where id='.$p1['id'];
    if(!mysql_query($query))
        die('Error: '.mysql_error());
        
    $query='update ifl_team set value_bids=value_bids-'.$bid['value'].',asset='.$team1['asset'].' ,value='.$team1['value'].' where team_name="'.$p1['team'].'"';
    if(!mysql_query($query))
        die('Error: '.mysql_error());
    $query='update ifl_team set asset='.$team2['asset'].' ,value='.$team2['value'].' where team_name="'.$p2['team'].'"';
    if(!mysql_query($query))
        die('Error: '.mysql_error());
    mysql_query('delete from bids_'.$p2['id']);
    mysql_query('delete from bids_'.$p1['id']);
    for($t=0;$t<8;$t++)
        mysql_query('delete from '.$t.'_bids where player='.$p1['id']);
    mysql_query('delete from '.$team1['id'].'_bids where player='.$p2['id']);
    
    $update=$p2['name'].' transferred from '.$p2['team'].' to '.$p1['team'].' in exchange for '.$p1['name'].' and '.$bid['value'].' points';
    mysql_query('insert into updates (string,type) values("'.$update.'","transfer")');
    header('location:manager.php');
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" type="text/css" href="../css/manager.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>IFL Manager</title>
</head>

<body onload="onload_func()">

<?php include '../common/header.php' ?>
<?php include '../common/sidebar.php' ?>
<div id="content">

<?php include 'transfers.php' ?>

<?php

if(isset($_REQUEST['id']))
{
    $res=mysql_query('select name,position,team,bidding_value from ifl_player where id='.$_REQUEST['id']);
    $row=mysql_fetch_array($res);
    if($row['team']!=$_SESSION['manager_team'])
        die('Invalid ID');
    echo 'Bids for <b>';
    echo $row['name'].'</b> (';
    echo $row['position'].',Value=';
    echo $row['bidding_value'].')<br /><br />';
    echo '<table>
              <tr>
                  <th class="left" id="tl">Player</th>
                  <th class="left">Position</th>
                  <th class="left">Team</th>
                  <th>Player Value</th>
                  <th>Exchange Value</th>
                  <th>&nbsp;</th>
                  <th id="tr">&nbsp;</th>
              </tr>';
    $res=mysql_query('select * from bids_'.$_REQUEST['id'].' where status<>1');
    $i=1;
    while($row=mysql_fetch_array($res))
    {
        if($i)
            {
                $c='one';
                $i=0;
            }
            else
            {
                $c='two';
                $i=1;
            }
        $q=mysql_query('select name,position,team,bidding_value from ifl_player where id='.$row['player']);
        $player=mysql_fetch_array($q);
        echo '<tr class="'.$c.'">
                  <td class="left">'.$player['name'].'</td>
                  <td class="left">'.$player['position'].'</td>
                  <td class="left">'.$player['team'].'</td>
                  <td>'.$player['bidding_value'].'</td>
                  <td>'.$row['value'].'</td>';
                  if($row['status']!=0)
                  echo '
                  <td>
                  <form action="'.$_SERVER['PHP_SELF'].'" method="post">
                  <input type="submit" value="Approve Bid" name="approve"/>
                  <input type="hidden" value="'.$row['id'].'" name="bid" />
                  <input type="hidden" value="'.$_REQUEST['id'].'" name="player" />
                  </form>
                  
                  </td>
                  <td>
                  <form action="rejectbid.php" method="post">
                  <input type="submit" value="Reject Bid" name="reject"/>
                  <input type="hidden" value="'.$row['id'].'" name="bid" />
                  <input type="hidden" value="'.$_REQUEST['id'].'" name="player" />
                  </form>
                  
                  </td>';
                  else
                  echo '<td>Buyout</td><td>Bid</td>';
              echo '</tr>';
    }
    echo '<tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>';
    echo '</table>';
}
else
    echo 'Specify an ID';
?>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con); ?>
</body>
</html>


