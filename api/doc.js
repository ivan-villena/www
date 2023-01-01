// window
'use strict';

class api_doc {
  
  _bot = 'body > .doc_bot';
  _win = 'body > .doc_win';
  _pan = 'body > .doc_pan';
  _sec = 'body > .doc_sec';
  _bar = 'body > .doc_bar';
  _pie = 'body > .doc_pie';
  _var = {};

  constructor(){
    // cargo elementos
    for( const $atr in this ){
      if( typeof(this[$atr]) == 'string' ) this[$atr] = document.querySelector(this[$atr]);
    }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_doc || $api_doc[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_doc[$est];

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

  // pantalla modal : .doc_win > article > header + section
  static win( $ide, $ope ){
    let $ = {};    
    // botones
    if( $ide.nodeName && $ide.classList.contains('fig_ico') ){
      // cierro pantalla
      $.art = $ide.parentElement.parentElement.parentElement;
      if( $ide.dataset.ope == 'fin' || $ide.dataset.ope == 'pre' ){        
        $.art.classList.add(DIS_OCU);
        // si es una pantalla por posicion, elimino el articulo
        if( $.art.dataset.pos ){
          if( $ide.dataset.ope == 'pre' ){
            $.art.parentElement.removeChild($.art);
          }
          else if( $ide.dataset.ope == 'fin' ){
            $.art.parentElement.querySelectorAll(`article.${$.art.classList[0]}[data-pos]`).forEach( $art => {
              $art.parentElement.removeChild($art);
            });
          }
        }
      }
    }// articulos
    else{
      $.art = typeof($ide) == 'string' ?  $api_doc._win.querySelector(`article.ide-${$ide}`) : $ide;
      $.htm = $.art.querySelector(`div:nth-child(2)`);
      // actualizo contenido
      if( !!$ope ){
        $.opc = $ope.opc ? api_lis.val_ite($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $api_doc._win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $api_doc._win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .doc_ope');
            $.ope.insertBefore( api_ele.val_cod( api_fig.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"api_doc.win(this)" 
            })), $.ope.querySelector('.fig_ico.ide-dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .fig_ico:first-of-type`) ){
          $.ico.innerHTML = '';
          if( $ope.ico !== undefined ){
            if( typeof($ope.ico) == 'string' ) $ope.ico = api_fig.ico($ope.ico,{ class:'mar_hor-1' });
            api_ele.val_mod($ope.ico,$.ico);
          }
        }
        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){
          $.tit.innerHTML = ( $ope.cab !== undefined ) ? api_tex.let($ope.cab) : '';
        }
        // contenido
        api_ele.val_eli($.htm);
        if( $ope.htm !== undefined ) api_ele.val_agr($ope.htm,$.htm);
      }
      // muestro por valor
      $.art.classList.contains(DIS_OCU) && $.art.classList.remove(DIS_OCU);
      $.htm.scroll(0,0);// scroll
    }
    // pantalla de fondo
    if( $api_doc._win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){
      $api_doc._win.classList.contains(DIS_OCU) && $api_doc._win.classList.remove(DIS_OCU);
    }
    else if( !$api_doc._win.classList.contains(DIS_OCU) ){
      $api_doc._win.classList.add(DIS_OCU);
    }    
  }

  // panel principal : aside.doc_pan
  static pan( $ide ){

    if( $ide && $ide.nodeName ) $ide = $ide.classList[0].split('-')[1];

    $api_doc._pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$ide}, .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
    $api_doc._pan.querySelectorAll(`:is(nav,article).ide-${$ide}`).forEach( $e => $e.classList.toggle(DIS_OCU) );

    // aculto-muestro contenedor
    if( $api_doc._pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){
      $api_doc._pan.classList.contains(DIS_OCU) && $api_doc._pan.classList.remove(DIS_OCU);
    }
    else if( !$api_doc._pan.classList.contains(DIS_OCU) ){
      $api_doc._pan.classList.add(DIS_OCU);
    }    
  }

  // seccion central : main.doc_sec > article
  static sec( $ide ){
    $api_doc._sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $api_doc._sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $api_doc._sec.scroll(0, 0);
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

      if( $api_doc._var = api_ele.val_ver($dat,{'eti':"form"}) ){

        $.lis = !!$api_doc._var.nextElementSibling ? $api_doc._var.nextElementSibling : $api_doc._var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.doc_ite > .fig_ico.ide-val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  
}