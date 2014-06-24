<?php
session_start();

include_once( 'dbutils.php');
include_once( 'saetv2.ex.class.php' );

$c = new SaeTClientV2( WB_AKEY , WB_SKEY ,$_SESSION['oauth2']['oauth_token'] ,'' );
$uid = $c->get_uid();
$me = $c->show_user_by_id( $uid['uid'] );//获取用户信息

?>

<?php

/**
 * 通过uid找到用户互粉
 * @param string $uid 用户uid
 * @return string 用户标签
 */
function get_tags($uid)
{
    global $c;
    $tags = $c->get_tags($uid);//获取用户标签 
    if( is_array($tags))
    {
        foreach( $tags as $tag )
        {
            if( is_array($tag) )
            {
                foreach( $tag as $k=>$v )
                {
                    if( is_numeric($k) )
                    {  
                        $stag .= $v." "; 
                    }
                }
            }
        }
    }
    
    return $stag;
}

/**
 * 更新数据库中用户的标签
 * @return 
 */
function updateusertags()
{
	$users = getUsers("tags", "");
    $count = count($users);
    for ($i=0; $i<$count;++$i)
    {
        $tags = get_tags($users[$i]['id']);
        if ( !empty($tags) )
        {
        	updateUserInfoField($users[$i]['id'], "tags", $tags);
        }
    }    
}

/**
 * 通过uid和粉丝总数插入用户关注列表
 * @param string $uid 用户uid
 * @param string $count 用户互粉数
 * @return boolean
 */
function importbilateral($uid, $count)
{
    global $c;
    $i = 0;   
    do
    {
        $bilateral = $c->bilateral($uid, $i, 50);
        if (array_key_exists("error", $bilateral))
        {
            return FALSE;  
        }
        
        if( is_array($bilateral) )
        {
            $user = $bilateral['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {              
                insertUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        ++$i;
        $count = (int)$count - 50;
    } while ( (int)$count > 0);     
    
    return TRUE;
}

/**
 * 通过uid和粉丝总数插入用户关注列表
 * @param string $uid 用户uid
 * @param string $count 用户关注数
 * @return boolean
 */
function importfriends($uid, $count)
{
    global $c;
    $i=0;
    do
    {
        $friend = $c->friends_by_id( $uid, $i, 200 ); 
        if (array_key_exists("error", $friend))
        {
           return FALSE;  
        }
        
        if( is_array($friend) )
        {
            $user = $friend['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {
                insertUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        $i = (int)$friend['next_cursor'];       
    } while($i > 0 && $i < (int)$count);      
    
    return TRUE;
}

/**
 * 通过uid和粉丝总数插入用户粉丝列表
 * @param string $uid 用户uid
 * @param string $count 用户粉丝数
 * @return boolean
 */
function importfollowers($uid, $count)
{
    global $c;
    $i=0;
    do
    {
        $followers = $c->followers_by_id( $uid, $i, 200 ); 
        if (array_key_exists("error", $followers))
        {
            return FALSE;
        }
        
        if( is_array($followers) )
        {
            $user = $followers['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {
                insertUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        $i = (int)$followers['next_cursor'];
    } while($i > 0 && $i < (int)$count);     
    
    return TRUE;
}


    
/**
 * 通过uid更新用户信息时间列表
 * @param string $uid 用户uid
 * @return string 操作类型 更新 或 插入
 */ 
function canupdateuserinfo($uid)
{
    $type = "default";
    $time = getUserTimeInfo($uid);
    $date = date("Y-m-d H:i:s",time());
    if( count( $time ) == 0 )
    {
        $type = "import";
        insertUserTimeInfo($uid, $date);
    }
    else
    {
        $importrate = abs(strtotime($date) - strtotime($time[0]['last_import_time']))/60/60/24;
        $updaterate = abs(strtotime($date) - strtotime($time[0]['last_update_time']))/60/60/24;
        if( $importrate > 7 )
        {
           	$type = "import";
            updateUserTimeInfo($uid, "last_import_time", $date);
        }
        
        if( $updaterate > 3 )
        {
            $type = "update";
            updateUserTimeInfo($uid, "last_update_time", $date);
        }
    }
    
    return $type;
}

/**
 * 通过uid和粉丝总数更新互粉列表
 * @param string $uid 用户uid
 * @param string $count 用户互粉数
 * @return boolean
 */
function updatebilateral($uid, $count)
{
    global $c;
    $i = 0;  
    do
    {
        $bilateral = $c->bilateral($uid, $i, 50);
        if (array_key_exists("error", $bilateral))
        {
            return FALSE;    
        }
        
        if( is_array($bilateral) )
        {
            $user = $bilateral['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {              
                updateUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        ++$i;
        $count = (int)$count - 50;
    } while ( (int)$count > 0);     
    
    return TRUE;
}

/**
 * 通过uid和粉丝总数更新用户关注列表
 * @param string $uid 用户uid
 * @param string $count 用户关注数
 * @return boolean
 */
function updatefriends($uid, $count)
{
    global $c;
    $i=0;
    do
    {
        $friend = $c->friends_by_id( $uid, $i, 200 ); 
        if (array_key_exists("error", $friend))
        {
            return FALSE;   
        }
        
        if( is_array($friend) )
        {
            $user = $friend['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {
                updateUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        $i = (int)$friend['next_cursor'];
    } while($i > 0 && $i < (int)$count);     
    
    return TRUE;
}

/**
 * 通过uid和粉丝总数更新用户粉丝列表
 * @param string $uid 用户uid
 * @param string $count 用户粉丝数
 * @return boolean
 */
function updatefollowers($uid, $count)
{
    global $c;
    $i=0;
    do
    {
        $followers = $c->followers_by_id( $uid, $i, 200 ); 
        if (array_key_exists("error", $followers))
        {
            return FALSE;
        }
        
        if( is_array($followers) )
        {
            $user = $followers['users'];
            $num = count($user);
            for( $j=0; $j<$num; ++$j )
            {
                updateUserBasicInfoToDB($user[$j]);
                $tags = get_tags($user[$j]['id']);
                if ( !empty($tags) )
                {
                    updateUserInfoField($user[$j]['id'], "tags", $tags);
                }
            }
        }
        
        $i = (int)$followers['next_cursor'];
    } while($i > 0 && $i < (int)$count);    
    
    return TRUE;
}

/**
 * 导入自己
 * @param array $m 自己的对象数组
 * @return boolean
 */
function importme($m)
{
	insertUserBasicInfoToDB($m);
    $tags = get_tags($m['id']);
    if ( !empty($tags) )
    {
        updateUserInfoField($m['id'], "tags", $tags);
    }
    
    importWeiBoInfoByUID($m['id']);
    importRelation();
    
    return TRUE;
}

/**
 * 更新自己
 * @param array $m 自己的对象数组
 * @return boolean
 */
function updateme($m)
{
	updateUserBasicInfoToDB($m);
    $tags = get_tags($m['id']);
    if ( !empty($tags) )
    {
        updateUserInfoField($m['id'], "tags", $tags);
    }
    
    importWeiBoInfoByUID($m['id']);
    updateRelation();
    
    return TRUE;
}

/**
 * 通过uid获取用户发表的最近20条微博
 * @param string $uid 用户uid
 * @return boolean
 */
function importWeiBoInfoByUID($uid)
{
    global $c;
    $blog = $c->user_timeline_by_id($uid, 1, 20);
    if( is_array($blog['statuses'] ))
    {
        foreach( $blog['statuses'] as $item )
        {
           	insertWeiBoInfo($item);
        }
    }
    
    return TRUE;
}

/**
 * 通过uid和关注数获取用户关注列表
 * @param string $uid 用户uid
 * @param string $count 用户关注数
 * @return mixed
 */
function getUserFriendsJson($uid, $count)
{
	$jsonfriends = array();
    $num = 0;
    
    global $c;
    $i=0;
    do
    {
        $friend = $c->friends_by_id( $uid, $i, 200 ); 
        if (array_key_exists("error", $friend))
        {
           return FALSE;  
        }
        
        if( is_array($friend) )
        {
            $user = $friend['users'];
            for( $j=0; $j<count($user); ++$j )
            {
                $jsonfriends[$num] = array();
            	$jsonfriends[$num]['id'] = $user[$j]['id'];
                $jsonfriends[$num]['screen_name'] = $user[$j]['screen_name'];
                ++$num;
            }
        }
        
        $i = (int)$friend['next_cursor'];       
    } while($i > 0 && $i < (int)$count);    
     
	return json_encode($jsonfriends);
}

/**
 * 通过uid和关注数获取用户粉丝列表
 * @param string $uid 用户uid
 * @param string $count 用户粉丝数
 * @return mixed
 */
function getUserFollowersJson($uid, $count)
{
	$jsonfollowers = array();
    $num = 0;
    
    global $c;
    $i=0;
    do
    {
        $followers = $c->followers_by_id( $uid, $i, 200 ); 
        
        if (array_key_exists("error", $followers))
        {
            return FALSE;
        }
        
        if( is_array($followers) )
        {
            $user = $followers['users'];
            for( $j=0; $j<count($user); ++$j )
            {
                $jsonfollowers[$num] = array();
            	$jsonfollowers[$num]['id'] = $user[$j]['id'];
                $jsonfollowers[$num]['screen_name'] = $user[$j]['screen_name'];   
                ++$num;
            }
        }
        
        $i = (int)$followers['next_cursor'];
    } while($i > 0 && $i < (int)$count);   
    
	return json_encode($jsonfollowers);
}

/**
 * 通过uid和关注数获取用户互粉列表
 * @param string $uid 用户uid
 * @param string $count 用户互粉数
 * @return mixed
 */
function getUserBilateralJson($uid, $count)
{
	$jsonbilateral = array();
    $num = 0;
    
    global $c;
    $i = 0;   
    do
    {
        $bilateral = $c->bilateral($uid, $i, 50);
        
        if (array_key_exists("error", $bilateral))
        {
            return FALSE;  
        }
        
        if( is_array($bilateral) )
        {
            $user = $bilateral['users'];
            for( $j=0; $j<count($user); ++$j )
            {      
                $jsonbilateral[$num] = array();
                $jsonbilateral[$num]['id'] = $user[$j]['id'];
                $jsonbilateral[$num]['screen_name'] = $user[$j]['screen_name']; 
                ++$num;
            }
        }
        
        ++$i;
        $count = (int)$count - 50;
    } while ( (int)$count > 0);   
    
	return json_encode($jsonbilateral);
}

/**
 * 导入用户关系
 */
function importRelation()
{
	global $me;
    $friends = getUserFriendsJson($me['id'], $me['friends_count']);
	$followers = getUserFollowersJson($me['id'], $me['followers_count']);
    $bilateral = getUserBilateralJson($me['id'], $me['bi_followers_count']);
    
    if ($friends != FALSE && $followers != FALSE && $bilateral != FALSE)
    {  
    	insertUserRelation($me['id'], $friends, $followers, $bilateral);
    }
}

/**
 * 更新用户关系
 */
function updateRelation()
{
    global $me;
    $friends = getUserFriendsJson($me['id'], $me['friends_count']);
	$followers = getUserFollowersJson($me['id'], $me['followers_count']);
    $bilateral = getUserBilateralJson($me['id'], $me['bi_followers_count']);
    
    if ($friends != FALSE && $followers != FALSE && $bilateral != FALSE)
    {  
    	updateUserRelation($me['id'], $friends, $followers, $bilateral);
    }
}

/**
* 获取用户教育信息
* @param string $uid 用户uid
*/
function getUserEducationInfo($uid)
{
    global $c;
    $education = $c->account_education($uid);
    if(is_array($education))
    {
        foreach($education as $edu)
        {
            print_r($edu);
        }
    }
}

/**
* 获取用户职业信息
* @param string $uid 用户uid
*/
function getUserCareerInfo($uid)
{
    global $c;
    $career = $c->account_career($uid);
    if(is_array($career))
    {
        foreach($career as $car)
        {
            print_r($car);
        }
    }
}

?>

<?php

if ( !empty( $_REQUEST['action'] ) )
{  
    $result = TRUE;
    $type = canupdateuserinfo($uid['uid']);
	if ($type == "import")
    {
		importme($me);
        $result = importbilateral($uid['uid'], $me['bi_followers_count']);
  	}
  	else if ($type == "update")
  	{
  		updateme($me);
        $result = updatebilateral($uid['uid'], $me['bi_followers_count']);		
  	}
        
    if ($result == TRUE)
    {
    	echo "<script>alert('粉丝列表导出完成！');location.href='search.html'</script>"; 
    }
    else
    {
    	echo "<script>alert('接口调用达到上限！');location.href='search.html'</script>";     
    }
}

?>