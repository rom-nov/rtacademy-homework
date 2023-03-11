<?php
declare( strict_types = 1 );
namespace Models;
class CountryModel
{
	public function get_list() : array
	{
		try
		{
			$host = 'rtacademy_database_mariadb';
			$port = '3306';
			$dbname = 'helloworld';
			$dbuser = 'helloworld';
			$dbpass = 'helloworld';

			$DB = new \PDO( "mysql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass );
			$statment = $DB -> query(
				'SELECT name
						  FROM countries
						  ORDER BY name ASC',
				\PDO::FETCH_ASSOC );
			$result = $statment->fetchAll( \PDO::FETCH_COLUMN, 0 );
		}
		catch( \PDOException $error )
		{
			die( 'Помилка БД: ' . $error -> getMessage() );
		}
		$DB = null;
		$statment = null;
		return $result;
	}
}