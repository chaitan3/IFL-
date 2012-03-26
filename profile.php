<?php include 'common/connect.php';
    session_start();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<script type="text/javascript" src="common/common.js"></script>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="shortcut icon" href="images/ifl.jpg"/>
<title>Institute Football League</title>
</head>

<body onload="onload_func()">
<?php include 'common/header.php' ?>
<?php include 'common/sidebar.php' ?>
<div id="content">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >
    Search for a Player: <input class="profile" type="text" name="string" value="<?php echo $_POST['string']; ?>" />
    <input class="profile" type="submit" value="Search" name="search" />
</form><br />
        
<?php
if (isset($_REQUEST['string']) || isset($_REQUEST['id'])){
$quit=0;
if(isset($_REQUEST['string']))
{
    if((strlen($_REQUEST['string']) < 2))//keep validation chk later
        $quit=1;
    else
        $res=mysql_query('select * from ifl_player where name like "%'.$_REQUEST['string'].'%"');
}
else
    $res=mysql_query('select * from ifl_player where id='.$_REQUEST['id']);
if(!$quit)
{
    if(mysql_num_rows($res)==0)
        echo 'No players found';
    else
        echo mysql_num_rows($res).' Player(s) found';
    echo '<br/><br/>';
    while($row=mysql_fetch_array($res)){
        $image = 'images/players/' . $row['id'] . '.jpg';
        if(file_exists($image))
            echo '<img id="player" src="'. $image .'" alt="." />';
        else
            echo '<img id="player" src="images/blank.jpg" alt="." />';
        
        
        echo '<table id="info">
                  <tr class="one">
                        <td id="tl" class="left">Name:</td>
                        <td id="tr" class="right">'.$row['name'].'</td>
                   </tr>
                   <tr class="two">
                        <td class="left">Club: </td>
                        <td class="right">'.$row['team'].'</td>
                   </tr>   
                   <tr class="one">
                        <td class="left">Position: </td>
                        <td class="right">'.$row['position'].'</td>
                   </tr>   
                   <tr class="two">
                        <td class="left">Goals scored: </td>
                        <td class="right">'.$row['goals_scored'].'</td>
                   </tr>
                   <tr class="one">
                        <td class="left">Yellow cards: </td>
                        <td class="right">'.$row['yellow_cards'].'</td>
                   </tr>
                   <tr class="two">
                        <td class="left">Red cards: </td>
                        <td class="right">'.$row['red_cards'].'</td>
                   </tr>
                   <tr class="one">
                   <td class="left">Assists: </td>
                   <td class="right">'.$row['assists'].'</td>
                   </tr>
                    <tr class="two">
                   <td class="left">Games played: </td>
                   <td class="right">'.$row['matches'].'</td>
                   </tr>
                   <tr class="one">
                   <td id="bl" class="left">Initial Bidding value: </td>
                   <td id="br" class="right">'.$row['bidding_value'].'</td>
                   </tr>
                   </table><br /><br />';
        if(isset($_SESSION['manager_team']) && ($row['team']!=$_SESSION['manager_team']))
        {
            $res2=mysql_query('select * from '.$_SESSION['manager_id'].'_bids where player='.$row['id']);
            if(mysql_num_rows($res2)==0)
                echo '<form method="post" action="manager/makebid.php">
                            <input type="submit" value="Make Bid" />
                            <input type="hidden" name="id" value="'.$row['id'].'"/>
                        </form>
                        <form method="post" action="manager/buyout.php"><br>
                            <input type="submit" value="Buyout Player" />
                            <input type="hidden" name="id" value="'.$row['id'].'"/>
                        </form>
                        ';
        }
    }
}
else
{
    echo 'Too few characters';
}

}
?>

</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php' ?>
</body>

</html>
<?php mysql_close($con); ?>
