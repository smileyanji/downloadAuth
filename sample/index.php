<?php
include_once './inc/config.inc' ;
$title = 'Menu - Demo' ;
?>

<?php include_once INC . DIRECTORY_SEPARATOR . 'header.inc' ; ?>

	<div>
		<h2>Storage</h2>
		<p><a href="./storage/select.php" > - select</a></p>
		<p><a href="./storage/space.php" > - space</a></p>
	</div>

	<div>
		<h2>Folder</h2>
		<p><a href="./folder/select.php" > - select</a></p>
		<p><a href="./folder/create.php" > - create</a></p>
	</div>

	<div>
		<h2>Contents</h2>
		<p><a href="./contents/" > - contents</a></p>
	</div>
</body>
</html>