<?php
    declare( strict_types = 1 );
	header( 'Location: http://127.0.0.1/block-11/11-05/cities.html' );

    //===== constants

    define( 'START',
            '<!doctype html>
            <html lang="uk">
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="style.css">
                <title>Cities</title>
            </head>
            <body>
            <table class="table">
            <tr>
                <th>Місто</th>
                <th>Країна</th>
                <th>Населення</th>
                <th>Координати</th>
            </tr>');
    define( 'END', '
            </table>
            </body>
            </html>' );

    //===== functions

    function is_exists( string $path ) : void
	{
		if( !file_exists( $path ) )
		{
			echo '<h2 class="error"> Файл не знайдно </h2>';
			exit;
		}
    }

    function is_open( string $path )
	{
		if( ( $resource = fopen( $path, 'r' ) ) === false )
		{
			echo '<h2 class="error"> Не вдалося відкрити файл </h2>';
			exit;
		}
        return $resource;
    }

    function convert_type( array $array_str ) : array
	{
		$array[ 'city' ] = trim( $array_str[ 0 ] );
		$array[ 'latitude' ] = ( float )$array_str[ 1 ];
		$array[ 'longitude' ] = ( float )$array_str[ 2 ];
		$array[ 'country' ] = trim( $array_str[ 3 ] );
		$array[ 'population' ] = ( int )$array_str[ 4 ];
        return $array;
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
                    return false;
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
			if( $data[ 'population' ] > 1000000 )
			{
				$out_array[] = $data;
			}
		}
        return $out_array;
    }

    function create_file( string $path, string $name )
	{
		chmod( $path, 0777 );
        if( ( $file = fopen( $path.$name, 'w' ) ) === false )
		{
			echo '<h2 class="error"> Не вдалося створити файл </h2>';
			exit;
        }
		chmod( $path, 0775 );
        return $file;
    }

    function write_table( $resource, $array ) : void
	{
		fwrite( $resource, START );
        foreach( $array as $inner_arr )
		{
            fwrite( $resource, "
            <tr>
                <td>${inner_arr[ 'city' ]}</td>
                <td>${inner_arr[ 'country' ]}</td>
                <td>${inner_arr[ 'population' ]}</td>
                <td>${inner_arr[ 'latitude' ]}, ${inner_arr[ 'longitude' ]}</td>
            </tr>");
        }
		fwrite( $resource, END );
	}

    //===== main script

    $path = './cities.csv';
	is_exists( $path );
	$resource = is_open( $path );
	$array_csv = csv_in_array( $resource );
    fclose( $resource );
    $html_file = create_file( './', 'cities.html' );
	write_table( $html_file, $array_csv );
    fclose( $html_file );
?>