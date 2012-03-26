<?php include '../common/connect.php' ?>
<?php 
    if(isset($_REQUEST['login']))
    {
        $user=$_REQUEST['username'];
        $pass=$_REQUEST['password'];
        if(ctype_alnum($user)==true && ctype_alnum($pass)==true)
        {
            $res=mysql_query('select * from login where username="'.$user.'" and password=PASSWORD("'.$pass.'") and type=1');
            if(mysql_num_rows($res)==1)
            {
                $row=mysql_fetch_array($res);
                session_start(); 
                $_SESSION['manager_team']=$row['name'];
                $res=mysql_query('select manager_name,value,id from ifl_team where team_name="'.$row['name'].'"');
                $row=mysql_fetch_array($res);
                $_SESSION['manager_name']=$row['manager_name'];
                $_SESSION['manager_id']=$row['id'];
                if(1==2)
                    header('location:manager.php');
                else
                {
                    $invalid=2;
                    session_destroy();
                }
            }
            else
                $invalid=1;
        }
        else
            $invalid=1;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>IFL Manager</title>
</head>

    
    <body onload="onload_func()">
    <?php include '../common/header.php' ?>
    <?php include '../common/sidebar.php' ?>
    <div id="content">
    <table id="login">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <tr>
        <th id="tl">Username</th>
        <th id="tr"><input type="text" name="username"/></th></tr>
        <tr class="one">
        <td>Password</td>
        <td><input type="password" name="password"/></td></tr>
        <tr class="one"><td id="bl"><input type="submit" name="login" value="Login"/></td><td id="br">&nbsp;</td></tr>
    </form>
    </table><br>
    <?php
     if($invalid==1) echo "Invalid Password"; 
        else if($invalid==2) echo 'Username/Password correct. Transfer Window is Closed. You can access the manager forum over <a href="../forums/viewforum.php?f=5">here</a>(login with the same username/pass)'; ?>
    </div>
    <?php include '../common/nav.php' ?>
    <?php include '../common/footer.php' ?>
    </body>
	
</html>
<?php mysql_close($con); ?>
