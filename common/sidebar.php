<div id="sidebar">
<p style="margin-top:10%">
For manager login click on Manager in the Navigation Bar<br><br>
</p>
<div id="updates">
<p style="text-align:center;font-weight:bold">IFL Updates</p>
<br>
<ul>
<?php
    $res=mysql_query('select * from updates order by id desc LIMIT 5');
    while($row=mysql_fetch_array($res))
    {
        echo '<li>'.$row['string'].'</li><br>';
    }
?>
<li><a href="/~sports/ifl/news.php">more>></a></li><br>
</ul>
</div>
<img src="/~sports/ifl/images/enclose.png" alt="." />
</div>
