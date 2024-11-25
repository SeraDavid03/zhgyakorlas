<?php
	function getDb($hostname = "localhost", $port = 3306, $username = "root", $password = "", $dbname = "adatbazis"): PDO
	{
		// Kapcsolodas az adatbazishoz
		try {
			$db = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname", $username, $password);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $db;
		}
		catch (PDException $e){
			echo 'sikertelen kapcsolodas' . $e->getMessage();
			exit;
		}
	}
?>