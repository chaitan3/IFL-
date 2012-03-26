<?php
function connect_db()
{
 global $con;
 $con = mysql_connect("localhost","sports","sports09");
if(!$con)
    die( "Could not connect: " . mysql_error());
mysql_select_db("sports", $con);
return $con;
}

$con = connect_db();

?>
