// window
'use strict';

class doc {

  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
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
      $.art = typeof($ide) == 'string' ?  $api_app.doc.win.querySelector(`article.ide-${$ide}`) : $ide;
      $.htm = $.art.querySelector(`div:nth-child(2)`);
      // actualizo contenido
      if( !!$ope ){
        $.opc = $ope.opc ? lis.val($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $api_app.doc.win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $api_app.doc.win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .ope');
            $.ope.insertBefore( ele.val_cod( fig.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"doc.win(this)" 
            })), $.ope.querySelector('.fig_ico.dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .fig_ico:first-of-type`) ){
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
    if( $api_app.doc.win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){
      $api_app.doc.win.classList.contains(DIS_OCU) && $api_app.doc.win.classList.remove(DIS_OCU);
    }
    else if( !$api_app.doc.win.classList.contains(DIS_OCU) ){
      $api_app.doc.win.classList.add(DIS_OCU);
    }    
  }

  // panel principal : aside.doc_pan
  static pan( $ide ){

    if( $ide && $ide.nodeName ) $ide = $ide.classList[0].split('-')[1];

    $api_app.doc.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$ide}, .${DIS_OCU} )`).forEach( $e => $e.classList.add(DIS_OCU) );
    $api_app.doc.pan.querySelectorAll(`:is(nav,article).ide-${$ide}`).forEach( $e => $e.classList.toggle(DIS_OCU) );

    // aculto-muestro contenedor
    if( $api_app.doc.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){
      $api_app.doc.pan.classList.contains(DIS_OCU) && $api_app.doc.pan.classList.remove(DIS_OCU);
     // $api_app.doc.sec.style.gridColumn = "2/sp";
    }
    else if( !$api_app.doc.pan.classList.contains(DIS_OCU) ){
      $api_app.doc.pan.classList.add(DIS_OCU);
      //$api_app.doc.sec.style.gridColumn = "1/sp";
    }    
  }

  // seccion central : main.doc_sec > article
  static sec( $ide ){
    $api_app.doc.sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $api_app.doc.sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $api_app.doc.sec.scroll(0, 0);
  }

  // indice por artículos
  static art( $dat, $cla = FON_SEL ){

    let $ = { lis : ele.val_ver($dat,{'eti':'nav'}) };

    if( $.lis ){
      // elimino marcas previas
      $.lis.querySelectorAll(
        `ul.lis.nav :is( li.pos.sep, li.pos:not(.sep) > div.val ).${$cla}`
      ).forEach( 
        $e => $e.classList.remove($cla) 
      );

      // controlo el toggle automatico por dependencias
      if( 
        ( $.dep = $dat.parentElement.parentElement.querySelector('ul.lis') ) 
        &&
        ( $dat.classList.contains('fig_ico') || $.dep.classList.contains(DIS_OCU) ) 
      ){
        doc.val($dat);
      }

      // pinto fondo
      if( !( $.bot = $dat.parentElement.querySelector('.fig_ico') ) || !$.bot.classList.contains('ocu') ){

        $dat.parentElement.classList.add($cla);
      }
    }
  }// - hago toogle por item
  static art_tog( $lis ){

    let $={};

    if( $.nav = $lis ? doc.art_mar($lis) : false ){
      // hago toogles ascendentes
      while( 
        ( $.lis = ele.val_ver($.nav,{'eti':'ul'}) ) 
        && 
        ( $.val = $.lis.previousElementSibling ) && $.val.nodeName == 'DIV' &&  $.val.classList.contains('val')
        && 
        ( $.nav = $.val.querySelector('a[href^="#"]') )
      ){
        if( $.lis.classList.contains(DIS_OCU) && ( $.ico = $.nav.previousElementSibling ) && $.ico.classList.contains('fig_ico') ){                
          doc.val($.ico);
        }                
      }
    }
  }// - marco valor seleccionado
  static art_mar( $lis ){

    let $nav, $val = location.href.split('#')[1];

    // hago toogle por item
    if( $val && ( $nav = $lis.querySelector(`a[href="#${$val}"]`) ) ){
        
      doc.art($nav);
    }

    return $nav;
  }// - ejecuto filtros
  static art_ver( $dat, $ope = 'a[href]' ){

    // ejecuto filtros
    lis.ite_ver($dat, $ope);

    // volver a marcar el fondo del elemento seleccionado
    doc.art_tog($api_app.var.nextElementSibling);

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
        ( $.bot = $.ite.querySelector('.fig_ico.val_tog') ) 
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

      if( $api_app.var = ele.val_ver($dat,{'eti':"form"}) ){

        $.lis = !!$api_app.var.nextElementSibling ? $api_app.var.nextElementSibling : $api_app.var.parentElement.parentElement;

        $.cla = ( $ope == 'tod' ) ? `.ocu` : `:not(.ocu)`;
              
        $.lis.querySelectorAll(`.val > .fig_ico.val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

  // variable : .atr > label + (select,input,textarea,button)[name]
  static var( $tip, $dat, $ope, ...$opc ){
    let $={};

    if( $tip && $tip.nodeName ){
      $dat = $tip;
      $api_app.var = ele.val_ver($dat,{'eti':'form'});
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