<?php
declare( strict_types = 1);
namespace lib;
interface ControlLoadFileInterface
{
	public function get_name() : string;
	public function get_mime() : string;
	public function error_load() : self;
	public function check_mimetypes( array $mimy_type ) : self;
	public function is_oversize( int $max_size ) : self;
}