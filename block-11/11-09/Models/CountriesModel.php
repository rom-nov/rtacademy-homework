<?php
declare( strict_types = 1 );
namespace Models;
class CountriesModel extends Model
{
	public function get_list() : array
	{
		try
		{
			$DB = Model::DB();
			$statment = $DB -> query(
						'SELECT name
						 FROM countries
						 ORDER BY name ASC;',
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