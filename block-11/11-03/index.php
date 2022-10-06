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
	global $splitString, $joinString;
	$arrayWords = $splitString( $str, $separator );
	$modifyArrayWords = modifyArrayStrings( $arrayWords );
	foreach( $modifyArrayWords as $index => $value )
	{
		$modifyArrayWords[ $index ] = checkOnExeption( $index, $value, $modifyArrayWords );
	}
	return $joinString( $modifyArrayWords, $separator );
}

function modifyNormalString( string $str ) : string
{
	$firstLetter = mb_strtoupper( mb_substr( $str, 0, 1 ) );
	$subString = mb_strtolower( mb_substr( $str, 1 ) );
	return $firstLetter . $subString;
}

function modifyString( string $str ) : string
{
	global $checkOnHardString, $getSeparator;
	if( gettype( $str ) !== 'string' || !mb_strlen( $str, 'UTF-8' ) )
	{
		Error();
	}
	if( $checkOnHardString( $str ) )
	{
		return modifyHardString( $str, $getSeparator( $str ) );
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
			Error();
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