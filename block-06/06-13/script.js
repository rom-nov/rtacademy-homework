if( document.getElementById( 'btn-calc' ) )
{
    document.getElementById( 'btn-calc' ).addEventListener( 'click', calc );
}

function calc()
{
    clearResult();

    const regexpDate = /^([1-9]\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])T([01]\d|2[0-3]):([0-5]\d)$/;
    const dateStart = document.getElementById( 'date-start' );
    const dateEnd = document.getElementById( 'date-end' );

    if( !validationDate( regexpDate, dateStart, dateEnd ) )
    {
        return;
    }

    const msStart = Date.parse( dateStart.value );
    const msEnd = Date.parse( dateEnd.value );
    const msDif = ( msEnd - msStart ) / 1000;
    const dayDif = Math.trunc( msDif / 86400 );
    const hourDif = Math.trunc( msDif / 3600 ) % 24;
    const minDif = Math.round( ( ( msDif / 3600 ) - Math.trunc( msDif / 3600 ) ) * 60 );

    visualResult( regexpDate, dateStart.value, dateEnd.value, dayDif, hourDif, minDif );
}

function visualResult( regexp, dateStart, dateEnd, day, hours, min )
{
    const element = document.querySelector( '.wrapper' );
    const start = dateStart.match( regexp );
    const end = dateEnd.match( regexp );

    if( element )
    {
        const wrapper = document.createElement( 'div' );
        wrapper.classList.add( 'result' );

        const header = document.createElement( 'h3' );
        header.append( document.createTextNode( 'Різниця між датами' ) );
        header.classList.add( 'result__h3' );

        const content = document.createElement( 'p' );
        content.innerHTML = `Різниця між <span>${start[3]}.${start[2]}.${start[1]} ${start[4]}:${start[5]}</span> та 
                            <span>${end[3]}.${end[2]}.${end[1]} ${end[4]}:${end[5]}</span> становить <br>
                            ${ choicePhrase( day, 'd', 'uk' ) }
                            ${ choicePhrase( hours, 'h', 'uk' ) }
                            ${ choicePhrase( min, 'm', 'uk' ) }`;

        wrapper.append( header );
        wrapper.append( content );
        element.append( wrapper );
    }
}

function choicePhrase( number, flag, lang )
{
    const end1 = /\d*(?<!1)1$/;
    const end234 = /\d*(?<!1)[234]$/;
    let indexLang = 0;
    let word;
    let dWord = [ ['день', 'дні', 'днів'],          ['day', 'days', 'days'] ];
    let hWord = [ ['годину', 'години', 'годин'],    ['hour', 'hours', 'hours'] ];
    let mWord = [ ['хвилину', 'хвилини', 'хвилин'], ['minute', 'minutes', 'minutes'] ];

    switch( lang )
    {
        case 'eng':
            indexLang = 1;
        break;
        default:
            indexLang = 0;
        break;
    }

    switch( true )
    {
        case number === 0:
            return '';
        case end1.test( number.toString() ):
            ( flag === 'd' ) ? word = dWord[indexLang][0] : ( ( flag === 'h' ) ? word = hWord[indexLang][0] : word = mWord[indexLang][0] );
            break;
        case end234.test( number.toString() ):
            ( flag === 'd' ) ? word = dWord[indexLang][1] : ( ( flag === 'h' ) ? word = hWord[indexLang][1] : word = mWord[indexLang][1] );
            break;
        default:
            ( flag === 'd' ) ? word = dWord[indexLang][2] : ( ( flag === 'h' ) ? word = hWord[indexLang][2] : word = mWord[indexLang][2] );
            break;
    }

    return number + ' ' + word;
}

function validationDate( regexp, dateStart, dateEnd )
{
    const arr = [ dateStart, dateEnd ];
    let check = true;
    arr.forEach( element => {
        element.classList.remove('error');
        if( !regexp.test( element.value ) || !element.value )
        {
            element.classList.add('error');
            check = false;
        }
    } );
    if( dateEnd.value < dateStart.value )
    {
        dateStart.classList.add('error');
        dateEnd.classList.add('error');
        check = false;
    }
    return check;
}

function clearResult()
{
    if( document.querySelector( '.result' ) )
    {
        document.querySelector( '.result' ).remove();
    }
}