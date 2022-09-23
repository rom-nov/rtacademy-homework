<?php
spl_autoload_register( fn( $class ) => require './' . str_replace('\\', '/', $class ) . '.php' );
$country = ( new Models\CountryModel() ) -> get_countries_list();
var_dump( $country );
//	try
//	{
//		$host = 'rtacademy_database_mariadb';
//		$port = '3306';
//		$dbname = 'helloworld';
//		$dbuser = 'helloworld';
//		$dbpass = 'helloworld';
//
//		$countries = new \PDO( "mysql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass );
//
//		var_dump( $countries );
//		$countries = null;
//	}
//	catch( \PDOException $error )
//	{
//		die( 'Помилка БД: ' . $error -> getMessage() );
//	}