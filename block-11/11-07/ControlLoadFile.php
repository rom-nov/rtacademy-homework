<?php
declare( strict_types = 1);
class ControlLoadFile
{
	protected string $name;
	protected string $mime_type;
	protected const MAX_SIZE = 10485760;

	public function __construct( string $name )
	{
		$this -> name = $name;
		$this -> is_empty() -> set_mime();
	}

	private function is_empty() : object
	{
		if( empty( $_FILES [ $this -> name ][ 'name' ] ) )
		{
			throw new Exception( 'Помилка. Необхідно завантажити файл.' );
		}
		return $this;
	}

	private function set_mime() : object
	{
		if( !( $this -> mime_type = mime_content_type( $_FILES[ $this -> name ][ 'tmp_name' ] ) ) )
		{
			throw new Exception( 'Помилка визначення типу завантажуваного файлу' );
		}
		return $this;
	}

	public function get_name() : string
	{
		return $this -> name;
	}

	public function get_mime() : string
	{
		return $this -> mime_type;
	}

	public function error_load() : object
	{
		if( $_FILES[ $this -> name ][ 'error' ] !== UPLOAD_ERR_OK )
		{
			throw new Exception( 'Сталася помилка під час завантаження файлу.' );
		}
		return $this;
	}

	public function check_mimetypes() : object
	{
		if( !in_array( $this -> mime_type , [ 'image/jpeg', 'image/png', 'image/gif' ] ) )
		{
			throw new Exception( 'Помилка. Файл повинен мати формат JPEG / PNG / GIF.' );
		}
		return $this;
	}

	public function is_oversize() : object
	{
		if( $_FILES[ $this -> name ][ 'size' ] > ControlLoadFile::MAX_SIZE )
		{
			throw new Exception( 'Помилка. Файл повинен бути менше ' . ControlLoadFile::MAX_SIZE . ' байт.' );
		}
		return $this;
	}
}