// Aplicacion
'use strict';

class app {
  
  // peticion
  uri = {    
  };
  // página
  doc = {
    bot : 'body > .doc_bot',
    win : 'body > .doc_win',
    pan : 'body > .doc_pan',
    sec : 'body > .doc_sec',
    bar : 'body > .doc_bar',    
    pie : 'body > .doc_pie'
  };
  // formulario
  var = {
  };
  // Valores
  val = {
    // acumulados
    acu : [ "pos", "mar", "ver", "opc" ],
    // filtros
    ver : {
      val : `form.ide-val select[name="val"]`,
      fec : `form.ide-fec input[name="ini"]`,
      pos : `form.ide-pos input[name="ini"]`
    }
  };
  // Estructura
  est = {
    lis : `article.ide-est .app_est`,
    // Valores
    val : {
      acu : `article.ide-est .ide-val .ide-acu`,      
      sum : `article.ide-est .ide-val .ide-sum`,
      cue : `article.ide-est .ide-val .ide-cue`
    },
    // filtros
    ver : `article.ide-est .ide-ver`,
    // Descripciones
    des : `article.ide-est .ide-des`
  };
  // Tablero
  tab = {
    lis : `main > article > .tab`,
    // Valores
    val : {
      acu : `aside.doc_pan > .ide-val .ide-acu`,      
      sum : `aside.doc_pan > .ide-val .ide-sum`,
      cue : `aside.doc_pan > .ide-val .ide-cue`
    },
    // Seleccion
    ver : `aside.doc_pan > .ide-ver`,
    // seccion + posicion
    sec : `aside.doc_pan > .ide-opc .ide-sec`,    
    pos : `aside.doc_pan > .ide-opc .ide-pos`,
    // ...atributos
    atr : `aside.doc_pan > .ide-opc .ide-atr`
  }

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
        else if( $dat.ope = document.querySelector(`nav.ope ~ div > section[class*="ide-"]:not(.${DIS_OCU})`) ){
          $dat.nav = $dat.ope.parentElement.previousElementSibling;
          if( $dat.ico = $dat.nav.querySelector(`.fig_ico.fon-sel`) ) $dat.ico.click();
        }
        // pantallas
        else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $dat.art = $api_app.doc.win.children;          
          for( let $ide = $dat.art.length-1; $ide >= 0; $ide-- ){
            const $art = $dat.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              doc.win( $art.querySelector(`header:first-child .fig_ico[data-ope="fin"]`) );
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
    
    // operador: cargo elementos
    ['doc','est','tab'].forEach( $ope => {
      // aseguro documento
      if( $ope == 'doc' || this.uri.cab == 'ope' ){
        for( const $ide in this[$ope] ){

          if( typeof(this[$ope][$ide]) == 'string' ){

            this[$ope][$ide] = document.querySelector(this[$ope][$ide]); 
          }
          else if( typeof(this[$ope][$ide]) == 'object' ){

            for( const $i in this[$ope][$ide] ){
              this[$ope][$ide][$i] = document.querySelector(this[$ope][$ide][$i]);
            }
          }
        }
      }
    });
  }

  // inicializo aplicacion : tablero + indices
  ini( $ = {} ){
    // inicio : muestro menu
    if( !$api_app.uri.cab ){

      ( $.bot_ini = $api_app.doc.bot.querySelector('.fig_ico.app_cab') ) && $.bot_ini.click();
    }
    // articulo
    else{
      // menu: expando seleccionado
      if( $.cab = $api_app.doc.pan.querySelector(`nav.ide-app_cab p.ide-${$api_app.uri.cab}`) ) 
        $.cab.click();

      // operadores
      if( $api_app.uri.cab == 'ope' ){
        if( $.cla_app = eval($.cla = `${$api_app.uri.esq}`) ){
          // inicializo: tablero + listado
          tab.lis_ini( $.cla_app );
          est.lis_ini();
        }
      }
      // indice por artículo
      else if( $api_app.uri.art ){
        if( $.art_nav = $api_app.doc.pan.querySelector('nav.ide-app_nav ul.lis.nav') ){
          // inicio indice
          app.art_tog($.art_nav);
          // muestro panel
          doc.pan('app_nav');
        }
      }
    }
  }
}
