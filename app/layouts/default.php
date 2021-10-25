<!DOCTYPE html>
<html lang="es">

<?php
global $core;
$core->renderTemplate("head") ?>
<style>
	input {
		display: block;
	}
</style>

<body>
	<?php $core->renderView() ?>
	<?php $core->renderTemplate("footer") ?>
</body>

</html>