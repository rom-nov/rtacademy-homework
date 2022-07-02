const formPhoto = {
    lastname     : [ document.getElementById( 'lastname' ),   'text' ],
    firstname    : [ document.getElementById( 'firstname' ),  'text' ],
    email        : [ document.getElementById( 'email' ),      'email' ],
    telphone     : [ document.getElementById( 'telphone' ),   'tel' ],
    instagram    : [ document.getElementById( 'instagram' ),  'url' ],
    room         : [ document.getElementById( 'room' ),       'select-one' ],
    startDate    : [ document.getElementById( 'startdate' ),  'datetime-local' ],
    endDate      : [ document.getElementById( 'enddate' ),    'datetime-local' ],
    numberPeople : [ document.getElementById( 'number' ),     'number' ],
    colorFon     : [ document.getElementById( 'color' ),      'color' ],
    comment      : [ document.getElementById( 'comment' ),    'textarea' ],
    rule         : [ document.getElementById( 'rule' ),       'checkbox' ],
    btnClear     : [ document.getElementById( 'btn-clear' ),  'reset' ],
    btnSubmit    : [ document.getElementById( 'btn-submit' ), 'submit' ],
};

document.addEventListener( 'click', registration );

function registration( event )
{
    const target = event.target;

    if( target === formPhoto.rule[0] )
    {
        if( formPhoto.rule[0].checked )
        {
            formPhoto.btnSubmit[0].removeAttribute( 'disabled' );
        }
        else
        {
            formPhoto.btnSubmit[0].setAttribute( 'disabled', 'disabled' );
        }
    }

    if( target === formPhoto.btnSubmit[0] )
    {
        if ( !checkFields( formPhoto ) || !checkDate( formPhoto ) )
        {
            event.preventDefault();
            return;
        }
        alert( 'Вартість оренди: ' + calcBill( formPhoto ) );
    }
}

function calcBill( form )
{
    // 1год = 60 * 60 * 1000 = 3600000мс
    // 1хв = 60 * 1000 = 60000мс
    // Зал 1: 1000 [грн/год] / 3600000 = 0.000277778 [грн/мс]
    // Зал 2: 900 [грн/год] / 3600000 = 0.00025 [грн/мс]
    // Зал 3: 800 [грн/год] / 3600000 = 0.000222222 [грн/мс]

    let multiplierPeople = 0;
    const beginDate = Date.parse( form.startDate[0].value ),
          finishDate = Date.parse( form.endDate[0].value );
    let nightStart, nightEnd;

    if( beginDate < Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T08:00:00' ) &&
        beginDate >= Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T00:00:00' ) )
    {
        nightStart = Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T00:00:00' );
        nightEnd = Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T08:00:00' );
    }
    else
    {
        nightStart = Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T21:00:00' );
        nightEnd = Date.parse( form.startDate[0].value.slice( 0, 10 ) + 'T23:59' ) + 60000 + (8 * 60 * 60 * 1000) ;
    }

    if( form.numberPeople[0].value - 3 > 0 )
    {
        multiplierPeople = 50 * ( form.numberPeople[0].value - 3 );
    }

    switch ( true )
    {
        //початок сеансу та кінець сеансу попали у відрізок часу 21:00 - 08:00
        case( ( beginDate >= nightStart ) &&
              ( finishDate <= nightEnd ) ):
            return form.room[0].value / 3600000 * ( finishDate - beginDate ) * 1.5 + multiplierPeople;
        //початок сеансу раніше 21:00, а кінець сеансу у проміжку часу 21:00 - 08:00
        case( ( beginDate - nightStart ) < 0 &&
              ( finishDate > nightStart ) &&
              ( finishDate <= nightEnd ) ):
            return form.room[0].value / 3600000 * ( Math.abs( beginDate - nightStart ) + ( finishDate - nightStart ) * 1.5 ) + multiplierPeople;
        //початок сеансу у проміжку часу 21:00 - 08:00, а кінець сеансу після 08:00
        case( ( nightEnd - finishDate ) < 0 &&
              ( beginDate >= nightStart ) &&
              ( beginDate < nightEnd ) ):
            return form.room[0].value / 3600000 * ( (nightEnd - beginDate) * 1.5 + Math.abs( nightEnd - finishDate ) ) + multiplierPeople;
        //початок сеансу раніше 21:00, а кінець сеансу після 08:00
        case( ( beginDate - nightStart ) < 0 &&
              ( nightEnd - finishDate ) < 0 ):
            return form.room[0].value / 3600000 * ( Math.abs( beginDate - nightStart ) + Math.abs( nightEnd - finishDate ) + ( nightEnd - nightStart ) * 1.5 ) + multiplierPeople;
        //інші проміжки часу
        default:
            return form.room[0].value / 3600000 * ( finishDate - beginDate ) + multiplierPeople;
    }
}

function checkDate( form )
{
    const beginDate = Date.parse( form.startDate[0].value ),
          finishDate = Date.parse( form.endDate[0].value );
    switch( true )
    {
        case( finishDate <= beginDate ):
            alert( 'Дата і час початку не має бути більшою за дату і час кінця або дорівнювати їй' );
            return false;
        case( Math.trunc( beginDate / 3600000 ) < Math.trunc( Date.now() / 3600000 ) ):
            alert( 'Дата і час початку не має бути раніше поточної години' );
            return false;
        case( finishDate % beginDate < 3600000 ):
            alert( 'Мінімальне замовлення має бути кратне одній годині' );
            return false;
    }
    return true;
}

function checkFields( form )
{
    for (const key in form)
    {
        if( !( form[key][0] && form[key][0].type === form[key][1] ) )
        {
            console.error( 'Пороблено!' );
            return false;
        }
    }
    return true;
}

