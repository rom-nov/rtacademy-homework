<?php
abstract class ImageModifyAbstract
{
	protected GDImage | bool $img = false;
	protected int $width;
	protected int $height;
	protected string $full_path;

	abstract public function crop_instagram() : self;
	abstract public function scale_img( int $width, int $height ) : self;
	abstract public function destroy() : self;

	public function get_width() : int
	{
		return $this -> width;
	}

	public function get_height() : int
	{
		return $this -> height;
	}

	public function get_img() : GDImage | bool
	{
		return $this -> img;
	}

	public function full_path() : string
	{
		return $this -> full_path;
	}

	public function check_size_img( int $size ) : self
	{
		if( $this -> get_width() < $size || $this -> get_height() < $size )
		{
			throw new Exception( 'Ширина та висота зображення має бути більше' . $size . 'px.' );
		}
		return $this;
	}

	public function save_file( int|string $name, string $type = '.jpg', string $path = './', string $dir = 'data/' ) : self
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