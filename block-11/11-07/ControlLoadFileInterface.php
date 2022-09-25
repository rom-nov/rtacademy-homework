<?php
declare( strict_types = 1);
interface ControlLoadFileInterface
{
	public function get_name() : string;
	public function get_mime() : string;
	public function error_load() : self;
	public function check_mimetypes() : self;
	public function is_oversize() : self;
}