<?php 

class Db
{
	public function getConnection()
	{
		$dsn = "mysql:host=localhost; dbname=TestApp";
		$user = "root";
		$password = "";

		$db = new PDO($dsn, $user, $password);

		return $db;
	}
}

 ?>