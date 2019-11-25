<?php
require './setting.php';
loginCheck();
	if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
		$id = $_GET['id'];
		$page = mysql_getrow("SELECT warm_page.*, warm_setting.username FROM warm_page LEFT JOIN warm_setting ON warm_setting.uid=warm_page.uid WHERE id=$id");
		if (empty($page)){
			backreferer('找不到对应的文章');
		} elseif (! $page->is_pub){
			backreferer('页面没有公开');
		}
		$gallery = explode(';',$page->gallery);
		$cid = $page->cid;
		$navbar_id = $id;
		$post = mysql_select("SELECT warm_post.*, warm_setting.username FROM warm_post LEFT JOIN warm_setting ON warm_setting.uid=warm_post.uid WHERE id=$id");
	} else {
		backreferer('找不到对应的文章');
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $page->title; ?> Networm</title>
<link rel="stylesheet/less" href="static/style.less" />
<link rel="stylesheet/less" href="static/gbbs.css" />
<script src="static/less.min.js"></script>

<link rel="stylesheet" href="<?php echo EDITOR_P; ?>/themes/default/default.css" />
<script charset="utf-8" src="<?php echo EDITOR_P; ?>/kindeditor-all-min.js"></script>
<script charset="utf-8" src="<?php echo EDITOR_P; ?>/lang/zh-CN.js"></script>
<script>
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});
	});
</script>
</head>
<body>
<div class="wrapper">
	<div class="header-wrapper">
		<div class="header bfc">
			<div class="nav left">
				<a class="btn logo" href="index.php">Networm</a><?php echo navbar_url($navbar_id); ?>
			</div>
			<div class="act right">
	<?php if (LOGIN_ID): ?>
				<a class="btn" href="write.php?id=<?php echo $id; ?>">编辑</a>
	<?php else: ?>
				<a class="btn" href="login.php">登录</a>
				<a class="btn" href="reg.php">注册</a>
	<?php endif; ?>
			</div>
		</div>
	</div>
		<div class="main">
			<h1 class="thin"><?php echo $page->title; ?></h1>
		</div>
		<div class="main bfc post_item">
			<div class="left">
				<img src="http://bbs-static.smartisan.com/uc_server/data/avatar/000/16/07/88_avatar_middle.jpg" /><br />
				<?php echo $page->username; ?>
			</div>
			<div class="right">
				<?php echo $page->createtime; ?><br />
				<?php if (LOGIN_ID == $page->uid): ?> <a class="btn" href="write.php?id=<?php echo $page->id; ?>">编辑</a><?php endif; ?>
			</div>
			<div class="bfc acontent">
				<?php echo $page->content; ?>
			</div>
		</div>
<?php foreach ($post as $item): ?>
		<div class="main bfc post_item">
			<div class="left">
				<img src="http://bbs-static.smartisan.com/uc_server/data/avatar/000/16/07/88_avatar_middle.jpg" /><br />
				<?php echo $item->username; ?>
			</div>
			<div class="right">
				<?php echo $item->createtime; ?><br />
				<?php if (LOGIN_ID == $item->uid): ?> <a class="btn" href="write.php?action=edit_post&post_id=<?php echo $item->post_id; ?>">编辑</a><?php endif; ?>
			</div>
			<div class="bfc acontent">
				<?php echo $item->content; ?>
			</div>
		</div>
<?php endforeach; ?>
<?php if (LOGIN_ID): ?>
		<div class="main bfc post_item">
			<div class="left">
				<img src="http://bbs-static.smartisan.com/uc_server/data/avatar/000/16/07/88_avatar_middle.jpg" />
			</div>
			<div class="right">
				&nbsp;
			</div>
			<div class="bfc acontent">
				<form class="bfc" name="post" action="save.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?php echo $id;?>" />
					<input type="hidden" name="action" value="add_post" />
					<div class="">
						<textarea name="content" rows="12" style="width:98%"></textarea>
					</div>
					<a class="submitbtn btn" href="javascript:editor.sync();document.forms[0].submit()">保存</a>
				</form>
			</div>
		</div>
<?php endif; ?>
<script src="static/common.js"></script>
</body>
</html>