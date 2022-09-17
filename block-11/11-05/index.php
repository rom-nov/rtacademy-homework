<?php
declare( strict_types = 1 );

//===== constants

const START =
	'<!doctype html>' .
	'<html lang="uk">' .
		'<head>' .
			'<meta charset="UTF-8">' .
			'<link rel="stylesheet" href="style.css">' .
			'<title>Cities</title>' .
		'</head>' .
		'<body>' .
			'<table class="table">' .
				'<tr>' .
					'<th>Місто</th>' .
					'<th>Країна</th>' .
					'<th>Населення</th>' .
					'<th>Координати</th>' .
				'</tr>';

const END =
			'</table>' .
		'</body>' .
	'</html>';

//===== functions

function is_exists( string $path ) : void
{
	if( !file_exists( $path ) )
	{
		throw new Exception( 'файл не знайдено' );
	}
}

function is_open( string $path )
{
	is_exists( $path );
	if( !$resource = fopen( $path, 'r' ) )
	{
		throw new Exception( 'не вдалося відкрити файл' );
	}
	return $resource;
}

function convert_type( array $array_str ) : array
{
	return [
		'city' => trim( $array_str[ 0 ] ),
		'latitude' => ( float )$array_str[ 1 ],
		'longitude' => ( float )$array_str[ 2 ],
		'country' => trim( $array_str[ 3 ] ),
		'population' => ( int )$array_str[ 4 ]
	];
}

function validation_data( array $arr ) : bool
{
	$regexp = '~^(([a-z])+([\s\'\-])?)+$~i';
	foreach( $arr as $key => $value )
	{
		switch( true )
		{
			case( ( $key === 'latitude' ||
					$key === 'longitude' ||
					$key === 'population' ) &&
					is_nan( $value ) ):
			case( is_string( $value ) &&
				 !preg_match( $regexp, $value ) ):
				return false;
		}
	}
	return true;
}

function csv_in_array( $csv ) : array
{
	$out_array = array();
	while( $data = fgetcsv( $csv ) )
	{
		if( count( $data ) != 5 )
		{
			continue;
		}
		$data = convert_type( $data );
		if( !validation_data( $data ) ||
			$data[ 'population' ] <= 1000000 )
		{
			continue;
		}
		$out_array[] = $data;
	}
	fclose( $csv );
	return $out_array;
}

function csv_work( string $path ) : array
{
	return csv_in_array( is_open( $path ) );
}

function create_file( string $path, string $name )
{
	chmod( $path, 0777 );
	if( !$file = fopen( $path.$name, 'w' ) )
	{
		throw new Exception( 'не вдалося створити файл ' . $name . '.html' );
	}
	chmod( $path, 0775 );
	return $file;
}

function write_data( $resource, array $array ) : void
{
	fwrite( $resource, START );
	foreach( $array as $inner_arr )
	{
		fwrite( $resource,
		'<tr>'.
			'<td>' . $inner_arr[ 'city' ] . '</td>' .
			'<td>' . $inner_arr[ 'country' ] . '</td>' .
			'<td>' . $inner_arr[ 'population' ] . '</td>' .
			'<td>' . $inner_arr[ 'latitude' ] . ', ' . $inner_arr[ 'longitude' ] . '</td>' .
		'</tr>');
	}
	fwrite( $resource, END );
	fclose( $resource );
}

//===== main script

try
{
	write_data( create_file( './', 'cities.html' ), csv_work( './cities.csv' ) );
	header( 'Location: http://127.0.0.1/block-11/11-05/cities.html' );
}
catch( Exception $error )
{
	echo '<h2 style="color: #ff0000"> ПОМИЛКА: ' . $error -> getMessage() . '</h2>';
}
?>