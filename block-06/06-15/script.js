//===== XMLHttpRequest
// let xhr = new XMLHttpRequest();
// xhr.open( "GET", "countries.json" );
// xhr.responseType = "json";
// xhr.send();
//
// xhr.onload = function()
// {
//   if( xhr.status !== 200)
//   {
//     console.error( `Помилка, данні не завантажено: ${xhr.status}: ${xhr.statusText}` );
//   }
//   else
//   {
//       showData( xhr.response );
//   }
// };
//
// xhr.onerror = function()
// {
//   console.error( "Не вдалося виконати запит до сервера" );
// };

//===== jQuery
// $.ajax(
//     {
//         'method': 'GET',
//         'url': 'countries.json',
//         'dataType': 'json',
//         'success': function( jsonContents )
//         {
//             showData( jsonContents );
//         },
//         'error': function( jqXHR, textStatus, errorThrown )
//         {
//             console.error( textStatus );
//         },
//     }
// );

//===== Fetch
async function loadJSON( url )
{
    let response = await fetch( url );
    if( response.ok )
    {
        let bodyResponse = await response.json();
        showData( bodyResponse );
    }
    else
    {
        console.error( `Помилка, данні не завантажено: ${response.status}: ${response.statusText}` );
    }
}
loadJSON( 'countries.json' );

//===== Other function
function showData( jsonData )
{
    const form = document.createElement( "form" );
    const label = document.createElement( "label" );
    const labelText = document.createTextNode( "Країни світу" );
    const input = document.createElement( "input" );
    const datalist = document.createElement( "datalist" );

    form.setAttribute( "id", "form" );
    form.setAttribute( "method", "get" );
    form.setAttribute( "action", "#" );
    label.setAttribute( "for", "country" );
    input.setAttribute( "id", "country" );
    input.setAttribute( "list", "countryList" );
    input.setAttribute( "name", "country" );
    datalist.setAttribute( "id", "countryList" );
    label.append( labelText );
    form.append( label );
    form.append( input );

    jsonData.forEach( ( item ) => {
        const option = document.createElement( "option" );
        option.setAttribute( "value", item.Name );
        datalist.append( option );
    } );

    form.append( datalist );
    document.body.prepend( form );
}