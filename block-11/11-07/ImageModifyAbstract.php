<?php
declare( strict_types = 1);
abstract class ImageModifyAbstract implements ImageModifyInterface
{
	protected GdImage | bool $img = false;
	protected int $width;
	protected int $height;
	protected string $full_path;

	abstract public function crop_instagram() : ImageModifyInterface;
	abstract public function scale_img( int $width, int $height ) : ImageModifyInterface;
	abstract public function destroy() : ImageModifyInterface;

	public function get_width() : int
	{
		return $this -> width;
	}

	public function get_height() : int
	{
		return $this -> height;
	}

	public function get_img() : GdImage | bool
	{
		return $this -> img;
	}

	public function full_path() : string
	{
		return $this -> full_path;
	}

	public function check_size_img( int $size ) : ImageModifyInterface
	{
		if( $this -> get_width() < $size || $this -> get_height() < $size )
		{
			throw new Exception( 'Ширина та висота зображення має бути більше ' . $size . 'px.' );
		}

		return $this;
	}

	public function save_file( string $name, string $type = self::TYPE, string $path = self::PATH, string $dir = self::DIR ) : ImageModifyInterface
	{
		$this -> full_path = $path . $dir . $name . $type;

		if( !file_exists( $path . $dir ) )
		{
			chmod( $path, 0777 );
			mkdir( $path . $dir );
			chmod( $path, 0775 );
		}

		if( !imagejpeg( $this -> img, $this -> full_path ) )
		{
			throw new Exception( 'Помилка. Не вдалося зберегти файл.' );
		}

		return $this;
	}
}