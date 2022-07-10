//main function
( async () => {

    const countriesJSON = await loadFile( "countries.json", "json" );
    const citiesCSV = await loadFile( "cities.csv", "text" );
    const citiesArr = parseCSV( citiesCSV );

    if( !countriesJSON || !citiesCSV )
    {
        return;
    }
    showCountries( countriesJSON );
    const inputCountries = document.getElementById( "country" );
    if( inputCountries )
    {
        inputCountries.addEventListener( "change", ( event ) => {
            const country = event.target.value;
            const citiesOfCountry =
                citiesArr.filter( ( item ) => item.country === country ).
                          sort( ( a, b ) => {
                              if( a.city > b.city ) return 1;
                              if( a.city < b.city ) return -1;
                              return 0;
                          } );
            // console.log( citiesOfCountry );
            showCities( citiesOfCountry );
        } );
    }
} )();

// other function
async function loadFile( url, typeResponse )
{
    let response = await fetch( url );
    if( response.ok )
    {
        return await response[ typeResponse ]();
    }
    else
    {
        console.error( `Помилка, данні не завантажено: ${response.status}: ${response.statusText}` );
        return null;
    }
}

function showCountries( countries )
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

    countries.forEach( ( item ) => {
        const option = document.createElement( "option" );
        option.setAttribute( "value", item.Name );
        datalist.append( option );
    } );

    form.append( datalist );
    document.body.prepend( form );
}

function showCities( cities )
{
    //remove table
    if( document.getElementById( "table" ) )
    {
        document.getElementById( "table" ).remove();
    }

    //check cities
    if( !cities.length )
    {
        return;
    }

    const form = document.getElementById( "form" );
    const table = document.createElement( "table" );
    table.setAttribute( "id", "table" );
    const trHader = document.createElement( "tr" );
    const headerTable = {
        cityName: document.createTextNode( "Назва" ),
        lat: document.createTextNode( "Широта" ),
        lon: document.createTextNode( "Довгота" ),
        population: document.createTextNode( "Населення" )
    };

    //header table
    for( const key in headerTable )
    {
        const thHeader = document.createElement( "th" );
        thHeader.append( headerTable[ key ] );
        trHader.append( thHeader );
    }
    table.append( trHader );

    //body table
    cities.forEach( ( item ) => {
        const tr = document.createElement( "tr" );
        for ( const key in item ) {
            if( key !== "country" )
            {
                const td = document.createElement( "td" );
                td.append( document.createTextNode( item[ key ] ) );
                tr.append( td );
            }
        }
        table.append( tr );
    } );

    form.append( table );
}

//===== parseCSV

function arrToObj( arr )
{
    return {
        "city" : arr[0].trim(),
        "latitude" : parseFloat(arr[1]),
        "longitude" : parseFloat(arr[2]),
        "country" : arr[3].trim(),
        "population" : parseInt(arr[4])
    };
}

function validationObj( obj )
{
    for( let key in obj )
    {
        if( !obj[key] )
        {
            return false;
        }
    }
    return true;
}

function parseCSV( str )
{
    let resultArray = [];
    let arr = str.split( '\n' );
    for ( let i = 0; i < arr.length; i++ )
    {
        if( arr[i].length < 10 )
        {
            continue;
        }
        let innerArr = arr[i].split( ',' );
        let obj = arrToObj( innerArr );
        if( !validationObj( obj ) )
        {
            continue;
        }
        resultArray.push( obj );
    }
    return resultArray;
}