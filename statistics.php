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
    $val=$_REQUEST['info'];
    if (!isset($_REQUEST['info']))
        $val=0;
?>

<body onload="onload_func()">
<?php include 'common/header.php' ?>
<?php include 'common/sidebar.php' ?>
<div id="content">
<form action="profile.php" method="post" >
    Search for a Player: <input class="profile" type="text" name="string" />
    <input class="profile" type="submit" value="Search" name="search" />
</form><br>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="sform">
Statistics:&nbsp;&nbsp;&nbsp;
    <select name="info" onChange="document.sform.go.click()">
        <option value="0" <?php if($val==0) echo 'selected="selected"';?>>Team Standings </option>
        <option value="1" <?php if($val==1) echo 'selected="selected"';?>>Players: Top Scorers </option>
        <option value="2" <?php if($val==2) echo 'selected="selected"';?>>Players: Red/Yellow Cards </option>
        <option value="3" <?php if($val==3) echo 'selected="selected"';?>>Players: Bidding Value </option>
    </select>
    <input type="submit" value="Go" name="go" />
</form><br/>
<?php
if($val>0)
{
    echo '<p id="help">G:Goals A:Assists RC:Red Cards YC:Yellow Cards</p><br>';
}?>
<table>

<?php 
    if($val==0)
    {
        $res=mysql_query('select * from ifl_team order by points desc,won desc, goals_for desc, goals_against asc');
        echo '<tr>
                <th id="tl"></th>
                <th class="left">Team</th>
                <th>P</th>
                <th>W</th>
                <th>L</th>
                <th>D</th>
                <th>GF</th>
                <th>GA</th>
                <th id="tr">Points</th>
                </tr>';
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
            echo '<tr class="'.$c.'">
                    <td><img id="logo" src="images/teams/'.$row['team_name'].'.png" alt="Image not found" /></td>
                    <td class="left">'.$row['team_name'].'</td>
                    <td>'.$row['played'].'</td>
                    <td>'.$row['won'].'</td>
                    <td>'.$row['lost'].'</td>
                    <td>'.$row['drawn'].'</td>
                    <td>'.$row['goals_for'].'</td>
                    <td>'.$row['goals_against'].'</td>
                    <td>'.$row['points'].'</td>
                    </tr>';
        }
    }
    else
    {
        if($val==1)
            $order='goals_scored desc';
        else if($val==2)
            $order='red_cards desc, yellow_cards desc';
        else if($val==3)
            $order='bidding_value desc';
        else
            $order='matches desc';
        $res=mysql_query('select * from ifl_player order by '.$order);
        echo '<tr>
                <th id="tl">Player</th>
                <th  class="left">Team</th>
                <th class="left">Position</th>
                <th>G</th>
                <th>A</th>
                <th>RC</th>
                <th>YC</th>
                <th id="tr">Bid Value</th>
                </tr>';
        $i=0;
        $t=1;
        while(($row=mysql_fetch_array($res)) && ($i<10)){
            if($t)
            {
                $c='one';
                $t=0;
            }
            else
            {
                $c='two';
                $t=1;
            }
            echo '<tr class="'.$c.'">
                    <td class="left">'.$row['name'].'</td>
                    <td class="left">'.$row['team'].'</td>
                    <td class="left">'.$row['position'].'</td>
                    <td>'.$row['goals_scored'].'</td>
                    <td>'.$row['assists'].'</td>
                    <td>'.$row['red_cards'].'</td>
                    <td>'.$row['yellow_cards'].'</td>
                    <td>'.$row['bidding_value'].'</td>
                    </tr>';
            $i=$i+1;
        }
    }
?>
<tr class="one"><td id="bl">&nbsp;</td><?php if($val==0) echo '<td>&nbsp;</td>'; ?><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
</table>
</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php' ?>
</body>

</html>
<?php mysql_close($con); ?>
