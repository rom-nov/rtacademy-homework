<?php
declare( strict_types = 1 );
namespace Models;
class CitiesModel extends Model
{
	public function get_list( string $country ) : array
	{
		try
		{
			$DB = Model::DB();
			$statment = $DB -> prepare(
						"SELECT cities.name
						FROM cities JOIN countries ON cities.country_id = countries.id
						WHERE countries.name = ?;" );
			$statment -> execute( [$country] );
			$result = $statment -> fetchAll( \PDO::FETCH_COLUMN, 0 );
		}
		catch( \PDOException $error )
		{
			die( 'Помилка БД: ' . $error -> getMessage() );
		}
		$DB = null;
		$statment = null;
		return $result;
	}
	public function get_info( string $city ) : array
	{
		try
		{
			$DB = Model::DB();
			$statment = $DB -> prepare(
				"SELECT continents.name as continent,
						countries.name as county,
						cities.name as city,
						cities.time_zone as time_zone
				 FROM cities
				 JOIN continents ON continents.id = cities.continent_id
				 JOIN countries ON cities.country_id = countries.id
				 WHERE cities.name = ?;"
			);
			$statment -> execute( [ $city ] );
			$result = $statment -> fetch( \PDO::FETCH_ASSOC );
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