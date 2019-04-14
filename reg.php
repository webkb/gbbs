<?php
require './setting.php';
/** 注册 */
if (isset($_POST['action']) && $_POST['action'] == 'reg') {
	if(! defined('REQUEST_TYPE')) {define('REQUEST_TYPE', 'ajax');}

	$returnMsg = reg();
	echo $returnMsg;
	exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>注册 Networm</title>
<link rel="stylesheet/less" href="static/style.less" />
<script src="static/less.min.js"></script>
<script src="static/common.js"></script>
</head>
<body class="login">
	<div class="login_outer">
		<div class="errorMsgDiv">
			<div id="notice" class="errorMsg"></div>
		</div>
		<h1>注册 Networm</h1>
		<div class="logindiv">
			
			<form class="loginform" action="" method="post">
				<div class="username">
					<label>邮箱</label>
					<input class="f2" placeholder="" name="mail" tabindex="1" />
				</div>
				<div class="username">
					<label>账号</label>
					<input class="f2" placeholder="" name="username" tabindex="1" />
				</div>
				<div class="username bfc">
					<label class="left">密码</label>
					<span class="right forget_password"><a href="login.php">登陆</a></span>
					<input class="f2" placeholder="" name="password" type="password" tabindex="2" />
				</div>
				<input class="submit" type="submit" value="注册" tabindex="3" />
			</form>
		</div>
	</div>
<script>
document.getElementsByClassName("loginform")[0].onsubmit = function () {
	document.getElementById("notice").innerHTML = '';
	document.getElementById("notice").className = 'errorMsg';
	document.getElementsByClassName("submit")[0].value = '注册中。。。';
	var data = 'action=reg&mail=' + document.getElementsByName("mail")[0].value + '&username=' + document.getElementsByName("username")[0].value + '&password=' + document.getElementsByName("password")[0].value;
	xc_ajax.post(this.action, data ,function(msg) {
		if (msg) {
			document.getElementsByClassName("submit")[0].value = '注册';
			document.getElementById("notice").innerHTML = msg;
			document.getElementById("notice").className = 'errorMsg loginStatus';
		} else {
			document.querySelector(".login_outer h1").className = 'loginStatus';
			location = 'login.php';
		}
	});
	return false;
}
</script>
</body>
</html>