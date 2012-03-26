<div id="common">
<div style="font-size:1.1em">
<?php
$liq=mysql_query('select value,value_bids from ifl_team where id='.$_SESSION['manager_id']);
$liq=mysql_fetch_array($liq);
echo $_SESSION['manager_name'].'&nbsp;&nbsp;&nbsp;&nbsp;Liquid Value: '.$liq['value'].' (Value in Bids: '.$liq['value_bids'].')';
?>
</div>
<a style="float:right" class="button" href="logout.php"><span>Logout</span></a><br><br><br>
<img id="biglogo" src="../images/teams/<?php echo $_SESSION['manager_team']; ?>.png" alt=".">
<ul>
<li><a class="button" href="manager.php"><span>Manage Team</span></a></li>
<li><a class="button" href="bids.php"><span>Transfers</span></a></li>
<li><a class="button" href="../forums/viewforum.php?f=5"><span>Manager Forum</span></a></li>

</ul><br><br>
<a class="button" href="history.php"><span>Transfer History</span></a>
<br /><br /><br>
</div>


