<?php
declare( strict_types = 1 );
spl_autoload_register( fn( $class ) => require './' . str_replace('\\', '/', $class ) . '.php' );
$country = ( new Models\CountryModel() ) -> get_list();
//header("Location: http://127.0.0.1/block-11/11-09/View/index.php");

