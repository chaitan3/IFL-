<?php include '../common/connect.php' ?>
<?php 
    if(1==1)
        $invalid=2;
    else if(isset($_REQUEST['register']) && !isset($invalid))
    {
        $user=$_REQUEST['username'];
        $pass=$_REQUEST['password'];
        $cpass=$_REQUEST['cpassword'];
        if(ctype_alnum($user)==false || ctype_alnum($pass)==false || strlen($user)==0 || strlen($pass)==0)
            $invalid='Username and Password can only have alphabets/numerals.';
        else if($pass!=$cpass)
            $invalid='Passwords don\'t match';
        else
        {
            $res=mysql_query('select username from login where username="'.$user.'"');
            if((mysql_num_rows($res)==0))
            {
                $query='insert into login (username,password,type,name) values("'.$user.'",PASSWORD("'.$pass.'"),2,"'.$_REQUEST['emailid'].'")';
                if(!mysql_query($query))
                    die('Error: '.mysql_error());
                session_start(); 
                $_SESSION['user']=$user;
                header('location:maketeam.php');
            }
            else
                $invalid='Username/Email already in use.';
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="shortcut icon" href="../images/ifl.jpg"/>
<title>Fantasy Premier League</title>
</head>

    
    <body onload="onload_func()">
    <?php include '../common/header.php' ?>
    <div id="content">
    Registration for FPL<br>
    <?php if(isset($invalid) && $invalid!=2) echo '<br>'.$invalid.'<br>';
        else if($invalid==2) echo "<br>No registrations allowed during Matches<br>";
     ?>
    <br>
    
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <table id="register">
        <tr>
        <th id="tl" class="left">Username</th>
        <th id="tr"><input type="text" name="username"/></th></tr>
        <tr class="one">
        <td class="left">Password</td>
        <td><input type="password" name="password"/></td></tr>
        <tr class="one">
        <td class="left">Confirm Password</td>
        <td><input type="password" name="cpassword"/></td></tr>
        <tr class="one">
        <td class="left" id="bl">Email ID</td>
        <td id="br"><input type="text" name="emailid"/></td>
        </tr>
    
    </table><br>
    <input type="submit" name="register" value="Register"/>
    </form>
    <br>
    </div>
    <?php include '../common/nav.php' ?>
    <?php include '../common/footer.php' ?>
    </body>
	
</html>
<?php mysql_close($con); ?>

