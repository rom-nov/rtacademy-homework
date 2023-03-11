<?php
declare( strict_types = 1 );
namespace lib;
class Main
{
	protected static string $error_message = '';
	protected static string $image_path = '';

	public const MAX_SIZE = 10485760; //10Mb
	public const TYPE_MIMY = [ 'image/jpeg', 'image/png', 'image/gif' ];
	protected const MIN_SIZE_IMG = 500;
	protected const RESULT_IMG_WIDTH = 240;
	protected const RESULT_IMG_HEIGHT = 300;
	public const RESULT_IMG_TYPE = '.jpg';
	public const SAVE_PATH = './';
	public const SAVE_DIR = 'data/';

	public static function start( string $img_file ) : void
	{
		try
		{
			if( empty( $_FILES ) && empty( $_POST ) )
			{
				return;
			}

			$file = ( new ControlLoadFile( $img_file ) )
				-> error_load()
				-> check_mimetypes( self::TYPE_MIMY )
				-> is_oversize( self::MAX_SIZE );

			$img = ( new GDImageModify( $file -> get_name() ) )
				-> check_size_img( self::MIN_SIZE_IMG )
				-> crop_instagram()
				-> scale_img( self::RESULT_IMG_WIDTH, self::RESULT_IMG_HEIGHT )
				-> save_file( strval( time() ), self::RESULT_IMG_TYPE, self::SAVE_PATH, self::SAVE_DIR )
				-> destroy();

			self::$image_path = $img -> full_path();
		}
		catch( \Exception $error )
		{
			self::$error_message = $error -> getMessage();
		}
	}

	public static function path_img() : string
	{
		return self::$image_path;
	}

	public static function get_error() : string
	{
		return self::$error_message;
	}
}