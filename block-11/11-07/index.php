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
        require 'ControlLoadFile.php';
        require 'GDImageModifyFile.php';

        function create_dir( string $dir_path, string $dir_name ) : string
        {
            if( !file_exists( $dir_path . $dir_name ) )
            {
                chmod( $dir_path, 0777 );
                mkdir( $dir_path . $dir_name );
                chmod( $dir_path, 0775 );
            }
            return $dir_path . $dir_name;
        }

        function save_file( GdImage $img, string $new_path ) : void
        {
            if( !imagejpeg( $img, $new_path ) )
            {
                throw new Exception( 'Помилка. Не вдалося зберегти файл.' );
            }
        }

        function main( ControlLoadFile $file, GDImageModifyFile $img ) : void
		{
			$file -> is_empty()
                  -> error_load()
                  -> set_mime()
                  -> check_mimetypes()
                  -> is_oversize();

			$img -> check_size_img( 500 )
				 -> crop_instagram()
				 -> scale_img( 240, 300 );

			$path_file = create_dir( './', 'data/' ) . time() . '.jpg';
			save_file( $img -> get_img(), $path_file );
			echo( '<img src="' . $path_file . '" width=auto height=auto>' );
        }

        //===== main script

        if( empty( $_FILES ) & empty( $_POST ) )
        {
            exit();
        }

        try
        {
            main( new ControlLoadFile( 'img' ), new GDImageModifyFile( 'img' ) );
        }
        catch( Exception $error )
        {
            echo( '<div class="error">' . $error -> getMessage() . '</div>' );
            exit();
        }
        ?>
    </form>
</body>
</html>