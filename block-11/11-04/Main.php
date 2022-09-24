<?php
declare( strict_types = 1);
class Main
{
	protected ControlMessage $message;
	protected static string $result_message = '';
	protected static string $error_message = '';

	public static function start() : void
	{
		try
		{
			if( $_POST )
			{
				$message = ( new ControlMessage() ) -> check();
			}
			if( !empty( $message ) )
			{
				self::$result_message = "<div class='user-name'>{$message -> get_fullname()} &lt;{$message -> get_email()}&gt;</div>".
										"<div class='user-text'>{$message -> get_message()}</div>";
			}
		}
		catch( Exception $error )
		{
			self::$error_message = "<div class='user-error'>{$error -> getMessage()}</div>";
		}
	}

	public static function result_message() : string
	{
		return self::$result_message;
	}

	public static function error_message() : string
	{
		return self::$error_message;
	}

}