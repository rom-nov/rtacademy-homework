<?php
declare( strict_types = 1);
namespace Models;
abstract class Model
{
	public const HOST = 'rtacademy_database_mariadb';
	public const PORT = '3306';
	public const DBNAME = 'helloworld';
	public const DBUSER = 'helloworld';
	public const DBPASS = 'helloworld';

	public static function DB() : \PDO
	{
		return new \PDO( 'mysql:host='.self::HOST.';port='.self::PORT.';dbname='.self::DBNAME, self::DBUSER, self::DBPASS );
	}
}