<?php
declare( strict_types = 1 );
namespace lib;
class ImagickModyfy extends ImageModifyAbstract
{
	public function __construct( $src_file )
	{
		$this -> img = new \Imagick( $_FILES[ $src_file ][ 'tmp_name' ] );

		if( !$this -> img )
		{
			throw new \Exception( 'Помилка. Не вдалося створити зображення.' );
		}

		$this -> width = $this -> img -> getImageWidth();
		$this -> height = $this -> img -> getImageHeight();
	}

	public function crop_instagram() : ImageModifyInterface
	{
		$width_output = $this -> width;
		$height_output = intval( $width_output * 1.25 );
		$x_output = 0;
		$y_output = intval( ( $this -> height - $height_output ) * 0.5 );

		if( !$this -> img -> cropImage( $width_output, $height_output, $x_output, $y_output ) )
		{
			throw new \Exception( 'Не вдалося обробити зображення.' );
		}

		return $this;
	}

	public function scale_img( int $width, int $height ) : ImageModifyInterface
	{
		if( !$this -> img -> resizeImage( $width, $height, \Imagick::FILTER_BOX,1 ) )
		{
			throw new \Exception( 'Не вдалося обробити зображення.' );
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

		if( !$this -> img -> setImageFormat( 'jpeg' ) )
		{
			throw new \Exception( 'Помилка. Не вдалось встановити формат зображення.' );
		}

		if( !$this -> img -> writeImage( $this -> full_path ) )
		{
			throw new \Exception( 'Помилка. Не вдалось зберегти файл.' );
		}

		return $this;
	}

	public function destroy() : ImageModifyInterface
	{
		$this ?-> img -> clear();
		return $this;
	}
}