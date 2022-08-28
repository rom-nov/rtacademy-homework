<?php
    declare( strict_types = 1 );

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
			$out_array[] = $data;
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

    function write_json( $resource, $array ) : void
	{
		fwrite( $resource, json_encode( $array ) );
	}

    //===== main script

    $path = './cities.csv';
	is_exists( $path );
	$resource = is_open( $path );
	$array_csv = csv_in_array( $resource );
    fclose( $resource );
    $json_file = create_file( './', 'cities.json' );
	write_json( $json_file, $array_csv );
    fclose( $json_file );
?>