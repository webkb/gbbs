<?php
require './setting.php';
	adminLoginCheck();

	$data = getItemData();
	$action		= isset($_POST['action'])									? $_POST['action']								: null;
	$id			= isset($_POST['id'])		&& is_numeric($_POST['id'])		? (int) $_POST['id']							: null;
	$post_id	= isset($_POST['post_id'])	&& is_numeric($_POST['post_id'])? (int) $_POST['post_id']						: null;
	$issetting	= isset($_POST['issetting'])								? (int) $_POST['issetting']						: null;
	$password	= isset($_POST['password'])									? db_real_escape_string($_POST['password'])		: '';

	if ($issetting) {
		if ($password == '') {
			mysql_xquery("UPDATE `warm_setting` SET $data");
		} else {
			mysql_xquery("UPDATE `warm_setting` SET $data, `password` = MD5(CONCAT(salt, '$password'))");
		}
		jump($_SERVER['HTTP_REFERER'], '编辑成功');
	}

	//post
	if (is_int($post_id) && $action == 'edit_post') {
		if (mysql_xquery("UPDATE `warm_post` SET $data WHERE post_id=$post_id"))
		{
			jump($_SERVER['HTTP_REFERER'], '编辑成功');
		}
	} elseif (is_int($id) && $action == 'add_post') {
		if (mysql_xquery("INSERT INTO `warm_post` SET $data"))
		{
			$post_id = $mysqli->insert_id;
			jump("page.php?id=$id#post_id=$post_id", '发表成功');
		}
	} else

	//page
	if (is_int($id)) {
		if (mysql_xquery("UPDATE `warm_page` SET $data WHERE id=$id"))
		{
			jump($_SERVER['HTTP_REFERER'], '编辑成功');
		}
	} else {
		if (mysql_xquery("INSERT INTO `warm_page` SET $data"))
		{
			$id = $mysqli->insert_id;
			jump("page.php?id=$id", '发表成功');
		}
	}
?>
