
<div id="links">
<ul>
<li><a id="top" href="/~sports/ifl/index.php" >Home</a></li>
<li><a href="/~sports/ifl/fixtures.php" >Fixtures</a></li>
<li><a id="sidemenu" href="/~sports/ifl/clubs.php" >Clubs</a>
<ul>
<?php
    $res=mysql_query('select team_name,id from ifl_team');
    while($row=mysql_fetch_array($res))
    {
        echo'<li><a href="/~sports/ifl/clubs.php?club='.urlencode($row['id']).'">'.$row['team_name'].'</a></li>';
    }
?>
</ul>
</li>
<li><a href="/~sports/ifl/fpl/fpl.php" >FPL</a></li>
<li><a href="/~sports/ifl/statistics.php" >Stats</a></li>
<li><a href="/~sports/ifl/forums" >Forums</a></li>
<li><a href="/~sports/ifl/manager/manager.php" >Manager</a></li>
<li><a id="bottom" href="/~sports/ifl/sponsors.php" >Sponsors</a></li>
</ul>
<img src="/~sports/ifl/images/nav.png" alt="." >
</div>
