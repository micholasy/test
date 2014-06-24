<?php
header('Content-Type: text/html; charset=UTF-8');

/**
 * 通过userid获取用户基本信息
 * @param string $userid 用户字符串类型uid
 * @return array
 */
function getUserBasicInfoByID($userid)
{
	$uid = intval($userid);
    $mysql = new SaeMysql();

    $sql = "SELECT * FROM `weibo_users` WHERE `id` = {$uid} LIMIT 0, 30 "; 
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;
}

/**
 * 通过username获取用户基本信息
 * @param string $username 用户昵称
 * @return array
 */
function getUserBasicInfoByName($username)
{
	$mysql = new SaeMysql();

    $sql = "SELECT * FROM `weibo_users` WHERE `screen_name` = \"{$username}\" LIMIT 0, 30 ";   
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data; 
}

/**
 * 通过username获取用户基本信息
 * @param string $username 用户昵称简写 譬如：simmy_shi 可通过输入 sim来查询
 * @return array
 */
function getUserBasicInfoByMohu($username)
{
	$mysql = new SaeMysql();

    $sql = "SELECT * FROM `weibo_users` WHERE `screen_name` REGEXP \"{$username}\" LIMIT 0, 30 ";   
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;        
}


/**
 * 显示所有用户记录
 */
function getAllUsers()
{
    $mysql = new SaeMysql();

    $sql = "SELECT * FROM `weibo_users` "; 
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;
}


/**
 * 通过$field==$value查找用户
 * @param string $field 数据库字段值
 * @param string $value 需要匹配的值
 * @return array
 */
function getUsers($field, $value)
{
	$mysql = new SaeMysql();

    $sql = "SELECT * FROM `weibo_users` WHERE $field = \"{$value}\"";   
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;      	    
    
}


/**
 * 通过$arr插入用户基本信息
 * @param array $arr 存有用户基本信息的数组
 * @return boolean值 成功返回true,失败返回false
 */
function insertUserBasicInfoToDB($arr)
{
  	if ( empty($arr) )
    {
    	return false;    
    }
    
    $id = $arr['id'];
    $screen_name = $arr['screen_name'];
    $province = $arr['province'];
    $city = $arr['city'];
    $location = $arr['location'];  
    
    $gender = $arr['gender'];
    $description = $arr['description'];
    $url = $arr['url'];
    $profile_image_url = $arr['profile_image_url'];
    $created_at = $arr['created_at']; 
    $online_status = $arr['online_status']; 
   
    $mysql = new SaeMysql();
    $sql = "INSERT INTO `app_ianalysis`.`weibo_users` 
    	  (`id`, `screen_name`, `province`, `city`, `location`, `gender`, `description`, `url`, `profile_image_url`, `created_at`, `online_status`)
    	  VALUES (\"{$id}\", \"{$screen_name}\", \"{$province}\", \"{$city}\", \"{$location}\", 
          		  \"{$gender}\", \"{$mysql->escape($description)}\", \"{$url}\", \"{$profile_image_url}\", \"{$created_at}\", \"{$online_status}\");";
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();		

	return true;    
}

/**
 * 通过$arr更新用户基本信息
 * @param array $arr 存有用户基本信息的数组
 * @return boolean值 成功返回true,失败返回false
 */
function updateUserBasicInfoToDB($arr)
{
	if ( empty($arr) )
    {
    	return false;    
    }
    
    $id = $arr['id'];
    $screen_name = $arr['screen_name'];
    $province = $arr['province'];
    $city = $arr['city'];
    $location = $arr['location'];  
    
    $gender = $arr['gender'];
    $description = $arr['description'];
    $url = $arr['url'];
    $profile_image_url = $arr['profile_image_url'];
    $created_at = $arr['created_at']; 
    $online_status = $arr['online_status']; 
    
    $mysql = new SaeMysql();
    $sql = "UPDATE `app_ianalysis`.`weibo_users` 
    		SET `screen_name` = \"{$screen_name}\", `province` = \"{$province}\", `city` = \"{$city}\", `location` = \"{$location}\", `gender` = \"{$gender}\", 
            `description` = \"{$description}\", `profile_image_url` = \"{$profile_image_url}\", `online_status` = \"{$online_status}\"
            WHERE `weibo_users`.`id` = {$id};";    
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();		

	return true;    
}

/**
 * 通过$arr插入用户基本信息
 * @param string $uid 用户的uid
 * @param string $field 数据库更新字段
 * @param string $value 更新后的值
 * @return boolean值 成功返回true,失败返回false
 */
function updateUserInfoField($uid, $field, $value)
{
   	$mysql = new SaeMysql();
    
    $sql = "UPDATE `app_ianalysis`.`weibo_users` SET {$field} = \"{$value}\" WHERE `weibo_users`.`id` = \"{$uid}\";";      
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();		

	return true;        
}

/**
 * 通过$user打印用户基本信息
 * @param array $user 存有用户基本信息的数组
 * @return boolean值 成功返回true,失败返回false
 */
function OutPutUserBasicInfo($user)
{
    $num = count($user);
    if ($num < 1)
    {
    	echo "没有查询到相关用户记录！" ;
        return false;
    }
    
    // 输出数据库查询结果表格
    echo "<TABLE style=\"border-collapse: collapse\"  borderColor=#cccccc cellPadding=1 border=1>";
    echo "<TR>";
    echo "<TH bgcolor=\"#666\">索引</TH>"; 
    echo "<TH bgcolor=\"#666\">UID</TH>"; 
    echo "<TH bgcolor=\"#666\">昵称</TH>";
    echo "<TH bgcolor=\"#666\">用户所在地</TH>";
    echo "<TH bgcolor=\"#666\">性别</TH>";
    echo "<TH bgcolor=\"#666\">在线状态</TH>"; 
    echo "<TH bgcolor=\"#666\">详细</TH>"; 
    echo "</TR>";
    
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
    echo "</table>";   
    
    return true;
}

/**
 * 通过$user打印用户详细信息
 * @param array $user 存有用户基本信息的数组
 * @return boolean值 成功返回true,失败返回false
 */
function OutPutUserDetailInfo($user)
{
    $num = count($user);
    if ($num < 1)
    {
    	echo "没有查询到相关用户记录！" ;
        return false;
    }
    
    $i=0;
    
    // 输出数据库查询结果表格
    echo "<TABLE style=\"border-collapse: collapse\"  borderColor=#cccccc border=1>";
    
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">用户UID：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['id']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">昵称：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['screen_name']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">所在省级ID：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['province']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">所在城市ID：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['city']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">用户所在地：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['location']."</TD>"; 
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">性别：</TD>";
    echo "<TD ALIGN=LEFT>".$user[$i]['gender']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">在线状态：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['online_status']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">个人描述：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['description']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">用户头像：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['profile_image_url']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">微博地址：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['url']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">注册日期：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['created_at']."</TD>";
    echo "</TR>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">用户标签：</TD>"; 
    echo "<TD ALIGN=LEFT>".$user[$i]['tags']."</TD>";
    echo "</TR>";
    
    return true;
}
//----------------------------用户更新情况记录表-----------------------------

/**
* 查询用户更新时间信息
* @param string $uid 用户的uid
*/
function getUserTimeInfo($uid)
{
    $mysql = new SaeMysql();
    $sql = "SELECT * FROM `user_record` WHERE `user_record`.`uid` = \"{$uid}\";"; 
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;         
}

/**
* 插入用户更新时间信息
* @param string $uid 用户的uid
* @param string $importtime 用户的导入时间
* @param string $updatetime 用户的更新时间
*/
function insertUserTimeInfo($uid, $time)
{
    $mysql = new SaeMysql();
    $sql = "INSERT INTO `app_ianalysis`.`user_record` (`uid`, `last_import_time`, `last_update_time`)
    	  	VALUES (\"{$uid}\", \"{$time}\", \"{$time}\");";
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();		

	return true;      
}

/**
* 更新用户更新时间信息
* @param string $uid 用户的uid
* @param string $importtime 用户的导入时间
* @param string $updatetime 用户的更新时间
*/
function updateUserTimeInfo($uid, $field, $value)
{
    $mysql = new SaeMysql();
    $sql = "UPDATE `app_ianalysis`.`user_record` SET {$field} = \"{$value}\" WHERE `user_record`.`uid` = \"{$uid}\";";      
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();		

	return true;    
}

//-----------------------------用户最新20条微博信息-----------------------------
function getWeiBoInfo($uid)
{
    $mysql = new SaeMysql();
    $sql = "SELECT * FROM `weibo_timeline` WHERE `uid` ={$uid} ORDER BY `created_at` DESC LIMIT 0 , 20"; 
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;
}

function insertWeiBoInfo($arr)
{
    $time = $arr['created_at']; 
  	$date = date('Y-m-d H:i:s', strtotime($time));
    $id = $arr['id'];
    $text = $arr['text'];
    $source = $arr['source'];
    $uid = $arr['user']['id'];
    $flag = 0;
    if(!empty($arr['retweeted_status']))
    {
        $text = $text."//".$arr['retweeted_status']['text'];
        $flag = 1;
    }
    if( count($arr['retweeted_status']['pic_urls']) > 0 )
    {
        $text = $text."url:";
        foreach($arr['retweeted_status']['pic_urls'] as $picurl)
        $text.=$picurl['thumbnail_pic']." ";
    }
    $mysql = new SaeMysql();
    $sql = "INSERT INTO `app_ianalysis`.`weibo_timeline` (`id`, `uid`, `text`, `source`,`created_at`,`is_retweeted`)
     	  	VALUES (\"{$id}\", \"{$uid}\", \"{$text}\", \"{$mysql->escape($source)}\", \"{$date}\", \"{$flag}\");";
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();	
    if($flag = 1)
    {
        updateweiboinfo($id, "text", $text);
    }
    
    
	return true;
}

function updateWeiBoInfo($id, $field, $value)
{
    $mysql = new SaeMysql();
    $sql = "UPDATE `app_ianalysis`.`weibo_timeline` SET {$field} = \"{$value}\" WHERE `weibo_timeline`.`id` = \"{$id}\";";      
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();
    
	return true;    
}

/**
 * 通过$user打印用户最新20条微博
 * @param array $user 存有用户微博信息的数组
 * @return boolean值 成功返回true,失败返回false
 */
function OutPutWeiboInfo($user)
{
    $num = count($user);
    if ($num < 1)
    {
    	echo "没有查询到相关用户记录！" ;
        return false;
    }
    
    $i=0;
    
    // 输出数据库查询结果表格
    echo "<TABLE style=\"border-collapse: collapse\"  borderColor=#cccccc border=1>";
    echo "<TR>";
    echo "<TD bgcolor=\"#708090\">用户UID：</TD>";
    echo "<TD bgcolor=\"#708090\">微博内容：</TD>";
    echo "</TR>";
    if( is_array($user))
    {
        foreach($user as $user)
        {
            echo "<TR>";
            echo "<TD ALIGN=LEFT>".$user['uid']."</TD>";
            echo "<TD ALIGN=LEFT>".$user['text']."</TD>";
            echo "</TR>";
        }
    }
    echo "</TABLE>";
    
    return true;
}
//----------------------------用户反馈记录表-----------------------------
/**
* 插入用户反馈
* @param string $name 昵称
* @param string $email 邮箱
* @param string $comment 反馈内容
*/
function insertUserFeedback($name, $email, $comment)
{
  	$date = date('Y-m-d H:i:s', time());
    
    $mysql = new SaeMysql();
    $sql = "INSERT INTO `app_ianalysis`.`user_feedback` (`name`, `email`, `comment`, `date`)
     	  	VALUES (\"{$name}\", \"{$email}\", \"{$mysql->escape($comment)}\", \"{$date}\");";
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();	
    
	return true;
}
//----------------------------用户关系表-----------------------------
/**
* 获取用户关系信息
* @param string $uid 用户的uid
*/
function getUserRelation($uid)
{
	$mysql = new SaeMysql();
    $sql = "SELECT * FROM `weibo_relation` WHERE `uid` ={$uid} LIMIT 0 , 20"; 
    $data = $mysql->getData( $sql ); 
    if( $mysql->errno() != 0 )  
    {  
        die( "Error:" . $mysql->errmsg() );  
    }  
    
    $mysql->closeDb(); 	      
    
    return $data;    
}
    
/**
* 插入用户关系
* @param string $uid 用户uid
* @param string $friend 用户关注
* @param string $follower 用户粉丝
* @param string $bi_follower 用户互粉
*/
function insertUserRelation($uid, $friends, $followers, $bi_followers)
{
    $mysql = new SaeMysql();
    $sql = "INSERT INTO `app_ianalysis`.`weibo_relation` (`uid`, `friends`, `followers`, `bi_followers`)
     	  	VALUES (\"{$uid}\", \"{$mysql->escape($friends)}\", \"{$mysql->escape($followers)}\", \"{$mysql->escape($bi_followers)}\");";
    
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0 && $mysql->errno() != 1062)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();	
    
	return true;
}

/**
* 更新用户关系
* @param string $uid 用户uid
* @param string $friend 用户关注
* @param string $follower 用户粉丝
* @param string $bi_follower 用户互粉
*/
function updateUserRelation($uid, $friends, $followers, $bi_followers)
{
    $mysql = new SaeMysql();
    $sql = "UPDATE `app_ianalysis`.`weibo_relation` SET `friends` = \"{$friends}\",`followers` = \"{$followers}\",`bi_followers` = \"{$bi_followers}\" WHERE `weibo_relation`.`uid` = \"{$uid}\";";      
    $mysql->runSql( $sql );
    if( $mysql->errno() != 0)
    {
        die( "Error:" . $mysql->errmsg() );
    }
    
    $mysql->closeDb();
    
	return true;    
}


?>