// WINDOW
'use strict';

// Archivo : fichero + texto + imagen + audio + video + app + ...tipos
class arc {  

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }
  // getter
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

  
  // imagen : .app_ima.$ide
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    if( $dat[2] !== undefined ){
      $.ele = !!($dat[3]) ? $dat[3] : {};      
      $_ = dat.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $.ele);
    }
    else{
      $ele = !!$dat[1] ? $dat[1] : {};
      $.tip = typeof($dat = $dat[0]);
      // por estilos : bkg
      if( $.tip == 'object' ){
        $ele = ele.val_jun( $dat, $ele );
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `img/${$dat}`;
        ele.css( $ele, ele.css_fon($.dir,{'tip':$.tip}) );
      }
      // etiqueta
      $.eti = 'span';
      if( !!$ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }// codifico botones
      if( $.eti == 'button' && !$ele['type'] ) $ele['type'] = "button";
      // aseguro identificador
      ele.cla($ele,`ima`,'ini');
      // contenido 
      $.htm = '';
      if( !!($ele['htm']) ){
        ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
        delete($ele['htm']);
      }
      $_ = `<${$.eti}${ele.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
  static ima_lis( $arc, $pad ){
    let $={
      'lis':[]
    };  
    lis.val($arc).forEach( $_arc => {
      if( typeof($_arc)=='object' ){
        $.ima = document.createElement('img');
        $.ima.src = URL.createObjectURL($_arc);
        $.lis.push($.ima);
      }
    });
    if( !!$pad && $pad.nodeName ){
      ele.val_eli($pad);
      ele.val_agr($.lis,$pad);
    }
    return $;
  }

}