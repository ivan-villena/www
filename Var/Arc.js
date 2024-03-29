// WINDOW
'use strict';

// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class Arc {  

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

  /* Enlaces */
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