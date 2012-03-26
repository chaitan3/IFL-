<?php include '../common/connect.php' ?>
<?php
session_start();
if(!isset($_SESSION['user']))
    header('location:fpl_login.php');
$res=mysql_query('select user from fpl where user="'.$_SESSION['user'].'"');
if(mysql_num_rows($res)==0)
    die('cheater, rascal');
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
    <h4>Fantasy Premier League: Leaderboard</h4><br>
    <?php include 'links.php'?>
    
    <?php
        $res=mysql_query('select user,points from fpl order by points desc');
        $rank=0;
        while($row=mysql_fetch_array($res))
        {
            $rank++;
            if($row['user']==$_SESSION['user'])
                break;
        }
    ?>
    <br><br><br>
    <p>Your Current Rank: <?php echo $rank;?></p>
    <br><br>
    <table id="ranking">
        <tr>
            <th id="tl">Rank</th>
            <th >User</th>
            <th id="tr">Points</th>
        </tr>
        <?php
            mysql_data_seek($res,0);
            for($i=0;$i<10;$i++)
            {
                if($i%2==0)
                    $t='class="one"';
                else
                    $t='class="two"';
                $row=mysql_fetch_array($res);
                echo '<tr '.$t.'>
                    <td>'.($i+1).'</td>
                    <td class="left">'.$row['user'].'</td>
                    <td>'.$row['points'].'</td>
                </tr>';
            }
        ?>
        <tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
    </table>
    <br>
    </div>
    <?php include '../common/nav.php' ?>
    <?php include '../common/footer.php' ?>
    </body>
	
</html>
<?php mysql_close($con); ?>



