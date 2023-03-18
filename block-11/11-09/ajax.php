<?php
declare( strict_types = 1 );
use Models\CitiesModel;
spl_autoload_register( fn( $class ) => require './' . str_replace('\\', '/', $class ) . '.php' );
if( !$_GET )
{
	exit();
}
if( str_contains( $_SERVER['REQUEST_URI'], 'ajax.php?country' ) )
{
	$cities = ( new CitiesModel() ) -> get_list( $_GET['country'] );
	echo json_encode( $cities );
}
if( str_contains( $_SERVER['REQUEST_URI'], 'ajax.php?city' ) )
{
	$info = ( new CitiesModel() ) -> get_info( $_GET['city'] );
	echo json_encode( $info );
}
exit();