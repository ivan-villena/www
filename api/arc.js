// WINDOW
'use strict';

// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class api_arc {  

  constructor(){      
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_arc || $api_arc[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_arc[$est];

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

  static dat( $val ){ 
    let $ ={},
        $_=[];
    $._tip={ 'image':'ima', 'audio':'mus', 'video':'vid', 'text' :'tex' };
    for( let $i=0, $arc ; $arc = $val[$i] ; $i++ ){
      $.nom = $arc.name;
      $.val = $.nom.split('.');
      $.dia = new Date($arc.lastModified);
      $_.push({
        'obj':$arc,
        'ide':$.val[0].toLowerCase(),
        'ext':$.val[1].toLowerCase(),
        'tam':$arc.size,// convertir a rec.bit - KB, MB, ... -
        'mod':$.dia.toLocaleDateString(),
        'tip':$._tip[$arc.type.split('/')[0]]
      });
    }
    return $_;
  }

  static url( $val, $tip ){

    let $_ = `${SYS_NAV}${ Array.isArray($val) ? $val.join('/') : $val }`;

    if( $tip === undefined ){
      window.location.href = $_;
    }
    else{

    }
    return $_;
  }
  static url_cod( $val, $tip ){
    let $_ = false;
    if( typeof($val)=='string' ){
      $_ = ( $tip == 'dec' ) ? decodeURI($val) : encodeURI($val);
    }
    return $_;
  }

}