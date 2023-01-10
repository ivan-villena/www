// window
'use strict';

class api_doc {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = sis_dat.est('doc',$ide,'dat');

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

  // contenedor : bloque + visible/oculto  
  static val( $dat, $ope ){
    let $ = {};
    // elementos del documento
    if( !$ope ){
      $.ite = $dat.parentElement;
      if( 
        ( $.bot = $.ite.querySelector('.fig_ico.ide-val_tog') ) 
        && ( $.sec = $.ite.nextElementSibling )
      ){        
      
        if( $.bot.classList.contains('ocu') ){
          $.bot.classList.remove('ocu');
          $.sec.classList.remove(DIS_OCU);
        }
        else{
          $.bot.classList.add('ocu');
          $.sec.classList.add(DIS_OCU);
        }
      }
    }
    // por opciones
    else if( ['tod','nad'].includes($ope) ){

      if( $dom.app.var = $dom.ver($dat,{'eti':"form"}) ){

        $.lis = !!$dom.app.var.nextElementSibling ? $dom.app.var.nextElementSibling : $dom.app.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.doc_ite > .fig_ico.ide-val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  // navegacion de contenido : pestaña-barra-operador
  static nav( $dat, $ope, ...$opc ){
    let $={};
    // capturo contenedor
    $.nav = $dat.parentElement;
    $.tip = $.nav.classList[0];
    $.tip_ver = false;
    // capturo contenido
    $.lis = $.nav.nextElementSibling;
    // con toggles
    $.val_tog = $opc.includes('tog');
    // elimino fondo por seleccion anterior
    if( $.sel_ant = $.nav.querySelector(`a.${FON_SEL}`) ){

      if( !$.val_tog || $.sel_ant != $dat ) $.sel_ant.classList.remove(FON_SEL);
    }
    // contenido
    if( $ope ){
      // recorro items
      api_lis.val_cod( $.lis.children ).forEach( $e => {
        // coincide con el seleccionado
        if( $e.classList.contains(`ide-${$ope}`) ){          
          // hago toogles
          if( $.val_tog ){
            $e.classList.toggle(DIS_OCU);
            $dat.classList.toggle(FON_SEL);
          }
          // muestro y selecciono
          else if( $e.classList.contains(DIS_OCU) ){
            $e.classList.remove(DIS_OCU);
            $dat.classList.add(FON_SEL);
          }
          $.tip_ver = !$e.classList.contains(DIS_OCU);

        }// oculto los no coincidentes
        else if( !$e.classList.contains(DIS_OCU) ){ 
          $e.classList.add(DIS_OCU); 
        }
      });
    }
    // oculto o muestro contenedor
    if( $.tip != 'pes' ){
      if( $.tip_ver ){
        $.lis.classList.contains(DIS_OCU) && $.lis.classList.remove(DIS_OCU);
      }else{
        !$.lis.classList.contains(DIS_OCU) && $.lis.classList.add(DIS_OCU);
      }
    }  
  }  

  // Variable : form > .dov_var > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $dom.app.var = $dom.ver($dat,{'eti':'form'});
      $.var_ide = $dat.getAttribute('name');
    }
    else{
      switch( $tip ){
      case 'mar':
        if( $ope ){
          $dat.parentElement.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e.classList.remove($ope) );
          $dat.classList.add($ope);
        }
        break;
      case 'tog':
        if( $ope ){
          $dat.parentElement.querySelectorAll(`.${$ope}`).forEach( $e => $e != $dat && $e.classList.remove($ope) );
          $dat.classList.toggle($ope);
        }
        break;
      }
    }
    return $;
  }// toggles por : form > fieldsets > ...
  static var_tog( $ide ){
    api_lis.val_cod( $dom.ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }
}