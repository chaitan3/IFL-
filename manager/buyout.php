<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
if(!isset($_REQUEST['id']))
        die("Invalid ID");
?>
<?php include '../common/connect.php';?>
<?php
$res=mysql_query('select team,name,bidding_value,emailid from ifl_player where team<>"'.$_SESSION['manager_team'].'" and id='.$_REQUEST['id']);
    if(mysql_num_rows($res)==0)
        die("Invalid ID");
    $row=mysql_fetch_array($res);
    $player=$row['name'];
    $val=$row['bidding_value'];
    $email=$row['emailid'];

    if(isset($_REQUEST['buyoutbid']))
    {
    $invalid=1;
    if(isset($_REQUEST['exid']))
    {
        #check code if exid and val are ok
        $res=mysql_query('select team,bidding_value from ifl_player where id='.$_REQUEST['exid']);
        $row=mysql_fetch_array($res);
        $res2=mysql_query('select value,value_bids from ifl_team where team_name="'.$_SESSION['manager_team'].'"');
        $r=mysql_fetch_array($res2);
        if($_REQUEST['choice']==1)
        {
            $xval=150;
            $flag2=1;
        }
        else
        {
            $xval=75;
            if($row['bidding_value']>($val/2))
                $flag2=1;
            else
                $flag2=0;
        }
            
        $value=($r['value']-$r['value_bids'])-$xval;
        if(($value>=0) && $flag2)
        {
            $res=mysql_query('select * from '.$_SESSION['manager_id'].'_bids where player='.$_REQUEST['id']);
            if(mysql_num_rows($res)==0)
            {
                $res=mysql_query('select * from '.$_SESSION['manager_id'].'_bids');
                $flag=1;
                while($bidp=mysql_fetch_array($res))
                {
                    $res2=mysql_query('select player from bids_'.$bidp['player'].' where player='.$_REQUEST['exid']);
                    if(mysql_num_rows($res2)!=0)
                    {
                        $flag=0;
                        break;
                    }
                }
                if($flag)
                {
                    $query='update ifl_team set value_bids=value_bids+'.($xval+$val).' where id='.$_SESSION['manager_id'];
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $query='insert into bids_'.$_REQUEST['id'].' (player,value,team,status) values('.$_REQUEST['exid'].','.($xval+$val).',"'.$_SESSION['manager_team'].'",2)';
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $query='insert into '.$_SESSION['manager_id'].'_bids values('.$_REQUEST['id'].') ';
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $invalid=2;
                }
            }
        }
    }
if($invalid==2)
        header('location:sendmail.php?id='.$_REQUEST['id']);
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<title>IFL Manager</title>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" type="text/css" href="../css/manager.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
</head>

<body onload="onload_func()">

<?php include '../common/header.php' ?>
<?php include '../common/sidebar.php' ?>
<div id="content">

<?php include 'transfers.php';
    if($invalid==1)
        echo '<b>INVALID BID</b><br /><br />';
 ?>
 <?php echo 'Bid for <b>'.$player.'</b>'.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Value:'.$val; ?><br /><br />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="choice" style="text-align:left; margin-left:5%;">
    <input onchange="document.choice.go.click()" <?php  if(!isset($_REQUEST['buyout']) || $_REQUEST['buyout']==1) echo 'checked'?> type="radio" name="buyout" value="1"> 150 + any player<br>
    <input onchange="document.choice.go.click()" <?php if($_REQUEST['buyout']==2) echo 'checked'?> type="radio" name="buyout" value="2"> 75 + any player(atleast 50%of player bought)
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>"/>&nbsp;&nbsp;&nbsp;
    <input type ="submit" name="go" value="Go"><br><br>
</form>    
 
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    
Select an Exchange Player<br /><br />
<table>
<tr>
    <th id="tl">&nbsp;</th>
    <th class="left" >Player</th>
    <th class="left" >Postion</th>
    <th id="tr">Value</th>
</tr>
<?php  
    if($_REQUEST['buyout']==2)
    {
        $choice=2;
        $res=mysql_query('select bidding_value from ifl_player where id='.$_REQUEST['id']);
        $row=mysql_fetch_array($res);
        $res=mysql_query('select id,name,position,bidding_value,for_sale from ifl_player where team="'.$_SESSION['manager_team'].'" and bidding_value > 0.5*'.$row['bidding_value'].' order by bidding_value');
    }
    else
    {
        $res=mysql_query('select id,name,position,bidding_value,for_sale from ifl_player where team="'.$_SESSION['manager_team'].'" order by bidding_value');
        $choice=1;
    }
    $mbids=mysql_query('select * from '.$_SESSION['manager_id'].'_bids');
    $i=1;
    while($row=mysql_fetch_array($res)){
            $flag=1;
            if(mysql_num_rows($mbids)>0)
            {
                mysql_data_seek($mbids,0);
                while($bidp=mysql_fetch_array($mbids))
                {
                    $res2=mysql_query('select player from bids_'.$bidp['player'].' where player='.$row['id']);
                    if(mysql_num_rows($res2)!=0)
                    {
                        $flag=0;
                        break;
                    }
                }
            }
            if($flag){
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
            echo '<tr class="'.$c.'">';
            echo '<td><input type="radio" name="exid" value="'.$row['id'].'" /></td>';
            echo '<td class="left" >'.$row['name'].'</td>';
            echo '<td class="left" >'.$row['position'].'</td>'; 
            echo '<td>'.$row['bidding_value'].'</td>'; 
            echo '</tr>';
        }
    }
?>
<tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
</table><br />
<input type="submit" name="buyoutbid" value="Send a Buyout Bid" />
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
<input type="hidden" name="choice" value="<?php echo $choice; ?>" />
</form>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con); ?>
</body>
</html>


