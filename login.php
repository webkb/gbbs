<?php
require './setting.php';
/** 登陆 */
if (isset($_POST['action']) && $_POST['action'] == 'login') {
	if (! defined('REQUEST_TYPE')) {
		define('REQUEST_TYPE', 'ajax');
	}
	$returnMsg = login();
	echo $returnMsg;
	exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>登录 Networm</title>
<link rel="stylesheet/less" href="static/style.less" />
<script src="static/less.min.js"></script>
<script src="static/common.js"></script>
</head>
<body class="loginwrapper">
	<div class="login">
		<div class="loginnotice">
			<h1>登录 Networm</h1>
			<div></div>
		</div>
		<form class="loginform" action="login.php" method="post">
			<label>
				<div class="bfc">
					<span>帐号</span>
					<span class="right forget_password"><a class="link" href="reg.php">注册</a></span>
				</div>
				<input class="f2" placeholder="" name="username" tabindex="1" />
			</label>
			<label>
				<div class="bfc">
					<label class="left">密码</label>
					<span class="right forget_password"><a class="link" href="reset.php">忘记密码？</a></span>
				</div>
				<input class="f2" placeholder="" name="password" type="password" tabindex="2" />
			</label>
			<input class="submit" type="submit" value="登录" tabindex="3" />
		</form>
	</div>
<script>
document.getElementsByClassName("loginform")[0].onsubmit = function () {
	var username = document.getElementsByName("username")[0].value;
	var password = document.getElementsByName("password")[0].value;
	var data = 'action=login&username=' + username + '&password=' + password;

	var loginHeader = document.querySelector(".loginnotice h1");
	var loginNotice = document.querySelector(".loginnotice div");
	var loginSubtmit = document.getElementsByClassName("submit")[0];

	loginHeader.className = 'submitStatus';
	loginNotice.className = '';
	loginNotice.innerHTML = '';

	loginSubtmit.value = '登录中。。。';
	loginSubtmit.disabled = true;

	xc_ajax.post(this.action, data ,function(response) {
		response = JSON.parse(response);
		if (response.success) {
			if (document.referrer.indexOf('reg.php')<0 || document.referrer.indexOf('login.php')<0 || document.referrer.indexOf('logout.php')<0) {
				location = 'index.php';
			} else {
				location = document.referrer;
			}
		} else {
			setTimeout(() => {
				loginHeader.className = '';
			}, 3000);
			loginNotice.className = 'errorStatus';
			loginNotice.innerHTML = response.msg;

			loginSubtmit.value = '登录';
			loginSubtmit.disabled = false; 
		}
	});
	return false;
}
</script>
</body>
</html>