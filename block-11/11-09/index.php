<?php
declare( strict_types = 1 );
use Models\CountriesModel;
spl_autoload_register( fn( $class ) => require './' . str_replace('\\', '/', $class ) . '.php' );
$countries = ( new CountriesModel() ) -> get_list();
require_once ( './View/LayoutView.php' );

