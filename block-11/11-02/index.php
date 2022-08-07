<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>11.02</title>
</head>
<body>
    <pre>
    <?php
        $boolVar = true;
        $intVar = 10;
        $floatVar = 2.4;
        $strAps = 'Lorem ipsum dolor sit amet.';
        $strQuot = "Lorem ipsum dolor sit amet $floatVar";
        $strHere = <<< TESTTEXTHERE
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
$intVar $floatVar
TESTTEXTHERE;
        $strNow = <<<'TESTTEXTNOW'
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet consequatur consequuntur, illo inventore molestias nihil non quam similique unde vero.
TESTTEXTNOW;
        $arrNum = [ 1, 2, 3, 4, 'string', true, 7 ];
        $arrMap = [ 'item1' => 1, 'item2' => 2, 'item3' => 'string', 'item4' => 4.4, 'item5' => false ];
        $nullVar = NULL;
        echo( '<br>' . '===== var_dump =====' . '<br>' );
        var_dump( $intVar );
        var_dump( $floatVar );
        var_dump( $boolVar );
        var_dump( $strAps );
        var_dump( $strQuot );
        var_dump( $strHere );
        var_dump( $strNow );
        var_dump( $arrNum );
        var_dump( $arrMap );
        var_dump( $nullVar );
        echo( '<br>' . '===== print_r =====' . '<br>' );
        print_r( $intVar . '<br>' );
        print_r( $floatVar . '<br>' );
        print_r( $boolVar ? 'true' . '<br>' : 'false' . '<br>' );
        print_r( $strAps . '<br>' );
        print_r( $strQuot . '<br>' );
        print_r( $strHere . '<br>' );
        print_r( $strNow . '<br>' );
        print_r( $arrNum );
        print_r( $arrMap );
        print_r( is_null( $nullVar ) ? 'NULL'.'<br>' : 'NO NULL'.'<br>' );
        echo( '<br>' . '===== echo =====' . '<br>' );
        echo( $intVar . '<br>');
        echo( $floatVar . '<br>' );
        echo( $boolVar ? 'true' . '<br>' : 'false' . '<br>' );
        echo( $strAps . '<br>' );
        echo( $strQuot . '<br>' );
        echo( $strHere . '<br>' );
        echo( $strNow . '<br>' );
        echo( implode( ', ', $arrNum ) . '<br>');
        echo( implode( ', ', $arrMap ) . '<br>' );
        echo( is_null( $nullVar ) ? 'NULL' : 'NO NULL' );
    ?>
    </pre>
</body>
</html>