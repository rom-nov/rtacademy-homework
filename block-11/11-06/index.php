<?php
declare( strict_types = 1 );

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
	while( ( $data = fgetcsv( $csv ) ) !== false )
	{
		if( count( $data ) != 5 )
		{
			continue;
		}
		$data = convert_type( $data );
		if( !validation_data( $data ) )
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

function write_json( array $array, string $path = './', string $name = 'cities.json' ) : void
{
	chmod( $path, 0777 );
	if( !file_put_contents( $path . $name, json_encode( $array ) ) )
	{
		throw new Exception( 'не вдалося записати дані у файл' );
	}
	chmod( $path, 0775 );
	echo( "<a href='$name' download='$name'>Завантажити файл</a>" );
}

//===== main script

try
{
	write_json( csv_work( './cities.csv' ) );
}
catch( Exception $error )
{
	echo '<h2 style="color: #ff0000"> ПОМИЛКА: ' . $error -> getMessage() . '</h2>';
}