<section id="home">
	<h1>Under construct</h1>
	<b> <?php
		if ($this->core->session->user) {
			echo "Bienvenido " . $this->core->session->user[0]["username"];
		}
		?></b></br>
	<a href="<? echo $this->core->generate('login') ?>">Login</a>
	<a href="<? echo $this->core->generate('register') ?>">Registro</a>
	<a href="<? echo $this->core->generate('logout') ?>">Logout</a>
</section>