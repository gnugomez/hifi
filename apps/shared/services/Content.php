<?php

namespace JGomez\Services;

use JGomez\Providers\Service;
use JGomez\Database;

final class ContentService extends Service
{
	private Database $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function getAllUsers(): array
	{
		return $this->db->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
	}

	public function getUser(string $username, string $email = ""): array
	{
		$stmt = $this->db->prepare("SELECT * FROM users where username = ? OR email = ?");
		$stmt->bind_param("ss", $username, $email);
		$stmt->execute();
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}

	public function addUser(string $email, string $username, string $password): bool
	{
		$stmt = $this->db->prepare("INSERT INTO USERS(email, username, password) VALUES(?, ?, ?)");
		$stmt->bind_param("sss", $email, $username, $password);
		return $stmt->execute();;
	}
}
