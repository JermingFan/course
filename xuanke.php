<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title> 选课页面 </title>
        <link href="css/other_style.css" rel='stylesheet' type='text/css' />
        <script> //document.cookie="c_name=123"; 
            //alert(document.cookie.split("=")[2]);  
            // 	var num = document.cookie.split("=")[2]; 
        </script>
    </head>
    <body>
        
        <?php
        require_once './sql.php'; 
		//   $stu_num = $_GET['stu'];
		$stu_num = $_COOKIE['c_name'];
        $sql = "SELECT * FROM student WHERE stu_num = '$stu_num' ";
        $result = _select_data($sql);
        $rows = mysql_fetch_assoc($result);
        $stu_name = $rows["stu_name"];
        $grade = $rows["grade"];
        $college_num = $rows["college_num"];
        $major_num = $rows["major_num"];
    
        $sql = "SELECT college_name FROM college WHERE college_num = '$college_num' ";
        $result = _select_data($sql);
        $rows = mysql_fetch_assoc($result);
        $college_name = $rows["college_name"];
    
        $sql = "SELECT major_name FROM major WHERE major_num = '$major_num' ";
        $result = _select_data($sql);
        $rows = mysql_fetch_assoc($result);
        $major_name = $rows["major_name"];
        echo '
            <p class="head">
            	选课时间为：2015年5月16日
                
            </p>
            <p class="info">
            &nbsp;&nbsp;&nbsp;&nbsp;学号： '.$stu_num.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名： '.$stu_name.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年级： '.$grade.'级<br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;学院： '.$college_name.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;专业： '.$major_name.'</p>
        ';
    
        if($_GET['action'] == "submit")
        { 
            $time_on = strtotime('15-05-16') - time();
            if($time_on < 0) 
            {
                $course = $_POST['box']; 
                $course_all = $course;
                //   echo "选课成功";
                $flag = 0;
                //当前一起选的课，时间进行比较
                foreach ((array)$course_all as $key=>$course_num_one)
                {
                    if($flag == 1)	break;
                    //当前这个的时间
                    $sql = "select course_time from course where course_num = '$course_num_one' ";
                    $result = _select_data($sql);
                    $rows = mysql_fetch_assoc($result);
                    
                    $time_s = $rows["course_time"];
                    $time_week_s = substr($time_s, 0, 1);
                    $time_jieci_s = substr($time_s, 2, 1);
                    $time_long_s = substr($time_s, 4, 1);
                    //当前这个与选中的每个比较
                    for($i=0;$i<=4;$i++)
                    {
                        if($course_all[$i]!=$course_num_one)
                        {
                            $sql = "select course_time from course where course_num = '$course_all[$i]' ";
                            $result = _select_data($sql);
                            $rows = mysql_fetch_assoc($result);
                                
                            $time_all = $rows["course_time"];
                            $time_week_all = substr($time_all, 0, 1);
                            $time_jieci_all = substr($time_all, 2, 1);
                            $time_long_all = substr($time_all, 4, 1);
                                
                            if($time_week_all==$time_week_s)
                            {
                                if($time_jieci_s>=$time_jieci_all&&$time_jieci_s<=$time_jieci_all+$time_long_all-1||$time_jieci_all>=$time_jieci_s&&$time_jieci_all<=$time_jieci_s+$time_long_s-1)
                                {	
                                    $flag=1;
                                    echo '<script>alert("当前选择的这些课程，时间冲突！");</script>';
                                }
                            }    
                        }
                    }
                }
                
                foreach ((array)$course as $key=>$course_num)
                {
                    if($flag==1)break;
                    //查目前选的课，上课的时间
                    $sql = "select course_time from course where course_num = '$course_num' ";
                    $result = _select_data($sql);
                    $rows = mysql_fetch_assoc($result);
                    
                    $time_now = $rows["course_time"];
                    $time_week_now = substr($time_now, 0, 1);
                    $time_jieci_now = substr($time_now, 2, 1);
                    $time_long_now = substr($time_now, 4, 1);
                    //$long=$time_jieci_now+$time_long_now-1;
                    
                    //查课表里的课，上课的时间
                    $sql = "select course_time from course_table where stu_num = '$stu_num' and course_num = '$course_num' ";
                    $result = _select_data($sql);
                    
                    //目前选的课与课表里的课，时间进行比较
                    while($rows = mysql_fetch_assoc($result))
                    {
                        $time = $rows["course_time"];
                        $time_week = substr($time, 0, 1);
                        $time_jieci = substr($time, 2, 1);
                        $time_long = substr($time, 4, 1);
                        
                        if($time_week==$time_week_now)
                        {
                            if($time_jieci_now>=$time_jieci&&$time_jieci_now<=$time_jieci+$time_long-1||$time_jieci>=$time_jieci_now&&$time_jieci<=$time_jieci_now+$time_long_now-1)
                            {
                                $flag=1;
                                echo '<script>alert("与课表时间冲突！");</script>';
                            }
                        }
                    }//while结束               
                }//foreach
                
                if($flag!=1)
                {
                    $i=0;	
                    //不存在时间冲突问题，将课程插入课表 
                    foreach ((array)$course as $key=>$course_num)
                    {
                        $sql = "select k_num,y_num from course where course_num = '$course_num' ";
                        $result = _select_data($sql);
                        $rows = mysql_fetch_assoc($result);
                        $k_num = $rows["k_num"];
                        $y_num = $rows["y_num"];
                        if($k_num>$y_num)
                        {
                            $y_num++;
                            $sql=" UPDATE `course` SET `y_num`= '$y_num' where course_num = '$course_num' ";	
                            _update_data($sql);
                                
                            $sql = " select course_name, course_time, course_type from course where course_num = '$course_num' ";
                            $result = _select_data($sql);
                            $rows = mysql_fetch_assoc($result);
                            $course_name = $rows["course_name"];
                            $course_time = $rows["course_time"];
                            $course_type = $rows["course_type"];
                                
                            $sql = "insert into course_table(course_num, stu_num, course_name, course_time, course_type) values('$course_num', '$stu_num', '$course_name', '$course_time',  '$course_type')";
                            _insert_data($sql);
                        }
                        else{$i=1;break;}
                    }
                    if($i==0){ echo '<script>alert("恭喜你，选课成功！");</script>';}
                    else echo '<script>alert("人数已满！");</script>';
                }
            }    
            else
            {
                echo '<script>alert("还未到选课时间！");</script>';
            } 
        }
        ?>
        
        <form id="form1" name="form1" method="post" action="?action=submit&stu=<?php echo $stu_num;?>"> 
            <table class="bordered" >
                <tr>
                    <th>必修课程</th>
                    <th>授课教师</th>
                    <th>上课时间</th>
                    <th>可选人数</th>
                    <th>已选人数</th>
                </tr>
                
                <?php
                require_once './sql.php';        
                mysql_query("SET NAMES utf8");
    
                $sql = "SELECT grade, college_num, major_num FROM student WHERE stu_num = '$stu_num' ";
                $result = _select_data($sql);
                $rows = mysql_fetch_assoc($result);
                $grade = $rows["grade"];//echo $grade;
                $college_num = $rows["college_num"];//echo $college_num;
                $major_num = $rows["major_num"];//echo $major_num;
    
                $sql = "SELECT * FROM course WHERE course_type = '1'and grade = '$grade' and  college_num = '$college_num' and major_num = '$major_num' ";
                $result = _select_data($sql);
                while($rows = mysql_fetch_assoc($result))
                {
                    $teacher_num = $rows["teacher_num"];//教师号
                    $course_num = $rows["course_num"];//课程号
                    $course_name = $rows["course_name"];//课程名
                    $k_num = $rows["k_num"];//可选人数
                    $y_num = $rows["y_num"];//已选人数
                    $course_time=$rows["course_time"];//上课时间
                    
                    $sql = "SELECT teacher_name FROM teacher WHERE teacher_num = '$teacher_num' ";
                    $result_t = _select_data($sql);
                    $rows_t = mysql_fetch_assoc($result_t);
                    $teacher_name = $rows_t["teacher_name"];//根据教师号查询出教师姓名
                    
                    
                    $time_week = substr($course_time, 0, 1);
                    $time_jieci = substr($course_time, 2, 1);
                    $time_long = substr($course_time, 4, 1);
                    
                    $time = $time_jieci;
                    if($time_week==1)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期一，第".$time."节";
                    }
                    else if($time_week==2)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期二，第".$time."节";
                    }
                    else if($time_week==3)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期三，第".$time."节";
                    }
                    else if($time_week==4)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期四，第".$time."节";
                    }
                    else if($time_week==5)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期五，第".$time."节";
                    }     
                ?>
                
                <tr>
                    <td>
                        <input type="checkbox" id="box" name="box[]" value="<?php echo $course_num; ?>" checked="checked" >
                        <?php echo $course_name; ?>
                    </td>
                    <td><?php echo $teacher_name; ?></td>
                    <td><?php echo $time; ?></td>
                    <td><?php echo $k_num; ?></td>
                    <td><?php echo $y_num; ?></td>
                </tr>
                
                <?php    
                } 
                ?>
                
            </table> 
            <br> 
               
            <table class="bordered" >       
                <tr>
                    <th>选修课程</th>
                    <th>授课教师</th>
                    <th>上课时间</th>
                    <th>可选人数</th>
                    <th>已选人数</th>
                </tr>
                
                <?php
                require_once './sql.php';        
                mysql_query("SET NAMES utf8");
                $sql = "SELECT * FROM course WHERE course_type = '0'and grade = '$grade' and  college_num = '$college_num' and major_num = '$major_num' ";
                $result = _select_data($sql);
                while($rows = mysql_fetch_assoc($result))
                {
                    $teacher_num = $rows["teacher_num"];//教师号
                    $course_num = $rows["course_num"];//课程号
                    $course_name = $rows["course_name"];//课程名
                    $k_num = $rows["k_num"];//可选人数
                    $y_num = $rows["y_num"];//已选人数
                    $course_time=$rows["course_time"];//上课时间
                    
                    $sql = "SELECT teacher_name FROM teacher WHERE teacher_num = '$teacher_num' ";
                    $result_t = _select_data($sql);
                    $rows_t = mysql_fetch_assoc($result_t);
                    $teacher_name = $rows_t["teacher_name"];//根据教师号查询出教师姓名
                    
                    
                    $time_week = substr($course_time, 0, 1);
                    $time_jieci = substr($course_time, 2, 1);
                    $time_long = substr($course_time, 4, 1);
                    
                    $time = $time_jieci;
                    if($time_week==1)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期一，第".$time."节";
                    }
                    else if($time_week==2)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期二，第".$time."节";
                    }
                    else if($time_week==3)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期三，第".$time."节";
                    }
                    else if($time_week==4)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期四，第".$time."节";
                    }
                    else if($time_week==5)
                    {
                        for($j=1;$j<$time_long;$j++)
                        {
                            $jie = $time_jieci+$j;
                            $time = $time.",".$jie;
                        }
                        $time="星期五，第".$time."节";
                    }
                ?>
                
                <tr>
                    <td>
                        <input type="checkbox" id="box" name="box[]" value="<?php echo $course_num; ?>">
                        <?php echo  $course_name; ?>
                    </td>
                    <td><?php echo $teacher_name; ?></td>
                    <td><?php echo $time; ?></td>
                    <td><?php echo $k_num; ?></td>
                    <td><?php echo $y_num; ?></td>
                </tr>
                
                <?php    
                } 
                ?> 
                
            </table>
            <br>
            
            <div class="submit">
                <input type="submit" name="submit" value="点击提交" />
                <input type="button" value="查看课表" onclick="location.href='course.php'">
                <input type="button" value="进行退选" onclick="location.href='tuixuan.php'">
                <input type="button" value="退出登录" onclick="location.href='index.php'">
            </div>
        </form>
    </body>
</html>