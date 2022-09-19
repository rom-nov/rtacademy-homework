<?php
declare( strict_types = 1 );
class ImagickModify extends ImageModifyAbstract
{
	public function __construct( $src_file )
	{
		$this -> img = ( new Imagick( $_FILES[ $src_file ][ 'tmp_name' ] ) ) -> setImageFormat( $_FILES[ $src_file ][ 'type' ] );
	}
	public function crop_instagram() : self
	{
		return $this;
	}
	public function scale_img( int $width, int $height ) : self
	{
		return $this;
	}
	public function destroy() : self
	{
		return $this;
	}
}