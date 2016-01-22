<?php
$link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
if($link)
{
    mysql_select_db(SAE_MYSQL_DB,$link);
    mysql_query("set names 'utf8'");
}

function _create_table($sql)
{
    mysql_query($sql) or die('创建表失败，错误信息：'.mysql_error());
    return "创建表成功";
}

function _insert_data($sql)
{
    if(!mysql_query($sql))
    {
        return 0;
    }
    else
    {
        if(mysql_affected_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 2;
        }
    }
}

function _delete_data($sql)
{
    if(!mysql_query($sql))
    {
        return 0;
    }
    else
    {
        if(mysql_affected_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 2;
        }
    }
}

function _update_data($sql)
{
    if(!mysql_query($sql))
    {
        return 0;
    }
    else
    {
        if(mysql_affected_rows() > 0)
        {
            return 1;
        }
        else
        {
            return 2;
        }
    }
}

function _select_data($sql)
{
    $ret = mysql_query($sql) or die('SQL语句有错误，错误信息：'.mysql_error());
    return $ret;
}

function _drop_table($sql)
{
    mysql_query($sql) or die('删除表失败，错误信息：'.mysql_error());
    return "删除表成功";
}
?>