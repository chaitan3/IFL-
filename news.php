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
<div id="content">
<br>
<h3>News Section</h3>
<br>
<ul class="left" style="margin-left:20px">
<?php
    $res=mysql_query('select * from updates order by id desc');
    while($row=mysql_fetch_array($res))
    {
        echo '<li>'.$row['string'].'</li><br>';
    }
?>
</ul>

</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php'?>
</body>

</html>		
<?php mysql_close($con); ?>

