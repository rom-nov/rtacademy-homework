<?php
declare( strict_types = 1 );
spl_autoload_register( fn( $class ) => require $class.'.php');
Main::start();
?>
<!doctype html>
<html lang="uk">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>11.4</title>
</head>
<body>
	<form id="form" action="" method="POST">
		<div class="form_row">
			<label for="fullname">Повне імʼя:</label> <br>
			<input id="fullname"  type="text" name="fullname" required placeholder="Прізвище Ім'я">
		</div>
		<div class="form_row">
			<label for="email">Email:</label> <br>
			<input id="email" type="email" name="email" required placeholder="email@email.com">
		</div>
		<div class="form_row">
			<label for="message">Повідомлення:</label> <br>
			<textarea id="message" name="message" required maxlength="200" minlength="3"></textarea>
		</div>
		<div class="form_row">
            <input id="agree" type="checkbox" name="agree" value="yes" required>
            <label for="agree">Я погоджуюсь з тим, що мені будуть надсилати спам-повідомлення</label>
		</div>
		<div class="form_row">
			<button id="btn-submit" type="submit" disabled="disabled">Надіслати</button>
		</div>
	</form>
    <script src="script.js"></script>
    <?php
    $result = Main::result_message();
    $error = Main::error_message();
    if( $error )
    {
        echo $error;
    }
    if( $result )
    {
        echo $result;
    }
    ?>
</body>
</html>