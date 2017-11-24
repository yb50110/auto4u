<?php
    $dbhandle = new mysqli('localhost','root','root','auto4you');
    $dbhandle->connect_error;

    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    // username and password sent from Form
        $myusername=mysqli_real_escape_string($db,$_POST['username']);
        $mypassword=mysqli_real_escape_string($db,$_POST['password']);
        $sql="SELECT * FROM admin WHERE username='$myusername' and password='$mypassword'";
        $result=mysqli_query($db,$sql);
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

        $count=mysqli_num_rows($result);

    // If result matched $myusername and $mypassword, table row must be 1 row
        if($count==1)
        {
            $_SESSION["username"] = $myusername;
            header("location: index.php");
        }
        else
        {
            $error="Your Login Name or Password is invalid";
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto4You</title>

    <link rel="stylesheet" href="stylesheets/main.css">
</head>
<body>

<div class="wrapper">
    <div class="page-login">
        <img src="/img/auto4you-logo.png" alt="Auto4you logo">

        <div class="panel">
            <div class="panel-header">
                Login
            </div>

            <div class="panel-body">
                <div style = "font-size:0.75em; color:#cc0000; margin:10px 0 20px 0;"><?php echo $error; ?></div>
                <form action = "" method = "post">
                    <input type = "text" name = "username" class = "box" placeholder="username"/><br /><br />
                    <input type = "password" name = "password" class = "box" placeholder="password"/><br/><br />
                    <button type = "submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script>

</script>
</html>
