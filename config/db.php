<?php

class Database
{
	private static $bdd = null;

	public static function getBdd() {
		if (!isset(self::$bdd)) {
			try { 
				self::$bdd = new PDO(ADB_HOST, ADB_USER, ADB_PASS);
				self::$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			} 
			catch (PDOExeption $e) { echo $e; }
		}
		return self::$bdd;
	}
}