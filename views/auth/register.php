<h1>Register</h1>
<a href="<? echo $this->core->generate('home') ?>">Home</a>
<a href="<? echo $this->core->generate('login') ?>">Login</a>
<?php
global $DB;
echo "<pre>" . print_r($this->errors, true) . "</pre>";
?>
<form action="" method="post">
	<input type="text" name="user" placeholder="Username">
	<input type="email" name="email" placeholder="Email">
	<input type="password" name="pass" placeholder="Password">
	<input type="password" name="cpass" placeholder="Confirm password">
	<button type="submit">Registrame</button>
</form>