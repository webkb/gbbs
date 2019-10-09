<?php
require './setting.php';
loginCheck();
	if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
		$id = $_GET['id'];
		$page = mysql_getrow("SELECT * FROM warm_page WHERE id=$id");
		if (empty($page)){
			backreferer('找不到对应的文章');
		} elseif (! $page->is_pub){
			backreferer('页面没有公开');
		}
		if ($page->type==0){
			jump("page.php?id=$id");
		}
	} else {
		$id = 0;
	}
	$navbar_id = $id;

	$list = mysql_select(
		"SELECT * FROM warm_page WHERE cid=$id AND is_pub=1 ORDER BY type DESC, createtime DESC",
		"SELECT COUNT(*) FROM warm_page WHERE cid=$id AND is_pub=1", 10
	);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo navbar($navbar_id); ?>Networm</title>
<link rel="stylesheet/less" href="static/style.less" />
<script src="static/less.min.js"></script>
</head>
<body>
<div class="wrapper">
	<div class="header-wrapper">
		<div class="header bfc">
			<div class="nav left">
				<a class="btn logo" href="index.php">Networm</a><?php echo navbar_url($navbar_id); ?>
			</div>
			<div class="act right">
<?php if (isindex($id)): ?>
				<a class="btn" href="write.php?cid=<?php echo $id; ?>">新建</a>
				<a class="btn" href="home.php">设置</a>
	<?php if (LOGIN_ID): ?>
				<span class=""><?php echo LOGIN_USERNAME; ?></span>
				<a class="btn" href="logout.php">退出</a>
	<?php else: ?>
				<a class="btn" href="login.php">登录</a>
				<a class="btn" href="reg.php">注册</a>
	<?php endif; ?>
<?php else: ?>
	<?php if (LOGIN_ID): ?>
				<a class="btn" href="write.php?cid=<?php echo $id; ?>">新建</a>
				<a class="btn" href="write.php?id=<?php echo $id; ?>">编辑</a>
	<?php endif; ?>
				<a class="btn" href="page.php?id=<?php echo $id; ?>" target="_blank">查看</a>
<?php endif ?>
			</div>
		</div>
	</div>
	<div class="main">
<?php if (! empty($list)): ?>
		<ul class="reset list">
<?php
foreach ($list as $key => $value) {
	$imgtype = $value->type ? '&#x1f4c1;' : '&#x1f4c4;';
	$id = $value->id;
	$title = $value->title;
	$createtime = $value->createtime;
	echo <<<html
			<li class="bfc">$imgtype <a href="list.php?id=$id">$title</a> <span class="right">$createtime</span></li>

html;
}
?>
		</ul>
<?php pagebar(); ?>
<?php else: ?>
		<p class="empty_list">还没有内容，<a class="link" href="write.php?cid=<?php echo $id; ?>">新建一个</a></p>
<?php endif; ?>
	</div>
</div>
</body>
</html>