<?php
$DB = new database();

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

	public function getAllUsers(): array
	{
		return $this->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
	}

	public function getUser(string $username): array
	{
		$stmt = $this->prepare("SELECT * FROM users where username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}

	/* 	public function addFonsi($username, $password, $email)
	{
		return $this->query("INSERT INTO fonsi (`username`,`passwd`,`email`) VALUES ('" . $username . "', '" . $password . "', '" . $email . "')");
	} */
}
