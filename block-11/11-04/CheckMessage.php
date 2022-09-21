<?php
declare( strict_types = 1 );
class CheckMessage
{
	protected string $fullname = '';
	protected string $email = '';
	protected string $message = '';
	protected string $agree = '';
	protected const REGEXP_FULLNAME = "~([А-ЯІЇЄҐа-яіїєґ]+['-]?[А-ЯІЇЄҐа-яіїєґ]+)+\s([А-ЯІЇЄҐа-яіїєґ]+['-]?[А-ЯІЇЄҐа-яіїєґ]+)+~";
	protected const REGEXP_EMAIL = '~[A-Za-z0-9._%+-]+@[A-Za-z0-9-.]+.[A-Za-z]{2,4}~';

	public function __construct()
	{
		if( count( $_POST ) !== 4 )
		{
			throw new Exception( 'Помилка, заповніть форму знов' );
		}
		foreach( $_POST as $value  )
		{
			$this -> is_empty( $value );
		}
		$this -> fullname = strip_tags( trim( $_POST['fullname'] ) );
		$this -> email = strip_tags( trim( $_POST['email'] ) );
		$this -> message = strip_tags( trim( $_POST['message'] ) );
		$this -> agree = strip_tags( $_POST['agree'] );
	}

	public function get_fullname() : string
	{
		return $this -> fullname;
	}

	public function get_email() : string
	{
		return $this -> email;
	}

	public function get_message() : string
	{
		return $this -> message;
	}

	public function check() : self
	{
		$this -> check_fullname() -> check_email() -> check_message();
		return $this;
	}

	protected function is_empty( string $value ) : void
	{
		if( empty( $value ) )
		{
			throw new Exception( 'Помилка, заповніть форму знов' );
		}
	}

	protected function check_fullname() : self
	{
		if( !preg_match( self::REGEXP_FULLNAME, $this -> fullname ) )
		{
			throw new Exception( 'Помилка, не коректне прізвище та ім\'я' );
		}
		return $this;
	}

	protected function check_email() : self
	{
		if( !preg_match( self::REGEXP_EMAIL, $this -> email ) )
		{
			throw new Exception( 'Помилка, не коректний email' );
		}
		return $this;
	}

	protected function check_message() : self
	{
		if( ( strlen( $this -> message ) < 3 || strlen( $this -> message ) > 200 ) )
		{
			throw new Exception( 'Обсяг повідомлення має бути від 3 до 200 символів' );
		}
		return $this;
	}
}