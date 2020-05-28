<?php

class Database
{
	private static $bdd = null;
	
	public function __construct() {}

	public static function getBdd() {
		if (!isset(self::$bdd)) {
			try { 
				self::$bdd = new PDO('mysql:host='.ADB_HOST.';dbname='.ADB_NAME.';charset=utf8', ADB_USER, ADB_PASS);
			} 
			catch (PDOExeption $e) { echo $e; }
		}
		return self::$bdd;
	}
}