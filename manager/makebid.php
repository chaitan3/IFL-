<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
if(!isset($_REQUEST['id']))
        die("Invalid ID");
?>
<?php include '../common/connect.php';?>
<?php
$res=mysql_query('select team,name,bidding_value from ifl_player where team<>"'.$_SESSION['manager_team'].'" and id='.$_REQUEST['id']);
    if(mysql_num_rows($res)==0)
        die("Invalid ID");
    $row=mysql_fetch_array($res);
    $player=$row['name'];
    $val=$row['bidding_value'];
   

    if(isset($_REQUEST['makebid']))
    {
    
    if(ctype_digit($_REQUEST['val']) && isset($_REQUEST['exid']))
    {
        #check code if exid and val are ok
        $res=mysql_query('select team,bidding_value from ifl_player where id='.$_REQUEST['exid']);
        $row=mysql_fetch_array($res);
        $res2=mysql_query('select value,value_bids from ifl_team where team_name="'.$_SESSION['manager_team'].'"');
        $r=mysql_fetch_array($res2);
        $value=($r['value']-$r['value_bids'])-$_REQUEST['val'];
        
        if(($value>=0) && ($_REQUEST['val']>($val-$row['bidding_value'])/2))
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
                    $query='update ifl_team set value_bids=value_bids+'.$_REQUEST['val'].' where id='.$_SESSION['manager_id'];
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $query='insert into bids_'.$_REQUEST['id'].' (player,value,team) values('.$_REQUEST['exid'].','.$_REQUEST['val'].',"'.$_SESSION['manager_team'].'")';
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $query='insert into '.$_SESSION['manager_id'].'_bids values('.$_REQUEST['id'].') ';
                    if(!mysql_query($query))
                        die('Error '.mysql_error());
                    $invalid=2;
                }
                else
                    $invalid=1;
            }
            else
                $invalid=1;
        }
        else
                $invalid=1;
    }
    else
        $invalid=1;
if($invalid==2)
        header('location:bids.php');
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php echo 'Bid for <b>'.$player.'</b>'.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Value:'.$val; ?><br /><br />
Select an Exchange Player<br /><br />
<table>
<tr>
    <th id="tl">&nbsp;</th>
    <th class="left" >Player</th>
    <th class="left" >Postion</th>
    <th id="tr">Value</th>
</tr>
<?php 
    $res=mysql_query('select id,name,position,bidding_value,for_sale from ifl_player where team="'.$_SESSION['manager_team'].'" order by bidding_value');
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
Exchange Value: <input type="text" name="val" />
<input type="submit" name="makebid" value="Make a Bid" />
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
</form>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con); ?>
</body>
</html>


