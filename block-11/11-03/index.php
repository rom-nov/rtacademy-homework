<?php
declare( strict_types = 1 );

class supportFunction
{
	static function checkOnHardString( string $str ) : bool
	{
		return mb_strpos( $str, '-' ) > 0  || mb_strpos( $str, ' ' ) > 0;
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

function checkOnExeption ( int $index, string $item, array $arr ) : string
{
	$result = $item;

	if ( $index > 0 && $index < count( $arr ) - 1 &&
	   ( $item === 'Сюр' || $item === 'Ле'   ||
		 $item === 'На'  || $item === 'І'    ||
		 $item === 'Та'  || $item === 'Апон' ||
		 $item === 'Де'  || $item === 'Лез'   ) )
	{
		$result = mb_strtolower( $item );
	}

	if( $item === 'Л\'аї' || $item === 'Д\'олонн' || $item === 'Д\'апт' )
	{
		if( $index > 0 )
		{
			$result = mb_strtolower( mb_substr( $item, 0, 2 ) ) . modifyString( mb_substr( $item, 2 ) );
		}
		else
		{
			$result = mb_substr( $item, 0, 2 ) . modifyString( mb_substr( $item, 2 ) );
		}
	}

	if( $index > 0 && $item === 'сен' )
	{
		$result = modifyString( $item );
	}

	return $result;
};

function modifyHardString( string $str, string $separator ) : string
{
	$arrayWords = supportFunction::splitString( $str, $separator );
	$modifyArrayWords = modifyArrayStrings( $arrayWords );
	foreach( $modifyArrayWords as $index => $value )
	{
		$modifyArrayWords[ $index ] = checkOnExeption( $index, $value, $modifyArrayWords );
	}
	return supportFunction::joinString( $modifyArrayWords, $separator );
}

function modifyNormalString( string $str ) : string
{
	$firstLetter = mb_strtoupper( mb_substr( $str, 0, 1 ) );
	$subString = mb_strtolower( mb_substr( $str, 1 ) );
	return $firstLetter . $subString;
}

function modifyString( string $str ) : string
{
	if( gettype( $str ) !== 'string' || !mb_strlen( $str, 'UTF-8' ) )
	{
		supportFunction::Error();
	}
	if( supportFunction::checkOnHardString( $str ) )
	{
		return modifyHardString( $str, supportFunction::getSeparator( $str ) );
	}
	return modifyNormalString( $str );
}

function modifyArrayStrings( array &$arr, int $inc = 0 ) : array
{
	if( $inc < count( $arr ) )
	{
		if ( gettype( $arr[ $inc ] ) === 'array' && count( $arr[ $inc ] ) )
		{
			$arr[ $inc ] = modifyArrayStrings( $arr[ $inc ] );
		}
		else
		{
			$arr[ $inc ] =  modifyString( $arr[ $inc ] );
		}
		modifyArrayStrings( $arr, ++$inc );
	}
	return $arr;
}

function capitalize( mixed $param ) : string|array
{
	switch( true )
	{
		case( gettype( $param ) === 'string' ):
			return modifyString( $param );
		case( gettype( $param ) === 'array' && count( $param ) ):
			return modifyArrayStrings( $param );
		default:
			supportFunction::Error();
	}
}

//echo capitalize( 'киїВ' );
//echo "<br>";
//echo capitalize( 'ньЮ-йоРк' );
//echo "<br>";
//echo capitalize( 'лЬвів' );
//echo "<br>";
//echo capitalize( 'ла-сЕЙн-СЮР-меР' );
//echo "<br>";
//var_dump( capitalize( ['киїВ', 'ньЮ-йоРк', 'рІо-дЕ-жаНеЙро', 'сЕН-сАтЮрНЕн-ЛЕЗ-аПт', 'сі-фур-ЛЕ-плаЖ', 'вільНЕв-сЕн-жОРж'] ) );
//echo "<br>";
//var_dump( capitalize( ["лА-рОш-СЮР-іОн", ['киїВ', 'ньЮ-йоРк', 'лЬвів'], "ЛЕ-сАБль-Д'олОНн", "брАЙтон І гоУв", "кіНГСТон-апОН-гаЛЛ", "л'аї-ЛЕ-рОз", "лагАРД-д'АПТ", "нова грАДишКА"] ) );
//echo capitalize( 123456 );
//echo "<br>";
//echo capitalize( [] );
//echo "<br>";
//echo capitalize( '' );