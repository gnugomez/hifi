<?php
$DB = new database(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE_NAME);

class database extends mysqli
{
	public function __construct(string $hostname = null, string $username = null, string $password = null, string $database = null, int $port = null, string $socket = null)
	{
		parent::__construct($hostname, $username, $password, $database, $port, $socket);

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

	/* 	public function getFonsi(): array
	{
		return $this->query("SELECT * FROM fonsi")->fetch_all(MYSQLI_ASSOC);
	}

	public function addFonsi($username, $password, $email)
	{
		return $this->query("INSERT INTO fonsi (`username`,`passwd`,`email`) VALUES ('" . $username . "', '" . $password . "', '" . $email . "')");
	} */
}
