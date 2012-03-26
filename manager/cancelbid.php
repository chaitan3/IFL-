<?php
    include '../common/connect.php';
    session_start();
    if(ctype_digit($_REQUEST['id']))
    {
        $res=mysql_query('select value from bids_'.$_REQUEST['id'].' where team="'.$_SESSION['manager_team'].'"');
        $row=mysql_fetch_array($res);
        $query='delete from bids_'.$_REQUEST['id'].' where team="'.$_SESSION['manager_team'].'"';
        mysql_query($query);
        $query='delete from '.$_SESSION['manager_id'].'_bids where player='.$_REQUEST['id'];
        mysql_query($query);
        $query='update ifl_team set value_bids=value_bids-'.$row['value'].' where id='.$_SESSION['manager_id'];
        mysql_query($query);
        if($_REQUEST['deltype']==0)
            header('location:bids.php');
        else
            header('location:makebid.php?id='.$_REQUEST['id']);
    }
?>
