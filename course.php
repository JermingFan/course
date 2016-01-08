<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8" />
        <title> 选课课表 </title>
        <link href="css/other_style.css" rel='stylesheet' type='text/css' />
    </head>
    <body>
        <p class="head">选课课程表</p>
        
        <?php 
			$stu_num = $_COOKIE['c_name'];
//$stu_num = $_GET['stu']; 
        ?>
        
            <table class="bordered">
                <tr>
                    <th>节次</th>
                    <th>星期一</th>
                    <th>星期二</th>
                    <th>星期三</th>
                    <th>星期四</th>
                    <th>星期五</th>
                </tr>
                
                <?php
                    require_once './sql.php';        
                    mysql_query("SET NAMES utf8");
                    $sql = "select course.course_name, course.course_time,course.teacher_num from course,course_table where course_table.stu_num = '$stu_num' and course_table.course_num = course.course_num ";
                    $result = _select_data($sql);
                    $courseTable = array();
                    while($rows = mysql_fetch_assoc($result))
                    {
                        
                        $teacher_num = $rows["teacher_num"];
                        $sql = "SELECT teacher_name FROM teacher WHERE teacher_num = '$teacher_num' ";
                        $result_t = _select_data($sql);
                        $rows_t = mysql_fetch_assoc($result_t);
                        $teacher_name = $rows_t["teacher_name"];//根据教师号查询出教师姓名
                        
                        $time = $rows["course_time"];
                        $time_week = substr($time, 0, 1);
                        $time_jieci = substr($time, 2, 1);
                        $time_long = substr($time, 4, 1);
                        for($k = 0; $k < $time_long; $k++)
                        {
                            $courseTable[$time_week][$time_jieci+$k] = $rows["course_name"]."<br>".$teacher_name;//echo  $courseTable[1][1];
                        }
                        //$courseTable[$time_week][$time_jieci+$time_long]=$rows["course_name"];
                    }
                    for($i = 1; $i <= 8; $i++)//第几节
                    {     
                ?>
                
                <tr>
                    <th> <?php echo '第'.$i.'节'; ?> </th>
                    <td style="border-width:1px;border-style:solid;"> <?php echo $courseTable[1][$i]; ?> </td>
                    <td style="border-width:1px;border-style:solid;"> <?php echo $courseTable[2][$i]; ?> </td>
                    <td style="border-width:1px;border-style:solid;"> <?php echo $courseTable[3][$i]; ?> </td>
                    <td style="border-width:1px;border-style:solid;"> <?php echo $courseTable[4][$i]; ?> </td>
                    <td style="border-width:1px;border-style:solid;"> <?php echo $courseTable[5][$i]; ?> </td>
                </tr>
                
                <?php    
                    } 
                ?>
                
            </table>
            <br>
        
        <div class="submit">
            <input type="button" value="返回选课" onclick="location.href='xuanke.php'">
            <input type="button" value="进行退选" onclick="location.href='tuixuan.php'">
            <input type="button" value="退出登录" onclick="location.href='index.php'">
        </div>
    </body>
</html>