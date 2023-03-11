'use strict';

class sis_app {
  
  constructor( $dat = {} ){

    let $ = {};

    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }    

    /* Cargo Eventos */

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
        else if( document.querySelector(`.doc_win > :not(.${DIS_OCU})`) ){
          // oculto la ultima pantalla
          $.art = $sis_app.doc.win.children;          
          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){
            const $art = $.art[$ide];
            if( !$art.classList.contains(DIS_OCU) ){
              sis_doc.win( $art.querySelector(`header:first-child .fig_ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // 4- paneles
        else if( document.querySelector(`.doc_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 
          sis_doc.pan();
        }
        break;
      }
    };
    // - 2: anulo formularios
    document.querySelectorAll('form').forEach( $ele => api_ele.act('eje_agr',$ele,'submit',`evt => evt.preventDefault()`) );

    /* Inicio Aplicacion */
    
    // desde Articulo
    if( $.cab = this.rec.uri.cab ){
      
      // Menu: expando seleccionado
      if( $.app_cab = this.doc.pan.querySelector(`.ide-app_cab p.ide-${$.cab}`) ){ 

        $.app_cab.click();
        
        if( $.art = this.rec.uri.art ){
          
          // Pinto fondo si hay opcion seleccionada
          if( $.app_art = $.app_cab.parentElement.nextElementSibling.querySelector(`a[href$="/${$.art}"]`) ){
            $.app_art.parentElement.classList.add('fon-sel');
          }
          
          // Índice: hago click y muestro panel
          if( $.art && ( $.art_nav = this.doc.pan.querySelector('.ide-app_nav ul.lis.nav') ) ){
            // inicializo enlace local
            api_lis.nav_tog($.art_nav);
            // muestro panel
            sis_doc.pan('app_nav');
          }        
        }
      }  
    }
    // o muestro menú principal
    else{

      // if( $.bot_ini = $sis_app.doc.bot.querySelector('.fig_ico.ide-app_cab') ) $.bot_ini.click();
    }    

  }

  // Log de peticiones
  log = { php: [], jso: [] };

  // Recursos de la Aplicacion
  rec = { uri : {} };

  /* -- Estructura -- */
  dat = {};
  // Cargo estructura y valores
  static dat_est( $esq, $est, $ope, $dat ) {

    let $={}, $_ = $sis_app.dat[$esq][$est];

    // Estructura de la base
    if( !$_ ){
      // ...
    }

    // Propiedades
    if( $ope ){
      $.ope_atr = typeof($ope) == 'string' ? $ope.split('.') : $ope;
      $.ope_atr.forEach( $ide => {
        $_ = ( typeof($_) == 'object' && !!($_[$ide]) ) ? $_[$ide] : false;
      });

      if( $_ && $dat ){
        switch( $.ope_atr[0] ){
        case 'val':
          $_ = api_obj.val( api_dat.get($esq,$est,$dat), $_ );
          break;
        }
      }
    }

    return $_;
  }// identificador: esq.est[...atr]
  static dat_ide( $dat = '', $val = {} ){
    
    $val.ide = $dat.split('.');
    $val.esq = $val.ide[0];
    $val.est = $val.ide[1] ? $val.ide[1] : false;
    $val.atr = $val.ide[2] ? $val.ide[2] : false;

    return $val;
  }// busco dato relacional por atributo
  static dat_atr_dat( $esq, $est, $atr ){

    let $={}, $_ = false;
    
    if( $sis_app.dat[$esq][$est]?.atr[$atr] && ( $.dat_atr = $sis_app.dat[$esq][$est]?.atr[$atr].var.dat ) ){

      $.dat_atr = $.dat_atr.split('_');

      $_ = { 'esq': $.dat_atr.shift(), 'est': $.dat_atr.join('_') };
    }
    return $_;
  }

}