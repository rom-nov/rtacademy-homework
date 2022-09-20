<?php
declare( strict_types = 1 );
namespace Models;
class CountryModel
{
	public function get_countries_list() : \PDO
	{
		try
		{
			//$host = '127.0.0.1';
			$host = 'rtacademy_database_mariadb';
			$port = '3306';
			$dbname = 'helloworld';
			$dbuser = 'helloworld';
			$dbpass = 'helloworld';

			$countries = new \PDO( "mysql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass );
		}
		catch( \PDOException $error )
		{
			die( 'Помилка БД: ' . $error -> getMessage() );
		}
		return $countries;
	}
}