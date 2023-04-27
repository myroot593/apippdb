<?php 

namespace App\Connection;
use \PDO;

Class Database
{
	private $host="localhost";
	private $user="root";
	private $pass ="";
	private $db = "ppdbpro";
	protected $connection;
	public function __construct()
	{
		try
		{
			$this->connection = new PDO("mysql:host=$this->host; dbname=$this->db",$this->user, $this->pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->connection;
		}
		catch(PDOException $e)
		{
			echo $e->getMessgae();
		}
		
	}
	public function __destruct()
	{
		$this->connection = null;
		
	}
}

