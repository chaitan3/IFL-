<?php include '../common/connect.php' ?>
<?php
session_start();
if(!isset($_SESSION['user']))
    header('location:fpl_login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>Fantasy Premier League</title>
</head>

    
    <body onload="onload_func()">
    <?php include '../common/header.php' ?>
    <div id="fpl_content">
    <h4>Fantasy Premier League: Rules</h4><br>
    <?php include 'links.php'?>
    <br><br>
    <ul class="left" style="margin-left:20px">
    <li>DEFENSE </li>
    <li>For clean sheets kept by your defenders or goalkeepers, you will earn 4 points</li>
    <li>If a midfielder keeps a clean sheet, you will earn 1 point</li>
    <li>For every three saves by your keeper, you will earn 1 point</li>
    <li>ATTACK</li>
    <li>For every assist by a player on your team, you earn 3 points</li>
    <li>For every goal scored by a defender or goalkeeper, you earn 6 points</li>
    <li>For every goal scored by a midfielder, you earn 5 points</li>
    <li>For every goal scored by a forward, you earn 4 points</li>
    <li>You have a captain in your team, you will earn double the points he gives you if he were a normal player</li>
    <li>Points of Midfielder/Goalkeeper/Defender will be deducted for conceding goals.</li>
    </ul>
    </div>
    <?php include '../common/nav.php' ?>
    <?php include '../common/footer.php' ?>
    </body>
	
</html>
<?php mysql_close($con); ?>




