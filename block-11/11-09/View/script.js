const country = document.getElementById( 'country' );
const cities = document.getElementById( 'citiesList' );
const city = document.getElementById( 'city' );
const form = document.getElementById( 'form' );
if( country )
{
    country.addEventListener( 'change', ( event )=>
    {
        clearTable();
        clearCity();
        clearListCities();
        ( async function()
        {
            let response = await fetch( `http://127.0.0.1/block-11/11-09/ajax.php?country=${event.target.value}` );
            if( response.ok )
            {
                let list = await response.json();
                list.forEach( ( item ) => {
                    const option = document.createElement( 'option' );
                    option.setAttribute( 'value', item );
                    cities.append( option );
                } );
            }
            else
            {
                alert('Помилка HTTP: ' + response.status);
            }
        } )();
    } );
}
if( city )
{
    city.addEventListener( 'change', ( event ) =>
    {
        clearTable();
        ( async function()
        {
            let response = await fetch( `http://127.0.0.1/block-11/11-09/ajax.php?city=${event.target.value}` );
            if( response.ok )
            {
                let info = await response.json();
                const div = document.createElement( 'div' );
                div.setAttribute( 'id', 'info_table' );
                const table = document.createElement( 'table' );
                //header table
                const trHeader = document.createElement( 'tr' );
                const headerTable = {
                    continent: document.createTextNode( 'Континент' ),
                    country: document.createTextNode( 'Країна' ),
                    city: document.createTextNode( 'Місто' ),
                    time_zone: document.createTextNode( 'Часова зона' )
                };
                for( const key in headerTable )
                {
                    const thHeader = document.createElement( 'th' );
                    thHeader.append( headerTable[ key ] );
                    trHeader.append( thHeader );
                }
                //body table
                const tr = document.createElement( 'tr' );
                for ( const item in info )
                {
                    const text = document.createTextNode( info[ item ] );
                    const td = document.createElement( 'td' );
                    td.append( text );
                    tr.append( td );
                }
                table.append( trHeader );
                table.append( tr );
                div.append( table )
                form.after( div );
            }
            else
            {
                alert('Помилка HTTP: ' + response.status);
            }
        } )();
    } );
}
function clearTable()
{
    const element = document.getElementById( 'info_table' );
    if( element )
    {
        element.remove();
    }
}
function clearListCities()
{
    if( cities )
    {
        cities.innerHTML = '';
    }
}
function clearCity()
{
    if( city )
    {
        city.value = '';
    }
}