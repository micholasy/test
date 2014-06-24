<?php
header('Content-Type: text/html; charset=UTF-8');
require_once("dbutils.php");


if( !empty($_REQUEST['username']) )
{
    $username = $_REQUEST['username'];     
    $user = getUserBasicInfoByMohu($username); 	  	
}
else if (  !empty($_REQUEST['search']) )
{
    $user = getAllUsers(); 
}
else
{
    echo "<script>alert('您输入的昵称有误，请重新输入！');location.href='search.html'</script>";     
}
       
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="http://ianalysis-ianalysis.stor.sinaapp.com/logo.ico" type="image/x-icon"/>
<title>艾数分析-ianalysis</title>
<style type="text/css">
    
body,div,ul,li{
 margin:0 auto;
 padding:0;
}

body{
 background-color:#F2F2F2;
 text-align:center;
}
 
table{
    width: 750px;
    }

a{
text-decoration:none;
font-size:14px;
}

th{
 color:#FFFFFF;
 font:14px "宋体";
 text-align: center;
 background: none repeat scroll 0% 0% #5B97CB;     
 }
    
#tabs {
    width: 750px;
    background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.08);
    color: #444;
}
    
</style>

</head>

<body>
<br />
<br />
    
<div id="tabs">
  <TABLE style="border-collapse: collapse"  borderColor=#cccccc cellPadding=1 border=1>
    <TR>
    <TH>索引</TH> 
    <TH>UID</TH>
    <TH>昵称</TH>
    <TH>用户所在地</TH>
    <TH>性别</TH>
    <TH>在线状态</TH>
    <TH>详细</TH>
    </TR>
    <?php
	$num = count($user);
    for ($i=0; $i<$num;++$i)
    {
        echo "<tr>";
        echo "<TD ALIGN=CENTER>".$i."</TD>";
        echo "<TD ALIGN=CENTER>".$user[$i]['id']."</TD>";
        echo "<TD ALIGN=CENTER>".$user[$i]['screen_name']."</TD>";
        echo "<TD ALIGN=CENTER>".$user[$i]['location']."</TD>";     
        echo "<TD ALIGN=CENTER>".$user[$i]['gender']."</TD>";
        echo "<TD ALIGN=CENTER>".$user[$i]['online_status']."</TD>";
        echo "<TD ALIGN=CENTER><a href=\"detail.php?uid={$user[$i]['id']}\">详细信息</a></TD>";
        echo "</tr>";
    }
	?>
   </TABLE>   
   <br/>
    <a href="search.html"> 返回上一页 </a>  
    <br/><br/>
</div>
    
    
</body>
</html>