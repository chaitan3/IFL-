<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
<?php
    if(isset($_REQUEST['update']))
    {
        $res=mysql_query('select id,for_sale from ifl_player where team="'.$_SESSION['manager_team'].'"');
        while($row=mysql_fetch_array($res))
        {
            if(($row['for_sale']==0) && in_array($row['id'],$_REQUEST['sale']))
            {
                $query='update ifl_player set for_sale=1 where id='.$row['id'];
                if(!mysql_query($query))
                    die( "Error: " . mysql_error());
            }
            else if(($row['for_sale']==1) && in_array($row['id'],$_REQUEST['sale'])==false)
            {
                $query='update ifl_player set for_sale=0 where id='.$row['id'];
                if(!mysql_query($query))
                    die( "Error: " . mysql_error());
            }
        }
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
If your team's value was below 400 at the start of the transfer window have to get their points above 400 before making any transfer.<br><br>
A player can't be transferred more than once in a transfer window.<br><br>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<table id="players">
<tr>
    <th class="left" id="tl">Player</th>
    <th class="left">Postion</th>
    <th class="left">Value</th>
    <th>For Sale</th>
    <th>&nbsp;</th>
    <th id="tr">Bids(N)</th>
</tr>

<?php
    $res=mysql_query('select id,name,position,bidding_value,for_sale from ifl_player where team="'.$_SESSION['manager_team'].'" order by bidding_value desc');
    $i=1;
    while($row=mysql_fetch_array($res)){
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
            echo '<td class="left"><a href="/profile.php?id='.urlencode($row['id']).'">'.$row['name'].'</a></td>';
            echo '<td class="left">'.$row['position'].'</td>'; 
            echo '<td>'.$row['bidding_value'].'</td>'; 
            $s='';
            if($row['for_sale']==1)
                $s='checked';
            echo '<td><input type="checkbox" '.$s.' name="sale[]" value="'.$row['id'].'"/></td>';
            echo '<td><a class="button" href="approvebid.php?id='.urlencode($row['id']).'"><span>Check Bids</span></a></td>';
            $num=mysql_query('select id from bids_'.$row['id'].' where status<>1');
            echo '<td>'.mysql_num_rows($num).'</td>';
            echo '</tr>';
        }
?>
<tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
</table>
<br/>
<input type="submit" name="update" value="Update" />
</form>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
</body>
</html>
<?php mysql_close($con); ?>
