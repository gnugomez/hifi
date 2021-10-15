<h1>Login</h1>
<a href="<? echo $this->core->generate('home') ?>">Home</a>
<a href="<? echo $this->core->generate('register') ?>">Registro</a>
<?php
global $DB;
echo "<pre>" . print_r($this->errors, true) . "</pre>"; ?>
<form action="" method="post">
	<input type="text" name="user" placeholder="Username">
	<input type="password" name="pass" placeholder="Password">
	<button type="submit">Entrar</button>
</form>