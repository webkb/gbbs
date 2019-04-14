<?php
function isindex($id = 0) {
	return stripos($_SERVER['PHP_SELF'], 'list.php') && $id==0;
}
function islist($id) {
	return stripos($_SERVER['PHP_SELF'], 'list.php') && $id!=0;
}
function ispage() {
	return stripos($_SERVER['PHP_SELF'], 'page.php');
}
function ishome() {
	return stripos($_SERVER['PHP_SELF'], 'home.php');
}
function islogin() {
	return stripos($_SERVER['PHP_SELF'], 'login.php');
}

function navbar($id = 0, $navbar = ''){
	if (! $id) return $navbar;
	$row = mysql_getrow("SELECT * FROM warm_page WHERE id=$id");
	if ($row->type) {
		$navbar = $navbar . $row->title . " < ";
	}
	return navbar($row->cid, $navbar);
}
function navbar_url($id = 0, $navbar = ''){
	if (! $id) return $navbar;
	$row = mysql_getrow("SELECT * FROM warm_page WHERE id=$id");
	if ($row->type) {
		$navbar = " <span>></span> <a class='link' href='list.php?id=$id'>" . $row->title . "</a>" . $navbar;
	}
	return navbar_url($row->cid, $navbar);
}

function getPostData() {
	$filed=array(
		'id',
		'title','content',
		'img','intro','gallery',
		'page_title','page_kw','page_ds',
		'cid',
		'pid',
		'type',
		'company','address','tel','fax','zip','mail','contact','mobile','username'
	);
	$data = array();
	if (! empty($_POST)) {//print_r($_POST);exit;
		if (isset($_POST['gallery'])) {
			$_POST['gallery'] = implode(';',$_POST['gallery']);
		}
		foreach ($filed as $f) {
			if (isset($_POST[$f])) {
				$v = db_real_escape_string($_POST[$f]);
				if (! is_numeric($v)) {
					$v = "'$v'";
				}
				$data[] = "`$f` = " . $v;
			}
		}
		return implode(', ',$data);
	}
}

function getItemData() {
	return getPostData() . ", `uid` = " . LOGIN_ID;
}
/**
* 注册
*/
function reg()
{
	global $lang;
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		return false;
	}
	$mail = isset($_POST['mail']) ? db_real_escape_string($_POST['mail']) : '';
	$username = isset($_POST['username']) ? db_real_escape_string($_POST['username']) : '';
	$password = isset($_POST['password']) ? db_real_escape_string($_POST['password']) : '';
	if (! is_mail($mail)) {
		return $lang['mail_is_error'];
	}
	if (getDBmail($mail)) {
		return $lang['mail_is_exist'];
	}
	if (! empty($mail) && ! empty($username) && ! empty($password)){
		if (! getDBusername($username)) {
				regDB($mail, $username, $password);
		} else {
			return $lang['username_is_exist'];
		}
	} else {
		return $lang['input_is_error'];
	}
}
/**
* 注册数据库
*/
function regDB($mail, $username, $password)
{
	$salt 		= microtime(true);
	$password	= md5($salt . $password);
	$check		= md5($salt . $password);
	
	$query = "INSERT INTO `warm_setting` SET `mail`='$mail', username='$username', `salt`='$salt', `password`='$password', `check`='$check'";
	mysql_xquery($query);
}
/**
* 登陆
*/
function login()
{
	global $lang;
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		return json_encode(array('status' => 'error', 'content' => $lang['request_method_error'], ));
	}
	$username = isset($_POST['username']) ? db_real_escape_string($_POST['username']) : '';
	$password = isset($_POST['password']) ? db_real_escape_string($_POST['password']) : '';
	if (! empty($username) && ! empty($password)){
		if ($setting = getDBusername($username)) {
			if ($setting->password == md5($setting->salt . $password)) {
				loginSet($setting, $password);
				return json_encode(array('success' => true, 'msg' => $lang['login_success'], ));
			} else {
				return json_encode(array('error' => true, 'msg' => $lang['password_is_error'], ));
			}
		} else {
			return json_encode(array('error' => true, 'msg' => $lang['username_is_error'], ));
		}
	} else {
		return json_encode(array('error' => true, 'msg' => $lang['input_is_error'], ));
	}
}
/**
* 登陆：根据数据库检查字符串
*/
function getDBmail($string)
{
	$string = db_real_escape_string($string);
	$row = mysql_getrow("SELECT `salt`, `password`, `check`, `uid`, `username`  FROM `warm_setting` WHERE mail = '$string'");
	return $row;
}
/**
* 登陆：根据数据库检查用户名
*/
function getDBusername($username)
{
	$username = db_real_escape_string($username);
	$row = mysql_getrow("SELECT `salt`, `password`, `check`, `uid`, `username`  FROM `warm_setting` WHERE username = '$username'");
	return $row;
}
/**
* 登陆：根据数据库检查检查
*/
function getDBcheck($check)
{
	$check = db_real_escape_string($check);
	$row = mysql_getrow("SELECT `salt`, `password`, `check`, `uid`, `username`  FROM `warm_setting` WHERE `check` = '$check'");
	return $row;
}
/**
* 自动登陆：登陆成功后重置数据库和Cookie
*/
function loginSet($setting, $password = null)
{
	$_SESSION['member'] = $setting;
	$username	= $setting->username;
	$salt		= $setting->salt;
	$check		= $setting->check;
	$salt		= md5($salt . microtime(true));
	$check		= md5($salt . $check);

	if (isset($password)) {
		$password	= md5($salt . $password);
		$query		= "UPDATE `warm_setting` SET `salt` = '$salt', `password` = '$password', `check` = '$check' WHERE username = '$username'";
		mysql_xquery($query);
	}

	$query		= "UPDATE `warm_setting` SET `check` = '$check' WHERE username = '$username'";
	mysql_xquery($query);
	setcookie('member_check', $check, time()+3600*24);
}
/**
* 自动登陆：检查是否可以自动登陆
*/
function autoLoginCheck()
{
	if (isset($_COOKIE['member_check'])) {
		$check		= $_COOKIE['member_check'];
		if ($setting = getDBcheck($check)) {
			loginSet($setting);
			return true;
		}
	}
}
/**
* 登陆检查
*/
function loginCheck()
{
	session_start();

	if (isset($_SESSION['member']) || autoLoginCheck()) {
		define('LOGIN_ID', $_SESSION['member']->uid);
		define('LOGIN_USERNAME', $_SESSION['member']->username);
		return true;
	} else {
		define('LOGIN_ID', 0);
		return false;
	}
}

function adminLoginCheck()
{
	$loginStatus = loginCheck();
	if (! $loginStatus && ! islogin()) {
		jump('login.php');
	}
}
?>