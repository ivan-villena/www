// WINDOW
'use strict';

// Numero : separador + operador + entero + decimal + rango
class api_num {  

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('num',$ide,'dat');

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
    
    return $_;
  }
  
  static val( $dat, $tot ){

    let $_ = $dat;

    if( !!$tot ){

      $_ = api_tex.val_agr( $_, $tot, "0" );
    }
    // parse-int o parse-float
    else{
      
      $_ = Number($dat);
    }
    return $_;
  }
  static val_tip( $dat ){

    let $_ = false;

    if( Number($dat) !== NaN ){

      $_ = /\./.test( String($dat) ) ? 'dec' : 'int' ;
    }

    return $_;
  }  
  static val_int( $dat ){

    if( typeof($dat) == 'string' ) $dat = parseInt($dat);

    return $dat.toFixed(0);
  }
  static val_dec( $dat, $val = 2 ){

    if( typeof($dat) == 'string' ) $dat = parseFloat($dat);

    return $dat.toFixed($val);
  }
  static val_ran( $dat, $max = 1, $min = 1 ){
    let $_ = $dat;

    if( typeof($_) == 'string' ) $_ = api_num.val($_);

    while( $_ > $max ){ $_ -= $max; }

    while( $_ < $min ){ $_ += $max; }

    return $_;
  }

  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }
  static var_act( $dat ){

    let $={};

    $.val = api_num.val($dat.value);

    // excluyo bits
    if( $dat.type != 'text' ){

      // valido minimos y máximos
      if( ( $.min = api_num.val($dat.min) ) && $dat.value && $.val < $.min ) $dat.value = $.val = $.min;    

      if( ( $.max = api_num.val($dat.max) ) && $dat.value && $.val > $.max ) $dat.value = $.val = $.max;

      // relleno con ceros
      if( $dat.getAttribute('num_pad') && ( $.num_cue = $dat.maxlength ) ) $.num_pad = api_num.val($.val,$.num_cue);

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