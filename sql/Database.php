<?php

class Database
{
	private $host = "localhost";
	private $db_name = "authentification";
	private $username = "root";
	private $password = "";
	public $pdo;

	public function dbConnection()
	{
		$this->pdo = null;
		try {
			$this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $exception) {
			echo "Connexion error: " . $exception->getMessage();
		}
		return $this->pdo;
	}
}