<?php
	declare( strict_types = 1 );
    $file = './cities.csv';

    if( !file_exists( $file ) )
    {
        header( 'HTTP/1.1 404 Not Found' );
        exit;
    }

    echo 'File exists';
?>