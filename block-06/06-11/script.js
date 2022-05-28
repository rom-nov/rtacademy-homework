let operand1 = document.getElementById( 'operand1' );
let operand2 = document.getElementById( 'operand2' );
let btnCalc = document.getElementById( 'btn-calc' );
let operation = document.getElementById( 'operation' );
let result = document.getElementById( 'result' );

btnCalc.addEventListener( 'click', calc );

function calc()
{
    let [ num1, num2 ] = validationValue( operand1, operand2 );
    let calcResult;

    operation.classList.remove('error');
    switch ( operation.value )
    {
        case '+':
            calcResult = num1 + num2;
        break;
        case '-':
            calcResult = num1 - num2;
        break;
        case '*':
            calcResult = num1 * num2;
        break;
        case '/':
            calcResult = num1 / num2;
        break;
        case '%':
            calcResult = num1 % num2;
        break;
        case '**':
            calcResult = num1 ** num2;
        break;
        default:
            operation.classList.add('error');
        break;
    }

    if( typeof( calcResult ) === 'bigint' )
    {
        result.value = calcResult + 'n';
    }
    else if( !isNaN( calcResult ) )
    {
        result.value = calcResult;
    }
}

function parseBigInt( arg )
{
    arg = arg.trim();
    let number = parseInt( arg );
    let prefix = arg[ number.toString().length ];
    if( !isNaN( number ) && prefix === 'n' && arg.length === number.toString().length + prefix.length )
    {
        return BigInt( number );
    }
}

function validationValue( ...values )
{
    let outArr = values.map( ( element ) => ( !element.value.trim() ) ? NaN : parseBigInt( element.value ) || Number( element.value ) );

    outArr.forEach( (item, index) => {
        values[index].classList.remove('error');
        if( typeof(item) !== 'bigint' && isNaN( item ) )
        {
            values[index].classList.add('error');
        }
    } );

    switch( true )
    {
        case( typeof( outArr[0] ) === 'bigint' && typeof( outArr[1] ) !== 'bigint' && !isNaN( outArr[1] ) ):
            outArr[1] = BigInt( outArr[1] );
            break;
        case( typeof( outArr[1] ) === 'bigint' && typeof( outArr[0] ) !== 'bigint' && !isNaN( outArr[0] ) ):
            outArr[0] = BigInt( outArr[0] );
            break;
        case( typeof( outArr[0] ) !== 'bigint' && isNaN( outArr[0] ) || typeof( outArr[1] ) !== 'bigint' && isNaN( outArr[1] ) ):
            [ outArr[0], outArr[1] ] = [ Number( outArr[0] ), Number( outArr[1] ) ];
            break;
    }
    return outArr;
}