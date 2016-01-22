<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> 选课登录 </title>
    <link href="css/index_style.css" rel='stylesheet' type='text/css' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="application/x-javascript">
        addEventListener("load", function()
        {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar()
        {
            window.scrollTo(0,1);
        }
    </script>
    <style>
        h1.pos_right
        {
            font-family:Microsoft YaHei;
            font-size:36px;
            color:#fff;
            position:absolute;
            top:15%;
            left:32%
        }
    </style>
</head>
<body>
<h1 class="pos_right">选课系统</h1>
<?php
if(isset($_POST["submit"]))
{
    bangding(urlencode($_POST["txtUserID"]), urlencode($_POST["txtUserPwd"]));
}
function bangding($user, $password)
{
    require_once './sql.php';
    $sql = "SELECT password,state_num FROM student WHERE stu_num = '$user'";
    $result = _select_data($sql);
    $rows = mysql_fetch_array($result);
    $pwd = $rows['password'];
    $state_num = $rows['state_num'];
    if($state_num !=0)
    {
        echo '<script>alert("没有选课资格！");</script>';
    }
    else if ($password == $pwd && $pwd != null)
    {
        ?>
        <script> document.cookie="c_name=<?php echo $user; ?>";
            // alert(document.cookie.split("=")[2]);  
        </script>

        <?php
        //echo '<script> location.replace("xuanke.php?stu='.$user.'"); </script>';
        echo '<script> location.replace("xuanke.php"); </script>';
    }
    else
    {
        echo '<script>alert("学号密码错误，请重新输入！");</script>';
    }
}
echo'
            <div class="main">
            <div class="user">
                <img src="images/user.png" alt="">
            </div>
            <div class="login">
                <div class="inset">
                    <!-----start-main---->
                        <form action = "" method = "post">
                         <div>
                            <span><label>学号</label></span>
                            <span><input name = "txtUserID" type="text" class="textbox" value = "" placeholder = "测试：120001/120002/130001">
                            </span>
                         </div>
                         <div>
                            <span><label>密码</label></span>
                            <span><input name = "txtUserPwd" type="password" class="password" value = "" placeholder = "密码：123456"></span>
                         </div>
                        <div class="sign">
                            <div class="submit">
                                <input type = "submit" name = "submit" value = "点击登录" id = "btnLogin" class = "button" />
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            <!-----//end-main---->
            </div>
             <!-----start-copyright---->
        ';
?>
</body>
</html>