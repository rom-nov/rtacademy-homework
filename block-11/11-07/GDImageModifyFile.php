<?php
declare( strict_types = 1);
class GDImageModifyFile
{
	protected GdImage|bool $img = false;
	protected int $width;
	protected int $height;

	public function __construct( $src_file )
	{
		switch( $_FILES[ $src_file ][ 'type' ] )
		{
			case 'image/jpeg':
				$this -> img = imagecreatefromjpeg( $_FILES[ $src_file ][ 'tmp_name' ] );
				break;
			case 'image/png':
				$this -> img = imagecreatefrompng( $_FILES[ $src_file ][ 'tmp_name' ] );
				break;
			case 'image/gif':
				$this -> img = imagecreatefromgif( $_FILES[ $src_file ][ 'tmp_name' ] );
				break;
		}
		$this -> set_width();
		$this -> set_height();
	}

	public function get_img() : GdImage|bool
	{
		return $this -> img;
	}

	public function set_width() : void
	{
		if( !$this -> img )
		{
			throw new Exception( 'Помилка. Не вдалося створити зображення.' );
		}
		$this -> width = imagesx( $this -> img );
	}

	public function get_width() : int
	{
		return $this -> width;
	}

	public function set_height() : void
	{
		if( !$this -> img )
		{
			throw new Exception( 'Помилка. Не вдалося створити зображення.' );
		}
		$this -> height = imagesy( $this -> img );
	}

	public function get_height() : int
	{
		return $this -> height;
	}

	public function check_size_img( int $size ) : object
	{
		if( $this -> get_width() < $size || $this -> get_height() < $size )
		{
			throw new Exception( 'Ширина та висота зображення має бути більше' . $size . 'px.' );
		}
		return $this;
	}

	public function crop_instagram() : object
	{
		$width_output = $this -> width;
		$height_output = round( $width_output * 1.25 );
		$x_output = 0;
		$y_output = round( ( $this -> height - $height_output ) * 0.5 );
		$this -> img = imagecrop( $this -> img, [ 'x' => $x_output, 'y' => $y_output, 'width' => $width_output, 'height' => $height_output ] );
		if( !($this -> img) )
		{
			throw new Exception( 'Не вдалося обробити зображення.' );
		}
		return $this;
	}

	public function scale_img( int $width, int $height ) : object
	{
		$this -> img = imagescale( $this -> img, $width, $height );
		if( !($this -> img) )
		{
			throw new Exception( 'Не вдалося обробити зображення.' );
		}
		return $this;
	}
}