// window
'use strict';

class doc {

  // botonera
  _bot = 'body > .doc_bot';
  // pantalla modal
  _win = 'body > .doc_win';
  // panel
  _pan = 'body > .doc_pan';
  // principal
  _sec = 'body > .doc_sec';
  // lateral
  _bar = 'body > .doc_bar';
  // barra inferior    
  _pie = 'body > .doc_pie';
  // variable
  _var = null;

  constructor( $dat ){

    if( $dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

    }else{
      $dat = {};
    }
        
    // cargo eventos de teclado
    document.onkeyup = $eve => {      
      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // menú contextual
        if( $dat.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $dat.men.classList.add(DIS_OCU);
        }
        // operadores
        else if( $dat.ope = document.querySelector(`nav.ope ~ div > section[class*="ide-"]:not(.${DIS_OCU})`) ){
          $dat.nav = $dat.ope.parentElement.previousElementSibling;
          if( $dat.ico = $dat.nav.querySelector(`.ico.fon-sel`) ) $dat.ico.click();
        }
        // pantallas
        else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $dat.art = $api_doc._win.children;          
          for( let $ide = $dat.art.length-1; $ide >= 0; $ide-- ){
            const $art = $dat.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              doc.win( $art.querySelector(`header:first-child .ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // navegacion
        else if( document.querySelector(`.doc_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          doc.pan();
        }
        break;
      }
    };
    // anulo formularios
    document.querySelectorAll('form').forEach( 
      $ele => ele.val_ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
    );
    
    // cargo operadores
    Object.keys(this).forEach( $ide => {
      if( typeof($ide) == 'string' ) this[$ide] = document.querySelector( this[$ide] );
    });
  }

  // pantalla modal : .doc_win > article > header + section
  static win( $ide, $ope ){
    let $ = {};    
    // botones
    if( $ide.nodeName && $ide.classList.contains('ico') ){
      // cierro pantalla
      $.art = $ide.parentElement.parentElement.parentElement;
      if( $ide.dataset.ope == 'fin' || $ide.dataset.ope == 'pre' ){        
        $.art.classList.add(DIS_OCU);
        // si es una pantalla por posicion, elimino el articulo
        if( $.art.dataset.pos ){
          if( $ide.dataset.ope == 'pre' ){
            $.art.parentElement.removeChild($.art);
          }else if( $ide.dataset.ope == 'fin' ){
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
        $.opc = $ope.opc ? lis.val($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $api_doc._win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $api_doc._win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .ope');
            $.ope.insertBefore( ele.val_cod( fig.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"doc.win(this)" 
            })), $.ope.querySelector('.ico.dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .ico:first-of-type`) ){
          $.ico.innerHTML = '';
          if( $ope.ico !== undefined ){
            if( typeof($ope.ico) == 'string' ) $ope.ico = fig.ico($ope.ico,{ class:'mar_hor-1' });
            ele.val_mod($ope.ico,$.ico);
          }
        }
        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){
          $.tit.innerHTML = ( $ope.cab !== undefined ) ? tex.let($ope.cab) : '';
        }
        // contenido
        ele.val_eli($.htm);
        if( $ope.htm !== undefined ) ele.val_agr($ope.htm,$.htm);
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
     // $api_doc._sec.style.gridColumn = "2/sp";
    }
    else if( !$api_doc._pan.classList.contains(DIS_OCU) ){
      $api_doc._pan.classList.add(DIS_OCU);
      //$api_doc._sec.style.gridColumn = "1/sp";
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
      lis.val_dec( $.lis.children ).forEach( $e => {
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
        ( $.bot = $.ite.querySelector('.ico.val_tog') ) 
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

      if( $api_doc._var = ele.val_ver($dat,{'eti':"form"}) ){

        $.lis = !!$api_doc._var.nextElementSibling ? $api_doc._var.nextElementSibling : $api_doc._var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .ico.val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  // variable : .atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $api_doc._var = ele.val_ver($dat,{'eti':'form'});
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
    lis.val_dec( ele.val_ver($dat,{'eti':'fieldset'}).children ).forEach( $e => $e != $dat && $e.classList.toggle(DIS_OCU) );
  }

  
}