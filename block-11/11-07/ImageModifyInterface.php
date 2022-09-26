<?php
declare( strict_types = 1);
interface ImageModifyInterface
{
	public function crop_instagram() : self;
	public function scale_img( int $width, int $height ) : self;
	public function destroy() : self;
	public function get_width() : int;
	public function get_height() : int;
	public function get_img() : GdImage | bool;
	public function full_path() : string;
	public function check_size_img( int $size ) : self;
	public function save_file( string $name, string $type, string $path, string $dir ) : self;
}