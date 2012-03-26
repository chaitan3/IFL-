<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
<?php
if(!isset($_REQUEST['reject']) && !isset($_REQUEST['areject']))
    die('rascal');

if(isset($_REQUEST['areject']))
{
    $res=mysql_query('select team from ifl_player where id='.$_REQUEST['player']);
    $team=mysql_fetch_array($res);
    if($team['team']!=$_SESSION['manager_team'])
        die('rascal');
    $query='update bids_'.$_REQUEST['player'].' set status=1,reason="'.$_REQUEST['reason'].'" where id='.$_REQUEST['bid'];
    if(!mysql_query($query))
        die('Error: '.mysql_error());
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
Specify a reason for rejecting the bid
<br><br><br>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<textarea cols="40" rows="5" name="reason"></textarea>
<br><br>
<input type="submit" value="Reject Bid" name="areject">
<input type="hidden" value="<?php echo $_REQUEST['bid']?>" name="bid" />
<input type="hidden" value="<?php echo $_REQUEST['player']?>" name="player" />
</form>
    
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con); ?>
</body>
</html>
