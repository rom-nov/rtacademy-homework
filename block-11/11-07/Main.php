<?php
declare( strict_types = 1 );
class Main
{
	protected static ControlLoadFileInterface $file;
	protected static ?ImageModifyInterface $img = null;
	protected static string $error_message = '';
	protected static string $image_path = '';

	public const MAX_SIZE = 10485760;
	public const TYPE_MIMY = [ 'image/jpeg', 'image/png', 'image/gif' ];
	protected const IMG_SIZE = 500;
	protected const IMG_WIDTH = 240;
	protected const IMG_HEIGHT = 300;
	public const TYPE = '.jpg';
	public const PATH = './';
	public const DIR = 'data/';

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
				-> check_mimetypes( self::TYPE_MIMY )
				-> is_oversize( self::MAX_SIZE );

			self::$img = ( new GDImageModify( self::$file -> get_name() ) )
				-> check_size_img( self::IMG_SIZE )
				-> crop_instagram()
				-> scale_img( self::IMG_WIDTH, self::IMG_HEIGHT )
				-> save_file( strval( time() ), self::TYPE, self::PATH, self::DIR )
				-> destroy();

			self::$image_path = self::$img -> full_path();
		}
		catch( Exception $error )
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