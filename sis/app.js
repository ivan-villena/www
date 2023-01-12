'use strict';

class sis_app {

  rec = { uri : {} };
  
  constructor( $dat = {} ){

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

    let $ = {};

    /* 
      Eventos
    */    
    // - 1: teclado
    document.onkeyup = ( $eve ) => {

      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // 1- opciones
        if( $.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $.men.classList.add(DIS_OCU);
        }
        // 2- operador
        else if( $.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.${DIS_OCU})`) ){
          $.nav = $.ope.parentElement.previousElementSibling;
          if( $.ico = $.nav.querySelector(`.fig_ico.fon-sel`) ) $.ico.click();
        }
        // 3- pantalla
        else if( document.querySelector(`.app_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $.art = $dom.app.win.children;          
          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
            const $art = $.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              sis_app.win( $art.querySelector(`header:first-child .fig_ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // 4- paneles
        else if( document.querySelector(`.app_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          sis_app.pan();
        }
        break;
      }
    };
    // - 2: anulo formularios
    document.querySelectorAll('form').forEach( $ele => $dom.ope($ele,'eje_agr','submit',`evt => evt.preventDefault()`) );

    /* 
      Inicio
    */
    // por Articulo
    if( $.cab = this.rec.uri.cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = $dom.app.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 
        $.app_cab.click();
        if( $.art = this.rec.uri.art ){
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){
            $.app_art.parentElement.classList.add('fon-sel');
          }
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = $dom.app.pan.querySelector('.ide-app_nav ul.lis.nav') ) ){
            // inicializo enlace local
            api_lis.nav_tog($.art_nav);
            // muestro panel
            sis_app.pan('app_nav');
          }        
        }
      }  
    }// muestro menú
    else{
      // if( $.bot_ini = $dom.app.bot.querySelector('.fig_ico.ide-app_cab') ) $.bot_ini.click();
    }    
  }

  /* -- Modal -- */
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
    }
    // articulos
    else{
      $.art = typeof($ide) == 'string' ?  $dom.app.win.querySelector(`article.ide-${$ide}`) : $ide;
      $.htm = $.art.querySelector(`div:nth-child(2)`);
      // actualizo contenido
      if( !!$ope ){
        $.opc = $ope.opc ? api_lis.val_ite($ope.opc) : [];
        // creo nueva ventana con mismo identificador
        if( $.opc.includes('pos') ){
          $dom.app.win.appendChild( $.art = $.art.cloneNode(true) );
          $.art.dataset.pos = $dom.app.win.querySelectorAll(`article[data-pos]`).length + 1;
          // agrego icono : retroceder
          if( $.art.dataset.pos > 1 ){
            $.ope = $.art.querySelector('header:first-child > .app_ope');
            $.ope.insertBefore( api_ele.val_cod( api_fig.ico('val_mov-izq',{ 
              'title': "Volver a la pantalla anterior", 'data-ope':"pre", 'onclick':"sis_app.win(this)" 
            })), $.ope.querySelector('.fig_ico.ide-dat_fin') );
          }
          $.htm = $.art.querySelector(`div:nth-child(2)`);
        }
        // icono
        if( $.ico = $.art.querySelector(`header:first-child > .fig_ico:first-of-type`) ){
          $.ico.innerHTML = '';
          if( $ope.ico !== undefined ){
            if( typeof($ope.ico) == 'string' ) $ope.ico = api_fig.ico($ope.ico,{ class:'mar_hor-1' });
            $dom.mod($ope.ico,$.ico);
          }
        }
        // titulo
        if( $.tit = $.art.querySelector(`header:first-child > h2`) ){
          $.tit.innerHTML = ( $ope.cab !== undefined ) ? api_tex.let($ope.cab) : '';
        }
        // contenido
        $dom.eli($.htm);
        if( $ope.htm !== undefined ) $dom.agr($ope.htm,$.htm);
      }
      // muestro por valor
      $.art.classList.contains(DIS_OCU) && $.art.classList.remove(DIS_OCU);
      $.htm.scroll(0,0);// scroll
    }

    // pantalla de fondo
    if( $dom.app.win.querySelector(`article[class*="ide-"]:not(.${DIS_OCU})`) ){
      $dom.app.win.classList.contains(DIS_OCU) && $dom.app.win.classList.remove(DIS_OCU);
    }
    else if( !$dom.app.win.classList.contains(DIS_OCU) ){
      $dom.app.win.classList.add(DIS_OCU);
    }    
  }

  /* -- Panel -- */
  static pan( $ide ){

    let $ = { 'ide':"", 'cla': ['bor-sel','fon-sel'] };
    
    // oculto todo
    if( !$ide ){

      $dom.act('cla_agr',$dom.app.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]`),DIS_OCU);
    }
    // muestro uno articulo/navegador
    else{
      // Enlace
      $dom.act('cla_eli',$dom.app.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      if( typeof($ide) == 'string' ) $ide = $dom.app.bot.querySelector(`a[data-ide="${$ide}"]`);
      $dom.act('cla_agr',$ide,$.cla);
      
      // Contenedor
      $.ide = $ide.dataset.ide;
      $dom.act('cla_agr',$dom.app.pan.querySelectorAll(`:is(nav,article)[class*="ide-"]:not( .ide-${$.ide}, .${DIS_OCU} )`),DIS_OCU);
      $dom.act('cla_tog',$dom.app.pan.querySelectorAll(`:is(nav,article).ide-${$.ide}`),DIS_OCU);
    }

    // Panel
    if( $dom.app.pan.querySelector(`:is(nav,article)[class*="ide-"]:not(.${DIS_OCU})`) ){      
      $dom.act('cla_eli',$dom.app.pan,DIS_OCU);
    }
    else{
      $dom.act('cla_eli',$dom.app.bot.querySelectorAll(`a.bor-sel.fon-sel`),$.cla);
      $dom.act('cla_agr',$dom.app.pan,DIS_OCU);
    }    
  }

  /* -- Sección Principal -- */
  static sec( $ide ){
    $dom.app.sec.querySelectorAll(`article[class*="ide-"]:not(.${DIS_OCU})`).forEach( $e => $e.classList.add(DIS_OCU) );
    $dom.app.sec.querySelectorAll(`article.ide-${$ide}.${DIS_OCU}`).forEach( $e => $e.classList.remove(DIS_OCU) );
    $dom.app.sec.scroll(0, 0);
  }

  /* Navegacion de Contenido : pestaña-barra-operador */
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

  /* Variable : form > .app_var > label + (select,input,textarea,button)[name] */
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
  
  /* contenedor : bloque + visible/oculto */
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
              
        $.lis.querySelectorAll(`.app_val > .fig_ico.ide-val_tog${$.cla}`).forEach( $e => $e.click() );
      }
    }
  }

}