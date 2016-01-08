<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title> 选课退选 </title>
    	<link href="css/other_style.css" rel='stylesheet' type='text/css' />
    </head>
    <body>
    <p class="head">请选择要退选的课程</p>
        
    <?php 
	$stu_num = $_COOKIE['c_name'];
//$stu_num = $_GET['stu'];
	echo "&nbsp;&nbsp;&nbsp;&nbsp;你的学号：".$stu_num;
    require_once './sql.php';    
    if($_GET['action'] == "submit")
    {     
    	$course = $_POST['box']; 
        //   echo "选课成功";
            
        foreach ((array)$course as $key=>$course_num)
        {
            $sql = "delete from course_table WHERE stu_num = '$stu_num' and course_num = '$course_num' ";
        	_delete_data($sql);
        }//foreach
        echo '<script>alert("退选成功！");</script>';
    }
    ?>  

        <form id="form1" name="form1" method="post" action="?action=submit&stu=<?php echo $stu_num;?>"> 
            <br>
            <table class="bordered">   
                <tr>
                    <th>已选择的课程</th>
                	<th>授课教师</th>
                    <th>上课时间</th>
                </tr>
                <?php
                    require_once './sql.php';        
                    mysql_query("SET NAMES utf8");
                    $sql = "SELECT * FROM course_table WHERE stu_num = '$stu_num' ";
                    $result = _select_data($sql);
                    while($rows = mysql_fetch_assoc($result))
                    {
                        $course_num = $rows["course_num"];
                        $sql = "SELECT teacher_num FROM course WHERE course_num = '$course_num' ";
            			$result_t = _select_data($sql);
						$rows_t = mysql_fetch_assoc($result_t);
                		$teacher_num = $rows_t["teacher_num"];//根据课程号查询出教师号
                        
                        $sql = "SELECT teacher_name FROM teacher WHERE teacher_num = '$teacher_num' ";
            			$result_t = _select_data($sql);
						$rows_t = mysql_fetch_assoc($result_t);
                		$teacher_name = $rows_t["teacher_name"];//根据教师号查询出教师姓名
                        
                        $course_time=$rows["course_time"];//上课时间
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
                        <input type="checkbox" id="box" name="box[]" value="<?php echo $rows["course_num"]; ?>">
                        <?php echo  $rows["course_name"]; ?>
                    </td>
                    <td><?php echo $teacher_name;?></td>
                    <td><?php echo $time;?></td>
                </tr>
                
                <?php    
                    } 
                ?>
                
            </table>
            <br>
            
            <div class="submit">
            	<input type="submit" name="submit" value="点击提交" />
            	<input type="button" value="查看课表" onclick="location.href='course.php'">
                <input type="button" value="退出登录" onclick="location.href='index.php'">
            </div>
        </form>
    </body>
</html>