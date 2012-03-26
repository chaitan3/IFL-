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


<body onload="onload_func()">
<?php include 'common/header.php' ?>
<?php include 'common/sidebar.php' ?>
<div id="content">
<?php 
    if(!isset($_REQUEST['club']))
        $val=0;
    else
        $val=$_REQUEST['club'];
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="cform">
    <select name="club" onChange="document.cform.go.click()">

<?php
    $res=mysql_query('select id,team_name from ifl_team');
    while($row=mysql_fetch_array($res)){
        $s='';
        if($val==$row['id'])
            $s=' selected="selected"';
        echo '<option value="'.$row['id'].'"'.$s.'>'.$row['team_name'].'</option>';
    }
?>

    </select>
    <input type="submit" value="Go" name="go" />
</form>

<?php
    $res=mysql_query('select * from ifl_team where id='.$val);
    $manager=mysql_fetch_array($res);
    echo '<img id="biglogo" src="images/teams/'.$manager['team_name'].'.png" alt="Image not found" />';
    echo '<br/><br/>';
    echo '<table>
              <tr>
                  <th id="tl" class="left">President</th>
                  <th class="left">Manager</th>
                  <th>Value</th>
                  <th>P</th>
                  <th>W</th>
                  <th>L</th>
                  <th>D</th>              
                  <th id="tr">Points</th>
                  
              </tr>
              <tr class="one">
                  <td class="left">'.$manager['president_name'].'</td>
                  <td class="left">'.$manager['manager_name'].'</td>
                  <td>'.$manager['value'].'</td>                  
                  <td>'.$manager['played'].'</td>
                  <td>'.$manager['won'].'</td>
                  <td>'.$manager['lost'].'</td>
                  <td>'.$manager['drawn'].'</td>
                  <td>'.$manager['points'].'</td>
              </tr>    
        <tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
          </table><br /><br /><br />';
    
    $res=mysql_query('select id,name,position from ifl_player where team="'.$manager['team_name'].'"');
    echo '<table id="plist">
              <tr>
                  <th id="tl" class="left">Player</th>
                  <th id="tr" class="right">Postion</th>
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
            echo '<tr class="'.$c.'">';
            echo '<td class="left"><a href="profile.php?id='.urlencode($row['id']).'">'.$row['name'].'</a></td>';
            echo '<td class="right">'.$row['position'].'</td>'; 
            echo '</tr>';
            }
            echo '<tr class="one"><td id="bl">&nbsp;</td><td id="br">&nbsp;</td></tr>';
            echo '</table>';
?>		
</div>
<?php include 'common/nav.php' ?>
<?php include 'common/footer.php' ?>
</body>

</html>		
<?php mysql_close($con); ?>
