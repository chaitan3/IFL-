<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
<?php
if(!isset($_REQUEST['id']))
    die('rascal');
$res=mysql_query('select emailid from ifl_player where id='.$_REQUEST['id']);
$row=mysql_fetch_array($res);
$email=$row['emailid'];
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
<br>
<p>
Please send an email to <?php echo $email;?> detailing your bid so that the player can approve your buyout.<br>
The buyout won't be approved unless you do so.
</p>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con); ?>
</body>
</html>
