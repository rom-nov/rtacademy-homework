<?php
declare( strict_types = 1);
class ControlLoadFile implements ControlLoadFileInterface
{
	protected string $file;
	protected string $mime_type;

	public function __construct( string $file )
	{
		$this -> file = $file;
		$this -> is_empty() -> set_mime();
	}

	protected function is_empty() : ControlLoadFileInterface
	{
		if( empty( $_FILES [ $this -> file ][ 'name' ] ) )
		{
			throw new Exception( 'Помилка. Необхідно завантажити файл.' );
		}

		return $this;
	}

	protected function set_mime() : ControlLoadFileInterface
	{
		if( !( $this -> mime_type = mime_content_type( $_FILES[ $this -> file ][ 'tmp_name' ] ) ) )
		{
			throw new Exception( 'Помилка визначення типу завантажуваного файлу' );
		}

		return $this;
	}

	public function get_name() : string
	{
		return $this -> file;
	}

	public function get_mime() : string
	{
		return $this -> mime_type;
	}

	public function error_load() : ControlLoadFileInterface
	{
		if( $_FILES[ $this -> file ][ 'error' ] !== UPLOAD_ERR_OK )
		{
			throw new Exception( 'Сталася помилка під час завантаження файлу.' );
		}

		return $this;
	}

	public function check_mimetypes() : ControlLoadFileInterface
	{
		if( !in_array( $this -> mime_type, self::TYPE_MIMY ) )
		{
			throw new Exception( 'Помилка. Файл повинен мати формат JPEG / PNG / GIF.' );
		}

		return $this;
	}

	public function is_oversize() : ControlLoadFileInterface
	{
		if( $_FILES[ $this -> file ][ 'size' ] > self::MAX_SIZE )
		{
			throw new Exception( 'Помилка. Файл повинен бути менше ' . self::MAX_SIZE . ' байт.' );
		}

		return $this;
	}
}