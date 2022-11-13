<?php
declare( strict_types = 1 );

$checkOnHardString = fn( string $str ) : bool => ( mb_strpos( $str, '-' ) > 0  || mb_strpos( $str, ' ' ) > 0 );
$getSeparator = fn( string $str ) : string => mb_strpos( $str, '-' ) > 0 ? '-' : ' ';
$splitString = fn( string $str, string $separator ) : array => mb_split( $separator , $str );
$joinString = fn( array $arr, string $separator ) : string => join( $separator, $arr );

function Error() : void
{
	exit( 'Очікую рядок або масив рядків!' );
}

function checkOnExeption ( int $index, string $item, array $arr, callable $checkOnHardStr, callable $getSeparator, callable $splitStr, callable $joinStr ) : string
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
			$result = mb_strtolower( mb_substr( $item, 0, 2 ) ) . modifyString( mb_substr( $item, 2 ), $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		}
		else
		{
			$result = mb_substr( $item, 0, 2 ) . modifyString( mb_substr( $item, 2 ), $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		}
	}

	if( $index > 0 && $item === 'сен' )
	{
		$result = modifyString( $item, $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
	}

	return $result;
};

function modifyHardString( string $str, string $separator, callable $checkOnHardStr, callable $getSeparator, callable $splitStr, callable $joinStr ) : string
{
	//global $splitString, $joinString;
	$arrayWords = $splitStr( $str, $separator );
	$modifyArrayWords = modifyArrayStrings( $arrayWords, $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
	foreach( $modifyArrayWords as $index => $value )
	{
		$modifyArrayWords[ $index ] = checkOnExeption( $index, $value, $modifyArrayWords, $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
	}
	return $joinStr( $modifyArrayWords, $separator );
}

function modifyNormalString( string $str ) : string
{
	$firstLetter = mb_strtoupper( mb_substr( $str, 0, 1 ) );
	$subString = mb_strtolower( mb_substr( $str, 1 ) );
	return $firstLetter . $subString;
}

function modifyString( string $str, callable $checkOnHardStr, callable $getSeparator, callable $splitStr, callable $joinStr ) : string
{
	//global $checkOnHardString, $getSeparator;
	if( gettype( $str ) !== 'string' || !mb_strlen( $str, 'UTF-8' ) )
	{
		Error();
	}
	if( $checkOnHardStr( $str ) )
	{
		return modifyHardString( $str, $getSeparator( $str ), $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
	}
	return modifyNormalString( $str );
}

function modifyArrayStrings( array &$arr, callable $checkOnHardStr, callable $getSeparator, callable $splitStr, callable $joinStr, int $inc = 0 ) : array
{
	if( $inc < count( $arr ) )
	{
		if ( gettype( $arr[ $inc ] ) === 'array' && count( $arr[ $inc ] ) )
		{
			$arr[ $inc ] = modifyArrayStrings( $arr[ $inc ], $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		}
		else
		{
			$arr[ $inc ] =  modifyString( $arr[ $inc ], $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		}
		modifyArrayStrings( $arr, $checkOnHardStr, $getSeparator, $splitStr, $joinStr, ++$inc );
	}
	return $arr;
}

function capitalize( mixed $param,
					 callable $checkOnHardStr,
					 callable $getSeparator,
					 callable $splitStr,
					 callable $joinStr) : string|array
{
	switch( true )
	{
		case( gettype( $param ) === 'string' ):
			return modifyString( $param, $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		case( gettype( $param ) === 'array' && count( $param ) ):
			return modifyArrayStrings( $param, $checkOnHardStr, $getSeparator, $splitStr, $joinStr );
		default:
			Error();
	}
}

//echo capitalize( 'киїВ',
//				 $checkOnHardString,
//				 $getSeparator,
//				 $splitString,
//				 $joinString );
//echo "<br>";
//echo capitalize( 'ньЮ-йоРк' );
//echo "<br>";
//echo capitalize( 'лЬвів' );
//echo "<br>";
//echo capitalize( 'ла-сЕЙн-СЮР-меР' );
//echo "<br>";
var_dump( capitalize( ['киїВ', 'ньЮ-йоРк', 'рІо-дЕ-жаНеЙро', 'сЕН-сАтЮрНЕн-ЛЕЗ-аПт', 'сі-фур-ЛЕ-плаЖ', 'вільНЕв-сЕн-жОРж'],
					  $checkOnHardString,
					  $getSeparator,
					  $splitString,
					  $joinString) );
//echo "<br>";
//var_dump( capitalize( ["лА-рОш-СЮР-іОн", ['киїВ', 'ньЮ-йоРк', 'лЬвів'], "ЛЕ-сАБль-Д'олОНн", "брАЙтон І гоУв", "кіНГСТон-апОН-гаЛЛ", "л'аї-ЛЕ-рОз", "лагАРД-д'АПТ", "нова грАДишКА"] ) );
//echo capitalize( 123456 );
//echo "<br>";
//echo capitalize( [] );
//echo "<br>";
//echo capitalize( '' );