const formPhoto = {
    lastname     : { element: document.getElementById( 'lastname' ),   type: 'text' },
    firstname    : { element: document.getElementById( 'firstname' ),  type: 'text' },
    email        : { element: document.getElementById( 'email' ),      type: 'email' },
    telphone     : { element: document.getElementById( 'telphone' ),   type: 'tel' },
    instagram    : { element: document.getElementById( 'instagram' ),  type: 'url' },
    room         : { element: document.getElementById( 'room' ),       type: 'select-one' },
    startDate    : { element: document.getElementById( 'startdate' ),  type: 'datetime-local' },
    endDate      : { element: document.getElementById( 'enddate' ),    type: 'datetime-local' },
    numberPeople : { element: document.getElementById( 'number' ),     type: 'number' },
    colorFon     : { element: document.getElementById( 'color' ),      type: 'color' },
    comment      : { element: document.getElementById( 'comment' ),    type: 'textarea' },
    rule         : { element: document.getElementById( 'rule' ),       type: 'checkbox' },
    btnClear     : { element: document.getElementById( 'btn-clear' ),  type: 'reset' },
    btnSubmit    : { element: document.getElementById( 'btn-submit' ), type: 'submit' },
};

if( formPhoto.rule.element )
{
    formPhoto.rule.element.addEventListener( 'click', unblockSubmit );
}

if( formPhoto.btnClear.element )
{
    formPhoto.btnClear.element.addEventListener( 'click', blockSubmit );
}

if( formPhoto.btnSubmit.element )
{
    formPhoto.btnSubmit.element.addEventListener( 'click', registration );
}

function registration( event )
{
    if ( !checkFields( formPhoto ) || !checkDates( formPhoto ) )
    {
        event.preventDefault();
        return;
    }

    const bill = calcBill( formPhoto );
    if( isNaN( bill ) )
    {
        alert( 'Вартість оренди розрахувати неможливо' );
        return;
    }
    alert( 'Вартість оренди: ' + bill );
}

function unblockSubmit()
{
    if( formPhoto.rule.element.checked )
    {
        formPhoto.btnSubmit.element.removeAttribute( 'disabled' );
    }
    else
    {
        formPhoto.btnSubmit.element.setAttribute( 'disabled', 'disabled' );
    }
}

function blockSubmit()
{
    if( !formPhoto.btnSubmit.element.hasAttribute( 'disabled' ) )
    {
        formPhoto.btnSubmit.element.setAttribute( 'disabled', 'disabled' );
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
    const beginDate = Date.parse( form.startDate.element.value ),
          finishDate = Date.parse( form.endDate.element.value );
    let nightStart, nightEnd;

    if( beginDate < Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T08:00:00' ) &&
        beginDate >= Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T00:00:00' ) )
    {
        nightStart = Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T00:00:00' );
        nightEnd = Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T08:00:00' );
    }
    else
    {
        nightStart = Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T21:00:00' );
        nightEnd = Date.parse( form.startDate.element.value.slice( 0, 10 ) + 'T23:59' ) + 60000 + (8 * 60 * 60 * 1000) ;
    }

    if( form.numberPeople.element.value - 3 > 0 )
    {
        multiplierPeople = 50 * ( form.numberPeople.element.value - 3 );
    }

    switch ( true )
    {
        //початок сеансу та кінець сеансу попали у відрізок часу 21:00 - 08:00
        case( ( beginDate >= nightStart ) &&
              ( finishDate <= nightEnd ) ):
            return Math.round( form.room.element.value / 3600000 * ( finishDate - beginDate ) * 1.5 + multiplierPeople );
        //початок сеансу раніше 21:00, а кінець сеансу у проміжку часу 21:00 - 08:00
        case( ( beginDate - nightStart ) < 0 &&
              ( finishDate > nightStart ) &&
              ( finishDate <= nightEnd ) ):
            return Math.round( form.room.element.value / 3600000 * ( Math.abs( beginDate - nightStart ) + ( finishDate - nightStart ) * 1.5 ) + multiplierPeople );
        //початок сеансу у проміжку часу 21:00 - 08:00, а кінець сеансу після 08:00
        case( ( nightEnd - finishDate ) < 0 &&
              ( beginDate >= nightStart ) &&
              ( beginDate < nightEnd ) ):
            return Math.round( form.room.element.value / 3600000 * ( (nightEnd - beginDate) * 1.5 + Math.abs( nightEnd - finishDate ) ) + multiplierPeople );
        //початок сеансу раніше 21:00, а кінець сеансу після 08:00
        case( ( beginDate - nightStart ) < 0 &&
              ( nightEnd - finishDate ) < 0 ):
            return Math.round( form.room.element.value / 3600000 * ( Math.abs( beginDate - nightStart ) + Math.abs( nightEnd - finishDate ) + ( nightEnd - nightStart ) * 1.5 ) + multiplierPeople );
        //інші проміжки часу
        default:
            return Math.round( form.room.element.value / 3600000 * ( finishDate - beginDate ) + multiplierPeople );
    }
}

function checkDates( form )
{
    const beginDate = Date.parse( form.startDate.element.value ),
          finishDate = Date.parse( form.endDate.element.value );
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
        if( !( form[key].element && form[key].element.type === form[key].type ) )
        {
            console.error( 'Пороблено!' );
            return false;
        }
    }
    return true;
}

