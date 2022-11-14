<?php
declare( strict_types = 1 );
abstract class supportFunctions
{
	static function checkOnHardString( string $str ) : bool
	{
		return mb_strpos( $str, '-' ) > 0 || mb_strpos( $str, ' ' ) > 0;
	}

	static function getSeparator( string $str ) : string
	{
		return mb_strpos( $str, '-' ) > 0 ? '-' : ' ';
	}

	static function splitString( string $str, string $separator ) : array
	{
		return mb_split( $separator , $str );
	}

	static function joinString( array $arr, string $separator ) : string
	{
		return join( $separator, $arr );
	}

	static function Error() : void
	{
		exit( 'Очікую рядок або масив рядків!' );
	}
}