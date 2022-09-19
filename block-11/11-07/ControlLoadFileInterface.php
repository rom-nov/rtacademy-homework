<?php
interface ControlLoadFileInterface
{
	public function get_name();
	public function get_mime();
	public function error_load();
	public function check_mimetypes();
	public function is_oversize();
}