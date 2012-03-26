<?php include '../common/connect.php' ?>
<?php 
    if(isset($_REQUEST['login']))
    {
        $user=$_REQUEST['username'];
        $pass=$_REQUEST['password'];
        if(ctype_alnum($user)==true && ctype_alnum($pass)==true)
        {
            $res=mysql_query('select * from login where username="'.$user.'" and password=PASSWORD("'.$pass.'") and type=2');
            if(mysql_num_rows($res)==1)
            {
                $row=mysql_fetch_array($res);
                session_start(); 
                $_SESSION['user']=$row['username'];
                if(1==1)
                    header('location:fpl.php');
                else
                {
                    session_destroy();
                    $invalid=2;
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
<title>FPL Login</title>
</head>

    
    <body onload="onload_func()">
    <?php include '../common/header.php' ?>
    <div id="fpl_content">
    <h3>Fantasy Premier League Login</h3><br>
    <?php
     if($invalid==1) echo "Invalid Password"; 
        else if($invalid==2) echo "Match is going on. No changes allowed"; ?>
        <br><br>
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
    </table>
    <br>
    Not Registered? Register <a href="register.php">Here</a> now!
    
    </div>
    <?php include '../common/nav.php' ?>
    <?php include '../common/footer.php' ?>
    </body>
	
</html>
<?php mysql_close($con); ?>

