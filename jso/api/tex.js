// WINDOW
'use strict';

// Texto : caracter + letra + oracion + parrafo
class _tex {  

  static cod( $dat ){
    // $& significa toda la cadena coincidente
    return $dat.toString().replace(/[.*+\-?^${}()|[\]\\]/g,'\\$&');
  }

  static dec( $dat, $opc = '' ){
    return new RegExp( $dat, $opc );
  }

  static agr( $dat, $tot = 0, $val = '', $lad = 'izq' ){
    let $_ = '';
    if( $lad=='izq' ){
      $_ = $dat.toString().padStart($tot,$val);
    }else if( $lad=='der' ){
      $_ = $dat.toString().padEnd($tot,$val);
    }else{
      $_ = $dat.toString();
    }
    return $_;
  }
  
  static fun( $ide, $val, ...$opc ){

    let $_ = "";

    switch( $ide ){
      case '': // Devuelve una cadena creada utilizando la secuencia de valores Unicode especificada.
        String.fromCharCode( num1, ...numN );
        break;
      case '': // Devuelve una cadena creada utilizando la secuencia de puntos de código especificada.
        String.fromCodePoint( num1, ...numN );
        break;
      case '': // Devuelve una cadena creada a partir de una plantilla literal sin formato.
        String.raw();
        break;
      case '': // Refleja la length de la cadena. Solo lectura.
        $_ = $val.length;
        break;
      case '': // Devuelve el caracter (exactamente una unidad de código UTF-16) en el index especificado.
        $_ = $val.charAt(index);
        break;
      case '': // Devuelve un número que es el valor de la unidad de código UTF-16 en el index dado.
        $_ = $val.charCodeAt( index );        
        break;
      case '': // Devuelve un número entero no negativo que es el valor del punto de código codificado en UTF-16 que comienza en la pos especificada.
        $_ = $val.codePointAt( pos );
        break;
      case '': // Combina el texto de dos (o más) cadenas y devuelve una nueva cadena.
        $_ = $val.concat( str, ...strN );
        break;
      case '': // Determina si la cadena de la llamada contiene searchString.
        $_ = $val.includes( searchString, position );
        break;
      case '': // Determina si una cadena termina con los caracteres de la cadena searchString.
        $_ = $val.endsWith( search, length );
        break;
      case '': // Devuelve el índice dentro del objeto String llamador de la primera aparición de searchValue, o -1 si no lo encontró.
        $_ = $val.indexOf( search, from );
        break;
      case '': // Devuelve el índice dentro del objeto String llamador de la última aparición de searchValue, o -1 si no lo encontró.
        $_ = $val.lastIndexOf( search, from );
        break;
      case '': // Devuelve un número que indica si la cadena de referencia compareString viene antes, después o es equivalente a la cadena dada en el orden de clasificación.
        $_ = $val.localeCompare( compare, locales, options );
        break;
      case '': // Se utiliza para hacer coincidir la expresión regular regexp con una cadena.
        $_ = $val.match( regexp );
        break;
      case '': // Devuelve un iterador de todas las coincidencias de regexp.
        $_ = $val.matchAll( regexp );
        break;
      case '': // Devuelve la forma de normalización Unicode del valor de la cadena llamada.
        $_ = $val.normalize( form ); 
        break;
      case '': // Rellena la cadena actual desde el final con una cadena dada y devuelve una nueva cadena de longitud targetLength.
        $_ = $val.padEnd( targetLength, padString );
        break;
      case '': // Rellena la cadena actual desde el principio con una determinada cadena y devuelve una nueva cadena de longitud targetLength.
        $_ = $val.padStart( targetLength, padString );
        break;
      case '': // Devuelve una cadena que consta de los elementos del objeto repetidos count veces.
        $_ = $val.repeat( count );         
        break;
      case '': // Se usa para reemplazar ocurrencias de searchFor usando replaceWith. searchFor puede ser una cadena o expresión regular, y replaceWith puede ser una cadena o función.        
        $_ = $val.replace( searchFor, replaceWith );
        break;
      case '': // Se utiliza para reemplazar todas las apariciones de searchFor usando replaceWith. searchFor puede ser una cadena o expresión regular, y replaceWith puede ser una cadena o función.
        $_ = $val.replaceAll(searchFor, replaceWith);        
        break;
      case '': // Busca una coincidencia entre una expresión regular regexp y la cadena llamadora.
        $_ = $val.search( regexp );
        break;
      case '': // Extrae una sección de una cadena y devuelve una nueva cadena.
        $_ = $val.slice( start, end );
        break;
      case '': // Devuelve un arreglo de cadenas pobladas al dividir la cadena llamadora en las ocurrencias de la subcadena sep.
        $_ = $val.split( sep, limit );
        break;        
      case '': // Determina si la cadena llamadora comienza con los caracteres de la cadena searchString.
        $_ = $val.startsWith( search, position );
        break;        
      case '': // Devuelve los caracteres en una cadena que comienza en la ubicación especificada hasta el número especificado de caracteres.
        $_ = $val.substr( from, length );
        break;
      case '': // Devuelve una nueva cadena que contiene caracteres de la cadena llamadora de (o entre) el índice (o indeces) especificados.
        $_ = $val.substring( start, end );
        break;
      case '': // Devuelve una cadena que representa el objeto especificado. Redefine el método toString().
        $_ = $val.toString();
        break;        
      case '': // Los caracteres dentro de una cadena se convierten a minúsculas respetando la configuración regional actual.
        $_ = $val.toLocaleLowerCase()
        break;
      case '': // Devuelve el valor de la cadena llamadora convertido a minúsculas.
        $_ = $val.toLowerCase();
        break;
      case '': // Devuelve el valor de la cadena llamadora convertido a mayúsculas.
        $_ = $val.toUpperCase();
        break;
      case '': // Recorta los espacios en blanco desde el principio y el final de la cadena. Parte del estándar ;
        $_ = $val.trim()
        break;
      case '': // Recorta los espacios en blanco desde el principio de la cadena.
        $_ = $val.trimStart();
        break;
      case '': // Recorta los espacios en blanco del final de la cadena.
        $_ = $val.trimEnd();
        break;
      case '': // Devuelve el valor primitivo del objeto especificado. Redefine el método valueOf().
        $_ = $val.valueOf(); 
        break;
    }

    return $_;
  } 

}