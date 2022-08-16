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
            function safe_work( $message )
            {
                if( $message )
                {
                    echo( '<div class="error">' . $message . '</div>' );
                    exit;
                }
            }

            function check_uploaded_file( $name, $max_size = 10485760 )
            {
                switch( true )
                {
                    case is_empty( $_FILES[$name]['name'] ):
                        safe_work( 'Помилка. Необхідно завантажити файл.' );
                        break;
                    case error_load( $_FILES[$name]['error'] ):
                        safe_work( 'Сталася помилка під час завантаження файлу.' );
                        break;
                    case check_mimetypes( $_FILES[$name]['type'] ):
                        safe_work( 'Помилка. Файл повинен мати формат JPEG / PNG / GIF.' );
                        break;
                    case is_oversize( $_FILES[$name]['size'], $max_size ):
                        safe_work( 'Помилка. Файл повинен бути менше ' . $max_size . ' байт.' );
                        break;
                    default:
                        return false;
                }
            }

            function is_empty( $file_name )
            {
                if( empty( $file_name ) )
                {
                    return true;
                }
            }

            function error_load( $error_name )
            {
                if( $error_name !== UPLOAD_ERR_OK )
                {
                    return true;
                }
            }

            function check_mimetypes( $file_type )
            {
                if( !in_array( $file_type, ['image/jpeg', 'image/png', 'image/gif'] ) )
                {
                    return true;
                }
            }

            function is_oversize( $file_size, $max_size )
            {
                if( $file_size > $max_size )
                {
                    return true;
                }
            }

            function creat_img( $name )
            {
                switch( $_FILES[$name]['type'] )
                {
                    case 'image/jpeg':
                        return imagecreatefromjpeg( $_FILES[$name]['tmp_name'] );
                    case 'image/png':
                        return imagecreatefrompng( $_FILES[$name]['tmp_name'] );
                    case 'image/gif':
                        return imagecreatefromgif( $_FILES[$name]['tmp_name'] );
                    default:
                        return false;
                }
            }

            function check_size_img( $width, $height )
            {
                if( $width < 500 || $height < 500 )
                {
                    safe_work( 'Ширина та висота зображення має бути більше 500px.' );
                }
            }

            function crop_img( $img, $width, $height )
            {
                $width_output = $width;
                $height_output = round( $width_output * 1.25 );
                $x_output = 0;
                $y_output = round( ( $height - $height_output ) * 0.5 );
                $image_output = imagecrop( $img, [ 'x' => $x_output, 'y' => $y_output, 'width' => $width_output, 'height' => $height_output ] );
                return check_modifying_img( $image_output );

            }

            function scale_img( $img, $width, $height )
            {
                return check_modifying_img( imagescale( $img, $width, $height ) );
            }

            function check_modifying_img( $img )
            {
                if( $img === false )
                {
                    safe_work( 'Не вдалося обробити зображення.' );
                }
                else
                {
                    return $img;
                }
            }

            function create_dir( $dir_path, $dir_name )
            {
                if( ! file_exists( $dir_path . $dir_name ) )
                {
                    chmod( $dir_path, 0777 );
                    mkdir( $dir_path . $dir_name );
                    chmod( $dir_path, 0775 );
                }
                return $dir_path . $dir_name;
            }

            function save_file( $img, $new_path )
            {
                if( ! imagejpeg( $img, $new_path ) )
                {
                    safe_work( 'Помилка. Не вдалося зберегти файл.' );
                }
            }

            //===== main script

            if( empty( $_FILES ) & empty( $_POST ) )
            {
                exit;
            }
            check_uploaded_file( 'img' );
            $img_origin = creat_img( 'img' );
            $img_width = imagesx( $img_origin );
            $img_height = imagesy( $img_origin );
            check_size_img( $img_width, $img_height );
            $img_output = scale_img ( crop_img( $img_origin, $img_width, $img_height ), 240, 300 );
            $new_path = create_dir( './', 'data/' ) . microtime(true) . '.jpg';
            save_file( $img_output, $new_path );
            echo( '<img src="'. $new_path .'" width=auto height=auto>' );
            imagedestroy( $img_origin );
            imagedestroy( $img_output );
        ?>
    </form>
</body>
</html>