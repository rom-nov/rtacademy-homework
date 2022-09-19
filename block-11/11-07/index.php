<?php declare( strict_types = 1); ?>
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
        <?php
        spl_autoload_register( fn( $class_name ) => require $class_name . '.php' );

        function main( string $img_file ) : void
		{
			try
			{
				$file = ( new ControlLoadFile( $img_file ) )
					-> error_load()
					-> check_mimetypes()
					-> is_oversize();

				$img = ( new GDImageModify( $file -> get_name() ) )
					-> check_size_img( 500 )
					-> crop_instagram()
					-> scale_img( 240, 300 )
                    -> save_file( time() )
                    -> destroy();

				echo( '<img src="' . $img -> full_path() . '" width=auto height=auto>' );
			}
            catch( Exception $error )
			{
				echo( '<div class="error">' . $error -> getMessage() . '</div>' );
				exit();
            }
        }

        //===== main script

        if( empty( $_FILES ) && empty( $_POST ) )
        {
            exit();
        }
        main( 'img' );
        ?>
    </form>
</body>
</html>