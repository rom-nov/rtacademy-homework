<?php
declare( strict_types = 1 );
class Main
{
	protected static ?ControlLoadFileInterface $file = null;
	protected static ?ImageModifyInterface $img = null;
	protected static string $error_message = '';

	protected const IMG_SIZE = 500;
	protected const IMG_WIDTH = 240;
	protected const IMG_HEIGHT = 300;

	public static function start( string $img_file ) : void
	{
		try
		{
			if( empty( $_FILES ) && empty( $_POST ) )
			{
				return;
			}

			self::$file = ( new ControlLoadFile( $img_file ) )
				-> error_load()
				-> check_mimetypes()
				-> is_oversize();

			self::$img = ( new GDImageModify( self::$file -> get_name() ) )
				-> check_size_img( self::IMG_SIZE )
				-> crop_instagram()
				-> scale_img( self::IMG_WIDTH, self::IMG_HEIGHT )
				-> save_file( strval( time() ) )
				-> destroy();
		}
		catch( Exception $error )
		{
			self::$error_message = $error -> getMessage();
		}
	}

	public static function path_img() : string
	{
		if( self::$img )
		{
			return self::$img -> full_path();
		}
		return '';
	}

	public static function get_error() : string
	{
		return self::$error_message;
	}
}