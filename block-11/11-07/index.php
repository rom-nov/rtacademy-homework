<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>11.7</title>
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
        if( empty( $_FILES ) & empty( $_POST ) )
        {
            exit;
        }

        $error_message = '';

        function upload_file( $name, $max_size = 10485760 )
        {
            global $error_message;
            //print_r( $_FILES[$name] );
            //echo '<br>';
            switch( true )
            {
                case is_empty( $_FILES[$name]['name'] ):
                    $error_message = is_empty( $_FILES[$name]['name'] );
                    break;
                case error_load( $_FILES[$name]['error'] ):
                    $error_message = error_load( $_FILES[$name]['error'] );
                    break;
                case check_mimetypes( $_FILES[$name]['type'] ):
                    $error_message = check_mimetypes( $_FILES[$name]['type'] );
                    break;
            }
        }
        upload_file( 'img' );

        function is_empty( $file_name ) // перевірка на відправку файла
        {
            if( empty( $file_name ) )
            {
                return 'Помилка. Необхідно завантажити файл.';
            }
        }

        function error_load( $error_name ) // перевірка успішне завантаження файлу на сервер
        {
            if( $error_name !== UPLOAD_ERR_OK )
            {
                return 'Сталася помилка під час завантаження файлу.';
            }
        }

        function check_mimetypes( $file_type )
        {
            if( !in_array( $file_type, ['image/jpeg', 'image/png', 'image/gif'] ) )
            {
                return 'Помилка. Файл повинен мати формат JPEG / PNG / GIF';
            }
        }

        function get_mimetypes( $file_type )
        {
            switch( true )
            {
                case in_array( $file_type, ['image/jpeg'] ):
                    return '.jpg';
                case in_array( $file_type, ['image/png'] ):
                    return '.png';
                case in_array( $file_type, ['image/gif'] ):
                    return '.gif';
            }
        }
    ?>
    <div class="error">
        <?php
            echo $error_message;
        ?>
    </div>
</body>
</html>