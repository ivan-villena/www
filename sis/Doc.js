// WINDOW
'use strict';

// Documento
class Doc { 

  Ope = {};

  Dat = {};

  constructor(){

    // Operadores globales del Documento
    this.Ope = {
      bot: 'body > .ope_cab',
      win: 'body > .ope_win',
      pan: 'body > .ope_pan',
      sec: 'body > .ope_sec',
      bar: 'body > .ope_bar',
      pie: 'body > .ope_pie',
      var: null
    }

    // Datos de Estructura: Operador + Listado + tablero
    this.Dat = {
      // operadores de valor
      ope: {
        // acumulados
        acu : [ "pos", "mar", "ver", "opc" ],
        // filtros
        ver : {
          dat : `form.ide-dat select[name="val"]`,
          fec : `form.ide-fec input[name="ini"]`,
          pos : `form.ide-pos input[name="ini"]`
        }    
      },
      // listado por tabla
      lis: {
        // Valores
        ope : `.ide-lis .dat_lis`,
        // Operador
        ope_acu : `.ide-lis .ide-ver .ide-acu`,      
        ope_sum : `.ide-lis .ide-ver .ide-sum`,
        ope_cue : `.ide-lis .ide-ver .ide-cue`,    
        // filtros
        ver : `.ide-lis .ide-ver`,
        // Descripciones
        des : `.ide-lis .ide-des`    
      },
      // tablero
      tab: {
        ide : null,
        dep : null,
        cla : null,
        // Valores
        ope : `main > article > .dat_tab`,
        // operador
        ope_acu : `.ope_pan > .ide-ope .ide-acu`,
        ope_sum : `.ope_pan > .ide-ope .ide-sum`,
        ope_cue : `.ope_pan > .ide-ope .ide-cue`,
        // Seleccion
        ver : `.ope_pan > .ide-ver`,
        // seccion + posicion
        sec : `.ope_pan > .ide-opc .ide-sec`,    
        pos : `.ope_pan > .ide-opc .ide-pos`,
        // ...opciones
        opc : `.ope_pan > .ide-opc .ide-opc`
      }
    }      

    /* Cargo Eventos */
    
    // - 1: teclado
    document.onkeyup = ( $eve ) => {
      
      let $ = {};
      
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
          if( $.ico = $.nav.querySelector(`.val_ico.fon-sel`) ) $.ico.click();
        }
        // 3- pantalla
        else if( document.querySelector(`.ope_win > :not(.${DIS_OCU})`) ){
          
          // oculto la ultima pantalla
          $.art = this.Ope.win.children;

          for( let $ide = $.art.length-1; $ide >= 0; $ide-- ){

            const $art = $.art[$ide];

            if( !$art.classList.contains(DIS_OCU) ){
              Doc_Ope.win( $art.querySelector(`header:first-child .val_ico[data-ope="fin"]`) );
              break;
            }
          }
        }
        // 4- paneles
        else if( document.querySelector(`.ope_pan > [class*="ide-"]:not(.${DIS_OCU})`) ){ 

          Doc_Ope.pan();
        }
        break;
      }
    };

    // - 2: anulo formularios
    document.querySelectorAll('form').forEach( $ele => Ele.act('eje_agr',$ele,'submit',`evt => evt.preventDefault()`) );

    /* Cargo Elementos */

    // Cargo Operadores del Documento
    for( const $atr in this.Ope ){

      this.Ope[$atr] = document.querySelector(this.Ope[$atr]);
    }
    
    // Cargo Formularios de Datos
    ['lis','tab'].forEach( $atr => {

      for( const $ide in this.Dat[$atr] ){ 

        if( typeof( this.Dat[$atr][$ide] ) == 'string' && !!this.Dat[$atr][$ide] ){

          this.Dat[$atr][$ide] = document.querySelector( this.Dat[$atr][$ide] ); 
        }
      }
    });

    // - Actualizo clase principal
    this.Dat.tab.cla = ".pos.ope";

  }

}