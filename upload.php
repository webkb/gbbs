<?php
/*
 * Upload Image
 * 2015-09-29
 */
require './setting.php';

$id = $_GET['id'];
if ($_FILES['imgfile'] && ! empty($_FILES)) {
	$img = upload();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title></title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="mgfile" id="imgfile" onchange="submit()"  />
<?php if ($img): ?>
	<span>上传成功</span>
<script>
	parent.document.getElementById("<?php echo $id; ?>").value="<?php echo $img; ?>";
	parent.document.getElementById("<?php echo $id; ?>file").src="../<?php echo $img; ?>";
</script>
<?php else: ?>
	<span>上传失败</span>
<?php endif; ?>
</form>
</body>
</html>