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
    const startDate = Date.parse( form.startDate.element.value ),
          finishDate = Date.parse( form.endDate.element.value ),
          parseStartDay = form.startDate.element.value.slice( 0, 10 ),
          parseFinishDay = form.endDate.element.value.slice( 0, 10 );
    let nightBeginStartDate, nightEndStartDate,
        nightBeginFinishDate, nightEndFinishDate,
        partNightStartDate, partDayStartDate,
        partNightFinishDate, partDayFinishDate,
        countDay, night, day, bill;
    //коефіцієнт кількості людей
    if( form.numberPeople.element.value - 3 > 0 )
    {
        multiplierPeople = 50 * ( form.numberPeople.element.value - 3 );
    }
    //визначення нічного часту для дати початку сеансу
    [ nightBeginStartDate, nightEndStartDate ] = setNightTime( startDate, parseStartDay );
    //визначення нічного часту для дати закінчення сеансу
    [ nightBeginFinishDate, nightEndFinishDate ] = setNightTime( finishDate, parseFinishDay );
    if( ( finishDate - startDate ) % ( 24 * 3600000 ) === 0 )
    {   //якщо кількість годин між початком та кінцем сеансу кратна добі,
        //тоді частини доби для дати початку та дати кінця сеансу відсутні
        partNightStartDate = 0;
        partDayStartDate = 0;
        partNightFinishDate = 0;
        partDayFinishDate = 0;
    }
    else
    {   //інакше рахуємо нічні та денні години дати початку та дати кінця сеансу
        if( startDate <= nightEndStartDate && startDate >= nightBeginStartDate )
        {
            partNightStartDate = nightEndStartDate - startDate +
                               ( startDate <= Date.parse( parseStartDay + 'T21:00:00' ) ? 3 * 3600000 : 0) ;
            partDayStartDate = ( startDate <= Date.parse( parseStartDay + 'T21:00:00' ) ? 13 * 3600000 : 0 );
        }
        else
        {
            partNightStartDate = 3 * 3600000;
            partDayStartDate = nightBeginStartDate - startDate;
        }

        if( finishDate <= nightEndFinishDate && finishDate >= nightBeginFinishDate )
        {
            partNightFinishDate = finishDate - nightBeginFinishDate +
                                ( finishDate >= Date.parse( parseFinishDay + 'T21:00:00' ) ? 8 * 3600000 : 0 );
            partDayFinishDate = ( finishDate >= Date.parse( parseFinishDay + 'T21:00:00' ) ? 13 * 3600000 : 0 );
        }
        else
        {
            partNightFinishDate = 8 * 3600000;
            partDayFinishDate = finishDate - Date.parse( parseFinishDay + 'T08:00:00' );
        }

    }
    //кількість "цілих" днів
    countDay = ( finishDate - partNightFinishDate - partDayFinishDate ) - ( startDate + partNightStartDate + partDayStartDate );
    countDay = Math.trunc( countDay / ( 24 * 3600000 ) );
    //кількість нічних годин
    night = ( countDay * 11 * 3600000 + partNightStartDate + partNightFinishDate ) * 1.5 ;
    //кількість денних годин
    day = countDay * 13 * 3600000 + partDayStartDate + partDayFinishDate;
    bill = ( night + day ) * form.room.element.value / 3600000 + multiplierPeople;
    return Math.round( bill );
}

function setNightTime( date, parseDate )
{
    if( date >= Date.parse( parseDate + 'T08:00:00' ) )
    {
        return [ Date.parse( parseDate + 'T21:00:00' ), Date.parse( parseDate + 'T23:59:59' ) ];
    }
    else
    {
        return [ Date.parse( parseDate + 'T00:00:00' ), Date.parse( parseDate + 'T08:00:00' ) ];
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

