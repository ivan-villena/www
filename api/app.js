// Aplicacion
'use strict';

class app {
  
  // peticion
  uri = {};

  // cargo elementos
  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }

    // evento-1: teclado
    document.onkeyup = $eve => {
      switch( $eve.keyCode ){
      // Escape => ocultar modales
      case 27: 
        // menú contextual
        if( $dat.men = document.querySelector(`ul.ope_opc:not(.${DIS_OCU})`) ){
          $dat.men.classList.add(DIS_OCU);
        }
        // operadores
        else if( $dat.ope = document.querySelector(`nav.ope ~ * > [class*="ide-"]:not(.${DIS_OCU})`) ){
          $dat.nav = $dat.ope.parentElement.previousElementSibling;
          if( $doc.ico = $dat.nav.querySelector(`.doc_ico.fon-sel`) ) $doc.ico.click();
        }
        // pantallas
        else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $dat.art = $api_doc._win.children;          
          for( let $ide = $dat.art.length-1; $ide >= 0; $ide-- ){
            const $art = $dat.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              doc.win( $art.querySelector(`header:first-child .doc_ico[data-ope="fin"]`) );
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

    // evento-2: anulo formularios
    document.querySelectorAll('form').forEach( 
      $ele => ele.val_ope( $ele,'eje_agr','submit',`evt => evt.preventDefault()`) 
    );
  }

  // inicializo aplicacion : tablero + indices
  ini( $ = {} ){
    // inicio : muestro menu
    if( !$api_app.uri.cab ){

      ( $.bot_ini = $api_doc._bot.querySelector('.doc_ico.app_cab') ) && $.bot_ini.click();
    }
    // articulo
    else{
      // menu: expando seleccionado
      if( $.cab = $api_doc._pan.querySelector(`.ide-app_cab p.ide-${$api_app.uri.cab}`) )  $.cab.click();

      // operadores
      if( $api_app.uri.cab == 'ope' ){
        if( $.cla_app = eval($.cla = `${$api_app.uri.esq}`) ){
          // inicializo: tablero + listado
          lis.tab_ini( $.cla_app );
          lis.est_ini();
        }
      }
      // indice por artículo
      else if( $api_app.uri.art ){
        if( $.art_nav = $api_doc._pan.querySelector('.ide-app_nav ul.lis.nav') ){
          // inicio indice
          lis.nav_tog($.art_nav);
          // muestro panel
          doc.pan('app_nav');
        }
      }
    }
  }
}
