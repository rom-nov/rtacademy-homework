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

    const dateDifObj = {
        calc( start, end )
        {
            const msDif = ( end - start ) / 1000;
            this.d = Math.trunc( msDif / 86400 );
            this.h = Math.trunc( msDif / 3600 ) % 24;
            this.m = Math.round( ( ( msDif / 3600 ) - Math.trunc( msDif / 3600 ) ) * 60 );
        }
    };
    dateDifObj.calc( Date.parse( dateStart.value ), Date.parse( dateEnd.value ) );

    visualResult( regexpDate, dateStart.value, dateEnd.value, dateDifObj );
}

function visualResult( regexp, dateStart, dateEnd, dateDifObj )
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
                            ${ choicePhrase( dateDifObj, 'uk' ) }`;

        wrapper.append( header );
        wrapper.append( content );
        element.append( wrapper );
    }
}

function choicePhrase( dateObj, lang )
{
    const phrase = [];
    const dictionary = {
        uk: {
            d: ['день', 'дні', 'днів'],
            h: ['годину', 'години', 'годин'],
            m: ['хвилину', 'хвилини', 'хвилин']
        },
        eng: {
            d: ['day', 'days', 'days'],
            h: ['hour', 'hours', 'hours'],
            m: ['minute', 'minutes', 'minutes']
        }
    };

    for( const key in dateObj )
    {
        if( typeof dateObj[ key ] !== 'number' || !dateObj[ key ] )
        {
            continue;
        }
        const index = searchIndex( dateObj[ key ] );
        phrase.push( dateObj[ key ] + ' ' + dictionary[ lang ][ key ][ index ] );
    }

    return phrase.join(' ');
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

function searchIndex( number )
{
    const end1 = /\d*(?<!1)1$/;
    const end234 = /\d*(?<!1)[234]$/;
    //(?<!1) - попередній символ не має бути 1
    switch( true )
    {
        case end1.test( number.toString() ):
            return  0;
        case end234.test( number.toString() ):
            return  1;
        default:
            return  2;
    }
}

function clearResult()
{
    if( document.querySelector( '.result' ) )
    {
        document.querySelector( '.result' ).remove();
    }
}