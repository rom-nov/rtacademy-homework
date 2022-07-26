const regexpName = /^((([a-z])+([\s\'\-])?([a-z])+)+|(([а-яіїєґ])+([\s\'\-])?([а-яіїєґ])+)+)$/i,
      regexpEmail = /[A-Z0-9\.\_\%\+\-]+\@[A-Z0-9\-\.]+\.[A-Z]{2,4}/i,
      regexpTelephone = /^\+380\d{9}$/,
      regexpInstagram = /^(https\:\/\/)?(www\.)?instagram\.com\/[\w\-\/]+/i,
      regexpRoom = /800|900|1000/,
      regexpDate = /^([1-9]\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])T([01]\d|2[0-3]):([0-5]\d)$/,
      regexpPeople = /^([1-9]|10)$/,
      regexpColor = /\#([a-f0-9]){6}/i;
const formPhoto = {
    lastname     : { id: 'lastname',   type: 'text',           name:'Прізвище',            required: true,  min: 2,    max: 64,   regexp: regexpName },
    firstname    : { id: 'firstname',  type: 'text',           name:'Ім\'я',               required: true,  min: 2,    max: 64,   regexp: regexpName },
    email        : { id: 'email',      type: 'email',          name:'Email',               required: true,  min: 6,    max: 255,  regexp: regexpEmail },
    telephone    : { id: 'telephone',  type: 'tel',            name:'Телефон',             required: true,  min: 13,   max: 13,   regexp: regexpTelephone },
    instagram    : { id: 'instagram',  type: 'url',            name:'Instagram',           required: false, min: 23,   max: 255,  regexp: regexpInstagram },
    room         : { id: 'room',       type: 'select-one',     name:'Зал',                 required: true,  min: null, max: null, regexp: regexpRoom },
    startDate    : { id: 'startdate',  type: 'datetime-local', name:'Дата та час початку', required: true,  min: null, max: null, regexp: regexpDate },
    endDate      : { id: 'enddate',    type: 'datetime-local', name:'Дата та час кінця',   required: true,  min: null, max: null, regexp: regexpDate },
    numberPeople : { id: 'number',     type: 'number',         name:'Кількість людей',     required: true,  min: null, max: null, regexp: regexpPeople },
    colorFon     : { id: 'color',      type: 'color',          name:'Колір фону',          required: false, min: null, max: null, regexp: regexpColor },
    comment      : { id: 'comment',    type: 'textarea',       name:'Коментар',            required: false, min: null, max: null, regexp: null },
    rule         : { id: 'rule',       type: 'checkbox',       name:'Правила фотостудії',  required: false, min: null, max: null, regexp: null },
    btnClear     : { id: 'btn-clear',  type: 'reset',          name:'Очистити',            required: false, min: null, max: null, regexp: null },
    btnSubmit    : { id: 'btn-submit', type: 'button',         name:'Надіслати',           required: false, min: null, max: null, regexp: null },
    setElements() {
        for( const key in this )
        {
            this[ key ].element = document.getElementById( this[ key ].id );
        }
    }
};
Object.defineProperty( formPhoto, "setElements", { enumerable: false } );
formPhoto.setElements();
formPhoto.startDate.element.setAttribute( 'min', setNowDate() );
formPhoto.endDate.element.setAttribute( 'min', setNowDate() );

if( formPhoto.btnSubmit.element )
{
    formPhoto.btnSubmit.element.addEventListener( 'click', registration );
}

if( formPhoto.rule.element )
{
    formPhoto.rule.element.addEventListener( 'click', unblockSubmit );
}

if( formPhoto.btnClear.element )
{
    formPhoto.btnClear.element.addEventListener( 'click', blockSubmit );
    formPhoto.btnClear.element.addEventListener( 'click', clearAll );
}

if( document.getElementById( 'form' ) )
{
    document.getElementById( 'form' ).addEventListener( 'input', validationEnteredValue );
}

//===== Event listeners

function registration( event )
{
    delPopUp();

    if ( !checkFields( formPhoto ) || !checkDates( formPhoto ) )
    {
        event.preventDefault();
        return;
    }

    const bill = calcBill( formPhoto );

    if( isNaN( bill ) )
    {
        showPopUp( document.getElementById( 'form' ), 'Помилка! Неможливо розрахувати вартість' );
        event.preventDefault();
        return;
    }

    showPopUp( document.getElementById( 'form' ), 'Вартість бронювання', ` ${bill} грн`, false, true, true );
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

function clearAll()
{
    for( const key in formPhoto )
    {
        if( formPhoto[ key ].element.classList.contains( 'error' ) )
        {
            formPhoto[ key ].element.classList.remove( 'error' );
            formPhoto[ key ].element.nextElementSibling.remove();
        }
    }
    delPopUp();
}

function validationEnteredValue( event )
{
    const target = event.target;
    const property = getProperty( target, formPhoto );
    if( property )
    {
        checkValue( formPhoto, property, target );
    }
}

//===== Others function

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
                               ( startDate < Date.parse( parseStartDay + 'T21:00:00' ) ? 3 * 3600000 : 0);
            partDayStartDate = ( startDate < Date.parse( parseStartDay + 'T21:00:00' ) ? 13 * 3600000 : 0 );
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
    countDay = countDay / ( 24 * 3600000 );
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
    if( ( formPhoto.startDate.element.classList.contains( 'error' ) ||
        formPhoto.endDate.element.classList.contains( 'error' ) ) )
    {
        return false;
    }

    const beginDate = Date.parse( form.startDate.element.value ),
          finishDate = Date.parse( form.endDate.element.value );

    switch( true )
    {
        case( finishDate <= beginDate ):
            showPopUp( document.getElementById( 'form' ), 'Дата і час початку не має бути більшою за дату і час кінця або дорівнювати їй' );
            return false;
        case( finishDate % beginDate < 3600000 ):
            showPopUp( document.getElementById( 'form' ), 'Мінімальне замовлення має бути кратне одній годині' );
            return false;
    }
    return true;
}

function checkFields( form )
{
    for( const key in form )
    {
        if( formPhoto[ key ].element.classList.contains( 'error' ) )
        {
            return false;
        }

        switch( true )
        {
            case( form[ key ].required && !document.getElementById( form[ key ].id ) ):
                showPopUp( document.getElementById( 'form' ), 'Помилка! Оновіть сторінку' );
                return false;
            case( form[ key ].element.type !== form[ key ].type ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Не коректний тип поля, оновіть сторінку' );
                return false;
            case( form[ key ].required && !form[ key ].element.value ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Заповніть обов\'язкове поле' );
                return false;
            case( form[ key ].required && form[ key ].regexp && !form[ key ].regexp.test( form[ key ].element.value ) ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Не коректне значення' );
                return false;
        }
    }
    return true;
}

function checkValue( form, key, target )
{
    const value = target.value;

    if( target.classList.contains( 'error' ) &&
        target.nextElementSibling.getAttribute( 'id' ) === 'error-message' )
    {
        target.classList.remove( 'error' );
        target.nextElementSibling.remove();
    }

    switch ( true )
    {
        case( target.type !== form[ key ].type ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Не коректний тип поля, оновіть сторінку' );
            return false;
        case( form[ key ].required && value.length === 0 ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Заповніть обов\'язкове поле' );
            return false;
        case( value.length && form[ key ].regexp && !form[ key ].regexp.test( value ) ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Не коректне значення' );
            return false;
        case( value.length && form[ key ].max && value.length > form[ key ].max ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Завелика кількість символів' );
            return false;
        case( value.length && form[ key ].min && value.length < form[ key ].min ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Замала кількість символів' );
            return false;
        case( ( key === 'startDate' || key === 'endDate' ) && Math.trunc( Date.parse( value ) / 3600000 ) < Math.trunc( Date.now() / 3600000 ) ):
            target.classList.add( 'error' );
            showErrorMessage( target, 'Дата і час не має бути раніше поточної години' );
            return false;
    }
    return true;
}

function getProperty( target, form )
{
    for( const key in form )
    {
        if( target.getAttribute( 'id' ) === form[ key ].id )
        {
            return key;
        }
    }
    return null;
}

function showErrorMessage( parent, text )
{
    const div = document.createElement( 'div' );
    const p = document.createElement( 'p' );
    p.append( document.createTextNode( text ) );
    div.append( p );
    div.setAttribute( 'id', 'error-message' );
    parent.after( div );
}

function showPopUp( parent, text, value = '', ok = true, cansel = false, submit = false )
{
    const div = document.createElement( 'div' );
    const p = document.createElement( 'p' );
    p.append( document.createTextNode( text + ' ' + value ) );
    div.append( p );
    div.setAttribute('id', 'popup' );

    function createButton( text, event )
    {
        const btn = document.createElement( 'button' );
        btn.append( document.createTextNode( text ) );
        btn.setAttribute( 'id', 'btn-button' );
        btn.addEventListener( 'click', event );
        return btn;
    }

    switch( true )
    {
        case( cansel && submit ):
            div.append( createButton( 'Скасувати', () => div.remove() ) );
            div.append( createButton( 'Замовити', () => document.getElementById( 'form' ).submit() ) );
            break;
        case( ok ):
            div.append( createButton( 'Зрозуміло', () => div.remove() ) );
            break;
    }
    parent.append( div );
}

function delPopUp()
{
    if( document.getElementById( 'popup' ) )
    {
        document.getElementById( 'popup' ).remove();
    }
}

function setNowDate()
{
    const nowDate = new Date( Date.now() ),
        year = nowDate.getFullYear(),
        month = ( nowDate.getMonth() < 10 ) ? `0${ nowDate.getMonth() }` : nowDate.getMonth(),
        day = ( nowDate.getDate() < 10 ) ? `0${ nowDate.getDate() }` : nowDate.getDate();
    return `${ year }-${ month }-${ day }T00:00`;
}
