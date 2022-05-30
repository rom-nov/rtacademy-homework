const operand1 = document.getElementById( 'operand1' );
const operand2 = document.getElementById( 'operand2' );
const btnCalc = document.getElementById( 'btn-calc' );
const operation = document.getElementById( 'operation' );
const result = document.getElementById( 'result' );

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
            if( num2 === 0 || num2 === 0n )
            {
                calcResult = NaN;
                operand2.classList.add('error');
            }
            else
            {
                calcResult = num1 / num2;
            }
        break;
        case '%':
            if( num2 === 0 || num2 === 0n )
            {
                calcResult = NaN;
                operand2.classList.add('error');
            }
            else
            {
                calcResult = num1 % num2;
            }
        break;
        case '**':
            ( ( typeof( num1 ) !== 'bigint' && isNaN( num1 ) ) &&
              ( num2 === 0 || num2 === 0n ) ) ? calcResult = NaN : calcResult = num1 ** num2;
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
    let postfix = arg[ number.toString().length ];
    if( !isNaN( number ) && postfix === 'n' && arg.length === number.toString().length + postfix.length ||
        number > Number.MAX_SAFE_INTEGER || number < Number.MIN_SAFE_INTEGER )
    {
        return BigInt( number );
    }
    return NaN;
}

function validationValue( ...values )
{
    let outArr = values.map( function( val )
    {
        switch( true )
        {
            case !val.value.trim():
                return NaN;
            case parseBigInt( val.value ) === 0n:
                return 0n;
            default:
                return parseBigInt( val.value ) || Number( val.value );
        }
    });

    let filterBigInt = outArr.filter( num => typeof( num ) === 'bigint' );
    let filterNumber = outArr.filter( num => typeof( num ) === 'number' );
    let filterNaN = filterNumber.filter( num => isNaN( num ) );

    if( !filterNaN.length && filterBigInt.length && filterBigInt.length !== outArr.length )
    {
        outArr = outArr.map( num => BigInt( num ) );
    }

    if( filterNaN.length )
    {
        outArr = outArr.map( num => Number( num ) );
    }

    outArr.forEach( (item, index) => {
        values[index].classList.remove('error');
        if( typeof(item) !== 'bigint' && isNaN( item ) )
        {
            values[index].classList.add('error');
        }
    } );

    return outArr;
}