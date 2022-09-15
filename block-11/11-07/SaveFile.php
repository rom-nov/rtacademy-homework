<?php
declare( strict_types = 1 );
class SaveFile
{
	static protected string $full_path;
	static public function save( GdImage $img, string $path, string $dir, int|string $name, string $type ) : void
	{
		SaveFile::$full_path = $path . $dir . $name . $type;

		if( !file_exists( $path . $dir ) )
		{
			chmod( $path, 0777 );
			mkdir( $path . $dir );
			chmod( $path, 0775 );
		}

		if( !imagejpeg( $img, SaveFile::$full_path ) )
		{
			throw new Exception( 'Помилка. Не вдалося зберегти файл.' );
		}
	}
	static public function full_path() : string
	{
		return SaveFile::$full_path;
	}
}
