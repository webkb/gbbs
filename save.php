<?php
require './setting.php';
	$tid		= isset($_POST['tid'])		&& is_numeric($_POST['tid'])	? (int) $_POST['tid']							: 0;
	$cid		= isset($_POST['cid'])		&& is_numeric($_POST['cid'])	? (int) $_POST['cid']							: 0;
	$id			= isset($_POST['id'])		&& is_numeric($_POST['id'])		? (int) $_POST['id']							: null;
	$post_id	= isset($_POST['post_id'])	&& is_numeric($_POST['post_id'])? (int) $_POST['post_id']						: null;
	$password	= isset($_POST['password'])									? db_real_escape_string($_POST['password'])		: '';
	$issetting	= isset($_POST['issetting'])								? db_real_escape_string($_POST['issetting'])	: '';

	$action		= isset($_POST['action'])									? db_real_escape_string($_POST['action'])		: '';
//edit setting
	if (! empty($issetting)) {
		$data = getItemData();
		if ($password == '') {
			mysql_xquery("UPDATE `warm_setting` SET $data");
		} else {
			mysql_xquery("UPDATE `warm_setting` SET $data, `password` = MD5(CONCAT(salt, '$password'))");
		}
		jump($_SERVER['HTTP_REFERER'], '编辑成功');
	}

	if (is_int($id) && $action == 'edit_post') {
		$data = getItemData();
		if (mysql_xquery("UPDATE `warm_post` SET $data WHERE post_id=$post_id "))
		{
			jump($_SERVER['HTTP_REFERER'], '编辑成功');
		}
	} elseif (is_int($id) && $action == 'add_post') {
		$data = getItemData();
		if (mysql_xquery("INSERT INTO `warm_post` SET $data"))
		{
			$post_id = $mysqli->insert_id;
			jump("page.php?id=$id#post_id=$post_id", '发表成功');
		}
	} elseif (is_int($id)) {
		$data = getItemData();
		if (mysql_xquery("UPDATE `warm_page` SET $data WHERE id=$id"))
		{
			jump($_SERVER['HTTP_REFERER'], '编辑成功');
		}
	} else {
		$data = getItemData();
		if (mysql_xquery("INSERT INTO `warm_page` SET $data"))
		{
			$id = $mysqli->insert_id;
			jump("page.php?id=$id", '发表成功');
		}
	}
?>
