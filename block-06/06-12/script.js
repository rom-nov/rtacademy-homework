if( document.getElementById( 'btn-calc' ) )
{
    document.getElementById( 'btn-calc' ).addEventListener( 'click', calc );
}

function calc()
{
    const operand1 = document.getElementById( 'operand1' );
    const operand2 = document.getElementById( 'operand2' );
    const btnCalc = document.getElementById( 'btn-calc' );
    const operation = document.getElementById( 'operation' );
    const result = document.getElementById( 'result' );

    if( !(operand1 && operand1.type === 'text'  &&
         operand2 && operand2.type === 'text' &&
         operation && operation.type === 'select-one' &&
         result && result.type === 'text' &&
         btnCalc.type === 'button') )
    {
        console.log( 'Пороблено!' );
        return;
    }

    let [ num1, num2 ] = validationNumber( operand1, operand2 );
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

function validationNumber( ...values )
{
    const regexpNumber = /^[+-]?(\d|(0\.|[1-9]\d*\.?)\d+)$/;
    const regexpBigInt = /^[+-]?\d+n$/i;

    let outArr = values.map( function( str )
    {
        switch( true )
        {
            case regexpNumber.test( str.value ):
                return ( Number( str.value ) > Number.MAX_SAFE_INTEGER ||
                         Number( str.value ) < Number.MIN_SAFE_INTEGER ) ?
                         BigInt( parseInt( str.value ) ) : Number( str.value );
            case regexpBigInt.test( str.value ):
                return BigInt( parseInt( str.value ) );
            default:
                return NaN;
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