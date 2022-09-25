<?php
declare( strict_types = 1);
interface ControlLoadFileInterface
{
	public const MAX_SIZE = 10485760;
	public const TYPE_MIMY = [ 'image/jpeg', 'image/png', 'image/gif' ];

	public function get_name() : string;
	public function get_mime() : string;
	public function error_load() : self;
	public function check_mimetypes() : self;
	public function is_oversize() : self;
}