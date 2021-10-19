<?php
class database extends mysqli
{

	public function __construct()
	{
		parent::__construct(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE_NAME);

		if ($this->connect_error) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . $this->connect_errno . PHP_EOL;
			echo "Debugging error: " . $this->connect_error . PHP_EOL;
			exit;
		}
	}

	public function query($query, $result_mode = MYSQLI_STORE_RESULT)
	{

		$superQuery = parent::query($query, $result_mode);

		if (!$superQuery) {
			$this->showError();
		}

		return $superQuery;
	}

	public function showError(): void
	{
		if (!IS_DEBUG) return;
		echo "Error: $this->error";
	}



	/* 	public function addFonsi($username, $password, $email)
	{
		return $this->query("INSERT INTO fonsi (`username`,`passwd`,`email`) VALUES ('" . $username . "', '" . $password . "', '" . $email . "')");
	} */
}
