<?php include 'common/connect.php' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="common/common.js"></script>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/clubs.css" />
<link rel="shortcut icon" href="images/ifl.jpg"/>
<title>Institute Football League</title>
</head>


<body onload="onload_func()">
<?php include 'common/header.php' ?>
<?php include 'common/sidebar.php' ?>
<div id="content" style="text-align:left;padding:10px">
<b>The Vision</b><br>
<p>The Institute Football League aims at increasing the participation of the students of IIT  Bombay in football. It acts as a common forum where  players, managers, owners and fans come together and contribute to drive the beautiful game to its highest levels</p><br>

<b>Format Of the League</b>
<br>
<li>8 Teams
<br>
	(a) Real Madrid<br>
	(b) AC Milan<br>
	(c) Liverpool<br>
	(d) Inter Milan<br>
	(e) Barcelona<br>
	(f) Manchester United<br>
	(g) Arsenal<br>
	(h) Chelsea<br></li>
<br>
<li>These teams play a round robin competition with 7 rounds of matches.</li>
<li>Each round of 4 matches is called a Match-day.</li>
<br>
<li>The points to determine the winner is :<br>
	3 for the winners<br>
	1 each for a draw<br>
	0 for the loss<br></li>
<br>
<li>Team with maximum points -> Champions</li>
<li>In case of a tie IFL will refer to the Goal Difference, Goals scored and then
the Head to Head record.</li>
<li>If everything is tied, there will be a penalty shootout between the two tied 
sides</li>
<li>The schedule once final cannot be changed unless the IFL Organisers decide otherwise</li>
<br>
</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php'?>
</body>

</html>		
<?php mysql_close($con); ?>
