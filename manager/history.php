<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
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
<h3>Transfer History</h3>
<br>
<ul class="left" style="margin-left:20px">
<?php 
    $res=mysql_query('select * from updates where type="transfer" order by id desc');
    while($row=mysql_fetch_array($res))
        { echo '<li>'.$row['string'].'</li><br>';
    }
?>  
</ul> 
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
</body>
</html>
<?php mysql_close($con); ?>




 
