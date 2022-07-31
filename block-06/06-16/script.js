//===== main function
( async () =>
{
    const citiesCSV = await loadFile( "cities.csv", "text" );
    const citiesArr = citiesCSV ? parseCSV( citiesCSV ) : null;
    const countriesJSON = citiesArr ? await loadFile( "countries.json", "json" ) : null;
    if( !citiesArr || !countriesJSON )
    {
        return;
    }
    showListCountries( countriesJSON );
    showTableCities( citiesArr );
} )();

//=====
async function loadFile( url, typeResponse )
{
    const response = await fetch( url );
    if( response.ok )
    {
        return await response[ typeResponse ]();
    }
    else
    {
        console.error( `Помилка, дані не завантажено: ${response.status}: ${response.statusText}` );
        return null;
    }
}

function showListCountries( countries )
{
    const form = document.createElement( "form" );
    const label = document.createElement( "label" );
    const labelText = document.createTextNode( "Країни" );
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

function showTableCities( arrCities )
{
    const inputCountries = document.getElementById( "country" );
    if( inputCountries )
    {
        inputCountries.addEventListener( "change", ( event ) =>
        {
            const country = event.target.value;
            const citiesOfCountry = arrCities.filter( ( item ) => item.country === country ).
                                              sort( ( a, b ) => {
                                                  switch( true )
                                                  {
                                                      case( a.city > b.city ): return 1;
                                                      case( a.city < b.city ): return -1;
                                                      default: return 0;
                                                  }
                                              } ).
                                              map( ( item ) => {
                                                  item.city = capitalize( item.city );
                                                  return item;
                                              } );
            showTable( citiesOfCountry );
        } );
    }
}

function showTable( cities )
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
    const trHeader = document.createElement( "tr" );
    const headerTable = {
        city: document.createTextNode( "Назва" ),
        coordinates: document.createTextNode( "Координати" ),
        population: document.createTextNode( "Населення" )
    };

    //header table
    for( const key in headerTable )
    {
        const thHeader = document.createElement( "th" );
        thHeader.append( headerTable[ key ] );
        if( key === 'coordinates' ) thHeader.setAttribute( "colspan", "2" );
        if( key === 'city' || key === 'population' )
        {
            addButtonSort( thHeader, cities, key );
        }
        trHeader.append( thHeader );
    }

    table.append( trHeader );

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

function addButtonSort( parentElement, arrData, keySort )
{
    const asc = document.createElement( "button" ),
          desc = document.createElement( "button" );
    asc.append( document.createTextNode( "<" ) );
    desc.append( document.createTextNode( ">" ) );
    asc.setAttribute( "type", "button" );
    desc.setAttribute( "type", "button" );
    parentElement.append( asc );
    parentElement.append( desc );

    function sortASC( item1, item2 )
    {
        switch( true )
        {
            case( item1[ keySort ] > item2[ keySort ] ): return 1;
            case( item1[ keySort ] < item2[ keySort ] ): return -1;
            default: return 0;
        }
    }

    asc.addEventListener( 'click', () => {
        arrData.sort( sortASC );
        showTable( arrData );
    } );

    desc.addEventListener( 'click', () => {
        arrData.sort( sortASC ).reverse();
        showTable( arrData );
    } );
}
//===== parseCSV (6.9)

function arrToObj( arr )
{
    return {
        "city" : arr[ 0 ].trim(),
        "latitude" : parseFloat( arr[ 1 ] ),
        "longitude" : parseFloat( arr[ 2 ] ),
        "country" : arr[ 3 ].trim(),
        "population" : parseInt( arr[ 4 ] )
    };
}

function validationObj( obj )
{
    for( let key in obj )
    {
        switch( true )
        {
            case( ( key === 'latitude' ||
                    key === 'longitude' ||
                    key === 'population' ) &&
                    isNaN( obj[ key ] ) ):
                return false;
            case( !obj[ key ] ):
                return false;
        }
    }
    return true;
}

function parseCSV( str )
{
    const resultArray = [];
    const strToArr = str.split( '\n' );

    for ( let i = 0; i < strToArr.length; i++ )
    {
        if( strToArr[ i ].length < 10 )
        {
            continue;
        }
        const innerArr = strToArr[ i ].split( ',' );
        if( innerArr.length < 5 )
        {
            continue;
        }
        const obj = arrToObj( innerArr );
        if( !validationObj( obj ) )
        {
            continue;
        }
        resultArray.push( obj );
    }

    if( !resultArray.length )
    {
        return null;
    }
    return resultArray;
}

//===== capitalize (6.7)

const showError = () => console.error( 'Очікую рядок або масив рядків!' );
const checkOnHardString = ( str ) => ( str.indexOf( '-' ) > 0  || str.indexOf( ' ' ) > 0 );
const getSeparator = ( str ) => str.indexOf( '-' ) > 0 ? '-' : ' ';
const splitString = ( str, separator ) => str.split( separator );
const joinString = ( arr, separator ) => arr.join( separator );

function Error()
{
    showError();
    return null;
}

function checkOnExeption( item, index, arr )
{
    if ( index > 0 && index < arr.length - 1 &&
        ( item.length <= 3 || item === 'upon' ) )
    {
        item = item.toLowerCase();
    }

    if( item === 'L\'haÿ' || item === 'D\'olonne' || item === 'D\'apt' )
    {
        if( index > 0 )
        {
            item = item.substring( 0, 2 ).toLowerCase() + modifyString( item.substring( 2 ) );
        }
        else
        {
            item = item.substring( 0, 2 ) + modifyString( item.substring( 2 ) );
        }
    }

    if( index > 0 && item === 'sen' )
    {
        item = modifyString( item );
    }

    return item;
}

function modifyHardString( str, separator )
{
    let arrayWords = splitString( str, separator );
    let modifyArrayWords = modifyArrayStrings( arrayWords ).map( checkOnExeption );
    return joinString( modifyArrayWords, separator );
}

function modifyNormalString( str )
{
    let firstLetter = str[ 0 ].toUpperCase();
    let subString = str.substring( 1 ).toLowerCase();
    return firstLetter + subString;
}

function modifyString( str )
{
    if( typeof( str ) !== 'string' || !str.length )
    {
        return Error();
    }
    if( checkOnHardString( str ) )
    {
        return modifyHardString( str, getSeparator( str ) );
    }
    return modifyNormalString( str );
}

function modifyArrayStrings( array, inc = 0 )
{
    if( inc < array.length )
    {
        if ( typeof( array[ inc ] ) === 'object' && array[ inc ] !== null && array[ inc ].length > 0 )
        {
            array[ inc ] = modifyArrayStrings( array[ inc ] , 0 );
        }
        else
        {
            array[ inc ] =  modifyString( array[ inc ] );
        }
        modifyArrayStrings( array, ++inc );
    }
    return array;
}

function capitalize( param )
{
    switch( true )
    {
        case( typeof( param ) === 'string' ):
            return modifyString( param );

        case( typeof( param ) === 'object' && param !== null && param.length > 0 ):
            return modifyArrayStrings( param );

        default:
            return Error();
    }
}