// WINDOW
'use strict';

// Numero : separador + operador + entero + decimal + rango
class Num {  
  
  /* Contenido */
  static val( $dat, $tot ){
    // devuelvo valor con "00..." o numérico : int | float
    let $_ = $dat;

    if( !!$tot ){

      $_ = Tex.val_agr( $_, $tot, "0" );
    }
    // parse-int o parse-float
    else{
      
      $_ = Number($dat);
    }
    return $_;
  }

  // valido y devuelvo tipo
  static tip( $dat ){

    let $_ = false;

    if( Number($dat) !== NaN ){

      $_ = /\./.test( String($dat) ) ? 'dec' : 'int' ;
    }

    return $_;
  }

  // devuelvo entero : ...mil.num
  static int( $dat ){

    if( typeof($dat) == 'string' ) $dat = parseInt($dat);

    return $dat.toFixed(0);
  }

  // devuelvo decimal : ...num,dec
  static dec( $dat, $val = 2 ){

    if( typeof($dat) == 'string' ) $dat = parseFloat($dat);

    return $dat.toFixed($val);
  }
  
  // reduzco a valor dentro del rango
  static ran( $dat, $max = 1, $min = 1 ){
    let $_ = $dat;

    if( typeof($_) == 'string' ) $_ = Num.val($_);

    while( $_ > $max ){ $_ -= $max; }

    while( $_ < $min ){ $_ += $max; }

    return $_;
  }
}