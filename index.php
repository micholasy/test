<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


//从POST过来的signed_request中提取oauth2信息
if(!empty($_REQUEST["signed_request"])){
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY  );
	$data=$o->parseSignedRequest($_REQUEST["signed_request"]);
	if($data=='-2'){
		 die('签名错误!');
	}else{
		$_SESSION['oauth2']=$data;
	}
}

//判断用户是否授权
if (empty($_SESSION['oauth2']["user_id"]))
{
	include "auth.php";
	exit;
} 
else 
{
	include "search.html";
    exit;
} 

?>

