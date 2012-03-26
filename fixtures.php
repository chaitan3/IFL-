<?php include 'common/connect.php' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="common/common.js"></script>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="shortcut icon" href="images/ifl.jpg"/>
<title>Institute Football League</title>
</head>

<?php 
    $val=$_REQUEST['day'];
    if (!isset($_REQUEST['day']))
        $val="m1";
?>

<body onload="onload_func()">
<?php include 'common/header.php' ?>
<?php include 'common/sidebar.php' ?>
<div id="content">
<br><br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="dform">
    <select name="day" onChange="document.dform.go.click()">

<?php
    $res=mysql_query('select * from matches_info');

    while($row=mysql_fetch_array($res)){
        $s='';
        if($row['code']==$_REQUEST['day'])
            $s='selected="selected"';
        echo '<option '.$s.' value="'.$row['code'].'">'.$row['name'].' - '.$row['date'].'</option>';
    }

?>

    </select>
    <input type="submit" value="Go" name="go" />
</form><br/><br/>
<table>
<tr class="one"><th id="tl">&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th id="tr">&nbsp;</th></tr>
<?php 
    $res=mysql_query('select * from matches where code="'.$val.'"');
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
        echo '<tr id="mlink" onclick="location.href=\''.$row['link'].'\'" class="'.$c.'">
                <td><img id="logo" src="images/teams/'.$row['team1'].'.png" alt="No logo" /></td>
                <td class="left">'.$row['team1'].'</td>
                <td>'.$row['goal1'].'</td>
                <td>-</td>
                <td>'.$row['goal2'].'</td>
                <td class="right">'.$row['team2'].'</td>
                <td><img id="logo" src="images/teams/'.$row['team2'].'.png" alt="No logo" /></td>
                <td>'.$row['time'].'</td>
                </tr>';
    }
?>
<tr class="one"><td id="bl">&nbsp;</td><td colspan="6" style="font-size:0.85em">Click on the row to see the Match Report</td><td id="br">&nbsp;</td></tr>
</table>
</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php' ?>
</body>

</html>
<?php mysql_close($con); ?>
