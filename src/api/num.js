// WINDOW
'use strict';

// Numero : separador + operador + entero + decimal + rango
class num {  

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_num || $api_num[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_num[$est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        default:        
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];        
          break;
        }        
      }
    }
    else{
      $_ = $_dat ? $_dat : [];
    }
    return $_;
  }
  
  static val( $dat, $tot ){

    let $_ = $dat;

    if( !!$tot ){

      $_ = tex.val_agr( $_, $tot, "0" );
    }
    // parse-int o parse-float
    else{
      
      $_ = Number($dat);
    }
    return $_;
  }
  static tip( $dat ){

    let $_ = false;

    if( Number($dat) !== NaN ){

      $_ = /\./.test( String($dat) ) ? 'dec' : 'int' ;
    }

    return $_;
  }

  static int( $dat ){

    if( typeof($dat) == 'string' ) $dat = parseInt($dat);

    return $dat.toFixed(0);
  }

  static val_dec( $dat, $val = 2 ){

    if( typeof($dat) == 'string' ) $dat = parseFloat($dat);

    return $dat.toFixed($val);
  }

  static ran( $dat, $max = 1, $min = 1 ){
    let $_ = $dat;

    if( typeof($_) == 'string' ) $_ = num.val($_);

    while( $_ > $max ){ $_ -= $max; }

    while( $_ < $min ){ $_ += $max; }

    return $_;
  }
  
  static mat( $ide, $val, ...$opc ){

    switch( $ide ){
    case '': // Constante de Euler, la base de los logaritmos naturales, aproximadamente 2.718.
      $_ = Math.E;
      break;
    case '': // Logaritmo natural de 2, aproximadamente 0.693.
      $_ = Math.LN;
      break;
    case '': // Logaritmo natural de 10, aproximadamente 2.303.
      $_ = Math.LN1;
      break;
    case '': // Logaritmo de E con base 2, aproximadamente 1.443.
      $_ = Math.LOG2;
      break;
    case '': // Logaritmo de E con base 10, aproximadamente 0.434.
      $_ = Math.LOG10;       
      break;
    case '': // Ratio de la circunferencia de un circulo respecto a su diámetro, aproximadamente 3.14159.
      $_ = Math.P;
      break;
    case '': // Raíz cuadrada de 1/2; Equivalentemente, 1 sobre la raíz cuadrada de 2, aproximadamente 0.707.
      $_ = Math.SQRT1_;
      break;
    case '': // Raíz cuadrada de 2, aproximadamente 1.414.
      $_ = Math.SQRT;
      break;
    // Tené en cuenta que las funciones trigonométricas (sin(), cos(), tan(), asin(), acos(), atan(), atan2()) devuelven ángulos en radianes. 
    // Para convertir radianes a grados, dividí por (Math.PI / 180), y multiplicá por esto para convertir a la inversa.
    case '': // Devuelve el valor absoluto de un número. 
      $_ = Math.abs(x);
      break;
    case '': // Devuelve el arco coseno de un número.
      $_ = Math.acos(x);
      break;
    case '': // Devuelve el arco coseno hiperbólico de un número.
      $_ = Math.acosh(x);
      break;
    case '': // Devuelve el arco seno de un número.
      $_ = Math.asin(x);
      break;
    case '': // Devuelve el arco seno hiperbólico de un número.
      $_ = Math.asinh(x);      
      break;
    case '': // Devuelve el arco tangente de un número.
      $_ = Math.atan(x);
      break;
    case '': // Devuelve el arco tangente hiperbólico de un número.
      $_ = Math.atanh(x);    
      break;
    case '': // Devuelve el arco tangente del cuociente de sus argumentos.
      $_ = Math.atan2(y, x);
      break;
    case '': // Devuelve la raíz cúbica de un número.
      $_ = Math.cbrt();
      break;
    case '': // Devuelve el entero más pequeño mayor o igual que un número.
      $_ = Math.ceil(x);
      break;
    case '': // Devuelve el número de ceros iniciales de un entero de 32 bits.
      $_ = Math.clz32(x);
      break;
    case '': // Devuelve el coseno de un número.
      $_ = Math.cos(x);
      break;
    case '': // Devuelve el coseno hiperbólico de un número.
      $_ = Math.cosh();
      break;
    case '': // Devuelve Ex, donde x es el argumento, y E es la constante de Euler (2.718...), la base de los logaritmos naturales.
      $_ = Math.exp();
      break;
    case '': // Devuelve ex - 1.
      $_ = Math.expm1();
      break;
    case '': // Devuelve el mayor entero menor que o igual a un número.
      $_ = Math.floor(x);
      break;
    case '': // Devuelve la representación flotante de precisión simple más cercana de un número.
      $_ = Math.fround(x);
      break;
    case '': // Devuelve la raíz cuadrada de la suma de los cuadrados de sus argumentos.
      $_ = Math.hypot( x, y, ...z );
      break;
    case '': // Devuelve el resultado de una multiplicación de enteros de 32 bits.
      $_ = Math.imul(x, y);
      break;
    case '': // Devuelve el logaritmo natural (log, también ln) de un número.
      $_ = Math.log(x);
      break;
    case '': // Devuelve el logaritmo natural de x + 1 (loge, también ln) de un número.
      $_ = Math.log1p(x);
      break;
    case '': // Devuelve el logaritmo en base 10 de x.
      $_ = Math.log10(x);
      break;
    case '': // Devuelve el logaritmo en base 2 de x.
      $_ = Math.log2(x);
      break;
    case '': // Devuelve el mayor de cero o más números.
      $_ = Math.max( ...x );
      break;
    case '': // Devuelve el más pequeño de cero o más números.
      $_ = Math.min( ...x );
      break;
    case '': // Las devoluciones de base a la potencia de exponente, que es, baseexponent.
      $_ = Math.pow(x, y);
      break;
    case '': // Devuelve un número pseudo-aleatorio entre 0 y 1.
      $_ = Math.random();
      break;
    case '': // Devuelve el valor de un número redondeado al número entero más cercano.
      $_ = Math.round(x);
      break;
    case '': // Devuelve el signo de la x, que indica si x es positivo, negativo o cero.
      $_ = Math.sign(x);
      break;
    case '': // Devuelve el seno de un número.
      $_ = Math.sin(x);
      break;
    case '': // Devuelve el seno hiperbólico de un número.
      $_ = Math.sinh(x);
      break;
    case '': // Devuelve la raíz cuadrada positiva de un número.
      $_ = Math.sqrt(x);
      break;
    case '': // Devuelve la tangente de un número.
      $_ = Math.tan(x);
      break;
    case '': // Devuelve la tangente hiperbólica de un número.
      $_ = Math.tanh(x);
      break;
    case '': // Devuelve la cadena "Math".
      $_ = Math.toSource();
      break;
    case '': // Devuelve la parte entera del número x, la eliminación de los dígitos fraccionarios.
      $_ = Math.trunc(x);
      break;
    }
  }

  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  static var_act( $dat ){

    let $={};

    $.val = num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = num.val($.val,$.num_cue);

      // actualizo valores por rango
      if( $dat.type == 'range' ){

        if( $.val = $dat.parentElement.nextElementSibling ) $.val.innerHTML = $.num_pad ? $.num_pad : $dat.value;
      }
      // por entero o decimales
      else{

        if( $.num_pad ) $dat.value = $.num_pad;
      }      
    }
  }    
}