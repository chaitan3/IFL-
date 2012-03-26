<?php
session_start();
if(!isset($_SESSION['manager_team']))
    header('location:manager_login.php');
?>
<?php include '../common/connect.php' ?>
<?php $range=$_REQUEST['range'];?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<title>IFL Manager</title>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" type="text/css" href="../css/manager.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
</head>

<body onload="onload_func()">

<?php include '../common/header.php' ?>
<?php include '../common/sidebar.php' ?>
<div id="content">

<?php include 'transfers.php' ?>
<form action="../profile.php" method="post" >
    Search for a Player: <input class="profile" type="text" name="string" />
    <input class="profile" type="submit" value="Search" name="search" />
</form><br>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="cform">
    Position <select name="pos" onChange="document.cform.go.click()">
    <option value="any">Any</option>
    <?php
        $res=mysql_query('select * from positions');
        while($row=mysql_fetch_array($res)){
            $s='';
            if($_REQUEST['pos']==$row['position'])
                $s='selected="selected"';
            echo '<option '.$s.' value="'.$row['position'].'" '.$s.'>'.$row['position'].'</option>';
        }
    ?>
    </select>
    &nbsp;&nbsp;&nbsp;
    Value Range <select name="range" onChange="document.cform.go.click()">
    <option value="0">Any</option>
    <option value="1" <?php if($range==1) echo 'selected="selected"';?>>0-25</option>
    <option value="2" <?php if($range==2) echo 'selected="selected"';?>>25-50</option>
    <option value="3" <?php if($range==3) echo 'selected="selected"';?>>50-100</option>
    <option value="4" <?php if($range==4) echo 'selected="selected"';?>>100-150</option>
    <option value="5" <?php if($range==5) echo 'selected="selected"';?>>150-250</option>
    <option value="6" <?php if($range==6) echo 'selected="selected"';?>>250+</option>
    </select>
    <input type="submit" value="Go" name="go" />
    <br><br>
    <input type="checkbox" <?php if(isset($_REQUEST['show'])) echo 'checked';?> value="1" name="show" onchange="document.cform.go.click()"/> Show bids made by you
</form>
<br/>
<table>
    <tr>
        <th id="tl" class="left">Player</th>
        <th class="left">Team</th>
        <th class="left">Position</th>
        <th>Value</th>
        <th id="tr">&nbsp;</th>
    </tr>
    <?php
        $s='';
        if(isset($_REQUEST['pos']) && ($_REQUEST['pos']!='any'))
        {
            $s=' and position="'.$_REQUEST['pos'].'"';
        }
        if(isset($_REQUEST['range']))
        {
            switch($range)
            {
                case 1:
                    $s=$s.' and bidding_value<25';
                    break;
                case 2:
                    $s=$s.' and bidding_value between 24 and 50';
                    break;
                case 3:
                    $s=$s.' and bidding_value between 49 and 100';
                    break;
                case 4:
                    $s=$s.' and bidding_value between 99 and 150';
                    break;
                case 5:
                    $s=$s.' and bidding_value between 149 and 250';
                    break;
                case 6:
                    $s=$s.' and bidding_value>=250';
                    break;
            }
        }
        $s=$s.' order by bidding_value desc';
        if(!isset($_REQUEST['show']))
            $res=mysql_query('select id,name,team,position,bidding_value from ifl_player where team<>"'.$_SESSION['manager_team'].'" and for_sale=1 '.$s);
        else
            $res=mysql_query('select id,name,team,position,bidding_value from ifl_player where id in (select player from '.$_SESSION['manager_id'].'_bids) '.$s);
        $i=1;
        while($row=mysql_fetch_array($res))
        {
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
                    <td class="left">'.$row['name'].'</td>
                    <td class="left">'.$row['team'].'</td>
                    <td class="left">'.$row['position'].'</td>
                    <td>'.$row['bidding_value'].'</td>';
            $res2=mysql_query('select * from '.$_SESSION['manager_id'].'_bids where player='.$row['id']);
            if(mysql_num_rows($res2)==0)
            {
                echo '<td><form method="post" action="makebid.php">
                            <input type="submit" value="Make Bid" />
                            <input type="hidden" name="id" value="'.$row['id'].'"/>
                        </form></td>';
            }
            else
            {
                
                $res2=mysql_query('select * from bids_'.$row['id'].' where team="'.$_SESSION['manager_team'].'"');
                $r2=mysql_fetch_array($res2);
                $res2=mysql_query('select name from ifl_player where id='.$r2['player']);
                $r3=mysql_fetch_array($res2);
                if($r2['status']==0)
                {
                echo '<td>Pending Approval</td>
                </tr>
                <tr id="hov" class="'.$c.'"><td id="xinfo"><p>'.$r3['name'].'</p></td><td>&nbsp;</td><td>&nbsp;</td><td id="xinfo"><p>'.$r2['value'].'</p>
                <td><a class="button" href=""><span>Details</span></a>
                </td></tr>
                <tr class="'.$c.'"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>
                <form method="post" action="cancelbid.php">
                            <input type="submit" value="Cancel Bid" />
                            <input type="hidden" name="id" value="'.$row['id'].'"/>
                        </form>
                </td>';
                }
                else if($r2['status']==1)
                    echo '<td>Bid Rejected</td>
                    </tr>
                    <tr id="hov" class="'.$c.'"><td colspan="4" id="xinfo"><p>'.$r2['reason'].'</p></td>
                    <td><a class="button" href=""><span>Reason</span></a>
                    </td></tr>
                    <tr class="'.$c.'"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>
                    <form method="post" action="cancelbid.php">
                                <input type="submit" value="Renegotiate" />
                                <input type="hidden" name="id" value="'.$row['id'].'"/>
                                <input type="hidden" name="deltype" value="1"/>
                            </form>
                    </td></tr>
                    <tr class="'.$c.'"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>
                    <form method="post" action="cancelbid.php">
                            <input type="submit" value="Cancel Bid" />
                            <input type="hidden" name="id" value="'.$row['id'].'"/>
                            <input type="hidden" name="deltype" value="0"/>
                        </form>
                    </td>';
                    else
                    echo '<td>Buyout Bid</td>
                </tr>
                <tr id="hov" class="'.$c.'"><td id="xinfo"><p>'.$r3['name'].'</p></td><td>&nbsp;</td><td>&nbsp;</td><td id="xinfo"><p>'.$r2['value'].'</p>
                <td><a class="button" href=""><span>Details</span></a>
                </td>';
            }
            echo '</tr>';
        }
        
    ?>
    <tr class="one"><td id="bl">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td id="br">&nbsp;</td></tr>
</table>
</div>
<?php include '../common/nav.php' ?>
<?php include '../common/footer.php' ?>
<?php mysql_close($con);?>
</body>
</html>


