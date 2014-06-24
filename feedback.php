<?php
header('Content-Type: text/html; charset=UTF-8');

require_once("dbutils.php");
    
function sendmail($smtp)
{
    $mail = new SaeMail();
    $opt = array
    (    
    	'from' 			=> '=?UTF-8?B?'.base64_encode($smtp['sitename']).'?=<'.$smtp['user'].'>',
        'to'			=> $smtp['to'],
        'smtp_host'		=> $smtp['host'],
        'smtp_port'		=> $smtp['port'],
        'smtp_username'	=> $smtp['user'],
        'smtp_password'	=> $smtp['pass'],
        'subject'		=> $smtp['subject'],
        'content'		=> $smtp['body'],
        'content_type'	=> 'HTML'   
    );
    
    $mail->setOpt( $opt );
    $ret = $mail->send();
    if ($ret == false)
    {
         var_dump($mail->errno(), $mail->errmsg());
    }   
}

if ( !empty($_POST['author']) && !empty($_POST['email']) && !empty($_POST['comment']) )
{
 	$author = $_POST['author'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    
    insertUserFeedback($author, $email, $comment);
    
    $date = date("Y-m-d", time());
    $html = sprintf(file_get_contents('mail.html'), $date); 
    
    $smtp = array();
    $smtp['sitename'] = '艾数分析';
    $smtp['user'] = 'ianalysis@163.com';
    $smtp['to'] = $email;
    $smtp['host'] = 'smtp.163.com';
    $smtp['port'] = 25;
    $smtp['pass'] = '13800138000';
    $smtp['subject'] = '艾数分析-反馈报告';
    $smtp['body'] = $html;
    sendmail($smtp);
 
  	if ($ret === false)
    {
         var_dump($mail->errno(), $mail->errmsg());
    }
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

#thanks {
 width: 750px;
 background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.03);
 border: 1px solid rgba(0, 0, 0, 0.08);
 color: #444;
 margin-top:300px;
}
    
p{
  font:14px "宋体";
}
    
span {
font:16px "宋体";        
color:#7cfc00; 
}
        
    
</style>
</head>

<body>
  
<div id="thanks">	
    <img src="http://ianalysis-ianalysis.stor.sinaapp.com/success.png" width="181" height="33" />
    <p>感谢您对艾数分析的支持，我们会尽快对您提出的反馈进行处理，并第一时间回复您的邮箱，请注意查看，谢谢。</p>	
    <br/><br/>
    <a href="search.html">返回首页</a> 
</div>

</body>
</html>