<?php
declare( strict_types = 1);
namespace lib;
class GDImageModify extends ImageModifyAbstract
{
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

		if( !$this -> img )
		{
			throw new \Exception( 'Помилка. Не вдалось створити зображення.' );
		}

		$this -> width = imagesx( $this -> img );
		$this -> height = imagesy( $this -> img );
	}

	public function crop_instagram() : ImageModifyInterface
	{
		$width_output = $this -> width;
		$height_output = round( $width_output * 1.25 );
		$x_output = 0;
		$y_output = round( ( $this -> height - $height_output ) * 0.5 );
		$this -> img = imagecrop( $this -> img, [ 'x' => $x_output, 'y' => $y_output, 'width' => $width_output, 'height' => $height_output ] );

		if( !$this -> img )
		{
			throw new \Exception( 'Помилка. Не вдалось обробити зображення.' );
		}

		return $this;
	}

	public function scale_img( int $width, int $height ) : ImageModifyInterface
	{
		if( !( $this -> img = imagescale( $this -> img, $width, $height ) ) )
		{
			throw new \Exception( 'Помилка. Не вдалось обробити зображення.' );
		}

		return $this;
	}

	public function save_file( string $name, string $type, string $path, string $dir ) : ImageModifyInterface
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
			throw new \Exception( 'Помилка. Не вдалось зберегти файл.' );
		}

		return $this;
	}

	public function destroy() : ImageModifyInterface
	{
		if( $this -> img )
		{
			imagedestroy( $this -> img );
		}

		return $this;
	}
}