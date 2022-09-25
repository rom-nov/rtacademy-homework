<?php
declare( strict_types = 1);
spl_autoload_register( fn( $class_name ) => require $class_name . '.php' );
Main::start( 'img' );
?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>11.7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="form" enctype="multipart/form-data" method="post">
        <div class="form__row">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
            <input class="form__img" name="img" type="file" accept="image/jpeg, image/png, image/gif">
        </div>
        <div class="form__row">
            <button class="form__btn" type="submit">Надіслати</button>
        </div>
    </form>
    <?php
    $result = Main::path_img();
    $error = Main::get_error();
    if( $result )
	{
		echo( '<img class="image" src="' . $result . '" width=auto height=auto>' );
    }
    if( $error )
	{
		echo( '<div class="error">' . $error . '</div>' );
    }
    ?>
</body>
</html>