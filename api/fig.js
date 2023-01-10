// WINDOW
'use strict';

class api_fig {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('fig',$ide,'dat');

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

  // controladres
  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

  /* Icono : .fig_ico.ide-$ide */
  static ico( $ide, $ele = {} ){
    let $_="<span class='fig_ico'></span>", $ = {};
    $.fig_ico = api_fig._('ico');
    if( !!($.fig_ico[$ide]) ){
      $.eti = 'span';
      if( $ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }      
      if( $.eti == 'button' && !($ele['type']) ) $ele['type'] = "button"; 
      $_ = `
      <${$.eti}${api_ele.atr(api_ele.cla($ele,`fig_ico ide-${$ide}`,'ini'))}>
        ${$.fig_ico[$ide].val}
      </${$.eti}>`;
    }
    return $_;
  }      

  /* imagen : .app_ima.$ide */
  static ima( ...$dat ){  

    let $_="", $={}, $ele={};
    
    if( $dat[2] !== undefined ){
      $.ele = !!($dat[3]) ? $dat[3] : {};      
      $_ = api_est.val('ima', `${$dat[0]}.${$dat[1]}`, $dat[2], $.ele);
    }
    else{
      $ele = !!$dat[1] ? $dat[1] : {};
      $.tip = typeof($dat = $dat[0]);
      // por estilos : bkg
      if( $.tip == 'object' ){
        $ele = api_ele.val_jun( $dat, $ele );
      }
      // por directorio : localhost/_/esq/ima/...
      else if( $.tip == 'string' ){    
        $.ima = $dat.split('.');
        $dat = $.ima[0];
        $.tip = !!$.ima[1] ? $.ima[1] : 'png';
        $.dir = `img/${$dat}`;
        api_ele.css( $ele, api_ele.css_fon($.dir,{'tip':$.tip}) );
      }
      // etiqueta
      $.eti = 'span';
      if( !!$ele['eti'] ){
        $.eti = $ele['eti'];
        delete($ele['eti']);
      }// codifico botones
      if( $.eti == 'button' && !$ele['type'] ) $ele['type'] = "button";
      // aseguro identificador
      api_ele.cla($ele,`fig_ima`,'ini');
      // contenido 
      $.htm = '';
      if( !!($ele['htm']) ){
        api_ele.cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $.htm = $ele['htm'];
        delete($ele['htm']);
      }
      $_ = `<${$.eti}${api_ele.atr($ele)}>${$.htm}</${$.eti}>`;
    }
    return $_;
  }
  static ima_lis( $arc, $pad ){
    let $={
      'lis':[]
    };  
    api_lis.val_ite($arc).forEach( $_arc => {
      if( typeof($_arc)=='object' ){
        $.ima = document.createElement('img');
        $.ima.src = URL.createObjectURL($_arc);
        $.lis.push($.ima);
      }
    });
    if( !!$pad && $pad.nodeName ){
      $dom.eli($pad);
      $dom.agr($.lis,$pad);
    }
    return $;
  }  

}