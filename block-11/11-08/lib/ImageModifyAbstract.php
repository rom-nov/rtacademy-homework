<?php
declare( strict_types = 1);
namespace lib;
abstract class ImageModifyAbstract implements ImageModifyInterface
{
	protected \GdImage | \Imagick | bool $img = false;
	protected int $width;
	protected int $height;
	protected string $full_path;

	abstract public function crop_instagram() : ImageModifyInterface;
	abstract public function scale_img( int $width, int $height ) : ImageModifyInterface;
	abstract public function save_file( string $name, string $type, string $path, string $dir ) : ImageModifyInterface;
	abstract public function destroy() : ImageModifyInterface;

	public function get_width() : int
	{
		return $this -> width;
	}

	public function get_height() : int
	{
		return $this -> height;
	}

	public function get_img() : \GdImage | \Imagick | bool
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
			throw new \Exception( 'Ширина та висота зображення має бути більше ' . $size . 'px.' );
		}

		return $this;
	}
}