const regexpName = /^((([а-яіїєґ])+([\`\-])?([а-яіїєґ])+)+)\s((([а-яіїєґ])+([\'\-])?([а-яіїєґ])+)+)$/i,
      regexpEmail = /[A-Z0-9\.\_\%\+\-]+\@[A-Z0-9\-\.]+\.[A-Z]{2,4}/i;

const formData = {
    fullName  : { id: 'fullname',  type: 'text',     name:'Повне імʼя',        required: true,  min: 5,    max: 64,   regexp: regexpName },
    email     : { id: 'email',     type: 'email',    name:'Email',             required: true,  min: 6,    max: 255,  regexp: regexpEmail },
    message   : { id: 'message',   type: 'textarea', name:'Повідомлення',      required: true,  min: 2,    max: 200,  regexp: null },
    rule      : { id: 'agree',     type: 'checkbox', name:'Спам-повідомлення', required: false, min: null, max: null, regexp: null },
    btnSubmit : { id: 'btn-submit', type: 'submit',  name:'Надіслати',         required: false, min: null, max: null, regexp: null },
    setElements() {
        for( const key in this )
        {
            this[ key ].element = document.getElementById( this[ key ].id );
        }
    }
};
Object.defineProperty( formData, "setElements", { enumerable: false } );
formData.setElements();
console.log( formData );

if( formData.btnSubmit.element )
{
    formData.btnSubmit.element.addEventListener( 'click', sendMessage );
}

if( formData.rule.element )
{
    formData.rule.element.addEventListener( 'click', blockUnblockSubmit );
}

if( document.getElementById( 'form' ) )
{
    document.getElementById( 'form' ).addEventListener( 'input', validationEnteredValue );
}

//===== Event listeners

function sendMessage( event )
{
    delPopUp();

    if ( !checkFields( formData ) )
    {
        event.preventDefault();
        return;
    }

    // showPopUp( document.getElementById( 'form' ), 'Надіслати повідомлення?', '', false, true, true );
}

function blockUnblockSubmit()
{
    if( formData.rule.element.checked )
    {
        formData.btnSubmit.element.removeAttribute( 'disabled' );
    }
    else
    {
        formData.btnSubmit.element.setAttribute( 'disabled', 'disabled' );
    }
}

function validationEnteredValue( event )
{
    const target = event.target;
    const property = getProperty( target, formData );
    if( property )
    {
        checkValue( formData, property, target );
    }
}

//===== Others function

function checkFields( form )
{
    let result = true;

    for( const key in form )
    {

        delErrorMessage( form[ key ].element )

        switch( true )
        {
            case( form[ key ].required && !document.getElementById( form[ key ].id ) ):
                showPopUp( document.getElementById( 'form' ), 'Помилка! Оновіть сторінку' );
                return false;
            case( form[ key ].element.type !== form[ key ].type ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Не коректний тип поля, оновіть сторінку' );
                result = false;
                break;
            case( form[ key ].required && !form[ key ].element.value ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Заповніть обов\'язкове поле' );
                result = false;
                break;
            case( form[ key ].required && form[ key ].regexp && !form[ key ].regexp.test( form[ key ].element.value ) ):
                form[ key ].element.classList.add( 'error' );
                showErrorMessage( form[ key ].element, 'Не коректне значення' );
                result = false;
                break;
        }
    }
    return result;
}

function checkValue( form, key, target )
{
    const value = target.value;

    delErrorMessage( target );

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

function delErrorMessage( target )
{
    if( target.classList.contains( 'error' ) &&
        target.nextElementSibling.getAttribute( 'id' ) === 'error-message' )
    {
        target.classList.remove( 'error' );
        target.nextElementSibling.remove();
    }
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
            div.append( createButton( 'Надіслати', () => document.getElementById( 'form' ).submit() ) );
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