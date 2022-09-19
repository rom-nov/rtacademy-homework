<?php
declare( strict_types = 1);
interface ControlLoadFileInterface
{
	public function get_name() : string;
	public function get_mime() : string;
	public function error_load() : ControlLoadFileInterface;
	public function check_mimetypes() : ControlLoadFileInterface;
	public function is_oversize() : ControlLoadFileInterface;
}