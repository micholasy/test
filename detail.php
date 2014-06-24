<?php
header('Content-Type: text/html; charset=UTF-8');
include_once("dbutils.php");

if ( !empty($_GET["uid"]) )
{
    $uid = $_GET["uid"];
    $user = getUserBasicInfoByID( $uid );
    $weibo = getWeiBoInfo( $uid ); 
    $relation = getUserRelation( $uid );
}
else
{
    echo "该用户记录不存在！<br/><br/>";   
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

a:link{
 color:#00F;
 text-decoration:none;
}

a:visited {
 color: #00F;
 text-decoration:none;
}

a:hover {
 color: #c00;
 text-decoration:underline;
}

ul{
 list-style:none;
}

.main{
 clear:both;
 padding:8px;
 text-align:center;
}

    
#tabs {
    width: 750px;
    background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.08);
    color: #444;
}
    
.menu0{
 width: 750px;
}

.menu0 li{
 display:block;
 float: left;
 padding: 4px 0;
 width:250px;
 color:#FFFFFF;
 font:14px "宋体";
 text-align: center;
 cursor:pointer;
 background: none repeat scroll 0% 0% #5B97CB;
}

.menu0 li.hover{
 color:#0066CC;
 background: #f2f6fb;
}

.left {
float: left;
}
    
.cNote, .c_tx5 {
color: #999;
font-size:small;
}
    
.WB_text {
text-align:left;
}

.tree {
font-size:14px;color:#5B97CB;  
border: 1 solid #1892B5; padding: 1;
}
    
.leaf {
font-size:13px; 
padding: 1;
}
    
</style>

<script>
function setTab(n)
{
 var tli=document.getElementById("menu0").getElementsByTagName("li");
 for(i=0;i<tli.length;i++)
 {
  tli[i].className=i==n?"hover":"";
  var con=document.getElementById("con_"+i);
  con.style.display=i==n?"block":"none";
 }
}
</script>
</head>

<body>
<br />
<br />
<div id="tabs">
 <ul class="menu0" id="menu0">
  <li onclick="setTab(0)" class="hover">个人资料</li>
  <li onclick="setTab(1)">最新微博</li>
  <li onclick="setTab(2)">人物关系</li>
 </ul>

 <div class="main" id="main0">
     <div id="con_0" class="hover">
            <TABLE>   
            <TR>
            <TD >用户UID：</TD>
            <TD ALIGN=LEFT><?=$user[0]['id']?></TD>
            </TR>
            <TR>
            <TD >昵称：</TD>
            <TD ALIGN=LEFT><?=$user[0]['screen_name']?></TD>
            </TR>
            <TR>
            <TD >所在省级ID：</TD>
            <TD ALIGN=LEFT><?=$user[0]['province']?></TD>
            </TR>
            <TR>
            <TD >所在城市ID：</TD>
            <TD ALIGN=LEFT><?=$user[0]['city']?></TD>
            </TR>
            <TR>
            <TD >用户所在地：</TD>
            <TD ALIGN=LEFT><?=$user[0]['location']?></TD> 
            </TR>
            <TR>
            <TD >性别：</TD>
            <TD ALIGN=LEFT><?=$user[0]['gender']?></TD>
            </TR>
            <TR>
            <TD >在线状态：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['online_status']?></TD>
            </TR>
            <TR>
            <TD >个人描述：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['description']?></TD>
            </TR>
            <TR>
            <TD >用户头像：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['profile_image_url']?></TD>
            </TR>
            <TR>
            <TD >微博地址：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['url']?></TD>
            </TR>
            <TR>
            <TD >注册日期：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['created_at']?></TD>
            </TR>
            <TR>
            <TD >用户标签：</TD> 
            <TD ALIGN=LEFT><?=$user[0]['tags']?></TD>
            </TR>
            </TABLE>
     </div>
     <div id="con_1" style="display:none">
             <?php
                for($i = 0;$i<count($weibo);++$i)
            	{
    				echo "<br/><br/>";
                    echo "<div class=\"WB_text\">".$weibo[$i]['text']."</div>";
    				echo "<br/>";
                    $flag = "[原创]";
                    if( $weibo[$i]['is_retweeted'] == 1 )
                    {
                        $flag = "[转发]";
                    }
                    echo "<div><SPAN class=\"left c_tx5\">".$weibo[$i]['created_at']."  来自".$weibo[$i]['source']."  ".$flag."</SPAN></div>";
                    echo "<HR style=\"FILTER: alpha(opacity=100,finishopacity=0,style=2)\" width=\"100%\" color=#cbcbcb SIZE=1/>";
            	}
             ?>
     </div>
     <div id="con_2" style="display:none">

            <div align=left class="tree" onclick="document.all.child1.style.display=(document.all.child1.style.display =='none')?'':'none'" > 
                <strong>+ 用户关注列表</strong>
            </div> 
            <div align=left class="leaf" id="child1" style="display:none">
            <?php 
			$friends = json_decode($relation[0]['friends'], true);
            for($i=0; $i<count($friends);++$i)
            {
                $uid = $friends[$i]['id'];
                $screen_name = $friends[$i]['screen_name'];
                echo $screen_name."<br/>";
            }
			?>
            </div>
            <div align=left class="tree" onclick="document.all.child2.style.display=(document.all.child2.style.display =='none')?'':'none'" > 
                <strong>+ 用户粉丝列表</strong> 
            </div>
            <div align=left class="leaf" id="child2" style="display:none">
 
            <?php    
            $followers = json_decode($relation[0]['followers'], true);
            for($i=0; $i<count($followers);++$i)
            {
                $uid = $followers[$i]['id'];
                $screen_name = $followers[$i]['screen_name'];
                echo $screen_name."<br/>";
            } 
			?>
            </div>
            <div align=left class="tree" onclick="document.all.child3.style.display=(document.all.child3.style.display =='none')?'':'none'" > 
                <strong>+ 用户互粉列表</strong> 
            </div>
            <div align=left class="leaf" id="child3" style="display:none">
            <?php
            $bi_followers = json_decode($relation[0]['bi_followers'], true);
            for($i=0; $i<count($bi_followers);++$i)
            {
                $uid = $bi_followers[$i]['id'];
                $screen_name = $bi_followers[$i]['screen_name'];
                echo $screen_name."<br/>";
            }
			?>
            </div>
     </div>
 </div>
    <br/><br/><br/>
</div>
<br/>
<a href="search.html">返回首页</a> 
<span>&nbsp;&nbsp;</span>
<a href="post.php?search=quanbu">返回上一页</a>   
<br/>
</body>
</html>

